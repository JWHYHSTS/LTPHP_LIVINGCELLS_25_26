<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemContextService
{
    public function build(): string
    {
        $parts = [];

        // 1) Mô tả hệ thống (cứng)
        $parts[] = $this->staticSystemDescription();

        // 2) Danh hiệu / tiêu chí (động)
        $parts[] = $this->awardsFromDb();

        // (Tuỳ chọn) Bạn có thể bơm thêm context khác (tài khoản/role/quy trình) sau này:
        // $parts[] = $this->rolesAndScreens();
        // $parts[] = $this->commonFaq();

        return implode("\n\n", array_filter($parts));
    }

    private function staticSystemDescription(): string
    {
        return <<<TXT
SYSTEM_OVERVIEW:
- Vai trò: Admin, SinhVien, CTCTHSSV, KhaoThi, DoanTruong.
- Chức năng chính:
  + Admin: quản lý tài khoản (thêm/sửa/xóa/import), phân quyền, trạng thái.
  + Sinh viên: xem thông tin cá nhân; xem danh sách sự kiện; xem chi tiết; đăng ký; xem “Đã đăng ký”.
  + CTCT-HSSV: quản lý danh sách sinh viên; quản lý điểm rèn luyện (import/export/sửa/xóa).
  + Khảo thí: quản lý điểm học tập (GPA) (import/export/sửa/xóa).
  + Đoàn trường: quản lý ngày tình nguyện; quản lý danh hiệu/tiêu chí; quản lý sự kiện; xem DS đăng ký; điểm danh.
- Khi người dùng hỏi “làm sao…”, trả lời theo dạng các bước thao tác trên giao diện.
TXT;
    }

    /**
     * Bơm dữ liệu danh hiệu từ DB.
     * - Tự nhận diện cột ID / Tên / điều kiện dựa trên tên cột thật trong DB
     * - Không làm crash chatbox nếu thiếu bảng/cột
     */
    private function awardsFromDb(): string
    {
        // Trong DB của bạn: bảng là bang_danhhieu (theo ảnh phpMyAdmin)
        $table = (string) config('ai.award_table', 'bang_danhhieu');
        $limit = (int) config('ai.award_context_limit', 30);

        try {
            if (!Schema::hasTable($table)) {
                return "AWARDS_DATA: (Unavailable – table '{$table}' does not exist.)";
            }

            $cols = Schema::getColumnListing($table);
            if (empty($cols)) {
                return "AWARDS_DATA: (Unavailable – cannot read columns from '{$table}'.)";
            }

            // ===== Heuristic nhận diện cột =====
            $idCol   = $this->pickFirstMatchingColumn($cols, [
                'ma', 'id', 'madanhhieu', 'madh', 'id_danhhieu', 'danhhieu_id'
            ]) ?? $cols[0];

            $nameCol = $this->pickFirstMatchingColumn($cols, [
                'ten', 'tendanhhieu', 'ten_danh_hieu', 'tieuchi', 'tentieuchi', 'name', 'title'
            ]);

            // Điều kiện: GPA/Điểm học tập
            $gpaCol = $this->pickFirstMatchingColumn($cols, [
                'diemhoctap', 'dieukienhoctap', 'gpa', 'dk_gpa', 'hoc_tap', 'diem_hoc_tap'
            ]);

            // Điều kiện: DRL/Điểm rèn luyện
            $drlCol = $this->pickFirstMatchingColumn($cols, [
                'diemrenluyen', 'dieukienrenluyen', 'drl', 'dk_drl', 'ren_luyen', 'diem_ren_luyen'
            ]);

            // Điều kiện: Ngày tình nguyện
            $volCol = $this->pickFirstMatchingColumn($cols, [
                'ngaytinhnguyen', 'dieukientinhnguyen', 'volunteer', 'songay', 'so_ngay', 'dk_ngaytn'
            ]);

            // Nếu không nhận diện được nameCol thì lấy cột string đầu tiên (trừ id)
            if (!$nameCol) {
                $nameCol = $this->pickFirstStringLikeColumn($table, $cols, $idCol) ?? $idCol;
            }

            // Build select động
            $select = [
                DB::raw("`{$idCol}` as id"),
                DB::raw("`{$nameCol}` as name"),
            ];
            if ($gpaCol) $select[] = DB::raw("`{$gpaCol}` as gpa_condition");
            if ($drlCol) $select[] = DB::raw("`{$drlCol}` as drl_condition");
            if ($volCol) $select[] = DB::raw("`{$volCol}` as vol_condition");

            $rows = DB::table($table)
                ->select($select)
                ->limit($limit)
                ->get();

            $lines = [];
            $lines[] = "AWARDS_DATA (table={$table}, up to {$limit} rows):";
            $lines[] = "COLUMNS_MAP: id={$idCol}; name={$nameCol}"
                . ($gpaCol ? "; GPA={$gpaCol}" : "")
                . ($drlCol ? "; DRL={$drlCol}" : "")
                . ($volCol ? "; VOL={$volCol}" : "");

            if ($rows->count() === 0) {
                $lines[] = "- (No awards found in database)";
                return implode("\n", $lines);
            }

            foreach ($rows as $r) {
                $gpa = property_exists($r, 'gpa_condition') ? $r->gpa_condition : null;
                $drl = property_exists($r, 'drl_condition') ? $r->drl_condition : null;
                $vol = property_exists($r, 'vol_condition') ? $r->vol_condition : null;

                $lines[] =
                    "- [#{$r->id}] {$r->name}"
                    . " | GPA: " . ($gpa ?? 'N/A')
                    . " | DRL: " . ($drl ?? 'N/A')
                    . " | VolunteerDays: " . ($vol ?? 'N/A');
            }

            return implode("\n", $lines);

        } catch (\Throwable $e) {
            // Không crash chatbox
            return "AWARDS_DATA: (Unavailable – {$e->getMessage()})";
        }
    }

    /**
     * Pick column by heuristics (case-insensitive, ignore underscores).
     */
    private function pickFirstMatchingColumn(array $cols, array $needles): ?string
    {
        $normCols = [];
        foreach ($cols as $c) {
            $normCols[$c] = $this->normalize($c);
        }

        foreach ($needles as $n) {
            $n2 = $this->normalize($n);
            foreach ($normCols as $orig => $nc) {
                if ($nc === $n2) return $orig;
            }
        }

        foreach ($needles as $n) {
            $n2 = $this->normalize($n);
            foreach ($normCols as $orig => $nc) {
                if (str_contains($nc, $n2)) return $orig;
            }
        }

        return null;
    }

    /**
     * Nếu không tìm được cột tên theo keyword, lấy cột kiểu text/varchar đầu tiên (trừ idCol).
     */
    private function pickFirstStringLikeColumn(string $table, array $cols, string $idCol): ?string
    {
        try {
            $colTypes = [];
            // SHOW COLUMNS trả Field/Type/Null/Key/Default/Extra
            $rows = DB::select("SHOW COLUMNS FROM `{$table}`");
            foreach ($rows as $r) {
                $field = $r->Field ?? null;
                $type  = $r->Type ?? '';
                if ($field) $colTypes[$field] = strtolower((string)$type);
            }

            foreach ($cols as $c) {
                if ($c === $idCol) continue;
                $t = $colTypes[$c] ?? '';
                if (str_contains($t, 'varchar') || str_contains($t, 'text') || str_contains($t, 'char')) {
                    return $c;
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return null;
    }

    private function normalize(string $s): string
    {
        $s = mb_strtolower($s);
        $s = str_replace(['_', '-', ' '], '', $s);
        return $s;
    }
}

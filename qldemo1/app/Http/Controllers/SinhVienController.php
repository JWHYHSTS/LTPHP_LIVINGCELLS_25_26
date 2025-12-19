<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\SinhVien;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Models\SuKien;
use App\Models\SuKienAnh;
use App\Models\DangKySuKien;

class SinhVienController extends Controller
{
    public function index(Request $r)
    {
        $matk = session('auth.MaTK') ?? session('user.MaTK');

        if (!$matk) {
            return view('sinhvien.index', [
                'sv'       => null,
                'gpaVal'   => null,
                'drlVal'   => null,
                'ngaySinh' => null,
                'ntnTong'  => 0,
                'awds'     => collect(),
                'goiY'     => null,
                'ntnItems'      => collect(),
                'awardProgress' => collect(),
            ]);
        }

        $sv = DB::table('BANG_SinhVien')->where('MaTK', $matk)->first();
        if (!$sv) {
            return view('sinhvien.index', [
                'sv'       => null,
                'gpaVal'   => null,
                'drlVal'   => null,
                'ngaySinh' => null,
                'ntnTong'  => 0,
                'awds'     => collect(),
                'goiY'     => null,
                'ntnItems'      => collect(),
                'awardProgress' => collect(),
            ]);
        }

        $masv = $sv->MaSV;

        $gpa = DB::table('BANG_DiemHocTap')
            ->where('MaSV', $masv)
            ->orderByDesc('NamHoc')
            ->orderByDesc('HocKy')
            ->first();
        $gpaVal = $gpa->DiemHe4 ?? null;

        $drl = DB::table('BANG_DiemRenLuyen')
            ->where('MaSV', $masv)
            ->orderByDesc('NamHoc')
            ->orderByDesc('HocKy')
            ->first();
        $drlVal = $drl->DiemRL ?? null;

        // Tổng số ngày tình nguyện đã duyệt (đã dùng đúng cột SoNgayTN & TrangThaiDuyet)
        $ntnTong = DB::table('BANG_NgayTinhNguyen')
            ->where('MaSV', $masv)
            ->where('TrangThaiDuyet', 'DaDuyet')
            ->selectRaw('COALESCE(SUM(SoNgayTN), 0) AS tong')
            ->value('tong') ?? 0;

        // Định dạng ngày sinh cho view (tùy có/không)
        $ngaySinh = null;
        if (!empty($sv->NgaySinh)) {
            try {
                $ngaySinh = Carbon::parse($sv->NgaySinh)->format('d/m/Y');
            } catch (\Throwable $e) {
                $ngaySinh = $sv->NgaySinh;
            }
        }

        // Khen thưởng đã nhận
        // TÍNH DANH HIỆU GIỐNG TRANG ĐOÀN TRƯỜNG (không cần AwardRules)
        $labels = $this->DanhHieuDatDuoc($masv);

        // Chuẩn hoá cho Blade: collection object có field Ten
        $awds = collect($labels)->map(fn($ten) => (object)[
            'Ten'   => $ten,
            'HocKy' => 'HK1'
        ]);

        // (Tuỳ chọn) gợi ý danh hiệu, nếu có logic thì set, không thì null
        $goiY = null;

        // 8) GỢI Ý DANH HIỆU — chỉ dựa trên NGÀY TÌNH NGUYỆN còn thiếu (1–3 ngày)
        //    Điều kiện: GPA và DRL đã đạt, NTN chưa đạt nhưng thiếu <= 3 ngày.
        $danhhieu = DB::table('BANG_DanhHieu')
            ->select('TenDH', 'DieuKienGPA', 'DieuKienDRL', 'DieuKienNTN')
            ->get();
        $goiY = [];
        foreach ($danhhieu as $dh) {
            $reqGpa = (float)($dh->DieuKienGPA ?? 0);
            $reqDrl = (int)($dh->DieuKienDRL ?? 0);
            $reqNtn = (int)($dh->DieuKienNTN ?? 0);

            $okGpa = (float)($gpaVal ?? 0) >= $reqGpa;
            $okDrl = (int)($drlVal ?? 0)  >= $reqDrl;

            if (!$okGpa || !$okDrl) {
                // Chưa đủ GPA/DRL thì không gợi ý
                continue;
            }

            $thieu = $reqNtn - (int)$ntnTong;
            if ($thieu > 0 && $thieu <= 3) {
                $goiY[] = "Bạn còn thiếu {$thieu} ngày tình nguyện để đạt danh hiệu {$dh->TenDH}.";
            }
        }

        $danhhieu = DB::table('BANG_DanhHieu')
            ->select('TenDH', 'DieuKienNTN')
            ->orderBy('TenDH')
            ->get();

        $awardProgress = $danhhieu->map(function ($dh) use ($ntnTong) {
            $req = (int)($dh->DieuKienNTN ?? 0);
            // tránh chia cho 0: nếu không quy định NTN, coi như đạt 100
            if ($req <= 0) {
                return (object)[
                    'ten'  => $dh->TenDH,
                    'req'  => 0,
                    'cur'  => (int)$ntnTong,
                    'pct'  => 100,
                ];
            }
            $pct = min(100, (int) round($ntnTong * 100 / $req));
            return (object)[
                'ten'  => $dh->TenDH,
                'req'  => $req,
                'cur'  => (int)$ntnTong,
                'pct'  => $pct,
            ];
        });

        return view('sinhvien.index', [
            'sv'       => $sv,
            'gpaVal'   => $gpaVal,
            'drlVal'   => $drlVal,
            'ngaySinh' => $ngaySinh,
            'ntnTong'  => (int)($ntnTong ?? 0),
            'ntnItems' => $ntnItems ?? collect(),
            'awds'     => $awds ?? collect(),
            'goiY'     => $goiY,
            'awardProgress' => $awardProgress,
        ]);
    }
    /**
     * Hàm tính danh hiệu đạt được của sinh viên
     */
    private function DanhHieuDatDuoc(string $maSV): array
    {
        // Lấy các chỉ số hiện tại của SV
        $gpa = DB::table('BANG_DiemHocTap')
            ->where('MaSV', $maSV)
            ->select(DB::raw('MAX(DiemHe4) as DiemHe4'))
            ->value('DiemHe4') ?? 0;

        $drl = DB::table('BANG_DiemRenLuyen')
            ->where('MaSV', $maSV)
            ->select(DB::raw('MAX(DiemRL) as DiemRL'))
            ->value('DiemRL') ?? 0;

        $ntn = DB::table('BANG_NgayTinhNguyen')
            ->where('MaSV', $maSV)
            ->where('TrangThaiDuyet', 'DaDuyet')
            ->select(DB::raw('SUM(SoNgayTN) as SoNgayTN'))
            ->value('SoNgayTN') ?? 0;

        // Lấy danh sách danh hiệu và điều kiện
        $danhhieu = DB::table('BANG_DanhHieu')->get();

        // Tính các danh hiệu thoả điều kiện
        $labels = [];
        foreach ($danhhieu as $d) {
            $okGPA = $gpa >= ($d->DieuKienGPA ?? 0);
            $okDRL = $drl >= ($d->DieuKienDRL ?? 0);
            $okNTN = $ntn >= ($d->DieuKienNTN ?? 0);

            if ($okGPA && $okDRL && $okNTN) {
                $labels[] = $d->TenDH;
            }
        }
        return $labels;
    }
    // Phương thức đổi mật khẩu
    public function changePassword(Request $r)
    {
        // Lấy thông tin tài khoản từ session
        $matk = session('auth.MaTK') ?? session('user.MaTK');
        if (!$matk) {
            return redirect()->route('login')->withErrors('Bạn cần đăng nhập.');
        }

        // Validate dữ liệu từ form (mật khẩu cũ và mật khẩu mới)
        $r->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'], // yêu cầu mật khẩu mới phải có ít nhất 6 ký tự và phải trùng khớp
        ], [
            'current_password.required' => 'Mật khẩu cũ là bắt buộc',
            'new_password.required' => 'Mật khẩu mới là bắt buộc',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Mật khẩu mới không khớp',
        ]);

        // Kiểm tra tài khoản trong bảng BANG_TaiKhoan
        $acc = DB::table('BANG_TaiKhoan')->where('MaTK', $matk)->first();
        if (!$acc) {
            return back()->withErrors('Không tìm thấy tài khoản.');
        }

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($r->input('current_password'), $acc->MatKhau)) {
            return back()->withErrors('Mật khẩu hiện tại không đúng.');
        }

        // Băm mật khẩu mới
        $newPasswordHash = Hash::make($r->input('new_password'));

        // Cập nhật mật khẩu mới vào bảng BANG_TaiKhoan
        DB::table('BANG_TaiKhoan')
            ->where('MaTK', $matk)
            ->update(['MatKhau' => $newPasswordHash]);
        return back()->with('ok', 'Đổi mật khẩu thành công!');
    }
    public function suKienIndex()
    {
        $events = SuKien::where('TrangThai', 'Open')
            ->orderByDesc('MaSK')
            ->get();

        // map ảnh bìa = ảnh ThuTu nhỏ nhất
        $images = SuKienAnh::orderBy('ThuTu')->get()->groupBy('MaSK');
        $coverMap = [];
        foreach ($images as $maSK => $arr) {
            $coverMap[$maSK] = $arr->first()->DuongDan;
        }

        return view('sinhvien.sukien.index', compact('events', 'coverMap'));
    }

    public function suKienDangKy(Request $request)
    {

        $data = $request->validate([
            'MaSK' => 'required|integer',
        ]);

        $u = session('user');

        // 1) ƯU TIÊN: nếu session đã có MaSV (MSSV thật) thì dùng luôn
        $maSV = $u['MaSV'] ?? null;

        // 2) NẾU session chỉ có MaTK (số) -> map sang MaSV trong bảng bang_sinhvien
        if (!$maSV) {
            $maTK = $u['MaTK'] ?? null;
            if ($maTK) {
                $maSV = DB::table('bang_sinhvien')->where('MaTK', $maTK)->value('MaSV');
            }
        }

        if (!$maSV) {
            return back()->with('error', 'Không xác định được MSSV (MaSV) để đăng ký.');
        }

        // 3) Check SV tồn tại (tránh lỗi FK)
        $existsSV = DB::table('bang_sinhvien')->where('MaSV', $maSV)->exists();
        if (!$existsSV) {
            return back()->with('error', "MSSV ($maSV) không tồn tại trong hệ thống.");
        }

        // 4) Tránh đăng ký trùng
        $exists = DB::table('bang_dangkysukien')
            ->where('MaSK', $data['MaSK'])
            ->where('MaSV', $maSV)
            ->exists();

        if (!$exists) {
            DB::table('bang_dangkysukien')->insert([
                'MaSK' => $data['MaSK'],
                'MaSV' => $maSV,                 // MSSV dạng 49.01.103.001
                'DangKyLuc' => now(),
                'TrangThaiDangKy' => 'Registered',
                'DaDiemDanh' => 0,
                'DiemDanhLuc' => null,
                'GhiChu' => null,
            ]);
        }

        return back()->with('success', 'Đã đăng ký sự kiện.');
        // A) Lấy sự kiện
        $sk = DB::table('bang_sukien')->where('MaSK', $data['MaSK'])->first();
        if (!$sk) return back()->with('error', 'Sự kiện không tồn tại.');

        // B) Chỉ cho đăng ký khi Open
        if (($sk->TrangThai ?? '') !== 'Open') {
            return back()->with('error', 'Sự kiện chưa mở hoặc đã đóng.');
        }

        // C) Không cho đăng ký nếu đã kết thúc
        if (Carbon::parse($sk->ThoiGianKetThuc)->lt(now())) {
            return back()->with('error', 'Sự kiện đã kết thúc.');
        }

        // D) Không cho đăng ký trùng (bạn đã có, nhưng nên check Registered)
        $exists = DB::table('bang_dangkysukien')
            ->where('MaSK', $data['MaSK'])
            ->where('MaSV', $maSV)
            ->where('TrangThaiDangKy', 'Registered')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã đăng ký sự kiện này rồi.');
        }

        // E) Chặn full slot
        if (!empty($sk->SoLuongToiDa)) {
            $cnt = DB::table('bang_dangkysukien')
                ->where('MaSK', $data['MaSK'])
                ->where('TrangThaiDangKy', 'Registered')
                ->count();

            if ($cnt >= (int)$sk->SoLuongToiDa) {
                return back()->with('error', 'Sự kiện đã đủ số lượng.');
            }
        }
    }

    public function suKienDaDangKy()
    {
        $u = session('user');

        $maSV = $u['MaSV'] ?? null;

        // Nếu session không có MaSV thì map từ MaTK -> MaSV
        if (!$maSV && isset($u['MaTK'])) {
            $maSV = DB::table('bang_sinhvien')
                ->where('MaTK', $u['MaTK'])
                ->value('MaSV');
        }

        $rows = collect();

        if ($maSV) {
            $rows = DB::table('bang_dangkysukien as dk')
                ->join('bang_sukien as sk', 'sk.MaSK', '=', 'dk.MaSK')
                ->where('dk.MaSV', $maSV)
                ->select(
                    'sk.MaSK',
                    'sk.TieuDe',
                    'sk.ThoiGianBatDau',
                    'sk.ThoiGianKetThuc',
                    'sk.DiaDiem',
                    'dk.DangKyLuc',
                    'dk.TrangThaiDangKy',
                    'dk.DaDiemDanh',
                    'dk.DiemDanhLuc'
                )
                ->orderByDesc('dk.DangKyLuc')
                ->get();
        }

        return view('sinhvien.sukien.dadangky', compact('rows'));
    }
    public function suKienTinhNguyenIndex()
    {
        $u = session('user');
        $maSV = $u['MaSV'] ?? null;

        if (!$maSV && isset($u['MaTK'])) {
            $maSV = DB::table('bang_sinhvien')->where('MaTK', $u['MaTK'])->value('MaSV');
        }

        if (!$maSV) {
            return redirect()->back()->with('error', 'Không xác định được sinh viên.');
        }

        $now = now();

        // Map sự kiện mà SV đã đăng ký (MaSK => true)
        $registeredMap = DB::table('bang_dangkysukien')
            ->where('MaSV', $maSV)
            ->where('TrangThaiDangKy', 'Registered')
            ->pluck('MaSK')
            ->flip();

        // Đếm số đăng ký theo sự kiện (để chặn full slot)
        $countMap = DB::table('bang_dangkysukien')
            ->select('MaSK', DB::raw('COUNT(*) as cnt'))
            ->where('TrangThaiDangKy', 'Registered')
            ->groupBy('MaSK')
            ->pluck('cnt', 'MaSK');

        // Lấy danh sách sự kiện (chỉ Open)
        $events = DB::table('bang_sukien')
            ->where('TrangThai', 'Open')
            ->orderByDesc('MaSK')
            ->get()
            ->map(function ($e) use ($registeredMap, $countMap, $now) {
                $e->is_registered = isset($registeredMap[$e->MaSK]);

                $end = Carbon::parse($e->ThoiGianKetThuc);
                $e->is_expired = $end->lt($now);

                $e->reg_count = (int)($countMap[$e->MaSK] ?? 0);
                $e->is_full = !empty($e->SoLuongToiDa) && $e->reg_count >= (int)$e->SoLuongToiDa;

                $e->can_register = (!$e->is_registered)
                    && (!$e->is_expired)
                    && (!$e->is_full);

                return $e;
            });

        // Map ảnh bìa: ảnh ThuTu nhỏ nhất của mỗi MaSK
        $coverMap = DB::table('bang_sukien_anh')
            ->select('MaSK', DB::raw('MIN(ThuTu) as min_thutu'))
            ->groupBy('MaSK')
            ->get()
            ->mapWithKeys(function ($x) {
                return [$x->MaSK => $x->min_thutu];
            });

        $coverPaths = DB::table('bang_sukien_anh')
            ->whereIn('MaSK', $coverMap->keys())
            ->orderBy('ThuTu')
            ->get();

        $finalCoverMap = [];
        foreach ($coverPaths as $img) {
            if (!isset($finalCoverMap[$img->MaSK])) {
                $finalCoverMap[$img->MaSK] = $img->DuongDan;
            }
        }

        return view('sinhvien.sukien.index', [
            'events'   => $events,
            'coverMap' => $finalCoverMap,
            'maSV'     => $maSV,
        ]);
    }
}

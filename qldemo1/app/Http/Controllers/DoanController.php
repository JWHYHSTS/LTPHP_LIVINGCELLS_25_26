<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KhenThuongExport;
use App\Models\NgayTinhNguyen;
use App\Models\DanhHieu;
use App\Models\SinhVien;
use App\Models\DiemHocTap;
use App\Models\DiemRenLuyen;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Imports\NtnImport;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\SuKien;
use App\Models\SuKienAnh;
use App\Models\DangKySuKien;

class DoanController extends Controller
{
    /* ======================== Khen thưởng danh hiệu ======================== */
    public function khenThuongIndex(Request $r)
    {
        $hk = $r->input('hk', 'HK1-2024-2025');
        $q  = trim((string) $r->input('q', ''));

        // 1) Lọc SV trước
        $sinhvien = SinhVien::query()
            ->select('MaSV', 'HoTen')
            ->search($q)
            ->orderBy('MaSV')
            ->get();

        $listMaSV = $sinhvien->pluck('MaSV')->all();

        // 2) Lấy GPA/DRL/NTN (Eloquent)
        $gpa = DiemHocTap::maxGpaByStudent($listMaSV);          // [MaSV => GPA]
        $drl = DiemRenLuyen::maxDrlByStudent($listMaSV);         // [MaSV => DRL]
        $ntn = NgayTinhNguyen::sumApprovedByStudent($listMaSV);  // [MaSV => SoNgayTN]

        // 3) Điều kiện danh hiệu
        $danhhieu = DanhHieu::query()->get();

        // 4) Tính danh hiệu đạt – CHỈ PUSH NẾU CÓ ÍT NHẤT 1 DANH HIỆU
        $rows = [];
        foreach ($sinhvien as $sv) {
            $ma = $sv->MaSV;
            $labels = [];

            foreach ($danhhieu as $d) {
                $okGPA = ($gpa[$ma] ?? 0) >= (float)($d->DieuKienGPA ?? 0);
                $okDRL = ($drl[$ma] ?? 0) >= (int)($d->DieuKienDRL ?? 0);
                $okNTN = ($ntn[$ma] ?? 0) >= (int)($d->DieuKienNTN ?? 0);
                if ($okGPA && $okDRL && $okNTN) {
                    $labels[] = $d->TenDH;
                }
            }

            // KHÔNG ĐẠT BẤT KỲ DANH HIỆU NÀO → BỎ QUA
            if (empty($labels)) {
                continue;
            }

            $rows[] = (object)[
                'MaSV'     => $sv->MaSV,
                'HoTen'    => $sv->HoTen,
                'DanhHieu' => implode(', ', $labels),   // luôn có ít nhất 1
            ];
        }

        // 5) Nếu q có chứa tên danh hiệu → lọc thêm trong $rows (toàn là SV có danh hiệu)
        if ($q !== '') {
            $qLower = mb_strtolower($q, 'UTF-8');
            $rows = array_values(array_filter($rows, function ($row) use ($qLower) {
                return mb_stripos($row->MaSV, $qLower, 0, 'UTF-8') !== false
                    || mb_stripos($row->HoTen, $qLower, 0, 'UTF-8') !== false
                    || mb_stripos($row->DanhHieu, $qLower, 0, 'UTF-8') !== false;
            }));
        }

        // 6) Phân trang Collection
        $data  = collect($rows);
        $page  = max(1, (int)$r->input('page', 1));
        $per   = 10;
        $total = $data->count();
        $items = $data->slice(($page - 1) * $per, $per)->values();

        $data = new LengthAwarePaginator(
            $items,
            $total,
            $per,
            $page,
            ['path' => $r->url(), 'query' => $r->query()]
        );

        return view('doan.khenthuong', compact('data', 'hk', 'q'));
    }
    public function exportExcel(Request $r)
    {
        $hk = $r->input('hk', 'HK1-2024-2025');
        $fileName = "Bao_cao_KhenThuong_{$hk}.xlsx";
        return Excel::download(new KhenThuongExport($hk), $fileName);
    }
    /* ======================== Đổi mật khẩu Đoàn Trường ======================== */
    public function changePassword(Request $request)
    {
        // 1. Validate
        $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed', 'different:old_password'],
        ], [
            'old_password.required'      => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required'      => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min'           => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed'     => 'Xác nhận mật khẩu không khớp.',
            'new_password.different'     => 'Mật khẩu mới phải khác mật khẩu cũ.',
        ]);

        // 2. Lấy user từ session (mảng)
        $user = session('user');
        if (!$user || empty($user['MaTK'])) {
            return back()->withErrors([
                'old_password' => 'Phiên đăng nhập đã hết hạn, vui lòng đăng nhập lại.',
            ]);
        }

        // 3. Lấy tài khoản tương ứng
        $tk = TaiKhoan::findOrFail($user['MaTK']);
        // 4. Kiểm tra mật khẩu cũ
        if (!Hash::check($request->old_password, $tk->MatKhau)) {
            return back()->withErrors(['old_password' => 'Mật khẩu cũ không đúng.']);
        }

        // 5. Cập nhật mật khẩu mới
        $tk->MatKhau = $request->new_password;
        $tk->save();

        return back()->with('ok', 'Đã đổi mật khẩu thành công.');
    }

    /* ======================== Ngày tình nguyện ======================== */
    public function tinhNguyenIndex(Request $r)
    {
        $q = trim((string) $r->input('q', ''));

        // dùng scopeSearch (join SV, select thêm HoTen)
        $query = NgayTinhNguyen::query()->search($q);

        // Sắp MSSV theo số (bỏ '.')
        $table = (new NgayTinhNguyen)->getTable();
        $query->orderByRaw('LPAD(REPLACE(' . $table . '.MaSV, ".", ""), 20, "0")');

        $data = $query->paginate(10)->withQueryString();

        // danh sách SV cho modal Thêm
        $dsSV = \App\Models\SinhVien::query()
            ->orderByRaw('LPAD(REPLACE(MaSV, ".", ""), 20, "0")')
            ->select('MaSV', 'HoTen')
            ->get();

        return view('doan.tinhnguyen', compact('data', 'q', 'dsSV'));
    }

    public function ntnStore(Request $r)
    {
        $r->validate([
            'MaSV'           => 'required|string|max:20|exists:BANG_SinhVien,MaSV',
            'TenHoatDong'    => 'required|string|max:200',
            'NgayThamGia'    => 'required|date',
            'SoNgayTN'       => 'required|integer|min:1',
            'TrangThaiDuyet' => 'required|in:ChuaDuyet,DaDuyet,TuChoi',
        ], [], ['MaSV' => 'MSSV']);

        NgayTinhNguyen::create([
            'MaSV'           => $r->MaSV,
            'TenHoatDong'    => $r->TenHoatDong,
            'NgayThamGia'    => $r->NgayThamGia,
            'SoNgayTN'       => $r->SoNgayTN,
            'TrangThaiDuyet' => $r->TrangThaiDuyet,
        ]);

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Đã thêm hoạt động tình nguyện.');
    }

    public function ntnUpdate(Request $r)
    {
        $r->validate([
            'MaNTN'          => 'required|integer|exists:bang_ngaytinhnguyen,MaNTN',
            'MaSV'           => 'required|string|max:20|exists:BANG_SinhVien,MaSV',
            'TenHoatDong'    => 'required|string|max:200',
            'NgayThamGia'    => 'required|date',
            'SoNgayTN'       => 'required|integer|min:1',
            'TrangThaiDuyet' => 'required|in:ChuaDuyet,DaDuyet,TuChoi',
        ]);

        $ntn = NgayTinhNguyen::findOrFail($r->MaNTN);
        $ntn->update([
            'MaSV'           => $r->MaSV,
            'TenHoatDong'    => $r->TenHoatDong,
            'NgayThamGia'    => $r->NgayThamGia,
            'SoNgayTN'       => $r->SoNgayTN,
            'TrangThaiDuyet' => $r->TrangThaiDuyet,
        ]);

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Đã cập nhật hoạt động.');
    }

    public function ntnDelete(Request $r)
    {
        $r->validate([
            'MaNTN' => 'required|integer|exists:bang_ngaytinhnguyen,MaNTN',
        ]);

        NgayTinhNguyen::destroy($r->MaNTN);

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Đã xoá hoạt động.');
    }

    // ========== Import Excel (.xlsx/.xls/.csv) ==========
    public function ntnImport(Request $r)
    {
        $r->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new NtnImport();
            Excel::import($import, $r->file('file'));

            $inserted = $import->insertedCount();
            $fails    = $import->failures();

            if ($fails->isNotEmpty()) {
                $errs = [];
                /** @var \Maatwebsite\Excel\Validators\Failure $f */
                foreach ($fails as $f) {
                    $errs[] = "Dòng {$f->row()}: " . implode(', ', $f->errors());
                }
                return back()
                    ->with('ok', "Đã nhập: {$inserted} dòng. Bỏ qua: {$fails->count()} dòng.")
                    ->withErrors($errs);
            }

            if ($inserted === 0) {
                return back()->withErrors(
                    'Không có dòng nào được nhập. Hãy kiểm tra lại tiêu đề cột: masv, tenhoatdong, ngaythamgia, songaytn, trangthaiduyet.'
                );
            }

            return redirect()
                ->route('doan.tinhnguyen.index')
                ->with('ok', "Nhập danh sách hoạt động TN thành công. Thêm mới: {$inserted} dòng.");
        } catch (QueryException $e) {
            return back()->withErrors('Import lỗi DB: ' . $e->getMessage());
        } catch (\Throwable $e) {
            return back()->withErrors('Import lỗi: ' . $e->getMessage());
        }
    }
    public function ntnTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header đúng theo cột bạn yêu cầu
        $headers = [
            'A1' => 'masv',
            'B1' => 'tenhoatdong',
            'C1' => 'ngaythamgia',
            'D1' => 'songaytn',
            'E1' => 'trangthaiduyet',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // In đậm + chỉnh rộng cột
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(16);
        $sheet->getColumnDimension('B')->setWidth(32);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(20);

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="mau_ngay_tinh_nguyen.xlsx"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
    /* ======================== Danh hiệu ======================== */
    public function danhHieuIndex(Request $r)
    {
        $hk = (int) $r->input('hk', 1);
        $nh = (string) $r->input('nh', '2024-2025');
        $q  = trim((string) $r->input('q', ''));

        $data = DanhHieu::query()
            ->search($q)
            ->select('MaDH', 'TenDH', 'DieuKienGPA', 'DieuKienDRL', 'DieuKienNTN')
            ->orderBy('MaDH')
            ->paginate(10)
            ->withQueryString();

        return view('doan.danhhieu', compact('data', 'hk', 'nh', 'q'));
    }

    public function dhStore(Request $r)
    {
        $ten = (string) \Illuminate\Support\Str::of($r->TenDH)->trim()->replaceMatches('/\s+/u', ' ');
        $r->merge(['TenDH' => $ten]);

        $r->validate([
            'TenDH'       => 'required|string|max:100|unique:bang_danhhieu,TenDH',
            'DieuKienGPA' => 'required|numeric|min:0|max:4',
            'DieuKienDRL' => 'required|integer|min:0|max:100',
            'DieuKienNTN' => 'required|integer|min:0',
        ]);

        DanhHieu::create([
            'TenDH'       => $r->TenDH,
            'DieuKienGPA' => $r->DieuKienGPA,
            'DieuKienDRL' => $r->DieuKienDRL,
            'DieuKienNTN' => $r->DieuKienNTN,
        ]);

        return redirect()->route('doan.danhhieu.index')->with('ok', 'Đã thêm danh hiệu.');
    }

    public function dhUpdate(Request $r)
    {
        $ten = (string) \Illuminate\Support\Str::of($r->TenDH)->trim()->replaceMatches('/\s+/u', ' ');
        $r->merge(['TenDH' => $ten]);

        $r->validate([
            'MaDH'        => 'required|integer|exists:bang_danhhieu,MaDH',
            'TenDH'       => ['required', 'string', 'max:100', Rule::unique('bang_danhhieu', 'TenDH')->ignore($r->MaDH, 'MaDH')],
            'DieuKienGPA' => 'nullable|numeric|min:0|max:4',
            'DieuKienDRL' => 'nullable|integer|min:0|max:100',
            'DieuKienNTN' => 'nullable|integer|min:0',
        ], [
            'TenDH.unique' => 'Tên danh hiệu đã tồn tại.',
        ], [
            'TenDH' => 'Tên danh hiệu',
        ]);

        $dh = DanhHieu::findOrFail($r->MaDH);
        $dh->update([
            'TenDH'       => $r->TenDH,
            'DieuKienGPA' => $r->DieuKienGPA,
            'DieuKienDRL' => $r->DieuKienDRL,
            'DieuKienNTN' => $r->DieuKienNTN,
        ]);

        return redirect()->route('doan.danhhieu.index')->with('ok', 'Đã cập nhật danh hiệu.');
    }

    public function dhDelete(Request $r)
    {
        $r->validate([
            'MaDH' => 'required|integer|exists:bang_danhhieu,MaDH',
        ]);

        DanhHieu::destroy($r->MaDH);

        return redirect()->route('doan.danhhieu.index')->with('ok', 'Đã xóa danh hiệu.');
    }
    // =========================
    // (1) QUẢN LÝ SỰ KIỆN
    // =========================
    public function suKienIndex(Request $request)
    {
        $q = $request->input('q');

        $events = SuKien::query()
    ->when($q, function ($query) use ($q) {
        $query->where(function ($sub) use ($q) {
            $sub->where('TieuDe', 'like', "%{$q}%")
                ->orWhere('DiaDiem', 'like', "%{$q}%");
        });
    })
    ->orderByDesc('MaSK')
    ->paginate(5)
    ->withQueryString(); // giữ ?q=... khi bấm chuyển trang

        // lấy ảnh đầu tiên theo ThuTu để hiển thị nhanh trên bảng
        $eventIds = $events->pluck('MaSK')->toArray();

$firstImages = SuKienAnh::query()
    ->select('MaSK', DB::raw('MIN(ThuTu) as min_thutu'))
    ->whereIn('MaSK', $eventIds)
    ->groupBy('MaSK')
    ->get()
    ->keyBy('MaSK');

        $imageMap = [];
        if ($firstImages->count()) {
            $rows = SuKienAnh::query()
                ->whereIn('MaSK', $firstImages->keys()->toArray())
                ->orderBy('ThuTu')
                ->get();

            foreach ($rows as $r) {
                if (!isset($imageMap[$r->MaSK])) {
                    $imageMap[$r->MaSK] = $r->DuongDan;
                }
            }
        }

        return view('doan.sukien.index', compact('events', 'imageMap', 'q'));
    }

    public function suKienStore(Request $request)
    {
        $data = $request->validate([
            'TieuDe'         => 'required|string|max:200',
            'NoiDung'        => 'required|string',
            'ThoiGianBatDau' => 'required|date',
            'ThoiGianKetThuc' => 'required|date|after_or_equal:ThoiGianBatDau',
            'DiaDiem'        => 'required|string|max:255',
            'SoLuongToiDa'   => 'nullable|integer|min:1',
            'TrangThai'      => 'required|in:Draft,Open,Closed,Cancelled',
            'images.*'       => 'nullable|image|max:5120', // 5MB/ảnh
        ]);

        $sk = new SuKien();
        $sk->TieuDe = $data['TieuDe'];
        $sk->NoiDung = $data['NoiDung'];
        $sk->ThoiGianBatDau = $data['ThoiGianBatDau'];
        $sk->ThoiGianKetThuc = $data['ThoiGianKetThuc'];
        $sk->DiaDiem = $data['DiaDiem'];
        $sk->SoLuongToiDa = $data['SoLuongToiDa'] ?? null;
        $sk->TrangThai = $data['TrangThai'];
        $sk->TaoLuc = now();
        $sk->CapNhatLuc = now();
        $sk->save();

        // upload nhiều ảnh
        if ($request->hasFile('images')) {
            $order = 1;
            foreach ($request->file('images') as $img) {
                if (!$img) continue;

                $path = $img->store('sukien', 'public'); // storage/app/public/sukien/...
                $publicPath = 'storage/' . $path;        // dùng để hiển thị <img src="...">

                SuKienAnh::create([
                    'MaSK'     => $sk->MaSK,
                    'DuongDan' => $publicPath,
                    'TenFile'  => $img->getClientOriginalName(),
                    'ThuTu'    => $order++,
                    'TaoLuc'   => now(),
                ]);
            }
        }

        return redirect()->route('doan.sukien.index')->with('success', 'Đã tạo sự kiện.');
    }

    public function suKienUpdate(Request $request)
    {
        $data = $request->validate([
            'MaSK'           => 'required|integer',
            'TieuDe'         => 'required|string|max:200',
            'NoiDung'        => 'required|string',
            'ThoiGianBatDau' => 'required|date',
            'ThoiGianKetThuc' => 'required|date|after_or_equal:ThoiGianBatDau',
            'DiaDiem'        => 'required|string|max:255',
            'SoLuongToiDa'   => 'nullable|integer|min:1',
            'TrangThai'      => 'required|in:Draft,Open,Closed,Cancelled',
            'images.*'       => 'nullable|image|max:5120',
        ]);

        $sk = SuKien::findOrFail($data['MaSK']);
        $sk->TieuDe = $data['TieuDe'];
        $sk->NoiDung = $data['NoiDung'];
        $sk->ThoiGianBatDau = $data['ThoiGianBatDau'];
        $sk->ThoiGianKetThuc = $data['ThoiGianKetThuc'];
        $sk->DiaDiem = $data['DiaDiem'];
        $sk->SoLuongToiDa = $data['SoLuongToiDa'] ?? null;
        $sk->TrangThai = $data['TrangThai'];
        $sk->CapNhatLuc = now();
        $sk->save();

        // nếu upload thêm ảnh thì append vào cuối
        if ($request->hasFile('images')) {
            $maxOrder = SuKienAnh::where('MaSK', $sk->MaSK)->max('ThuTu');
            $order = $maxOrder ? ($maxOrder + 1) : 1;

            foreach ($request->file('images') as $img) {
                if (!$img) continue;

                $path = $img->store('sukien', 'public');
                $publicPath = 'storage/' . $path;

                SuKienAnh::create([
                    'MaSK'     => $sk->MaSK,
                    'DuongDan' => $publicPath,
                    'TenFile'  => $img->getClientOriginalName(),
                    'ThuTu'    => $order++,
                    'TaoLuc'   => now(),
                ]);
            }
        }

        return redirect()->route('doan.sukien.index')->with('success', 'Đã cập nhật sự kiện.');
    }

    public function suKienDelete(Request $request)
    {
        $data = $request->validate([
            'MaSK' => 'required|integer',
        ]);

        $sk = SuKien::findOrFail($data['MaSK']);

        // xóa file ảnh trong storage (nếu có)
        $images = SuKienAnh::where('MaSK', $sk->MaSK)->get();
        foreach ($images as $img) {
            // DuongDan dạng "storage/sukien/xxx.jpg" => cần đổi về "sukien/xxx.jpg"
            $relative = str_replace('storage/', '', $img->DuongDan);
            Storage::disk('public')->delete($relative);
        }

        // Xóa sự kiện => ảnh + đăng ký sẽ bị xóa theo FK ON DELETE CASCADE (nếu bạn tạo như SQL)
        $sk->delete();

        return redirect()->route('doan.sukien.index')->with('success', 'Đã xóa sự kiện.');
    }

    public function suKienToggle(Request $request)
    {
        $data = $request->validate([
            'MaSK' => 'required|integer',
        ]);

        $sk = SuKien::findOrFail($data['MaSK']);

        // Toggle hợp lý:
        // Draft  -> Open
        // Open   -> Closed
        // Closed -> Open
        // Cancelled: không cho mở lại (tuỳ bạn có muốn cho không)
        if ($sk->TrangThai === 'Draft') {
            $sk->TrangThai = 'Open';
        } elseif ($sk->TrangThai === 'Open') {
            $sk->TrangThai = 'Closed';
        } elseif ($sk->TrangThai === 'Closed') {
            $sk->TrangThai = 'Open';
        } elseif ($sk->TrangThai === 'Cancelled') {
            return back()->with('error', 'Sự kiện đã bị huỷ, không thể mở lại.');
        }

        $sk->CapNhatLuc = now();
        $sk->save();

        return back()->with('success', 'Đã đổi trạng thái sự kiện.');
    }

    // =========================
    // (3) DS ĐĂNG KÝ SỰ KIỆN + ĐIỂM DANH
    // =========================
    public function suKienDangKyIndex(Request $request)
    {
 $MaSK = $request->get('MaSK');
    $q    = $request->get('q');

    $events = DB::table('bang_sukien')
        ->orderByDesc('MaSK')
        ->get();

    $rows = collect();

    if (!empty($MaSK)) {
        $rows = DB::table('bang_dangkysukien as dk')
            ->join('bang_sinhvien as sv', 'sv.MaSV', '=', 'dk.MaSV')
            ->where('dk.MaSK', $MaSK)
            ->when($q, function($query) use ($q){
                $query->where(function($sub) use ($q){
                    $sub->where('sv.MaSV', 'like', "%{$q}%")
                        ->orWhere('sv.HoTen', 'like', "%{$q}%");
                });
            })
            ->select(
                'dk.MaSK',
                'dk.MaSV',
                'sv.HoTen',
                'sv.Lop',
                'dk.DangKyLuc',
                'dk.TrangThaiDangKy',
                'dk.DaDiemDanh',
                'dk.DiemDanhLuc'
            )
            ->orderBy('sv.HoTen')
            ->paginate(5)
            ->appends($request->query()); // giữ MaSK, q khi chuyển trang
    }

    return view('doan.sukien.dangky', compact('events', 'rows', 'MaSK'));
    }

    public function suKienDiemDanh(Request $request)
    {
        $data = $request->validate([
            'MaSK'   => 'required|integer',
            'MaSV'   => 'required|string|max:20',
            'action' => 'required|in:checkin,checkout',
        ]);

        // Luôn update theo đúng 2 điều kiện (MaSK, MaSV) để tránh update hàng loạt
        $payload = [];

        if ($data['action'] === 'checkin') {
            $payload = [
                'DaDiemDanh' => 1,
                'DiemDanhLuc' => now(),
            ];
        } else {
            $payload = [
                'DaDiemDanh' => 0,
                'DiemDanhLuc' => null,
            ];
        }

        $affected = DB::table('bang_dangkysukien')
            ->where('MaSK', $data['MaSK'])
            ->where('MaSV', $data['MaSV'])
            ->update($payload);

        if ($affected === 0) {
            return back()->with('error', 'Không tìm thấy đăng ký của sinh viên trong sự kiện này.');
        }

        return back()->with('success', 'Đã cập nhật điểm danh.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\TaiKhoan;
use App\Models\AdminProfile;
use App\Models\CtctHssvProfile;
use App\Models\KhaoThiProfile;
use App\Models\DoanTruongProfile;

class StaffProfileController extends Controller
{
    public function index(Request $r)
    {
        $q = trim((string) $r->input('q'));

        $data = TaiKhoan::query()
            ->select('MaTK', 'TenDangNhap', 'VaiTro', 'TrangThai', 'Email')
            ->whereIn('VaiTro', ['Admin', 'CTCTHSSV', 'KhaoThi', 'DoanTruong'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('TenDangNhap', 'like', "%{$q}%")
                       ->orWhere('Email', 'like', "%{$q}%")
                       ->orWhere('VaiTro', 'like', "%{$q}%")
                       ->orWhere('MaTK', $q);
                });
            })
            ->with(['adminProfile', 'ctctProfile', 'khaoThiProfile', 'doanProfile'])
            ->orderBy('MaTK', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.staff.index', compact('data', 'q'));
    }

    public function upsert(Request $r)
    {
        // 1) Validate chung
        $r->validate([
            'MaTK'   => ['required', 'integer', 'exists:BANG_TaiKhoan,MaTK'],
            'VaiTro' => ['required', Rule::in(['Admin','CTCTHSSV','KhaoThi','DoanTruong'])],

            // field để form dùng chung (nullable)
            'MaAdmin' => ['nullable', 'string', 'max:20'],
            'MaCTCT'  => ['nullable', 'string', 'max:20'],
            'MaPKT'   => ['nullable', 'string', 'max:20'],
            'MaDT'    => ['nullable', 'string', 'max:20'],

            'TenPhong' => ['nullable', 'string', 'max:50'],
            'TenDT'    => ['nullable', 'string', 'max:50'],
            'NguoiQL'  => ['nullable', 'string', 'max:50'],
        ], [
            'MaTK.required'   => 'Mã tài khoản là bắt buộc.',
            'MaTK.exists'     => 'Mã tài khoản không tồn tại.',
            'VaiTro.required' => 'Vai trò là bắt buộc.',
            'VaiTro.in'       => 'Vai trò không hợp lệ.',
        ]);

        $tk = TaiKhoan::where('MaTK', $r->MaTK)->firstOrFail();

        // Chặn request giả vai trò
        if ($tk->VaiTro !== $r->VaiTro) {
            return back()->withErrors([
                'VaiTro' => 'Vai trò không khớp với tài khoản đang chọn.'
            ])->withInput();
        }

        // 2) Xử lý theo vai trò
        if ($tk->VaiTro === 'Admin') {

            $r->validate([
                'MaAdmin' => [
                    'required', 'string', 'max:20',
                    // UNIQUE theo MaAdmin, nhưng khi sửa thì ignore theo MaTK (không dùng id)
                    Rule::unique('BANG_Admin', 'MaAdmin')->ignore($tk->MaTK, 'MaTK'),
                ],
            ], [
                'MaAdmin.required' => 'MaAdmin là bắt buộc.',
                'MaAdmin.max'      => 'MaAdmin không được vượt quá 20 ký tự.',
                'MaAdmin.unique'   => 'MaAdmin đã tồn tại, hãy nhập mã khác.',
            ]);

            AdminProfile::updateOrCreate(
                ['MaTK' => $tk->MaTK],
                ['MaAdmin' => $r->MaAdmin]
            );

            return redirect()->route('admin.staff.index')->with('ok', 'Đã lưu thông tin Admin.');
        }

        if ($tk->VaiTro === 'CTCTHSSV') {

            $r->validate([
                'MaCTCT' => [
                    'required', 'string', 'max:20',
                    // UNIQUE MaCTCT, ignore theo MaTK
                    Rule::unique('BANG_CTCTHSSV', 'MaCTCT')->ignore($tk->MaTK, 'MaTK'),
                ],
                'TenPhong' => ['required', 'string', 'max:50'],
                'NguoiQL'  => ['required', 'string', 'max:50'],
            ], [
                'MaCTCT.required'   => 'MaCTCT là bắt buộc.',
                'MaCTCT.max'        => 'MaCTCT không được vượt quá 20 ký tự.',
                'MaCTCT.unique'     => 'MaCTCT đã tồn tại, hãy nhập mã khác.',
                'TenPhong.required' => 'Tên phòng là bắt buộc.',
                'TenPhong.max'      => 'Tên phòng không được vượt quá 50 ký tự.',
                'NguoiQL.required'  => 'Người quản lý là bắt buộc.',
                'NguoiQL.max'       => 'Người quản lý không được vượt quá 50 ký tự.',
            ]);

            CtctHssvProfile::updateOrCreate(
                ['MaTK' => $tk->MaTK],
                [
                    'MaCTCT'   => $r->MaCTCT,
                    'TenPhong' => $r->TenPhong,
                    'NguoiQL'  => $r->NguoiQL,
                ]
            );

            return redirect()->route('admin.staff.index')->with('ok', 'Đã lưu thông tin CTCT-HSSV.');
        }

        if ($tk->VaiTro === 'KhaoThi') {

            $r->validate([
                'MaPKT' => [
                    'required', 'string', 'max:20',
                    // Lưu ý: tên bảng phải đúng DB của bạn (bạn đang dùng BANG_KhaoThi)
                    Rule::unique('BANG_KhaoThi', 'MaPKT')->ignore($tk->MaTK, 'MaTK'),
                ],
                'TenPhong' => ['required', 'string', 'max:50'],
                'NguoiQL'  => ['required', 'string', 'max:50'],
            ], [
                'MaPKT.required'    => 'MaPKT là bắt buộc.',
                'MaPKT.max'         => 'MaPKT không được vượt quá 20 ký tự.',
                'MaPKT.unique'      => 'MaPKT đã tồn tại, hãy nhập mã khác.',
                'TenPhong.required' => 'Tên phòng là bắt buộc.',
                'TenPhong.max'      => 'Tên phòng không được vượt quá 50 ký tự.',
                'NguoiQL.required'  => 'Người quản lý là bắt buộc.',
                'NguoiQL.max'       => 'Người quản lý không được vượt quá 50 ký tự.',
            ]);

            KhaoThiProfile::updateOrCreate(
                ['MaTK' => $tk->MaTK],
                [
                    'MaPKT'    => $r->MaPKT,
                    'TenPhong' => $r->TenPhong,
                    'NguoiQL'  => $r->NguoiQL,
                ]
            );

            return redirect()->route('admin.staff.index')->with('ok', 'Đã lưu thông tin Phòng Khảo thí.');
        }

        // DoanTruong
        $r->validate([
            'MaDT' => [
                'required', 'string', 'max:20',
                // Lưu ý: tên bảng phải đúng DB của bạn (bạn đang dùng BANG_DoanTruong)
                Rule::unique('BANG_DoanTruong', 'MaDT')->ignore($tk->MaTK, 'MaTK'),
            ],
            'TenDT'   => ['required', 'string', 'max:50'],
            'NguoiQL' => ['required', 'string', 'max:50'],
        ], [
            'MaDT.required'    => 'MaDT là bắt buộc.',
            'MaDT.max'         => 'MaDT không được vượt quá 20 ký tự.',
            'MaDT.unique'      => 'MaDT đã tồn tại, hãy nhập mã khác.',
            'TenDT.required'   => 'Tên Đoàn trường là bắt buộc.',
            'TenDT.max'        => 'Tên Đoàn trường không được vượt quá 50 ký tự.',
            'NguoiQL.required' => 'Người quản lý là bắt buộc.',
            'NguoiQL.max'      => 'Người quản lý không được vượt quá 50 ký tự.',
        ]);

        DoanTruongProfile::updateOrCreate(
            ['MaTK' => $tk->MaTK],
            [
                'MaDT'    => $r->MaDT,
                'TenDT'   => $r->TenDT,
                'NguoiQL' => $r->NguoiQL,
            ]
        );

        return redirect()->route('admin.staff.index')->with('ok', 'Đã lưu thông tin Đoàn Trường.');
    }
}

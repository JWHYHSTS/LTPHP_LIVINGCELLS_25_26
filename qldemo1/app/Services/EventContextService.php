<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class EventContextService
{
    public function buildContext(int $limit = 12): string
    {
        $events = DB::table('bang_sukien')
            ->select([
                'MaSK as id',
                'TieuDe as title',
                'NoiDung as content',
                'ThoiGianBatDau as start_at',
                'ThoiGianKetThuc as end_at',
                'DiaDiem as location',
                'SoLuongToiDa as max_qty',
                'TrangThai as status',
            ])
            ->orderBy('ThoiGianBatDau', 'desc')
            ->limit($limit)
            ->get();

        if ($events->isEmpty()) {
            return "EVENTS_DATA: Không có sự kiện nào trong hệ thống.";
        }

        $lines = [];
        $lines[] = "EVENTS_DATA (tối đa {$limit} sự kiện):";

        foreach ($events as $e) {
            $lines[] =
                "- [#{$e->id}] {$e->title}"
                . " | Thời gian: {$e->start_at} → {$e->end_at}"
                . " | Địa điểm: {$e->location}"
                . " | SL tối đa: " . ($e->max_qty ?? 'Không giới hạn')
                . " | Trạng thái: {$e->status}"
                . " | Nội dung: " . mb_strimwidth((string) $e->content, 0, 220, "...");
        }

        return implode("\n", $lines);
    }
}

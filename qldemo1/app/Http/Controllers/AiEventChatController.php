<?php

namespace App\Http\Controllers;

use App\Services\AiChatService;
use App\Services\EventContextService;
use App\Services\SystemContextService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AiEventChatController extends Controller
{
    public function chat(
        Request $request,
        AiChatService $ai,
        EventContextService $eventCtx,
        SystemContextService $systemCtx
    ): JsonResponse {
        try {
            $data = $request->validate([
                'message' => 'required|string|max:2000',
                'history' => 'nullable|array',
                'history.*.role' => 'required_with:history|in:user,assistant',
                'history.*.content' => 'required_with:history|string|max:4000',
            ]);

            $history = array_slice($data['history'] ?? [], -10);

            $ctxSystem = $systemCtx->build();
            $ctxEvents = $eventCtx->buildContext((int) config('ai.event_context_limit', 12));

            $systemText = <<<PROMPT
Bạn là trợ lý AI của HỆ THỐNG QUẢN LÝ SINH VIÊN.

MỤC TIÊU:
- Trả lời tất cả câu hỏi liên quan đến chức năng hệ thống.
- Với dữ liệu sự kiện: chỉ dùng EVENTS_DATA (không bịa).

QUY TẮC:
1) Không bịa dữ liệu DB. Với sự kiện: chỉ dựa EVENTS_DATA.
2) Thiếu dữ liệu: nói "Hệ thống chưa cung cấp dữ liệu đó" + hướng dẫn cách xem.
3) Liệt kê sự kiện tối đa 5 và có #ID.
4) Trả lời ngắn gọn, có bước thao tác nếu hỏi “làm sao…”.

SYSTEM_CONTEXT:
{$ctxSystem}

EVENTS_DATA:
{$ctxEvents}
PROMPT;

            $messages = [['role' => 'system', 'content' => $systemText]];
            foreach ($history as $h) {
                $messages[] = ['role' => $h['role'], 'content' => $h['content']];
            }
            $messages[] = ['role' => 'user', 'content' => $data['message']];

            $reply = $ai->chat($messages);

            return response()->json([
                'reply' => trim($reply) !== '' ? trim($reply) : '(AI không trả lời)',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dữ liệu gửi lên không hợp lệ.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('AiEventChatController chat failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}

<?php

return [
    'base_url' => env('AI_BASE_URL', 'https://api.openai.com/v1'),
    'api_key'  => env('AI_API_KEY'),
    'model'    => env('AI_MODEL', 'gpt-4o-mini'),

    // Events
    'event_table' => env('EVENT_TABLE', 'bang_sukien'),
    'event_context_limit' => (int) env('EVENT_CONTEXT_LIMIT', 12),

    // Awards / Criteria (Danh hiệu)
    'award_table' => env('AWARD_TABLE', 'bang_danhhieu'),
    'award_context_limit' => (int) env('AWARD_CONTEXT_LIMIT', 30),
];

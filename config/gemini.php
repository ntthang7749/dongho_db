<?php

return [
    // Hỗ trợ danh sách nhiều API Keys để tự động xoay vòng (Key Rotation)
    'api_keys' => explode(',', env('GEMINI_API_KEYS', env('GEMINI_API_KEY', ''))),
    
    'api_key' => env('GEMINI_API_KEY', ''),
    'model' => env('GEMINI_MODEL', 'gemini-2.0-flash-lite'),

    // ✅ PHẢI LÀ v1, KHÔNG PHẢI v1beta
    'base_url' => 'https://generativelanguage.googleapis.com/v1/models',
    'timeout' => 30,
];

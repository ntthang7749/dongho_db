<?php

$apiKey = 'AIzaSyAlvZdAYVfP89ZvCghGioUg8iYoqSg04Lc'; // Key của bạn
$model = 'gemini-1.5-flash'; // Tên model chuẩn
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => 'Xin chào, bạn là ai?'],
            ],
        ],
    ],
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo 'STATUS: '.$status."\n";
echo 'BODY: '.$response."\n";

curl_close($ch);

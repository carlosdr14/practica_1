<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SlackHelper
{
    public static function sendMessage($message)
    {
        $webhookUrl = env('SLACK_WEBHOOK_URL');

        if (!$webhookUrl) {
            return false; // Evitar errores si la URL no está configurada
        }

        Http::post($webhookUrl, [
            'text' => $message,
        ]);
    }
}

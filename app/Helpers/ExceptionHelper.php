<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Log;

class ExceptionHelper
{
    public static function logError(\Exception $exception, $message = null, array $data = [])
    {
        $data['message'] = $exception->getMessage();
        $data['file']    = $exception->getMessage();
        $data['line']    = $exception->getMessage();
        Log::error(
            $message ?? $exception->getMessage(),
            $data
        );
    }
}

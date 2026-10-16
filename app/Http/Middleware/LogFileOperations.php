<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class LogFileOperations
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('files')) {
            $summary = array_map(
                fn ($file) => [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ],
                $request->file('files')
            );

            Log::info('Uploaded files: ' . json_encode($summary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        return $next($request);
    }
}
<?php

use Illuminate\Support\Facades\Route;

// Vue SPA の catch-all — /api/* 以外の全リクエストに index.html を返す
Route::get('/{any}', function () {
    $spaIndex = public_path('spa/index.html');
    if (file_exists($spaIndex)) {
        return response(file_get_contents($spaIndex))->header('Content-Type', 'text/html');
    }
    return response('App not built. Run: cd frontend && npm run build', 503);
})->where('any', '.*');

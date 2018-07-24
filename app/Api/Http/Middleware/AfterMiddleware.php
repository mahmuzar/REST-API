<?php

/**
 * Включил возможность задавать язык, который хочется использовать при работе с API
 * Если передавать заголовок Locale: ru то сообщения об ошибках будут на русском языке
 * 
 */

namespace App\Api\Http\Middleware;

use Closure;

class AfterMiddleware {

    public function handle($request, Closure $next) {
        if ($request->header('Locale')) {
            app('translator')->setLocale($request->header('Locale'));
        }
        
        return $next($request);
    }

}

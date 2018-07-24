<?php

/**
 * Авторизация пользователя.
 * 
 * Исползуется для залогинивания пользователя. Основная работа происходит в
 *  AuthServiceProvider
 * При успехе выбрасываем исключение
 */

namespace App\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use App\Api\Exceptions\LoginException;

class SecurityController extends Controller {

    public function login(Request $request) {

        if ($request->user('api')) {
            // Аутентификация прошла успешно
            throw new LoginException(trans('user.login.success', ['token'=> $request->user('api')->access_token]), Response::HTTP_OK);
        }
        throw new AuthenticationException;
    }

    public function registration() {
        
    }

}

<?php

namespace App\Api\Providers;

use App\Api\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use App\Api\Exceptions\LoginException;
use App\Api\Exceptions\TokenException;
use Illuminate\Database\Eloquent\Model;

class AuthServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Аутентификация пользователя
     * В случае успеха возвращается объект аутентифицированного пользователя
     * Иначе выбрасывается исключение в зависимости от типа ошибки
     * 
     * @return void
     */
    public function boot() {
        $this->app['auth']->viaRequest('api', function ($request) {
            //Получаем токен из заголовков
            $access_token = $request->headers->get('Token');
            $user = User::where('username', $request->input('username'))->first();
            if (!empty($request->input('username'))) {
                if (!empty($user)) {
                    if (Hash::check($request->input('password'), $user->password)) {
                        $user->access_token = bin2hex(openssl_random_pseudo_bytes(30));
                        if ($user->update()) {
                            return $user;
                        }else{
                            
                        }
                    } else {
                        throw new LoginException(trans('validation.login'), 401);
                    }
                } else {
                    throw new LoginException(trans('validation.login'), 401);
                }
            }
            if (!empty($access_token)) {
                $user = User::where('access_token', $access_token)->first();
                if ($user instanceof Model) {
                    return $user;
                } else {
                    throw new TokenException(trans('validation.token.wrong'), 401);
                }
            } else {
                throw new TokenException(trans('validation.token.wrong'), 401);
            }
        });
    }

}

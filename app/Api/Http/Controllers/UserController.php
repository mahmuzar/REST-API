<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Api\Http\Controllers;

use App\Api\User;
use Illuminate\Http\Request;
use Validator;
use App\Api\Rules\Required;
use App\Api\Exceptions\SuccessException;
use App\Api\Exceptions\ErrorException;
use Illuminate\Http\Response;


class UserController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
                    'username' => ['required', 'string', new Required()],
                    'password' => ['required', 'string', new Required()],
                    'email' => ['required', 'string', new Required()],
        ]);
        if ($validator->fails()) {
            throw new ErrorException(json_encode($validator->errors()), Response::HTTP_NOT_FOUND);
        }
        $user = new User;
        $user->username = $request->username;
        $user->password = $request->password;
        $user->email = $request->email;

        if ($user->save()) {
            throw new SuccessException(trans('user.created', ['id' => $user->id]), Response::HTTP_CREATED);
        }else{
            throw new ErrorException(json_encode(trans('user.not_created')), Response::HTTP_NOT_FOUND);
        }
    }

  

    //
}

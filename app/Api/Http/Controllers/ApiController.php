<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Api\Http\Controllers;


class ApiController extends Controller {

    public function index() {
        return response()->json('Lumen RESTful API By mahmuzar (https://mahmuzar.com)');
    }

}

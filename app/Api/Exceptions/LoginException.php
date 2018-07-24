<?php

/**
 * Исклюение при аутентификации
 */

namespace App\Api\Exceptions;

use Exception;

class LoginException extends Exception {

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report() {
//
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request) {
        $content['status'] = $this->getCode();
        $message = json_decode($this->getMessage());
        if (json_last_error() == JSON_ERROR_NONE) {
            $content['message'] = $message;
        } else {
            $content['message'] = $this->getMessage();
        }
        return response()->json(["error" => $content], $content['status']);
    }

}

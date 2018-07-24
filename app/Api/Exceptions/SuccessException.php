<?php

/**
 * Исключение успешной операции
 */

namespace App\Api\Exceptions;

use Exception;

class SuccessException extends Exception {

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
        $content['message'] = $this->getMessage();
        return response()->json(["ok" => $content], $content['status']);
    }

}

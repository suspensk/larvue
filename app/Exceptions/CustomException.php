<?php
/**
 * Created by PhpStorm.
 * User: suspensk
 * Date: 27.11.2018
 * Time: 6:50
 */

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->view(
            'errors.custom',
            array(
                'exception' => $this
            )
        );
    }
}
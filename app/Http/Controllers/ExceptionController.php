<?php
/**
 * Created by PhpStorm.
 * User: suspensk
 * Date: 27.11.2018
 * Time: 6:53
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ExceptionController extends Controller
{
    public function index()
    {
        // something went wrong and you want to throw CustomException
        throw new \App\Exceptions\CustomException('Something Went Wrong.');
    }
}
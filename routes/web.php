<?php
// Exception routes
Route::get('exception/index', 'ExceptionController@index');
Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');

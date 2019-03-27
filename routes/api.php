<?php

Route::post('login', 'UserController@login');
Route::post('logout', 'UserController@logout');
Route::post('register', 'UserController@register');
//Route::post('notes/add', 'NoteController@store');
//Route::get('notes', 'NoteController@index');

Route::resource('/notes', 'NoteController');
Route::get('/count', 'NoteController@notesCount');
Route::post('notes/{id}', 'NoteController@update'); // put does not works in axios
Route::resource('/tags', 'TagController');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/category/{category}/tasks', 'CategoryController@tasks');


    Route::resource('/category', 'CategoryController');
    Route::resource('/task', 'TaskController');
});

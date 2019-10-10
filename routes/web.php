<?php

/*w*/

Route::get('/', function () {
    return view('auth/login');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('{path}','HomeController@index')->where('path','([A-z\d-\/_.]+?)');



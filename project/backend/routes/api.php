<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/hello', function () {
    return response()->json(array(
        "foo" => "bar"
    ));
});

Route::get('/users', function () {
    $users = DB::table('users')->get();
    return response()->json($users);
});

Route::any('/signup', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/login', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/user/state', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/user/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/user/posts/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/user/search/{term}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/user/posts/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/request/send', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/request/accept', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/request/decline', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/request/unfriend', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/request/unsend', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/request/timeline/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/send', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/like', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/liked', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/unline', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/timeline/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/get/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/discover/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/delete/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/comment/delete/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/post/comment/send/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/message/send', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/message/create', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/message/all/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

Route::any('/message/threads/{id}', function () {
    return response()->json(array(
        "message" => "hello"
    ));
});

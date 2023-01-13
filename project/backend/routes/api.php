<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// $users = DB::table('User')->get();
// return response()->json($users);

Route::any('/signup', function (Request $req) {
    $body = json_decode($req->getContent());

    $exisiting_users_query = DB::raw('SELECT * FROM User WHERE email = ? OR username = ?');
    $existing_users = DB::select($exisiting_users_query, [$body->email, $body->username]);

    if (count($existing_users) > 0) {
        return response()->json([
            "success" => false,
            "message" => "User exists with given email or username",
        ]);
    }

    $create_user_query = 'INSERT INTO User (email, username, password_hash, bio) VALUES (?, ?, ?, ?)';
    DB::select($create_user_query, [$body->email, $body->username, $body->password, $body->bio]);

    return response()->json(["success" => true]);
});

Route::any('/login', function (Request $req) {
    $body = json_decode($req->getContent());

    $exisiting_users_query = DB::raw('SELECT * FROM User WHERE email = ? LIMIT 1');
    $existing_users = DB::select($exisiting_users_query, [$body->email]);

    if (count($existing_users) <= 0) {
        return response()->json([
            "success" => false,
            "message" => "User not found",
        ]);
    }

    $user = $existing_users[0];
    if ($body->password != $user->password_hash) {
        return response()->json([
            "success" => false,
            "message" => "Invalid credentials",
        ]);
    }

    return response()->json(["success" => true, "user" => $user]);
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

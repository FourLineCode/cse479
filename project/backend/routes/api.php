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

Route::any('/user/state', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->curr_id || !$body->user_id) {
        return response()->json(["message" => "Provide id"]);
    }

    $get_friendship_query = DB::raw('SELECT * FROM Friendship WHERE (first_user_id = ? OR second_user_id = ?) AND (first_user_id = ? OR second_user_id = ?) LIMIT 1');
    $friendships = DB::select($get_friendship_query, [$body->curr_id, $body->curr_id, $body->user_id, $body->user_id]);
    if (count($friendships) > 0) {
        return response()->json(["state" => "FRIEND"]);
    }

    $get_request_query = DB::raw('SELECT * FROM FriendRequest WHERE (from_user_id = ? OR to_user_id = ?) AND (from_user_id = ? OR to_user_id = ?) LIMIT 1');
    $requests = DB::select($get_request_query, [$body->curr_id, $body->curr_id, $body->user_id, $body->user_id]);
    if (count($requests) > 0) {
        if ($requests[0]->from_user_id === $body->curr_id) {
            return response()->json(["state" => "SENT"]);
        } else {
            return response()->json(["state" => "PENDING"]);
        }
    }

    return response()->json(["state" => "NONE"]);
});

Route::any('/user/{id}', function ($id) {
    if (!$id) {
        return response()->json(["message" => "Provide id"]);
    }

    $get_user_query = DB::raw('SELECT * FROM User WHERE id = ? LIMIT 1');
    $users = DB::select($get_user_query, [$id]);

    return response()->json($users[0]);
});

Route::any('/user/posts/{id}', function ($id) {
    if (!$id) {
        return response()->json(["message" => "Provide id"]);
    }

    $get_user_query = DB::raw('SELECT * FROM User WHERE id = ? LIMIT 1');
    $users = DB::select($get_user_query, [$id]);

    if (count($users) <= 0) {
        return response()->json(["message" => "User doesn't exists"]);
    }

    $get_posts_query = DB::raw('SELECT * FROM Post WHERE author_id = ? ORDER BY created_at DESC');
    $posts = DB::select($get_posts_query, [$id]);

    for ($i = 0; $i < count($posts); $i++) {
        $get_post_likes_query = DB::raw('SELECT COUNT(*) AS count FROM PostLike WHERE post_id = ?');
        $likes = DB::select($get_post_likes_query, [$posts[$i]->id]);

        $get_comments_query = DB::raw('SELECT COUNT(*) AS count FROM PostComment WHERE post_id = ?');
        $comments = DB::select($get_comments_query, [$posts[$i]->id]);

        $posts[$i]->likeCount = $likes[0]->count;
        $posts[$i]->commentCount = $comments[0]->count;
        $posts[$i]->author = $users[0];
    }

    return response()->json($posts);
});

Route::any('/user/search/{term}', function ($term) {
    $term = strtolower(trim($term));
    if (!$term) {
        return response()->json([]);
    }

    $search_user_query = DB::raw('SELECT * FROM User WHERE LOWER(username) LIKE ?');
    $users = DB::select($search_user_query, ["%$term%"]);

    return response()->json($users);
});

Route::any('/request/send', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/request/accept', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/request/decline', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/request/unfriend', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/request/unsend', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/request/timeline/{id}', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/send', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/like', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/liked', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/unline', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/timeline/{id}', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/get/{id}', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/discover/{id}', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/delete/{id}', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/comment/delete/{id}', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/post/comment/send/{id}', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/message/send', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/message/create', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/message/all/{id}', function (Request $req) {
    return response()->json(["success" => true]);
});

Route::any('/message/threads/{id}', function (Request $req) {
    return response()->json(["success" => true]);
});

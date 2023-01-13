<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    $body = json_decode($req->getContent());
    if (!$body->from || !$body->to) {
        return response()->status(400);
    }

    $create_request_query = DB::raw('INSERT INTO FriendRequest (from_user_id, to_user_id) VALUES (?, ?)');
    DB::select($create_request_query, [$body->from, $body->to]);

    return response()->json(["success" => true]);
});

Route::any('/request/accept', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->from || !$body->to) {
        return response()->status(400);
    }

    $delete_request_query = DB::raw('DELETE FROM FriendRequest WHERE from_user_id = ? AND to_user_id = ?');
    DB::select($delete_request_query, [$body->from, $body->to]);

    $create_friendship_query = DB::raw('INSERT INTO Friendship (first_user_id, second_user_id) VALUES (?, ?)');
    DB::select($create_friendship_query, [$body->from, $body->to]);

    return response()->json(["success" => true]);
});

Route::any('/request/decline', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->from || !$body->to) {
        return response()->status(400);
    }

    $delete_request_query = DB::raw('DELETE FROM FriendRequest WHERE from_user_id = ? AND to_user_id = ?');
    DB::select($delete_request_query, [$body->from, $body->to]);

    return response()->json(["success" => true]);
});

Route::any('/request/unfriend', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->first || !$body->second) {
        return response()->status(400);
    }

    $unfriend_query = DB::raw('DELETE FROM Friendship WHERE (first_user_id = ? OR second_user_id = ?) AND (first_user_id = ? OR second_user_id = ?)');
    DB::select($unfriend_query, [$body->first, $body->first, $body->second, $body->second]);

    return response()->json(["success" => true]);
});

Route::any('/request/unsend', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->from || !$body->to) {
        return response()->status(400);
    }

    $delete_request_query = DB::raw('DELETE FROM FriendRequest WHERE from_user_id = ? AND to_user_id = ?');
    DB::select($delete_request_query, [$body->from, $body->to]);

    return response()->json(["success" => true]);
});

Route::any('/request/timeline/{id}', function ($id) {
    if (!$id) {
        return response()->status(400);
    }

    $get_pending_request_query = DB::raw('SELECT * FROM FriendRequest WHERE to_user_id = ?');
    $requests = DB::select($get_pending_request_query, [$id]);

    for ($i = 0; $i < count($requests); $i++) {
        $get_user_query = DB::raw('SELECT * FROM User WHERE id = ? LIMIT 1');
        $users = DB::select($get_user_query, [$requests[$i]->from_user_id]);

        $requests[$i]->from_user = $users[0];
    }

    return response()->json($requests);
});

Route::any('/post/send', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->post || !$body->author_id) {
        return response()->status(400);
    }

    $create_post_query = DB::raw('INSERT INTO Post (post_body, author_id) VALUES (?, ?)');
    DB::select($create_post_query, [$body->post, $body->author_id]);

    return response()->json(["success" => true]);
});

Route::any('/post/like', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->user_id || !$body->post_id) {
        return response()->json(["message" => "Invalid request"]);
    }

    $like_query = DB::raw('INSERT INTO PostLike (post_id, user_id) VALUES (?, ?)');
    DB::select($like_query, [$body->post_id, $body->user_id]);

    return response()->json(["success" => true]);
});

Route::any('/post/liked', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->user_id || !$body->post_id) {
        return response()->json(["message" => "Invalid request"]);
    }

    $get_liked_query = DB::raw('SELECT * FROM PostLike WHERE post_id = ? AND user_id = ? LIMIT 1');
    $likes = DB::select($get_liked_query, [$body->post_id, $body->user_id]);

    return response()->json(["liked" => count($likes) > 0]);
});

Route::any('/post/unlike', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->user_id || !$body->post_id) {
        return response()->json(["message" => "Invalid request"]);
    }

    $unlike_query = DB::raw('DELETE FROM PostLike WHERE post_id = ? AND user_id = ?');
    DB::select($unlike_query, [$body->post_id, $body->user_id]);

    return response()->json(["success" => true]);
});

Route::any('/post/timeline/{id}', function ($id) {
    if (!$id) {
        return response()->json(["message" => "Provide id"]);
    }

    $get_friend_ids_query = DB::raw('SELECT CASE WHEN first_user_id = ? THEN second_user_id ELSE first_user_id END AS friend_id FROM Friendship WHERE first_user_id = ? OR second_user_id = ?');
    $friends = DB::select($get_friend_ids_query, [$id, $id, $id]);
    $friend_ids = [(int)$id];
    foreach ($friends as $friend) {
        array_push($friend_ids, (int)$friend->friend_id);
    }

    $get_posts_query = DB::raw('SELECT * FROM Post WHERE author_id IN (' . implode(', ', $friend_ids) . ') ORDER BY created_at DESC');
    $posts = DB::select($get_posts_query);

    for ($i = 0; $i < count($posts); $i++) {
        $get_user_query = DB::raw('SELECT * FROM User WHERE id = ? LIMIT 1');
        $users = DB::select($get_user_query, [$posts[$i]->author_id]);

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

Route::any('/post/get/{id}', function ($id) {
    if (!$id) {
        return response()->json(["message" => "Provide id"]);
    }

    $get_post_query = DB::raw('SELECT * FROM Post WHERE id = ? LIMIT 1');
    $posts = DB::select($get_post_query, [$id]);

    if (count($posts) <= 0) {
        return response()->status(400);
    }

    $get_author_query = DB::raw('SELECT * FROM User WHERE id = ? LIMIT 1');
    $authors = DB::select($get_author_query, [$posts[0]->author_id]);

    $get_post_likes_query = DB::raw('SELECT COUNT(*) AS count FROM PostLike WHERE post_id = ?');
    $likes = DB::select($get_post_likes_query, [$posts[0]->id]);

    $get_comments_query = DB::raw('SELECT * FROM PostComment WHERE post_id = ?');
    $comments = DB::select($get_comments_query, [$posts[0]->id]);

    for ($i = 0; $i < count($comments); $i++) {
        $get_comment_author_query = DB::raw('SELECT * FROM User WHERE id = ? LIMIT 1');
        $users = DB::select($get_comment_author_query, [$comments[$i]->user_id]);
        $comments[$i]->author = $users[0];
    }

    $posts[0]->author = $authors[0];
    $posts[0]->likeCount = $likes[0]->count;
    $posts[0]->commentCount = count($comments);
    $posts[0]->comments = $comments;

    return response()->json($posts[0]);

    return response()->json(["success" => true]);
});

Route::any('/post/discover/{id}', function ($id) {
    if (!$id) {
        return response()->json(["message" => "Provide id"]);
    }

    $get_random_posts_query = DB::raw('SELECT * FROM Post WHERE author_id NOT IN (?) ORDER BY RAND() LIMIT 10');
    $posts = DB::select($get_random_posts_query, [$id]);

    for ($i = 0; $i < count($posts); $i++) {
        $get_user_query = DB::raw('SELECT * FROM User WHERE id = ? LIMIT 1');
        $users = DB::select($get_user_query, [$posts[$i]->author_id]);

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

Route::any('/post/delete/{id}', function ($id) {
    if (!$id) {
        return response()->json(["message" => "Provide id"]);
    }

    $delete_post_query = DB::raw('DELETE FROM Post WHERE id = ?');
    $delete_likes_query = DB::raw('DELETE FROM PostLike WHERE post_id = ?');
    $delete_comments_query = DB::raw('DELETE FROM PostComment WHERE post_id = ?');
    DB::select($delete_comments_query, [$id]);
    DB::select($delete_likes_query, [$id]);
    DB::select($delete_post_query, [$id]);

    return response()->json(["success" => true]);
});

Route::any('/post/comment/send/{id}', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->body || !$body->post_id || !$body->author_id) {
        return response()->status(400);
    }

    $create_comment_query = DB::raw('INSERT INTO PostComment (comment_body, post_id, user_id) VALUES (?, ?, ?)');
    DB::select($create_comment_query, [$body->body, $body->post_id, $body->author_id]);

    return response()->json(["success" => true]);
});

Route::any('/post/comment/delete/{id}', function ($id) {
    if (!$id) {
        return response()->json(["message" => "Provide id"]);
    }

    $delete_comment_query = DB::raw('DELETE FROM PostComment WHERE id = ?');
    DB::select($delete_comment_query, [$id]);

    return response()->json(["success" => true]);
});

Route::any('/message/create', function (Request $req) {
    $body = json_decode($req->getContent());
    if (count($body->participants) < 2) {
        return response()->status(400);
    }

    $find_thread_query = DB::raw('SELECT thread_id FROM MessageThreadOnUser WHERE user_id = ? AND thread_id IN ( SELECT thread_id FROM MessageThreadOnUser WHERE user_id = ? ) LIMIT 1');
    $threads = DB::select($find_thread_query, [$body->participants[0], $body->participants[1]]);

    if (count($threads) > 0) {
        return response()->json(["thread_id" => $threads[0]->thread_id]);
    }

    $create_thread_query = DB::raw('INSERT INTO MessageThread VALUES ();');
    DB::select($create_thread_query);

    $get_last_id_query = DB::raw('SELECT LAST_INSERT_ID() AS inserted_id');
    $inserted_id = DB::select($get_last_id_query);

    $create_participation_query = DB::raw('INSERT INTO MessageThreadOnUser (user_id, thread_id) VALUES (?, ?), (?, ?)');
    DB::select($create_participation_query, [$body->participants[0], $inserted_id[0]->inserted_id, $body->participants[1], $inserted_id[0]->inserted_id]);

    return response()->json(["thread_id" => $inserted_id[0]->inserted_id]);
});

Route::any('/message/send', function (Request $req) {
    $body = json_decode($req->getContent());
    if (!$body->author_id || !$body->thread_id || !$body->body) {
        return response()->status(400);
    }

    $send_message_query = DB::raw('INSERT INTO Message (message_body, author_id, thread_id) VALUES (?, ?, ?)');
    DB::select($send_message_query, [$body->body, $body->author_id, $body->thread_id]);

    return response()->json(["success" => true]);
});

Route::any('/message/all/{id}', function ($id) {
    if (!$id) {
        return response()->json(["message" => "Provide id"]);
    }

    $get_messages_query = DB::raw('SELECT * FROM Message WHERE thread_id = ? ORDER BY created_at ASC');
    $messages = DB::select($get_messages_query, [$id]);

    for ($i = 0; $i < count($messages); $i++) {
        $get_user_query = DB::raw('SELECT * FROM User WHERE id = ? LIMIT 1');
        $users = DB::select($get_user_query, [$messages[$i]->author_id]);

        $messages[$i]->author = $users[0];
    }

    return response()->json($messages);
});

Route::any('/message/threads/{id}', function ($id) {
    if (!$id) {
        return response()->json(["message" => "Provide id"]);
    }

    $find_thread_query = DB::raw('SELECT * FROM MessageThreadOnUser WHERE user_id = ? ORDER BY created_at DESC');
    $threadRefs = DB::select($find_thread_query, [$id]);

    $threads = [];
    for ($i = 0; $i < count($threadRefs); $i++) {
        $get_thread_query = DB::raw('SELECT * FROM MessageThread WHERE id = ? LIMIT 1');
        $found_threads = DB::select($get_thread_query, [$threadRefs[$i]->thread_id]);

        $get_user_query = DB::raw('SELECT * FROM User WHERE id IN ( SELECT user_id FROM MessageThreadOnUser WHERE thread_id = ? AND NOT user_id = ? ) LIMIT 1');
        $users = DB::select($get_user_query, [$threadRefs[$i]->thread_id, $id]);

        $found_threads[0]->user = $users[0];
        array_push($threads, $found_threads[0]);
    }

    return response()->json($threads);
});

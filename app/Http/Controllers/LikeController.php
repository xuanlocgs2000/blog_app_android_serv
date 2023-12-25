<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Post;

class LikeController extends Controller
{
    //like & unlike
    public function LikeOrUnLike($id)
    {
        $post = Post::find($id);
        if (!$post) {
            # code...
            return response([
                'message' => "Khong tim thay bai viet"
            ], 403);
        }
        $like = $post->likes()->where('user_id', auth()->user()->id)->first();
        // if not like then like
        if (!$like) {
            Like::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id

            ]);
            return response([
                'message' => 'Liked'
            ], 200);
        }
        //diskliked
        $like->delete();
        return response([
            'message' => 'Disklike'
        ], 200);
    }
}

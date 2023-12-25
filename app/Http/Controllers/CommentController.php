<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    //get all post comments
    public function index($id)
    {
        $post =  Post::find($id);
        if (!$post) {
            # code...
            return response([
                'message' => "Khong tim thay bai viet"

            ], 403);
        }
        return response([
            'comments' => $post->comments()->with('user:id,name,image,email')->get()
        ], 200);
    }
    //comment 
    public function store(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            # code...
            return response([
                'message' => "Khong tim thay"
            ], 403);
        }
        //validate
        $attrs = $request->validate([
            'comment' => 'required|String'
        ]);
        Comment::create([
            'comment' => $attrs['comment'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);
        return response([
            'post' => $post->comments()->with('user:id,name,image,email')->get()
        ], 200);
    }

    //cap nhat 
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            # code...
            return response([
                'message' => "Khong tim thay binh luan"
            ], 403);
        }
        if ($comment->user_id != auth()->user()->id) {
            return response([
                'message' => "Khong the cap nhat binh luan nay"

            ], 403);
        }
        //validate
        $attrs = $request->validate([
            'comment' => 'required|String'
        ]);
        $comment->update([
            'comment' => $attrs['comment']
        ]);
        return response([
            'message' => " Cap nhat binh luan thanh cong"

        ], 200);
    }
    public function delete($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            # code...
            return response([
                'message' => "Khong tim thay binh luan"
            ], 403);
        }
        if ($comment->user_id != auth()->user()->id) {
            return response([
                'message' => "Khong the xoa binh luan nay"

            ], 403);
        }
        $comment->delete();
        return response([
            'message' => " Xoa binh luan thanh cong"

        ]);
    }
}

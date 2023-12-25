<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Post;

class PostController extends Controller
{
    //get all posts 
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'desc')
                ->with('user:id,name,image')->withCount('comments', 'likes')
                ->with('likes', function ($like) {
                    return $like->where('user_id', auth()->user()->id)
                        ->select('id', 'user_id', 'post_id')->get();
                })
                ->get()
        ], 200);
    }
    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)->withCount('comments', 'likes')->get()
        ], 200);
    }

    //tao bai viet
    public function store(Request $request)
    {
        //validate
        $attrs = $request->validate([
            'body' => 'required|String'
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);

        return response([
            'message' => 'Bai viet da duoc tao',
            'post' => $post
        ], 200);
    }
    //cap nhat bai viet
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Khong tim thay bai viet'
            ], 403);
        }
        if ($post->user_id != auth()->user()->id) {
            # code...
            return response([
                'message' => 'khong the cap nhat'
            ], 403);
        }
        $attrs = $request->validate([
            'body' => 'required|String'
        ]);
        $post->update([
            'body' => $attrs['body']
        ]);

        return response([
            'message' => 'Bai viet da duoc  cap nhat',
            'post' => $post
        ], 200);
    }

    //delete post
    public function  delete($id)
    {
        $post = Post::find($id);
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Khong tim thay bai viet'
            ], 403);
        }
        if ($post->user_id != auth()->user()->id) {
            # code...
            return response([
                'message' => 'khong the cap xoa'
            ], 403);
        }
        $post->comment()->delete();
        $post->likes()->delete();
        $post->delete();
        return response([
            'message' => 'da xoa bai viet'
        ], 200);
    }
}

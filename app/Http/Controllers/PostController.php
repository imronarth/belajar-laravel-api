<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\PostDetailResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        // return response()->json(['data' => $post]);
        return PostResource::collection($posts);
    }
    public function show($id)
    {
        $post = Post::with('writer:id,username')->findOrFail($id);
        // return response()->json(['data' => $post]);
        return new PostDetailResource($post);
    }
    public function show2($id)
    {
        $post = Post::findOrFail($id);
        // return response()->json(['data' => $post]);
        return new PostDetailResource($post);
    }
}

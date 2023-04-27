<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['writer:id,username', 'comments:post_id,user_id,comment_content'])->get();
        // return response()->json(['data' => $post]);
        return PostDetailResource::collection($posts);
    }
    public function show($id)
    {
        $post = Post::with('writer:id,username', 'comments:post_id,user_id,comment_content')->findOrFail($id);
        return new PostDetailResource($post);
    }
    public function store(Request $request)
    {
        return $request->file;
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);
        $request['author'] = Auth::user()->id;
        $post = Post::create($request->all());
        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        if ($request->file) {
            $fileName = $this->random_string(30);
            $extension = $request->file->extension();

            Storage::putFileAs('image', $request->file, $fileName. '.'. $extension);
        }

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }
}

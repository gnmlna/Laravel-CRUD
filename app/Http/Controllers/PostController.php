<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function DeletePost(Post $post){
        if (auth()->user()->id === $post->user_id) {
            $post->delete();
        }

        return redirect('/');
    }

    public function EditPost(Post $post, Request $request){
        if (auth()->user()->id != $post->user_id) {
            return redirect('/');
        }

        $incomingFields = $request->validate([
            'title' => 'required|min:3|max:100',
            'body' => 'required|min:10|max:5000'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);
        return redirect('/');
    }


    public function showEditScreen(Post $post){
        if (auth()->user()->id != $post->user_id) {
            return redirect('/');
        }

        return view('edit-post', ['post' => $post]);
    }

    public function createPost(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required|min:3|max:100',
            'body' => 'required|min:10|max:5000'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        Post::create($incomingFields);

        return redirect('/');

    }
}

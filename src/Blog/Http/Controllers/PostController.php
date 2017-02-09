<?php

namespace Webup\LaravelHelium\Blog\Http\Controllers;

use Illuminate\Routing\Controller;
use Webup\LaravelHelium\Blog\Entities\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::whereNotNull('published_at')->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('helium::web.post.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::whereNotNull('published_at')->where('slug', $slug)->first();
        if (!$post) {
            abort(404);
        }

        return view('helium::web.post.show', [
            'post' => $post,
        ]);
    }
}

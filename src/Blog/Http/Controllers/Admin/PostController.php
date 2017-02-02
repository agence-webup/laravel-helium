<?php

namespace Webup\LaravelHelium\Blog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;
use Webup\LaravelHelium\Blog\Entities\Post;
use Webup\LaravelHelium\Blog\Values\State;
use Yajra\Datatables\Datatables;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('helium::post.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $postQuery = Post::query()->select('id', 'title', 'published_at', 'state');

        return Datatables::of($postQuery)
            ->rawColumns(['state', 'actions'])
            ->editColumn('published_at', function ($post) {
                return $post->published_at ? $post->published_at->format('d/m/Y H:i') : '';
            })
            ->editColumn('state', function ($post) {
                return view('helium::post.state', ['state' => $post->state])->render();
            })
            ->addColumn('actions', function ($post) {
                return view('helium::post.datatable-actions', ['post' => $post])->render();
            })
            ->orderColumn('published_at', 'published_at IS NULL $1, published_at $1')
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new Post();

        return view('helium::post.create', ['post' => $post]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all(), null);
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        Post::create($validator->getData());

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => trans('helium::messages.saved'),
            'level' => 'success',
        ]);

        return redirect()->route('admin.post.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        return view('helium::post.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validator = $this->validator($request->all(), $post);
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $post->fill($validator->getData());
        if ($request->image) {
            $post->image = 'blog/'.uniqid().'.'.$request->image->extension();
            $request->image->storeAs('public', $post->image);
        }
        $post->save();

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => trans('helium::messages.saved'),
            'level' => 'success',
        ]);

        return redirect()->route('admin.post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => trans('helium::messages.deleted'),
            'level' => 'success',
        ]);

        return redirect()->route('admin.post.index');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $post)
    {
        Validator::extend('slug', function ($attribute, $value, $parameters, $validator) {
            return $value == str_slug($value);
        });

        $states = implode(',', array_keys(State::list()));
        $uniqSlug = $post ? '|unique:posts,slug,'.$post->id : '|unique:posts,slug';

        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'image' => 'image|required_unless:state,'.State::DRAFT,
            'content' => '',
            'seo_title' => 'max:255',
            'seo_description' => 'max:255',
            'slug' => 'required|slug'.$uniqSlug,
            'state' => 'required|in:'.$states,
            'published_at' => 'date|required_if:state,'.State::SCHEDULED,
        ]);

        $validator->after(function ($validator) use ($post) {
            if ($validator->errors()->count()) {
                return;
            }

            $data = $validator->getData();

            // update published date
            if ($data['state'] == State::DRAFT) {
                $data['published_at'] = null;
            } elseif ($data['state'] == State::PUBLISHED) {
                if (!$post || !$post->state || $post->state->value() == State::DRAFT) {
                    $data['published_at'] = new \Carbon\Carbon();
                } else {
                    unset($data['published_at']);
                }
            }

            $validator->setData($data);
        });

        return $validator;
    }
}

<?php

namespace Webup\LaravelHelium\Page\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;
use Webup\LaravelHelium\Page\Entities\Page;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('helium::page.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $query = Page::query()->select('id', 'title', 'published');

        return Datatables::of($query)
            ->rawColumns(['published', 'actions'])
            ->editColumn('published', function ($page) {
                return view('helium::page.state', ['published' => $page->published])->render();
            })
            ->addColumn('actions', function ($page) {
                return view('helium::page.datatable-actions', ['page' => $page])->render();
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('helium::page.create', [
            'page' => new Page()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $page = new Page();
        $page->title = $request->get('title');
        $page->content = $request->get('content');
        $page->published = $request->has('published');
        $page->seo_title = $page->title;
        $page->slug = str_slug($page->title);
        $page->save();

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => trans('helium::messages.saved'),
            'level' => 'success',
        ]);

        return redirect()->route(helium_route_name('page.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Webup\LaravelHelium\Page\Entities\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Webup\LaravelHelium\Page\Entities\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('helium::page.edit', [
            'page' => $page
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Webup\LaravelHelium\Page\Entities\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $page->title = $request->get('title');
        $page->content = $request->get('content');
        $page->published = $request->has('published');
        $page->seo_title = $page->title;
        $page->slug = str_slug($page->title);
        $page->save();

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => trans('helium::messages.saved'),
            'level' => 'success',
        ]);

        return redirect()->route(helium_route_name('page.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Webup\LaravelHelium\Page\Entities\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Page $page)
    {
        $page->delete();

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => trans('helium::messages.deleted'),
            'level' => 'success',
        ]);

        return redirect()->route(helium_route_name('page.index'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'content' => '',
            'published_at' => 'nullable|boolean',
        ]);

        return $validator;
    }
}

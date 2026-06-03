<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $blogs =Blog::latest()->get();
        return view('blog.index',compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request -> validate([
            'title'=>'required',
            'content'=>'required|max:255'
        ]);

        Blog::create($request->all());
        return redirect()->route('blog.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(blog $blog)
    {
        return view('blog.view',compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(blog $blog)
    {
        return view('blog.update',compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, blog $blog)
    {
        $request -> validate([
            'title'=>'required',
            'content'=>'required|max:255'
        ]);

        $blog->update($request->all());
        return redirect()->route('blog.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(blog $blog)
    {
        $blog->delete();
         return redirect()->route('blog.index');
    }
}

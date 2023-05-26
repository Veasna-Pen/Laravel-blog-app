<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        $categories = Category::all();
        return View('post.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return View('post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|max:2048',
            'description' => 'required',
        ]);

        $imagePath = $request->file('image')->store('public/images');
        $post = new Post([
            'title' => $request->get('title'),
            'category_id' => $request->get('category_id'),
            'image' => $imagePath,
            'description' => $request->get('description'),
        ]);
        $post->save();

        return redirect('post')->with('success', 'Post Created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('post.edit', compact('post','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $image = $post->image;
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required',
        ]);

        if($request->hasFile('image')){
            Storage::delete($post->image);
            $image = $request->file('image')->store('public/images');
        }

        $post->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'image' => $image,
            'description' => $request->description
        ]);
        return redirect('post')->with('success', 'Post Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
      // Check if the image attribute is not null
        if ($post->image) {
            // Delete the image file
            Storage::delete($post->image);
        }
        $post->delete();
        return redirect('post')->with('success', 'Post Deleted successfully');

    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the post.
     *
     * This page is in adimn home page (route: admin_home)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new post
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.post.edit',
            [
                'title' => 'Add Post',
                'route' => route('admin.posts.store')
            ]
        );
    }


    /**
     * Store a newly created post in database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // validate the input data
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);
        $title = $request->input('title');
        $content = $request->input('content');

        // save to database
        DB::table('posts')->insert(
            [
                'title' => $title,
                'content' => $content,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime()
            ]
        );

        // redirect to admin home page with flash message
        return redirect()->route('admin.home')->with('success', 'New post has been created.');

    }

    /**
     * Show the form for editing the specified post.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        // Get post by id or throw 404 not found exception if you don't catch exception
        $post = Post::findOrFail($id);
        return view('admin.post.edit',
            [
                'post' => $post,
                'title' => 'Edit Post',
                'route' => route('admin.posts.update',['id' => $id])
            ]
        );
    }

    /**
     * Update the specified post in db.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get post by id or throw 404 not found exception if you don't catch exception
        $post = Post::findOrFail($id);

        // validate the input data
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $post->title = $request->title;
        $post->content = $request->content;

        // save to database
        $post->save();


        // redirect to admin home page with flash message
        return redirect()->route('admin.home')->with('success', 'Post has been updated.');

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Get post by id or throw 404 not found exception if you don't catch exception
        $post = Post::findOrFail($id);

        $post->delete();


        // flash message
        $request->session()->flash('success', 'Post has been deleted.');
        return response()->json([
           'redirect' => route('admin.home')
        ]);
    }




}

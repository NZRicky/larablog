<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // if specified tag, list post with that tag
        $tag = $request->query("tag");


        if ($tag) {
            $qb = DB::table('post_tags')
                ->join('posts', 'posts.id', '=', 'post_tags.post_id')
                ->join('tags', 'tags.id', '=', 'post_tags.tag_id')
                ->where('tags.name', $tag);
        } else {
            $qb = DB::table('posts');
        }

        // show public posts only
        if (!Auth::check()) {
            $qb = $qb->where('is_private', false);
        }

        $posts = $qb->simplePaginate(5);

        return view('home', [
            'posts' => $posts
        ]);
    }
}

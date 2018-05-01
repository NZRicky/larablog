<?php

/**
 * Created by PhpStorm.
 * User: ricky
 * Date: 24/02/18
 * Time: 11:34 PM
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
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
     * Dashboard for admin page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $posts = DB::table('posts')->paginate(10);
        return view('admin.home',['posts' => $posts]);
    }

}
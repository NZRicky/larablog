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
        $tags = $request->input('tags');

        // store tags if specified
        if ($tags) {
            $tagIds = $this->storeTags($tags);
        }

        // save to database
        $postId = DB::table('posts')->insertGetId(
            [
                'title' => $title,
                'content' => $content,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime()
            ]
        );

        // store tagId & postId in post_tags table
        if (isset($tagIds) && count($tagIds) > 0 && $postId > 0) {
            $this->storePostTags($tagIds, $postId);
        }

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

        $postTags = DB::table("post_tags")
            ->leftJoin('tags', 'post_tags.tag_id','=', 'tags.id')
            ->where('post_tags.post_id', $id)
            ->get();

        $tagAry = [];
        foreach($postTags as $pt) {
            $tagAry[] = $pt->name;
        }

        $post->tags = implode(", ", $tagAry);

        return view('admin.post.edit',
            [
                'post' => $post,
                'title' => 'Edit Post',
                'route' => route('admin.posts.update', ['id' => $id])
            ]
        );
    }

    /**
     * Update the specified post in db.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get post by id or throw 404 not found exception if you don't catch exception
        $post = Post::findOrFail($id);

        // get old tag ids
        $postTags = DB::table("post_tags")
            ->leftJoin('tags', 'post_tags.tag_id','=', 'tags.id')
            ->where('post_tags.post_id', $id)
            ->get();

        $oldTagIdAry = [];
        foreach($postTags as $pt) {
            $oldTagIdAry[] = $pt->tag_id;
        }


        // validate the input data
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $post->title = $request->title;
        $post->content = $request->content;

        // save to database
        $post->save();

        // process tag
        $tags = $request->input('tags');

        // store tags if specified
        if ($tags) {
            $newTagIdAry = $this->storeTags($tags);

            // store new tag id to post_tags table
            if (count($newTagIdAry) > 0) {
                $this->storePostTags($newTagIdAry, $id);
            }

            // remove deleted tag from tags & post_tags table
            $diffTagIdAry =  array_diff($oldTagIdAry, $newTagIdAry);

            DB::table('post_tags')->where('post_id', $id)
                ->whereIn('tag_id', $diffTagIdAry)
                ->delete();

            // TODO: need to do some cleanup to check if the deleted tag from tags table
            // if that tag is no longer used in somewhere else.

        }



        // redirect to admin home page with flash message
        return redirect()->route('admin.home')->with('success', 'Post has been updated.');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Get post by id or throw 404 not found exception if you don't catch exception
        $post = Post::findOrFail($id);

        // delete tags from post_tags table
        DB::table("post_tags")->where('post_id', $id)->delete();

        $post->delete();

        // flash message
        $request->session()->flash('success', 'Post has been deleted.');

        return response()->json([
            'redirect' => route('admin.home')
        ]);
    }


    /**
     * Insert tag to database if it's new and get inserted id
     * or return existing tag id
     *
     * @param $tags tags split by comma
     * @return array inserted tag id or existing tag id collection
     */
    private function storeTags($tags)
    {

        $tagsAry = explode(",", $tags);

        // use array to store the new tag & existing tag ids
        $tagsIdsAry = array();

        foreach ($tagsAry as $tagName) {
            $existTag = DB::table('tags')->where('name', $tagName)->first();

            if ($existTag) {
                // get existing tag id
                $tagId = $existTag->id;
            } else {
                // insert new tag to database & return inserted id.
                $tagId = DB::table('tags')->insertGetId([
                    'name' => $tagName
                ]);
            }
            $tagsIdsAry[] = $tagId;
        }
        return $tagsIdsAry;
    }

    /**
     * Insert data to post_tags table
     *
     * @param array $tagIds tag id collection
     * @param $postId post id
     */
    private function storePostTags(array $tagIds, $postId)
    {
        // make arrays to insert to post_tags table
        $insertDatas = [];
        foreach ($tagIds as $tagId) {

            // check if same post_id & tag_id in post_tags table
            $existingRecord = DB::table('post_tags')->where([
                ['post_id', '=', $postId],
                ['tag_id', '=', $tagId]
            ])->first();


            if (!$existingRecord) {
                $insertDatas[] = array(
                    'post_id' => $postId,
                    'tag_id' => $tagId
                );
            }
        }
        DB::table("post_tags")->insert($insertDatas);

    }


}

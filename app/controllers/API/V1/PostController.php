<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/20/2016
 * Time: 4:25 PM
 */

namespace API\V1;

use APIController;
use Comment;
use Like;
use Post;
use Rate;

class PostController extends APIController
{
    const ROOT_DOMAIN = "http://192.168.1.105:8000/";

    public function __construct()
    {
//        $this->beforeFilter('article.permView', array('except' => 'index'));
    }

    /**
     * Index
     *
     * @return Response
     */
    public function index()
    {

        $user = \API::user();

        \Log::info($user);

        $posts = new Post();

        if ($user != null) {
            $userId = $user->id;
            $posts = Post::where('created_by', $userId)->get();
        }

//        $posts = $posts
//            ->sort()
//            ->paginate(input_perpage());

        $this->res['data'] = $posts->toArray();

        return \Response::api($this->res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($post)
    {
        $post = Post::find($post);

        $post->increaseViewCount();

        $this->res['data'] = $post;

        return \Response::api($this->res);
    }

    public function store() {
        $post = new Post();

        $rules = Post::$rules;

        $validator = \Validator::make(\Input::all(), $rules);

        if ($validator->fails()) {
            $this->res['errors'] = $post->errors();
        }

        $post->fill(\Input::except('file'));
        $post->image = \Input::file('file');

        $destinationPath = public_path() . '/uploads/';
        $filename =  \Input::file('file')->getClientOriginalName();
        \Input::file('file')->move($destinationPath, $filename);

        $post->image = $destinationPath . $filename;

        $user = \Api::user();
        $post->created_by = $user->id;
        $post->updated_by = $user->id;

        if ($post->save()) {
            $this->res['data'] = $post->toArray();
        } else {
            $this->res['errors'] = $post->errors();
        }

        return \Response::api($this->res);
    }
}
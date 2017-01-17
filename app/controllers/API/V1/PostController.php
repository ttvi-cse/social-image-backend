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
use Location;

class PostController extends APIController
{

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
//            ->filter(\Input::all())
//            ->sort()
//            ->paginate(input_perpage());

        $this->res['data'] = $posts->toArray();

        return \Response::api($this->res);
    }

    public function all() {
        $posts = new Post();

        $posts = Post::all();

        $this->res['data'] = $posts->toArray();

        return \Response::api($this->res);
    }

    public function location($id) {
        $posts = new Post();

        $posts = Post::where('location', $id)->get();

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
        $this->res['data'] = $post;

        $post->increaseViewCount();

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

        if ($post->save()) {
            $this->res['data'] = $post->toArray();
        } else {
            $this->res['errors'] = $post->errors();
        }

        \Log::info($this->res);

        return \Response::api($this->res);
    }

    /**
     * Comments
     *
     * @return Response
     */
    public function comments($post)
    {
        $method = \Request::method();

        switch ($method) {
            case 'GET':
                $this->res['data'] = $post->comments()
                    ->default()
                    ->sort()
                    ->get()
                    ->toArray();
                break;
            case 'POST':
                $comment = \API::user()->comment($post, \Input::get('content', ''), \Input::get('parent_id', NULL));
                if($comment->errors()->isEmpty()){
                    $this->res['data'] = $comment->toArray();
                } else{
                    $this->res['errors'] = $comment->errors();
                }
                break;
            default:
                # code...
                break;
        }

        return \Response::api($this->res);
    }


    public function locations() {
        $method = \Request::method();

        switch ($method) {
            case 'GET':
                $location = new Location();

                $locations = Location::all();

                $this->res['data'] = $locations->toArray();

                break;
            case 'POST':
                $location = Location::firstOrNew(\Input::all());

                $rules = Location::$rules;

                $validator = \Validator::make(\Input::all(), $rules);

                if ($validator->fails()) {
                    $this->res['errors'] = $location->errors();
                }


                $location->fill(\Input::all());

                if ($location->save()) {
                    $this->res['data'] = $location->toArray();
                } else {
                    $this->res['errors'] = $location->errors();
                }

                break;
            default:
                # code...
                break;
        }

        return \Response::api($this->res);
    }
}
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
        $this->beforeFilter('article.permView', array('except' => 'index'));
    }

    /**
     * Index
     *
     * @return Response
     */
    public function index()
    {

        $user = \API::user();
        if ($user != null) {
            $post = $user->post();
        }

        \Log::info($post);

        $this->res['data'] = $post->toArray();
        
        return \Response::api($this->res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($article)
    {
        if($article->status_id != 1){
            \App::abort(404);
        }

        $article->load('created_by_user');
        $article->loadAttribute('has_quiz');
        $article->loadAttribute('has_poll');
        $this->res['data'] = $article->toArray();

        $article->increaseViewCount();

        return \Response::api($this->res);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/24/2016
 * Time: 11:32 AM
 */

namespace API\V1;

use APIController;
use Comment;

class CommentController extends APIController {
    //    Comment
    public function index($post_id)
    {
        $post_id = $post_id;
        $user_id = \Input::get('user_id');
        $content = \Input::get('content');

        if ($post_id == null || $user_id == null || $content == null) {
            return \Response::api(array(
                'message' => "Missing field.",
                'errors' => "",
                'data' => array()
            ));
        } else {
            $comment = new Comment;
            $comment->content = $content;
            $comment->post_id = $post_id;
            $comment->user_id = $user_id;
            $comment->save();
            return \Response::api(array(
                'message' => null,
                'errors' => null,
                'data' => Comment::where('content', $content)
                    ->where('post_id', $post_id)
                    ->where('user_id', $user_id)
                    ->orderBy('created_at', 'desc')
                    ->first()
            ));
        }
    }

    public function create($post_id)
    {
        $post_id = $post_id;
        if ($post_id == null) {
            return \Response::api(array(
                'message' => "Missing field.",
                'errors' => "",
                'data' => array()
            ));
        } else {
            return \Response::api(array(
                'message' => null,
                'errors' => null,
                'data' => Comment::where('post_id', $post_id)->get()
            ));
        }
    }
}
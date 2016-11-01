<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 7:05 PM
 */
use \Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run() {
        // Comments
        $users = User::all();
        foreach ($users as $user) {
            foreach (Post::all() as $art) {
                Comment::create([
                    'content' => $user->first_name . "'s comment.",
                    'target_id' => $art->id,
                    'created_by' => $user->id,
                    'updated_by' => $user->id
                ]);
            }
        }
    }
}
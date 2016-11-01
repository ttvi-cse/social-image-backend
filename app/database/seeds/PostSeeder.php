<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 6:17 PM
 */

use \Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            Post::create([
                'content' => 'Post content',
                'created_by' => $user->id,
                'updated_by' => $user->id
            ]);
        }


    }
}
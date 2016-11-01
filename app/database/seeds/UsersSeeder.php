<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 11:07 AM
 */

use \Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run() {

        $users = [
            [
                "password" => "##apollo##",
                "username" => "admin",
                "email"    => "admin@apollo.com",
                "first_name" => "Admin",
                "gender"  => 1,
            ],
            [
                "password" => "##apollo##",
                "username" => "ttvi",
                "email"    => "ttvi@apollo.com",
                "first_name" => "Tran",
                "last_name" => "Trung Vi",
                "gender"  => 1,
            ],
            [
                "password" => "##apollo##",
                "username" => "thanhloc",
                "email"    => "thanhloc@apollo.com",
                "first_name" => "Loc",
                "last_name" => "Thanh",
                "gender"  => 1,
            ],
            [
                "password" => "##apollo##",
                "username" => "numnine",
                "email"    => "numnine@apollo.com",
                "first_name" => "Linh",
                "last_name" => "Tinh",
                "gender"  => 1,
            ],
            [
                "password" => "##apollo##",
                "username" => "minhthuan",
                "email"    => "minhthuan@apollo.com",
                "first_name" => "Thuan",
                "last_name" => "Minh",
                "gender"  => 1,
            ],
            [
                "password" => "##apollo##",
                "username" => "cuong",
                "email"    => "cuong@apollo.com",
                "first_name" => "Cuong",
                "last_name" => "",
                "gender"  => 1,
            ],
            [
                "password" => "##apollo##",
                "username" => "yenquyen",
                "email"    => "yenquyen@apollo.com",
                "first_name" => "Quyen",
                "last_name" => "Tran Yen",
                "gender"  => 2,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use JetBrains\PhpStorm\NoReturn;

class UserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function addUser() :void
    {
        $users = [
            [
                'name' => 'Демидов Ярослав Дмитриевич',
                'author' => 'e0fd0cc2-8784-428a-8b49-74f931f19dd3',
            ],
        ];

        foreach ($users as $user){
            User::create($user);
        }
    }

    public function getUsers(): \Illuminate\Http\JsonResponse
    {
        $users=User::all();
        return response()->json($users);
    }}


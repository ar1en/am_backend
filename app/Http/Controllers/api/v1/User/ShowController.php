<?php

namespace App\Http\Controllers\api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class ShowController extends Controller
{
    public function __invoke($id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => sprintf("User with id:%s not found", $id)]);
        return response()->json($user);
    }
}

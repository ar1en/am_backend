<?php

namespace App\Http\Controllers\api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = $request->input('users');
        foreach($users as $user)
        {
            $item = User::create([
                'name'=> $user['name'],
                'author'=> $user['author'],
            ]);

            foreach ($user['vacancies'] as $vacancy)
            {
                $item->vacancies()->attach($vacancy,
                    [
                        'id' => Str::uuid(),
                        'author' => $user['author'],
                        'validFrom' => date("Y-m-d H:i:s", time()),
                        //'created_at' => date("Y-m-d H:i:s", time()),
                        //'updated_at' => date("Y-m-d H:i:s", time()),
                    ]);
            }
        }
        return response()->json(['message' => "ok"]);
    }
}

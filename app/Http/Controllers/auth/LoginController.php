<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\DBModels\Data\DLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(Request $request){

        // Validate the request
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        // Find the user
        $user = DLogins::where('login', $request->login)->first();
        //dd($user);
        // Verify the password and generate the token
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return response with token
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        // Return error message if invalid credentials
        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}

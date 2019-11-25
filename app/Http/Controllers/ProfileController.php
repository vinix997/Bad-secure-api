<?php

namespace App\Http\Controllers;

use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function profileView($user_id)
    {
        
        $user = User::find($user_id);
        
        $profile = UserProfile::where('user_id', $user_id)->first();

        $data = [
            'code' => 1,
            'message' => 'User Profile',
            'data' =>[
                'user' => $user,
                'profile' => $profile,
            ]
        ];
        
        return response()->json($data, 200);
    }
    
}

<?php

namespace App\Http\Controllers;

use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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

    public function updateProfile(Request $request, $user_id)
    {
        $user = User::find($user_id);

        $profile = UserProfile::where('user_id', $user_id)->first();

        if(!$profile)
        {
            $profile = new UserProfile();
            $profile->user_id = $user_id;
            $profile->save();
        }

        $avatar = null;

        if($request->file('avatar'))
        {
            if($profile->avatar)
            {
                $current_avatar_path = storage_path('avatar').'/'. $profile->avatar;
                if(\file_exists($current_avatar_path))
                {
                    unlink($current_avatar_path);
                }
            }
            $avatar = $user->username.'.jpg';
            $request->file('avatar')->move(storage_path('avatar'),$avatar);
        }
        try{
            DB::beginTransaction();
            $profile->avatar = $avatar;
            $profile->firstname = $request->firstname;
            $profile->lastname = $request->lastname;
            $profile->gender = $request->gender;
            $profile->save();
            DB::commit();
        } catch(Exception $e)
        {
            Log::error($e);
            DB::rollback();

            $response = [
                'code' => 500,
                'message' => 'Internal Server Error',
                'data' => []
            ];

            return response()->json($response, 500);
        }
        $data = [
            'code' => 1,
            'message' => 'Update Profile Success',
            'data' => $profile
        ];

        return response()->json($data, 200);
    }
    
}

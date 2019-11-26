<?php

namespace App\Http\Controllers;

use App\User;
use App\UserProfile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
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

    public function register(Request $request)
    {
        try{
            DB::beginTransaction();

            $newUser = new User();
            $newUser->username = $request->username;
            $newUser->email = $request->email;
            $newUser->password = $request->password;
            $newUser->save();
            DB::commit();
            
            $response = [
                'code' => 1,
                'message' => 'Register succeded',
                'data' => []
            ];

            return response()->json($response, 200);
        } catch(QueryException $e)
        {
            
            Log::error($e);
            DB::rollback();

            $response = [
                'code' => 2,
                'message' => 'Username/email is already in use',
                'data' => []
            ];

            return response()->json($response, 200);
        } catch(Exception $e)
        {
            Log::error($e);
            DB::rollback();
            $response = [
                'code' => 500,
                'message' => 'Internal Server Error',
                'data' => []
            ];
            return response()->json($response, 200);
        }
    }
    
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email',$email)->where('password',$password)->first();

        if(!$user)
        {
            $response = [
                'code' => 2,
                'message' => 'Email/Password is wrong',
                'data' => []
            ];
            return response()->json($response, 200);
        }
        try{
            DB::beginTransaction();

            $token = sha1($email.$password);
            $user->token = $token;
            $user->save();
            DB::commit();
        } catch(QueryException $e)
        {
            Log::error($e);
            DB::rollback();

            $response = [
                'code' => 500,
                'message' => 'Internal Server Error',
                'data' => []
            ];
            return response()->json($response, 200);
        }

        $response = [
            'code' => 1 ,
            'message' => 'Login Success',
            'data' => $user
        ];
        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $id = $request->id;
        $token = $request->token;

        $user = User::where('id', $id)->where('token', $token)->first();

        if(!$user)
        {
            $response = [
                'code' => 2,
                'message' => 'No user to log out',
                'data' => []
            ];
            return response()->json($response, 200);
        }

        try{
            DB::beginTransaction();

            $user->token = null;
            $user->save();

            DB::commit();
        } catch(QueryException $e)
        {
            Log::error($e);
            dd($e);
            DB::rollback();
            $response = [
                'code' => 500,
                'message' => 'Internal Server Error',
                'data' => []
            ];

            return response()->json($response, 500);
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
        $response = [
            'code' => 1,
            'message' => 'Logged out',
            'data' => $user
        ];
        return response()->json($response, 200);
    }
    //
}

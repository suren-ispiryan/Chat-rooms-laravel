<?php
namespace App\Http\Controllers;
// requests
use Illuminate\Http\Request;
// models
use App\Models\User;
// facades
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class SignController extends Controller
{
    public function register (Request $request)
    {
       // Recaptcha v3
        $response = Http::asForm()->post(env('RECAPTCHA_SITE_VERIFY'), [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request['g-recaptcha-response'],
            'remoteip' => $request->ip(),
        ]);
        // if not robot and password = confirmation of password
        if ($response['success'] === true) {
            // create token
            $str = mt_rand();
            $token = md5($str);
            // set user to db
            $user_info = [
                'name' => $request->fullName,
                'email' => $request->email,
                'password' => $request->password,
                'token' => $token
            ];
            $user = User::create($user_info);
            // keep user in auth
            if ($user) {
                Auth::login($user);
            }
        } else {
            return response()->json('error');
        }
        return response()->json($user_info);
    }

    public function login (Request $request)
    {
        $email = $request->email;
        $password =  $request->password;
        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response($token);
        } else {
            return response('');
        }
    }

    public function logOut ()
    {
        return response('success');
    }
}

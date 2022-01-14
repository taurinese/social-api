<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'image' => 'file'
        ]);
        
        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);
        
        if($request->image){
            $fileName = date('mdYHis') . uniqid() . $request->file('image')->getFilename();
            $uploadedFileUrl = $request->file('image')->storeOnCloudinaryAs('images', $fileName);
            $user->url_image = $uploadedFileUrl;
            $user->save();
        } 

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken,
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'user' => auth()->user()
        ]);
    }

    public function logout()
    {
        // auth()->user()->tokens()->delete();
        Auth::logout();
        return $this->success([], 'Disconnected');
    }
}
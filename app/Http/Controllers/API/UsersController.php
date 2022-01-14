<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Exception;

class UsersController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(auth()->user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->error('Cannot access this route',  405);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::find($id);
            if(!$user) throw new Exception('User not found');
            return $this->success($user);
        } catch (\Throwable $th) {
            return $this->error($th, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'email' => 'email|required_without:password',
            'password' => 'string|max:255|required_without:email',
            'image' => 'file'
        ]);

        try {
            $user = User::find($id);
            if(!$user){
                throw new Exception('User not found');
            }
            if($request->email) $user->email = $request->email;
            if($request->password) $user->password = bcrypt($request->password);
            if($request->image){
                $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
                $user->url_image = $uploadedFileUrl;
            } 
            $user->save();
            return $this->success($user);
        } catch (\Throwable $th) {
            return $this->error($th, 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->error('Cannot access this route',  405);
    }

}

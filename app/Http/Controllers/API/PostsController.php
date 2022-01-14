<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class PostsController extends Controller
{
    use ApiResponser;
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return $this->success(Post::with('user')->with('comments')->withCount('likes')->orderBy('created_at', 'desc')->get());
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $attr = $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'file'
        ]);
        $post = Post::create([
            'content' => $attr['content'],
            'user_id' => auth()->id()
        ]);
        
        if($request->image){
            $fileName = date('mdYHis') . uniqid() . $request->file('image')->getFilename();
            $uploadedFileUrl = $request->file('image')->storeOnCloudinaryAs('images', $fileName)->getSecurePath();
            $post->url_image = $uploadedFileUrl;
            $post->save();
        } 
        
        
        return $this->success($post, 'Post created');
        
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {   
        $post = Post::where('id', $id)->with('user')->with('comments')->withCount('likes')->get();
        if(!$post) return $this->error('Post not found', 404);
        return $this->success($post);
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
        return $this->error('Cannot access this resource', 404);
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $post = Post::find($id);
        if(!$post) return $this->error('Post not found', 404);
        $post->delete();
        return $this->success($post);
    }
}

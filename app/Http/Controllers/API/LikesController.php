<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class LikesController extends Controller
{

    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|max:255|in:post,comment',
            'post_id' => 'required_without:comment_id',
            'comment_id' => 'required_without:post_id'
        ]);
        if(Like::where('user_id', auth()->id())->where('type', $request->type)->where('post_id', $request->post_id)->where('comment_id', $request->comment_id)->exists())
            return $this->error('Already liked', 409);
        $like = Like::create([
            'type' => $request->type,
            'user_id' => auth()->id(),
            'post_id' => $request->post_id ?? null,
            'comment_id' => $request->comment_id ?? null
        ]);
        return $this->success($like);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->error('Cannot access this resource', 404);
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
        $like = Like::find($id);
        if(!$like) return $this->error('Like not found', 404);
        $like->delete();
        return $this->success($like);
    }
}

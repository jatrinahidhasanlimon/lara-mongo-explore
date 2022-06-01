<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    //
    public function index(){
        // return 'hey';
        return Post::all();
    }
    public function store(Request $request){
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->slug = $request->slug;
 
        $post->save();
 
        return response()->json(["result" => "ok"], 200);
    }
    public function destroy($postId)
    {
        $post = Post::find($postId);
        $post->delete();

        return response()->json(["result" => "ok"], 200);       
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
                'vendor_id' => 'required|exists:vendors,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $post = new Post();
            $post->content = $request->input('content');
            $post->vendor_id = $request->input('vendor_id');
            if ($request->hasFile('post_image')) {
                $image = $request->file('post_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/posts'), $imageName);
                $post->post_image = $imageName;
            }
            $post->save();
            return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
            // return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        try {
            $post = Post::find($request->id);
            if (!$post) {
                return response()->json(['error' => 'Post not found'], 404);
            }
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
                'vendor_id' => 'required|exists:vendors,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $post->content = $request->input('content');
            $post->vendor_id = $request->input('vendor_id');
            if ($request->hasFile('post_image')) {
                $image = $request->file('post_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/posts'), $imageName);
                $post->post_image = $imageName;
            }
            $post->save();
            return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}

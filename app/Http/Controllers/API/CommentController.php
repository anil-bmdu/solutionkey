<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:posts,id',
                'content' => 'required|string',
                'user_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $comment = new Comment();
            $comment->post_id = $request->input('post_id');
            $comment->content = $request->input('content');
            $comment->comment_user_id = $request->input('user_id');
            $comment->save();
            return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}

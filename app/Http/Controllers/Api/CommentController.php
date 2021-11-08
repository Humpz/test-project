<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index()
    {
//        $comments = Comment::where('post_id',1)->orderBy('created_at', 'desc')->get();
//        return CommentResource::collection($comments)->flatten();
        return CommentResource::collection(Comment::whereNull('parent_id')->with('replies.replies')->orderBy('created_at', 'desc')->get())->flatten();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
            'comment' => ['required'],
        ])->validate();

        try {
            $comment = new Comment;
            $comment->tier = 1;
            $comment->name = $request->name;
            $comment->comment = $request->comment;
            $comment->post_id = 1; //Assuming Post Id is 1
            $comment->save();
            return response()->json('Commented Successfully', 200);

        }catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function storeReply(Request $request)
    {
        Validator::make($request->all(), [
            'parent_id' => ['required'],
            'tier' => ['required'],
            'name' => ['required'],
            'comment' => ['required'],
        ])->validate();

        try {
            $comment = new Comment;
            $comment->parent_id = $request->parent_id;
            $comment->tier = $request->tier+1;
            $comment->name = $request->name;
            $comment->comment = $request->comment;
            $comment->post_id = 1; //Assuming Post Id is 1
            $comment->save();
            return response()->json('Replied Successfully', 200);

        }catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $query = Comment::with('article');

        // Scope to reporter's articles only if logged in user is a reporter
        if (auth()->user()->isReporter()) {
            $query->whereHas('article', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        $comments = $query->latest()->paginate(15);

        return view('admin.comments.index', compact('comments'));
    }

    public function toggleApprove(Comment $comment)
    {
        if (auth()->user()->isReporter() && $comment->article->user_id !== auth()->id()) {
            abort(403, 'You are only authorized to moderate comments on your own articles.');
        }

        $comment->update(['is_approved' => !$comment->is_approved]);

        return back()->with('success', 'Comment status updated successfully!');
    }

    public function destroy(Comment $comment)
    {
        if (auth()->user()->isReporter() && $comment->article->user_id !== auth()->id()) {
            abort(403, 'You are only authorized to delete comments on your own articles.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
}

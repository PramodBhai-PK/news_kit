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
            abort(403, 'आपको केवल अपने आर्टिकल्स के कमेंट मॉडरेट करने की अनुमति है।');
        }

        $comment->update(['is_approved' => !$comment->is_approved]);

        return back()->with('success', 'कमेंट स्टेटस अपडेट हो गया है!');
    }

    public function destroy(Comment $comment)
    {
        if (auth()->user()->isReporter() && $comment->article->user_id !== auth()->id()) {
            abort(403, 'आपको केवल अपने आर्टिकल्स के कमेंट हटाने की अनुमति है।');
        }

        $comment->delete();

        return back()->with('success', 'कमेंट हटा दिया गया है।');
    }
}

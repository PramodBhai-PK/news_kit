@extends('layouts.admin')

@section('title', Auth::user()->isReporter() ? 'मेरी टिप्पणियां' : 'पाठक टिप्पणियां (Comments)')
@section('page_title', Auth::user()->isReporter() ? 'मेरी ख़बरों पर टिप्पणियां (My Articles Comments)' : 'पाठक टिप्पणियां मॉडरेशन (Comments Moderation)')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-900 text-sm">
            {{ Auth::user()->isReporter() ? 'मेरी ख़बरों पर आई टिप्पणियां' : 'सभी टिप्पणियां (All Reader Comments)' }}
        </h3>
        <span class="text-xs text-slate-500 font-semibold">{{ $comments->total() }} कुल टिप्पणियां</span>
    </div>

    <div class="divide-y divide-slate-100">
        @forelse($comments as $comment)
            <div class="p-5 flex flex-col sm:flex-row items-start justify-between gap-4 hover:bg-slate-50/80 transition-colors">
                <div class="space-y-1.5 max-w-3xl">
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-slate-900 text-sm">{{ $comment->user_name }}</span>
                        <span class="text-xs text-slate-400">&lt;{{ $comment->user_email }}&gt;</span>
                        <span class="text-[11px] text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                        @if($comment->is_approved)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">स्वीकृत (Approved)</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">लंबित (Pending)</span>
                        @endif
                    </div>

                    <p class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                        {{ $comment->comment }}
                    </p>

                    <div class="text-xs text-slate-500">
                        <span>ख़बर:</span>
                        <a href="{{ route('portal.article', $comment->article->slug) }}" target="_blank" class="font-semibold text-brand-primary hover:underline">
                            {{ $comment->article->title }}
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <form action="{{ route('admin.comments.toggle', $comment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $comment->is_approved ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                            {{ $comment->is_approved ? 'अस्वीकृत करें' : 'स्वीकृत करें' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('क्या आप इस कमेंट को हटाना चाहते हैं?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100" title="हटाएं">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-slate-400 text-sm">अभी कोई टिप्पणी नहीं आई है।</div>
        @endforelse
    </div>

    <div class="p-4 border-t border-slate-100">
        {{ $comments->links() }}
    </div>
</div>
@endsection

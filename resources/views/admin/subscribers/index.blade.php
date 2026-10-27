@extends('layouts.admin')

@section('title', 'न्यूज़लेटर सब्सक्राइबर्स')
@section('page_title', 'न्यूज़लेटर सब्सक्राइबर्स (Newsletter Subscribers)')

@section('content')
<div class="space-y-6">

    <!-- 1. Metric Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">कुल ग्राहक (Total Subscribers)</span>
                <span class="text-3xl font-black text-slate-900 mt-1 block">{{ number_format($totalCount) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-primary/10 text-brand-primary flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">सक्रिय ग्राहक (Active)</span>
                <span class="text-3xl font-black text-emerald-600 mt-1 block">{{ number_format($activeCount) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">निष्क्रिय (Unsubscribed)</span>
                <span class="text-3xl font-black text-slate-400 mt-1 block">{{ number_format($totalCount - $activeCount) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
        </div>
    </div>

    <!-- 2. Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.subscribers.index') }}" method="GET" class="relative w-full sm:w-80">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="ईमेल खोजें (Search email)..." 
                   class="w-full bg-slate-50 text-slate-900 text-xs rounded-xl pl-9 pr-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
            <span class="absolute left-3 top-2.5 text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
        </form>

        <span class="text-xs text-slate-400 font-semibold">
            कुल {{ $subscribers->total() }} रिकॉर्ड मिले
        </span>
    </div>

    <!-- 3. Subscribers Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-6">ईमेल पता (Email)</th>
                        <th class="py-3.5 px-6">स्थिति (Status)</th>
                        <th class="py-3.5 px-6">IP पता (IP Address)</th>
                        <th class="py-3.5 px-6">पंजीकरण तिथि (Subscribed On)</th>
                        <th class="py-3.5 px-6 text-right">कार्रवाई (Action)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($subscribers as $sub)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 text-xs font-mono font-bold">
                                    {{ strtoupper(substr($sub->email, 0, 1)) }}
                                </span>
                                <span>{{ $sub->email }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <form action="{{ route('admin.subscribers.toggle', $sub->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold transition-all {{ $sub->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $sub->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $sub->is_active ? 'सक्रिय (Active)' : 'निष्क्रिय (Inactive)' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 font-mono text-slate-500 text-[11px]">
                                {{ $sub->ip_address ?: 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-slate-500">
                                <div>{{ $sub->created_at->format('d M, Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $sub->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('admin.subscribers.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('क्या आप इस ईमेल को सूची से हटाना चाहते हैं?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="हटाएं">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="block text-sm font-semibold">कोई न्यूज़लेटर ग्राहक नहीं मिला।</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

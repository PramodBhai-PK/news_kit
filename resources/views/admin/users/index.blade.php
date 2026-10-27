@extends('layouts.admin')

@section('title', 'टीम व यूजर प्रबंधन (Team & Roles)')
@section('page_title', 'टीम व यूज़र्स प्रबंधन (Team, Editors & Reporters)')

@section('content')
<div class="space-y-8">

    <!-- 1. Role Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- All Team -->
        <a href="{{ route('admin.users.index') }}" 
           class="p-4 rounded-2xl border transition-all {{ !request('role') ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:border-slate-300' }}">
            <span class="text-xs font-bold uppercase tracking-wider block opacity-80">कुल टीम (Total)</span>
            <span class="text-2xl font-black mt-1 block">{{ $roleCounts['all'] }}</span>
        </a>

        <!-- Editors -->
        <a href="{{ route('admin.users.index', ['role' => 'editor']) }}" 
           class="p-4 rounded-2xl border transition-all {{ request('role') === 'editor' ? 'bg-purple-600 text-white border-purple-600 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:border-purple-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider block opacity-80">संपादक (Editors)</span>
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            </div>
            <span class="text-2xl font-black mt-1 block">{{ $roleCounts['editor'] }}</span>
        </a>

        <!-- Reporters -->
        <a href="{{ route('admin.users.index', ['role' => 'reporter']) }}" 
           class="p-4 rounded-2xl border transition-all {{ request('role') === 'reporter' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:border-emerald-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider block opacity-80">रिपोर्टर्स (Reporters)</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <span class="text-2xl font-black mt-1 block">{{ $roleCounts['reporter'] }}</span>
        </a>

        <!-- Publishers -->
        <a href="{{ route('admin.users.index', ['role' => 'publisher']) }}" 
           class="p-4 rounded-2xl border transition-all {{ request('role') === 'publisher' ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:border-blue-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider block opacity-80">प्रकाशक (Publishers)</span>
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            </div>
            <span class="text-2xl font-black mt-1 block">{{ $roleCounts['publisher'] }}</span>
        </a>

        <!-- Admins -->
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
           class="p-4 rounded-2xl border transition-all {{ request('role') === 'admin' ? 'bg-rose-600 text-white border-rose-600 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:border-rose-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider block opacity-80">एडमिन्स (Admins)</span>
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            </div>
            <span class="text-2xl font-black mt-1 block">{{ $roleCounts['admin'] }}</span>
        </a>
    </div>

    <!-- 2. Main Team Grid: Add Form (5 cols) + Team List (7 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left: Add New Staff Member Form (5 cols) -->
        @if(auth()->user()->isAdmin())
            <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-5">
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-base">नया टीम सदस्य जोड़ें (Add Staff Member)</h3>
                    <p class="text-xs text-slate-500">संपादक, रिपोर्टर या प्रकाशक को रोल आधारित एक्सेस दें।</p>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">पूरा नाम (Full Name) *</label>
                        <input type="text" name="name" required placeholder="उदा. राहुल वर्मा" 
                               value="{{ old('name') }}"
                               class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">ईमेल एड्रेस *</label>
                            <input type="email" name="email" required placeholder="rahul@newskit.com" 
                                   value="{{ old('email') }}"
                                   class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">फ़ोन नंबर (Phone)</label>
                            <input type="text" name="phone" placeholder="+91 98765 00000" 
                                   value="{{ old('phone') }}"
                                   class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">रोल (Assigned Role) *</label>
                            <select name="role" required class="w-full bg-slate-50 text-slate-900 text-xs font-bold rounded-xl px-3 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                                <option value="reporter" selected>न्यूज़ रिपोर्टर (Reporter)</option>
                                <option value="editor">वरिष्ठ संपादक (Editor)</option>
                                <option value="publisher">डिजिटल प्रकाशक (Publisher)</option>
                                <option value="admin">प्रधान संपादक (Super Admin)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">लॉगिन पासवर्ड *</label>
                            <input type="password" name="password" required placeholder="न्यूनतम 6 अक्षर" 
                                   class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">संक्षिप्त बायो (Author Bio)</label>
                        <textarea name="bio" rows="2" placeholder="पत्रकारिता का अनुभव, कवरेज का क्षेत्र..." 
                                  class="w-full bg-slate-50 text-slate-900 text-xs rounded-xl p-3 border border-slate-200 focus:border-brand-primary outline-none">{{ old('bio') }}</textarea>
                    </div>

                    <!-- Avatar Upload / URL -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">प्रोफ़ाइल फ़ोटो (Avatar)</label>
                        <input type="file" name="avatar_file" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-brand-primary cursor-pointer w-full">
                        <input type="url" name="avatar_url" placeholder="या इमेज URL: https://images.unsplash.com/..." class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                    </div>

                    <button type="submit" class="w-full bg-brand-primary hover:brightness-110 text-white font-extrabold text-xs py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <span>टीम सदस्य सहेजें (Create Account)</span>
                    </button>
                </form>
            </div>
        @endif

        <!-- Right: Team Members Directory (7 cols or 12 cols) -->
        <div class="{{ auth()->user()->isAdmin() ? 'lg:col-span-7' : 'lg:col-span-12' }} bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            
            <!-- Filter & Search Bar -->
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">टीम डायरेक्टरी (Staff Directory)</h3>
                    <p class="text-xs text-slate-400">सभी रिपोर्टर्स, एडिटर्स और पब्लिशर्स की सूची।</p>
                </div>

                <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-64">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    <input type="text" name="q" placeholder="नाम, ईमेल या फ़ोन खोजें..." 
                           value="{{ request('q') }}"
                           class="w-full bg-slate-50 text-xs rounded-xl pl-8 pr-3 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                    <span class="absolute left-2.5 top-2.5 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </form>
            </div>

            <!-- Members List -->
            <div class="divide-y divide-slate-100">
                @forelse($users as $staff)
                    <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors">
                        
                        <div class="flex items-start gap-3.5 min-w-0">
                            <!-- Avatar -->
                            <img src="{{ $staff->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" 
                                 alt="{{ $staff->name }}" 
                                 class="w-12 h-12 rounded-full object-cover ring-2 ring-slate-100 shrink-0">

                            <!-- Info -->
                            <div class="space-y-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $staff->name }}</h4>
                                    
                                    <!-- Role Badge -->
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $staff->role_badge_class }}">
                                        {{ $staff->role_label }}
                                    </span>

                                    @if($staff->is_active)
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700">सक्रिय</span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700">निष्क्रिय</span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                    <span>✉️ {{ $staff->email }}</span>
                                    @if($staff->phone)
                                        <span>📞 {{ $staff->phone }}</span>
                                    @endif
                                    <span>📰 <strong>{{ $staff->articles_count }}</strong> आर्टिकल्स</span>
                                </div>

                                @if($staff->bio)
                                    <p class="text-xs text-slate-400 line-clamp-1 italic">
                                        {{ $staff->bio }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Actions for Admin -->
                        @if(auth()->user()->isAdmin())
                            <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                <!-- Digital Press ID Card View & Print -->
                                <a href="{{ route('admin.users.press-card', $staff->id) }}" 
                                   class="px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition-colors flex items-center gap-1 shadow-2xs"
                                   title="प्रेस पहचान पत्र देखें व प्रिंट करें">
                                    <span>🪪 प्रेस कार्ड</span>
                                </a>

                                <!-- Status Toggle -->
                                @if($staff->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle', $staff->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="px-2.5 py-1 rounded-lg text-xs font-bold transition-colors {{ $staff->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                            {{ $staff->is_active ? 'रोकें (Pause)' : 'सक्रिय करें' }}
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.users.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('क्या आप इस सदस्य को वाकई हटाना चाहते हैं?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100" title="हटाएं">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] font-bold text-slate-400 px-2">आप (You)</span>
                                @endif
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 text-sm space-y-2">
                        <p class="font-bold">इस फ़िल्टर में कोई टीम सदस्य नहीं मिला।</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

    </div>

</div>
@endsection

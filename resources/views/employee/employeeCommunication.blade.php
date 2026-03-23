<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Directory | Employee Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body{font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;transition:margin-left .3s ease}
        main{transition:margin-left .3s ease}
        aside:not(:hover)~main{margin-left:4rem}
        aside:hover~main{margin-left:14rem}
        .messenger-shell{background:linear-gradient(180deg,#171717 0%,#202020 100%)}
        .messenger-sidebar{background:linear-gradient(180deg,#161616 0%,#1c1c1c 100%)}
        .messenger-thread{background:radial-gradient(circle at top right, rgba(88,28,135,.25), transparent 24%),linear-gradient(180deg,#202020 0%,#181818 100%)}
        .messenger-scroll::-webkit-scrollbar{width:8px}
        .messenger-scroll::-webkit-scrollbar-thumb{background:#4b5563;border-radius:999px}
        .messenger-scroll::-webkit-scrollbar-track{background:transparent}
    </style>
</head>
<body class="bg-[radial-gradient(circle_at_top,_#f0fdf4,_#eff6ff_35%,_#f8fafc_75%)] text-slate-900">
@php
    $directoryMembers = collect($admins ?? []);
    $conversationSummaries = collect($conversationSummaries ?? []);
    $selectedParticipant = $selectedParticipant ?? null;
    $selectedConversation = $selectedConversation ?? null;
    $messages = collect(optional($selectedConversation)->messages ?? []);
    $availableCount = $directoryMembers->filter(fn ($member) => in_array(strtolower(trim((string) ($member->status ?? ''))), ['approved', 'available'], true))->count();
@endphp
<div class="flex min-h-screen">
    @include('components.employeeSideBar')
    <main class="flex-1 ml-16 transition-all duration-300">
        @include('components.employeeHeader.communicationHeader')
        <div class="px-4 pb-8 pt-6 md:px-8 md:pb-10">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif
            @if (session('warning'))
                <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">{{ session('warning') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
            @endif

            <section class="rounded-[2rem] border border-white/70 bg-white/75 p-5 shadow-[0_18px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl md:p-6">
                <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700">Directory Controls</p>
                        <h3 class="mt-2 text-2xl font-black tracking-tight text-slate-900">Find people by name, role, or status.</h3>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Use the search box and quick filters to narrow the list without leaving the page.</p>
                    </div>
                    <div class="flex flex-col gap-3 xl:min-w-[560px] xl:max-w-[640px] xl:flex-row">
                        <label class="group flex flex-1 items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <i class="fa fa-search text-slate-400"></i>
                            <input id="directory-search" type="text" placeholder="Search by employee name, role, or account type" class="w-full bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400">
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="directory-filter rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white" data-filter="all">All <span class="ml-1 text-white/70">{{ $directoryMembers->count() }}</span></button>
                            <button type="button" class="directory-filter rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700" data-filter="available">Available <span class="ml-1 text-emerald-500">{{ $availableCount }}</span></button>
                            <button type="button" class="directory-filter rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600" data-filter="other">Other</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-6">
                <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700">Directory Cards</p>
                        <h3 class="mt-2 text-2xl font-black tracking-tight text-slate-900">Meet the people behind the system.</h3>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/90 px-4 py-2 text-sm text-slate-600 shadow-sm">
                        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        <span id="directory-results-count">{{ $directoryMembers->count() }}</span>
                        <span>visible member<span id="directory-results-plural">{{ $directoryMembers->count() === 1 ? '' : 's' }}</span></span>
                    </div>
                </div>
                <div id="directory-grid" class="grid grid-cols-1 gap-6 md:grid-cols-2 2xl:grid-cols-3">
                    @foreach($admins as $admin)
                        @php
                            $fullName = trim(implode(' ', array_filter([$admin->first_name ?? '', $admin->middle_name ?? '', $admin->last_name ?? ''])));
                            $initials = strtoupper(substr((string) ($admin->first_name ?? ''), 0, 1) . substr((string) ($admin->last_name ?? ''), 0, 1));
                            $displayStatus = trim((string) ($admin->status ?? ''));
                            if (strtolower($displayStatus) === 'approved') { $displayStatus = 'Available'; }
                            $isAvailable = strtolower($displayStatus) === 'available';
                            $jobRole = trim((string) ($admin->job_role ?? 'Administrator'));
                            $role = trim((string) ($admin->role ?? 'Admin'));
                            $email = trim((string) ($admin->email ?? ''));
                            $adminUnreadCount = (int) ($admin->unread_message_count ?? 0);
                            $adminHasUnreadMessages = (bool) ($admin->has_unread_messages ?? false);
                        @endphp
                        <article class="directory-card rounded-[2rem] border border-white/80 bg-white/90 p-6 shadow-[0_18px_60px_rgba(15,23,42,0.08)]" data-name="{{ strtolower($fullName) }}" data-role="{{ strtolower($jobRole.' '.$role) }}" data-status="{{ $isAvailable ? 'available' : 'other' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-20 w-20 items-center justify-center rounded-[1.6rem] bg-gradient-to-br from-emerald-500 via-teal-500 to-sky-500 text-2xl font-black text-white">{{ $initials !== '' ? $initials : 'AD' }}</div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $role !== '' ? $role : 'Admin' }}</p>
                                        <div class="mt-1 flex flex-wrap items-center gap-2">
                                            <h4 class="text-xl font-black leading-tight text-slate-900">{{ $fullName !== '' ? $fullName : 'Admin User' }}</h4>
                                            @if ($adminHasUnreadMessages)
                                                <span class="inline-flex items-center rounded-full bg-rose-500 px-2.5 py-1 text-[11px] font-bold text-white">{{ $adminUnreadCount > 99 ? '99+' : $adminUnreadCount }} unread</span>
                                            @endif
                                        </div>
                                        <p class="mt-1 text-sm font-medium text-slate-500">{{ $jobRole }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $isAvailable ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    <span class="h-2 w-2 rounded-full {{ $isAvailable ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $displayStatus !== '' ? $displayStatus : 'No Status' }}
                                </span>
                            </div>
                            <div class="mt-5 rounded-[1.5rem] border border-slate-200 bg-white/80 px-4 py-4">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Contact</p>
                                <p class="mt-1 truncate text-sm text-slate-600">{{ $email !== '' ? $email : 'Email not available' }}</p>
                            </div>
                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="mailto:{{ $email }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white"><i class="fa fa-user"></i>View Profile</a>
                                <a href="{{ route('employee.employeeCommunication', ['user' => $admin->id]) }}#chat-panel" class="relative inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                                    @if ($adminHasUnreadMessages)
                                        <span class="absolute -right-2 -top-2 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">{{ $adminUnreadCount > 99 ? '99+' : $adminUnreadCount }}</span>
                                    @endif
                                    <i class="fa fa-comment"></i>Connect
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            @if ($selectedParticipant)
                            @php
                                $participantName = trim(implode(' ', array_filter([$selectedParticipant->first_name ?? null, $selectedParticipant->middle_name ?? null, $selectedParticipant->last_name ?? null])));
                                $participantName = $participantName !== '' ? $participantName : (string) ($selectedParticipant->email ?? 'Admin');
                                $participantInitials = strtoupper(substr(trim((string) ($selectedParticipant->first_name ?? 'A')), 0, 1).substr(trim((string) ($selectedParticipant->last_name ?? '')), 0, 1));
                            @endphp
                            <div class="fixed bottom-5 right-5 z-50 w-[370px] max-w-[calc(100vw-1.5rem)] overflow-hidden rounded-t-2xl rounded-b-[1.35rem] border border-slate-800 bg-[#1f1f1f] shadow-[0_30px_80px_rgba(0,0,0,0.45)]">
                            <div class="border-b border-slate-700 bg-[#242424] px-4 py-3">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="relative flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-slate-300 to-slate-500 text-sm font-black text-slate-950">{{ $participantInitials !== '' ? $participantInitials : 'AD' }}
                                            <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-[#242424] bg-emerald-400"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-xl font-bold text-white">{{ $participantName }}</p>
                                            <p class="text-sm text-slate-400">Active now</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 text-violet-400">
                                        <a href="{{ route('employee.employeeCommunication') }}" class="text-violet-400"><i class="fa-solid fa-xmark"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div id="message-thread" class="messenger-scroll h-[340px] space-y-4 overflow-y-auto bg-[#1f1f1f] px-4 py-4">
                                @forelse ($messages as $message)
                                    @php
                                        $isOwnMessage = (int) ($message->sender_user_id ?? 0) === (int) auth()->id();
                                        $senderName = trim(implode(' ', array_filter([$message->sender->first_name ?? null, $message->sender->last_name ?? null])));
                                        $senderName = $senderName !== '' ? $senderName : ($isOwnMessage ? 'You' : $participantName);
                                    @endphp
                                    <div class="flex items-end gap-2 {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                                        @unless ($isOwnMessage)
                                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-slate-300 to-slate-500 text-[9px] font-bold text-slate-950">{{ $participantInitials !== '' ? $participantInitials : 'AD' }}</div>
                                        @endunless
                                        <div class="max-w-[78%] rounded-[1.45rem] px-4 py-2.5 shadow-sm {{ $isOwnMessage ? 'bg-gradient-to-r from-violet-600 to-fuchsia-500 text-white' : 'bg-[#303030] text-slate-100' }}">
                                            <p class="whitespace-pre-line text-sm leading-6">{{ $message->body }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex min-h-[16rem] items-center justify-center">
                                        <div class="max-w-sm text-center">
                                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-800 text-violet-400"><i class="fa-solid fa-comment-dots text-xl"></i></div>
                                            <h4 class="mt-4 text-lg font-black text-white">Start the conversation.</h4>
                                            <p class="mt-2 text-sm leading-6 text-slate-400">Your first message creates the chat thread and lets the admin reply from their own inbox.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <form method="POST" action="{{ route('employee.communication.send') }}" class="border-t border-slate-700 bg-[#1f1f1f] px-4 py-3">
                                @csrf
                                <input type="hidden" name="participant_user_id" value="{{ $selectedParticipant->id }}">
                                @if ($selectedConversation)<input type="hidden" name="conversation_id" value="{{ $selectedConversation->id }}">@endif
                                <div class="flex items-end gap-3">
                                    <div class="flex items-center pb-2 text-blue-500">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                    <div class="flex-1 rounded-full bg-[#3a3a3a] px-4 py-2">
                                        <textarea name="body" rows="1" class="w-full resize-none bg-transparent text-sm text-white outline-none placeholder:text-slate-500" placeholder="Aa">{{ old('body') }}</textarea>
                                    </div>
                                    <div class="flex items-center pb-2 text-blue-500">
                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-violet-600 text-white"><i class="fa-solid fa-paper-plane text-xs"></i></button>
                                    </div>
                                </div>
                            </form>
                            </div>
            @endif
        </div>
    </main>
</div>
<script>
const sidebar=document.querySelector('aside');const main=document.querySelector('main');if(sidebar&&main){sidebar.addEventListener('mouseenter',function(){main.classList.remove('ml-16');main.classList.add('ml-56')});sidebar.addEventListener('mouseleave',function(){main.classList.remove('ml-56');main.classList.add('ml-16')})}
const searchInput=document.getElementById('directory-search');const filterButtons=Array.from(document.querySelectorAll('.directory-filter'));const directoryCards=Array.from(document.querySelectorAll('.directory-card'));const resultsCount=document.getElementById('directory-results-count');const resultsPlural=document.getElementById('directory-results-plural');let activeFilter='all';
function applyDirectoryFilters(){const query=(searchInput?.value||'').trim().toLowerCase();let visibleCount=0;directoryCards.forEach((card)=>{const name=card.dataset.name||'';const role=card.dataset.role||'';const status=card.dataset.status||'';const matchesQuery=query===''||name.includes(query)||role.includes(query);const matchesStatus=activeFilter==='all'||status===activeFilter;const isVisible=matchesQuery&&matchesStatus;card.classList.toggle('hidden',!isVisible);if(isVisible){visibleCount+=1}});if(resultsCount){resultsCount.textContent=String(visibleCount)}if(resultsPlural){resultsPlural.textContent=visibleCount===1?'':'s'}}
filterButtons.forEach((button)=>{button.addEventListener('click',function(){activeFilter=button.dataset.filter||'all';filterButtons.forEach((item)=>{item.classList.remove('bg-slate-900','text-white','bg-emerald-600');item.classList.add('bg-slate-100','text-slate-600')});if(activeFilter==='available'){button.classList.remove('bg-slate-100','text-slate-600','bg-emerald-50','text-emerald-700');button.classList.add('bg-emerald-600','text-white')}else{button.classList.remove('bg-slate-100','text-slate-600');button.classList.add('bg-slate-900','text-white')}filterButtons.forEach((item)=>{if(item!==button&&item.dataset.filter==='available'){item.classList.remove('bg-emerald-600','text-white');item.classList.add('bg-emerald-50','text-emerald-700')}});applyDirectoryFilters()})});if(searchInput){searchInput.addEventListener('input',applyDirectoryFilters)}applyDirectoryFilters();
(function(){const thread=document.getElementById('message-thread');if(thread){thread.scrollTop=thread.scrollHeight}})();
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication | Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body{font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;transition:margin-left .3s ease}
        main{transition:margin-left .3s ease}
        aside~main{margin-left:16rem}
        .admin-display{font-family:"Arial Black","Segoe UI",Tahoma,Geneva,Verdana,sans-serif;letter-spacing:-.03em}
        .messenger-shell{background:linear-gradient(180deg,#171717 0%,#202020 100%)}
        .messenger-sidebar{background:linear-gradient(180deg,#161616 0%,#1c1c1c 100%)}
        .messenger-thread{background:radial-gradient(circle at top right, rgba(88,28,135,.25), transparent 24%),linear-gradient(180deg,#202020 0%,#181818 100%)}
        .messenger-scroll::-webkit-scrollbar{width:8px}
        .messenger-scroll::-webkit-scrollbar-thumb{background:#4b5563;border-radius:999px}
        .messenger-scroll::-webkit-scrollbar-track{background:transparent}
    </style>
</head>
<body class="bg-[radial-gradient(circle_at_top,_#f8fafc,_#eef2ff_40%,_#f8fafc_100%)] text-slate-900">
@php
    $directoryMembers = collect($employees ?? []);
    $conversationSummaries = collect($conversationSummaries ?? []);
    $selectedParticipant = $selectedParticipant ?? null;
    $selectedConversation = $selectedConversation ?? null;
    $messages = collect(optional($selectedConversation)->messages ?? []);
    $availableCount = $directoryMembers->filter(fn ($member) => in_array(strtolower(trim((string) ($member->status ?? ''))), ['approved', 'available'], true))->count();
@endphp
<div class="flex min-h-screen">
    @include('components.adminSideBar')
    <main class="flex-1 ml-16 transition-all duration-300">
        @include('components.adminHeader.dashboardHeader', [
            'headerTitle' => 'Communication Hub',
            'headerSubtitle' => 'Open employee threads, send updates, and keep conversations in one place.',
            'headerSearchPlaceholder' => 'Search employees or conversations...',
        ])
        <div class="space-y-8 p-4 pt-20 md:p-8">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif
            @if (session('warning'))
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">{{ session('warning') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
            @endif
            <section class="rounded-[2rem] border border-slate-200 bg-white/90 p-6 shadow-sm">
                <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700">Employee Directory</p>
                        <h3 class="mt-2 text-2xl font-black tracking-tight text-slate-900">Choose an employee to start or continue a chat.</h3>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Use the directory to jump straight into a message thread with any approved employee.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-500"><i class="fa-solid fa-user-group text-emerald-500"></i>{{ $availableCount }} available employee{{ $availableCount === 1 ? '' : 's' }}</div>
                </div>
                <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2 xl:grid-cols-3">
                    @foreach ($directoryMembers as $employee)
                        @php
                            $employeeName = trim(implode(' ', array_filter([$employee->first_name ?? null, $employee->middle_name ?? null, $employee->last_name ?? null])));
                            $employeeName = $employeeName !== '' ? $employeeName : (string) ($employee->email ?? 'Employee');
                            $employeeInitials = strtoupper(substr(trim((string) ($employee->first_name ?? 'E')), 0, 1).substr(trim((string) ($employee->last_name ?? '')), 0, 1));
                            $department = trim((string) ($employee->department ?? optional($employee->employee)->department ?? 'General'));
                            $position = trim((string) ($employee->position ?? optional($employee->employee)->position ?? 'Employee'));
                            $employeeUnreadCount = (int) ($employee->unread_message_count ?? 0);
                            $employeeHasUnreadMessages = (bool) ($employee->has_unread_messages ?? false);
                        @endphp
                        <article class="rounded-[1.75rem] border border-slate-200 bg-slate-50/70 p-5 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-[1.2rem] bg-gradient-to-br from-slate-900 to-emerald-600 text-lg font-black text-white">{{ $employeeInitials !== '' ? $employeeInitials : 'EM' }}</div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="truncate text-lg font-black text-slate-900">{{ $employeeName }}</p>
                                            @if ($employeeHasUnreadMessages)
                                                <span class="inline-flex items-center rounded-full bg-rose-500 px-2.5 py-1 text-[11px] font-bold text-white">{{ $employeeUnreadCount > 99 ? '99+' : $employeeUnreadCount }} unread</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-500">{{ $position }}</p>
                                    </div>
                                </div>
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $department !== '' ? $department : 'General' }}</span>
                            </div>
                            <div class="mt-5 flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                                <div class="min-w-0">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Email</p>
                                    <p class="truncate text-sm text-slate-600">{{ $employee->email }}</p>
                                </div>
                                <a href="{{ route('admin.adminCommunication', ['user' => $employee->id]) }}#admin-chat-panel" class="relative inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                    @if ($employeeHasUnreadMessages)
                                        <span class="absolute -right-2 -top-2 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">
                                            {{ $employeeUnreadCount > 99 ? '99+' : $employeeUnreadCount }}
                                        </span>
                                    @endif
                                    <i class="fa-solid fa-comment"></i>Connect
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            @if ($selectedParticipant)
                            @php
                                $participantName = trim(implode(' ', array_filter([$selectedParticipant->first_name ?? null, $selectedParticipant->middle_name ?? null, $selectedParticipant->last_name ?? null])));
                                $participantName = $participantName !== '' ? $participantName : (string) ($selectedParticipant->email ?? 'Employee');
                                $participantInitials = strtoupper(substr(trim((string) ($selectedParticipant->first_name ?? 'E')), 0, 1).substr(trim((string) ($selectedParticipant->last_name ?? '')), 0, 1));
                            @endphp
                            <div class="fixed bottom-5 right-5 z-50 w-[370px] max-w-[calc(100vw-1.5rem)] overflow-hidden rounded-t-2xl rounded-b-[1.35rem] border border-slate-800 bg-[#1f1f1f] shadow-[0_30px_80px_rgba(0,0,0,0.45)]">
                            <div class="border-b border-slate-700 bg-[#242424] px-4 py-3">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="relative flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-slate-300 to-slate-500 text-sm font-black text-slate-950">{{ $participantInitials !== '' ? $participantInitials : 'EM' }}
                                            <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-[#242424] bg-emerald-400"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-xl font-bold text-white">{{ $participantName }}</p>
                                            <p class="text-sm text-slate-400">Active now</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 text-violet-400">
                                        <a href="{{ route('admin.adminCommunication') }}" class="text-violet-400"><i class="fa-solid fa-xmark"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div id="admin-message-thread" class="messenger-scroll h-[340px] space-y-4 overflow-y-auto bg-[#1f1f1f] px-4 py-4">
                                @forelse ($messages as $message)
                                    @php
                                        $isOwnMessage = (int) ($message->sender_user_id ?? 0) === (int) auth()->id();
                                        $senderName = trim(implode(' ', array_filter([$message->sender->first_name ?? null, $message->sender->last_name ?? null])));
                                        $senderName = $senderName !== '' ? $senderName : ($isOwnMessage ? 'You' : $participantName);
                                    @endphp
                                    <div class="flex items-end gap-2 {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                                        @unless ($isOwnMessage)
                                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-slate-300 to-slate-500 text-[9px] font-bold text-slate-950">{{ $participantInitials !== '' ? $participantInitials : 'EM' }}</div>
                                        @endunless
                                        <div class="max-w-[78%] rounded-[1.45rem] px-4 py-2.5 shadow-sm {{ $isOwnMessage ? 'bg-gradient-to-r from-blue-600 to-violet-600 text-white' : 'bg-[#303030] text-slate-100' }}">
                                            <p class="whitespace-pre-line text-sm leading-6">{{ $message->body }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex min-h-[16rem] items-center justify-center">
                                        <div class="max-w-sm text-center">
                                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-800 text-violet-400"><i class="fa-solid fa-comments text-xl"></i></div>
                                            <h4 class="mt-4 text-lg font-black text-white">Start the conversation.</h4>
                                            <p class="mt-2 text-sm leading-6 text-slate-400">Send the first message and the employee will be able to respond from their own communication page.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <form method="POST" action="{{ route('admin.communication.send') }}" class="border-t border-slate-700 bg-[#1f1f1f] px-4 py-3">
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
(function(){const thread=document.getElementById('admin-message-thread');if(thread){thread.scrollTop=thread.scrollHeight}})();
</script>
</body>
</html>

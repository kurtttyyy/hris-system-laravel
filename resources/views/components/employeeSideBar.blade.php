@php
    $employeeUser = auth()->user();
    $employeeUnreadMessages = 0;
    if (
        $employeeUser
        && \Illuminate\Support\Facades\Schema::hasTable('conversations')
        && \Illuminate\Support\Facades\Schema::hasTable('conversation_messages')
    ) {
        $employeeUnreadMessages = \App\Models\ConversationMessage::query()
            ->whereNull('read_at')
            ->where('sender_user_id', '!=', (int) $employeeUser->id)
            ->whereHas('conversation', function ($query) use ($employeeUser) {
                $query->where(function ($innerQuery) use ($employeeUser) {
                    $innerQuery->where('user_one_id', (int) $employeeUser->id)
                        ->orWhere('user_two_id', (int) $employeeUser->id);
                });
            })
            ->count();
    }
@endphp

<aside
    class="group fixed left-0 top-0 h-screen bg-gray-900 border-r border-gray-700
           w-16 hover:w-56 transition-all duration-300 overflow-hidden z-50"
>

    <!-- Logo -->
    <div class="p-4 border-b border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" height="40">
            </div>

            <!-- Logo text -->
            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <h1 class="text-sm font-bold text-white">
                    Northeastern College
                </h1>
                <p class="text-xs text-gray-400">
                    Employee Portal
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-2 space-y-2">

        <!-- Dashboard -->
        <a href="{{ route('employee.employeeHome') }}"
           data-employee-nav
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeHome')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-dashboard text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Dashboard
            </span>
        </a>

        <!-- Leave Requests -->
        <a href="{{ route('employee.employeeLeave') }}"
           data-employee-nav
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeLeave')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-calendar text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Leave Requests
            </span>
        </a>

        <!-- Payslips -->
        <a href="{{ route('employee.employeePayslip') }}"
           data-employee-nav
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeePayslip')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-file-text-o text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Payslips
            </span>
        </a>

        <!-- Documents -->
        <a href="{{ route('employee.employeeDocument') }}"
           data-employee-nav
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeDocument')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-folder text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Documents
            </span>
        </a>

        <!-- Communication -->
        <a href="{{ route('employee.employeeCommunication', ['reset_chat' => 1]) }}"
           data-employee-nav
           class="relative flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeCommunication')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <span class="relative inline-flex items-center justify-center w-6">
                <i class="fa fa-users text-lg text-center"></i>
                @if ($employeeUnreadMessages > 0)
                    <span class="absolute -right-2 -top-2 inline-flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[9px] font-bold leading-none text-white group-hover:hidden">{{ $employeeUnreadMessages > 9 ? '9+' : $employeeUnreadMessages }}</span>
                @endif
            </span>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Communication
            </span>
            @if ($employeeUnreadMessages > 0)
                <span class="ml-auto hidden min-w-[1.4rem] items-center justify-center rounded-full bg-rose-500 px-1.5 py-0.5 text-[11px] font-bold leading-none text-white group-hover:inline-flex">{{ $employeeUnreadMessages > 99 ? '99+' : $employeeUnreadMessages }}</span>
            @endif
        </a>

        <!-- Resignation -->
        <a href="{{ route('employee.employeeResignation') }}"
           data-employee-nav
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeResignation')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-user-times text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Resignation
            </span>
        </a>

    </nav>

</aside>

<!-- Font Awesome -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .employee-nav-overlay {
        position: fixed;
        inset: 0;
        z-index: 70;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(2, 6, 23, 0.22);
        backdrop-filter: blur(6px);
        opacity: 0;
        pointer-events: none;
        transition: opacity 180ms ease;
    }

    .employee-nav-overlay.is-visible {
        opacity: 1;
        pointer-events: auto;
    }

    .employee-nav-overlay__card {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.95rem 1.1rem;
        border-radius: 9999px;
        background: rgba(15, 23, 42, 0.92);
        color: #f8fafc;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.25);
        transform: translateY(8px) scale(0.98);
        transition: transform 220ms ease;
    }

    .employee-nav-overlay.is-visible .employee-nav-overlay__card {
        transform: translateY(0) scale(1);
    }

    .employee-nav-overlay__spinner {
        width: 1rem;
        height: 1rem;
        border-radius: 9999px;
        border: 2px solid rgba(255, 255, 255, 0.28);
        border-top-color: #4ade80;
        animation: employee-nav-spin 0.75s linear infinite;
    }

    @keyframes employee-nav-spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    (function () {
        const links = Array.from(document.querySelectorAll('[data-employee-nav]'));
        const currentUrl = new URL(window.location.href);
        const tabSession = currentUrl.searchParams.get('tab_session') || '';
        if (!links.length && !tabSession) {
            return;
        }

        let overlay = document.querySelector('[data-employee-nav-overlay]');
        const prefetched = new Set();

        const appendTabSession = (href) => {
            if (!href || !tabSession) {
                return href;
            }

            const url = new URL(href, window.location.origin);
            if (url.origin !== window.location.origin) {
                return href;
            }

            url.searchParams.set('tab_session', tabSession);
            return `${url.pathname}${url.search}${url.hash}`;
        };

        if (tabSession) {
            document.querySelectorAll('a[href]').forEach((anchor) => {
                const href = anchor.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                    return;
                }

                const nextHref = appendTabSession(href);
                if (nextHref) {
                    anchor.setAttribute('href', nextHref);
                }
            });

            document.querySelectorAll('form').forEach((form) => {
                let hiddenInput = form.querySelector('input[name="tab_session"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'tab_session';
                    form.appendChild(hiddenInput);
                }
                hiddenInput.value = tabSession;
            });
        }

        const ensureOverlay = () => {
            if (overlay) {
                return overlay;
            }

            overlay = document.createElement('div');
            overlay.className = 'employee-nav-overlay';
            overlay.setAttribute('data-employee-nav-overlay', '');
            overlay.innerHTML = `
                <div class="employee-nav-overlay__card">
                    <span class="employee-nav-overlay__spinner"></span>
                    <span class="text-sm font-semibold tracking-wide">Opening page...</span>
                </div>
            `;
            document.body.appendChild(overlay);
            return overlay;
        };

        const prefetchPage = (href) => {
            if (!href || prefetched.has(href)) {
                return;
            }

            prefetched.add(href);
            fetch(href, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Employee-Prefetch': '1',
                },
            }).catch(() => {
            });
        };

        links.forEach((link) => {
            const href = appendTabSession(link.getAttribute('href'));
            if (!href) {
                return;
            }

            link.setAttribute('href', href);

            link.addEventListener('mouseenter', () => prefetchPage(href), { passive: true });
            link.addEventListener('focus', () => prefetchPage(href), { passive: true });

            link.addEventListener('click', (event) => {
                if (
                    event.defaultPrevented
                    || event.metaKey
                    || event.ctrlKey
                    || event.shiftKey
                    || event.altKey
                    || link.target === '_blank'
                ) {
                    return;
                }

                const latestUrl = new URL(window.location.href);
                const nextUrl = new URL(href, window.location.origin);
                if (latestUrl.pathname === nextUrl.pathname && latestUrl.search === nextUrl.search) {
                    return;
                }

                ensureOverlay().classList.add('is-visible');
            });
        });

        window.addEventListener('pageshow', () => {
            if (overlay) {
                overlay.classList.remove('is-visible');
            }
        });
    })();
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Hierarchy | Northeastern College</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            transition: margin-left 0.3s ease;
        }

        main {
            transition: margin-left 0.3s ease;
        }

        aside:not(:hover) ~ main {
            margin-left: 4rem;
        }

        aside:hover ~ main {
            margin-left: 14rem;
        }

        .hierarchy-page {
            min-height: 100vh;
            background:
                radial-gradient(circle at top, rgba(232, 248, 229, 0.96), rgba(241, 250, 239, 0.94) 50%, rgba(226, 245, 224, 0.96) 100%);
        }

        .tree-shell {
            max-width: 86rem;
            margin: 0 auto;
        }

        .tree-head-card {
            width: 11.25rem;
            min-height: 10.2rem;
        }

        .tree-card {
            width: 11.25rem;
            min-height: 10.4rem;
        }

        .tree-line-v {
            width: 2px;
            background: #7ccf83;
        }

        .tree-line-h {
            height: 2px;
            background: #7ccf83;
        }

        .tree-manager-grid {
            display: grid;
            justify-content: center;
            gap: 2rem 4.5rem;
        }

        .tree-staff-grid {
            display: grid;
            justify-content: center;
            gap: 1.25rem;
        }

        .tree-avatar {
            overflow: hidden;
        }

        .tree-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tree-employee-card {
            position: relative;
        }

        .tree-employee-button {
            width: 100%;
            text-align: inherit;
            cursor: pointer;
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }

        .tree-employee-button:hover,
        .tree-employee-button:focus-visible,
        .tree-employee-card.is-open .tree-employee-button {
            transform: translateY(-4px);
            box-shadow: 0 20px 42px rgba(16, 185, 129, 0.18);
            border-color: #10b981;
            outline: none;
        }

        .tree-card-popover {
            position: absolute;
            left: 50%;
            top: calc(100% + 0.85rem);
            z-index: 20;
            width: 15rem;
            padding: 0.95rem 1rem;
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.97);
            box-shadow: 0 22px 45px rgba(15, 23, 42, 0.14);
            backdrop-filter: blur(10px);
            opacity: 0;
            pointer-events: none;
            transform: translateX(-50%) translateY(10px);
            transition: opacity 180ms ease, transform 180ms ease;
        }

        .tree-card-popover::before {
            content: "";
            position: absolute;
            left: 50%;
            top: -0.4rem;
            width: 0.8rem;
            height: 0.8rem;
            background: rgba(255, 255, 255, 0.97);
            border-left: 1px solid rgba(16, 185, 129, 0.2);
            border-top: 1px solid rgba(16, 185, 129, 0.2);
            transform: translateX(-50%) rotate(45deg);
        }

        .tree-employee-card:hover .tree-card-popover,
        .tree-employee-card:focus-within .tree-card-popover,
        .tree-employee-card.is-open .tree-card-popover {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }

        .tree-card-popover__label {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #059669;
        }

        .tree-card-popover__value {
            margin-top: 0.2rem;
            font-size: 0.8rem;
            line-height: 1.35;
            color: #0f172a;
            word-break: break-word;
        }

        @media (min-width: 1024px) {
            .tree-manager-grid.cols-1 {
                grid-template-columns: repeat(1, 11.25rem);
            }

            .tree-manager-grid.cols-2 {
                grid-template-columns: repeat(2, 11.25rem);
            }

            .tree-manager-grid.cols-3 {
                grid-template-columns: repeat(3, 11.25rem);
            }

            .tree-staff-grid.cols-1 {
                grid-template-columns: repeat(1, 11.25rem);
            }

            .tree-staff-grid.cols-2 {
                grid-template-columns: repeat(2, 11.25rem);
            }
        }

        @media (max-width: 1023px) {
            .tree-line-h,
            .tree-line-v.desktop-line,
            .tree-branch {
                display: none !important;
            }

            .tree-manager-grid,
            .tree-staff-grid {
                grid-template-columns: 1fr;
                justify-items: center;
            }

            .tree-head-card,
            .tree-card {
                width: min(100%, 18rem);
            }

            .tree-card-popover {
                position: static;
                width: min(100%, 18rem);
                margin-top: 0.85rem;
                transform: none;
            }

            .tree-card-popover::before {
                display: none;
            }
        }
    </style>
</head>
<body class="hierarchy-page text-slate-900">
<div class="flex min-h-screen">
    @include('components.employeeSideBar')

    <main class="flex-1 ml-16 transition-all duration-300">
        <div class="px-4 pb-10 pt-20 md:px-8">
            @php
                $managerCountValue = max($managerNodes->count(), 1);
                $managerGridClass = 'cols-'.min($managerCountValue, 3);
            @endphp

            <section class="tree-shell">
                <div class="text-center">
                    <h1 class="text-3xl font-black tracking-tight text-emerald-900 md:text-5xl">
                        {{ $departmentName }} Employee Hierarchy
                    </h1>
                    <p class="mt-2 text-base text-emerald-800 md:text-[1.05rem]">
                        View the Head of Department and employees under each level
                    </p>
                </div>

                @if ($headNode)
                    <div class="mt-16 flex flex-col items-center">
                        <article class="tree-head-card rounded-[1.1rem] bg-gradient-to-br from-emerald-900 via-emerald-800 to-green-600 px-4 py-5 text-center text-white shadow-[0_22px_60px_rgba(34,139,34,0.22)]">
                            @if (!empty($headNode['photo_url']))
                                <div class="tree-avatar mx-auto h-[3.85rem] w-[3.85rem] rounded-full ring-8 ring-white/8">
                                    <img src="{{ $headNode['photo_url'] }}" alt="{{ $headNode['name'] }}">
                                </div>
                            @else
                                <div class="mx-auto flex h-[3.85rem] w-[3.85rem] items-center justify-center rounded-full bg-white/18 text-[1.1rem] font-black ring-8 ring-white/8">
                                    {{ $headNode['initials'] }}
                                </div>
                            @endif
                            <h2 class="mt-4 text-[0.95rem] font-black leading-snug">{{ $headNode['name'] }}</h2>
                            <p class="mt-1 text-[0.72rem] text-emerald-50">{{ $headNode['title'] }}</p>
                            <p class="mt-1.5 text-[0.72rem] font-semibold text-white">{{ $headNode['team'] }}</p>
                        </article>

                        @if ($managerNodes->isNotEmpty())
                            <div class="tree-line-v mt-0 h-7"></div>
                        @endif
                    </div>
                @endif

                @if ($managerNodes->isNotEmpty())
                    <div class="mx-auto hidden w-fit flex-col items-center lg:flex">
                        <div class="tree-line-h" style="width: calc({{ max($managerNodes->count() - 1, 0) }} * (11.25rem + 4.5rem));"></div>
                        <div class="tree-manager-grid {{ $managerGridClass }}" style="margin-top: 0;">
                            @foreach ($managerNodes as $managerNode)
                                <div class="flex flex-col items-center">
                                    <div class="tree-line-v desktop-line h-5"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tree-manager-grid {{ $managerGridClass }} mt-0">
                        @foreach ($managerNodes as $managerNode)
                            @php
                                $staffCountValue = max($managerNode['employees']->count(), 1);
                                $staffGridClass = 'cols-'.min($staffCountValue, 2);
                            @endphp

                            <div class="flex flex-col items-center">
                                <div class="tree-employee-card" data-tree-employee-card>
                                    <article
                                        tabindex="0"
                                        role="button"
                                        aria-label="View {{ $managerNode['name'] }} information"
                                        class="tree-employee-button tree-card rounded-[1.1rem] border-2 border-emerald-400 bg-white px-4 py-5 text-center shadow-[0_18px_40px_rgba(110,231,183,0.14)]"
                                    >
                                        @if (!empty($managerNode['photo_url']))
                                            <div class="tree-avatar mx-auto h-[3.25rem] w-[3.25rem] rounded-full border border-emerald-200">
                                                <img src="{{ $managerNode['photo_url'] }}" alt="{{ $managerNode['name'] }}">
                                            </div>
                                        @else
                                            <div class="mx-auto flex h-[3.25rem] w-[3.25rem] items-center justify-center rounded-full bg-emerald-100 text-[1rem] font-black text-emerald-900">
                                                {{ $managerNode['initials'] }}
                                            </div>
                                        @endif
                                        <h3 class="mt-4 text-[0.92rem] font-black leading-snug text-slate-900">{{ $managerNode['name'] }}</h3>
                                        <p class="mt-1 text-[0.72rem] text-slate-700">{{ $managerNode['title'] }}</p>
                                        <p class="mt-1.5 text-[0.72rem] font-semibold text-emerald-700">{{ $managerNode['team'] }}</p>
                                    </article>

                                    <div class="tree-card-popover">
                                        <div>
                                            <p class="tree-card-popover__label">Employee ID</p>
                                            <p class="tree-card-popover__value">{{ $managerNode['employee_id'] }}</p>
                                        </div>
                                        <div class="mt-3">
                                            <p class="tree-card-popover__label">Email</p>
                                            <p class="tree-card-popover__value">{{ $managerNode['email'] }}</p>
                                        </div>
                                        <div class="mt-3">
                                            <p class="tree-card-popover__label">Status</p>
                                            <p class="tree-card-popover__value">{{ $managerNode['status'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if ($managerNode['employees']->isNotEmpty())
                                    <div class="tree-line-v desktop-line h-7"></div>
                                    <div class="hidden lg:flex lg:flex-col lg:items-center">
                                        <div class="tree-line-h" style="width: calc({{ max($managerNode['employees']->count() - 1, 0) }} * (11.25rem + 1.25rem));"></div>
                                        <div class="tree-staff-grid {{ $staffGridClass }} mt-0">
                                            @foreach ($managerNode['employees'] as $employeeNode)
                                                <div class="flex flex-col items-center">
                                                    <div class="tree-line-v desktop-line h-5"></div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="tree-staff-grid {{ $staffGridClass }} mt-0">
                                        @foreach ($managerNode['employees'] as $employeeNode)
                                            <div class="tree-employee-card" data-tree-employee-card>
                                                <article
                                                    tabindex="0"
                                                    role="button"
                                                    aria-label="View {{ $employeeNode['name'] }} information"
                                                    class="tree-employee-button tree-card rounded-[1.1rem] border-2 border-emerald-400 bg-white px-4 py-5 text-center shadow-[0_16px_36px_rgba(110,231,183,0.14)]"
                                                >
                                                    @if (!empty($employeeNode['photo_url']))
                                                        <div class="tree-avatar mx-auto h-[3.25rem] w-[3.25rem] rounded-full border border-emerald-200">
                                                            <img src="{{ $employeeNode['photo_url'] }}" alt="{{ $employeeNode['name'] }}">
                                                        </div>
                                                    @else
                                                        <div class="mx-auto flex h-[3.25rem] w-[3.25rem] items-center justify-center rounded-full bg-emerald-100 text-[1rem] font-black text-emerald-900">
                                                            {{ $employeeNode['initials'] }}
                                                        </div>
                                                    @endif
                                                    <h4 class="mt-4 text-[0.92rem] font-black leading-snug text-slate-900">{{ $employeeNode['name'] }}</h4>
                                                    <p class="mt-1 text-[0.72rem] text-slate-700">{{ $employeeNode['title'] }}</p>
                                                    <p class="mt-1.5 text-[0.72rem] font-semibold text-emerald-700">{{ $employeeNode['team'] }}</p>
                                                </article>

                                                <div class="tree-card-popover">
                                                    <div>
                                                        <p class="tree-card-popover__label">Employee ID</p>
                                                        <p class="tree-card-popover__value">{{ $employeeNode['employee_id'] }}</p>
                                                    </div>
                                                    <div class="mt-3">
                                                        <p class="tree-card-popover__label">Email</p>
                                                        <p class="tree-card-popover__value">{{ $employeeNode['email'] }}</p>
                                                    </div>
                                                    <div class="mt-3">
                                                        <p class="tree-card-popover__label">Status</p>
                                                        <p class="tree-card-popover__value">{{ $employeeNode['status'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @elseif ($headNode)
                    <div class="mt-10 text-center text-sm text-emerald-800">
                        No second-level employees found for this department.
                    </div>
                @else
                    <div class="mt-10 rounded-2xl border border-amber-200 bg-amber-50 px-6 py-5 text-center text-amber-800">
                        No hierarchy records were grouped for this department yet.
                    </div>
                @endif
            </section>
        </div>
    </main>
</div>
<script>
    (function () {
        const employeeCards = Array.from(document.querySelectorAll('[data-tree-employee-card]'));
        if (!employeeCards.length) {
            return;
        }

        const closeAllCards = () => {
            employeeCards.forEach((card) => card.classList.remove('is-open'));
        };

        employeeCards.forEach((card) => {
            const trigger = card.querySelector('.tree-employee-button');
            if (!trigger) {
                return;
            }

            trigger.addEventListener('click', (event) => {
                event.stopPropagation();
                const willOpen = !card.classList.contains('is-open');
                closeAllCards();
                card.classList.toggle('is-open', willOpen);
            });

            trigger.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter' && event.key !== ' ') {
                    return;
                }

                event.preventDefault();
                const willOpen = !card.classList.contains('is-open');
                closeAllCards();
                card.classList.toggle('is-open', willOpen);
            });
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('[data-tree-employee-card]')) {
                closeAllCards();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeAllCards();
            }
        });
    })();
</script>
</body>
</html>

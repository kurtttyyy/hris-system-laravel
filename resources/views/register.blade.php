<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Northeastern College | HRMS - Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #14532d 0%, #15803d 45%, #22c55e 100%);
            overflow-x: hidden;
        }

        .register-auth-shell {
            perspective: 1600px;
        }

        .register-form-card {
            opacity: 0;
            transform: translateX(72px) scale(0.985);
            transform-origin: right center;
            animation: register-form-swap 0.72s cubic-bezier(0.22, 0.9, 0.2, 1) forwards;
        }

        .register-showcase-card {
            opacity: 0;
            transform: translateX(-72px) scale(0.985);
            transform-origin: left center;
            animation: register-showcase-swap 0.72s cubic-bezier(0.22, 0.9, 0.2, 1) 0.08s forwards;
        }

        .register-auth-shell.is-exiting .register-form-card,
        .register-auth-shell.is-exiting .register-showcase-card {
            animation-duration: 0.78s;
            animation-timing-function: cubic-bezier(0.16, 0.84, 0.24, 1);
            animation-delay: 0s;
            animation-fill-mode: forwards;
        }

        .register-auth-shell.is-exiting-right .register-form-card {
            animation-name: register-form-swap-to-right;
        }

        .register-auth-shell.is-exiting-right .register-showcase-card {
            animation-name: register-showcase-swap-to-left;
        }

        .register-auth-shell.is-entering-from-right .register-form-card {
            animation-name: register-form-enter-from-right;
        }

        .register-auth-shell.is-entering-from-right .register-showcase-card {
            animation-name: register-showcase-enter-from-left;
        }

        @keyframes register-form-swap {
            from {
                opacity: 0;
                transform: translateX(72px) scale(0.985);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes register-showcase-swap {
            from {
                opacity: 0;
                transform: translateX(-72px) scale(0.985);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes register-form-swap-to-right {
            from {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
            to {
                opacity: 0.28;
                transform: translateX(calc(100% + 1.2rem)) scale(0.975);
            }
        }

        @keyframes register-showcase-swap-to-left {
            from {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
            to {
                opacity: 0.28;
                transform: translateX(calc(-100% - 1.2rem)) scale(0.975);
            }
        }

        @keyframes register-form-enter-from-right {
            from {
                opacity: 0;
                transform: translateX(calc(100% + 2rem)) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes register-showcase-enter-from-left {
            from {
                opacity: 0;
                transform: translateX(calc(-100% - 2rem)) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
    </style>
</head>
<body>

<div data-auth-root class="register-auth-shell min-h-screen flex items-center justify-center bg-gradient-to-br from-green-900 via-green-700 to-green-500 p-6">
    <div class="w-full max-w-6xl grid grid-cols-1 gap-8 lg:grid-cols-2">

        <div class="register-form-card bg-white rounded-3xl shadow-2xl p-10 flex items-center">
            <div class="w-full max-w-md mx-auto">
                <div class="mb-8 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-white flex items-center justify-center shadow-sm ring-1 ring-slate-100">
                        <img src="/images/logo.webp" alt="Northeastern College Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">Northeastern College</h1>
                        <p class="text-sm text-gray-500">HR Management System</p>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                <p class="text-gray-500 mb-8">Register to access the HRMS</p>

                <form class="space-y-5" method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <input type="text" placeholder="First Name" name="first_name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">
                        <input type="text" placeholder="Middle Name" name="middle_name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>

                    <input type="text" placeholder="Last Name" name="last_name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">

                    <input type="email" placeholder="Email Address" name="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">

                    <input type="password" placeholder="Password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">

                    <input type="password" placeholder="Confirm Password" name="confirmation_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">

                    <button type="submit" class="w-full bg-gradient-to-r from-green-800 via-green-600 to-green-800 text-white font-semibold py-3 rounded-xl transition hover:opacity-95">
                        Create Account
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-8">
                    Already have an account?
                    <a href="{{ route('login') }}" data-auth-link="login" class="text-green-700 font-semibold hover:underline">
                        Sign in
                    </a>
                </p>
            </div>
        </div>

        <div class="register-showcase-card bg-white/95 rounded-3xl shadow-2xl p-10 flex flex-col justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-green-700">
                    Registration
                </div>

                <h2 class="mt-6 text-4xl font-extrabold text-gray-900 mb-4">
                    Your account moves into the left panel
                </h2>

                <p class="text-gray-600 mb-8 leading-relaxed">
                    The register screen now feels like a switched layout. The form takes over the left container,
                    while the overview content slides across to the right.
                </p>

                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">&#10003;</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Smooth Visual Switch</h3>
                            <p class="text-sm text-gray-500">
                                The register form animates into the left card to make the change feel intentional.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">&#10003;</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Balanced Layout</h3>
                            <p class="text-sm text-gray-500">
                                The supporting content shifts to the right card so both auth screens still feel related.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold">&#10003;</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Clean Mobile Fallback</h3>
                            <p class="text-sm text-gray-500">
                                On small screens the cards stack naturally, so the animation does not break responsiveness.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 mt-10 pt-8 border-t">
                <div>
                    <p class="text-2xl font-bold text-gray-900">50K+</p>
                    <p class="text-sm text-gray-500">Active Users</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">99.9%</p>
                    <p class="text-sm text-gray-500">Uptime</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">4.9/5</p>
                    <p class="text-sm text-gray-500">User Rating</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    (function () {
        const root = document.querySelector('[data-auth-root]');
        if (!root) {
            return;
        }

        const transitionKey = 'auth_transition_state_v1';
        const savedState = sessionStorage.getItem(transitionKey);
        if (savedState === 'to-register') {
            root.classList.add('is-entering-from-right');
            sessionStorage.removeItem(transitionKey);
        }

        document.querySelectorAll('[data-auth-link]').forEach((link) => {
            link.addEventListener('click', function (event) {
                const href = this.getAttribute('href');
                if (!href || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                    return;
                }

                event.preventDefault();
                sessionStorage.setItem(transitionKey, 'from-register');
                root.classList.remove('is-entering-from-right');
                root.classList.add('is-exiting', 'is-exiting-right');

                window.setTimeout(() => {
                    window.location.href = href;
                }, 700);
            });
        });
    })();
</script>

</body>
</html>

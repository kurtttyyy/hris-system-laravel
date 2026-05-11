@extends('layouts.app')

@section('content')
@include('layouts.header')
<div class="header-divider" aria-hidden="true"></div>

<style>
    .policy-page {
        --policy-ink: #0f172a;
        --policy-muted: #5b6475;
        --policy-line: rgba(15, 23, 42, 0.08);
        --policy-brand: #157347;
        --policy-brand-strong: #0d5c38;
        background:
            radial-gradient(circle at top left, rgba(21, 115, 71, 0.12), transparent 28%),
            radial-gradient(circle at top right, rgba(244, 197, 66, 0.12), transparent 22%),
            linear-gradient(180deg, #eef7f0 0%, #f8fafc 24%, #ffffff 100%);
        min-height: 100vh;
    }

    .policy-hero {
        position: relative;
        overflow: hidden;
        padding: 5rem 0 3rem;
    }

    .policy-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, rgba(4, 18, 16, 0.92), rgba(5, 76, 48, 0.72) 48%, rgba(11, 18, 32, 0.86));
    }

    .policy-shell {
        position: relative;
        z-index: 1;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .policy-hero-card {
        padding: 2.2rem;
        border-radius: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.16);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0.07));
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.22);
        backdrop-filter: blur(10px);
        color: #fff;
    }

    .policy-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.55rem 1rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #fff3c4;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.16em;
    }

    .policy-kicker::before {
        content: "";
        width: 0.55rem;
        height: 0.55rem;
        border-radius: 50%;
        background: #f4c542;
        box-shadow: 0 0 0 6px rgba(244, 197, 66, 0.18);
    }

    .policy-title {
        margin: 1.2rem 0 0.9rem;
        font-size: clamp(2.3rem, 5vw, 4.2rem);
        line-height: 1.04;
        font-weight: 800;
    }

    .policy-copy {
        max-width: 44rem;
        margin: 0;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.85;
    }

    .policy-content {
        max-width: 1280px;
        margin: 0 auto;
        padding: 3rem 1.5rem 5rem;
    }

    .policy-grid {
        display: grid;
        grid-template-columns: minmax(0, 0.8fr) minmax(0, 1.2fr);
        gap: 1.5rem;
        align-items: start;
    }

    .policy-side-card,
    .policy-card {
        padding: 1.8rem;
        border-radius: 1.8rem;
        border: 1px solid var(--policy-line);
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.07);
    }

    .policy-side-card h3,
    .policy-card h2 {
        margin: 0 0 0.8rem;
        color: var(--policy-ink);
        font-weight: 800;
    }

    .policy-side-card p,
    .policy-card p {
        margin: 0;
        color: var(--policy-muted);
        line-height: 1.8;
    }

    .policy-side-list {
        list-style: none;
        padding: 0;
        margin: 1.25rem 0 0;
        display: grid;
        gap: 0.9rem;
    }

    .policy-side-list li {
        list-style: none;
    }

    .policy-side-item {
        padding: 0.95rem 1rem;
        border-radius: 1rem;
        background: #f8fafc;
        border: 1px solid rgba(15, 23, 42, 0.05);
    }

    .policy-side-item summary {
        list-style: none;
        cursor: pointer;
        color: var(--policy-ink);
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .policy-side-item summary::-webkit-details-marker {
        display: none;
    }

    .policy-side-item summary::after {
        content: '+';
        width: 1.3rem;
        height: 1.3rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--policy-brand-strong);
        background: rgba(21, 115, 71, 0.12);
        flex-shrink: 0;
    }

    .policy-side-item[open] summary::after {
        content: '−';
    }

    .policy-side-item p {
        margin-top: 0.7rem;
        margin-bottom: 0;
        color: var(--policy-muted);
        font-weight: 500;
        line-height: 1.7;
    }

    .policy-stack {
        display: grid;
        gap: 1rem;
    }

    #guest-cookie-page .guest-cookie-reveal {
        opacity: 1;
        transform: translateY(0);
        will-change: opacity, transform;
    }

    #guest-cookie-page .guest-cookie-reveal.is-scroll-animated,
    .site-footer.guest-cookie-reveal.is-scroll-animated {
        animation: guest-cookie-fade-up 0.72s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: var(--guest-cookie-delay, 0ms);
    }

    #guest-cookie-page .guest-cookie-card-motion,
    .site-footer .guest-cookie-card-motion {
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            border-color 0.25s ease;
    }

    #guest-cookie-page .guest-cookie-card-motion:hover,
    .site-footer .guest-cookie-card-motion:hover {
        transform: translateY(-4px);
    }

    #guest-cookie-page .guest-cookie-pop {
        animation: guest-cookie-pop 0.58s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: var(--guest-cookie-delay, 120ms);
    }

    @keyframes guest-cookie-fade-up {
        0% {
            opacity: 0;
            transform: translateY(26px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes guest-cookie-pop {
        0% {
            opacity: 0;
            transform: scale(0.9);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        #guest-cookie-page .guest-cookie-reveal,
        #guest-cookie-page .guest-cookie-card-motion,
        #guest-cookie-page .guest-cookie-pop,
        .site-footer.guest-cookie-reveal,
        .site-footer .guest-cookie-card-motion {
            opacity: 1;
            transform: none;
            transition: none;
            animation: none;
            will-change: auto;
        }
    }

    .site-footer {
        background:
            radial-gradient(circle at top left, rgba(21, 115, 71, 0.12), transparent 24%),
            linear-gradient(180deg, #0f1113 0%, #0b0c0d 100%);
        color: rgba(255, 255, 255, 0.82);
        margin-top: 0;
    }

    .site-footer a {
        color: rgba(255, 255, 255, 0.82);
        text-decoration: none;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .site-footer a:hover {
        color: #ffffff;
        transform: translateX(2px);
    }

    .footer-shell {
        max-width: 1240px;
        margin: 0 auto;
        padding: 4rem 1.5rem 2rem;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.3fr 1fr 1fr;
        gap: 2rem;
    }

    .footer-brand {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .footer-brand-mark {
        width: 3.75rem;
        height: 3.75rem;
        border-radius: 50%;
        object-fit: cover;
        background: #fff;
        padding: 0.35rem;
        box-shadow: 0 14px 26px rgba(0, 0, 0, 0.28);
    }

    .footer-brand h3,
    .footer-title {
        margin: 0 0 1.25rem;
        color: #fff;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .footer-title {
        font-size: 1.15rem;
    }

    .footer-info-list,
    .footer-link-list {
        list-style: none;
        padding: 0;
        margin: 1.4rem 0 0;
    }

    .footer-info-list li,
    .footer-link-list li {
        margin-bottom: 0.95rem;
    }

    .footer-contact {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
    }

    .footer-icon {
        width: 1.2rem;
        height: 1.2rem;
        flex: 0 0 1.2rem;
        color: rgba(255, 255, 255, 0.85);
        margin-top: 0.15rem;
    }

    .footer-feature-text {
        margin: 0;
        max-width: 18rem;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.9;
    }

    .newsletter-copy {
        margin: 0 0 1rem;
        color: rgba(255, 255, 255, 0.72);
        line-height: 1.7;
    }

    .newsletter-form {
        display: grid;
        gap: 0.8rem;
    }

    .newsletter-input-wrap {
        position: relative;
    }

    .newsletter-input {
        width: 100%;
        height: 3rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 0.95rem;
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        padding: 0.8rem 3rem 0.8rem 1rem;
        outline: none;
    }

    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.45);
    }

    .newsletter-input:focus {
        border-color: rgba(52, 211, 153, 0.55);
        box-shadow: 0 0 0 0.18rem rgba(52, 211, 153, 0.15);
    }

    .newsletter-input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.1rem;
        height: 1.1rem;
        color: rgba(255, 255, 255, 0.55);
    }

    .newsletter-btn {
        border: none;
        border-radius: 0.95rem;
        min-height: 3rem;
        background: linear-gradient(135deg, #1b1d1f 0%, #232628 100%);
        color: #fff;
        font-weight: 700;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.05);
    }

    .newsletter-btn:hover {
        background: linear-gradient(135deg, #157347 0%, #1ea55d 100%);
    }

    .newsletter-note {
        margin: 0.35rem 0 0;
        color: rgba(191, 219, 254, 0.8);
        font-size: 0.9rem;
    }

    .footer-bottom {
        margin-top: 3rem;
        padding-top: 1.4rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .footer-bottom p {
        margin: 0;
        color: rgba(255, 255, 255, 0.68);
    }

    .footer-bottom-links {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    @media (max-width: 991.98px) {
        .policy-grid,
        .footer-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767.98px) {
        .policy-hero {
            padding: 4.4rem 0 2.8rem;
        }

        .policy-shell,
        .policy-content {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .policy-hero-card,
        .policy-side-card,
        .policy-card {
            padding: 1.4rem;
            border-radius: 1.45rem;
        }

        .footer-shell {
            padding: 3rem 1rem 1.5rem;
        }

        .footer-bottom {
            flex-direction: column;
            align-items: flex-start;
        }

        .footer-bottom-links {
            gap: 1rem;
        }
    }
</style>

<main id="guest-cookie-page" class="policy-page">
    <section class="policy-hero">
        <div class="policy-shell">
            <div class="policy-hero-card">
                <span class="policy-kicker">Cookie Policy</span>
                <h1 class="policy-title">How cookies and similar tools support this recruitment site.</h1>
                <p class="policy-copy">
                    This page explains how Northeastern College Human Resources may use cookies, session storage, and related technologies on public recruitment pages.
                </p>
            </div>
        </div>
    </section>

    <section class="policy-content">
        <div class="policy-grid">
            <div class="policy-side-card">
                <h3>Cookie overview</h3>
                <p>
                    We use straightforward language so visitors understand what cookie data is used for and how it helps forms and navigation work correctly.
                </p>
                <ul class="policy-side-list">
                    <li>
                        <details class="policy-side-item">
                            <summary>Essential cookies</summary>
                            <p>Required for core site behavior, such as page security, session continuity, and basic functionality.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Form/session stability</summary>
                            <p>Helps maintain state while filling forms, moving between steps, and preventing accidental data loss.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Navigation preferences</summary>
                            <p>May remember non-sensitive interface preferences to improve consistency during a visit.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Security support</summary>
                            <p>Supports protective controls that help identify abnormal activity and protect form submissions.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Limited analytics context</summary>
                            <p>May be used in aggregate to understand page performance and improve recruitment workflows.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Cookie duration</summary>
                            <p>Some cookies expire quickly, while others remain longer depending on function and security needs.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Managing cookies</summary>
                            <p>You can adjust browser settings to restrict cookies, though some features may not work as expected.</p>
                        </details>
                    </li>
                </ul>
            </div>

            <div class="policy-stack">
                <div class="policy-card">
                    <h2>What Cookies Are</h2>
                    <p>
                        Cookies are small text files saved by your browser that help websites recognize sessions and support reliable user experience.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Why We Use Them</h2>
                    <p>
                        On recruitment pages, cookies primarily support secure access, stable form handling, and smooth navigation across application steps.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Categories We May Use</h2>
                    <p>
                        Cookies may include essential, functional, and limited performance-related categories used to maintain and improve site operation.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Third-Party Services</h2>
                    <p>
                        Some integrated services may set their own cookies when needed for embedded features. Their handling is governed by their respective policies.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Your Browser Controls</h2>
                    <p>
                        Most browsers let you review, block, or delete cookies. Disabling certain cookies can impact login state, forms, and page behavior.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Policy Updates</h2>
                    <p>
                        This Cookie Policy may be revised as system features or legal requirements evolve. The latest version published here is the active reference.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Contact</h2>
                    <p>
                        For questions about cookie use on the recruitment site, contact the Human Resources Department through official channels listed below.
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="footer-shell">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <img src="{{ asset('images/nclogo.png') }}" alt="Northeastern College logo" class="footer-brand-mark">
                    <div>
                        <h3>Northeastern<br>College</h3>
                    </div>
                </div>

                <ul class="footer-info-list">
                    <li>
                        <a href="https://www.google.com/maps/search/?api=1&query=Villasis%2C+Santiago+City%2C+Isabela+3311" target="_blank" rel="noopener noreferrer" class="footer-contact">
                            <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>
                            <span>Villasis, Santiago City<br>Isabela, 3311</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/NCnianAko" target="_blank" rel="noopener noreferrer" class="footer-contact">
                            <svg class="footer-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M13.5 21v-7h2.3l.4-3h-2.7V9.2c0-.9.3-1.5 1.6-1.5H16V5.1c-.3 0-1.2-.1-2.2-.1-2.2 0-3.8 1.3-3.8 3.8V11H7.5v3H10v7h3.5Z"/>
                            </svg>
                            <span>facebook.com/NCnianAko</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://icloudph.com/nc/sias/" target="_blank" rel="noopener noreferrer" class="footer-contact">
                            <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/>
                            </svg>
                            <span>SIAS Online</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-link-list">
                    <li><a href="{{ route('guest.index') }}">Home</a></li>
                    <li><a href="{{ route('guest.about') }}">About</a></li>
                    <li><a href="{{ route('guest.policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('guest.jobOpenLanding') }}">Job Vacancies</a></li>
                    <li><a href="{{ route('login_display') }}">Applicant Login</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">Policy</h4>
                <p class="footer-feature-text">We aim to present clear, accessible information about how recruitment-related data may be handled on this site.</p>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 Northeastern College. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="{{ route('guest.policy') }}">Privacy Policy</a>
                <a href="{{ route('guest.terms') }}">Terms of Service</a>
                <a href="{{ route('guest.cookie') }}">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const page = document.getElementById('guest-cookie-page');
        if (!page) return;

        const revealGroups = [
            ['.policy-hero-card', 0],
            ['.policy-side-card', 120],
            ['.policy-side-item', 160],
            ['.policy-card', 180],
        ];

        revealGroups.forEach(([selector, baseDelay]) => {
            page.querySelectorAll(selector).forEach((item, index) => {
                item.classList.add('guest-cookie-reveal');
                item.style.setProperty('--guest-cookie-delay', `${Math.min(baseDelay + ((index % 6) * 45), 420)}ms`);
            });
        });

        page.querySelectorAll('.policy-hero-card, .policy-side-card, .policy-side-item, .policy-card').forEach((item) => {
            item.classList.add('guest-cookie-card-motion');
        });

        page.querySelectorAll('.policy-kicker, .policy-side-item summary').forEach((item, index) => {
            item.classList.add('guest-cookie-pop');
            item.style.setProperty('--guest-cookie-delay', `${120 + ((index % 5) * 35)}ms`);
        });

        document.querySelectorAll('.site-footer .footer-contact, .site-footer .footer-link-list a, .site-footer .footer-bottom-links a').forEach((item) => {
            item.classList.add('guest-cookie-card-motion');
        });

        const animatedItems = Array.from(page.querySelectorAll('.guest-cookie-reveal'));
        const footer = document.querySelector('.site-footer');
        if (footer) {
            footer.classList.add('guest-cookie-reveal');
            footer.style.setProperty('--guest-cookie-delay', '180ms');
            animatedItems.push(footer);
        }

        if (!('IntersectionObserver' in window)) {
            animatedItems.forEach((item) => item.classList.add('is-scroll-animated'));
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('is-scroll-animated');
                    void entry.target.offsetWidth;
                    entry.target.classList.add('is-scroll-animated');
                } else {
                    entry.target.classList.remove('is-scroll-animated');
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -35px 0px',
        });

        animatedItems.forEach((item) => observer.observe(item));
    });
</script>
@endsection



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

<main class="policy-page">
    <section class="policy-hero">
        <div class="policy-shell">
            <div class="policy-hero-card">
                <span class="policy-kicker">Privacy Policy</span>
                <h1 class="policy-title">How we handle information on our recruitment pages.</h1>
                <p class="policy-copy">
                    This page explains the general way Northeastern College’s Human Resources pages may collect, use, and protect information shared by visitors and applicants while using the public-facing recruitment site.
                </p>
            </div>
        </div>
    </section>

    <section class="policy-content">
        <div class="policy-grid">
            <div class="policy-side-card">
                <h3>Policy overview</h3>
                <p>
                    We keep our policy language straightforward so visitors can understand what information is requested, why it is needed, and how it supports the recruitment process.
                </p>
                <ul class="policy-side-list">
                    <li>
                        <details class="policy-side-item">
                            <summary>Information you submit through forms</summary>
                            <p>Includes details entered in applications, uploaded files, and messages you send through recruitment forms.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Basic usage and application tracking</summary>
                            <p>We may log basic page and form activity to keep application flow stable and monitor submission progress.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Communication related to recruitment</summary>
                            <p>Contact details may be used to send interview schedules, status updates, and follow-up notices.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Reasonable safeguards for stored records</summary>
                            <p>Submitted records are handled with practical organizational and technical controls for recruitment processing.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Cookie and session usage on public pages</summary>
                            <p>Session and cookie data may be used for core functions like keeping form state and navigation context.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>Your rights over submitted information</summary>
                            <p>You may request corrections or updates to submitted data through official HR channels and verification steps.</p>
                        </details>
                    </li>
                    <li>
                        <details class="policy-side-item">
                            <summary>How policy updates are posted</summary>
                            <p>Policy text may be updated as processes evolve, and the latest page version is treated as the current reference.</p>
                        </details>
                    </li>
                </ul>
            </div>

            <div class="policy-stack">
                <div class="policy-card">
                    <h2>Information We Collect</h2>
                    <p>
                        When you interact with our recruitment pages, we may collect the information you voluntarily provide, such as your name, email address, application details, uploaded documents, and any other data required to process your inquiry or job application.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>How We Use Information</h2>
                    <p>
                        Information may be used to review applications, communicate updates, verify submitted records, improve recruitment workflows, and support internal hiring and onboarding processes related to available positions.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Protection and Retention</h2>
                    <p>
                        We aim to apply reasonable organizational and technical safeguards to protect submitted information. Records may be retained for recruitment, compliance, and administrative purposes based on institutional needs and applicable policies.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Cookies and Session Data</h2>
                    <p>
                        Public pages may use basic cookies or temporary session data to keep forms stable, remember navigation state, and improve usability. These are used to support normal site functions and are not intended for intrusive tracking.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Information Sharing</h2>
                    <p>
                        Submitted information is handled within authorized recruitment and administrative workflows. Data may be shared only with personnel who need access for hiring evaluation, verification, compliance, or official institutional processing.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Your Data Rights</h2>
                    <p>
                        You may request clarification, correction, or updates to information you submitted, subject to verification and institutional procedures. Requests should be directed through official Human Resources contact channels.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Policy Changes</h2>
                    <p>
                        This policy content may be revised from time to time to reflect operational, legal, or system updates. The latest posted version on this page serves as the current reference for recruitment-site privacy practices.
                    </p>
                </div>

                <div class="policy-card">
                    <h2>Contact</h2>
                    <p>
                        For questions about this page or recruitment-related privacy concerns, please contact the Human Resources Department through the official channels listed in the footer below.
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
@endsection



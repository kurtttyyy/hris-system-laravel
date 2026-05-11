@extends('layouts.app')

@section('content')
@include('layouts.header')
<div class="header-divider" aria-hidden="true"></div>

<style>
    .about-page {
        --about-ink: #0f172a;
        --about-muted: #5b6475;
        --about-line: rgba(15, 23, 42, 0.08);
        --about-brand: #157347;
        --about-brand-strong: #0d5c38;
        --about-accent: #f4c542;
        background:
            radial-gradient(circle at top left, rgba(21, 115, 71, 0.12), transparent 28%),
            radial-gradient(circle at top right, rgba(244, 197, 66, 0.16), transparent 22%),
            linear-gradient(180deg, #eef7f0 0%, #f8fafc 24%, #ffffff 100%);
        min-height: 100vh;
    }

    .about-hero {
        position: relative;
        overflow: hidden;
        padding: 5.5rem 0 4rem;
    }

    .about-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, rgba(4, 18, 16, 0.9), rgba(5, 76, 48, 0.68) 48%, rgba(11, 18, 32, 0.82));
    }

    .about-hero-shell {
        position: relative;
        z-index: 1;
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .about-hero-card {
        padding: 2.2rem;
        border-radius: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.16);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0.07));
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.22);
        backdrop-filter: blur(10px);
        color: #fff;
    }

    .about-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.25fr) minmax(280px, 0.75fr);
        gap: 1.5rem;
        align-items: stretch;
    }

    .about-kicker {
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

    .about-kicker::before {
        content: "";
        width: 0.55rem;
        height: 0.55rem;
        border-radius: 50%;
        background: var(--about-accent);
        box-shadow: 0 0 0 6px rgba(244, 197, 66, 0.18);
    }

    .about-hero-title {
        margin: 1.3rem 0 1rem;
        font-size: clamp(2.4rem, 5vw, 4.5rem);
        line-height: 1.03;
        font-weight: 800;
    }

    .about-hero-copy {
        max-width: 42rem;
        margin: 0;
        color: rgba(255, 255, 255, 0.86);
        line-height: 1.85;
        font-size: 1.02rem;
    }

    .about-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.75rem;
    }

    .about-hero-side {
        display: grid;
        gap: 1rem;
    }

    .about-hero-panel {
        padding: 1.35rem;
        border-radius: 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }

    .about-hero-panel span {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: #ffe9a6;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .about-hero-panel h3 {
        margin: 1rem 0 0.7rem;
        font-size: 1.4rem;
        font-weight: 800;
        line-height: 1.15;
        color: #fff;
    }

    .about-hero-panel p {
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.8;
    }

    .about-highlight-list {
        display: grid;
        gap: 0.8rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .about-highlight-list li {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        padding: 0.85rem 0.95rem;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.08);
    }

    .about-highlight-list strong {
        display: block;
        color: #fff;
        font-size: 0.96rem;
        font-weight: 800;
    }

    .about-highlight-list p {
        margin: 0.2rem 0 0;
        color: rgba(255, 255, 255, 0.76);
        line-height: 1.6;
        font-size: 0.9rem;
    }

    .about-highlight-icon {
        display: inline-flex;
        width: 2.3rem;
        height: 2.3rem;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: rgba(244, 197, 66, 0.18);
        color: #ffe082;
        font-weight: 800;
        flex: 0 0 2.3rem;
    }

    .about-metric {
        padding: 1rem 1.1rem;
        border-radius: 1.2rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .about-metric strong {
        display: block;
        font-size: 1.6rem;
        font-weight: 800;
    }

    .about-metric span {
        display: block;
        margin-top: 0.2rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .about-section {
        max-width: 1160px;
        margin: 0 auto;
        padding: 4rem 1.5rem 0;
    }

    .about-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.18fr) minmax(0, 0.82fr);
        gap: 1.5rem;
        align-items: start;
    }

    .about-panel,
    .about-side-card {
        padding: 2rem;
        border-radius: 1.8rem;
        border: 1px solid var(--about-line);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.07);
    }

    .about-section-title {
        margin: 0 0 0.9rem;
        color: var(--about-ink);
        font-size: clamp(1.8rem, 2.8vw, 2.7rem);
        font-weight: 800;
        line-height: 1.1;
    }

    .about-text {
        margin: 0;
        color: var(--about-muted);
        line-height: 1.85;
    }

    .about-feature-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .about-feature-card {
        padding: 1.15rem;
        border-radius: 1.35rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(241, 248, 243, 0.92));
        border: 1px solid rgba(15, 23, 42, 0.06);
    }

    .about-feature-card strong {
        display: block;
        margin-bottom: 0.45rem;
        color: var(--about-ink);
        font-size: 1rem;
        font-weight: 800;
    }

    .about-feature-card p {
        margin: 0;
        color: var(--about-muted);
        line-height: 1.7;
        font-size: 0.94rem;
    }

    .about-side-stack {
        display: grid;
        gap: 1rem;
    }

    .about-side-card h3 {
        margin: 0;
        color: var(--about-ink);
        font-size: 1.25rem;
        font-weight: 800;
    }

    .about-side-card p {
        margin: 0.85rem 0 0;
        color: var(--about-muted);
        line-height: 1.8;
    }

    .about-side-card.is-dark {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(21, 115, 71, 0.96));
        border-color: rgba(255, 255, 255, 0.06);
    }

    .about-side-card.is-dark h3,
    .about-side-card.is-dark p {
        color: #fff;
    }

    .about-side-card.is-dark p {
        color: rgba(255, 255, 255, 0.82);
    }

    .about-values {
        max-width: 1160px;
        margin: 0 auto;
        padding: 4rem 1.5rem 5rem;
    }

    .about-values-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.4rem;
    }

    .about-value-card {
        padding: 1.5rem;
        border-radius: 1.5rem;
        border: 1px solid var(--about-line);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.95));
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.05);
    }

    .about-value-card span {
        display: inline-flex;
        width: 2.6rem;
        height: 2.6rem;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #ecfdf3;
        color: var(--about-brand-strong);
        font-weight: 800;
    }

    .about-value-card h4 {
        margin: 1rem 0 0.6rem;
        color: var(--about-ink);
        font-size: 1.1rem;
        font-weight: 800;
    }

    .about-value-card p {
        margin: 0;
        color: var(--about-muted);
        line-height: 1.75;
    }

    #guest-about-page .guest-about-reveal {
        opacity: 1;
        transform: translateY(0);
        will-change: opacity, transform;
    }

    #guest-about-page .guest-about-reveal.is-scroll-animated {
        animation: guest-about-fade-up 0.72s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: var(--guest-about-delay, 0ms);
    }

    #guest-about-page .guest-about-card-motion,
    .site-footer .guest-about-card-motion {
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            border-color 0.25s ease;
    }

    #guest-about-page .guest-about-card-motion:hover,
    .site-footer .guest-about-card-motion:hover {
        transform: translateY(-4px);
    }

    #guest-about-page .guest-about-pop {
        animation: guest-about-pop 0.58s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: var(--guest-about-delay, 120ms);
    }

    @keyframes guest-about-fade-up {
        0% {
            opacity: 0;
            transform: translateY(26px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes guest-about-pop {
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
        #guest-about-page .guest-about-reveal,
        #guest-about-page .guest-about-pop,
        #guest-about-page .guest-about-card-motion,
        .site-footer .guest-about-card-motion {
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
        .about-hero-grid,
        .about-grid,
        .about-values-grid,
        .about-metrics,
        .about-feature-grid {
            grid-template-columns: 1fr;
        }

        .footer-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .about-hero {
            padding: 4.5rem 0 3.2rem;
        }

        .about-hero-card,
        .about-panel,
        .about-side-card {
            padding: 1.4rem;
            border-radius: 1.45rem;
        }

        .about-section,
        .about-values,
        .about-hero-shell {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .footer-shell {
            padding: 3rem 1rem 1.5rem;
        }

        .footer-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
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

<main id="guest-about-page" class="about-page">
    <section class="about-hero">
        <div class="about-hero-shell">
            <div class="about-hero-card">
                <div class="about-hero-grid">
                    <div>
                        <span class="about-kicker">About Northeastern College</span>
                        <h1 class="about-hero-title">A learning community where people build careers with purpose.</h1>
                        <p class="about-hero-copy">
                            Northeastern College creates opportunities for educators, administrators, and support professionals to do meaningful work in service of students and community growth. Our Human Resources Department helps connect the right people to roles where they can contribute, develop, and lead.
                        </p>

                        <div class="about-metrics">
                            <div class="about-metric">
                                <strong>{{ $openCount }}</strong>
                                <span>Open Positions</span>
                            </div>
                            <div class="about-metric">
                                <strong>{{ $department }}</strong>
                                <span>Departments</span>
                            </div>
                            <div class="about-metric">
                                <strong>{{ $employee }}</strong>
                                <span>Employees</span>
                            </div>
                        </div>
                    </div>

                    <div class="about-hero-side">
                        <div class="about-hero-panel">
                            <span>HR Focus</span>
                            <h3>Building a workplace that supports growth and service.</h3>
                            <p>
                                We hire people who want to contribute to a stronger academic community while growing in their own profession.
                            </p>
                        </div>

                        <div class="about-hero-panel">
                            <ul class="about-highlight-list">
                                <li>
                                    <span class="about-highlight-icon">1</span>
                                    <div>
                                        <strong>Clear Opportunities</strong>
                                        <p>Browse roles with a more focused and transparent hiring experience.</p>
                                    </div>
                                </li>
                                <li>
                                    <span class="about-highlight-icon">2</span>
                                    <div>
                                        <strong>Collaborative Culture</strong>
                                        <p>Work across teams that value support, professionalism, and shared progress.</p>
                                    </div>
                                </li>
                                <li>
                                    <span class="about-highlight-icon">3</span>
                                    <div>
                                        <strong>Long-Term Impact</strong>
                                        <p>Help shape an educational environment that serves both learners and community.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <section class="about-section">
        <div class="about-grid">
            <div class="about-panel">
                <h2 class="about-section-title">Why work with us</h2>
                <p class="about-text">
                    We believe a strong institution is built by people who care deeply about excellence, integrity, and service. That is why we design our hiring experience to be more welcoming, more transparent, and more aligned with the culture of a school community that values growth.
                </p>

                <div class="about-feature-grid">
                    <div class="about-feature-card">
                        <strong>Mission-Driven Work</strong>
                        <p>Every role supports an environment focused on student success, academic quality, and professional responsibility.</p>
                    </div>
                    <div class="about-feature-card">
                        <strong>Collaborative Teams</strong>
                        <p>Departments work together across teaching, administration, and operations to keep the campus moving forward.</p>
                    </div>
                    <div class="about-feature-card">
                        <strong>Career Growth</strong>
                        <p>Opportunities are built to help employees strengthen their skills, expand experience, and prepare for leadership.</p>
                    </div>
                    <div class="about-feature-card">
                        <strong>Community Impact</strong>
                        <p>The work done here contributes to a learning culture rooted in care, discipline, and long-term development.</p>
                    </div>
                </div>
            </div>

            <div class="about-side-stack">
                <div class="about-side-card">
                    <h3>Our hiring approach</h3>
                    <p>
                        We aim to make applications easier to understand, quicker to navigate, and more respectful of each applicant's time and effort.
                    </p>
                </div>

                <div class="about-side-card is-dark">
                    <h3>Human Resources Department</h3>
                    <p>
                        We support recruitment, onboarding, and employee development with the goal of helping the institution grow through capable and committed people.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-values">
        <h2 class="about-section-title">What we value</h2>
        <div class="about-values-grid">
            <div class="about-value-card">
                <span>01</span>
                <h4>Integrity</h4>
                <p>We value accountability, fairness, and professionalism in every hiring and employment decision.</p>
            </div>
            <div class="about-value-card">
                <span>02</span>
                <h4>Service</h4>
                <p>We support a campus culture where work is guided by responsibility to students, families, and the broader community.</p>
            </div>
            <div class="about-value-card">
                <span>03</span>
                <h4>Growth</h4>
                <p>We believe people do their best work when they are trusted, supported, and given room to keep improving.</p>
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
                    <li><a href="{{ route('guest.jobOpenLanding') }}">Job Vacancies</a></li>
                    <li><a href="{{ route('login_display') }}">Applicant Login</a></li>
                    <li><a href="{{ route('register') }}">Create Account</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">About</h4>
                <p class="footer-feature-text">Building careers, growing leaders, and creating meaningful opportunities for the next generation.</p>
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
        const page = document.getElementById('guest-about-page');
        if (!page) return;

        const revealGroups = [
            ['.about-hero-card', 0],
            ['.about-metric', 120],
            ['.about-hero-panel', 160],
            ['.about-panel', 120],
            ['.about-side-card', 160],
            ['.about-feature-card', 180],
            ['.about-values > .about-section-title', 120],
            ['.about-value-card', 180],
        ];

        revealGroups.forEach(([selector, baseDelay]) => {
            page.querySelectorAll(selector).forEach((item, index) => {
                item.classList.add('guest-about-reveal');
                item.style.setProperty('--guest-about-delay', `${Math.min(baseDelay + ((index % 6) * 45), 420)}ms`);
            });
        });

        page.querySelectorAll('.about-metric, .about-hero-panel, .about-highlight-list li, .about-panel, .about-side-card, .about-feature-card, .about-value-card').forEach((item) => {
            item.classList.add('guest-about-card-motion');
        });

        page.querySelectorAll('.about-kicker, .about-highlight-icon, .about-value-card span').forEach((item, index) => {
            item.classList.add('guest-about-pop');
            item.style.setProperty('--guest-about-delay', `${120 + ((index % 5) * 40)}ms`);
        });

        document.querySelectorAll('.site-footer .footer-contact, .site-footer .footer-link-list a, .site-footer .footer-bottom-links a').forEach((item) => {
            item.classList.add('guest-about-card-motion');
        });

        const animatedItems = Array.from(page.querySelectorAll('.guest-about-reveal'));
        const footer = document.querySelector('.site-footer');
        if (footer) {
            footer.classList.add('guest-about-reveal');
            footer.style.setProperty('--guest-about-delay', '180ms');
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




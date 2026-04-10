@extends('layouts.app')

@section('page-loader')
<div id="page-loader" class="page-loader" role="status">
    <div class="loader-content">
        <div class="loader-icon">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
        </div>
        <div class="loader-text">
            loading opportunities<span class="dots">...</span>
        </div>
    </div>
</div>
@endsection
@push('loader-script')
<script>
(function(){
    const loader = document.getElementById('page-loader');
    if(!loader) return;

    const minDelay = 1000;
    const start = Date.now();

    function hideLoader(){
        const elapsed = Date.now() - start;
        const remaining = Math.max(0, minDelay - elapsed);
        setTimeout(()=>{
            loader.classList.add('fade-out');
            setTimeout(()=> loader.remove(), 350);
        }, remaining);
    }

    if(document.readyState === 'complete'){
        hideLoader();
    } else {
        window.addEventListener('load', hideLoader);
    }
})();
</script>
@endpush


@section('content')
@include('layouts.header')  {{-- UNIVERSAL HEADER --}}
<div class="header-divider" aria-hidden="true"></div>

@if(session('popup_error'))
<div class="modal fade" id="applicationPopupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ session('popup_error') }}
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endif

@if(session('show_rating_modal') || session('success') === 'Submitted successfully')
<style>
    .rating-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    .rating-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.6rem 0.8rem;
        cursor: pointer;
        background: #fff;
    }
    .rating-option:hover {
        background: #f9fafb;
        border-color: #86efac;
    }
    .btn-check:checked + .rating-option {
        border-color: #16a34a;
        background: #f0fdf4;
    }
    .rating-stars {
        color: #eab308;
        letter-spacing: 1px;
        font-size: 1rem;
        line-height: 1;
    }
    .rating-label {
        font-size: 0.9rem;
        color: #374151;
        font-weight: 600;
    }
</style>
<div class="modal fade" id="applicationRatingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rate Your Application Experience</h5>
            </div>
            <form method="POST" action="{{ route('applicant.rating.store') }}">
                @csrf
                <div class="modal-body">
                    <p class="mb-1">Thank you for submitting your application. Please rate the system.</p>
                    <p class="mb-3">Thank you for applying. You can check your application at Application Status.</p>
                    <div class="rating-grid">
                        <input type="radio" class="btn-check" name="rating" id="rate5" value="5" required>
                        <label class="rating-option" for="rate5">
                            <span class="rating-label">Excellent</span>
                            <span class="rating-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                        </label>

                        <input type="radio" class="btn-check" name="rating" id="rate4" value="4" required>
                        <label class="rating-option" for="rate4">
                            <span class="rating-label">Very Good</span>
                            <span class="rating-stars">&#9733;&#9733;&#9733;&#9733;</span>
                        </label>

                        <input type="radio" class="btn-check" name="rating" id="rate3" value="3" required>
                        <label class="rating-option" for="rate3">
                            <span class="rating-label">Good</span>
                            <span class="rating-stars">&#9733;&#9733;&#9733;</span>
                        </label>

                        <input type="radio" class="btn-check" name="rating" id="rate2" value="2" required>
                        <label class="rating-option" for="rate2">
                            <span class="rating-label">Fair</span>
                            <span class="rating-stars">&#9733;&#9733;</span>
                        </label>

                        <input type="radio" class="btn-check" name="rating" id="rate1" value="1" required>
                        <label class="rating-option" for="rate1">
                            <span class="rating-label">Poor</span>
                            <span class="rating-stars">&#9733;</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .careers-page {
        --careers-ink: #0f172a;
        --careers-muted: #5b6475;
        --careers-line: rgba(15, 23, 42, 0.08);
        --careers-surface: rgba(255, 255, 255, 0.9);
        --careers-brand: #157347;
        --careers-brand-strong: #0d5c38;
        --careers-accent: #f4c542;
        --careers-shadow: 0 24px 70px rgba(15, 23, 42, 0.12);
        background:
            radial-gradient(circle at top left, rgba(21, 115, 71, 0.12), transparent 28%),
            radial-gradient(circle at top right, rgba(244, 197, 66, 0.2), transparent 24%),
            linear-gradient(180deg, #eef7f0 0%, #f8fafc 22%, #ffffff 100%);
    }

    #jobList .hover-card:hover {
        border-color: #22c55e !important;
        box-shadow:
            0 28px 60px rgba(34, 197, 94, 0.45),
            0 0 0 6px rgba(34, 197, 94, 0.30),
            0 0 34px rgba(34, 197, 94, 0.42) !important;
    }

    .careers-hero {
        min-height: 560px;
        padding: 6rem 0 7rem;
        display: flex;
        align-items: center;
    }

    .careers-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(115deg, rgba(4, 18, 16, 0.85), rgba(5, 76, 48, 0.6) 45%, rgba(11, 18, 32, 0.78));
        z-index: 1;
    }

    .careers-hero::after {
        content: "";
        position: absolute;
        inset: auto auto 2.5rem 6%;
        width: 16rem;
        height: 16rem;
        border-radius: 50%;
        background: rgba(244, 197, 66, 0.18);
        filter: blur(40px);
        z-index: 1;
    }

    .hero-backdrop {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.05), transparent 65%),
            linear-gradient(90deg, rgba(244, 197, 66, 0.1), transparent 40%);
        z-index: 2;
        pointer-events: none;
    }

    .hero-shell {
        position: relative;
        z-index: 3;
        max-width: 860px;
        margin: 0 auto;
        padding: 2.25rem;
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 2rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.06));
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.22);
        backdrop-filter: blur(10px);
    }

    .hero-kicker {
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

    .hero-kicker::before {
        content: "";
        width: 0.55rem;
        height: 0.55rem;
        border-radius: 50%;
        background: var(--careers-accent);
        box-shadow: 0 0 0 6px rgba(244, 197, 66, 0.18);
    }

    .hero-title {
        margin-top: 1.35rem;
        margin-bottom: 1rem;
        font-size: clamp(2.5rem, 5vw, 4.6rem);
        line-height: 1.04;
        font-weight: 800;
        color: #ffffff;
        text-shadow: 0 8px 30px rgba(0, 0, 0, 0.28);
    }

    .hero-highlight {
        color: #ffe082;
    }

    .hero-copy {
        max-width: 640px;
        margin: 0 auto 1.8rem;
        font-size: 1.05rem;
        line-height: 1.8;
        color: rgba(255, 255, 255, 0.88);
    }

    .hero-search-card {
        max-width: 760px;
        margin: 0 auto;
        padding: 0.9rem;
        border-radius: 1.5rem;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.16);
        box-shadow: 0 20px 55px rgba(0, 0, 0, 0.18);
        backdrop-filter: blur(10px);
    }

    .hero-metrics {
        margin-top: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.85rem;
    }

    .hero-metric {
        padding: 0.8rem 1rem;
        min-width: 140px;
        border-radius: 1.15rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #fff;
        text-align: left;
    }

    .hero-metric strong {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 1.15rem;
        font-weight: 800;
    }

    .hero-metric-rating {
        position: relative;
        display: inline-block;
        font-size: 0.9rem;
        line-height: 1;
        letter-spacing: 0.08em;
    }

    .hero-metric-rating-base {
        color: rgba(255, 255, 255, 0.28) !important;
    }

    .hero-metric-rating-fill {
        position: absolute;
        inset: 0 auto 0 0;
        overflow: hidden;
        color: #facc15 !important;
        text-shadow: 0 0 10px rgba(250, 204, 21, 0.35);
        white-space: nowrap;
    }

    .hero-metric span {
        display: block;
        margin-top: 0.15rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.82rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .filter-panel {
        position: relative;
        z-index: 4;
        max-width: 1160px;
        margin: -3.7rem auto 0;
        padding: 1.4rem;
        border-radius: 1.75rem;
        border: 1px solid rgba(255, 255, 255, 0.75);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 250, 252, 0.94));
        box-shadow: var(--careers-shadow);
    }

    .filter-intro {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1rem;
    }

    .filter-intro h2 {
        margin: 0;
        color: var(--careers-ink);
        font-size: 1.35rem;
        font-weight: 800;
    }

    .filter-intro p {
        margin: 0.35rem 0 0;
        color: var(--careers-muted);
        font-size: 0.94rem;
    }

    .filter-chip {
        padding: 0.55rem 0.95rem;
        border-radius: 999px;
        background: #ecfdf3;
        color: var(--careers-brand-strong);
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .filter-field {
        padding: 1rem;
        height: 100%;
        border-radius: 1.2rem;
        border: 1px solid var(--careers-line);
        background: linear-gradient(180deg, #ffffff, #f8fbf8);
    }

    .filter-field .form-label {
        margin-bottom: 0.5rem;
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--careers-brand-strong);
    }

    .filter-field .form-select {
        border-radius: 0.95rem;
        border-color: rgba(21, 115, 71, 0.16);
        min-height: 48px;
    }

    .filter-field .form-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 0.2rem rgba(34, 197, 94, 0.18);
    }

    .stats-shell {
        max-width: 1160px;
        margin: 1.5rem auto 0;
    }

    .stats-shell .stat-card {
        height: 100%;
        padding: 1.4rem 1.35rem;
        border: 1px solid rgba(15, 23, 42, 0.05);
        border-radius: 1.4rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.94), rgba(243, 247, 244, 0.96));
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.07);
        text-align: left;
    }

    .stats-shell .stat-label {
        display: block;
        margin-bottom: 0.7rem;
        color: var(--careers-muted);
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .stats-shell .stat-number {
        color: var(--careers-ink);
        font-size: clamp(1.8rem, 3vw, 2.35rem);
        font-weight: 800;
        line-height: 1;
    }

    .job-section {
        max-width: 1160px;
        margin: 4rem auto 0;
        padding-bottom: 4rem;
    }

    .section-heading {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: end;
        margin-bottom: 1.75rem;
    }

    .section-heading h2 {
        margin: 0;
        color: var(--careers-ink);
        font-size: clamp(1.8rem, 3vw, 2.4rem);
        font-weight: 800;
    }

    .section-heading p {
        margin: 0.55rem 0 0;
        max-width: 580px;
        color: var(--careers-muted);
        line-height: 1.7;
    }

    .section-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.7rem 1rem;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid var(--careers-line);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
        color: var(--careers-brand-strong);
        font-weight: 700;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    .job-card {
        position: relative;
        height: 100%;
        padding: 1.45rem;
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 1.6rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(246, 250, 247, 0.98));
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    }

    .job-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(135deg, rgba(21, 115, 71, 0.28), rgba(244, 197, 66, 0.16), rgba(21, 115, 71, 0.08));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .job-card:hover {
        transform: translateY(-6px);
    }

    .job-card-top {
        display: flex;
        justify-content: space-between;
        gap: 0.8rem;
        align-items: start;
        margin-bottom: 1rem;
    }

    .job-card-title {
        margin: 0;
        color: var(--careers-ink);
        font-size: 1.2rem;
        font-weight: 800;
    }

    .job-card-dept {
        margin-top: 0.28rem;
        color: var(--careers-brand-strong);
        font-weight: 700;
    }

    .job-card-copy {
        list-style: none;
        padding: 0;
        margin: 0 0 1rem;
    }

    .job-card-copy li {
        position: relative;
        padding-left: 1rem;
        margin-bottom: 0.7rem;
        color: var(--careers-muted);
        line-height: 1.65;
    }

    .job-card-copy li::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0.72rem;
        width: 0.42rem;
        height: 0.42rem;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--careers-brand), #34d399);
    }

    .job-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin-bottom: 1.15rem;
    }

    .job-meta-pill {
        padding: 0.5rem 0.85rem;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid rgba(15, 23, 42, 0.06);
        color: var(--careers-ink);
        font-size: 0.82rem;
        font-weight: 700;
    }

    .job-badge-stack {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin-bottom: 1.2rem;
    }

    .job-badge-stack .badge {
        padding: 0.55rem 0.8rem;
        border-radius: 999px;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .job-card .green-btn {
        min-height: 48px;
        border-radius: 1rem !important;
        background: linear-gradient(135deg, var(--careers-brand) 0%, #22c55e 100%) !important;
        border: none !important;
        box-shadow: 0 16px 28px rgba(21, 115, 71, 0.2);
    }

    .empty-state {
        border: 1px solid rgba(245, 158, 11, 0.18);
        border-radius: 1.3rem;
        background: linear-gradient(180deg, rgba(255, 251, 235, 0.96), rgba(255, 255, 255, 0.96));
        color: #9a6700;
        box-shadow: 0 16px 34px rgba(245, 158, 11, 0.08);
    }

    .site-footer {
        background:
            radial-gradient(circle at top left, rgba(21, 115, 71, 0.12), transparent 24%),
            linear-gradient(180deg, #0f1113 0%, #0b0c0d 100%);
        color: rgba(255, 255, 255, 0.82);
        margin-top: 4rem;
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
        grid-template-columns: 1.3fr 1fr 1fr 1.1fr;
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

    .footer-copy {
        max-width: 18rem;
        color: rgba(255, 255, 255, 0.72);
        line-height: 1.7;
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
        .careers-hero {
            min-height: 520px;
            padding: 5rem 0 6rem;
        }

        .filter-panel {
            margin-top: -2.8rem;
        }

        .section-heading,
        .filter-intro {
            flex-direction: column;
            align-items: start;
        }

        .footer-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .hero-shell {
            padding: 1.5rem;
            border-radius: 1.5rem;
        }

        .hero-search-card {
            padding: 0.7rem;
            border-radius: 1.2rem;
        }

        .search-input {
            flex-direction: column;
        }

        .search-input .form-control,
        .search-input .btn-hero {
            width: 100%;
            border-radius: 1rem !important;
        }

        .search-input .btn-hero {
            margin-left: 0;
            margin-top: 0.7rem;
        }

        .hero-metric {
            min-width: calc(50% - 0.5rem);
        }

        .filter-panel {
            padding: 1rem;
            border-radius: 1.35rem;
        }

        .job-card {
            padding: 1.15rem;
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

    @media (max-width: 575.98px) {
        .hero-metric {
            width: 100%;
        }
    }
</style>

<main class="careers-page">
<section class="hero careers-hero text-white position-relative overflow-hidden">

    <!-- Carousel Background -->
    <div id="heroCarousel" class="carousel slide carousel-fade position-absolute top-0 start-0 w-100 h-100"
         data-bs-ride="carousel" data-bs-interval="4000">

        <div class="carousel-inner h-100">
            <div class="carousel-item active h-90">
                <img src="{{ asset('images/banner2.png') }}"
                     class="d-block w-100 h-90 object-fit-cover carousel-dark-img"
                     alt="Careers">
            </div>
            <div class="carousel-item h-90">
                <img src="{{ asset('images/sirV.png') }}"
                     class="d-block w-100 h-90 object-fit-cover "
                     alt="Team">
            </div>
            <div class="carousel-item h-90">
                <img src="{{ asset('images/carousel.jpg') }}"
                     class="d-block w-100 h-90 object-fit-cover carousel-dark-img"
                     alt="Growth">
            </div>
        </div>
    </div>

    <div class="hero-backdrop"></div>

    <!-- Hero Content -->
    <div class="container text-center position-relative z-3">
        <div class="hero-shell animated-card2 delay-5">
            <span class="hero-kicker">Career Opportunities 2026</span>
            <h1 class="hero-title">
                Find a role where your <span class="hero-highlight">skills can grow</span>
            </h1>

            <p class="hero-copy">
                Explore meaningful opportunities, discover the teams behind them, and take the next confident step in your professional journey with us.
            </p>

            <form id="jobSearchForm" class="hero-search-card animated-card2 delay-5" role="search">
                <div class="input-group search-input">
                    <input id="jobSearchInput" type="search" class="form-control"
                           placeholder="Search job titles, keywords..."
                           aria-label="Search">
                    <button class="btn btn-hero" type="submit">Search</button>
                </div>
            </form>

            <div class="hero-metrics">
                <div class="hero-metric">
                    <strong>{{ $openCount }}</strong>
                    <span>Open Positions</span>
                </div>
                <div class="hero-metric">
                    <strong>{{ $department }}</strong>
                    <span>Departments</span>
                </div>
                <div class="hero-metric">
                    <strong>{{ $employee }}</strong>
                    <span>Employees</span>
                </div>
                <div class="hero-metric">
                    @php
                        $ratingValue = is_null($companyRating ?? null) ? 0.0 : max(0, min(5, (float) $companyRating));
                        $ratingWidth = ($ratingValue / 5) * 100;
                    @endphp
                    <strong>
                        <span>{{ number_format($ratingValue, 1) }}</span>
                        <span class="hero-metric-rating" aria-label="{{ number_format($ratingValue, 1) }} out of 5 stars">
                            <span class="hero-metric-rating-base">★★★★★</span>
                            <span class="hero-metric-rating-fill" style="width: {{ number_format($ratingWidth, 2, '.', '') }}%;">★★★★★</span>
                        </span>
                    </strong>
                    <span>Company Rating</span>
                </div>
            </div>
        </div>
    </div>
</section>


            <div class="container position-relative z-2 animated-card2 delay-5">
            <div class="filter-panel">
                <div class="filter-intro">
                    <div>
                        <h2>Refine Your Search</h2>
                        <p>Filter openings by department, employment type, and location to quickly find the role that fits you best.</p>
                    </div>
                    <span class="filter-chip">{{ $open_position->count() }} opportunities available</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="filter-field">
                            <label class="form-label small mb-1">Department</label>
                            <select id="departmentFilter" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($open_position->pluck('department')->unique() as $departments)
                                    <option value="{{ $departments }}">{{ $departments }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="filter-field">
                            <label class="form-label small mb-1">Employment Type</label>
                            <select id="employmentFilter" class="form-select">
                                <option value="">All Types</option>
                                @foreach($open_position->pluck('employment')->unique() as $employments)
                                    <option value="{{ $employments }}">{{ $employments }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="filter-field">
                            <label class="form-label small mb-1">Location</label>
                            <select id="locationFilter" class="form-select">
                                <option value="">All Location</option>
                                @foreach($open_position->pluck('location')->unique() as $locations)
                                    <option value="{{ $locations }}">{{ $locations }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<div class="container job-section">
    <div class="section-heading">
        <div>
            <h2>Job Vacancies</h2>
            <p>Browse our latest openings and choose the path that matches your strengths, goals, and preferred work environment.</p>
        </div>
        <div class="section-pill">Updated opportunities across multiple teams</div>
    </div>

    <div id="jobList" class="row g-4">
        @foreach ($open_position as $position)
            <div class="col-12 col-md-6 job-item"
                data-title="{{ Str::lower($position->title) }}"
                data-department="{{ Str::lower($position->department) }}"
                data-employment="{{ Str::lower($position->employment) }}"
                data-location="{{ Str::lower($position->location) }}"
                data-description="{{ Str::lower($position->job_description) }}"
            >
                <div class="job-card card animated-card delay-5 hover-card border-1">
                    <div class="job-card-top">
                        <div>
                            <h5 class="job-card-title">{{ $position->title }}</h5>
                            <div class="job-card-dept">{{ $position->department }}</div>
                        </div>
                        @php
                            $postedDays = $position->created_at
                                ? now()->diffInDays($position->created_at, true)
                                : null;
                            $postedDaysWhole = is_null($postedDays) ? null : (int) floor($postedDays);
                        @endphp
                        @if (!is_null($postedDaysWhole) && $postedDaysWhole <= 3)
                            <span class="badge bg-success">New</span>
                        @elseif (!is_null($postedDaysWhole))
                            <span class="badge bg-secondary">{{ $postedDaysWhole }} {{ $postedDaysWhole === 1 ? 'day' : 'days' }} ago</span>
                        @endif
                    </div>

                    @php
                        $lines = preg_split("/\r\n|\n|\r/", $position->job_description);
                    @endphp

                    <div class="job-meta-row">
                        <span class="job-meta-pill">{{ $position->location }}</span>
                        <span class="job-meta-pill">{{ $position->employment }}</span>
                        <span class="job-meta-pill">{{ $position->work_mode }}</span>
                    </div>

                    <ul class="job-card-copy">
                        @foreach (array_slice($lines, 0, 3) as $line)
                            <li>
                                {{
                                    Str::limit(
                                        ltrim($line, "•- "),
                                        150,
                                        '......'
                                    )
                                }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="job-badge-stack">
                        @if ($position->employment == "Full-Time")
                            <span class="badge bg-success bg-opacity-25 text-success me-1 bordered-badge">Full - Time</span>
                            <span class="badge bg-purple-light-opacity me-1">{{ $position->work_mode }}</span>
                        @else
                            <span class="badge bg-success bg-opacity-25 text-success me-1 bordered-badge">Part - Time</span>
                            <span class="badge bg-purple-light-opacity me-1">{{ $position->work_mode }}</span>
                        @endif
                    </div>

                    <button
                        onclick="window.location.href='{{ route('guest.jobOpen', $position->id) }}';"
                        class="btn btn-primary w-100 green-btn"
                    >
                        View Details & Apply
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div id="noResultsMessage" class="alert empty-state mt-4 d-none" role="alert">
        No jobs matched your filters.
    </div>
</div>

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
                    <li><a href="#heroCarousel">Home</a></li>
                    <li><a href="{{ route('guest.jobOpenLanding') }}">Job Vacancies</a></li>
                    <li><a href="#departmentFilter">Departments</a></li>
                    <li><a href="{{ route('login_display') }}">Applicant Login</a></li>
                    <li><a href="{{ route('register') }}">Create Account</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">About</h4>
                <p class="footer-feature-text">Building careers, growing leaders, and creating meaningful opportunities for the next generation.</p>
            </div>

            <div>
                <h4 class="footer-title">Newsletter</h4>
                <p class="newsletter-copy">Subscribe to receive campus announcements and updates.</p>
                <form class="newsletter-form" onsubmit="event.preventDefault()">
                    <div class="newsletter-input-wrap">
                        <input type="email" class="newsletter-input" placeholder="Enter your email" aria-label="Email address">
                        <svg class="newsletter-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4 8 8 5 8-5"/>
                        </svg>
                    </div>
                    <button type="submit" class="newsletter-btn">Subscribe</button>
                </form>
                <p class="newsletter-note">We respect your privacy. Unsubscribe at any time.</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 Northeastern College. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jobSearchForm = document.getElementById('jobSearchForm');
        const searchInput = document.getElementById('jobSearchInput');
        const departmentFilter = document.getElementById('departmentFilter');
        const employmentFilter = document.getElementById('employmentFilter');
        const locationFilter = document.getElementById('locationFilter');
        const jobItems = Array.from(document.querySelectorAll('.job-item'));
        const noResultsMessage = document.getElementById('noResultsMessage');

        function normalize(value) {
            return (value || '').toString().trim().toLowerCase();
        }

        function applyFilters() {
            const searchTerm = normalize(searchInput?.value);
            const selectedDepartment = normalize(departmentFilter?.value);
            const selectedEmployment = normalize(employmentFilter?.value);
            const selectedLocation = normalize(locationFilter?.value);

            let visibleCount = 0;

            jobItems.forEach((item) => {
                const title = normalize(item.dataset.title);
                const department = normalize(item.dataset.department);
                const employment = normalize(item.dataset.employment);
                const location = normalize(item.dataset.location);
                const description = normalize(item.dataset.description);

                const matchesSearch = !searchTerm ||
                    title.includes(searchTerm) ||
                    description.includes(searchTerm) ||
                    department.includes(searchTerm) ||
                    location.includes(searchTerm);

                const matchesDepartment = !selectedDepartment || department === selectedDepartment;
                const matchesEmployment = !selectedEmployment || employment === selectedEmployment;
                const matchesLocation = !selectedLocation || location === selectedLocation;

                const isVisible = matchesSearch && matchesDepartment && matchesEmployment && matchesLocation;

                item.classList.toggle('d-none', !isVisible);
                if (isVisible) visibleCount++;
            });

            noResultsMessage.classList.toggle('d-none', visibleCount > 0);
        }

        jobSearchForm?.addEventListener('submit', function (event) {
            event.preventDefault();
            applyFilters();
        });

        searchInput?.addEventListener('input', applyFilters);
        departmentFilter?.addEventListener('change', applyFilters);
        employmentFilter?.addEventListener('change', applyFilters);
        locationFilter?.addEventListener('change', applyFilters);
    });
</script>

@if(session('popup_error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('applicationPopupModal');
        if (!modalEl) return;
        const popup = new bootstrap.Modal(modalEl);
        popup.show();
    });
</script>
@endif

@if(session('show_rating_modal') || session('success') === 'Submitted successfully')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ratingModalEl = document.getElementById('applicationRatingModal');
        if (!ratingModalEl) return;
        const ratingPopup = new bootstrap.Modal(ratingModalEl);
        ratingPopup.show();
    });
</script>
@endif



</main>
@endsection

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

<main>
<section class="hero text-white py-5 position-relative overflow-hidden">

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
                <img src="{{ asset('images/Banner1.png') }}"
                     class="d-block w-100 h-90 object-fit-cover "
                     alt="Team">
            </div>
            <div class="carousel-item h-90">
                <img src="{{ asset('images/banner3.png') }}"
                     class="d-block w-100 h-90 object-fit-cover carousel-dark-img"
                     alt="Growth">
            </div>
        </div>
    </div>

    <!-- Hero Content -->
    <div class="container text-center py-5 position-relative z-3">
        <h1 class="display-5 fw-bold text-warning">
            Build Your Future With Us
        </h1>

        <p class="lead mb-4 text-warning">
            Explore career opportunities and take the first step towards your dream job
        </p>

        <form id="jobSearchForm" class="d-flex justify-content-center mb-4 animated-card2 delay-5" role="search">
            <div class="input-group search-input" style="max-width:720px;">
                <input id="jobSearchInput" type="search" class="form-control"
                       placeholder="Search job titles, keywords..."
                       aria-label="Search">
                <button class="btn btn-hero" type="submit">Search</button>
            </div>
        </form>
    </div>
</section>


            <div class="container mt-4 position-relative z-2 animated-card2 delay-5">
            <div class="filter-card bg-white rounded shadow-sm p-4 mx-auto" style="max-width:1100px; margin-top:-40px;">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Department</label>
                        <select id="departmentFilter" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($open_position->pluck('department')->unique() as $departments)
                                <option value="{{ $departments }}">{{ $departments }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Employment Type</label>
                        <select id="employmentFilter" class="form-select">
                            <option value="">All Types</option>
                            @foreach($open_position->pluck('employment')->unique() as $employments)
                                <option value="{{ $employments }}">{{ $employments }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
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

        <div class="container stats-section mt-4 ">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-white rounded p-3 animated-card delay-5">
                        <div class="stat-number fw-bold display-6">{{$openCount}}</div>
                        <div class="stat-label small text-muted">Open Positions</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-white rounded p-3 animated-card delay-5">
                        <div class="stat-number fw-bold display-6">{{$department}}</div>
                        <div class="stat-label small text-muted">Departments</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-white rounded p-3 animated-card delay-5">
                        <div class="stat-number fw-bold display-6">{{$employee}}</div>
                        <div class="stat-label small text-muted">Employees</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-white rounded p-3 animated-card delay-5">
                        <div class="stat-number fw-bold display-6">
                            4.8<span class="star">★</span>
                        </div>
                        <div class="stat-label small text-muted">Company Rating</div>
                    </div>
                </div>
            </div>
        </div>

<div class="container mt-5">
    <h2 class="fw-bold text-start">Job Vacancies</h2>

    <div id="jobList" class="row">
        @foreach ($open_position as $position)
            <div class="col-12 col-md-6 job-item"
                data-title="{{ Str::lower($position->title) }}"
                data-department="{{ Str::lower($position->department) }}"
                data-employment="{{ Str::lower($position->employment) }}"
                data-location="{{ Str::lower($position->location) }}"
                data-description="{{ Str::lower($position->job_description) }}"
            >
                <div class="card p-3 rounded shadow-sm mb-4 animated-card delay-5 hover-card border-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-1">{{ $position->title }}</h5>
                        @php
                            $postedDays = $position->created_at
                                ? now()->diffInDays($position->created_at)
                                : null;
                        @endphp
                        @if (!is_null($postedDays) && $postedDays <= 3)
                            <span class="badge bg-success">New</span>
                        @elseif (!is_null($postedDays))
                            <span class="badge bg-secondary">{{ $postedDays }} {{ $postedDays === 1 ? 'day' : 'days' }} ago</span>
                        @endif
                    </div>

                    <small class="text-muted">{{ $position->department }}</small>

                    @php
                        $lines = preg_split("/\r\n|\n|\r/", $position->job_description);
                    @endphp

                    <ul class="list-unstyled mt-2 mb-3">
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



                    <div class="mb-3">
                        @if ($position->employment == "Full-Time")
                            <span class="badge bg-success bg-opacity-25 text-success me-1 bordered-badge">Full - Time</span>
                            <span class="badge bg-purple-light-opacity me-1">{{ $position->work_mode }}</span>
                        @else
                            <span class="badge bg-success bg-opacity-25 text-success me-1 bordered-badge">Part - Time</span>
                            <span class="badge bg-purple-light-opacity me-1">{{ $position->work_mode }}</span>
                        @endif
                    </div>

                    <button
                        onclick="window.location.href='{{ route('guest.jobOpen', $position->id) }}'";
                        class="btn btn-primary w-100 green-btn"
                    >
                        View Details & Apply
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div id="noResultsMessage" class="alert alert-warning mt-3 d-none" role="alert">
        No jobs matched your filters.
    </div>
</div>

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



</main>
@endsection

@once
<style>
    [data-admin-scroll-header],
    [data-admin-scroll-card] {
        transition: transform 0.28s ease, box-shadow 0.28s ease, opacity 0.28s ease, filter 0.28s ease;
        transform-origin: top center;
    }

    [data-admin-scroll-header].is-scrolled [data-admin-scroll-card] {
        transform: scale(0.985);
        box-shadow: 0 16px 36px rgba(3, 19, 29, 0.22);
        filter: saturate(0.98);
    }
</style>

<script>
    (function () {
        if (window.__adminHeaderScrollBehaviorInitialized) {
            return;
        }

        window.__adminHeaderScrollBehaviorInitialized = true;

        const updateAdminHeadersOnScroll = () => {
            const isScrolled = window.scrollY > 24;
            document.querySelectorAll('[data-admin-scroll-header]').forEach((header) => {
                header.classList.toggle('is-scrolled', isScrolled);
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateAdminHeadersOnScroll, { once: true });
        } else {
            updateAdminHeadersOnScroll();
        }

        window.addEventListener('scroll', updateAdminHeadersOnScroll, { passive: true });
    })();
</script>
@endonce

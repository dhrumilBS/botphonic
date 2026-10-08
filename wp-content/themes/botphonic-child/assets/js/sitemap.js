document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('sitemapSearch');
    const sections = document.querySelectorAll('.section-item');
    const noResults = document.getElementById('sitemapNoResults');

    if (!searchInput) return;
    function debounce(fn, delay) {
        let timeout;
        return function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, arguments), delay);
        };
    }

    function handleSearch() {
        const query = searchInput.value.toLowerCase().trim();
        let totalMatches = 0;

        sections.forEach(section => {
            const title = section.querySelector('h3').textContent.toLowerCase();
            const links = section.querySelectorAll('a');
            let sectionMatches = 0;
            if (query && title.includes(query)) {
                section.style.display = '';
                links.forEach(link => link.style.display = '');
                totalMatches++;
                return;
            }

            links.forEach(link => {
                const text = link.textContent.toLowerCase();
                const href = link.getAttribute('href').toLowerCase();

                if (!query || text.includes(query) || href.includes(query)) {
                    link.style.display = '';
                    sectionMatches++;
                    totalMatches++;
                } else {
                    link.style.display = 'none';
                }
            });

            section.style.display = sectionMatches > 0 ? '' : 'none';
        });

        noResults.style.display =
            totalMatches === 0 && query !== '' ? 'block' : 'none';
    }

    searchInput.addEventListener('input', debounce(handleSearch, 150));

    const schema = {
        "@context": "https://schema.org",
        "@type": "SiteNavigationElement",
        "name": "Botphonic Sitemap",
        "url": "https://botphonic.ai/sitemap/"
    };

    const script = document.createElement('script');
    script.type = 'application/ld+json';
    script.textContent = JSON.stringify(schema);
    document.head.appendChild(script);
});
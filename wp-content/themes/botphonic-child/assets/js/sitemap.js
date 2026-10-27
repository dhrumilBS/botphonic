document.addEventListener('DOMContentLoaded', function () {
	'use strict';

	const sitemap = document.querySelector('.sitemap-page');
	if (!sitemap) return;

	const searchInput = sitemap.querySelector('#sitemapSearch');
	const noResults = sitemap.querySelector('#sitemapNoResults');
	const sections = sitemap.querySelectorAll(
		'.section-list > .section-item'
	);

	if (!searchInput || !noResults || !sections.length) return;

	function handleSearch() {
		const query = searchInput.value.trim().toLowerCase();
		let totalMatches = 0;

		sections.forEach(function (section) {
			const heading = section.querySelector('h2, h3');
			const links = section.querySelectorAll('.section-body a');

			const title = heading
			? heading.textContent.toLowerCase().trim()
			: '';

			const headingMatches =
				  query !== '' && title.includes(query);

			let sectionMatches = 0;

			links.forEach(function (link) {
				const text = link.textContent.toLowerCase();
				const href = (link.getAttribute('href') || '').toLowerCase();

				const matches =
					  !query ||
					  headingMatches ||
					  text.includes(query) ||
					  href.includes(query);

				link.hidden = !matches;

				if (matches) {
					sectionMatches++;
					totalMatches++;
				}
			});

			section.hidden = sectionMatches === 0;
		});

		noResults.hidden = !query || totalMatches > 0;
		noResults.style.display =
			query && totalMatches === 0 ? 'block' : 'none';
	}

	searchInput.addEventListener('input', handleSearch);
	searchInput.addEventListener('search', handleSearch);

	handleSearch();
});

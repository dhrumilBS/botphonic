/* Optimized User Tracking Code */
jQuery(document).ready(function ($) {
	const originUrl = window.location.origin;
	const curUrl = window.location.href;
	const thisPage = window.location.protocol + "//" + window.location.hostname + window.location.pathname;
	const getRefererUrl = () => {
		let refererUrl = document.referrer;
		if (refererUrl.includes("?")) {
			refererUrl = refererUrl.split("?")[0];
		}
		return refererUrl || "Direct";
	};
	const isUrlValid = (url) => {
		try {
			new URL(url);
			return true;
		} catch (_) {
			return false;
		}
	};
	const changeParametersForDirect = (encodedParams) => {
		const selectors = ['header', '.main-wrapper','.wrapper', '.elementor-location-footer'];
		selectors.forEach(selector => {
			$(selector).find('a').each(function () {
				let link = $(this).attr('href');
				if (isUrlValid(link)) {
					if (!link.includes('referer=') && !link.includes('origin_referer=')) {
						const separator = link.includes('?') ? '&' : '?';
						$(this).attr('href', link + separator + encodedParams);
					}
				}
			});
		});
	};
	const referer = getRefererUrl();
	if (curUrl.startsWith(originUrl) && curUrl.includes('?')) {
		const params = new URLSearchParams(window.location.search);
		const hasOriginReferer = params.has('origin_referer');
		if (hasOriginReferer) {
			params.set('referer', referer);
			const newParamsStr = params.toString();
			changeParametersForDirect(newParamsStr);
		} else {
			params.set('referer', referer);
			params.set('origin_referer', referer);
			const newParamsStr = params.toString();
			changeParametersForDirect(newParamsStr);
		}
	} else {
		if (referer === "Direct") {
			const queryString = `utm_source=Direct&utm_medium=${thisPage}&referer=Direct&origin_referer=${thisPage}`;
			changeParametersForDirect(queryString);
		} else if (!referer.includes(window.location.hostname)) {
			const queryString = `utm_source=website_organic&utm_medium=${referer}&referer=${referer}&origin_referer=${referer}`;
			changeParametersForDirect(queryString);
		}
	}
});
/* End Optimized User Tracking Code */
document.addEventListener('wpcf7submit', function(event) {
	const form = event.target;
	const formWrapper = form.closest('.wpcf7');

	if (!formWrapper || formWrapper.getAttribute('data-wpcf7-id') !== '3104') return;

	const email = form.querySelector('input[name="your-email"]')?.value.trim() || '';
	const red = form.querySelector('input[name="red"]')?.value.trim() || '';
	const ref = form.querySelector('input[name="ref"]')?.value.trim() || '';

	const redirectUrl = `https://app.botphonic.ai/register/?red=${encodeURIComponent(red)}&ref=${encodeURIComponent(ref)}&email=${encodeURIComponent(email)}`;
window.location.href = redirectUrl;
}, false);
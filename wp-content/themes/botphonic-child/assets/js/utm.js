document.addEventListener("DOMContentLoaded", () => {

    function isCrawler() {
        const ua = navigator.userAgent.toLowerCase();

        const crawlers = [
            "googlebot",
            "facebookexternalhit",
            "linkedinbot"
        ];

        return crawlers.some(bot => ua.includes(bot));
    }

    const thisDomain = window.location.hostname.replace(/^www\./, '');
    console.log(thisDomain);
    

    const getReferer = () => {
        if (!document.referrer) return "direct"; // FIX 1: Use "direct" instead of thisDomain

        try {
            return new URL(document.referrer).hostname.replace(/^www\./, '');
        } catch (_) {
            return "direct";
        }
    };

    const isValidUrl = (url) => {
        try {
            new URL(url);
            return true;
        } catch (_) {
            return false;
        }
    };

    const isSameDomain = (url) => {
        try {
            return new URL(url).hostname.replace(/^www\./, '') === thisDomain;
        } catch (_) {
            return false;
        }
    };

    const appendUTMParams = (encodedParams) => {
        const linkSelectors = ['header', '.main-wrapper', '.wrapper', 'footer'];
        linkSelectors.forEach((selector) => {
            document.querySelectorAll(`${selector} a`).forEach((a) => {
                const link = a.getAttribute("href");
                if (isValidUrl(link) && isSameDomain(link)) {
                    const sep = link.includes("?") ? "&" : "?";
                    a.setAttribute("href", link + sep + encodedParams);
                }
            });
        });
    };

    const getExistingUTMs = () => {
        const params = new URLSearchParams(window.location.search);
        const allowed = ["utm_source", "utm_medium", "utm_campaign"];
        const collected = new URLSearchParams();
        allowed.forEach((key) => {
            if (params.has(key)) collected.set(key, params.get(key));
        });
        return collected.toString();
    };

    const populateFormFields = (utm_source, utm_medium, utm_campaign) => {
        const formUTMs = {
            utm_source_cf7: utm_source,
            utm_medium_cf7: utm_medium,
            utm_campaign_cf7: utm_campaign
        };

        setTimeout(() => {
            Object.keys(formUTMs).forEach((key) => {
                document.querySelectorAll(`input[name="${key}"]`).forEach((field) => {
                    field.value = formUTMs[key];
                });
            });
        }, 500);
    };

    const referer = getReferer();
    const utmFromUrl = getExistingUTMs();

    if (!isCrawler()) {
        if (utmFromUrl) {
            appendUTMParams(utmFromUrl);
            const params = new URLSearchParams(utmFromUrl);
            populateFormFields(
                params.get("utm_source") || "",
                params.get("utm_medium") || "",
                params.get("utm_campaign") || ""
            );
        } else {
            const utm_source = referer;
            const utm_medium = "organic";
            const utm_campaign = "seo";
            const defaultUTMs = new URLSearchParams({ utm_source, utm_medium, utm_campaign }).toString();

            populateFormFields(utm_source, utm_medium, utm_campaign);
            appendUTMParams(defaultUTMs);
        }
    } 
});
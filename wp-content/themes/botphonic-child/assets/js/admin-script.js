wp.domReady(() => {
    const siteOrigin = window.location.origin;
    let isUpdating = false;
    let lastProcessedUrl = '';

    // ---- Rules config: tweak here, nothing else needs to change ----
    // For each checkbox: true = must be checked, false = must be unchecked,
    // null/omitted = leave as-is (don't touch it).
    const RULES = {
        internal: {
            newTab: true,
            nofollow: null,
            sponsored: null
        },
        external: {
            newTab: true,
            nofollow: true,
            sponsored: null // leave untouched
        }
    };
    // ------------------------------------------------------------------

    function getCheckboxByLabel(popover, matchText) {
        const labels = popover.querySelectorAll('label');
        for (const label of labels) {
            if (label.textContent.trim().toLowerCase().includes(matchText)) {
                if (label.htmlFor) {
                    const input = popover.querySelector('#' + CSS.escape(label.htmlFor));
                    if (input) return input;
                }
                const wrapper = label.closest('.components-checkbox-control, .components-toggle-control, .components-base-control');
                if (wrapper) {
                    const input = wrapper.querySelector('input[type="checkbox"]');
                    if (input) return input;
                }
            }
        }
        return null;
    }

    function expandAdvancedIfNeeded(popover) {
        const btn = [...popover.querySelectorAll('button')].find(
            b =>
                b.textContent.trim().toLowerCase() === 'advanced' &&
                b.getAttribute('aria-expanded') === 'false'
        );
        if (btn) {
            btn.click();
            return true;
        }
        return false;
    }

    // Only clicks if the checkbox's current state doesn't match desired.
    // desired === null/undefined means "leave it alone".
    function setCheckbox(checkbox, desired) {
        if (!checkbox || desired === null || desired === undefined) return;
        if (checkbox.checked !== desired) checkbox.click();
    }

    function applyRules(popover, isInternal) {
        const newTab = getCheckboxByLabel(popover, 'new tab');
        const nofollow = getCheckboxByLabel(popover, 'nofollow');
        const sponsored = getCheckboxByLabel(popover, 'sponsored');

        if (!newTab) return false; // not enough of the UI is ready yet

        const rule = isInternal ? RULES.internal : RULES.external;

        isUpdating = true;
        setCheckbox(newTab, rule.newTab);
        setCheckbox(nofollow, rule.nofollow);
        setCheckbox(sponsored, rule.sponsored);
        return true;
    }

    function updateLinkOptions() {
        if (isUpdating) return;
        const popover = document.querySelector(
            '.components-popover.block-editor-link-control, .block-editor-link-control'
        );
        if (!popover) {
            lastProcessedUrl = '';
            return;
        }
        const urlInput = popover.querySelector(
            'input[type="url"], input[type="text"]'
        );
        if (!urlInput) return;

        const url = urlInput.value.trim();
        if (!url) {
            lastProcessedUrl = '';
            return;
        }
        if (url === lastProcessedUrl) return;

        let isInternal = false;
        try {
            const parsed = new URL(url, siteOrigin);
            isInternal = parsed.origin === siteOrigin;
        } catch {
            return;
        }

        if (expandAdvancedIfNeeded(popover)) return; // wait for panel to expand, next mutation re-triggers us

        const applied = applyRules(popover, isInternal);
        if (!applied) return;

        lastProcessedUrl = url;
        setTimeout(() => {
            isUpdating = false;
        }, 100);
    }

    const observer = new MutationObserver(() => {
        if (!isUpdating) {
            updateLinkOptions();
        }
    });
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    document.body.addEventListener('input', e => {
        if (
            e.target.matches('.block-editor-link-control input[type="url"], .block-editor-link-control input[type="text"]')
        ) {
            lastProcessedUrl = '';
            setTimeout(updateLinkOptions, 200);
        }
    });
});
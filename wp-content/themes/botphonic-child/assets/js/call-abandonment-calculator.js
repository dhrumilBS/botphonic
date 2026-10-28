(function () {
    'use strict';

    function initCalculator(root) {
        if (root.dataset.bpgAbndReady === '1') return;
        root.dataset.bpgAbndReady = '1';

        var form = root.querySelector('.bpg-abnd__form');
        var abandoned = form.elements.abandoned_calls;
        var incoming = form.elements.incoming_calls;
        var error = root.querySelector('.bpg-abnd__error');
        var result = root.querySelector('[data-result]');
        var detail = root.querySelector('[data-detail]');
        var formatter = new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 });

        function clearError() {
            error.textContent = '';
            error.hidden = true;
            abandoned.removeAttribute('aria-invalid');
            incoming.removeAttribute('aria-invalid');
        }

        function showError(message, field) {
            clearError();
            error.textContent = message;
            error.hidden = false;
            if (field) {
                field.setAttribute('aria-invalid', 'true');
                field.focus();
            }
            result.textContent = '—';
            detail.textContent = 'Correct the inputs and calculate again.';
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            clearError();
            var a = Number(abandoned.value);
            var t = Number(incoming.value);

            if (abandoned.value.trim() === '' || !Number.isSafeInteger(a) || a < 0) {
                showError('Enter a valid, non-negative whole number of abandoned calls.', abandoned);
                return;
            }
            if (incoming.value.trim() === '' || !Number.isSafeInteger(t) || t <= 0) {
                showError('Enter a valid whole number of incoming calls greater than zero.', incoming);
                return;
            }
            if (a > t) {
                showError('Abandoned calls cannot be greater than total incoming calls.', abandoned);
                return;
            }

            result.textContent = ((a / t) * 100).toFixed(2) + '%';
            detail.textContent = formatter.format(a) + ' abandoned out of ' + formatter.format(t) + ' incoming calls';
        });

        form.addEventListener('input', function () {
            clearError();
            result.textContent = '—';
            detail.textContent = 'Calculate again to see the updated result.';
        });

        form.addEventListener('reset', function () {
            clearError();
            result.textContent = '—';
            detail.textContent = 'Enter your call counts to calculate the result.';
        });
    }

    function initAll() {
        document.querySelectorAll('.bpg-abnd').forEach(initCalculator);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    // Elementor can inject a shortcode widget after the initial page load.
    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(function (mutations) {
            for (var i = 0; i < mutations.length; i++) {
                if (mutations[i].addedNodes.length) {
                    initAll();
                    return;
                }
            }
        });
        observer.observe(document.documentElement, { childList: true, subtree: true });
    }
}());

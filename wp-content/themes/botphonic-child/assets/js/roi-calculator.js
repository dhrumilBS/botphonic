(function () {
    var DEFAULT_CPM = 0.40;
    var OVERHEAD = 1.25;
    var WO_FACTOR = 0.25;
    var MISSED_RECOVERY = 0.28;
    var TOTAL_STEPS = 6;

    var STEP_TITLES = ['', 'About you', 'Call volume', 'Agent costs', 'Missed calls', 'AI cost', 'Your savings'];
    var STEP_ICONS = [
        '',
        '<path d="M15.8307 17.5V15.8333C15.8307 14.9493 15.4795 14.1014 14.8544 13.4763C14.2293 12.8512 13.3815 12.5 12.4974 12.5H7.4974C6.61334 12.5 5.76549 12.8512 5.14037 13.4763C4.51525 14.1014 4.16406 14.9493 4.16406 15.8333V17.5 M9.9974 9.16667C11.8383 9.16667 13.3307 7.67428 13.3307 5.83333C13.3307 3.99238 11.8383 2.5 9.9974 2.5C8.15645 2.5 6.66406 3.99238 6.66406 5.83333C6.66406 7.67428 8.15645 9.16667 9.9974 9.16667Z"/>',
        '<path d="M18.33 14.1v2.5a1.67 1.67 0 0 1-1.82 1.66 16.5 16.5 0 0 1-7.19-2.56 16.26 16.26 0 0 1-5-5A16.5 16.5 0 0 1 1.77 3.5 1.67 1.67 0 0 1 3.42 1.67h2.5a1.67 1.67 0 0 1 1.66 1.43c.11.8.3 1.58.59 2.34a1.67 1.67 0 0 1-.38 1.76L6.74 8.26a13.33 13.33 0 0 0 5 5l1.06-1.06a1.67 1.67 0 0 1 1.76-.38c.76.29 1.55.48 2.34.59a1.67 1.67 0 0 1 1.43 1.69Z"/>',
        '<path d="M10 1.67v16.66M14.17 4.17H7.92A2.92 2.92 0 0 0 5 7.08a2.92 2.92 0 0 0 2.92 2.92h4.16A2.92 2.92 0 0 1 15 12.92a2.92 2.92 0 0 1-2.92 2.91H5"/>',
        '<path d="M18.33 1.67 13.33 6.67m0-5 5 5M18.33 14.1v2.5a1.67 1.67 0 0 1-1.82 1.66 16.5 16.5 0 0 1-7.19-2.56 16.26 16.26 0 0 1-5-5A16.5 16.5 0 0 1 1.77 3.5 1.67 1.67 0 0 1 3.42 1.67h2.5a1.67 1.67 0 0 1 1.66 1.43c.11.8.3 1.58.59 2.34a1.67 1.67 0 0 1-.38 1.76L6.74 8.26a13.33 13.33 0 0 0 5 5l1.06-1.06a1.67 1.67 0 0 1 1.76-.38c.76.29 1.55.48 2.34.59a1.67 1.67 0 0 1 1.43 1.69Z"/>',
        '<path d="M3.33 11.67h5.84l-1.5 6.66 9.16-10h-5.83l1.5-6.66-9.17 10Z"/>',
        '<path d="M18.33 5.83 11.25 12.92l-4.17-4.17-5.41 5.42"/><path d="M13.33 5.83h5v5"/>'
    ];

    var currentStep = 1;
    var missedOn = false;
    var brcCharts = {};
    var chartJsLoaded = false;

    var wrap = document.querySelector('.brc-wrap');
    var progress = document.getElementById('brcProgress');
    var titleEl = document.getElementById('brcTitle');
    var countEl = document.getElementById('brcCount');
    var iconEl = document.getElementById('brcIcon');
    var btnBack = document.getElementById('brcBack');
    var btnNext = document.getElementById('brcNext');
    var btnReset = document.getElementById('brcReset');
    var missedToggle = document.getElementById('brcMissedToggle');
    var missedFields = document.getElementById('brcMissedFields');
    var containSlider = document.getElementById('brcContainment');
    var containVal = document.getElementById('brcContainmentVal');
    var costPreview = document.getElementById('brcCostPreview');
    var resultsDiv = document.getElementById('brcResultsContent');

    function parseN(s) {
        return parseFloat(String(s || 0).replace(/[^0-9.]/g, '')) || 0;
    }

    function sym() {
        var v = document.getElementById('brcCurrency').value;
        return v === 'EUR' ? '€' : v === 'GBP' ? '£' : '$';
    }

    function fmtCur(n) {
        var s = sym(),
            abs = Math.round(Math.abs(n)).toLocaleString('en-US');
        return s + (n < 0 ? '-' : '') + abs;
    }

    function fmtK(n) {
        if (Math.abs(n) >= 1000000) return sym() + (n / 1000000).toFixed(1) + 'M';
        if (Math.abs(n) >= 1000) return sym() + (n / 1000).toFixed(0) + 'k';
        return fmtCur(n);
    }

    function buildProgress() {
        progress.innerHTML = '';
        for (var i = 1; i <= TOTAL_STEPS; i++) {
            var cls = 'brc-stage' + (i === currentStep ? ' brc-active' : i < currentStep ? ' brc-done' : '');
            progress.innerHTML += '<div class="' + cls + '">' +
                '<div class="brc-stage-bar"></div>' +
                '<div class="brc-stage-label">' + STEP_TITLES[i] + '</div>' +
                '</div>';
        }
    }

    function updateNav() {
        titleEl.textContent = STEP_TITLES[currentStep];
        countEl.textContent = currentStep + ' / ' + TOTAL_STEPS;
        iconEl.innerHTML = '<svg viewBox="0 0 20 20" height="20" width="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">' + STEP_ICONS[currentStep] + '</svg>';

        btnBack.classList.toggle('brc-disabled', currentStep === 1);

        if (currentStep === TOTAL_STEPS) {
            btnNext.classList.add('brc-hidden');
            btnReset.classList.remove('brc-hidden');
        } else {
            btnNext.classList.remove('brc-hidden');
            btnReset.classList.add('brc-hidden');
            btnNext.innerHTML = (currentStep === TOTAL_STEPS - 1) ?
                'Calculate <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M5 11l4-4-4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>' :
                'Next <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M5 11l4-4-4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        }
    }

    function updateCurrLabels() {
        var v = document.getElementById('brcCurrency').value;
        var label = (v === 'EUR' ? '€' : v === 'GBP' ? '£' : '$') + ' ' + v;
        var els = wrap.querySelectorAll('.brc-curr-label');
        for (var i = 0; i < els.length; i++) els[i].textContent = label;
    }

    function showStep(n) {
        for (var i = 1; i <= TOTAL_STEPS; i++) {
            var el = document.getElementById('brcStep' + i);
            if (el) el.classList.toggle('brc-hidden', i !== n);
        }
        currentStep = n;
        buildProgress();
        updateNav();
        updateCurrLabels();
        if (n === 5) updateCostPreview();
        if (n === 6) renderResults();
    }

    function updateCostPreview() {
        var mins = parseN(document.getElementById('brcMonthlyMins').value) * 12;
        var cpm = parseN(document.getElementById('brcCPM').value) || DEFAULT_CPM;
        var rate = parseInt(containSlider.value) / 100;
        costPreview.textContent = mins > 0 ? fmtCur(mins * cpm * rate) : '—';
    }

    function calcAll() {
        var monthlyMins = parseN(document.getElementById('brcMonthlyMins').value);
        var yearlyMins = monthlyMins * 12;
        var agents = parseN(document.getElementById('brcAgents').value);
        var salary = parseN(document.getElementById('brcSalary').value);
        var cpm = parseN(document.getElementById('brcCPM').value) || DEFAULT_CPM;
        var containment = parseInt(containSlider.value) / 100;
        var missedCalls = missedOn ? parseN(document.getElementById('brcMissedCalls').value) : 0;
        var revPerCall = missedOn ? parseN(document.getElementById('brcRevPerCall').value) : 0;

        var totalPayroll = agents * salary * OVERHEAD;
        var agentCostPerMin = totalPayroll / (yearlyMins || 1);
        var aiMins = yearlyMins * containment;
        var bfCost = aiMins * cpm;
        var callAutomation = aiMins * agentCostPerMin;
        var workforceSaving = totalPayroll * WO_FACTOR;
        var missedRevenue = missedCalls * revPerCall * MISSED_RECOVERY;
        var totalValue = callAutomation + workforceSaving + missedRevenue;
        var annualSavings = totalValue - bfCost;
        var costReduction = totalPayroll > 0 ? (annualSavings / totalPayroll) * 100 : 0;
        var roi = bfCost > 0 ? totalValue / bfCost : 0;

        var breakEven = 12;
        if (totalValue >= bfCost) {
            breakEven = 1;
        } else {
            for (var m = 1; m <= 12; m++) {
                if ((totalValue / 12) * m >= (bfCost / 12) * m) {
                    breakEven = m;
                    break;
                }
            }
        }

        var months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        var ramp = function (m) {
            return Math.min(1, 0.28 + m * 0.07);
        };

        return {
            yearlyMins: yearlyMins,
            agents: agents,
            salary: salary,
            totalPayroll: totalPayroll,
            bfCost: bfCost,
            callAutomation: callAutomation,
            workforceSaving: workforceSaving,
            missedRevenue: missedRevenue,
            totalValue: totalValue,
            annualSavings: annualSavings,
            costReduction: costReduction,
            roi: roi,
            breakEven: breakEven,
            containment: containment,
            months: months,
            monthlySavings: months.map(function (m) {
                return {
                    auto: Math.round(callAutomation / 12 * ramp(m)),
                    wf: Math.round(workforceSaving / 12 * ramp(m)),
                    miss: Math.round(missedRevenue / 12 * ramp(m))
                };
            }),
            cumReturn: months.map(function (m) {
                var cumVal = months.slice(0, m).reduce(function (s, mm) {
                    return s + totalValue / 12 * ramp(mm);
                }, 0);
                var cumCost = bfCost / 12 * m;
                return Math.round(cumVal - cumCost);
            })
        };
    }

    function destroyCharts() {
        Object.keys(brcCharts).forEach(function (k) {
            try {
                brcCharts[k].destroy();
            } catch (e) { }
        });
        brcCharts = {};
    }

    function renderResults() {
        destroyCharts();
        var d = calcAll();
        var company = (document.getElementById('brcCompany').value || '').trim();
        var name = company || 'Your business';
        var pct = Math.min(100, Math.max(0, d.costReduction));
        var s = sym();
        var hasMiss = d.missedRevenue > 0;

        resultsDiv.innerHTML =
            '<div class="brc-results-header">' +
            '<div class="brc-results-company">' + name + '</div>' +
            '<div class="brc-results-headline">Your estimated ROI with Botphonic AI voice automation</div>' +
            '</div>'

            +
            '<div class="brc-metrics-grid">' +
            '<div class="brc-metric brc-metric-hero"><div class="brc-metric-label">Annual savings</div><div class="brc-metric-value" id="brcMetSavings">' + fmtCur(d.annualSavings) + '</div></div>' +
            '<div class="brc-metric"><div class="brc-metric-label">ROI</div><div class="brc-metric-value">' + d.roi.toFixed(1) + 'x</div></div>' +
            '<div class="brc-metric"><div class="brc-metric-label">Break-even</div><div class="brc-metric-value">' + (d.breakEven === 1 ? '1 month' : d.breakEven + ' mos') + '</div></div>' +
            '</div>'

            +
            '<div class="brc-reduction-row">' +
            '<div class="brc-reduction-bar"><div class="brc-reduction-fill" id="brcFill" style="width:0%"></div></div>' +
            '<span class="brc-reduction-label">' + Math.round(pct) + '% cost reduction</span>' +
            '</div>'

            +
            '<div class="brc-charts-grid">' +
            '<div class="brc-chart-card">' +
            '<div class="brc-chart-title">Annual cost: before vs after</div>' +
            '<div class="brc-legend"><span><span class="brc-legend-dot" style="background:#ef4444"></span>Today</span><span><span class="brc-legend-dot" style="background:#006dee"></span>With Botphonic</span></div>' +
            '<div class="brc-chart-wrap" style="height:180px"><canvas id="brcChartCost" role="img" aria-label="Bar chart: today ' + fmtCur(d.totalPayroll) + ' vs with Botphonic ' + fmtCur(d.bfCost) + '">Today: ' + fmtCur(d.totalPayroll) + ', With Botphonic: ' + fmtCur(d.bfCost) + '</canvas></div>' +
            '</div>' +
            '<div class="brc-chart-card">' +
            '<div class="brc-chart-title">Value breakdown</div>' +
            '<div class="brc-legend"><span><span class="brc-legend-dot" style="background:#006dee"></span>Automation</span><span><span class="brc-legend-dot" style="background:#059669"></span>Workforce</span>' + (hasMiss ? '<span><span class="brc-legend-dot" style="background:#f59e0b"></span>Missed</span>' : '') + '</div>' +
            '<div class="brc-chart-wrap" style="height:180px"><canvas id="brcChartValue" role="img" aria-label="Donut chart showing value sources">Value breakdown across automation, workforce and missed call recovery.</canvas></div>' +
            '</div>' +
            '</div>'

            +
            '<div class="brc-chart-card" style="margin-bottom:16px">' +
            '<div class="brc-chart-title">Monthly savings ramp — 12 months</div>' +
            '<div class="brc-legend"><span><span class="brc-legend-dot" style="background:#006dee"></span>Call automation</span><span><span class="brc-legend-dot" style="background:#059669"></span>Workforce</span>' + (hasMiss ? '<span><span class="brc-legend-dot" style="background:#f59e0b"></span>Missed recovery</span>' : '') + '</div>' +
            '<div class="brc-chart-wrap" style="height:210px"><canvas id="brcChartMonthly" role="img" aria-label="Stacked bar chart of monthly savings over 12 months">Monthly savings growing from month 1 to month 12.</canvas></div>' +
            '</div>'

            +
            '<div class="brc-chart-card" style="margin-bottom:16px">' +
            '<div class="brc-chart-title">12-month cumulative net return</div>' +
            '<div class="brc-legend"><span><span class="brc-legend-dot" style="background:#006dee"></span>Net return (value − cost)</span></div>' +
            '<div class="brc-chart-wrap" style="height:190px"><canvas id="brcChartNet" role="img" aria-label="Line chart cumulative net return over 12 months">Cumulative net return reaches ' + fmtCur(d.cumReturn[11]) + ' by month 12.</canvas></div>' +
            '</div>'

            +
            '<div class="brc-table-card">' +
            '<div class="brc-chart-title" style="margin-bottom:4px">Full value summary</div>' +
            '<div class="brc-value-row"><span>Call automation savings</span><span class="brc-value-positive">+' + fmtCur(d.callAutomation) + '</span></div>' +
            '<div class="brc-value-row"><span>Workforce optimisation (' + Math.round(WO_FACTOR * 100) + '%)</span><span class="brc-value-positive">+' + fmtCur(d.workforceSaving) + '</span></div>' +
            (hasMiss ? '<div class="brc-value-row"><span>Missed call recovery</span><span class="brc-value-positive">+' + fmtCur(d.missedRevenue) + '</span></div>' : '') +
            '<div class="brc-value-row"><span class="brc-value-negative">Botphonic investment</span><span class="brc-value-negative">−' + fmtCur(d.bfCost) + '</span></div>' +
            '<div class="brc-value-row"><span>Net annual savings</span><span class="brc-value-total">' + fmtCur(d.annualSavings) + '</span></div>' +
            '</div>'

            +
            '<div class="brc-disclaimer">' +
            '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6v2.67M8 11h.01M14.49 12L9.15 2.67a1.34 1.34 0 0 0-2.3 0L1.5 12a1.33 1.33 0 0 0 1.16 2h10.67a1.33 1.33 0 0 0 1.16-2Z"/></svg>' +
            'Illustrative estimate only. Based on ' + Math.round(d.containment * 100) + '% AI containment and ' + Math.round(WO_FACTOR * 100) + '% workforce optimisation. Actual results vary by implementation and operational factors.' +
            '</div>'

            +
            '<div class="brc-cta">' +
            '<div><div class="brc-cta-title">Ready to see real numbers?</div><div class="brc-cta-sub">Talk to our team for a tailored Botphonic assessment</div></div>' +
            '<a href="/contact" class="brc-cta-btn">Get a custom quote <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M5 11l4-4-4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></a>' +
            '</div>';

        setTimeout(function () {
            var fillEl = document.getElementById('brcFill');
            if (fillEl) fillEl.style.width = Math.round(pct) + '%';
            drawCharts(d, s);
        }, 80);
    }

    function drawCharts(d, s) {
        if (typeof Chart === 'undefined') {
            var script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js';
            script.onload = function () {
                drawCharts(d, s);
            };
            document.head.appendChild(script);
            return;
        }

        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var hasMiss = d.missedRevenue > 0;

        brcCharts.cost = new Chart(document.getElementById('brcChartCost'), {
            type: 'bar',
            data: {
                labels: ['Today', 'With Botphonic'],
                datasets: [{
                    data: [d.totalPayroll, d.bfCost],
                    backgroundColor: ['rgba(239,68,68,.15)', 'rgba(0,109,238,.15)'],
                    borderColor: ['#ef4444', '#006dee'],
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (c) {
                                return fmtCur(c.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8', font: { size: 12 }
                        }
                    },
                    y: {
                        grid: { color: 'rgba(0,0,0,.06)' },
                        ticks: {
                            callback: function (v) { return fmtK(v); },
                            color: '#94a3b8', font: { size: 11 }
                        }
                    }
                }
            }
        });

        var vLabels = ['Call automation', 'Workforce'];
        var vData = [d.callAutomation, d.workforceSaving];
        var vBg = ['rgba(0,109,238,.2)', 'rgba(5,150,105,.2)'];
        var vBorder = ['#006dee', '#059669'];
        if (hasMiss) {
            vLabels.push('Missed calls');
            vData.push(d.missedRevenue);
            vBg.push('rgba(245,158,11,.2)');
            vBorder.push('#f59e0b');
        }

        brcCharts.value = new Chart(document.getElementById('brcChartValue'), {
            type: 'doughnut',
            data: {
                labels: vLabels,
                datasets: [{
                    data: vData,
                    backgroundColor: vBg,
                    borderColor: vBorder,
                    borderWidth: 1.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (c) {
                                return c.label + ': ' + fmtCur(c.raw);
                            }
                        }
                    }
                }
            }
        });

        var mDatasets = [{
            label: 'Call automation',
            data: d.monthlySavings.map(function (m) {
                return m.auto;
            }),
            backgroundColor: 'rgba(0,109,238,.75)',
            borderRadius: 3
        },
        {
            label: 'Workforce',
            data: d.monthlySavings.map(function (m) {
                return m.wf;
            }),
            backgroundColor: 'rgba(5,150,105,.70)',
            borderRadius: 3
        }
        ];
        if (hasMiss) mDatasets.push({
            label: 'Missed recovery',
            data: d.monthlySavings.map(function (m) {
                return m.miss;
            }),
            backgroundColor: 'rgba(245,158,11,.7)',
            borderRadius: 3
        });

        brcCharts.monthly = new Chart(document.getElementById('brcChartMonthly'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: mDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (c) {
                                return c.dataset.label + ': ' + fmtCur(c.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 11
                            },
                            autoSkip: false,
                            maxRotation: 0
                        }
                    },
                    y: {
                        stacked: true,
                        grid: { color: 'rgba(0,0,0,.06)' },
                        ticks: {
                            callback: function (v) {
                                return fmtK(v);
                            },
                            color: '#94a3b8',
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        brcCharts.net = new Chart(document.getElementById('brcChartNet'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Net return',
                    data: d.cumReturn,
                    borderColor: '#006dee',
                    backgroundColor: 'rgba(0,109,238,.08)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.38,
                    pointRadius: 3,
                    pointBackgroundColor: '#006dee'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (c) {
                                return fmtCur(c.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11 }, autoSkip: false, maxRotation: 0 }
                    },
                    y: {
                        grid: { color: 'rgba(0,0,0,.06)' },
                        ticks: {
                            callback: function (v) { return (v < 0 ? '-' : '') + fmtK(Math.abs(v)); }, color: '#94a3b8', font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    btnNext.addEventListener('click', function () {
        if (currentStep < TOTAL_STEPS) showStep(currentStep + 1);
    });

    btnBack.addEventListener('click', function () {
        if (currentStep > 1) {
            destroyCharts();
            showStep(currentStep - 1);
        }
    });

    btnReset.addEventListener('click', function () {
        destroyCharts();
        ['brcCompany', 'brcMonthlyMins', 'brcAgents', 'brcSalary', 'brcMissedCalls', 'brcRevPerCall'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.value = '';
        });
        document.getElementById('brcCPM').value = '0.40';
        containSlider.value = 60;
        containVal.textContent = '60%';
        missedOn = false;
        missedToggle.classList.remove('brc-on');
        missedToggle.setAttribute('aria-checked', 'false');
        missedFields.classList.add('brc-hidden');
        showStep(1);
    });

    missedToggle.addEventListener('click', function () {
        missedOn = !missedOn;
        missedToggle.classList.toggle('brc-on', missedOn);
        missedToggle.setAttribute('aria-checked', String(missedOn));
        missedFields.classList.toggle('brc-hidden', !missedOn);
    });
    missedToggle.addEventListener('keydown', function (e) {
        if (e.key === ' ' || e.key === 'Enter') {
            e.preventDefault();
            missedToggle.click();
        }
    });

    containSlider.addEventListener('input', function () {
        containVal.textContent = containSlider.value + '%';
        if (currentStep === 5) updateCostPreview();
    });

    document.getElementById('brcCPM').addEventListener('input', function () {
        if (currentStep === 5) updateCostPreview();
    });

    document.getElementById('brcCurrency').addEventListener('change', function () {
        updateCurrLabels();
        if (currentStep === 5) updateCostPreview();
    });
    showStep(1);
})();
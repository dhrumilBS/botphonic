<?php

/**
 * Usage:
 *   [evaluation_matrix]
 *   [evaluation_matrix vendor_a="Botphonic" vendor_b="Competitor"]
 *
 * @package BotphonicChild
 */

if (! defined('ABSPATH')) {
  exit;
}

add_shortcode('evaluation_matrix', 'botphonic_evaluation_matrix_shortcode');

/**
 * Renders the evaluation matrix.
 *
 * @param  array  $atts  Shortcode attributes.
 * @return string        HTML output (buffered).
 */
function botphonic_evaluation_matrix_shortcode($atts)
{

  $atts = shortcode_atts(
    array(
      'vendor_a' => '',
      'vendor_b' => '',
    ),
    $atts,
    'evaluation_matrix'
  );

  static $instance = 0;
  ++$instance;
  $uid      = 'bpem_' . $instance;
  $vendor_a = esc_attr($atts['vendor_a']);
  $vendor_b = esc_attr($atts['vendor_b']);

  botphonic_evaluation_matrix_enqueue_assets();

  ob_start();
?>

  <div class="bpem-wrap" id="<?php echo esc_attr($uid); ?>"
    data-uid="<?php echo esc_attr($uid); ?>"
    data-vendor-a="<?php echo $vendor_a; ?>"
    data-vendor-b="<?php echo $vendor_b; ?>">

    <!-- ── Header ─────────────────────────────────────────────────────── -->
    <div class="bpem-header">
      <p class="bpem-intro">Enter vendor names, then click the stars to score each criterion. Weights reflect how critical each area is to a successful deployment.</p>
      <div class="bpem-controls">
        <div class="bpem-vendor-inputs">
          <div class="bpem-input-group">
            <span class="bpem-dot bpem-dot--a" aria-hidden="true"></span>
            <input class="bpem-vendor-input" id="<?php echo esc_attr($uid); ?>_nameA"
              placeholder="Vendor A" value="<?php echo $vendor_a; ?>"
              maxlength="40" aria-label="Vendor A name" />
          </div>
          <div class="bpem-input-group">
            <span class="bpem-dot bpem-dot--b" aria-hidden="true"></span>
            <input class="bpem-vendor-input" id="<?php echo esc_attr($uid); ?>_nameB"
              placeholder="Vendor B" value="<?php echo $vendor_b; ?>"
              maxlength="40" aria-label="Vendor B name" />
          </div>
        </div>
        <button class="bpem-btn bpem-btn--ghost" id="<?php echo esc_attr($uid); ?>_reset" aria-label="Reset all scores">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="1 4 1 10 7 10" />
            <path d="M3.51 15a9 9 0 1 0 .49-3.51" />
          </svg>
          Reset
        </button>
      </div>
    </div>

    <!-- ── Summary cards ──────────────────────────────────────────────── -->
    <div class="bpem-summary">
      <div class="bpem-card bpem-card--a">
        <p class="bpem-card__label" id="<?php echo esc_attr($uid); ?>_labelA">Vendor A — weighted score</p>
        <p class="bpem-card__score" id="<?php echo esc_attr($uid); ?>_totalA">0</p>
      </div>
      <div class="bpem-card bpem-card--b">
        <p class="bpem-card__label" id="<?php echo esc_attr($uid); ?>_labelB">Vendor B — weighted score</p>
        <p class="bpem-card__score" id="<?php echo esc_attr($uid); ?>_totalB">0</p>
      </div>
      <div class="bpem-card">
        <p class="bpem-card__label">Max possible score</p>
        <p class="bpem-card__score bpem-card__score--muted" id="<?php echo esc_attr($uid); ?>_maxScore">—</p>
      </div>
      <div class="bpem-card">
        <p class="bpem-card__label">Criteria scored</p>
        <p class="bpem-card__score bpem-card__score--muted" id="<?php echo esc_attr($uid); ?>_scoredCount">0 / 12</p>
      </div>
    </div>

    <!-- ── Legend ─────────────────────────────────────────────────────── -->
    <div class="bpem-legend">
      <span class="bpem-legend__item"><span class="bpem-badge bpem-badge--3">3</span> Non-negotiable</span>
      <span class="bpem-legend__item"><span class="bpem-badge bpem-badge--2">2</span> Important</span>
      <span class="bpem-legend__item"><span class="bpem-badge bpem-badge--1">1</span> Nice to have</span>
      <span class="bpem-legend__sep" aria-hidden="true">|</span>
      <span class="bpem-legend__item">&#9733; = 1 (poor) &rarr; 5 (excellent)</span>
    </div>

    <!-- ── Matrix table ───────────────────────────────────────────────── -->
    <div class="bpem-table-wrap">
      <table class="bpem-table" role="grid" aria-label="Vendor evaluation matrix">
        <thead>
          <tr>
            <th scope="col" class="bpem-th bpem-th--criterion">Criterion</th>
            <th scope="col" class="bpem-th bpem-th--weight">Weight</th>
            <th scope="col" class="bpem-th bpem-th--vendor" id="<?php echo esc_attr($uid); ?>_thA">Vendor A</th>
            <th scope="col" class="bpem-th bpem-th--vendor" id="<?php echo esc_attr($uid); ?>_thB">Vendor B</th>
            <th scope="col" class="bpem-th bpem-th--weighted">Wtd A</th>
            <th scope="col" class="bpem-th bpem-th--weighted">Wtd B</th>
          </tr>
        </thead>
        <tbody id="<?php echo esc_attr($uid); ?>_tbody">
          <!-- rows injected by JS -->
        </tbody>
      </table>
    </div>

    <!-- ── Verdict bar ────────────────────────────────────────────────── -->
    <div class="bpem-verdict" id="<?php echo esc_attr($uid); ?>_verdict" aria-live="polite" hidden>
      <svg class="bpem-verdict__icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
        stroke="#C08B1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
        <path d="M4 22h16" />
        <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
        <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z" />
      </svg>
      <span class="bpem-verdict__label">Leading vendor:</span>
      <strong class="bpem-verdict__winner" id="<?php echo esc_attr($uid); ?>_verdictWinner">—</strong>
      <span class="bpem-verdict__score" id="<?php echo esc_attr($uid); ?>_verdictScore"></span>
    </div>

  </div><!-- .bpem-wrap -->

<?php
  return ob_get_clean();
}


/* ──────────────────────────────────────────────────────────────────────────
 * Enqueue assets (inline CSS + footer JS) — once per page load
 * ────────────────────────────────────────────────────────────────────────── */

function botphonic_evaluation_matrix_enqueue_assets()
{

  if (wp_style_is('bpem-styles', 'enqueued')) {
    return;
  }

  /* ── CSS ─────────────────────────────────────────────────────────── */
  $css = '
.bpem-wrap { --bpem-a: #1A5FAD; --bpem-b: #0D7055; --bpem-gold: #C08B1A; --bpem-surface: #F8F9FB; --bpem-border: #E3E6EC; --bpem-text: #1C1E24; --bpem-muted: #6B7280; --bpem-radius: 8px; color: var(--bpem-text); max-width: 100%; }
.bpem-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
.bpem-intro { font-size: 13px; color: var(--bpem-muted); margin: 0; max-width: 460px; line-height: 1.6; }
.bpem-controls { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.bpem-vendor-inputs { display: flex; gap: 8px; }
.bpem-input-group { display: flex; align-items: center; gap: 6px; background: #fff; border: 1px solid var(--bpem-border); border-radius: var(--bpem-radius); padding: 7px 11px; transition: border-color .15s, box-shadow .15s; }
.bpem-input-group:focus-within { border-color: var(--bpem-a); box-shadow: 0 0 0 3px rgba(26, 95, 173, .12); }
.bpem-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.bpem-dot--a { background: var(--bpem-a); }
.bpem-dot--b { background: var(--bpem-b); }
.bpem-vendor-input { border: none; outline: none; font-size: 13px; font-family: inherit; color: var(--bpem-text); background: transparent; width: 120px; }
.bpem-vendor-input::placeholder { color: #B0B7C3; }
.bpem-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: var(--bpem-radius); font-family: inherit; font-size: 13px; font-weight: 500; line-height: 1; cursor: pointer; border: none; transition: background .15s, color .15s, transform .1s; white-space: nowrap; text-decoration: none; }
.bpem-btn:active { transform: scale(.97); }
.bpem-btn--ghost { background: #fff; border: 1px solid var(--bpem-border); color: var(--bpem-muted); }
.bpem-btn--ghost:hover { border-color: #C0C6D2; color: var(--bpem-text); background: #F4F6FA; }
.bpem-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px; margin-bottom: 20px; }
.bpem-card { background: #fff; border: 1px solid var(--bpem-border); border-radius: var(--bpem-radius); padding: 14px 16px; }
.bpem-card--a { border-top: 3px solid var(--bpem-a); }
.bpem-card--b { border-top: 3px solid var(--bpem-b); }
.bpem-card__label { font-size: 11px; font-weight: 600; color: var(--bpem-muted); text-transform: uppercase; letter-spacing: .05em; margin: 0 0 6px; }
.bpem-card__score { font-size: 30px; font-weight: 700; margin: 0; letter-spacing: -.03em; line-height: 1; }
.bpem-card--a .bpem-card__score { color: var(--bpem-a); }
.bpem-card--b .bpem-card__score { color: var(--bpem-b); }
.bpem-card__score--muted { color: #B0B7C3; }
.bpem-legend { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 18px; margin-bottom: 14px; font-size: 12px; color: var(--bpem-muted); }
.bpem-legend__item { display: flex; align-items: center; gap: 5px; }
.bpem-legend__sep { color: var(--bpem-border); }
.bpem-badge { display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; font-size: 11px; font-weight: 700; line-height: 1; flex-shrink: 0; }
.bpem-badge--3 { background: #FDEAEA; color: #8C2020; }
.bpem-badge--2 { background: #FEF3D7; color: #6B3C00; }
.bpem-badge--1 { background: #E2F5EE; color: #0C5940; }
.bpem-table-wrap { overflow-x: auto; border: 1px solid var(--bpem-border); border-radius: var(--bpem-radius); }
.bpem-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 580px; }
.bpem-th { background: var(--bpem-surface); padding: 10px 14px; font-size: 11px; font-weight: 600; color: var(--bpem-muted); text-transform: uppercase; letter-spacing: .05em; text-align: left; border-bottom: 1px solid var(--bpem-border); white-space: nowrap; }
.bpem-th--vendor,
.bpem-th--weighted { text-align: center; }
.bpem-th--criterion { min-width: 210px; }
.bpem-th--weight { width: 68px; }
.bpem-th--vendor { width: 148px; }
.bpem-th--weighted { width: 68px; }
.bpem-area-row td { background: var(--bpem-surface); padding: 6px 14px; font-size: 10px; font-weight: 700; color: #9CA3AF; letter-spacing: .1em; text-transform: uppercase; border-top: 1px solid var(--bpem-border); border-bottom: 1px solid var(--bpem-border); }
.bpem-table tbody tr:not(.bpem-area-row) td { padding: 11px 14px; border-bottom: 1px solid var(--bpem-border); vertical-align: middle; }
.bpem-table tbody tr:last-child td { border-bottom: none; }
.bpem-table tbody tr:not(.bpem-area-row):hover { background: #F6F8FC; }
.bpem-criterion__name { font-weight: 500; color: var(--bpem-text); text-transform: capitalize; }
.bpem-criterion__desc { font-size: 11px; color: var(--bpem-muted); margin-top: 3px; line-height: 1.4; }
.bpem-stars { display: flex; justify-content: center; gap: 1px; }
.bpem-stars .bpem-star { font-size: 22px; line-height: 1; cursor: pointer; color: #D9DDE6; background: none; border: none; padding: 1px 2px; transition: color .1s, transform .1s; display: block; }
.bpem-stars .bpem-star.bpem-star--a { color: var(--bpem-a); }
.bpem-stars .bpem-star.bpem-star--b { color: var(--bpem-b); }
.bpem-stars--a .bpem-star.is-hovered { color: var(--bpem-a); opacity: .55; }
.bpem-stars--b .bpem-star.is-hovered { color: var(--bpem-b); opacity: .55; }
.bpem-star:hover { transform: scale(1.3); }
.bpem-star:focus-visible { transform: scale(1.3); outline: 2px solid var(--bpem-text); outline-offset: 1px; border-radius: 4px; }
.bpem-weighted { text-align: center; font-size: 13px; font-weight: 600; color: #C8CDD8; }
.bpem-weighted--a { color: var(--bpem-a); }
.bpem-weighted--b { color: var(--bpem-b); }
.bpem-verdict { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; padding: 13px 18px; margin-top: 14px; border-radius: var(--bpem-radius); background: #FFFCEF; border: 1px solid #EDD97A; }
.bpem-verdict__icon { flex-shrink: 0; }
.bpem-verdict__label { font-size: 13px; color: var(--bpem-muted); }
.bpem-verdict__winner { font-size: 14px; font-weight: 700; color: var(--bpem-text); }
.bpem-verdict__score { font-size: 13px; color: var(--bpem-muted); }
@media (max-width: 600px) {
    .bpem-header { flex-direction: column; }
    .bpem-controls { width: 100%; justify-content: space-between; }
    .bpem-vendor-inputs { flex-wrap: wrap; }
    .bpem-card__score { font-size: 24px; }
    .bpem-verdict { flex-direction: column; align-items: flex-start; }
}

@media (prefers-reduced-motion: reduce) {

    .bpem-star,
    .bpem-btn,
    .bpem-input-group { transition: none; }
    .bpem-star:hover,
    .bpem-star:focus-visible,
    .bpem-btn:active { transform: none; }
}';

  wp_register_style('bpem-styles', false);
  wp_enqueue_style('bpem-styles');
  wp_add_inline_style('bpem-styles', $css);

  /* ── JS ──────────────────────────────────────────────────────────── */
  if (wp_script_is('bpem-script', 'enqueued')) {
    return;
  }

  $js = '
(function () {
    "use strict";

    var CRITERIA = [
        { area: "Conversation performance", id: "speed", name: "Response speed", desc: "Time to first word, latency under load", weight: 2 },
        { area: "Conversation performance", id: "accuracy", name: "Call understanding accuracy", desc: "Intent recognition, NLU accuracy rate", weight: 3 },
        { area: "Conversation performance", id: "continuity", name: "Conversation continuity", desc: "Multi-turn memory, context retention", weight: 3 },
        { area: "Business process", id: "workflow", name: "Workflow flexibility", desc: "Custom rules, branching, edge-case handling", weight: 2 },
        { area: "Business process", id: "crm", name: "CRM connectivity", desc: "HubSpot, Salesforce, Zoho sync via API/webhook", weight: 3 },
        { area: "Business process", id: "scheduling", name: "Scheduling capabilities", desc: "Calendar integration, booking confirmation", weight: 2 },
        { area: "Reliability", id: "escalation", name: "Escalation quality", desc: "Transfer fidelity, data hand-off to live agent", weight: 3 },
        { area: "Reliability", id: "uptime", name: "Reliability & uptime", desc: "SLA guarantee, failover, peak-hour performance", weight: 3 },
        { area: "Integration readiness", id: "analytics", name: "Analytics & reporting", desc: "Call logs, resolution rates, trend dashboards", weight: 2 },
        { area: "Governance", id: "security", name: "Security controls", desc: "SOC 2, HIPAA, data residency, audit trails", weight: 2 },
        { area: "Deployment", id: "deployment", name: "Ease of deployment", desc: "Setup time, onboarding, config complexity", weight: 1 },
        { area: "Cost", id: "cost", name: "Overall cost efficiency", desc: "TCO, ROI vs. human receptionist baseline", weight: 1 }
    ];

    var instances = {};

    /* ── Init all matrices on the page ── */
    function initAll() {
        document.querySelectorAll(".bpem-wrap").forEach(function (wrap) {
            var uid = wrap.dataset.uid;
            if (!uid) return;

            var tbody = g(uid + "_tbody");
            var alreadyBuilt = tbody && tbody.children.length > 0;
            if (instances[uid] && alreadyBuilt) return;

            instances[uid] = { a: {}, b: {} };
            buildTable(uid);
            bindControls(uid);

            var va = wrap.dataset.vendorA;
            var vb = wrap.dataset.vendorB;
            if (va) g(uid + "_nameA").value = va;
            if (vb) g(uid + "_nameB").value = vb;
            updateNames(uid);
        });
    }

    function g(id) { return document.getElementById(id); }

    function vendorName(uid, vendor) {
        var input = g(uid + "_name" + vendor.toUpperCase());
        var fallback = vendor === "a" ? "Vendor A" : "Vendor B";
        return ((input && input.value) || fallback).trim() || fallback;
    }

    /* ── Build table ── */
    function buildTable(uid) {
        var tbody = g(uid + "_tbody");
        if (!tbody) return;
        tbody.innerHTML = "";
        var lastArea = "";

        CRITERIA.forEach(function (c) {
            if (c.area !== lastArea) {
                lastArea = c.area;
                var ar = document.createElement("tr");
                ar.className = "bpem-area-row";
                var td = document.createElement("td");
                td.setAttribute("colspan", "6");
                td.textContent = c.area;
                ar.appendChild(td);
                tbody.appendChild(ar);
            }

            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td>" +
                "<div class=\"bpem-criterion__name\">" + escHtml(c.name) + "</div>" +
                "<div class=\"bpem-criterion__desc\">" + escHtml(c.desc) + "</div>" +
                "</td>" +
                "<td><span class=\"bpem-badge bpem-badge--" + c.weight + "\">" + c.weight + "</span></td>" +
                "<td><div class=\"bpem-stars bpem-stars--a\" id=\"" + uid + "_starsA_" + c.id + "\" role=\"radiogroup\" aria-label=\"" + escHtml(c.name) + ", vendor A\"></div></td>" +
                "<td><div class=\"bpem-stars bpem-stars--b\" id=\"" + uid + "_starsB_" + c.id + "\" role=\"radiogroup\" aria-label=\"" + escHtml(c.name) + ", vendor B\"></div></td>" +
                "<td class=\"bpem-weighted\" id=\"" + uid + "_waCell_" + c.id + "\">\u2014</td>" +
                "<td class=\"bpem-weighted\" id=\"" + uid + "_wbCell_" + c.id + "\">\u2014</td>";
            tbody.appendChild(tr);

            buildStars(uid, "a", c.id, c.weight, c.name);
            buildStars(uid, "b", c.id, c.weight, c.name);
        });

        var max = CRITERIA.reduce(function (s, c) { return s + c.weight * 5; }, 0);
        var el = g(uid + "_maxScore");
        if (el) el.textContent = max;
    }

    function buildStars(uid, vendor, cid, weight, criterionName) {
        var container = g(uid + "_stars" + vendor.toUpperCase() + "_" + cid);
        if (!container) return;
        container.innerHTML = "";

        for (var s = 1; s <= 5; s++) {
            (function (val) {
                var btn = document.createElement("button");
                btn.type = "button";
                btn.dataset.value = val;
                btn.className = "bpem-star";
                btn.textContent = "\u2605";
                btn.setAttribute("aria-label", "Rate " + criterionName + " " + val + " out of 5");
                btn.setAttribute("aria-pressed", "false");

                btn.addEventListener("mouseenter", function () { previewStars(uid, vendor, cid, val); });
                btn.addEventListener("focus", function () { previewStars(uid, vendor, cid, val); });
                btn.addEventListener("blur", function () { renderStars(uid, vendor, cid); });
                btn.addEventListener("click", function () { setScore(uid, vendor, cid, val, weight); });

                container.appendChild(btn);
            })(s);
        }

        container.addEventListener("mouseleave", function () {
            renderStars(uid, vendor, cid);
        });
    }
    function previewStars(uid, vendor, cid, previewVal) {
        var stars = starEls(uid, vendor, cid);
        stars.forEach(function (star) {
            var starVal = parseInt(star.dataset.value, 10);
            star.classList.toggle("is-hovered", starVal <= previewVal);
        });
    }

    function setScore(uid, vendor, cid, val, weight) {
        instances[uid][vendor][cid] = val;
        renderStars(uid, vendor, cid);
        var cell = g(uid + "_w" + vendor + "Cell_" + cid);
        if (cell) {
            cell.textContent = val * weight;
            cell.className = "bpem-weighted bpem-weighted--" + vendor;
        }
        updateTotals(uid);
    }

    function starEls(uid, vendor, cid) {
        return document.querySelectorAll("#" + uid + "_stars" + vendor.toUpperCase() + "_" + cid + " .bpem-star");
    }

    function renderStars(uid, vendor, cid) {
        var val = instances[uid][vendor][cid] || 0;
        starEls(uid, vendor, cid).forEach(function (s, i) {
            s.className = "bpem-star" + (i < val ? " bpem-star--" + vendor : "");
            s.setAttribute("aria-pressed", i + 1 === val ? "true" : "false");
        });
    }

    /* ── Totals & verdict ── */
    function updateTotals(uid) {
        var ta = 0, tb = 0, count = 0;
        CRITERIA.forEach(function (c) {
            var sa = instances[uid].a[c.id] || 0;
            var sb = instances[uid].b[c.id] || 0;
            ta += sa * c.weight;
            tb += sb * c.weight;
            if (sa || sb) count++;
        });
        var elA = g(uid + "_totalA"); if (elA) elA.textContent = ta;
        var elB = g(uid + "_totalB"); if (elB) elB.textContent = tb;
        var elC = g(uid + "_scoredCount"); if (elC) elC.textContent = count + " / " + CRITERIA.length;
        updateVerdict(uid, ta, tb);
    }

    function updateVerdict(uid, ta, tb) {
        var verdict = g(uid + "_verdict");
        if (!verdict) return;
        if (ta === 0 && tb === 0) { verdict.hidden = true; return; }
        verdict.hidden = false;

        var nameA = vendorName(uid, "a");
        var nameB = vendorName(uid, "b");
        var elW = g(uid + "_verdictWinner");
        var elS = g(uid + "_verdictScore");
        var label = verdict.querySelector(".bpem-verdict__label");

        if (ta === tb) {
            if (label) label.textContent = "Result:";
            if (elW) elW.textContent = "Tied";
            if (elS) elS.textContent = "(" + ta + " each)";
            return;
        }

        if (label) label.textContent = "Leading vendor:";
        var winner = ta > tb ? nameA : nameB;
        var ws = Math.max(ta, tb);
        var ls = Math.min(ta, tb);
        if (elW) elW.textContent = winner;
        if (elS) elS.textContent = "(" + ws + " vs " + ls + ")";
    }

    function updateNames(uid) {
        var a = vendorName(uid, "a");
        var b = vendorName(uid, "b");
        var elLA = g(uid + "_labelA"); if (elLA) elLA.textContent = a + " \u2014 weighted score";
        var elLB = g(uid + "_labelB"); if (elLB) elLB.textContent = b + " \u2014 weighted score";
        var elTA = g(uid + "_thA"); if (elTA) elTA.textContent = a;
        var elTB = g(uid + "_thB"); if (elTB) elTB.textContent = b;
        var ta = parseInt((g(uid + "_totalA") || {}).textContent, 10) || 0;
        var tb = parseInt((g(uid + "_totalB") || {}).textContent, 10) || 0;
        updateVerdict(uid, ta, tb);
    }

    /* ── Reset ── */
    function resetMatrix(uid) {
        instances[uid] = { a: {}, b: {} };
        CRITERIA.forEach(function (c) {
            renderStars(uid, "a", c.id);
            renderStars(uid, "b", c.id);
            var wa = g(uid + "_waCell_" + c.id); if (wa) { wa.textContent = "\u2014"; wa.className = "bpem-weighted"; }
            var wb = g(uid + "_wbCell_" + c.id); if (wb) { wb.textContent = "\u2014"; wb.className = "bpem-weighted"; }
        });
        var elA = g(uid + "_totalA"); if (elA) elA.textContent = "0";
        var elB = g(uid + "_totalB"); if (elB) elB.textContent = "0";
        var elC = g(uid + "_scoredCount"); if (elC) elC.textContent = "0 / " + CRITERIA.length;
        var v = g(uid + "_verdict"); if (v) v.hidden = true;
    }

    /* ── Controls ── */
    function bindControls(uid) {
        var inA = g(uid + "_nameA");
        var inB = g(uid + "_nameB");
        if (inA) inA.addEventListener("input", function () { updateNames(uid); });
        if (inB) inB.addEventListener("input", function () { updateNames(uid); });

        var btnReset = g(uid + "_reset");
        if (btnReset) btnReset.addEventListener("click", function () { resetMatrix(uid); });
    }

    /* ── Escape helper ── */
    function escHtml(s) {
        return s
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");
    }

    /* ── Boot ── */
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initAll);
    } else {
        initAll();
    }

    /* Elementor live-editor support */
    document.addEventListener("bpem:reinit", initAll);
    if (window.elementorFrontend) {
        window.addEventListener("elementor/frontend/init", function () {
            if (window.elementorFrontend.hooks) {
                window.elementorFrontend.hooks.addAction("frontend/element_ready/shortcode.default", initAll);
            }
        });
    }

})();
';

  wp_register_script('bpem-script', false, array(), null, true);
  wp_enqueue_script('bpem-script');
  wp_add_inline_script('bpem-script', $js);
}
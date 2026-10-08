<?php

/**
 * Botphonic ROI Calculator Shortcode
 *
 * Usage: [roi_calculator]
 *
 * Place this file in:
 * wp-content/themes/botphonic-child/inc/roi_calculator.php
 *
 * Then in your child theme's functions.php add:
 * require_once get_stylesheet_directory() . '/inc/roi_calculator.php';
 */

if (!defined('ABSPATH')) {
	exit;
}
add_shortcode('roi_calculator', 'botphonic_roi_calculator_shortcode');
function botphonic_roi_calculator_shortcode()
{
	wp_enqueue_style('calculator');
	wp_enqueue_script('calculator');
	ob_start();
?>
<div class="brc-wrap" id="brc-<?php echo esc_attr(uniqid()); ?>">
	<div class="brc-progress" id="brcProgress"></div>
	<div class="brc-card">
		<div class="brc-header">
			<div class="brc-header-left">
				<div class="brc-icon-wrap" id="brcIcon">
					<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
						 width="20" height="20" stroke-linejoin="round">
						<path
							  d="M10 2v16M14.17 4.17H7.92C6.28 4.17 5 5.45 5 7.08s1.28 2.92 2.92 2.92h4.16C13.72 10 15 11.28 15 12.92S13.72 15.83 12.08 15.83H5" />
					</svg>
				</div>
				<span class="brc-title" id="brcTitle">About you</span>
			</div>
			<span class="brc-step-count" id="brcCount">1 / 6</span>
		</div>

		<div class="brc-body">

			<div id="brcStep1" class="brc-step">
				<div class="brc-field">
					<label class="brc-label">Company name <span class="brc-optional">(optional)</span></label>
					<input class="brc-input" id="brcCompany" type="text" placeholder="e.g. Acme Corp"
						   autocomplete="organization">
				</div>
				<div class="brc-field">
					<label class="brc-label">Currency</label>
					<select class="brc-select" id="brcCurrency">
						<option value="USD">$ USD</option>
						<option value="EUR">€ EUR</option>
						<option value="GBP">£ GBP</option>
					</select>
				</div>
			</div>

			<div id="brcStep2" class="brc-step brc-hidden">
				<div class="brc-field">
					<label class="brc-label">Monthly call minutes</label>
					<p class="brc-sublabel">Total minutes your agents handle each month across all channels</p>
					<input class="brc-input" id="brcMonthlyMins" type="text" placeholder="e.g. 50,000"
						   inputmode="numeric">
				</div>
			</div>

			<div id="brcStep3" class="brc-step brc-hidden">
				<div class="brc-row">
					<div class="brc-field">
						<label class="brc-label">Number of agents</label>
						<input class="brc-input" id="brcAgents" type="text" placeholder="e.g. 25" inputmode="numeric">
					</div>
					<div class="brc-field">
						<label class="brc-label">Avg annual salary (<span class="brc-curr-label">$ USD</span>)</label>
						<input class="brc-input" id="brcSalary" type="text" placeholder="e.g. 45,000"
							   inputmode="numeric">
					</div>
				</div>
			</div>

			<div id="brcStep4" class="brc-step brc-hidden">
				<div class="brc-field">
					<div class="brc-toggle-row">
						<div>
							<div class="brc-toggle-title">Do you miss calls today?</div>
							<div class="brc-toggle-sub">Include revenue lost from unanswered calls</div>
						</div>
						<button class="brc-toggle-btn" id="brcMissedToggle" type="button" role="switch"
								aria-checked="false" aria-label="Toggle missed call revenue">
							<div class="brc-toggle-dot"></div>
						</button>
					</div>
				</div>
				<div class="brc-missed-fields brc-hidden" id="brcMissedFields">
					<div class="brc-row">
						<div class="brc-field">
							<label class="brc-label">Missed calls per year</label>
							<input class="brc-input" id="brcMissedCalls" type="text" placeholder="e.g. 10,000"
								   inputmode="numeric">
						</div>
						<div class="brc-field">
							<label class="brc-label">Revenue per missed call (<span class="brc-curr-label">$
								USD</span>)</label>
							<input class="brc-input" id="brcRevPerCall" type="text" placeholder="e.g. 50"
								   inputmode="numeric">
						</div>
					</div>
				</div>
			</div>

			<div id="brcStep5" class="brc-step brc-hidden">
				<div class="brc-field">
					<label class="brc-label">Cost per minute (<span class="brc-curr-label">$ USD</span>)</label>
					<p class="brc-sublabel">Your Botphonic rate is $0.40/min — adjust if you have a custom quote</p>
					<input class="brc-input" id="brcCPM" type="text" placeholder="0.40" value="0.40"
						   inputmode="decimal">
				</div>
				<div class="brc-field">
					<label class="brc-label">AI containment rate</label>
					<p class="brc-sublabel">% of calls fully resolved by AI — no human needed (industry benchmark:
						40–70%)</p>
					<div class="brc-slider-row">
						<input class="brc-slider" type="range" min="20" max="90" step="5" value="60" id="brcContainment"
							   aria-label="AI containment rate">
						<span class="brc-slider-val" id="brcContainmentVal">60%</span>
					</div>
				</div>
				<div class="brc-cost-preview">
					<span class="brc-cost-preview-label">Estimated annual AI cost</span>
					<span class="brc-cost-preview-value" id="brcCostPreview">—</span>
				</div>
			</div>

			<div id="brcStep6" class="brc-step brc-hidden">
				<div id="brcResultsContent"></div>
			</div>
		</div>


		<div class="brc-footer">
			<button class="brc-btn brc-disabled" id="brcBack" type="button">
				<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
					<path d="M9 11L5 7l4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
						  stroke-linejoin="round" />
				</svg>
				Back
			</button>
			<div style="display:flex;gap:8px;align-items:center;">
				<button class="brc-btn brc-btn-reset brc-hidden" id="brcReset" type="button">
					<svg width="13" height="13" viewBox="0 0 14 14" fill="none">
						<path d="M2 7a5 5 0 1 0 1.1-3.1M2 2v3h3" stroke="currentColor" stroke-width="1.5"
							  stroke-linecap="round" stroke-linejoin="round" />
					</svg>
					Start over
				</button>
				<button class="brc-btn brc-btn-primary" id="brcNext" type="button">
					Next
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
						<path d="M5 11l4-4-4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
							  stroke-linejoin="round" />
					</svg>
				</button>
			</div>
		</div>
	</div>
</div>
<?php
	return ob_get_clean();
}

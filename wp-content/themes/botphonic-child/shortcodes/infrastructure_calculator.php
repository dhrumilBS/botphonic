<?php
if (!defined('ABSPATH')) exit;
function botphonic_infrastructure_calculator_shortcode()
{
	$uid = uniqid('calc_');
	ob_start();
?>
<div class="infra-calc" id="<?php echo esc_attr($uid); ?>" style="background-color: var(--bg-dark-1);">
	<div class="calc-card">
		<div class="calc-grid">

			<div class="calc-left">
				<h3>Your Outreach Goals</h3>
				<p class="sub">Adjust sliders to match your sending volume</p>

				<div class="field">
					<label>Daily emails to send</label>
					<input type="range" class="emailsPerDay" min="100" max="10000" step="100" value="1000">
					<div class="range">
						<span>100</span>
						<span class="emailsPerDayVal">1,000</span>
						<span>10,000</span>
					</div>
				</div>

				<div class="field">
					<label>Emails per inbox per day</label>
					<input type="range" class="perInbox" min="20" max="100" step="5" value="40">
					<div class="range">
						<span>20</span>
						<span class="perInboxVal">40</span>
						<span>100</span>
					</div>
				</div>

				<div class="field">
					<label>Inboxes per domain</label>
					<input type="range" class="inboxPerDomain" min="1" max="5" step="1" value="3">
					<div class="range">
						<span>1</span>
						<span class="inboxPerDomainVal">3</span>
						<span>5</span>
					</div>
				</div>
			</div>

			<div class="calc-right">
				<p class="label">Recommended Infrastructure</p>

				<div class="result">
					<span>Sending inboxes needed</span>
					<strong class="inboxesNeeded" style="color:var(--secondary)">25</strong>
				</div>

				<div class="result">
					<span>Sending domains needed</span>
					<strong class="domainsNeeded" style="color:var(--accent)">9</strong>
				</div>

				<div class="result">
					<span>Max emails per day</span>
					<strong class="maxEmails">1,000</strong>
				</div>

				<div class="result">
					<span>Max emails per month (30-day)</span>
					<strong class="maxMonthly">30,000</strong>
				</div>

				<div class="result highlight">
					<span>Estimated Infrastructure Cost</span>
					<strong class="costEstimate">$90 – $150</strong>
				</div>

				<div class="result highlight">
					<span>Setup complexity</span>
					<strong class="complexity">Medium</strong>
				</div>

				<p class="calc-note">Based on safe sending limits to maintain inbox placement</p>

				<div class="my-4">
					<a href="https://app.botphonic.ai/" target="_blank" rel="noopener" class="theme-btn">Set Up This Infrastructure →</a>
				</div>
			</div>

		</div>
	</div>
</div>

<style>
	.calc-card { max-width: 1000px; margin: auto; padding: 28px; border-radius: 20px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); }
	.calc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; align-items: center; }
	.calc-left h3 { color: #fff; font-size: 22px; margin-bottom: 6px; }
	.sub { color: rgba(255, 255, 255, 0.6); margin-bottom: 20px; font-size: 13px; }
	.field { margin-bottom: 18px; }
	.field label { font-size: 13px; color: rgba(255, 255, 255, 0.6); }
	input[type="range"] { width: 100%; accent-color: var(--secondary); height: 24px; }
	.range { display: flex; justify-content: space-between; font-size: 12px; margin-top: 4px; color: rgba(255, 255, 255, 0.6); }
	.range span:nth-child(2) { color: #fff; font-weight: 600; }
	.calc-right .label { color: rgba(255, 255, 255, 0.5); margin-bottom: 10px; font-size: 12px; }
	.result { display: flex; justify-content: space-between; padding: 12px; border-radius: 10px; margin-bottom: 8px; background: rgba(255, 255, 255, 0.05); transition: 0.2s ease; align-items: center; }
	.result:hover { background: rgba(255, 255, 255, 0.08); }
	.result span { color: rgba(255, 255, 255, 0.6); font-size: 13px; }
	.result strong { color: #fff; font-size: 17px; }
	.result.highlight { background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); }
	.calc-note { font-size: 12px; color: rgba(255, 255, 255, 0.5); margin-top: 8px; }
	@media (max-width: 768px) {
		.calc-grid { grid-template-columns: 1fr; }
		.calc-card { padding: 20px; }		
	}
</style>

<script>
	(function() {
		const root = document.getElementById('<?php echo esc_attr($uid); ?>');
		if (!root) return;

		const epd = root.querySelector('.emailsPerDay');
		const pi = root.querySelector('.perInbox');
		const ipd = root.querySelector('.inboxPerDomain');
		const epdVal = root.querySelector('.emailsPerDayVal');
		const piVal = root.querySelector('.perInboxVal');
		const ipdVal = root.querySelector('.inboxPerDomainVal');
		const inboxesEl = root.querySelector('.inboxesNeeded');
		const domainsEl = root.querySelector('.domainsNeeded');
		const maxEmailsEl = root.querySelector('.maxEmails');
		const maxMonthlyEl = root.querySelector('.maxMonthly');
		const costEl = root.querySelector('.costEstimate');
		const complexityEl = root.querySelector('.complexity');

		let timeout;

		function updateCalc() {
			const emails = +epd.value;
			const perInbox = +pi.value;
			const perDomain = +ipd.value;
			const inboxes = Math.ceil(emails / perInbox);
			const domains = Math.ceil(inboxes / perDomain);
			const domainCost = domains * 10;
			const inboxCost = inboxes * 2;
			const minCost = domainCost + inboxCost;
			const maxCost = Math.round(minCost * 1.5);

			let complexity = "Low";
			if (inboxes > 20) complexity = "Medium";
			if (inboxes > 50) complexity = "High";

			epdVal.textContent = emails.toLocaleString();
			piVal.textContent = perInbox;
			ipdVal.textContent = perDomain;

			inboxesEl.textContent = inboxes.toLocaleString();
			domainsEl.textContent = domains.toLocaleString();
			maxEmailsEl.textContent = emails.toLocaleString();
			maxMonthlyEl.textContent = (emails * 30).toLocaleString();

			costEl.textContent = `$${minCost} – $${maxCost}`;
			complexityEl.textContent = complexity;
		}

		function debounceUpdate() {
			clearTimeout(timeout);
			timeout = setTimeout(updateCalc, 50);
		}

		[epd, pi, ipd].forEach(el => {
			el.addEventListener('input', debounceUpdate);
		});

		updateCalc();
	})();
</script>

<?php
	return ob_get_clean();
}

add_shortcode('infrastructure_calculator', 'botphonic_infrastructure_calculator_shortcode');

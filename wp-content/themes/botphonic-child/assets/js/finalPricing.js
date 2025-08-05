let shortBillingPeriod = "Yearly";
let shortCurrency = "USD";
const botphonicFeatures = {
    Starter: [
        "50 mins",
        "5 Concurrent Calls",
        "Voice API, LLM, transcriber costs",
        "Unlimited Assistants",
        "API & Integrations",
        "Real-Time Booking, Human Transfer & More Actions",
        "Voice AI Courses & Community Support"
    ],
    Pro: [
        "2,000 mins, then $0.13/min",
        "25 Concurrent Calls",
        "8,000 Custom Workflows",
        "Team Access",
        "Support via Ticketing"
    ],
    Growth: [
        "4,000 mins, then $0.12/min",
        "50 Concurrent Calls",
        "42,000 Custom Workflows",
        "25 Subaccounts",
        "Everything in Pro",
        "Rebilling",
        "Access to New Features"
    ],
    Agency: [
        "6,000 mins, then $0.12/min",
        "80 Concurrent Calls",
        "100,000 Custom Workflows",
        "Unlimited Subaccounts",
        "White Label Platform",
        "30-Day Onboarding & Private Slack Channel",
        "Support via Ticketing"
    ],
    Enterprise: [
        "Volume-based Price, as low as $0.08/min",
        "SIP Trunk Integration",
        "Guaranteed Uptime (SLA)",
        "Custom Integrations",
        "200+ Concurrent Calls",
        "Compliance (SOC2, HIPAA, GDPR)",
        "Enterprise Onboarding, Training, Support"
    ]
};

// All plans storage
let allPlanPrices = {};
const billingCycles = ["Monthly", "Yearly"];
const currencies = ["USD", "INR"];
function fetchPlanPrices(billingCycle, currency) {
    const url = `https://api.botphonic.ai/api/plans?billingCycle=${billingCycle}&currency=${currency}`;
    return fetch(url)
        .then(res => res.json())
        .then(({ data: plans }) => {
            allPlanPrices[billingCycle] ??= {};
            allPlanPrices[billingCycle][currency] = {};

            plans.forEach(plan => {
                allPlanPrices[billingCycle][currency][plan.name] = plan;
            });
        })
        .catch(err => {
            console.error(`Error fetching ${billingCycle} plans for ${currency}:`, err);
            document.getElementById("shortPlanContainer").innerText = "Error loading short plans.";
        });
}

// Fetch all combinations
Promise.all(
    billingCycles.flatMap(cycle =>
        currencies.map(curr => fetchPlanPrices(cycle, curr))
    )
);

// Render short plan cards
function renderShortPlans() {
    const container = document.getElementById("shortPlanContainer");

    const plans = allPlanPrices[shortBillingPeriod]?.[shortCurrency];
    if (!plans) {
        console.log("Short plans not available.");
        return;
    }

    container.innerHTML = "";
    Object.values(plans).forEach(plan => {
        const cardCol = createElement("div", ['col']);
        const card = createElement("div", ["short-plan-card", "pricing-card"]);

        let monthlyPriceRaw = allPlanPrices["Monthly"]?.[shortCurrency]?.[plan.name]?.price;
        let planPriceRaw = plan.price;
        let planHTML = "";

        if (planPriceRaw !== null && planPriceRaw > 0) {
            const monthlyPrice = typeof monthlyPriceRaw === "number" ? monthlyPriceRaw : 0;
            const yearlyPrice = planPriceRaw;
            const expectedYearly = monthlyPrice * 12;
            const discount = shortBillingPeriod === "Yearly" && monthlyPrice > 0 ? (expectedYearly - yearlyPrice) : 0;

            planHTML = `
            <div class="short-plan-price">
                    <div class="short-plan-final">${shortCurrency} ${yearlyPrice.toFixed(2)}<small>/mo</small></div>
                ${shortBillingPeriod === "Yearly" && monthlyPrice > 0
                    ? `<span class="d-inline short-plan-strike old-price">${shortCurrency} ${monthlyPrice.toFixed(2)}</span>`
                    : ""}
            </div>`;

            // discount = discount > 0 ? `<div class="short-plan-badge">Save ${shortCurrency} ${(expectedYearly - discount).toFixed(2)}</div>` : ""

        } else {
            planHTML = `
            <div class="short-plan-price plan-price">  
                <span class="short-plan-final">Custom Pricing</span>
            </div>`;
        }

        card.innerHTML = `
        <div class="short-plan-header">
            <div class="short-plan-title h4">${plan.name}</div
            ${planHTML}
        </div>`;

        if (botphonicFeatures[plan.name] && Array.isArray(botphonicFeatures[plan.name])) {
            const featureList = document.createElement('ul');
            featureList.className = 'short-plan-main-features';

            botphonicFeatures[plan.name].forEach(feature => {
                const li = document.createElement('li');
                li.className = 'short-plan-feature-item';
                li.innerHTML = `<span class="check-icon">✔</span> ${feature}`;
                featureList.appendChild(li);
            });

            card.appendChild(featureList);
        }
        card.innerHTML += `<a href="https://app.botphonic.ai/register/" target="_blank" class="theme-btn short-plan-btn">Select</a>`;
        cardCol.appendChild(card);
        container.appendChild(cardCol);
    });
    if (document.querySelector('.small-note') === null)
        container.insertAdjacentHTML("afterend", `<div class="small-note text-end small mt-2">
                <p class="text-muted">Local taxes (VAT, GST, etc.) will be charged in addition to the prices mentioned.</p>
            </div>`);

}

// Event listeners
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".short-plan-billing-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".short-plan-billing-btn").forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            shortBillingPeriod = this.getAttribute("data-period");
            renderShortPlans();
        });
    });

    // shortCurrency change
    document.getElementById("short-currency-select").addEventListener("change", function () {
        shortCurrency = this.value;
        renderShortPlans();
    });



    const toggle = document.getElementById("billingToggle");
    const monthlyBtn = document.getElementById("monthlyBtn");
    const yearlyBtn = document.getElementById("yearlyBtn");

    let isYearly = true;

    function updateUI() {
        toggle.style.left = isYearly ? "14px" : "2px";
        monthlyBtn.style.opacity = isYearly ? "0.5" : "1";
        yearlyBtn.style.opacity = isYearly ? "1" : "0.5";
        shortBillingPeriod = isYearly ? "Yearly" : "Monthly";
        renderShortPlans();
    }

    monthlyBtn.onclick = () => (isYearly = false, updateUI());
    yearlyBtn.onclick = () => (isYearly = true, updateUI());
    toggle.parentElement.onclick = () => (isYearly = !isYearly, updateUI());

    updateUI(); // Initial state
});
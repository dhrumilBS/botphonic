let billingPeriod = "Yearly";
let currency = "USD";
let data = '';
let monthlyPlanPrice = {};

function fetchMontlyPlanPrice(currency, callback) {
    const url = `https://api.botphonic.ai/api/plans?billingCycle=Monthly&currency=${currency}`;
    fetch(url)
        .then(res => res.json())
        .then(json => {
            data = json.data;
            monthlyPlanPrice = {};
            data.forEach((plan) => {
                monthlyPlanPrice[plan.name] = plan.price;
            });

            if (typeof callback === "function") {
                callback(); // Only fetch plans after monthly prices are ready
            }
        })
        .catch(err => {
            console.error('Error fetching monthlyPlanPrice:', err);
            document.getElementById("planCards").innerText = "Error loading plans.";
        });
}


fetchMontlyPlanPrice(currency)

function fetchPlans() {
    const url = `https://api.botphonic.ai/api/plans?billingCycle=${billingPeriod}&currency=${currency}`;
    fetch(url)
        .then(res => res.json())
        .then(json => {
            if (json.status === 200 && Array.isArray(json.data)) {
                renderFeatures(json.data);
            } else {
                console.error('Unexpected response:', json);
                document.getElementById("planCards").innerText = "Failed to load plans.";
            }
        })
        .catch(err => {
            console.error('Error fetching plans:', err);
            document.getElementById("planCards").innerText = "Error loading plans.";
        });
}
function createElement(tag, className = [], text = '') {
    const el = document.createElement(tag);
    if (className.length) el.classList.add(...className);
    if (text) el.innerText = text;
    return el;
}
function renderFeatureKeys(plans) {
    const featureList = document.getElementById("featureList");
    featureList.innerHTML = "";
    featureList.classList.add('feature-list');

    const featureSections = Object.keys(plans[0].features);
    featureSections.forEach(section => {
        const headerRowWrap = createElement("div", ["section-header-wrap"]);
        const headerRow = createElement("div", ["section-header", "h5", "feature-key"], section);
        headerRowWrap.appendChild(headerRow);
        featureList.appendChild(headerRowWrap);

        const keys = Object.keys(plans[0].features[section]);
        keys.forEach(key => {
            const row = createElement("div", ["feature-item"]);
            const rowLabel = createElement("div", ["feature-key"], key);
            row.appendChild(rowLabel);
            headerRowWrap.appendChild(row);
        });
    });
}
function renderFeatures(plans) {
    renderFeatureKeys(plans);

    const planContainer = document.getElementById("planCardsRow");
    planContainer.innerHTML = "";

    const currencySymbols = {
        USD: '$',
        INR: '₹'
    };


    const currencySymbol = currencySymbols[currency] || '';

    plans.forEach(plan => {
        let discountRate = plan.price;
        let fullPrice = '';
        let discount = '';
        let originalPrice = "";
        let discountedPrice = "";
        let monthlyPriceText = "";
        let discountBadge = "";

        if (plan.billingCycle === "Yearly" && typeof (monthlyPlanPrice[plan.name]) === "number") {
            fullPrice = monthlyPlanPrice[plan.name];
            discount = Math.round((fullPrice - discountRate) * 100 / (fullPrice));

            originalPrice = `<span class="original-price">${currencySymbol}${monthlyPlanPrice[plan.name]}</span>`;
            discountedPrice = `<span class="discounted-price">${currencySymbol}${discountRate}<span class="billing-cycle">/mo</span></span>`;
            monthlyPriceText = `<div class="monthly-price">Only <strong>${currencySymbol}${Math.round(discountRate * 12)}/year</strong></div>`;
            discountBadge = `<div class="discount-badge">${discount}% OFF</div>`;
            let html = originalPrice + discountedPrice + monthlyPriceText;

        } else if (plan.price) {
            let originalPriceDisplay = `<s>${currencySymbol}${Math.round(plan.price)}</s> `;
            discountedPrice = `<span class="discounted-price">${currencySymbol}${plan.price}<span class="billing-cycle">/mo</span></span>`;
        } else {
            discountedPrice = "Custom Pricing";
        }

        const col = createElement("div", ["plan-card-col", "col", "mb-5", "mb-sm-0"]);
        const card = createElement("div", ["pricing-card", "card-main"]);
        card.setAttribute("id", plan.name.toLowerCase());

        card.innerHTML = `
                <div class="plan-card">
                    ${discountBadge}

                    <div class="plan-name h4 plan-title">${plan.name}</div>
                    <div class="price-section">
                        ${originalPrice}
                        ${discountedPrice}
                        ${monthlyPriceText}
                    </div>
                    <a href='https://app.botphonic.ai/register' target='_blank' class='price-link'>Subscribe</a>
                </div>
                <div class="feature-list"></div>`;


        // Add "Recommended" badge if Pro plan with Monthly billing
        const isName = plan.name.toLowerCase() === "pro";
        const isBillingCycle = plan.billingCycle.toLowerCase() === "monthly";
        if (isName && isBillingCycle) {
            const badge = createElement("div", ["recommended-badge"], "Recommended");
            card.prepend(badge);
            card.classList.add("rec", "recommended");
        }

        // Fill features
        const featureList = card.querySelector(".feature-list");
        const features = plan.features || {};
        Object.keys(features).forEach(section => {
            const sectionDiv = createElement("div", ["section-header-wrap"]);
            sectionDiv.setAttribute("id", `section-${section.toLowerCase()}`);

            const sectionHeader = createElement("div", ["section-header", "h5", "feature-key", "mt-0", "mt-sm-2"], section);
            sectionDiv.appendChild(sectionHeader);
            featureList.appendChild(sectionDiv);

            const keys = Object.keys(features[section]);
            keys.forEach(key => {
                const featureItem = createElement("div", ["feature-item"]);
                const featureKey = createElement("span", ["feature-key"], key);
                const featureValue = createElement("span", ["feature-value"]);
                const val = features[section][key];

                if (val === true) {
                    featureValue.innerHTML = "<img src='https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/ic-yes.svg' alt='Yes' class='check-icon' />";
                } else if (val === false) {
                    featureValue.innerHTML = "<img src='https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/ic-no.svg' alt='No' class='check-icon' />";
                } else if (typeof val === 'object' && val !== null && 'price' in val) {
                    featureValue.innerText = `${currencySymbol}${val.price}`;
                } else {
                    featureValue.innerText = val ?? "--";
                }

                featureItem.appendChild(featureKey);
                featureItem.appendChild(featureValue);
                sectionDiv.appendChild(featureItem);
            });
        });

        col.appendChild(card);
        planContainer.appendChild(col);
    });

    setupCollapsibleSections();
}

function setupCollapsibleSections() {
    if (window.innerWidth <= 575) {
        document.querySelectorAll(".section-header").forEach(header => {
            header.addEventListener("click", function () {
                const wrapper = this.closest(".section-header-wrap");
                this.classList.toggle("active");
                wrapper.classList.toggle("open");
            });
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    fetchMontlyPlanPrice(currency, fetchPlans);

    // Billing period buttons
    document.querySelectorAll(".billing-period-btn").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            document.querySelectorAll(".billing-period-btn").forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            billingPeriod = this.getAttribute("data-period");

            // Always re-fetch monthly prices before showing Yearly plan
            if (billingPeriod === "Yearly") {
                fetchMontlyPlanPrice(currency, fetchPlans);
            } else {
                fetchPlans();
            }
        });
    });

    // Currency dropdown
    const currencySelect = document.getElementById("currency-select");
    if (currencySelect) {
        currencySelect.addEventListener("change", function () {
            currency = this.value;

            if (billingPeriod === "Yearly") {
                fetchMontlyPlanPrice(currency, fetchPlans);
            } else {
                fetchPlans();
            }
        });
    }
});

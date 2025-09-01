async function getTemplate() {
    try {
        const resp = await fetch("https://api.botphonic.ai/api/template?perPage=100");
        if (!resp.ok) throw new Error(`HTTP error! ${resp.status} - ${resp.statusText}`);
        const { data } = await resp.json();
        return data.records.map(item => ({
            id: item._id,
            price: item.price == 0 ? "Free" : item.price,
            type: item.type,
            solution: item.industry.replace(/_/g, " ").replace(/\b\w/g, l => l.toUpperCase()),
            title: item.name,
            tags: item.tags,
            text: item.description,
            downloads: item.downloads,
            tools: item.tools,
            voiceLanguage: item.voiceSetting.language || "English",
            voiceGender: item.voiceSetting.voicePrompting?.gender || "Male/Female",
        }));
    } catch (err) {
        console.log("Error loading templates:", err);
        return null;
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    const resp = await getTemplate();
    if (!resp) return;
    const container = document.getElementById("templateGrid");
    const template = document.getElementById("templateGridItem");
    const indstryFormCheckInput = document.getElementById("indstryFormCheckInput");
    const industryInputs = document.getElementById("industryInputs");
    const typeFormCheckInput = document.getElementById("typeFormCheckInput");
    const tempTypeInputs = document.getElementById("tempTypeInputs");
    const clearAllBtn = document.getElementById("clearAllBtn");

    container.innerHTML = '';
    industryInputs.innerHTML = '';
    tempTypeInputs.innerHTML = '';

    resp.forEach(({ id, solution, price, title, text, type, tags, downloads }) => {
        const clone = template.content.cloneNode(true);
        const card = clone.querySelector(".template-card-wrapper");
        card.dataset.id = id;
        card.dataset.industry = solution;
        card.dataset.type = type;
        clone.querySelector(".industry-badge").textContent = solution;
        clone.querySelector(".price").textContent = price;
        clone.querySelector("h5").textContent = title;
        clone.querySelector("p").textContent = text;
        clone.querySelector(".downloads").textContent = downloads;
        const tagsContainer = clone.querySelector(".tags");
        [...tags, type].forEach(tag => {
            const span = document.createElement("span");
            span.className = "hashtag";
            span.textContent = tag;
            tagsContainer.append(span);
        });
        container.append(clone);
    });

    const createInputs = (items, inputTemplate, inputsContainer) => {
        [...new Set(items)].filter(Boolean).forEach(val => {
            const clone = inputTemplate.content.cloneNode(true);
            const input = clone.querySelector(".form-check-input");
            const label = clone.querySelector(".form-check-label");
            input.id = input.value = val;
            label.htmlFor = val;
            label.textContent = val;
            inputsContainer.append(clone);
        });
    };

    createInputs(resp.map(i => i.solution), indstryFormCheckInput, industryInputs);
    createInputs(resp.map(i => i.type), typeFormCheckInput, tempTypeInputs);

    const filterCards = () => {
        const selectedIndustry = [...industryInputs.querySelectorAll(".form-check-input:checked")].map(i => i.value);
        const selectedType = [...tempTypeInputs.querySelectorAll(".form-check-input:checked")].map(i => i.value);
        document.querySelectorAll(".template-card-wrapper").forEach(card => {
            const showIndustry = !selectedIndustry.length || selectedIndustry.includes(card.dataset.industry);
            const showType = !selectedType.length || selectedType.includes(card.dataset.type);
            card.style.display = showIndustry && showType ? "" : "none";
        });
    };

    industryInputs.addEventListener("change", filterCards);
    tempTypeInputs.addEventListener("change", filterCards);

    clearAllBtn.addEventListener('click', () => {
        [...industryInputs.querySelectorAll(".form-check-input:checked"),
        ...tempTypeInputs.querySelectorAll(".form-check-input:checked")].forEach(input => input.click());
    });

    document.addEventListener("click", e => {
        if (e.target.matches(".templateModal")) {
            const card = e.target.closest(".template-card-wrapper");
            const data = resp.find(item => item.id === card.dataset.id);
            const modal = document.getElementById("templateModal");
            modal.querySelector(".modal-title").textContent = data.title;
            modal.querySelector("#templateText").textContent = data.text;
            modal.querySelector("#templateType").textContent = data.type;
            modal.querySelector("#templateIndustry").textContent = data.solution;
            modal.querySelector("#templateLanguage").textContent = data.voiceLanguage;
            modal.querySelector("#templateVoice").textContent = data.voiceGender;
            modal.querySelector("#templateActions").innerHTML = data.tools.map(item => `<span class='action'>${item.name}</span>`).join("");
            modal.querySelector("#templateDetails").innerHTML = `
                <span class="badge bg-primary">Downloads: ${data.downloads}</span>
                <span class="badge bg-success">${data.price}</span>
            `;
            new bootstrap.Modal(modal).show();
        }
    });
});
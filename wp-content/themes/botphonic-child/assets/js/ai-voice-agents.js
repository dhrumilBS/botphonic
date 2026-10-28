/* AI voice agent template library: live data, search, filters, sort and details. */
(function () {
	'use strict';

	var root = document.getElementById('bp-voice-agents');
	if (!root) return;

	root.classList.remove('no-js');

	var grid = root.querySelector('[data-bpv-grid]');
	var cardTemplate = document.getElementById('bpv-card-template');
	var countEl = root.querySelector('[data-bpv-count]');
	var emptyEl = root.querySelector('[data-bpv-empty]');
	var searchInput = root.querySelector('[data-bpv-search]');
	var sortSelect = root.querySelector('[data-bpv-sort]');
	var activeCountEl = root.querySelector('[data-bpv-active-count]');
	var filtersPanel = root.querySelector('[data-bpv-filters-panel]');
	var optionGroups = {
		type: root.querySelector('[data-bpv-options="type"]'),
		industry: root.querySelector('[data-bpv-options="industry"]')
	};
	var modal = root.querySelector('[data-bpv-modal]');

	var state = { search: '', type: [], industry: [], sort: 'featured' };
	var agents = [];

	/* Data
	   ====================================================================== */
	var ACRONYMS = { dnd: 'DND', dnc: 'DNC', crm: 'CRM', sms: 'SMS', url: 'URL', id: 'ID', api: 'API' };

	function titleCase(value) {
		return String(value || '').replace(/_/g, ' ').trim().replace(/\w\S*/g, function (word, index) {
			var lower = word.toLowerCase();
			return index > 0 && (lower === 'and' || lower === 'of') ? lower : lower.charAt(0).toUpperCase() + lower.slice(1);
		});
	}

	function humanizeAction(name) {
		var words = String(name || '').replace(/[_-]+/g, ' ').trim().toLowerCase().split(/\s+/).filter(Boolean);
		return words.map(function (word, index) {
			if (ACRONYMS[word]) return ACRONYMS[word];
			return index === 0 ? word.charAt(0).toUpperCase() + word.slice(1) : word;
		}).join(' ');
	}

	// "FOLLOW-UP", "Follow UP" and "#Follow up" are one tag.
	function cleanTags(tags, type) {
		var seen = {};
		var typeKey = String(type || '').toLowerCase();
		return (tags || []).reduce(function (list, tag) {
			var text = String(tag || '').replace(/#/g, '').replace(/\s+/g, ' ').trim();
			if (!text) return list;
			if (text === text.toUpperCase() && /[A-Z]/.test(text)) {
				text = text.charAt(0) + text.slice(1).toLowerCase();
			}
			var key = text.toLowerCase().replace(/[^a-z0-9]/g, '');
			if (!seen[key] && key !== typeKey) {
				seen[key] = true;
				list.push(text);
			}
			return list;
		}, []);
	}

	function formatPrice(price) {
		return !price || Number(price) === 0 ? 'Free' : '$' + price;
	}

	function fromApi(item, index) {
		var voice = item.voiceSetting || {};
		var gender = voice.voicePrompting && voice.voicePrompting.gender;
		return {
			id: item._id,
			order: index,
			price: formatPrice(item.price),
			type: titleCase(item.type),
			industry: titleCase(item.industry),
			title: item.name || '',
			text: item.description || '',
			tags: cleanTags(item.tags, item.type),
			downloads: Number(item.downloads) || 0,
			// The API can list the same tool twice; show each action once.
			actions: (item.tools || []).map(function (tool) { return humanizeAction(tool.name); }).filter(function (name, i, list) {
				return name && list.indexOf(name) === i;
			}),
			language: voice.language || 'English',
			voice: gender ? titleCase(gender) : 'Male/Female'
		};
	}

	function fromFallback(item, index) {
		return {
			id: item.id,
			order: index,
			price: 'Free',
			type: item.type,
			industry: item.industry,
			title: item.title,
			text: item.text,
			tags: cleanTags(item.tags, item.type),
			downloads: Number(item.downloads) || 0,
			actions: [],
			language: 'English',
			voice: 'Male/Female'
		};
	}

	function readFallback() {
		var node = document.getElementById('bpv-fallback-data');
		try {
			return JSON.parse(node.textContent).map(fromFallback);
		} catch (e) {
			return [];
		}
	}

	function fetchAgents() {
		return fetch(root.getAttribute('data-api'))
			.then(function (resp) {
				if (!resp.ok) throw new Error('HTTP ' + resp.status);
				return resp.json();
			})
			.then(function (json) {
				return json.data.records.map(fromApi);
			})
			.catch(function (err) {
				console.log('Error loading templates:', err);
				return null;
			});
	}

	/* Filters
	   ====================================================================== */
	function buildOptions(key) {
		var group = optionGroups[key];
		var counts = {};
		agents.forEach(function (agent) {
			if (agent[key]) counts[agent[key]] = (counts[agent[key]] || 0) + 1;
		});

		group.textContent = '';
		Object.keys(counts).sort().forEach(function (value) {
			var label = document.createElement('label');
			label.className = 'bpv-option';
			var input = document.createElement('input');
			input.type = 'checkbox';
			input.value = value;
			input.checked = state[key].indexOf(value) > -1;
			var text = document.createElement('span');
			text.className = 'bpv-option__label';
			text.textContent = value;
			var count = document.createElement('span');
			count.className = 'bpv-option__count';
			count.textContent = counts[value];
			label.append(input, text, count);
			group.append(label);
		});

		// Drop selections that no longer exist in the data.
		state[key] = state[key].filter(function (value) { return counts[value]; });
	}

	function readOptions(key) {
		state[key] = Array.prototype.map.call(optionGroups[key].querySelectorAll('input:checked'), function (input) {
			return input.value;
		});
	}

	function matches(agent) {
		if (state.type.length && state.type.indexOf(agent.type) === -1) return false;
		if (state.industry.length && state.industry.indexOf(agent.industry) === -1) return false;
		if (!state.search) return true;
		var haystack = [agent.title, agent.industry, agent.type, agent.text].concat(agent.tags).join(' ').toLowerCase();
		return state.search.split(/\s+/).every(function (word) { return haystack.indexOf(word) > -1; });
	}

	function sorted(list) {
		var copy = list.slice();
		if (state.sort === 'popular') {
			copy.sort(function (a, b) { return b.downloads - a.downloads || a.order - b.order; });
		} else if (state.sort === 'name') {
			copy.sort(function (a, b) { return a.title.localeCompare(b.title); });
		} else {
			copy.sort(function (a, b) { return a.order - b.order; });
		}
		return copy;
	}

	/* Rendering
	   ====================================================================== */
	function fill(card, agent) {
		var isInbound = agent.type.toLowerCase() === 'inbound';
		card.dataset.id = agent.id;
		card.dataset.industry = agent.industry;
		card.dataset.type = agent.type;
		card.querySelector('[data-f="typechip"]').className = 'bpv-type bpv-type--' + (isInbound ? 'inbound' : 'outbound');
		card.querySelector('[data-f="type"]').textContent = agent.type;
		card.querySelector('[data-f="price"]').textContent = agent.price;
		card.querySelector('[data-f="title"]').textContent = agent.title;
		card.querySelector('[data-f="srtitle"]').textContent = ': ' + agent.title;
		card.querySelector('[data-f="industry"]').textContent = agent.industry;
		card.querySelector('[data-f="text"]').textContent = agent.text;
		card.querySelector('[data-f="downloads"]').textContent = agent.downloads.toLocaleString();

		var tags = card.querySelector('[data-f="tags"]');
		tags.textContent = '';
		agent.tags.slice(0, 3).forEach(function (tag) {
			var li = document.createElement('li');
			li.textContent = tag;
			tags.append(li);
		});
		if (agent.tags.length > 3) {
			var more = document.createElement('li');
			more.className = 'bpv-tags__more';
			more.textContent = '+' + (agent.tags.length - 3);
			tags.append(more);
		}
		tags.hidden = !agent.tags.length;
	}

	function render() {
		var visible = sorted(agents.filter(matches));
		var fragment = document.createDocumentFragment();

		visible.forEach(function (agent) {
			var clone = cardTemplate.content.cloneNode(true);
			fill(clone.querySelector('.bpv-card'), agent);
			fragment.append(clone);
		});
		grid.textContent = '';
		grid.append(fragment);

		var total = agents.length;
		countEl.textContent = visible.length === total
			? total + (total === 1 ? ' template' : ' templates')
			: 'Showing ' + visible.length + ' of ' + total + ' templates';
		emptyEl.hidden = visible.length > 0;
		grid.hidden = visible.length === 0;

		var active = state.type.length + state.industry.length;
		activeCountEl.textContent = active;
		activeCountEl.hidden = active === 0;
	}

	function updateStats() {
		var industries = {};
		agents.forEach(function (agent) { industries[agent.industry] = true; });
		var stats = { templates: agents.length, industries: Object.keys(industries).length };
		Array.prototype.forEach.call(root.querySelectorAll('[data-bpv-stat]'), function (el) {
			el.textContent = stats[el.getAttribute('data-bpv-stat')];
		});
	}

	function load(list) {
		agents = list;
		buildOptions('type');
		buildOptions('industry');
		updateStats();
		render();
	}

	/* Details dialog
	   ====================================================================== */
	function openDetails(agent, trigger) {
		var set = function (name, value) { modal.querySelector('[data-m="' + name + '"]').textContent = value; };
		var isInbound = agent.type.toLowerCase() === 'inbound';

		modal.querySelector('[data-m="typechip"]').className = 'bpv-type bpv-type--' + (isInbound ? 'inbound' : 'outbound');
		set('type', agent.type);
		set('type2', agent.type);
		set('industry', agent.industry);
		set('industry2', agent.industry);
		set('title', agent.title);
		set('text', agent.text);
		set('language', agent.language);
		set('voice', agent.voice);
		set('downloads', agent.downloads.toLocaleString());
		set('price', agent.price);

		var list = modal.querySelector('[data-m="actions"]');
		list.textContent = '';
		agent.actions.forEach(function (action) {
			var li = document.createElement('li');
			li.textContent = action;
			list.append(li);
		});
		modal.querySelector('[data-m="actionswrap"]').hidden = !agent.actions.length;

		modal._trigger = trigger;
		if (typeof modal.showModal === 'function') {
			modal.showModal();
		} else {
			modal.setAttribute('open', '');
		}
		modal.querySelector('.bpv-modal__body').scrollTop = 0;
	}

	function closeDetails() {
		if (typeof modal.close === 'function') {
			modal.close();
		} else {
			modal.removeAttribute('open');
		}
	}

	modal.addEventListener('close', function () {
		if (modal._trigger && document.contains(modal._trigger)) modal._trigger.focus();
	});
	modal.addEventListener('click', function (e) {
		// A click on the backdrop lands on the dialog element itself.
		if (e.target === modal || e.target.closest('[data-bpv-close]')) closeDetails();
	});

	grid.addEventListener('click', function (e) {
		var card = e.target.closest('.bpv-card');
		if (!card) return;
		var agent = agents.filter(function (item) { return item.id === card.dataset.id; })[0];
		if (agent) openDetails(agent, card.querySelector('[data-bpv-open]'));
	});

	/* Controls
	   ====================================================================== */
	Object.keys(optionGroups).forEach(function (key) {
		optionGroups[key].addEventListener('change', function () {
			readOptions(key);
			render();
		});
	});

	var searchTimer = 0;
	searchInput.addEventListener('input', function () {
		clearTimeout(searchTimer);
		searchTimer = setTimeout(function () {
			state.search = searchInput.value.trim().toLowerCase();
			render();
		}, 120);
	});
	root.querySelector('[data-bpv-search-form]').addEventListener('submit', function (e) {
		e.preventDefault();
		grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
	});

	sortSelect.addEventListener('change', function () {
		state.sort = sortSelect.value;
		render();
	});

	Array.prototype.forEach.call(root.querySelectorAll('[data-bpv-clear]'), function (button) {
		button.addEventListener('click', function () {
			state.type = [];
			state.industry = [];
			state.search = '';
			searchInput.value = '';
			Array.prototype.forEach.call(root.querySelectorAll('[data-bpv-options] input'), function (input) {
				input.checked = false;
			});
			render();
		});
	});

	// Filters start collapsed on small screens and always open on wide ones.
	var narrow = window.matchMedia('(max-width: 991.98px)');
	function syncPanel() {
		filtersPanel.open = !narrow.matches;
	}
	syncPanel();
	if (narrow.addEventListener) {
		narrow.addEventListener('change', syncPanel);
	} else {
		narrow.addListener(syncPanel);
	}

	/* Start: fallback now, live data when it arrives */
	load(readFallback());
	fetchAgents().then(function (list) {
		if (list && list.length) load(list);
	});
})();

<?php
add_filter('wpseo_schema_graph', function ($graph, $context) {

	// ─── 1. Enhance the existing Organization node ───────────────────────
	foreach ($graph as &$node) {
		$types = isset($node['@type']) ? (array) $node['@type'] : [];
		if (in_array('Organization', $types, true)) {
			$node['description'] = 'Botphonic is an AI-powered call assistant platform that helps businesses automate and scale customer communication with smart voice technology.';
			$node['email'] = 'contact@botphonic.ai';

			// Link to the Person entity below via @id (not inline duplicate)
			$node['founder'] = [
				'@id' => home_url('/#ketan-mangukiya'), // fixed: must match Person node's @id exactly
			];

			$node['address'] = [
				'@type' => 'PostalAddress',
				'streetAddress' => '1915, 447 Broadway, 2nd Floor',
				'addressLocality' => 'New York',
				'addressRegion' => 'NY',
				'postalCode' => '10013',
				'addressCountry' => 'US',
			];

			break; // Only one Organization node should exist; stop after the first match
		}
	}
	unset($node); // Required: break foreach-by-reference to prevent ghost mutation

	// ─── 2. SoftwareApplication — homepage only ──────────────────────────
	if (is_front_page() && !is_page(23537)) {
		$already_present = false;
		foreach ($graph as $node) {
			if (($node['@id'] ?? '') === home_url('/#software-home')) {
				$already_present = true;
				break;
			}
		}

		if (!$already_present) {
			$graph[] = [
				'@type' => 'SoftwareApplication',
				'@id' => home_url('/#software-home'),
				'name' => 'Botphonic AI Call Assistant',
				'applicationCategory' => 'BusinessApplication',
				'operatingSystem' => 'All',
				'url' => home_url('/'),
				'description' => 'End-to-end AI call assistant that handles inbound and outbound calls, schedules appointments, qualifies leads, and closes sales with human-like voice AI at under 300ms latency.',
				'publisher' => [
					'@id' => home_url('/#organization'),
				],
				'mainEntityOfPage' => [
					'@id' => home_url('/#webpage'),
				],
				'offers' => [
					'@type' => 'AggregateOffer',
					'priceCurrency' => 'USD',
					'lowPrice' => '22.00',
					'highPrice' => '60.00',
					'offerCount' => '3',
					'url' => home_url('/pricings-plans/'),
				],
			];
		}
	}

	// ─── 3. Founder Person entity (site-wide, not homepage-only) ─────────
	$person_already_present = false;
	foreach ($graph as $node) {
		if (($node['@id'] ?? '') === home_url('/#ketan-mangukiya')) {
			$person_already_present = true;
			break;
		}
	}

	if (!$person_already_present) {
		$graph[] = [
			'@type' => 'Person',
			'@id' => home_url('/#ketan-mangukiya'),
			'name' => 'Ketan Mangukiya',
			'jobTitle' => 'Founder & CEO',
			'sameAs' => [
				'https://www.linkedin.com/in/ketanmangukiya',
			],
			'worksFor' => [
				'@id' => home_url('/#organization'),
			],
			'knowsAbout' => [
				'Artificial Intelligence',
				'Voice AI',
				'Conversational AI',
				'SaaS',
				'Call Automation',
			],
			'description' => 'Founder of Botphonic, building AI-powered voice agents for automated calling and customer interactions.',
		];
	}

	return $graph;
}, 10, 2);
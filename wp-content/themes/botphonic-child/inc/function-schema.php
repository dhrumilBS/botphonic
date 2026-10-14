<?php
add_filter('wpseo_schema_graph', function ($graph, $context) {

	foreach ($graph as &$node) {
		$types = isset($node['@type']) ? (array) $node['@type'] : [];
		if (in_array('Organization', $types, true)) {
			$node['description'] = 'Botphonic is an AI-powered call assistant platform that helps businesses automate and scale customer communication with smart voice technology.';
			$node['email'] = 'contact@botphonic.ai';

			$node['founder'] = [
				'@id' => home_url('/#ketan-mangukiya'),
			];

			$node['address'] = [
				'@type' => 'PostalAddress',
				'streetAddress' => '1915, 447 Broadway, 2nd Floor',
				'addressLocality' => 'New York',
				'addressRegion' => 'NY',
				'postalCode' => '10013',
				'addressCountry' => 'US',
			];

			break;
		}
	}
	unset($node);

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
					'url' => home_url('/pricing/'),
				],
			];
		}
	}

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


add_filter('wpseo_schema_graph', function ($graph) {

	if (!function_exists('botphonic_is_alt_archive_view') || !botphonic_is_alt_archive_view() || is_search()) {
		return $graph;
	}

	$items = array();
	$position = 0;

	foreach ((array) $GLOBALS['wp_query']->posts as $alt_post) {
		$items[] = array(
			'@type' => 'ListItem',
			'position' => ++$position,
			'url' => get_permalink($alt_post),
			'name' => wp_strip_all_tags(get_the_title($alt_post)),
		);
	}

	if (!$items) {
		return $graph;
	}

	foreach ($graph as &$node) {
		$types = isset($node['@type']) ? (array) $node['@type'] : array();

		if (!in_array('CollectionPage', $types, true)) {
			continue;
		}

		$list_id = $node['@id'] . '#itemlist';
		$node['about'] = array('@id' => home_url('/#organization'));
		$node['mainEntity'] = array('@id' => $list_id);

		$graph[] = array(
			'@type' => 'ItemList',
			'@id' => $list_id,
			'name' => __('Botphonic comparison guides', 'botphonic'),
			'numberOfItems' => count($items),
			'itemListOrder' => 'https://schema.org/ItemListOrderDescending',
			'itemListElement' => $items,
		);
		break;
	}
	unset($node);

	return $graph;
}, 30);

add_filter('wpseo_schema_article_type', function ($type, $indexable) {

	if (
		function_exists('botphonic_alt_post_type')
		&& isset($indexable->object_sub_type)
		&& botphonic_alt_post_type() === $indexable->object_sub_type
		&& 'None' === $type
	) {
		return 'Article';
	}

	return $type;
}, 10, 2);

add_filter('wpseo_schema_article', function ($data) {

	if (function_exists('botphonic_is_alt_single_view') && botphonic_is_alt_single_view()) {
		unset($data['wordCount']);
	}

	return $data;
});

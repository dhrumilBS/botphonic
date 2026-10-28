<?php
/**
 * Leadership people, shared by the Organization node below and the About page
 * (functions.php §11). The @id values must stay the same in both places so
 * Google merges them into one person.
 * Source: https://botphonic.ai/blog/author/bharat-godhani/ and /tosif-saiyad/
 */
if (!function_exists('botphonic_leadership_people')) {
	function botphonic_leadership_people()
	{
		$org_id = home_url('/#organization');

		return array(
			array(
				'@type' => 'Person',
				'@id' => home_url('/#/schema/person/bharat-godhani'),
				'name' => 'Bharat Godhani',
				'jobTitle' => 'Founder & CEO',
				'worksFor' => array('@id' => $org_id),
				'url' => home_url('/blog/author/bharat-godhani/'),
				'sameAs' => array('https://www.linkedin.com/in/bharat-godhani/'),
				'description' => 'Founder and CEO of Botphonic, which he founded in 2025. Over thirteen years building Bigscal, he has overseen more than 1,000 software projects across 25 countries. He designed Botphonic\'s end-to-end architecture and leads its product direction.',
				'knowsAbout' => array(
					'Voice AI',
					'Call automation',
					'Inbound calls',
					'Outbound calls',
					'Software architecture',
				),
			),
			array(
				'@type' => 'Person',
				'@id' => home_url('/#/schema/person/tosif-saiyad'),
				'name' => 'Tosif Saiyad',
				'jobTitle' => 'Chief Technology Officer',
				'worksFor' => array('@id' => $org_id),
				'url' => home_url('/blog/author/tosif-saiyad/'),
				'sameAs' => array('https://www.linkedin.com/in/tosif-saiyad-30a184140/'),
				'description' => 'CTO of Botphonic, leading engineering of its AI voice agents. In software development since 2014, with a background in backend development, APIs, cloud infrastructure and real-time applications. Led the work that brought Botphonic\'s response latency to about 300 milliseconds.',
				'knowsAbout' => array(
					'Voice AI',
					'Telephony',
					'Real-time applications',
					'APIs and integrations',
					'Cloud infrastructure',
				),
			),
		);
	}
}

add_filter('wpseo_schema_graph', function ($graph, $context) {

	foreach ($graph as &$node) {
		$types = isset($node['@type']) ? (array) $node['@type'] : [];
		if (in_array('Organization', $types, true)) {
			$node['description'] = 'Botphonic is an AI-powered call assistant platform that helps businesses automate and scale customer communication with smart voice technology.';
			$node['email'] = 'contact@botphonic.ai';

			$node['founder'] = [
				'@id' => home_url('/#/schema/person/bharat-godhani'),
			];
			$node['foundingDate'] = '2025';

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
					'lowPrice' => '12.00', // Starter, billed yearly. Update when prices change.
					'url' => home_url('/pricing/'),
				],
			];
		}
	}

	// Founder & CEO and CTO, on every page (the Organization above points to the founder).
	$present = array();
	foreach ($graph as $node) {
		if (!empty($node['@id'])) {
			$present[$node['@id']] = true;
		}
	}
	foreach (botphonic_leadership_people() as $person) {
		if (empty($present[$person['@id']])) {
			$graph[] = $person;
		}
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
<?php
/**
 * Rwanda local SEO: page titles, structured data and location targeting
 * (proposal section C). The Core plugin's SEO layer is Kenya-targeted; these
 * filters retarget it at Kigali and Rwanda without editing the shared plugin.
 *
 * @package SoundCreationsRwanda
 */

if ( \! defined( 'ABSPATH' ) ) {
	exit;
}

/* Archive titles: the pages that rank for category searches. */
add_filter(
	'sc_seo_archive_titles',
	function ( $map ) {
		return array_merge(
			(array) $map,
			array(
				'sc_solution'   => 'Audio Visual, Lighting & Acoustic Solutions in Kigali, Rwanda',
				'sc_product'    => 'Professional Audio Equipment Rwanda',
				'sc_project'    => 'Sound & AV Installation Projects Rwanda',
				'sc_brand'      => 'Professional Audio & Acoustic Brands Rwanda',
				'sc_resource'   => 'Professional Audio & AV Videos',
				'sc_case_study' => 'Audio & AV Case Studies Rwanda',
			)
		);
	}
);

/* Singular titles: Core appends "Kenya"; swap the location. */
add_filter(
	'sc_seo_singular_title',
	function ( $title ) {
		return scrw_localise( $title );
	}
);

/** Replace Kenya location words with Rwanda equivalents. */
function scrw_localise( $text ) {
	return str_replace(
		array( 'Nairobi County', 'Nairobi', 'Kenya' ),
		array( 'Kigali City', 'Kigali', 'Rwanda' ),
		(string) $text
	);
}

/*
 * Structured data. On this install the "primary" branch and the Organization
 * address carry the Kigali address from Settings, but Core labels them
 * Nairobi / KE. Walk the graph and correct every PostalAddress, rename the
 * branch node, and link the entity to the parent group site.
 */
add_filter(
	'sc_seo_graph',
	function ( $graph ) {
		return scrw_fix_graph_node( $graph );
	},
	20
);

function scrw_fix_graph_node( $node ) {
	if ( \! is_array( $node ) ) {
		return $node;
	}

	if ( isset( $node['@type'] ) && 'PostalAddress' === $node['@type'] ) {
		if ( isset( $node['addressCountry'] ) && 'KE' === $node['addressCountry'] ) {
			$node['addressLocality'] = 'Kigali';
			$node['addressRegion']   = 'Kigali City';
			$node['addressCountry']  = 'RW';
		}
		return $node;
	}

	foreach ( array( 'name', 'alternateName', 'description' ) as $k ) {
		if ( isset( $node[ $k ] ) && is_string( $node[ $k ] ) ) {
			$node[ $k ] = scrw_localise( $node[ $k ] );
		}
	}

	$types = isset( $node['@type'] ) ? (array) $node['@type'] : array();
	$is_org = false;
	foreach ( $types as $t ) {
		if ( is_string( $t ) && ( 'Organization' === $t || false \!== strpos( $t, 'Business' ) || 'ProfessionalService' === $t || 'Store' === $t ) ) {
			$is_org = true;
		}
	}
	if ( $is_org && \! isset( $node['parentOrganization'] ) ) {
		$node['parentOrganization'] = array(
			'@type' => 'Organization',
			'name'  => 'Sound Creations Ltd',
			'url'   => SCRW_GROUP_URL,
		);
	}

	foreach ( $node as $k => $v ) {
		if ( is_array( $v ) ) {
			$node[ $k ] = scrw_fix_graph_node( $v );
		}
	}
	return $node;
}

/* Geo meta for Kigali. */
add_action(
	'wp_head',
	function () {
		echo '<meta name="geo.region" content="RW-01">' . "\n";
		echo '<meta name="geo.placename" content="Kigali">' . "\n";
	},
	2
);

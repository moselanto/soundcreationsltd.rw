<?php
/**
 * Rwanda market SEO (v1.7.0): "price in Rwanda" intent on product pages.
 *
 * Google autocomplete for Rwanda (gl=rw, 13 Oct 2026) shows buyers search
 * with "price in rwanda" far more than any other modifier: "keyboard price in
 * rwanda", "piano price in rwanda", "yamaha piano price in rwanda",
 * "speaker price in rwanda", "microphone price in rwanda", "wireless
 * microphone price in rwanda", "mixer price in rwanda", "guitar price in
 * rwanda", "shure sm7b price rwanda". Competitors that rank (Roots Rwanda,
 * Safe Home Rwanda, Ubuy, Zimcompass) all show an RWF price.
 *
 * What this file does, honestly:
 *  1. Adds an optional "Price in Rwanda (RWF)" and "In stock in Kigali"
 *     field to every product. Nothing is invented: if no price is entered the
 *     page says "ask for today's price" with a one-tap WhatsApp message.
 *  2. Shows a visible price box at the top of each product description.
 *  3. Only when a price is entered, adds an Offer (RWF) to the Product schema,
 *     which is what Google needs for price-rich results and the Shopping tab.
 *  4. Adds a short visible buyer FAQ (price, genuine/warranty, where to buy)
 *     plus matching FAQPage schema, unless the product already has its own FAQ.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Price in RWF for a product, or 0 when not set. */
function scrw_product_price( $post_id ) {
	return (int) preg_replace( '/[^0-9]/', '', (string) get_post_meta( $post_id, '_scrw_price_rwf', true ) );
}

/** True when the editor ticked "In stock in Kigali". */
function scrw_product_in_stock( $post_id ) {
	return '1' === (string) get_post_meta( $post_id, '_scrw_in_stock', true );
}

/** WhatsApp link with a pre-filled price question. */
function scrw_product_whatsapp_url( $name ) {
	$num = function_exists( 'scrw_schema_setting' ) ? scrw_schema_setting( 'whatsapp' ) : '';
	if ( ! $num && function_exists( 'scrw_schema_setting' ) ) {
		$num = scrw_schema_setting( 'phone' );
	}
	$num = preg_replace( '/[^0-9]/', '', (string) $num );
	if ( ! $num ) {
		return '';
	}
	$msg = sprintf( 'Hello Sound Creations, what is today\'s price in Rwanda for the %s? Is it in stock in Kigali?', $name );
	return 'https://wa.me/' . $num . '?text=' . rawurlencode( $msg );
}

/* ---- 1. Editor fields ---- */
add_action(
	'add_meta_boxes',
	function () {
		add_meta_box(
			'scrw_price',
			__( 'Price in Rwanda', 'soundcreations-rwanda' ),
			function ( $post ) {
				wp_nonce_field( 'scrw_price_save', 'scrw_price_nonce' );
				$price = scrw_product_price( $post->ID );
				echo '<p><label for="scrw_price_rwf"><strong>Price (RWF, VAT included)</strong></label><br><input type="text" inputmode="numeric" id="scrw_price_rwf" name="scrw_price_rwf" value="' . esc_attr( $price ? $price : '' ) . '" style="width:100%" placeholder="e.g. 780000"></p>';
				echo '<p><label><input type="checkbox" name="scrw_in_stock" value="1" ' . checked( scrw_product_in_stock( $post->ID ), true, false ) . '> In stock in Kigali</label></p>';
				echo '<p style="color:#555;font-size:12px;">Leave the price blank to show "Ask for today\'s price". A price is shown on the page and sent to Google; keep it current.</p>';
			},
			'sc_product',
			'side',
			'high'
		);
	}
);

add_action(
	'save_post_sc_product',
	function ( $post_id ) {
		if ( ! isset( $_POST['scrw_price_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['scrw_price_nonce'] ) ), 'scrw_price_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$price = isset( $_POST['scrw_price_rwf'] ) ? (int) preg_replace( '/[^0-9]/', '', sanitize_text_field( wp_unslash( $_POST['scrw_price_rwf'] ) ) ) : 0;
		if ( $price > 0 ) {
			update_post_meta( $post_id, '_scrw_price_rwf', $price );
		} else {
			delete_post_meta( $post_id, '_scrw_price_rwf' );
		}
		if ( ! empty( $_POST['scrw_in_stock'] ) ) {
			update_post_meta( $post_id, '_scrw_in_stock', '1' );
		} else {
			delete_post_meta( $post_id, '_scrw_in_stock' );
		}
	}
);

/** The buyer FAQ for a product (visible text and schema use the same rows). */
function scrw_product_faq_rows( $post_id ) {
	$name  = wp_strip_all_tags( get_the_title( $post_id ) );
	$price = scrw_product_price( $post_id );
	$price_answer = $price
		? sprintf( 'The %1$s is RWF %2$s (VAT included) at Sound Creations in Kigali. Prices can change with exchange rates, so confirm today\'s price by phone or WhatsApp before you visit.', $name, number_format( $price ) )
		: sprintf( 'Prices for the %s change with stock and exchange rates, so we quote today\'s price in RWF on request. Send us a WhatsApp message or call and we will reply with the current price and availability.', $name );
	return array(
		array(
			'q' => sprintf( 'How much is the %s in Rwanda?', $name ),
			'a' => $price_answer,
		),
		array(
			'q' => sprintf( 'Is the %s genuine, with warranty?', $name ),
			'a' => 'Yes. Sound Creations supplies genuine products through official brand channels, with local warranty and after-sales support from our Kigali team.',
		),
		array(
			'q' => sprintf( 'Where can I buy the %s in Kigali?', $name ),
			'a' => 'At the Sound Creations showroom on KN1 Rd, Muhima, Kigali (Monday to Friday 9:00 to 18:00, Saturday 9:00 to 13:30), or by enquiry on this page.',
		),
	);
}

/** True when our FAQ should be printed (the product has no FAQ of its own). */
function scrw_product_use_faq( $post_id ) {
	return empty( get_post_meta( $post_id, '_sc_faq', true ) );
}

/* ---- 2 + 4. Visible price box and FAQ ---- */
add_filter(
	'the_content',
	function ( $content ) {
		if ( is_admin() || is_feed() || ! is_singular( 'sc_product' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}
		$id    = get_the_ID();
		$name  = wp_strip_all_tags( get_the_title( $id ) );
		$price = scrw_product_price( $id );
		$wa    = scrw_product_whatsapp_url( $name );

		// v2.3.1: explicit sizes on divs (not p/eyebrow classes) so theme
		// typography cannot enlarge the copy, and a self-styled WhatsApp
		// button so its label is always readable (live check 18 Oct 2026).
		$box  = '<div class="scrw-price" style="border:1px solid rgba(255,255,255,.12);border-left:4px solid #ba0b0b;border-radius:12px;padding:16px 18px;margin:0 0 1.5rem;font-size:16px;line-height:1.5;">';
		$box .= '<div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#ba0b0b;margin:0 0 6px;">' . esc_html__( 'Price in Rwanda', 'soundcreations-rwanda' ) . '</div>';
		if ( $price ) {
			$box .= '<div style="font-size:22px;font-weight:700;margin:0 0 6px;">RWF ' . esc_html( number_format( $price ) ) . ' <span style="font-size:13px;font-weight:400;opacity:.75;">' . esc_html__( 'VAT included', 'soundcreations-rwanda' ) . '</span></div>';
		} else {
			$box .= '<div style="font-size:18px;font-weight:600;margin:0 0 6px;">' . esc_html__( 'Ask for today\'s price in RWF', 'soundcreations-rwanda' ) . '</div>';
		}
		$box .= '<div style="font-size:15px;margin:0 0 12px;opacity:.85;">' . ( scrw_product_in_stock( $id ) ? esc_html__( 'In stock at our Kigali showroom.', 'soundcreations-rwanda' ) : esc_html__( 'Genuine product with local warranty. Ask about availability in Kigali.', 'soundcreations-rwanda' ) ) . '</div>';
		if ( $wa ) {
			$box .= '<a class="scrw-price__wa" href="' . esc_url( $wa ) . '" target="_blank" rel="noopener" style="display:inline-block;background:#ba0b0b;color:#fff;font-size:15px;font-weight:600;line-height:1.2;padding:12px 20px;border-radius:999px;text-decoration:none;white-space:normal;">' . esc_html__( 'Get the price on WhatsApp', 'soundcreations-rwanda' ) . '</a>';
		}
		$box .= '</div>';

		$faq = '';
		if ( scrw_product_use_faq( $id ) ) {
			$faq = '<h2>' . esc_html__( 'Buying in Rwanda: common questions', 'soundcreations-rwanda' ) . '</h2>';
			foreach ( scrw_product_faq_rows( $id ) as $row ) {
				$faq .= '<h3 style="font-size:1.05rem;margin:1.2rem 0 .3rem;">' . esc_html( $row['q'] ) . '</h3><p>' . esc_html( $row['a'] ) . '</p>';
			}
		}
		return $box . $content . $faq;
	},
	8
);

/* ---- 3 + 4. Schema: Offer when priced, FAQPage for the visible FAQ ---- */
add_filter(
	'sc_seo_graph',
	function ( $graph ) {
		if ( ! is_array( $graph ) || ! is_singular( 'sc_product' ) ) {
			return $graph;
		}
		$id      = get_queried_object_id();
		$url     = get_permalink( $id );
		$page_id = untrailingslashit( $url ) . '/#webpage';
		$price   = scrw_product_price( $id );

		foreach ( $graph as $i => $node ) {
			if ( ! is_array( $node ) || ! in_array( 'Product', (array) ( $node['@type'] ?? array() ), true ) ) {
				continue;
			}
			if ( $price ) {
				$offer = array(
					'@type'         => 'Offer',
					'url'           => $url,
					'price'         => (string) $price,
					'priceCurrency' => 'RWF',
					'itemCondition' => 'https://schema.org/NewCondition',
					'seller'        => array( '@id' => home_url( '/#organization' ) ),
					'areaServed'    => array( '@type' => 'Country', 'name' => 'Rwanda' ),
				);
				if ( scrw_product_in_stock( $id ) ) {
					$offer['availability'] = 'https://schema.org/InStock';
				}
				$graph[ $i ]['offers'] = $offer;
			} else {
				// v2.2.2: no real price, so no Product node at all. Search Console
				// reported 28 invalid Product snippets ("Either 'offers', 'review'
				// or 'aggregateRating' should be specified"). We never invent a
				// price, rating or review, so an unpriced, enquiry-only item is
				// described by its ItemPage, breadcrumb and visible FAQ instead.
				// Entering a real RWF price brings the Product + Offer back.
				unset( $graph[ $i ] );
			}
		}
		$graph = array_values( $graph );

		if ( scrw_product_use_faq( $id ) ) {
			$qs = array();
			foreach ( scrw_product_faq_rows( $id ) as $row ) {
				$qs[] = array(
					'@type'          => 'Question',
					'name'           => $row['q'],
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $row['a'],
					),
				);
			}
			$graph[] = array(
				'@type'      => 'FAQPage',
				'@id'        => untrailingslashit( $url ) . '/#faq',
				'isPartOf'   => array( '@id' => $page_id ),
				'mainEntity' => $qs,
			);
		}
		return $graph;
	},
	50
);

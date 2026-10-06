<?php
/**
 * Sound Creations widgets for Elementor.
 *
 * Every widget reads business data from Sound Creations -> Settings (phone,
 * email, address, WhatsApp...) or from the site's own content (Projects,
 * Brands), so editors never retype contact details and a change in Settings
 * updates every page at once. Loaded only inside elementor/widgets/register.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

/** Read a Sound Creations setting safely. */
function scrw_s( $key, $default = '' ) {
	return function_exists( 'sc_setting' ) ? (string) sc_setting( $key, $default ) : (string) $default;
}

/** Shared base: category, keywords. */
abstract class SCRW_Widget_Base extends Widget_Base {
	public function get_categories() {
		return array( 'sound-creations' );
	}
	public function get_keywords() {
		return array( 'sound creations', 'rwanda', 'kigali' );
	}
}

/* ---------------------------------------------------------------- */
class SCRW_Widget_Contact_Card extends SCRW_Widget_Base {
	public function get_name() {
		return 'scrw_contact_card';
	}
	public function get_title() {
		return 'SC Contact Card';
	}
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => 'Contact card', 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'heading', array( 'label' => 'Heading', 'type' => Controls_Manager::TEXT, 'default' => 'Talk to our Kigali team' ) );
		$this->add_control( 'note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => 'Phone, email, address, hours and WhatsApp come from Sound Creations &rarr; Settings.' ) );
		$this->add_control( 'show_hours', array( 'label' => 'Show opening hours', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_whatsapp', array( 'label' => 'Show WhatsApp button', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->end_controls_section();
	}
	protected function render() {
		$st    = $this->get_settings_for_display();
		$phone = scrw_s( 'phone' );
		$tel   = scrw_s( 'phone_link' );
		$email = scrw_s( 'email' );
		$addr  = scrw_s( 'address' );
		$wa    = function_exists( 'sc_whatsapp_url' ) ? sc_whatsapp_url() : '';
		echo '<div class="scrw-contact-card">';
		if ( ! empty( $st['heading'] ) ) {
			echo '<h3 class="scrw-contact-card__title">' . esc_html( $st['heading'] ) . '</h3>';
		}
		echo '<ul class="scrw-contact-card__list">';
		if ( $phone ) {
			$p2  = scrw_s( 'phone2' );
			$t2  = scrw_s( 'phone2_link' );
			$alt = $p2 ? '<br><a href="' . esc_url( 'tel:' . $t2 ) . '">' . esc_html( $p2 ) . '</a>' : '';
			echo '<li><span>Phone</span><a href="' . esc_url( 'tel:' . $tel ) . '">' . esc_html( $phone ) . '</a>' . $alt . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
		}
		if ( $email ) {
			echo '<li><span>Email</span><a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a></li>';
		}
		if ( $addr ) {
			echo '<li><span>Address</span>' . esc_html( $addr ) . '</li>';
		}
		if ( 'yes' === $st['show_hours'] ) {
			$hours = array_filter( array( scrw_s( 'hours_week' ), scrw_s( 'hours_sat' ) ) );
			if ( $hours ) {
				echo '<li><span>Hours</span>' . esc_html( implode( ' | ', $hours ) ) . '</li>';
			}
		}
		echo '</ul>';
		if ( 'yes' === $st['show_whatsapp'] && $wa ) {
			echo '<a class="sc-btn sc-btn--primary" href="' . esc_url( $wa ) . '" target="_blank" rel="noopener">Chat on WhatsApp</a>';
		}
		echo '</div>';
	}
}

/* ---------------------------------------------------------------- */
class SCRW_Widget_Enquiry_Form extends SCRW_Widget_Base {
	public function get_name() {
		return 'scrw_enquiry_form';
	}
	public function get_title() {
		return 'SC Enquiry / Quote Form';
	}
	public function get_icon() {
		return 'eicon-form-horizontal';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => 'Form', 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control(
			'type',
			array(
				'label'   => 'Form type',
				'type'    => Controls_Manager::SELECT,
				'default' => 'quote',
				'options' => array(
					'quote'        => 'Request a Quote',
					'consultation' => 'Request a Consultation',
					'contact'      => 'General Contact',
					'support'      => 'Technical Support',
					'dealer'       => 'Dealer Application',
				),
			)
		);
		$this->add_control( 'title', array( 'label' => 'Form heading (optional)', 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$this->add_control( 'note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => 'Submissions are spam-filtered, saved under Enquiries and emailed per Sound Creations &rarr; Enquiry Routing.' ) );
		$this->end_controls_section();
	}
	protected function render() {
		$st = $this->get_settings_for_display();
		if ( ! function_exists( 'sc_enq_render_form' ) ) {
			echo '<p>Activate the Sound Creations Enquiries plugin to show this form.</p>';
			return;
		}
		echo sc_enq_render_form( sanitize_key( $st['type'] ), (string) $st['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin escapes its own markup.
	}
}

/* ---------------------------------------------------------------- */
class SCRW_Widget_Projects_Grid extends SCRW_Widget_Base {
	public function get_name() {
		return 'scrw_projects_grid';
	}
	public function get_title() {
		return 'SC Projects Grid';
	}
	public function get_icon() {
		return 'eicon-gallery-grid';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => 'Projects', 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'count', array( 'label' => 'Number of projects', 'type' => Controls_Manager::NUMBER, 'default' => 6, 'min' => 1, 'max' => 24 ) );
		$this->add_control( 'location', array( 'label' => 'Only projects whose location contains', 'type' => Controls_Manager::TEXT, 'default' => '', 'placeholder' => 'e.g. Rwanda or Kigali' ) );
		$this->add_control( 'show_link', array( 'label' => 'Show "View all projects" link', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->end_controls_section();
	}
	protected function render() {
		$st   = $this->get_settings_for_display();
		$args = array(
			'post_type'      => 'sc_project',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, (int) $st['count'] ),
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			'no_found_rows'  => true,
		);
		$loc = trim( (string) $st['location'] );
		if ( '' !== $loc ) {
			$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_sc_location',
					'value'   => $loc,
					'compare' => 'LIKE',
				),
			);
		}
		$q = new WP_Query( $args );
		if ( ! $q->have_posts() ) {
			echo '<p class="scrw-empty">Projects will appear here once they are added under Projects in wp-admin.</p>';
			return;
		}
		echo '<div class="scrw-proj-grid">';
		while ( $q->have_posts() ) {
			$q->the_post();
			$pid = get_the_ID();
			$img = function_exists( 'sc_project_card_image' ) ? sc_project_card_image( $pid ) : get_the_post_thumbnail_url( $pid, 'medium_large' );
			$pl  = (string) get_post_meta( $pid, '_sc_location', true );
			echo '<a class="sc-project" href="' . esc_url( get_permalink() ) . '">';
			if ( $img ) {
				echo '<span class="sc-project__media"><img src="' . esc_url( $img ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy" decoding="async" width="400" height="250"></span>';
			}
			echo '<h3 class="sc-project__title">' . esc_html( get_the_title() ) . '</h3>';
			if ( '' !== $pl ) {
				echo '<p class="sc-project__loc">' . esc_html( $pl ) . '</p>';
			}
			echo '</a>';
		}
		wp_reset_postdata();
		echo '</div>';
		if ( 'yes' === $st['show_link'] ) {
			echo '<p class="scrw-proj-more"><a class="sc-link-arrow" href="' . esc_url( home_url( '/projects/' ) ) . '">View all projects &rarr;</a></p>';
		}
	}
}

/* ---------------------------------------------------------------- */
class SCRW_Widget_Brands extends SCRW_Widget_Base {
	public function get_name() {
		return 'scrw_brands';
	}
	public function get_title() {
		return 'SC Brands & Partners';
	}
	public function get_icon() {
		return 'eicon-logo';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => 'Brands', 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => 'Shows the partner logos managed under Brands in wp-admin (same as the main site).' ) );
		$this->end_controls_section();
	}
	protected function render() {
		echo do_shortcode( '[sc_partners]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme shortcode escapes its own markup.
	}
}

/* ---------------------------------------------------------------- */
class SCRW_Widget_CTA_Band extends SCRW_Widget_Base {
	public function get_name() {
		return 'scrw_cta_band';
	}
	public function get_title() {
		return 'SC Call-to-Action Band';
	}
	public function get_icon() {
		return 'eicon-banner';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => 'Call to action', 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'title', array( 'label' => 'Heading', 'type' => Controls_Manager::TEXT, 'default' => 'Planning a project in Rwanda?' ) );
		$this->add_control( 'text', array( 'label' => 'Text', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Tell us about your venue. Our Kigali team will recommend, quote and deliver the right system.' ) );
		$this->add_control( 'btn_label', array( 'label' => 'Button label', 'type' => Controls_Manager::TEXT, 'default' => 'Request a Quote' ) );
		$this->add_control( 'btn_url', array( 'label' => 'Button link', 'type' => Controls_Manager::URL, 'default' => array( 'url' => '/request-a-quote/' ) ) );
		$this->add_control( 'image', array( 'label' => 'Background image (optional)', 'type' => Controls_Manager::MEDIA ) );
		$this->end_controls_section();
	}
	protected function render() {
		$st  = $this->get_settings_for_display();
		$bg  = ( ! empty( $st['image']['url'] ) ) ? $st['image']['url'] : '';
		$url = ( ! empty( $st['btn_url']['url'] ) ) ? $st['btn_url']['url'] : '/request-a-quote/';
		if ( 0 === strpos( $url, '/' ) ) {
			$url = home_url( $url );
		}
		$cls = $bg ? 'sc-cta-band sc-cta-band--photo' : 'sc-cta-band';
		$sty = $bg ? ' style="background-image:url(\'' . esc_url( $bg ) . '\');"' : '';
		echo '<div class="' . esc_attr( $cls ) . '"' . $sty . '><div class="sc-cta-band__inner">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $sty built from esc_url.
		if ( ! empty( $st['title'] ) ) {
			echo '<h2>' . esc_html( $st['title'] ) . '</h2>';
		}
		if ( ! empty( $st['text'] ) ) {
			echo '<p>' . esc_html( $st['text'] ) . '</p>';
		}
		if ( ! empty( $st['btn_label'] ) ) {
			echo '<a class="sc-btn sc-btn--primary" href="' . esc_url( $url ) . '">' . esc_html( $st['btn_label'] ) . '</a>';
		}
		echo '</div></div>';
	}
}

/* ---------------------------------------------------------------- */
/**
 * SC Page Design: drops a page's full designed layout (hero, sections, brand
 * walls...) into Elementor as one block, so editors can add, reorder and
 * combine Elementor sections around it instead of starting from a blank page.
 * Copy inside the design still comes from Sound Creations -> Settings.
 */
class SCRW_Widget_Page_Design extends SCRW_Widget_Base {
	public static function designs() {
		return array(
			'home'         => 'Homepage design',
			'about'        => 'About page design',
			'contact'      => 'Contact page design',
			'consultation' => 'Request a Consultation design',
			'fane'         => 'FANE Africa page design',
			'yamaha'       => 'Yamaha Rwanda page design',
		);
	}
	public static function part_for( $design ) {
		$map = array(
			'home'         => 'template-parts/designs/home',
			'about'        => 'template-parts/designs/about',
			'contact'      => 'template-parts/contact-page',
			'consultation' => 'template-parts/consult-page',
			'fane'         => 'template-parts/designs/fane',
			'yamaha'       => 'template-parts/designs/yamaha',
		);
		return isset( $map[ $design ] ) ? $map[ $design ] : '';
	}
	public function get_name() {
		return 'scrw_page_design';
	}
	public function get_title() {
		return 'SC Page Design';
	}
	public function get_icon() {
		return 'eicon-single-page';
	}
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => 'Page design', 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control(
			'design',
			array(
				'label'   => 'Design',
				'type'    => Controls_Manager::SELECT,
				'default' => 'about',
				'options' => self::designs(),
			)
		);
		$this->add_control( 'note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => 'Shows the full designed layout. Edit its text and photos in Sound Creations &rarr; Settings. Add your own Elementor sections above or below it, or remove this block to build the page from scratch.' ) );
		$this->end_controls_section();
	}
	protected function render() {
		$st   = $this->get_settings_for_display();
		$part = self::part_for( isset( $st['design'] ) ? (string) $st['design'] : '' );
		if ( '' === $part ) {
			return;
		}
		echo '<div class="scrw-page-design">';
		get_template_part( $part );
		echo '</div>';
	}
}

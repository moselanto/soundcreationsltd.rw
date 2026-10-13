<?php
/**
 * Branded wp-login.php screen (login, lost password, reset password, register).
 *
 * Replaces the WordPress logo and default grey look with the Sound Creations
 * identity: dark stage background, purple/red brand colours, branded logo that
 * links to the home page, and accessible focus states. Presentation only - no
 * change to how authentication works.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL of the login logo shipped with the plugin.
 *
 * @return string
 */
function sc_core_login_logo_url() {
	return plugins_url( 'assets/img/login-logo.png', SC_CORE_DIR . 'sound-creations-core.php' );
}

// Logo links to the site, not wordpress.org.
add_filter(
	'login_headerurl',
	function () {
		return home_url( '/' );
	}
);

// Accessible name for the logo link.
add_filter(
	'login_headertext',
	function () {
		return get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : 'Sound Creations Ltd';
	}
);

// The site runs in English only; hide the language switcher under the form.
add_filter( 'login_display_language_dropdown', '__return_false' );

// Branded styles.
add_action(
	'login_enqueue_scripts',
	function () {
		$logo = esc_url( sc_core_login_logo_url() );

		$css = '
		:root{
			--sc-login-bg:#0b0a10;
			--sc-login-card:#16141f;
			--sc-login-border:rgba(255,255,255,.08);
			--sc-login-text:#ecebf2;
			--sc-login-muted:#a7a3b8;
			--sc-login-purple:#624489;
			--sc-login-purple-dark:#46305f;
			--sc-login-red:#ba0b0b;
			--sc-login-red-dark:#920808;
		}
		body.login{
			background:
				radial-gradient(1200px 600px at 15% -10%, rgba(98,68,137,.35), transparent 60%),
				radial-gradient(900px 500px at 110% 110%, rgba(186,11,11,.22), transparent 60%),
				var(--sc-login-bg);
			color:var(--sc-login-text);
			font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
			min-height:100vh;
			display:flex;
			flex-direction:column;
			justify-content:center;
		}
		.login #login{width:360px;max-width:calc(100% - 32px);padding:32px 0 24px;margin:0 auto;}
		.login h1 a{
			background-image:url("' . $logo . '");
			background-size:contain;
			background-position:center;
			background-repeat:no-repeat;
			width:280px;
			height:102px;
			max-width:100%;
			margin:0 auto 28px;
		}
		.login form{
			background:var(--sc-login-card);
			border:1px solid var(--sc-login-border);
			border-top:3px solid var(--sc-login-red);
			border-radius:14px;
			box-shadow:0 24px 60px rgba(0,0,0,.45);
			padding:28px 26px 30px;
			margin-top:0;
		}
		.login form::before{
			content:"Staff sign in";
			display:block;
			font-size:18px;
			font-weight:600;
			letter-spacing:.01em;
			color:var(--sc-login-text);
			margin-bottom:18px;
		}
		.login.login-action-lostpassword form::before{content:"Reset your password";}
		.login.login-action-rp form::before,
		.login.login-action-resetpass form::before{content:"Choose a new password";}
		.login.login-action-register form::before{content:"Create an account";}
		.login label{color:var(--sc-login-muted);font-size:14px;font-weight:500;}
		.login form .input,
		.login input[type=text],
		.login input[type=password],
		.login input[type=email]{
			background:#0f0e15;
			border:1px solid rgba(255,255,255,.14);
			border-radius:10px;
			color:var(--sc-login-text);
			font-size:16px;
			padding:10px 12px;
			min-height:46px;
			box-shadow:none;
		}
		.login form .input:focus,
		.login input[type=text]:focus,
		.login input[type=password]:focus,
		.login input[type=email]:focus{
			border-color:var(--sc-login-purple);
			box-shadow:0 0 0 3px rgba(98,68,137,.45);
			outline:none;
		}
		.login .button.wp-hide-pw{color:var(--sc-login-muted);min-height:46px;}
		.login .button.wp-hide-pw:focus{border-color:var(--sc-login-purple);box-shadow:0 0 0 2px rgba(98,68,137,.6);}
		.login .button.wp-hide-pw .dashicons{color:var(--sc-login-muted);}
		.login input[type=checkbox]{
			background:#0f0e15;
			border-color:rgba(255,255,255,.25);
		}
		.login input[type=checkbox]:checked::before{filter:hue-rotate(60deg) saturate(2);}
		.login input[type=checkbox]:focus{border-color:var(--sc-login-purple);box-shadow:0 0 0 2px rgba(98,68,137,.6);}
		.login .forgetmenot label{color:var(--sc-login-muted);}
		.wp-core-ui .button-primary,
		.login .button-primary{
			background:var(--sc-login-red);
			border:0;
			border-radius:10px;
			box-shadow:none;
			text-shadow:none;
			color:#fff;
			font-weight:600;
			font-size:15px;
			min-height:44px;
			padding:0 22px;
			transition:background .15s ease;
		}
		.wp-core-ui .button-primary:hover,
		.login .button-primary:hover{background:var(--sc-login-red-dark);color:#fff;}
		.wp-core-ui .button-primary:focus,
		.login .button-primary:focus{
			background:var(--sc-login-red-dark);
			box-shadow:0 0 0 3px rgba(186,11,11,.45);
			outline:2px solid transparent;
		}
		.login .message,
		.login .notice,
		.login .success,
		.login #login_error{
			background:var(--sc-login-card);
			color:var(--sc-login-text);
			border:1px solid var(--sc-login-border);
			border-left:4px solid var(--sc-login-purple);
			border-radius:10px;
			box-shadow:none;
		}
		.login #login_error,
		.login .notice-error{border-left-color:var(--sc-login-red);}
		.login .success{border-left-color:#2e9e5b;}
		.login #login_error a,
		.login .message a{color:#c9b6ec;}
		.login #nav,
		.login #backtoblog{text-align:center;padding:0;margin:18px 0 0;}
		.login #nav a,
		.login #backtoblog a,
		.login .privacy-policy-page-link a{
			color:var(--sc-login-muted);
			text-decoration:none;
			font-size:13px;
		}
		.login #nav a:hover,
		.login #backtoblog a:hover,
		.login .privacy-policy-page-link a:hover{color:#fff;text-decoration:underline;}
		.login #nav a:focus,
		.login #backtoblog a:focus,
		.login h1 a:focus{
			color:#fff;
			outline:2px solid var(--sc-login-purple);
			outline-offset:3px;
			box-shadow:none;
			border-radius:4px;
		}
		.login .privacy-policy-page-link{margin:16px 0 0;}
		.sc-login-footer{
			text-align:center;
			color:#6f6b80;
			font-size:12px;
			line-height:1.6;
			padding:8px 16px 28px;
		}
		.sc-login-footer strong{color:var(--sc-login-muted);font-weight:600;}
		.login .pw-weak{color:var(--sc-login-text);}
		@media (max-width:480px){
			.login #login{padding-top:24px;}
			.login h1 a{width:220px;height:80px;margin-bottom:20px;}
			.login form{padding:22px 18px 24px;}
		}
		@media (prefers-reduced-motion:reduce){
			.login .button-primary{transition:none;}
		}
		';

		wp_add_inline_style( 'login', $css );
	}
);

// Small brand footer under the form.
add_action(
	'login_footer',
	function () {
		$year = gmdate( 'Y' );
		echo '<div class="sc-login-footer"><strong>Sound Creations Ltd</strong> &middot; Professional audio, lighting &amp; AV<br>&copy; ' . esc_html( $year ) . ' Sound Creations. Authorised staff only.</div>';
	}
);

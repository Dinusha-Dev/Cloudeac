<?php
/**
 * Fallback site header (used when no Elementor Theme Builder header exists).
 * The loader, progress bar and skip link come from the wp_body_open hook in inc/chrome.php.
 */
$quote_url = ki_quote_url();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="topbar"><div class="container"><?php echo ki_render_topbar(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div></div>

<header class="site-header" data-header>
	<div class="container header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> home">
			<img class="brand-light" src="<?php echo esc_url( ki_asset( 'img/logo-light-h.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="260" height="68">
			<img class="brand-dark" src="<?php echo esc_url( ki_asset( 'img/logo-h.png' ) ); ?>" alt="" width="260" height="68" aria-hidden="true">
		</a>
		<?php echo ki_render_nav(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div class="header-actions">
			<a class="btn btn-primary header-cta" href="<?php echo esc_url( $quote_url ); ?>" data-quote data-magnetic><span>Get a Quotation</span></a>
			<?php echo ki_render_nav_toggle(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</header>


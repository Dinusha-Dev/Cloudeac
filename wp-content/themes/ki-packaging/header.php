<?php
/**
 * Site header.
 */
$quote_url = ki_quote_url();
$phone     = ki_opt( 'phone' );
$email     = ki_opt( 'email' );
$socials   = function_exists( 'ki_socials' ) ? ki_socials() : array();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>

<div class="loader" aria-hidden="true"><img src="<?php echo esc_url( ki_asset( 'img/icon.png' ) ); ?>" alt="" width="72" height="72"><span class="loader-bar"></span></div>
<div class="scroll-progress" aria-hidden="true"><span></span></div>

<div class="topbar">
	<div class="container topbar-inner">
		<ul class="topbar-info">
			<li><?php ki_the_icon( 'map-pin', '', 15 ); ?><span><?php echo esc_html( ki_opt( 'address' ) ); ?></span></li>
			<?php if ( $phone ) : ?><li><?php ki_the_icon( 'phone', '', 15 ); ?><a href="<?php echo esc_url( ki_tel( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li><?php endif; ?>
			<?php if ( $email ) : ?><li><?php ki_the_icon( 'mail', '', 15 ); ?><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li><?php endif; ?>
		</ul>
		<div class="topbar-right">
			<?php if ( $socials ) : ?>
				<ul class="social-list">
					<?php foreach ( $socials as $key => $s ) : ?>
						<li><a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s['label'] ); ?>"><?php ki_the_icon( $key, '', 16 ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<span class="topbar-tag"><?php echo esc_html( ki_opt( 'tagline' ) ); ?></span>
			<?php endif; ?>
		</div>
	</div>
</div>

<header class="site-header" data-header>
	<div class="container header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> home">
			<img class="brand-light" src="<?php echo esc_url( ki_asset( 'img/logo-light-h.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="260" height="68">
			<img class="brand-dark" src="<?php echo esc_url( ki_asset( 'img/logo-h.png' ) ); ?>" alt="" width="260" height="68" aria-hidden="true">
		</a>

		<nav class="primary-nav" id="primary-nav" aria-label="Primary" data-nav>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav-list',
				'depth'          => 1,
				'fallback_cb'    => 'ki_primary_fallback',
			) );
			?>
			<div class="nav-extra">
				<a class="btn btn-primary btn-lg" href="<?php echo esc_url( $quote_url ); ?>" data-quote>Get a Quotation</a>
				<?php if ( $phone ) : ?><a class="nav-phone" href="<?php echo esc_url( ki_tel( $phone ) ); ?>"><?php ki_the_icon( 'phone', '', 18 ); ?> <?php echo esc_html( $phone ); ?></a><?php endif; ?>
				<p><?php echo esc_html( ki_opt( 'address' ) ); ?></p>
			</div>
		</nav>

		<div class="header-actions">
			<a class="btn btn-primary header-cta" href="<?php echo esc_url( $quote_url ); ?>" data-quote data-magnetic><span>Get a Quotation</span></a>
			<button class="nav-toggle" type="button" aria-controls="primary-nav" aria-expanded="false" aria-label="Open menu" data-nav-toggle>
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>

<main id="main" class="site-main">

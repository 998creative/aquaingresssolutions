<?php
/**
 * Site header.
 *
 * @package AquaIngressSolutions
 */

if (!defined('ABSPATH')) {
    exit;
}

$home_url = ais_home_section_url('home');
$about_url = ais_about_url();
$strata_url = ais_strata_url();
$services_url = ais_home_section_url('services');
$case_studies_url = ais_case_studies_url();
$contact_url = ais_contact_url('contact-form');
$is_case_studies = is_home() || is_singular('post');
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Skip to content</a>

<header class="site-header" id="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="<?php echo esc_url($home_url); ?>" aria-label="Aqua Ingress Solutions home">
      <img src="<?php echo esc_url(get_theme_file_uri('assets/images/Aqua-Ingress-Soluations-Logo-White.png')); ?>" alt="Aqua Ingress Solutions" width="220" height="57">
    </a>

    <button class="menu-toggle" id="menu-toggle" aria-expanded="false" aria-controls="site-nav" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>

    <nav class="site-nav" id="site-nav" aria-label="Primary">
      <a href="<?php echo esc_url($home_url); ?>"<?php echo is_front_page() ? ' aria-current="page"' : ''; ?>>Home</a>
      <div class="nav-item nav-dropdown<?php echo ais_is_about_section() ? ' is-current' : ''; ?>">
        <button class="nav-dropdown-toggle" type="button" aria-expanded="false" aria-controls="about-nav-menu">
          About
          <span class="dropdown-caret" aria-hidden="true"></span>
        </button>
        <div class="dropdown-menu" id="about-nav-menu">
          <a href="<?php echo esc_url($about_url); ?>"<?php echo (is_page_template('page-about.php') || is_page('about')) ? ' aria-current="page"' : ''; ?>>About Us</a>
          <a href="<?php echo esc_url($strata_url); ?>"<?php echo (is_page_template('page-strata-building-manager-support.php') || is_page('strata-building-manager-support')) ? ' aria-current="page"' : ''; ?>>Strata &amp; Building Manager Support</a>
        </div>
      </div>
      <a href="<?php echo esc_url($services_url); ?>">Services</a>
      <a href="<?php echo esc_url($case_studies_url); ?>"<?php echo $is_case_studies ? ' aria-current="page"' : ''; ?>>Case Studies</a>
      <a class="cta-link" href="<?php echo esc_url($contact_url); ?>"<?php echo (is_page_template('page-contact.php') || is_page('contact')) ? ' aria-current="page"' : ''; ?>>Book Consultation</a>
    </nav>
  </div>
</header>

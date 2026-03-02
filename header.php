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
$positive_url = ais_positive_waterproofing_url();
$negative_url = ais_negative_waterproofing_url();
$torch_on_url = ais_torch_on_membranes_url();
$injection_url = ais_injection_waterproofing_url();
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
      <div class="nav-item nav-dropdown<?php echo ais_is_services_section() ? ' is-current' : ''; ?>">
        <button class="nav-dropdown-toggle" type="button" aria-expanded="false" aria-controls="services-nav-menu">
          Services
          <span class="dropdown-caret" aria-hidden="true"></span>
        </button>
        <div class="dropdown-menu" id="services-nav-menu">
          <a href="<?php echo esc_url($positive_url); ?>"<?php echo (is_page_template('page-positive-waterproofing.php') || is_page('positive-waterproofing')) ? ' aria-current="page"' : ''; ?>>Positive Waterproofing</a>
          <a href="<?php echo esc_url($negative_url); ?>"<?php echo (is_page_template('page-negative-waterproofing.php') || is_page('negative-waterproofing')) ? ' aria-current="page"' : ''; ?>>Negative Waterproofing</a>
          <a href="<?php echo esc_url($torch_on_url); ?>"<?php echo (is_page_template('page-torch-on-membranes.php') || is_page('torch-on-membranes')) ? ' aria-current="page"' : ''; ?>>Torch-On Membranes</a>
          <a href="<?php echo esc_url($injection_url); ?>"<?php echo (is_page_template('page-injection-waterproofing.php') || is_page('injection-waterproofing')) ? ' aria-current="page"' : ''; ?>>Injection Waterproofing</a>
        </div>
      </div>
      <a href="<?php echo esc_url($case_studies_url); ?>"<?php echo $is_case_studies ? ' aria-current="page"' : ''; ?>>Case Studies</a>
      <a class="cta-link" href="<?php echo esc_url($contact_url); ?>"<?php echo (is_page_template('page-contact.php') || is_page('contact')) ? ' aria-current="page"' : ''; ?>>Book Consultation</a>
    </nav>
  </div>
</header>

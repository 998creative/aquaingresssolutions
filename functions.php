<?php
/**
 * Theme setup and utility functions.
 *
 * @package AquaIngressSolutions
 */

if (!defined('ABSPATH')) {
    exit;
}

function ais_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );
}
add_action('after_setup_theme', 'ais_theme_setup');

function ais_enqueue_assets()
{
    wp_enqueue_style(
        'ais-fonts',
        'https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap',
        array(),
        null
    );

    $style_path = get_theme_file_path('style.css');
    $style_ver = file_exists($style_path) ? (string) filemtime($style_path) : null;
    wp_enqueue_style(
        'ais-style',
        get_stylesheet_uri(),
        array('ais-fonts'),
        $style_ver
    );

    $script_path = get_theme_file_path('script.js');
    $script_ver = file_exists($script_path) ? (string) filemtime($script_path) : null;
    wp_enqueue_script(
        'ais-script',
        get_theme_file_uri('script.js'),
        array(),
        $script_ver,
        true
    );
}
add_action('wp_enqueue_scripts', 'ais_enqueue_assets');

function ais_get_page_url($slug, $fallback = '')
{
    $page = get_page_by_path($slug);
    if ($page instanceof WP_Post) {
        return get_permalink($page);
    }

    if ($fallback !== '') {
        return home_url($fallback);
    }

    return home_url('/' . trim($slug, '/') . '/');
}

function ais_add_hash($url, $hash = '')
{
    $hash = trim((string) $hash);
    if ($hash === '') {
        return $url;
    }

    $hash = ltrim($hash, '#');
    return rtrim($url, '/') . '/#' . $hash;
}

function ais_about_url()
{
    return ais_get_page_url('about');
}

function ais_contact_url($hash = '')
{
    return ais_add_hash(ais_get_page_url('contact'), $hash);
}

function ais_strata_url()
{
    return ais_get_page_url('strata-building-manager-support');
}

function ais_positive_waterproofing_url()
{
    return ais_get_page_url('positive-waterproofing');
}

function ais_negative_waterproofing_url()
{
    return ais_get_page_url('negative-waterproofing');
}

function ais_torch_on_membranes_url()
{
    return ais_get_page_url('torch-on-membranes');
}

function ais_injection_waterproofing_url()
{
    return ais_get_page_url('injection-waterproofing');
}

function ais_home_section_url($section = '')
{
    return ais_add_hash(home_url('/'), $section);
}

function ais_case_studies_url()
{
    $posts_page_id = (int) get_option('page_for_posts');
    if ($posts_page_id > 0) {
        return get_permalink($posts_page_id);
    }

    // Fallback to a standard posts query when a dedicated posts page isn't configured.
    return home_url('/?post_type=post');
}

function ais_is_about_section()
{
    return is_page_template('page-about.php')
        || is_page_template('page-strata-building-manager-support.php')
        || is_page('about')
        || is_page('strata-building-manager-support');
}

function ais_is_services_section()
{
    return is_page_template('page-positive-waterproofing.php')
        || is_page_template('page-negative-waterproofing.php')
        || is_page_template('page-torch-on-membranes.php')
        || is_page_template('page-injection-waterproofing.php')
        || is_page('positive-waterproofing')
        || is_page('negative-waterproofing')
        || is_page('torch-on-membranes')
        || is_page('injection-waterproofing');
}

function ais_body_classes($classes)
{
    if (is_page_template('page-about.php') || is_page('about')) {
        $classes[] = 'page-about';
    }

    if (is_page_template('page-contact.php') || is_page('contact')) {
        $classes[] = 'page-contact';
    }

    if (is_page_template('page-strata-building-manager-support.php') || is_page('strata-building-manager-support')) {
        $classes[] = 'page-strata';
    }

    if (is_page_template('page-positive-waterproofing.php') || is_page('positive-waterproofing')) {
        $classes[] = 'page-positive-waterproofing';
    }

    if (is_page_template('page-negative-waterproofing.php') || is_page('negative-waterproofing')) {
        $classes[] = 'page-negative-waterproofing';
    }

    if (is_page_template('page-torch-on-membranes.php') || is_page('torch-on-membranes')) {
        $classes[] = 'page-torch-on-membranes';
    }

    if (is_page_template('page-injection-waterproofing.php') || is_page('injection-waterproofing')) {
        $classes[] = 'page-injection-waterproofing';
    }

    return $classes;
}
add_filter('body_class', 'ais_body_classes');

function ais_get_case_study_location($post_id)
{
    $location = get_post_meta($post_id, 'ais_case_study_location', true);
    if (!empty($location)) {
        return $location;
    }

    $excerpt = get_the_excerpt($post_id);
    if (!empty($excerpt)) {
        return $excerpt;
    }

    return 'Queensland';
}

function ais_register_meta()
{
    register_post_meta(
        'post',
        'ais_case_study_location',
        array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => static function () {
                return current_user_can('edit_posts');
            },
        )
    );
}
add_action('init', 'ais_register_meta');

function ais_allowed_enquiry_types()
{
    return array(
        'Leak Investigation',
        'Positive Waterproofing',
        'Negative Waterproofing',
        'Torch-on Membrane',
        'Injection Waterproofing',
        'Other',
    );
}

function ais_contact_form_redirect_target($status = '')
{
    $url = ais_contact_url('contact-form');
    if ($status === '') {
        return $url;
    }

    return add_query_arg('contact_status', sanitize_key($status), $url);
}

function ais_get_contact_form_notice()
{
    $status = isset($_GET['contact_status']) ? sanitize_key((string) wp_unslash($_GET['contact_status'])) : '';
    if ($status === '') {
        return null;
    }

    if ($status === 'success') {
        return array(
            'type'    => 'success',
            'role'    => 'status',
            'message' => 'Thanks, your enquiry has been sent. We will get back to you shortly.',
        );
    }

    if ($status === 'invalid') {
        return array(
            'type'    => 'error',
            'role'    => 'alert',
            'message' => 'Please complete all required fields and provide a valid email address.',
        );
    }

    return array(
        'type'    => 'error',
        'role'    => 'alert',
        'message' => 'Sorry, there was a problem sending your enquiry. Please try again or call us directly.',
    );
}

function ais_handle_contact_form_submission()
{
    if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper((string) $_SERVER['REQUEST_METHOD']) !== 'POST') {
        wp_safe_redirect(ais_contact_form_redirect_target('error'));
        exit;
    }

    $nonce = isset($_POST['ais_contact_nonce']) ? (string) wp_unslash($_POST['ais_contact_nonce']) : '';
    if (!wp_verify_nonce($nonce, 'ais_submit_contact_form')) {
        wp_safe_redirect(ais_contact_form_redirect_target('error'));
        exit;
    }

    // Honeypot field should stay empty for human submissions.
    $honeypot = isset($_POST['company_website']) ? trim((string) wp_unslash($_POST['company_website'])) : '';
    if ($honeypot !== '') {
        wp_safe_redirect(ais_contact_form_redirect_target('success'));
        exit;
    }

    // Basic bot trap: reject submissions posted unrealistically fast.
    $submitted_ts = isset($_POST['ais_form_ts']) ? absint($_POST['ais_form_ts']) : 0;
    if ($submitted_ts > 0 && (time() - $submitted_ts) < 3) {
        wp_safe_redirect(ais_contact_form_redirect_target('success'));
        exit;
    }

    $name = isset($_POST['name']) ? trim(sanitize_text_field((string) wp_unslash($_POST['name']))) : '';
    $email = isset($_POST['email']) ? sanitize_email((string) wp_unslash($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? trim(sanitize_text_field((string) wp_unslash($_POST['phone']))) : '';
    $enquiry_type = isset($_POST['enquiry_type']) ? sanitize_text_field((string) wp_unslash($_POST['enquiry_type'])) : '';
    $message = isset($_POST['message']) ? trim(sanitize_textarea_field((string) wp_unslash($_POST['message']))) : '';

    $phone_digits = preg_replace('/\D+/', '', $phone);
    if (
        $name === ''
        || !is_email($email)
        || $phone === ''
        || strlen((string) $phone_digits) < 6
        || !in_array($enquiry_type, ais_allowed_enquiry_types(), true)
    ) {
        wp_safe_redirect(ais_contact_form_redirect_target('invalid'));
        exit;
    }

    $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
    $subject = sprintf('[%s] New Enquiry: %s', $site_name, $enquiry_type);

    $body = "New website enquiry received.\n\n";
    $body .= "Name: {$name}\n";
    $body .= "Email: {$email}\n";
    $body .= "Phone: {$phone}\n";
    $body .= "Type of Enquiry: {$enquiry_type}\n\n";
    $body .= "Message:\n";
    $body .= ($message !== '' ? $message : 'No message provided.');
    $body .= "\n\n";
    $body .= "Submitted from: " . home_url('/') . "\n";

    $recipient = apply_filters('ais_contact_form_recipient', get_option('admin_email'));
    $recipient = sanitize_email((string) $recipient);
    if (!is_email($recipient)) {
        $recipient = sanitize_email((string) get_option('admin_email'));
    }

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    $headers[] = sprintf('Reply-To: %s <%s>', $name, $email);

    $sent = false;
    if (is_email($recipient)) {
        $sent = wp_mail($recipient, $subject, $body, $headers);
    }

    wp_safe_redirect(ais_contact_form_redirect_target($sent ? 'success' : 'error'));
    exit;
}
add_action('admin_post_nopriv_ais_submit_contact_form', 'ais_handle_contact_form_submission');
add_action('admin_post_ais_submit_contact_form', 'ais_handle_contact_form_submission');

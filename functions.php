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

    return home_url('/case-studies/');
}

function ais_is_about_section()
{
    return is_page_template('page-about.php')
        || is_page_template('page-strata-building-manager-support.php')
        || is_page('about')
        || is_page('strata-building-manager-support');
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

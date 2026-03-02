<?php
/**
 * Plugin Name: Aqua Theme GitHub Updater
 * Description: Updates the Aqua Ingress Solutions theme directly from GitHub (no Elementor dependency).
 * Version: 1.0.0
 * Author: 998 Creative
 * License: GPL-2.0+
 *
 * @package AquaThemeGitHubUpdater
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Aqua_Theme_GitHub_Updater
{
    private const THEME_SLUG = 'aquaingresssolutions';
    private const REPO_OWNER = '998creative';
    private const REPO_NAME = 'aquaingresssolutions';
    private const REPO_BRANCH = 'main';
    private const OPTION_INSTALLED_SHA = 'ais_theme_installed_sha';
    private const TRANSIENT_REMOTE_META = 'ais_theme_github_remote_meta';
    private const CACHE_TTL = 300;

    private static $instance = null;

    public static function init(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_filter('pre_set_site_transient_update_themes', array($this, 'inject_theme_update'));
        add_filter('themes_api', array($this, 'inject_theme_info'), 10, 3);
        add_filter('upgrader_source_selection', array($this, 'normalize_extracted_folder'), 10, 4);
        add_filter('auto_update_theme', array($this, 'enable_auto_updates_for_theme'), 10, 2);
        add_action('upgrader_process_complete', array($this, 'store_installed_sha_after_upgrade'), 10, 2);
        add_action('admin_init', array($this, 'maybe_seed_installed_sha'));
    }

    public static function on_activation(): void
    {
        $updater = self::init();
        $remote = $updater->get_remote_meta(true);
        if (!empty($remote['sha']) && empty(get_option(self::OPTION_INSTALLED_SHA))) {
            update_option(self::OPTION_INSTALLED_SHA, sanitize_text_field($remote['sha']), false);
        }
    }

    public function maybe_seed_installed_sha(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (!empty(get_option(self::OPTION_INSTALLED_SHA))) {
            return;
        }

        $remote = $this->get_remote_meta();
        if (!empty($remote['sha'])) {
            update_option(self::OPTION_INSTALLED_SHA, sanitize_text_field($remote['sha']), false);
        }
    }

    public function inject_theme_update($transient)
    {
        if (!is_object($transient) || empty($transient->checked[self::THEME_SLUG])) {
            return $transient;
        }

        $remote = $this->get_remote_meta();
        if (empty($remote['sha']) || empty($remote['version']) || empty($remote['package'])) {
            return $transient;
        }

        $installed_sha = (string) get_option(self::OPTION_INSTALLED_SHA, '');
        if ($installed_sha !== '' && hash_equals($installed_sha, $remote['sha'])) {
            return $transient;
        }

        $transient->response[self::THEME_SLUG] = array(
            'theme'       => self::THEME_SLUG,
            'new_version' => $remote['version'],
            'url'         => $this->repository_url(),
            'package'     => $remote['package'],
            'requires'    => '6.0',
            'tested'      => get_bloginfo('version'),
        );

        return $transient;
    }

    public function inject_theme_info($false, $action, $args)
    {
        if ('theme_information' !== $action || empty($args->slug) || self::THEME_SLUG !== $args->slug) {
            return $false;
        }

        $remote = $this->get_remote_meta();
        if (empty($remote['version'])) {
            return $false;
        }

        return (object) array(
            'name'          => 'Aqua Ingress Solutions',
            'slug'          => self::THEME_SLUG,
            'version'       => $remote['version'],
            'author'        => '<a href="https://github.com/998creative">998 Creative</a>',
            'homepage'      => $this->repository_url(),
            'requires'      => '6.0',
            'tested'        => get_bloginfo('version'),
            'download_link' => $remote['package'],
            'sections'      => array(
                'description' => 'Bespoke Aqua Ingress Solutions theme delivered from GitHub.',
                'changelog'   => 'Latest commit: ' . esc_html(substr($remote['sha'], 0, 7)),
            ),
        );
    }

    public function normalize_extracted_folder($source, $remote_source, $upgrader, $hook_extra)
    {
        if (
            !is_array($hook_extra)
            || empty($hook_extra['type'])
            || 'theme' !== $hook_extra['type']
            || empty($hook_extra['theme'])
            || self::THEME_SLUG !== $hook_extra['theme']
        ) {
            return $source;
        }

        $desired = trailingslashit($remote_source) . self::THEME_SLUG;
        if ($source === $desired) {
            return $source;
        }

        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }

        if (!$wp_filesystem || !$wp_filesystem->move($source, $desired, true)) {
            return new WP_Error(
                'ais_theme_update_source_move_failed',
                __('Could not prepare the downloaded theme package folder.', 'aqua-theme-github-updater')
            );
        }

        return $desired;
    }

    public function enable_auto_updates_for_theme($update, $item)
    {
        if (!is_object($item) || empty($item->theme)) {
            return $update;
        }

        if (self::THEME_SLUG === $item->theme) {
            return true;
        }

        return $update;
    }

    public function store_installed_sha_after_upgrade($upgrader, $hook_extra): void
    {
        if (
            !is_array($hook_extra)
            || empty($hook_extra['type'])
            || empty($hook_extra['action'])
            || 'theme' !== $hook_extra['type']
            || 'update' !== $hook_extra['action']
            || empty($hook_extra['themes'])
            || !is_array($hook_extra['themes'])
            || !in_array(self::THEME_SLUG, $hook_extra['themes'], true)
        ) {
            return;
        }

        $remote = $this->get_remote_meta(true);
        if (!empty($remote['sha'])) {
            update_option(self::OPTION_INSTALLED_SHA, sanitize_text_field($remote['sha']), false);
        }
    }

    private function get_remote_meta(bool $force = false): array
    {
        if (!$force) {
            $cached = get_transient(self::TRANSIENT_REMOTE_META);
            if (is_array($cached) && !empty($cached['sha'])) {
                return $cached;
            }
        }

        $request = wp_remote_get(
            $this->commit_api_url(),
            array(
                'timeout' => 15,
                'headers' => array(
                    'Accept'     => 'application/vnd.github+json',
                    'User-Agent' => $this->github_user_agent(),
                ),
            )
        );

        if (is_wp_error($request) || 200 !== wp_remote_retrieve_response_code($request)) {
            return array();
        }

        $body = json_decode((string) wp_remote_retrieve_body($request), true);
        if (!is_array($body) || empty($body['sha']) || empty($body['commit']['committer']['date'])) {
            return array();
        }

        $sha = sanitize_text_field($body['sha']);
        $date = sanitize_text_field($body['commit']['committer']['date']);
        $timestamp = strtotime($date);
        if (false === $timestamp) {
            $timestamp = time();
        }

        $meta = array(
            'sha'     => $sha,
            'version' => gmdate('Y.m.d.His', $timestamp),
            'package' => $this->zip_download_url(),
        );

        set_transient(self::TRANSIENT_REMOTE_META, $meta, self::CACHE_TTL);
        return $meta;
    }

    private function github_user_agent(): string
    {
        return 'WordPress/' . get_bloginfo('version') . '; ' . home_url('/');
    }

    private function commit_api_url(): string
    {
        return sprintf(
            'https://api.github.com/repos/%1$s/%2$s/commits/%3$s',
            self::REPO_OWNER,
            self::REPO_NAME,
            self::REPO_BRANCH
        );
    }

    private function zip_download_url(): string
    {
        return sprintf(
            'https://github.com/%1$s/%2$s/archive/refs/heads/%3$s.zip',
            self::REPO_OWNER,
            self::REPO_NAME,
            self::REPO_BRANCH
        );
    }

    private function repository_url(): string
    {
        return sprintf(
            'https://github.com/%1$s/%2$s',
            self::REPO_OWNER,
            self::REPO_NAME
        );
    }
}

register_activation_hook(__FILE__, array('Aqua_Theme_GitHub_Updater', 'on_activation'));
Aqua_Theme_GitHub_Updater::init();

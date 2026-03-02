<?php
/**
 * Plugin Name: PP Theme Deploy
 * Description: Receives a theme zip from GitHub Actions and deploys it to wp-content/themes.
 * Version: 1.0.0
 * Author: 998 Creative
 * License: GPL-2.0+
 *
 * @package PPThemeDeploy
 */

if (!defined('ABSPATH')) {
    exit;
}

final class PP_Theme_Deploy
{
    private const DEFAULT_THEME_SLUG = 'aquaingresssolutions';
    private const OPTION_DEPLOY_TOKEN = 'pp_theme_deploy_token';
    private const OPTION_LAST_DEPLOY = 'pp_theme_deploy_last_deploy';
    private const OPTION_LAST_COMMIT = 'pp_theme_deploy_last_commit';

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
        add_action('rest_api_init', array($this, 'register_routes'));
        add_action('admin_menu', array($this, 'register_settings_page'));
        add_action('admin_post_pp_theme_deploy_regenerate_token', array($this, 'handle_regenerate_token'));
    }

    public static function on_activation(): void
    {
        if (!defined('PP_THEME_DEPLOY_TOKEN') || PP_THEME_DEPLOY_TOKEN === '') {
            if (empty(get_option(self::OPTION_DEPLOY_TOKEN))) {
                update_option(self::OPTION_DEPLOY_TOKEN, wp_generate_password(64, false, false), false);
            }
        }
    }

    public function register_routes(): void
    {
        register_rest_route(
            'pp-theme-deploy/v1',
            '/deploy',
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'deploy_theme'),
                'permission_callback' => array($this, 'authorize_request'),
            )
        );
    }

    public function authorize_request(WP_REST_Request $request)
    {
        $provided = (string) $request->get_header('x-pp-deploy-token');
        $expected = $this->get_deploy_token();

        if ($provided === '' || $expected === '' || !hash_equals($expected, $provided)) {
            return new WP_Error(
                'pp_theme_deploy_forbidden',
                __('Invalid deploy token.', 'pp-theme-deploy'),
                array('status' => 403)
            );
        }

        return true;
    }

    public function deploy_theme(WP_REST_Request $request): WP_REST_Response
    {
        if (empty($_FILES['theme_zip']) || !is_array($_FILES['theme_zip'])) {
            return new WP_REST_Response(
                array(
                    'ok'      => false,
                    'message' => 'Missing theme_zip file upload.',
                ),
                400
            );
        }

        $upload = $_FILES['theme_zip'];
        if (!isset($upload['error']) || (int) $upload['error'] !== UPLOAD_ERR_OK || empty($upload['tmp_name'])) {
            return new WP_REST_Response(
                array(
                    'ok'      => false,
                    'message' => 'Theme zip upload failed.',
                ),
                400
            );
        }

        $theme_slug = sanitize_key((string) $request->get_param('theme_slug'));
        if ($theme_slug === '') {
            $theme_slug = self::DEFAULT_THEME_SLUG;
        }

        $commit_sha = sanitize_text_field((string) $request->get_param('commit_sha'));
        $result = $this->replace_theme_from_zip((string) $upload['tmp_name'], $theme_slug);

        if (is_wp_error($result)) {
            return new WP_REST_Response(
                array(
                    'ok'      => false,
                    'message' => $result->get_error_message(),
                ),
                500
            );
        }

        update_option(self::OPTION_LAST_DEPLOY, gmdate('c'), false);
        if ($commit_sha !== '') {
            update_option(self::OPTION_LAST_COMMIT, $commit_sha, false);
        }

        return new WP_REST_Response(
            array(
                'ok'         => true,
                'theme_slug' => $theme_slug,
                'commit_sha' => $commit_sha,
                'message'    => 'Theme deployed successfully.',
            ),
            200
        );
    }

    private function replace_theme_from_zip(string $uploaded_tmp_file, string $theme_slug)
    {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/misc.php';

        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            WP_Filesystem();
        }

        if (empty($wp_filesystem)) {
            return new WP_Error('pp_theme_deploy_fs', __('Could not initialize filesystem.', 'pp-theme-deploy'));
        }

        $zip_path = wp_tempnam('pp-theme-deploy.zip');
        if (!$zip_path || !copy($uploaded_tmp_file, $zip_path)) {
            return new WP_Error('pp_theme_deploy_zip_copy', __('Could not prepare uploaded zip file.', 'pp-theme-deploy'));
        }

        $extract_root = trailingslashit(get_temp_dir()) . 'pp-theme-deploy-' . wp_generate_password(12, false, false);
        wp_mkdir_p($extract_root);

        $unzip = unzip_file($zip_path, $extract_root);
        @unlink($zip_path);
        if (is_wp_error($unzip)) {
            $wp_filesystem->delete($extract_root, true);
            return $unzip;
        }

        $source_path = $this->resolve_source_path($extract_root);
        if ($source_path === '') {
            $wp_filesystem->delete($extract_root, true);
            return new WP_Error('pp_theme_deploy_source', __('Could not resolve extracted theme source.', 'pp-theme-deploy'));
        }

        $themes_dir = trailingslashit(WP_CONTENT_DIR) . 'themes/';
        $live_path = $themes_dir . $theme_slug;
        $incoming_path = $themes_dir . $theme_slug . '-incoming';
        $backup_path = $themes_dir . $theme_slug . '-backup';

        $wp_filesystem->delete($incoming_path, true);
        $wp_filesystem->delete($backup_path, true);

        $copied = copy_dir($source_path, $incoming_path);
        $wp_filesystem->delete($extract_root, true);
        if (is_wp_error($copied)) {
            return $copied;
        }

        if ($wp_filesystem->exists($live_path)) {
            if (!$wp_filesystem->move($live_path, $backup_path, true)) {
                $wp_filesystem->delete($incoming_path, true);
                return new WP_Error('pp_theme_deploy_backup', __('Could not move current theme to backup.', 'pp-theme-deploy'));
            }
        }

        if (!$wp_filesystem->move($incoming_path, $live_path, true)) {
            if ($wp_filesystem->exists($backup_path)) {
                $wp_filesystem->move($backup_path, $live_path, true);
            }
            return new WP_Error('pp_theme_deploy_promote', __('Could not move deployed theme into place.', 'pp-theme-deploy'));
        }

        $wp_filesystem->delete($backup_path, true);
        wp_clean_themes_cache(true);
        delete_site_transient('update_themes');

        return true;
    }

    private function resolve_source_path(string $extract_root): string
    {
        $style_at_root = trailingslashit($extract_root) . 'style.css';
        if (is_file($style_at_root)) {
            return $extract_root;
        }

        $entries = glob(trailingslashit($extract_root) . '*', GLOB_ONLYDIR);
        if (!is_array($entries) || count($entries) !== 1) {
            return '';
        }

        $candidate = (string) $entries[0];
        if (!is_file(trailingslashit($candidate) . 'style.css')) {
            return '';
        }

        return $candidate;
    }

    private function get_deploy_token(): string
    {
        if (defined('PP_THEME_DEPLOY_TOKEN') && PP_THEME_DEPLOY_TOKEN !== '') {
            return (string) PP_THEME_DEPLOY_TOKEN;
        }

        $token = (string) get_option(self::OPTION_DEPLOY_TOKEN, '');
        if ($token === '') {
            $token = wp_generate_password(64, false, false);
            update_option(self::OPTION_DEPLOY_TOKEN, $token, false);
        }

        return $token;
    }

    public function register_settings_page(): void
    {
        add_options_page(
            __('PP Theme Deploy', 'pp-theme-deploy'),
            __('PP Theme Deploy', 'pp-theme-deploy'),
            'manage_options',
            'pp-theme-deploy',
            array($this, 'render_settings_page')
        );
    }

    public function render_settings_page(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $endpoint = rest_url('pp-theme-deploy/v1/deploy');
        $token = $this->get_deploy_token();
        $last_deploy = (string) get_option(self::OPTION_LAST_DEPLOY, '');
        $last_commit = (string) get_option(self::OPTION_LAST_COMMIT, '');
        ?>
        <div class="wrap">
          <h1>PP Theme Deploy</h1>
          <p>Use this endpoint + token in GitHub Actions secrets.</p>
          <table class="form-table" role="presentation">
            <tr>
              <th scope="row">Deploy Endpoint</th>
              <td><code><?php echo esc_html($endpoint); ?></code></td>
            </tr>
            <tr>
              <th scope="row">Deploy Token</th>
              <td><code><?php echo esc_html($token); ?></code></td>
            </tr>
            <tr>
              <th scope="row">Last Deploy (UTC)</th>
              <td><code><?php echo $last_deploy !== '' ? esc_html($last_deploy) : 'Never'; ?></code></td>
            </tr>
            <tr>
              <th scope="row">Last Commit</th>
              <td><code><?php echo $last_commit !== '' ? esc_html($last_commit) : 'N/A'; ?></code></td>
            </tr>
          </table>

          <?php if (!defined('PP_THEME_DEPLOY_TOKEN')) : ?>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
              <?php wp_nonce_field('pp_theme_deploy_regenerate_token'); ?>
              <input type="hidden" name="action" value="pp_theme_deploy_regenerate_token">
              <?php submit_button(__('Regenerate Token', 'pp-theme-deploy'), 'secondary'); ?>
            </form>
          <?php else : ?>
            <p><em>Token is defined in wp-config.php via <code>PP_THEME_DEPLOY_TOKEN</code>.</em></p>
          <?php endif; ?>
        </div>
        <?php
    }

    public function handle_regenerate_token(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('Insufficient permissions.', 'pp-theme-deploy'));
        }

        check_admin_referer('pp_theme_deploy_regenerate_token');
        update_option(self::OPTION_DEPLOY_TOKEN, wp_generate_password(64, false, false), false);

        wp_safe_redirect(admin_url('options-general.php?page=pp-theme-deploy'));
        exit;
    }
}

register_activation_hook(__FILE__, array('PP_Theme_Deploy', 'on_activation'));
PP_Theme_Deploy::init();

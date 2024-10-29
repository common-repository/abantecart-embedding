<?php
/**
 * Plugin Name:   AbanteCart Embedding
 * Plugin URI:    https://abantecart.atlassian.net/wiki/spaces/AD/pages/2375221441/WordPress+Embed+Plugin
 * Description:   Easily add ecommerce and a smooth shopping cart to your WordPress site. The most powerful way to sell physical products, digital items and services.
 * Version:       1.0.2
 * Author:        AbanteCart Team
 * Author URI:    https://www.abantecart.com
 * License:       GPL-3.0
 * License URI:   https://github.com/abantecart/abantecart-embed/blob/main/LICENSE
*/

/**
 * Exit if accessed directly
 * @since 0.0.1
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main class
 */
final class Abantecart
{
    public $version     = '1.0.2';
    public $dir         = '';
    public $url         = '';
    public $name = 'Abantecart';
    public $alias = 'Abantecart';
    public $lang = 'abantecart-embed';
    public $home = 'https://www.abantecart.com/';
    public $embeds = 'https://abantecart.atlassian.net/wiki/spaces/AD/pages/2375221441/WordPress+Embed+Plugin';
    public $developer = true;

    /**
     * The single instance of the class.
     */
    protected static $instance = null;

    /**
     * Main Instance.
     * Ensures only one instance is loaded or can be loaded.
     * @return Abantecart
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->dir = plugin_dir_path(__FILE__);
        $this->url = plugin_dir_url(__FILE__);
        $this->init_hooks();

        do_action($this->alias . '_loaded');
    }

    private function init_hooks()
    {
        register_activation_hook(__FILE__, array( $this, 'activation_hook' ));
        register_deactivation_hook(__FILE__, array( $this, 'deactivation_hook' ));

        add_action('plugins_loaded', array( $this, 'plugin_loaded' ), 9);
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10);
        add_action('admin_menu', array( $this, 'admin_menu' ));
        add_action('admin_notices', array( $this, 'admin_notices' ));
        add_action('admin_init', array( $this, 'init_settings' ));

        add_filter('plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2);
        add_action('init', array( $this, 'load_plugin_textdomain' ));
    }

    /**
     * Save plugin version on activation
     *
     * This hook performs an instant redirect after it fires, meaning it's impossible to use `add_action` or
     * `add_filter` type calls until after it has occurred. Our workaround is to store temporary data using the
     * Transients API to check-for-and-delete later.
     *
     * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
     * @see https://codex.wordpress.org/Transients_API
     * @since 0.0.1
     */
    public function activation_hook()
    {
        add_option($this->alias . '_version', $this->version);
        set_transient($this->alias . '_did_activate', true, 5);
    }

    /**
     * Delete plugin data on activation
     * @since 1.9.0
     */
    public function deactivation_hook()
    {
        delete_option($this->alias . '_version');
    }

    /**
     * Initializes the plugin and its features
     * @since 0.0.1
     */
    public function plugin_loaded()
    {
        /**
         * Register an embed handler
         */
        $prefix = abantecart()->alias;
        $options = (array)get_option($prefix.'_settings');
        $options['abantecart_store_url'] =
            isset($options['abantecart_store_url']) ? (array)$options['abantecart_store_url'] : array();

        foreach ($options['abantecart_store_url'] as $k => $url) {
            $url = rtrim($url, '/');
            if (!$url) {
                continue;
            }
            wp_embed_register_handler(
                'abantecart-embed-'.$k,
                '#^'.$url.'/.*$#i',
                'wpdocs_embed_handler_abantecart'
            );

            wp_oembed_add_provider(
                '#'.$url.'/.*#i',
                $url.'/?rt=r/embed/get/oembed',
                true
            );
        }
    }

    /**
     * Enqueue scripts and styles
     * @since 1.7.2
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style($this->alias.'-main', plugins_url('assets/css/main.css', __FILE__), null, $this->version);

        wp_register_script(
            $this->alias.'-settings',
            plugins_url('assets/js/blocks.js', __FILE__),
            array('jquery'),
            $this->version,
            true
        );
        wp_enqueue_script($this->alias.'-settings');
    }

    public function admin_menu()
    {
        add_menu_page(
            __('AbanteCart Embedding settings', $this->lang),
            $this->name,
            'manage_options',
            $this->alias,
            array($this, 'settings_page'),
            plugins_url('assets/img/icon.png', __FILE__),
            2
        );

        add_submenu_page(
            $this->alias,
            __('AbanteCart Embedding settings', $this->lang),
            __('Settings', $this->lang),
            'manage_options',
            $this->alias
        );

        add_submenu_page(
            $this->alias,
            __('AbanteCart Embedding Help', $this->lang),
            __('Help', $this->lang),
            'manage_options',
            $this->alias . '_help',
            array( $this, 'help_page' )
        );
    }

    public function settings_page()
    {
        include($this->dir .  '/views/settings.php');
    }

    public function help_page()
    {
        include($this->dir .  '/views/help.php');
    }

    /**
     * Prompt users to complete store setup on activation
     * @since 2.1.0
     */
    public function admin_notices()
    {
        $current_screen = get_current_screen();

        if (in_array($current_screen->base, array('dashboard', 'plugins'))) {
            ?>
            <div class="notice notice-success notice-large is-dismissible">
                <h3 class="notice-title"><?php _e('Awesome! Your new AbanteCart plugin is now active.',
                        'abantecart-embed'); ?></h3>
                <p><strong><?php _e('Take a few simple steps to complete your store setup.',
                            'abantecart-embed'); ?></strong></p>
                <p>
                    <a class="button button-primary" href="<?php echo admin_url('admin.php?page='
                        .$this->alias); ?>"><?php _e('Setup Abantecart Embed', 'abantecart-embed'); ?></a>
                </p>
            </div>
            <?php
        }
    }

    // Register our settings. Add the settings section, and settings fields
    public function init_settings()
    {
        register_setting($this->alias . '_settings', $this->alias . '_settings', array($this, 'settings_validate' ));

        if (isset($_GET['developer']) && $_GET['developer'] == 'true') {
            setcookie($this->alias . '_developer', 'true', time() + 315360000);
        }

        if ((isset($_GET['developer']) && $_GET['developer'] == 'true') || (isset($_COOKIE[$this->alias . '_developer']) && $_COOKIE[$this->alias . '_developer'] == 'true')) {
            $this->developer = true;
        }

        $this->redirect();
    }

    public function settings_validate($input)
    {
        $oe = new WP_oEmbed();
        foreach ($input['abantecart_store_url'] as $k => &$url) {
            $url = sanitize_text_field($url);
            $url = rtrim($url, '/');
            if (!$url) {
                continue;
            }
            $oe->providers['#'.$url.'/.*#i'] = array(
                $url.'/?rt=r/embed/get/oembed',
                true,
            );

            $oe_url = $url.'/?rt=r/embed/get/oembed';
            $result = $oe->get_data($oe_url);
            if ($result === false) {
                // add settings saved message with the class of "updated"*/
                add_settings_error(
                    $this->alias.'_settings', // Slug title of setting
                    $this->alias.'_settings', // Slug-name , Used as part of 'id' attribute in HTML output.
                    __(
                        'Error. Incorrect Store Url '
                        .$url.'. Please <a href="'.$url
                        .'/?rt=r/embed/get/oembed">check out response of url</a>. URL must be accessible and safe(no IPs, no local hosts, only real domain).',
                        $this->alias
                    ),
                    // message text, will be shown inside styled <div> and <p> tags
                    'error' // Message type, controls HTML class. Accepts 'error' or 'updated'.
                );
            }
        }
        return $input;
    }

    /**
     * Redirect user to settings page on plugin activation
     * @since 2.1.0
     */
    public function redirect()
    {
        if (!get_transient($this->alias . '_did_activate')) {
            return;
        }

        // Delete transient so `redirect` is only called once (after activation)
        delete_transient($this->alias . '_did_activate');

        wp_redirect(admin_url('admin.php?page=' . $this->alias));
        exit;
    }

    /**
     * Return default arguments for widgets or shortcodes
     * TODO: We should get these from the user defaults on AbanteCart
     *
     * @since 1.5.1
     */
    public function default_args()
    {
        $defaults = array(
            'title'            => esc_attr__('AbanteCart Widget', 'abantecart'),
            'link'             => '',
            'store_link'       => '',
            'type'             => '',
            'interact'         => 'modal',
            'style'            => 'price-right',
            'action'           => 'add-to-cart',
            'width'            => '320',
            'auto_width'       => 'true',
            'fluid_width'      => 'false',
            'button_text'      => __('Add to cart', 'abantecart'),
            'text_color'       => $this->colors()['white'],
            'background_color' => $this->colors()['primary'],
            'link_color'       => $this->colors()['primary'],
            'chbg_color'       => $this->colors()['primary'],
            'chtx_color'       => $this->colors()['white'],
            'tab_active'       => array(0 => true, 1 => false, 2 => false),
            'show_logos'       => '',
            'show_description' => 'true',
            'intro_text'       => '',
            'outro_text'       => '',
        );

        return $defaults;
    }

    /**
     * Return common colors
     * @since 2.0.0
     */
    public function colors()
    {
        return array(
            'primary' => '#8f47e6',
            'white'   => '#fff',
        );
    }

    /**
     * Show row meta on the plugin screen.
     */
    public function plugin_action_links($links, $file)
    {
        $settings_link = '<a href="' . admin_url('admin.php?page=' . $this->alias) . '">' . esc_html__('Settings', $this->lang) . '</a>';

        if ($file == $this->alias . '-embed/index.php') {
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    /**
     * Load Localisation files.
     * @since  1.0.0
     */
    public function load_plugin_textdomain()
    {
        $locale = apply_filters('plugin_locale', get_locale(), $this->lang);

        load_textdomain($this->lang, WP_LANG_DIR . '/' . $this->lang . '-' . $locale . '.mo');
        load_plugin_textdomain($this->lang, false, plugin_basename(dirname(__FILE__)) . '/languages');
    }

}

/**
 * Start the plugin
 * @return Abantecart
 */
function abantecart()
{
    return Abantecart::instance();
}
abantecart();

/*
 * Pretty print helper function for quick debugging
 */
if (!function_exists('pp')) {
    function pp($array)
    {
        echo '<pre style="white-space:pre-wrap;">';
        print_r($array);
        echo '</pre>'."\n";
    }
}

function wpdocs_embed_handler_abantecart($matches, $attr, $url, $rawattr)
{
    $query = parse_url(html_entity_decode($url), PHP_URL_QUERY);
    parse_str($query, $params);
    $h = isset($params['height']) && (int)$params['height'] ? (int)$params['height'] : 450;
    return '<iframe height="'.$h.'" width="100%" src="'.$url
        .'" frameborder="0" scrolling="auto" marginwidth="0" marginheight="0"></iframe>';
}

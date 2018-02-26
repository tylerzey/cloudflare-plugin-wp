<?php

/**
 * Plugin Name: Clear Cache
 * Plugin URI: http://happycatplugins.com/plugins/clear-cache/
 * Version: 1.0
 * Author: Sophia & Tyler Zey
 * Author URI: http://happycatplugins.com
 * Description: This plugin automatically clears your Cloudflare cache after you update any WP OPTIONS settings. This means when you change a theme setting, we send a ping to Cloudflare to automatically clear your cache.
 * License: GPL2
 */

/*  Copyright 2017 Happy Cat Plugins

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly
$cloudflare = new CloudflareClearCache();

class CloudflareClearCache
{
    /**
     * Constructor
     */
    public function __construct()
    {
		// Plugin Details
        $this->plugin = new stdClass;
        $this->plugin->name = 'cache-clear-on-update'; // Plugin Folder
        $this->plugin->displayName = 'Clear Cloudflare Cache On Save'; // Plugin Name
        $this->plugin->version = '1.0';
        $this->plugin->folder = plugin_dir_path(__FILE__);
        $this->plugin->url = plugin_dir_url(__FILE__);

		// Hooks
        add_action('admin_init', array(&$this, 'registerSettings'));
        add_action('admin_menu', array(&$this, 'adminPanelsAndMetaBoxes'));

        $options = get_option($this->plugin->name);
        if (isset($options['auth']) && $options['auth'] == true) {
            add_action('admin_init', array(&$this, 'myplugin_init'));
        }

        function easy_tags_load_styles_scripts($hook)
        {
            global $easy_tags_add_page;
            if ($hook != $easy_tags_add_page)
                return;
            wp_enqueue_style('easy-tags-main-style', plugins_url('/css/main.css', __FILE__));
            wp_enqueue_style('easy-tags-bootstrap', plugins_url('/css/bootstrap.min.css', __FILE__));
            wp_enqueue_script('easy-tags-main-bt-popper', plugins_url('/js/popper.min.js', __FILE__));
            wp_enqueue_script('easy-tags-main-bt-js', plugins_url('/js/bootstrap.min.js', __FILE__));
				//wp_enqueue_script( 'easy-tags-main-bt-jq',plugins_url( '/js/jquery-3.2.1.slim.min.js', __FILE__ ) );

        }
        add_action('admin_enqueue_scripts', 'easy_tags_load_styles_scripts');
    }

    function myplugin_init()
    {
        $alloptions = wp_load_alloptions();
        foreach ($alloptions as $key => $value) {
            add_filter('pre_update_option_' . $key, function ($new_value, $old_value) {
                include('_cache.php');
                return $new_value;
            }, 10, 2);
        }
    }

    /**
     * Register the plugin settings panel
     */
    function adminPanelsAndMetaBoxes()
    {
        global $easy_tags_add_page;
        $easy_tags_add_page = add_menu_page($this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array(&$this, 'adminPanel'), $icon_url = 'dashicons-editor-table', $position = 21);
    }

    /**
     * Register Settings
     */
    function registerSettings()
    {
        register_setting($this->plugin->name, 'cloudflare_email', 'trim');
        register_setting($this->plugin->name, 'cloudflare_api_key', 'trim');
        register_setting($this->plugin->name, 'zone_id', 'trim');
    }

    /**
     * Output the Administration Panel
     * Save POSTed data from the Administration Panel into a WordPress option
     */
    function adminPanel()
    {
		// only admin user can access this page
        if (!current_user_can('administrator')) {
            echo '<p>' . __('Sorry, you are not allowed to access this page.', $this->plugin->name) . '</p>';
            return;
        }

    	// Save Settings
        if (isset($_REQUEST['submit'])) {
        	// Check nonce
            if (!isset($_REQUEST[$this->plugin->name . '_nonce'])) {
	        	// Missing nonce
                $this->errorMessage = __('nonce field is missing. Settings NOT saved.', $this->plugin->name);
            } elseif (!wp_verify_nonce($_REQUEST[$this->plugin->name . '_nonce'], $this->plugin->name)) {
	        	// Invalid nonce
                $this->errorMessage = __('Invalid nonce specified. Settings NOT saved.', $this->plugin->name);
            } else {
                // Save
                $updateArray['cloudflare_email'] = empty($_REQUEST['cloudflare_email']) ? "" : $_REQUEST['cloudflare_email'];
                $updateArray['cloudflare_api_key'] = empty($_REQUEST['cloudflare_api_key']) ? "" : $_REQUEST['cloudflare_api_key'];
                $updateArray['zone_id'] = empty($_REQUEST['zone_id']) ? "" : $_REQUEST['zone_id'];
                update_option($this->plugin->name, $updateArray);
            }
        }
        if (isset($_REQUEST['clearcache_2']) && isset($_REQUEST['clearcache'])){
            include('_cache.php');
            ?>
            <div class="updated notice is-dismissable">
                <p><?php _e( 'The cache has been cleared, excellent!', 'my_plugin_textdomain' ); ?></p>
            </div>
            <?php   
        }

        if(isset($_REQUEST['zonedelete'])){
            include('_del.php');
            ?>
            <div class="updated notice is-dismissable">
                <p><?php _e( 'The dns record has been deleted, excellent!', 'my_plugin_textdomain' ); ?></p>
            </div>
            <?php 
        }
        if(isset($_REQUEST['add_dns_cloudflare']) && isset($_REQUEST['cloudflare_content']) && isset($_REQUEST['cloudflare_name'])){
            include('_add.php');
            ?>
            <div class="updated notice is-dismissable">
                <p><?php _e( 'The dns record has been created, excellent!', 'my_plugin_textdomain' ); ?></p>
            </div>
            <?php  
        }
        // Get latest settings
        $mysettings = get_option($this->plugin->name);
        $this->settings = array(
            'cloudflare_email' => esc_html(wp_unslash($mysettings['cloudflare_email'])),
            'cloudflare_api_key' => esc_html(wp_unslash($mysettings['cloudflare_api_key'])),
            'zone_id' => esc_html(wp_unslash($mysettings['zone_id']))
        );

    	// Load Settings Form
        include_once(WP_PLUGIN_DIR . '/' . $this->plugin->name . '/views/settings.php');
    }
}
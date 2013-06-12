<?php
/*
Plugin Name:    Adobe Machine Translator
Plugin URI:     https://github.com/leohacker/WPPlugin-AdobeMachineTranslator
Description:    Translator plugin for Wordpress blog system powered by Adobe Machine Translation services.
Version:        0.1
Author:         Leo Jiang
Author URI:     https://github.com/leohacker
License:        GPL2
*/
/*
Copyright 2013  Leo Jiang  (email : ljiang@adobe.com)

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

/**
 * How to learn to write a plugin for WP.
 *
 * 1. Writing a plugin
 *      [http://codex.wordpress.org/Writing_a_Plugin]
 *      Basic concepts of writing a WP plugin.
 *      - actions and filters. (Hooks)
 *      - plugin name, readme.txt and sections in readme.txt, headers in main plugin php file.
 *      - Save plugin data into the DB: option, metadata.
 *      - Options mechanism, replaced by settings API.
 *      - I18n
 *      - Suggestions:
 *        - Use class to create namespace for functions.
 *        - DB operations.
 *        - Don't use the <script> and <style> directly. wp_enqueue_style() and wp_enqueue_script().
 *
 * 2. Code structure
 *      - Hooks activation/deactivation/uninstall and class based/OOP architecture for modern plugin.
 *      [http://wordpress.stackexchange.com/questions/25910/uninstall-activate-deactivate-a-plugin-typical-features-how-to/25979#25979]
 *      - How to write a WP plugin
 *      [http://www.yaconiello.com/blog/how-to-write-wordpress-plugin/#sthash.8LHGEab7.dpbs]
 *
 * 3. Create option page
 *      - Administration Menus
 *          [http://codex.wordpress.org/Adding_Administration_Menus]
 *      - Create options pages
 *          [http://codex.wordpress.org/Creating_Options_Pages]
 *      - Settings API
 *          [http://codex.wordpress.org/Settings_API]
 *          setting API will add and update the option semi-automatically.
 *      - How to handle WP settings
 *          [http://www.yaconiello.com/blog/how-to-handle-wordpress-settings/#sthash.4fWd9CAd.dpbs]
 *      - WordPress Settings API Tutorial
 *          [http://ottopress.com/2009/wordpress-settings-api-tutorial/]
 *      - WP Settings API explained and HTML form samples
 *          [http://www.presscoders.com/2010/05/wordpress-settings-api-explained/]
 *      - Incorporating the Settings API in WP Themes - a very detailed tutorial
 *          [http://www.chipbennett.net/2011/02/17/incorporating-the-settings-api-in-wordpress-themes/]
 *
 * 4. Script and CSS Style
 *      - Add style and script only on admin page.
 *        [http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts]
 *        [http://codex.wordpress.org/Function_Reference/wp_enqueue_style]
 *
 * 5. Plugin API
 *    [http://codex.wordpress.org/Plugin_API]
 *
 * 6. Reference
 *    - Plugin Resource
 *      [http://codex.wordpress.org/Plugin_Resources] You can find the links to development resources and references here.
 *    - Sanitization and Validation in WP.
 *      [http://wp.tutsplus.com/tutorials/creative-coding/data-sanitization-and-validation-with-wordpress/]
 *    - Top 10 Most Common Coding Mistakes in WP plugins.
 *      [http://planetozh.com/blog/2009/09/top-10-most-common-coding-mistakes-in-wordpress-plugins/]
 *    - Internationalization
 *      [http://codex.wordpress.org/I18n_for_WordPress_Developers]
 */
/**
 * WP Plugin Help:
 *
 * your plugin is NOT included within the global scope.
 * It's included in the activate_plugin function, and so its "main body" is not automatically in the global scope.
 * @see http://codex.wordpress.org/Function_Reference/register_activation_hook
 */

// exit if WP core isn't loaded.
defined('ABSPATH') OR exit;

if(!class_exists('AdobeMachineTranslator'))
{
    // include the global variable of languages arrays.
    require_once(sprintf("%s/languages_adobe.php", dirname(__FILE__)));
    // AMTSettings class
    require_once(sprintf("%s/settings.php", dirname(__FILE__)));

    class AdobeMachineTranslator
    {
        private $plugin_settings;
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->plugin_settings = new AMTSettings();

            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array(&$this->plugin_settings, 'plugin_settings_link'));

            add_action('wp_enqueue_scripts', array($this, 'page_style_script'));
        }

        public function page_style_script()
        {
            wp_enqueue_style('adobe-machine-translator', plugins_url("style/microsoft-ajax-translation.css", __FILE__), false, '20120229', 'screen');
            wp_enqueue_script('jquery-translate', plugins_url("js/jquery.translate-1.4.7.js", __FILE__), array('jquery'), '1.4.7', true);
            // wp_enqueue_script('jquery-translate', plugins_url("js/jquery.translate-1.4.7.min.js", __FILE__), array('jquery'), '1.4.7', true);
        }

        /**
         * Callback function when activating the plugin.
         *
         * WP Plugin Help:
             * If you are interested in doing something just after a plugin has been activated
             * it is important to note that the hook process performs an instant redirect after it fires.
             * So it is impossible to fire add_action, do_action, add_filter, ... type calls
             * until the redirect has occurred. A quick workaround to this quirk is to use
             * the add_option method like so.
             * @see http://codex.wordpress.org/Function_Reference/register_activation_hook
             *
             * Don't echo anything in setup callbacks (activate, deactivate).
         */
        public function activate()
        {
            // $this->plugin_settings->set_options_default();
        }

        /**
         * Callback function when deactivating the plugin.
         */
        public function deactivate()
        {

        }
    }
}

if(class_exists('AdobeMachineTranslator'))
{
    $amt_plugin = new AdobeMachineTranslator();
    register_activation_hook(__FILE__, array(&$amt_plugin, 'activate'));
    register_deactivation_hook(__FILE__, array(&$amt_plugin, 'deactivate'));
}
?>
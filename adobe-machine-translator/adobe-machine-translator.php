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
 *    [http://codex.wordpress.org/Plugin_API/Action_Reference]
 *    [http://codex.wordpress.org/Plugin_API/Filter_Reference]
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
        private $options;
        private $browser_lg;

        private $before_translate = '['; // text to display before and after "Translate" link
        private $after_translate = ']';
        /**
         * Constructor.
         */
        public function __construct()
        {
            global $target_languages;
            $this->plugin_settings = new AMTSettings();
            $this->options = $this->plugin_settings->options;
            $this->browser_lg = $this->preferred_language( $target_languages );
        }

        public function init()
        {
            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array(&$this, 'plugin_settings_link'));
            add_action('wp_enqueue_scripts', array($this, 'page_style_script'));

            if ($this->options['enable_post'] || $this->options['enable_page']) {
                add_filter('the_content', array(&$this, 'filter_post'), 50);
            }

            if ($this->options['enable_comment']) {
                add_filter('comment_text', array(&$this, 'filter_comment'), 50);
            }

            add_action('wp_footer', array($this, 'popup_languages'));
                // add_filter( 'wp_footer', array( &$this, 'getLanguagePopup' ), 5 );
                // add_filter( 'wp_footer', array( &$this, 'getFooterJS' ), 5 );
        }

        /**
         * Add a link 'Settings' beside Activate in plugins management page.
         */
        public function plugin_settings_link($links)
        {
            $settings_link = '<a href="options-general.php?page='.$this->plugin_settings->page_name.'">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        public function page_style_script()
        {
            wp_enqueue_style('adobe-machine-translator', plugins_url("style/microsoft-ajax-translation.css", __FILE__), false, '20120229', 'screen');
            wp_enqueue_script('jquery-translate', plugins_url("js/jquery.translate-1.4.7.js", __FILE__), array('jquery'), '1.4.7', true);
            // wp_enqueue_script('jquery-translate', plugins_url("js/jquery.translate-1.4.7.min.js", __FILE__), array('jquery'), '1.4.7', true);
        }

        public function filter_post($content = '')
        {
            $backtrace = debug_backtrace();
            if ( !is_feed() && // ignore feeds
            ( "the_excerpt" != $backtrace[7]["function"] ) && // ignore excerpts
            ( ( !is_page() && $this->options['enable_post'] ) || ( is_page() && $this->options['enable_page'] ) ) && // apply to posts or pages
            // ! ( in_array( get_the_ID(), $this->options['exclude_pages'] ) ) && // exclude certain pages and posts
            ! ( $this ->options['exclude_home'] && is_home() ) )
            { // if option is set exclude home page
                $translate_block = $this->generate_translate_block('post');
                $translate_hr = ($this->options['enable_hline']) ? ( '<hr class="translate_hr" />'."\n" ) : "";
                $id = get_the_ID();

                $content = '<div id="content_div-' . $id . '">' . "\n" . $content . "</div>\n";

                if ($this->options['button_position'] == 'bottom' ) {
                    $content = $content .
                        '<div class="translate_block">' . "\n"
                        .$translate_hr
                        .$translate_block
                        ."</div>\n";
                } else if ($this->options['button_position'] == 'top') {
                    $content = '<div class="translate_block">' . "\n"
                        .$translate_block
                        .$translate_hr
                        ."</div>\n"
                        .$content;
                }
            }
            return $content;
        }

        public function filter_comment($content = '')
        {
            // if ( !is_feed() ) { // ignore feeds
            //     $translate_block = $this -> generate_translate_block('comment');
            //     $translate_hr = ( $this -> options['hlineEnable'] ) ? ( '<hr class="translate_hr" />' . "\n" ) : "";
            //     $content = $content .
            //         '<div class="translate_block" style="display: none;">' . "\n" .
            //         $translate_hr .
            //         $translate_block .
            //         "</div>\n";
            // }
            return $content;
        }

        /**
         * generate translate_block
         * @return $translate_block string
         * @param $type string a 'post or 'comment'
         */
        function generate_translate_block($type = 'post') {
            if ( 'post' == $type ) {
                $id = get_the_ID();
            } elseif ( 'comment' == $type ) {
                global $comment;
                $id = $comment -> comment_ID;
            } else {
                return NULL;
            }

            $browser_lg = $this->browser_lg;

            $div = '<div id="ajaxPath" style="display:none;">'.admin_url('admin-ajax.php').'</div>';

            global $translate_message;
            $link_id = 'translate_button_'.$type.'-'.$id;
            $translate_button_text = ($this->before_translate).($translate_message[$browser_lg]).($this->after_translate);
            $href_value = sprintf("javascript:show_translate_popup('%s', '$s', '%s');", $browser_lg, $type, $id);
            $link = sprintf('<a class="translate_translate" id="%s" lang="%s" xml:lang="%s" href="%s">%s</a>',
                            $link_id, $browser_lg, $browser_lg, $href_value, $translate_button_text);

            $img_src = plugins_url('images/transparent.gif', __FILE__);
            $img_id = "translate_loading_".$type.'-'.$id;
            $img = sprintf('<img src="%s" id="%s" class="translate_loading" style="display: none;" width="16" height="16" alt="" />',
                           $img_src, $img_id);

            $translate_block = $div.$link.$img."\n";
            return $translate_block;
        }

        /**
         * echoes the language popup in the wp_footer
         */
        function popup_languages() {
            $numberof_languages = count( $this->options['languages'] );
            $languages_per_column = ceil( ( $numberof_languages + 2 ) / 3 );
            $index = 0;

            $background_color = $this->options['background_color'] ? 'background-color: '.$this->options['background_color'] : '';

            global $languages_English;
            global $languages_localized;

            $output = sprintf('<div id="translate_popup" style="display: none; %s">', $background_color);
            $output .= "\n\t";
            $output .= '<table class="translate_links"><tr><td valign="top">' . "\n";

            foreach ($this->options['languages'] as $lg) {
                $output .= "\t\t";
                $output .= sprintf('<a class="languagelink" lang="%s" xml:lang="%s" href="#" title="%s">',
                                   $lg, $lg, $languages_English[$lg]);
                if ($this->options['link_style'] == 'flag' || $this->options['link_style'] == 'text_flag') {
                    $output .= sprintf('<img class="translate_flag %s " src="%s" alt="%s" width="16" height="11" />',
                                       $lg, plugins_url('images/transparent.gif', __FILE__), $languages_localized[$lg]);
                }
                if ($this->options['link_style'] == 'text' || $this->options['link_style'] == 'text_flag') {
                    $output .= $languages_localized[$lg];
                }
                $output .= '</a>'."\n";
                if ( 0 == ++$index % $languages_per_column ) {
                    $output .= "\t" . '</td><td valign="top">' . "\n";
                }

            }

            $output .= "\t\t";
            $output .= "\n\t</td></tr></table>\n</div>\n";
            echo $output;
        }

        /**
         * function from: http://us.php.net/manual/en/function.http-negotiate-language.php
         * determine which language out of an available set the user prefers most
         * @param array $available_languages An array with language-tag-strings (must be lowercase) that are available
         * @param string $http_accept_language An HTTP_ACCEPT_LANGUAGE string (read from $_SERVER['HTTP_ACCEPT_LANGUAGE'] if left out)
         * @return string Best language chosen from $available_languages
         */
        function preferred_language($available_languages, $http_accept_language="auto") {
            // if $http_accept_language was left out, read it from the HTTP-Header
            if ($http_accept_language == "auto")
                $http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

            // standard  for HTTP_ACCEPT_LANGUAGE is defined under
            // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
            // pattern to find is therefore something like this:
            //    1#( language-range [ ";" "q" "=" qvalue ] )
            // where:
            //    language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" )
            //    qvalue         = ( "0" [ "." 0*3DIGIT ] )
            //            | ( "1" [ "." 0*3("0") ] )
            preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" . "(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i",
                $http_accept_language, $hits, PREG_SET_ORDER);

            // default language (in case of no hits) is 'en'
            $bestlang = 'en';
            $bestqval = 0;

            foreach ($hits as $arr) {
                // read data from the array of this hit
                $langprefix = strtolower ($arr[1]);
                if (!empty($arr[3])) {
                    $langrange = strtolower ($arr[3]);
                    $language = $langprefix . "-" . $langrange;
                }
                else $language = $langprefix;
                $qvalue = 1.0;
                if (!empty($arr[5]))
                    $qvalue = floatval($arr[5]);

                // find q-maximal language
                if (in_array($language,$available_languages) && ($qvalue > $bestqval)) {
                    $bestlang = $language;
                    $bestqval = $qvalue;
                }
                // if no direct hit, try the prefix only but decrease q-value by 10% (as http_negotiate_language does)
                else if (in_array($langprefix,$available_languages) && (($qvalue * 0.9) > $bestqval)) {
                    $bestlang = $langprefix;
                    $bestqval = $qvalue * 0.9;
                }
            }
            return $bestlang;
        }

        // The next two functions are designed to be inserted into custom theme.
        /**
         * get a translate button that can be used anywhere in a post or page as needed by a custom theme. This should be in the WordPress loop.
         */
        function translate_button() {
            $backtrace = debug_backtrace();
            if ( !is_feed() && // ignore feeds
            ( "the_excerpt" != $backtrace[7]["function"] ) && // ignore excerpts
            ( ( !is_page() && $this->options['enable_post'] ) || ( is_page() && $this->options['enable_page'] ) ) && // apply to posts or pages
            ! ( in_array( get_the_ID(), $this -> options['exclude_pages'] ) ) && // exclude certain pages and posts
            ! ( $this -> options['exclude_home'] && is_home() ) ) { // if option is set exclude home page
                $translate_block = $this->generate_translate_block('post');
                return '<div class="translate_block" style="display: none;">' . "\n" .
                    $translate_block .
                    "</div>\n";
            }
        }

        /**
         * echo a translate button for current post. This should be in the WordPress loop.
         */
        function adobe_ajax_translate_button() {
            echo $this->translate_button();
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
        public function activate() {}

        /**
         * Callback function when deactivating the plugin.
         */
        public function deactivate() {}
    }
}

if(class_exists('AdobeMachineTranslator'))
{
    $amt_plugin = new AdobeMachineTranslator();
    register_activation_hook(__FILE__, array(&$amt_plugin, 'activate'));
    register_deactivation_hook(__FILE__, array(&$amt_plugin, 'deactivate'));
    add_action('init', array($amt_plugin, 'init'));
}
?>
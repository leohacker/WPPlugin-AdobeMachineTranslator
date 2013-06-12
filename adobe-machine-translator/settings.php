<?php
/**
 * Settings class for plugin Adobe Machine Translator.
 * All options are stored in an array, use the real option name as index of array.
 * $options = get_option($option_name), $options is the array.
 * $options['enable_post'], get the value for option 'enable_post'.
 *
 * Use the WordPress settings API to generate the option page for plugin.
 * @see settings_page()
 *
 * @author Leo Jiang <ljiang@adobe.com>
 * @license GPL v2
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

if(!class_exists('AMTSettings'))
{
    class AMTSettings
    {
        private $page_name = "AMT_options_page";            // option page name
        private $section_name = "AMT_options_section";      // section name in option page
        private $option_name = "AMT_options";               // name in DB for options.

        private $default_options = array(       // default values for options
            'enable_post' => true,              // is stored as "1" and "0" in database or returns false if the option doesn't exist
            'enable_comment' => false,          // is stored as "1" and "0" in database or returns false if the option doesn't exist
            'enabel_page' => false,              // is stored as "1" and "0" in database or returns false if the option doesn't exist
            'button_position' => 'bottom',      // is stored as 'top', 'bottom', or 'none'
            'link_style' => 'text',             // is stored as 'text', 'flag', or 'flag_text'

            'enable_hline' => true,             // is stored as "1" and "0" in database or returns false if the option doesn't exist

            'copy_background' => false,         // is stored as "1" and "0" in database or returns false if the option doesn't exist
            'background' => NULL,               // is stored as NULL or CSS color in the format #5AF or #55AAFF

            'exclude_home' => false,            // is stored as "1" and "0" in database or returns false if the option doesn't exist
            'exclude_list' => array(),          // array of post and page id's to exclude
            'DNT_jquery_selector' => NULL,      // is stored as "" or string in the style of a jQuery selector

            'languages' => array()              // array of language codes to display in popup window.
        );

        private $options;                       // local copy of options.

        public function __construct()
        {
            // load the default option if the options aren't existed in database yet.
            $this->options = get_option($this->option_name, $this->default_options);
            update_option($this->option_name, $this->options);

            // register the option name into DB, construct the contents of option page.
            add_action('admin_init', array(&$this, 'init_settings'));
            // add plugin option page into admin menu, and use the settings API to render the option page.
            add_action('admin_menu', array(&$this, 'add_menu'));
        }

        /**
         * Add a link 'Settings' beside Activate in plugins management page.
         */
        function plugin_settings_link($links)
        {
            $settings_link = '<a href="options-general.php?page='.$this->page_name.'">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        public function set_options_default()
        {
            update_option($this->option_name, $this->default_options);
        }

        /**
         * Create the settings menu and option page.
         */
        public function add_menu()
        {
            add_options_page("Adobe Machine Translator Settings",   // page_title
                             "Adobe Machine Translator",            // menu_title
                             'manage_options',                      // capability
                             $this->page_name,                      // menu_slug, page id
                             array(&$this, 'settings_page'));       // callback function to create the settings page.
        }

        /**
         * Use the WP Settings API to create option page.
         * settings_fields(option_name), add the essential and hidden elements into option page.
         * do_settings_sections(page_name), render the sections in page_name to create the option page.
         */
        public function settings_page()
        {
            if(!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permission to access this page.'));
            }
            ?>

            <div class="wrap">
                <?php screen_icon(); ?>
                <h2>Adobe Machine Translator</h2>
                <form method="POST" action="options.php">
                    <?php settings_fields($this->option_name); ?>
                    <?php do_settings_sections($this->page_name); ?>
                    <input name="Submit" class="button-primary" type="submit" value="Save Changes" />
                </form>
            </div>
            <?php
        }

        public function init_settings()
        {
            // Same value for option group, option name and parameters of settings_fields().
            // In the WordPress codex, https://codex.wordpress.org/Function_Reference/register_setting,
            // They discussed errors and have a conclusion:
            // In short, an easy solution is to make $option_group match $option_name.

            // register the options as array, named as $this->option_name, "AMT_options".
            register_setting($this->option_name,                    // option group
                             $this->option_name,                    // option name
                             array(&$this, 'sanitize'));            // callback function for sanitizing the input.

            // add the only section for plugin options.
            add_settings_section($this->section_name,               // section id
                                 '',                                // section title
                                 array($this, 'section_text'),      // callback function to create the section description.
                                 $this->page_name);                 // page id

            // add the fields(options) into specified section.
            add_settings_field('scope',                                 // ID
                               'Translation Scope',                     // Label
                               array(&$this, 'show_translation_scope'), // callback
                               $this->page_name,
                               $this->section_name
                               );

            add_settings_field('button',
                               'Translate Button Position',
                               array(&$this, 'show_radio_group'),
                               $this->page_name,
                               $this->section_name,
                               array(
                                     'index' => 'button_position',
                                     'value_label' => array('top' => 'Top',
                                                            'bottom' => 'Bottom',
                                                            'none' => 'None')
                                     )
                               );

            add_settings_field('link',
                               'Language Link Style',
                               array(&$this, 'show_radio_group'),
                               $this->page_name,
                               $this->section_name,
                               array(
                                     'index' => 'link_style',
                                     'value_label' => array('text' => 'Language Text',
                                                            'flag' => 'Flag',
                                                            'flag_text' => 'Flag and Language Text'
                                                            )
                                     )
                               );

            add_settings_field('hline',
                               'Horizontal Line',
                               array(&$this, 'show_checkbox'),
                               $this->page_name,
                               $this->section_name,
                               array(
                                     'index' => 'enable_hline',
                                     'label' => 'Show line above or below Translate button')
                               );

            add_settings_field('backcolor',
                               'Background color',
                               array(&$this, 'show_background_color'),
                               $this->page_name,
                               $this->section_name
                               );

            add_settings_field('exclude',
                               'Exclude',
                               array(&$this, 'show_exclude'),
                               $this->page_name,
                               $this->section_name
                               );

            add_settings_field('dnt',
                               'Do Not Translate',
                               array(&$this, 'show_dnt'),
                               $this->page_name,
                               $this->section_name
                               );

            add_settings_field('languages',
                               'Languages',
                               array(&$this, 'show_languages'),
                               $this->page_name,
                               $this->section_name
                               );
        }

        /**
         * Display the description text for section.
         */
        public function section_text()
        {
            // Empty section text.
        }

        /**
         * Display the fields.
         */
        public function show_translation_scope()
        {
            $scopes = array('enable_post' => "Enable post translation",
                            'enable_comment' => "Enable comment translation",
                            'enable_page' => "Enable page translation");

            foreach( $scopes as $index => $label ) {
                $this->show_checkbox(array('index' => $index, 'label' => $label));
            }
        }

        public function show_background_color()
        {
            $this->show_checkbox(array('index' => 'copy_background',
                                       'label' => 'Copy background color from page body'));
            $this->show_input_text('background', 10, true);

            ?>
            <span class="description" style="padding-left: 10px" >Background color in the format #5AF or #55AAFF</span>
            <?php
        }

        public function show_exclude()
        {
            $this->show_checkbox(array('index' => 'exclude_home',
                                       'label' => 'Exclude home page'));
            $this->show_input_text('exclude_list', 60, true);

            ?>
            <br />
            <span class="description">A comma separated list of post and page ID’s</span>
            <?php
        }

        public function show_dnt()
        {
            $this->show_input_text('DNT_jquery_selector', 20, true);

            ?>
            <span class="description" style="padding-left: 10px" >Selector in jQuery format (See the FAQ)</span>
            <?php
        }

        public function show_languages()
        {
        ?>
            <table class="translate_links">
                <tr>
                    <?php
                        global $languages_English;
                        global $languages_localized;

                        $languages = (array)$this->options['languages'];
                        $name = $this->option_name.'[languages][]';

                        $plugin_root = plugins_url('', __FILE__);

                        $number_langs = count($languages_English);
                        $i = 0;
                        foreach ( $languages_English as $lang_id => $lang_english ) {
                            if ( $i % 10 == 0 ) {
                                echo '<td valign="top">'."\n";
                            }
                            $localized = $languages_localized[$lang_id];
                            $checked = in_array($lang_id, $languages) ? 'checked' : '';

                            $output = sprintf('<label valign="bottom"><input valign="center" type="checkbox" name="%s" %s value="%s" />'.
                                              ' <img class="translate_flag %s" src="%s/images/transparent.gif" alt="%s" width="16" height="11" />'.
                                              ' <span>%s</span></label><br />',
                                              $name, $checked, $lang_id,
                                              $lang_id, $plugin_root, $localized, $lang_english
                                              );
                            echo $output;
                            if ( $i %10 == 9 ) {
                                echo '</td>'."\n";
                            }
                            ++$i;
                        }
                    ?>
                </tr>
            </table>
        <?php
        }

        /**
         * Standard display functions for HTML controls.
         */
        public function show_checkbox($args)
        {
            $index = $args['index'];
            $label = $args['label'];

            $options = $this->options;
            $option = $options[$index];

            $name = $this->option_name.'['.$index.']';
            $checked = ($option) ? 'checked' : '';
            $type = "checkbox";

            $output = sprintf('<label><input name="%s" type="%s" %s /> %s </label><br/>',
                             $name, $type, $checked, $label);
            echo $output;
        }

        public function show_radio_group($args)
        {
            $index = $args['index'];
            $value_label = $args['value_label'];

            foreach ($value_label as $value => $label) {
                $this->show_radio_button($index, $value, $label);
            }
        }

        public function show_radio_button($index, $value, $label)
        {
            $options = $this->options;
            $option = $options[$index];

            $name = $this->option_name.'['.$index.']';
            $checked = ($value == $option) ? 'checked' : '';
            $type = "radio";

            $output = sprintf('<label><input name="%s" value="%s" type="%s" %s /> %s </label><br/>',
                              $name, $value, $type, $checked, $label);
            echo $output;
        }

        // TODO: refactor the parameters into $args.
        public function show_input_text($index, $size, $enable) {
            $options = $this->options;
            $option = $options[$index];

            $name = $this->option_name.'['.$index.']';
            $type = "text";

            $disable = $enable ? '' : 'disabled' ;

            $output = sprintf('<input name="%s" value="%s" size="%d" type="%s" %s />',
                              $name, $option, $size, $type, $disable);
            echo $output;
        }

        /**
         * Validate the input fields. The returned array will be stored in the database.
         */
        function sanitize($input)
        {
            // update the $this->options before saving the options into database.
            $this->options = $input;
            return $this->options;
        }

    }
}
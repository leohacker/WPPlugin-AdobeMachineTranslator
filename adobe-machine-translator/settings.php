<?php
if(!class_exists('AMTSettings'))
{
    class AMTSettings
    {
        private $page_id;
        private $section_id;
        private $options;

        public function __construct()
        {
            $this->page_id = "AMT_options_page";
            $this->section_id = "AMT_options_section";
            $this->options = "AMT_options";

            // register hook functions.
            add_action('admin_init', array(&$this, 'init_settings'));
            add_action('admin_menu', array(&$this, 'add_menu'));

        }

        function plugin_settings_link($links)
        {
            $settings_link = '<a href="options-general.php?page='.$this->page_id.'">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        public function set_options_default()
        {
            $options = array(                       // default values for options
                'enable_post' => true,              // is stored as "1" and "0" in database or returns false if the option doesn't exist
                'enable_comment' => false,          // is stored as "1" and "0" in database or returns false if the option doesn't exist
                'enabel_page' => true,              // is stored as "1" and "0" in database or returns false if the option doesn't exist
                'linkStyle' => 'text',              // is stored as 'text', 'image', or 'imageandtext'
                'linkPosition' => 'bottom',         // is stored as 'top', 'bottom', or 'none'
                'hlineEnable' => true,              // is stored as "1" and "0" in database or returns false if the option doesn't exist

                'copyBodyBackgroundColor' => false, // is stored as "1" and "0" in database or returns false if the option doesn't exist
                'backgroundColor' => NULL,          // is stored as NULL or CSS color in the format #5AF or #55AAFF

                'excludeHome' => false,             // is stored as "1" and "0" in database or returns false if the option doesn't exist
                'excludePages' => array(),          // array of post and page id's to exclude
                'doNotTranslateSelector' => NULL,   // is stored as "" or string in the style of a jQuery selector

                'languages' => array()              // array of language codes to display in popup
            );
            update_option($this->options, $options);
        }

        public function add_menu()
        {
            add_options_page("Adobe Machine Translator Settings",   // page_title
                             "Adobe Machine Translator",            // menu_title
                             'manage_options',                      // capability
                             $this->page_id,                        // menu_slug, page id
                             array(&$this, 'settings_page'));       // callback function to create the settings page.
        }

        public function settings_page()
        {
            // if(!current_user_can('manage_options')) {
            //     wp_die(__('You do not have sufficient permission to access this page.'));
            // }
            include(sprintf("%s/settings_page.php", dirname(__FILE__)));
        }

        public function init_settings()
        {
            // Same value for option group, option name and parameters of settings_fields().
            // In the WordPress codex, https://codex.wordpress.org/Function_Reference/register_setting,
            // They discussed errors and have a conclusion:
            // In short, an easy solution is to make $option_group match $option_name.

            // register the options as array.
            register_setting($this->options,                        // option group
                             $this->options,                        // option name
                             array(&$this, 'sanitize'));            // callback function for sanitizing the input.

            add_settings_section($this->section_id,                 // section id
                                 '',                                // section title
                                 array($this, 'section_text'),      // callback function to create the section description.
                                 $this->page_id);                   // page id

            add_settings_field('scope',                                 // ID
                               'Translation Scope',                     // Label
                               array(&$this, 'show_translation_scope'), // callback
                               $this->page_id,
                               $this->section_id
                               );
            // add_settings_field('link',
            //                    'Translate Button Style')
        }

        public function section_text()
        {
            // Empty section text.
        }

        public function show_translation_scope()
        {
            $options = get_option($this->options);

            $scopes = array('enable_post' => "Enable post translation",
                            'enable_comment' => "Enable comment translation",
                            'enable_page' => "Enable page translation");

            foreach( $scopes as $index => $label )
            {
                $option = $options[$index];
                if ($option) {
                    $checked = ' checked="checked" ';
                } else {
                    $checked = ' ';
                }
                $id = $index;
                $name = $this->options.'['.$index.']';
                $type = "checkbox";

                $output = sprintf('<input id="%s" name="%s" type="%s" %s /> %s <br />',
                                 $id, $name, $type, $checked, $label);
                echo $output;
            }
        }

        function sanitize($input)
        {
            $options = get_option($this->options);
            $options['enable_post'] = $this->sanitize_checkbox($input['enable_post']);
            $options['enable_comment'] = $this->sanitize_checkbox($input['enable_comment']);
            $options['enable_page'] = $this->sanitize_checkbox($input['enable_page']);
            return $input;
        }

        function sanitize_checkbox($value)
        { // sanitize checkbox option values
            // return an empty string return '' if failed.
            return ( $value ) ? 1 : 0;
        }
    }


}
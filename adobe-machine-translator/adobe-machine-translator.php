<?php
/*
Plugin Name: Adobe Machine Translator
Plugin URI: https://github.com/leohacker/WPPlugin-AdobeMachineTranslator
Description: Translator plugin for Wordpress blog system powered by Adobe Machine Translation services.
Version: 0.1
Author: Leo Jiang
Author URI: https://github.com/leohacker
License: GPL2
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



if(!class_exists('AdobeMachineTranslator'))
{
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
        }

        /**
         * Activate the plugin.
         */
        public function activate()
        {
            $this->plugin_settings->set_options_default();
        }

        /**
         * Deactivate the plugin.
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
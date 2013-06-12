<?php
/**
 * Prefer the uninstall.php than register an uninstall hook to install the plugin.
 * @see http://codex.wordpress.org/Function_Reference/register_uninstall_hook
 *
 * If the plugin can not be written without running code within the plugin, then
 * the plugin should create a file named 'uninstall.php' in the base plugin folder.
 * This file will be called, if it exists, during the uninstall process bypassing the uninstall hook.
 */
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

// Use the option name directly to avoid to include the settings.php.
delete_option('AMT_options');
?>
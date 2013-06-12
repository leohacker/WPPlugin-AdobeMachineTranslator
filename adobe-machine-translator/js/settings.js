jQuery(document).ready(function($) {
    jQuery('input[name="microsoft_translation_options[postEnable]"]').click(function() { // disable and enable excludeHome checkbox
        if ( jQuery(this).is(':checked') ) {
            jQuery('input[name="microsoft_translation_options[excludeHome]"]').removeAttr('disabled');
        } else {
            jQuery('input[name="microsoft_translation_options[excludeHome]"]').attr('disabled', 'disabled');
        }
    });
    jQuery('input[name="microsoft_translation_options[copyBodyBackgroundColor]"]').click(function() { // disable and enable backgroundColor text field
        if ( jQuery(this).is(':checked') ) {
            jQuery('input[name="microsoft_translation_options[backgroundColor]"]').attr('disabled', 'disabled');
        } else {
            jQuery('input[name="microsoft_translation_options[backgroundColor]"]').removeAttr('disabled');
        }
    });
    jQuery('#languages_all').click(function() { // check all languages
        jQuery('.translate_links input').attr('checked', 'checked');
    });
    jQuery('#languages_none').click(function() { // uncheck all languages
        jQuery('.translate_links input').removeAttr('checked');
    });
});
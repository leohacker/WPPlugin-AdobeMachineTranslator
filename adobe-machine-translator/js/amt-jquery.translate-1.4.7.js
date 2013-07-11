/**
 * Microsoft AJAX Translation
 * 2012-02-29
 */

jQuery( function() {
//	var bingAppId = bingApp.appId;
//	jQuery.translate.load(bingAppId);
	jQuery('.translate_block').show(); // Show [Translate] buttons after the document is ready
	var popup_id = document.getElementById( 'translate_popup' );
	if ( popup_id ) {
		document.body.appendChild( popup_id ); // Move popup to the end of the body if it isn't already
	}
});

function microsoft_translate( lang, type, id ) {
    if ( type == "comment" ) {
        translate_comment(lang, id);
    } else {    // page is another type of post.
        translate_post(lang, id);
    }
}

function translate_comment(lang, id) {
    var type = 'comment';
    var type_prefix = 'content_comment-';
    var comment_id_translation = type_prefix + id + '-translation';
    var comment_id_orig = type_prefix + id + '-orig';
    var class_id = '.' + type_prefix + id ;

    jQuery(class_id + ':gt(0)').remove();
    jQuery(class_id + ':first').after(jQuery(class_id + ':first').clone());
    jQuery(class_id + ':first').attr('id', comment_id_orig);
    jQuery(class_id + ':last').attr('id', comment_id_translation);

    // hide the translation post.
    jQuery('#'+comment_id_translation).hide();
    jQuery( '#translate_popup' ).slideUp( 'fast' ); // Close the popup

    text_node = jQuery('#'+comment_id_translation);
    translate_comment_node(text_node, lang, type, id);
}

function translate_comment_node(text_node, lang, type, id) {
        var loading_id;

        loading_id = '#translate_loading_' + type + '-' + id;
        jQ_button_id = jQuery( '#translate_button_' + type + '-' + id );
        jQuery( loading_id ).show();
        // translate the post content.
        var text= text_node.html();
        var data = {
            action: 'get_translate',
            from: 'en',
            to: lang,
            str: text
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(jQuery("#ajaxPath").text(), data).done(function(response) {
            jQuery( loading_id ).hide();
            insert_translation(text_node, response);

            var type_prefix = 'content_comment-';
            var comment_id_translation = type_prefix + id + '-translation';
            var comment_id_orig = type_prefix + id + '-orig';

            var translate_node = jQuery('#'+comment_id_translation);
            translate_node.show();
            jQuery('#' + 'translate_button_' + type + '-' + id).hide();

            var translation_position = AMTOptions.translation_position;
            jQuery('#hide_button_' + type + '-' + id).show();
            if ( translation_position == "replace") {
                jQuery('#'+comment_id_orig).hide();
                jQuery('#hide_button_' + type + '-' + id).hide();
                jQuery('#origin_button_' + type + '-' + id).show();
            }
        });
}

function translate_post(lang, id) {
    var type = 'post'
    var post_id_orig        = type + '-' + id + '-orig';
    var post_id_translation = type + '-' + id + '-translation';
    var title_id            = 'title' + '-' + id;
    var content_id          = 'content_' + type + '-' + id;

    var class_id = type + '-' + id;
    // remove the old translation post;
    jQuery('.'+class_id + ':gt(0)').remove();
    jQuery('.'+class_id + ':first').after(jQuery('.'+class_id+':first').clone());
    jQuery('.'+class_id + ':first').attr('id', post_id_orig);
    jQuery('.'+class_id + ':last').attr('id', post_id_translation);

    // hide the translation post.
    jQuery('#'+post_id_translation).hide();
    jQuery('#'+post_id_translation + ' .translate_loading').remove();

    jQuery( '#translate_popup' ).slideUp( 'fast' ); // Close the popup

    title_node = jQuery('#'+ post_id_translation + ' #' + title_id);
    text_node = jQuery('#'+ post_id_translation + ' #' + content_id);

    translate_post_nodes(title_node, text_node, lang, type, id);
}

function translate_post_nodes(title_node, text_node, lang, type, id) {
        var loading_id;

        loading_id = '#translate_loading_' + type + '-' + id;
        jQ_button_id = jQuery( '#translate_button_' + type + '-' + id );
        jQuery( loading_id ).show();
        // translate the post content.
        var text= text_node.html();
        var data = {
            action: 'get_translate',
            from: 'en',
            to: lang,
            str: text
        };

    	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    	jQuery.post(jQuery("#ajaxPath").text(), data).done(function(response) {
            insert_translation(text_node, response);

            var text = title_node.html();
            var data = {
                action: 'get_translate',
                from: 'en',
                to: lang,
                str: text
            };
            jQuery.post(jQuery("#ajaxPath").text(), data).done(function(response) {
                jQuery( loading_id ).hide();
                insert_translation(title_node, response);

                var post_id_orig        = type + '-' + id + '-orig';
                var post_id_translation = type + '-' + id + '-translation';

                var translate_node = jQuery('#'+post_id_translation);
                translate_node.show();
                jQuery('#'+post_id_translation + ' #' + 'translate_button_' + type + '-' + id).hide();

                var translation_position = AMTOptions.translation_position;
                jQuery('#'+post_id_translation + ' #' + 'hide_button_' + type + '-' + id).show();
                if ( translation_position == "replace") {
                    jQuery('#' + post_id_orig).hide();
                    jQuery('#'+post_id_translation + ' #' + 'hide_button_' + type + '-' + id).hide();
                    jQuery('#'+post_id_translation + ' #' + 'origin_button_' + type + '-' + id).show();
                }

            });
        });
}

function hide_translate(type, id) {
    show_origin(type, id);
}

function show_origin(type, id) {
    if( type == 'comment') {
        jQuery('#hide_button_comment'+'-'+id).hide();
        jQuery('#origin_button_comment'+'-'+id).hide();
        jQuery('#translate_button_comment'+'-'+id).show();
        type = 'content_comment';

    }
    var post_id_orig        = type + '-' + id + '-orig';
    var post_id_translation = type + '-' + id + '-translation';
    jQuery('#'+post_id_orig).show();
    jQuery('#'+post_id_translation).remove();
}

function insert_translation(node, xml) {
     xmlDoc = jQuery.parseXML( xml );
     translation = jQuery(xmlDoc).find( "TranslatedText" );
     node.html(unescape(translation.text()));
}

function localize_languages( browser_lang, popup_id ) {
	var language_nodes = jQuery( '#translate_popup a' ).get(),
		llangs = [], // array that holds localized language names
		i;
	for ( i in language_nodes ) {
		llangs[i] = language_nodes[i].title; // Make array of English language names
	}
	jQuery.translate( llangs, 'en', browser_lang, {
		complete: function() {
			llangs = this.translation;
			for ( i in language_nodes ) { // copy localized language names into titles
				language_nodes[i].title = llangs[i];
			}
			jQuery( popup_id ).data( 'localized', true );
		}
	});
}

function show_translate_popup( browser_lang, type, id ) {
	var popup_id = document.getElementById( 'translate_popup' ),
		jQ_popup_id = jQuery( popup_id ),
		jQ_button_id = jQuery( '#translate_button_' + type + '-' + id ),
		buttonleft = Math.round( jQ_button_id.offset().left ),
		buttonbottom = Math.round( jQ_button_id.offset().top + jQ_button_id.outerHeight(true) ),
		rightedge_diff;
	if ( popup_id ) {
		// jQuery.translate.languageCodeMap['pt'] = 'pt-PT'; // automatically convert 'pt' to 'pt-PT' in the jquery translate plugin
		if ( 'none' == popup_id.style.display || jQ_popup_id.position().top != buttonbottom ) { // check for hidden popup or incorrect placement
			popup_id.style.display = 'none';
			jQ_popup_id.css( 'left', buttonleft ).css( 'top', buttonbottom ); // move popup to correct position
			jQ_popup_id.slideDown( 'fast' );
			// move popup to the left if right edge is outside of window
			rightedge_diff = jQuery(window).width() + jQuery(window).scrollLeft() - jQ_popup_id.offset().left - jQ_popup_id.outerWidth(true);
			if ( rightedge_diff < 0 ) {
				jQ_popup_id.css( 'left', Math.max( 0, jQ_popup_id.offset().left + rightedge_diff ) );
			}
			jQuery( '#translate_popup .languagelink' ).each( function() { // bind click event onto all the anchors
				jQuery( this ).unbind( 'click' ).click( function () {
					microsoft_translate( this.lang, type, id );
					return false;
				});
			});
			if ( 'en' != browser_lang && ( ! jQ_popup_id.data( 'localized' ) ) ) { // If the browser's preferred language isn't English and the popup hasn't already been localized
				localize_languages( browser_lang, popup_id );
			}
		} else {
			jQ_popup_id.slideUp( 'fast' );
		}
	}
}
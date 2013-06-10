=== Plugin Name ===
Contributors: Leo Jiang
Tags: machine translation
Requires at least: 3.0.
Tested up to: 3.5
Stable tag: HEAD
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Yet another machine translation plugin for WordPress blog system, powered by Adobe Machine Translation CLSMT service.

== Description ==

The Adobe Machine Translator WordPress plugin offers a "Translate" button that allows readers to translate posts, comments,
pages into a specified language by calling Adobe CLSMT translation service.

The Adobe Machine Translator is inspirited by Microsoft AJAX Translator, and is rewritten and enhanced. Original
code structure in Microsoft AJAX Translator make the code too difficult to maintain. I rewrite the code with
WordPress settings API.

The Adobe Machine Translator WordPress plugin provides a quick, simple, and light way to add translation to your blog.

A "Translate" button can be added to the bottom or top of posts, pages, and/or comments. When the button is clicked a popup window opens showing a list of available languages.

You can choose which of the N languages to display in the Administration Panel. The list of languages can be shown as text (in the native language of each language), as flag icons, or as both. Flag icons can be confusing and sometimes misleading so I recommend the text option.

The plugin detects the browser's preferred language and shows the "Translate" button in that language if available. That language is listed first in the popup. The tooltip for each language is also translated into the browser's preferred language. If the browser's preferred language isn't English a "Translate" button is shown on the Administration Panel that translates the panel. See <a href="http://www.w3.org/International/questions/qa-lang-priorities">this page</a> for more information about setting your browser's preferred language.

The source code hosts on github.com https://github.com/leohacker/WPPlugin-AdobeMachineTranslator.

We inherit the functionality design from Google AJAX Translation. Microsoft AJAX Translation is forked from Google AJAX Translation.

* <a herf="http://wordpress.org/plugins/google-ajax-translation/">Google AJAX Translation</a>
* <a herf="http://wordpress.org/plugins/microsoft-ajax-translation/">Microsoft AJAX Translation</a>

== Features ==
* **38 languages supported.** Powered by Microsoft's state-of-the-art statistical machine translation system.
* **Detects your source language automatically.** If your source text changes to more than one language it may get confused.
* **Detects visitor's language automatically.** Show the translate button in your readers' prefered language according to their broswer UA.
* **AJAX translation.** Better user experience as no refresh is needed.
* **On demand translation.** The plugin can translate just the content of the post, and full page translation is also supported.
* **Flexible to exclude certain posts and pages.** Even a section of a page can be excluded from being translated by a jQuery selector.

== Installation ==

1. Get the plugin archive.
2. Upload the .zip file in Plugins > Add New page, or expand the zip file then upload the `adobe-machine-translator` folder into `wp-content/plugins/` directory.
3. Activate the plugin in Plugins > Installed Plugins page.
4. Setup the options in Settings, especially the languages.
5. Enjoy it.

== Frequently Asked Questions ==

TODO: read and update after complete the features.

= Can I customize the position of Translate button? =

Yes. You can position the "Translate" button anywhere within the WordPress loop as shown below:

<pre>
&lt;?php
if( method_exists( $MicrosoftTranslation, 'microsoft_ajax_translate_button' ) ) {
    $MicrosoftTranslation -> microsoft_ajax_translate_button();
}
?&gt;
</pre>

= Can I translate the whole page? =

Yes. Just click the "powered by bing" image on the translate popup, and your reader will get a full translated website.

= Why is the quality of the translation not as good as I would like it to be? =

You should understand that the translation your reader sees is raw machine translation. Currently, it still requires human skills to translate sentences without errors.

= Translation not working? =

1. Have you filled in your AppID in the settings page?
2. There are so many visitors requesting translation that Microsoft suspended your AppID.
3. This plugin automatically uses the jQuery library supplied by your WordPress installation. If your theme or another plugin has another copy of jQuery hard coded into it this plugin may not work.
4. Ask in the forums.

= Operation and Customizing =

* Google Ajax Translation automatically detects your source language. If your source text changes to more than one language it can get confused.
* Clicking the "powered by Google" link will take you to a full-page Google translation in your browser's preferred language. (This service will refuse to translate a page into the same language though, e.g. English to English.)
* If your theme doesn't have a unique id for each post the plugin will fall back to translating just the content of the post but not the title, date, author, tags, etc. Try the plugin with the default theme (Kubrick) to see how it should work. If you need help modifying your theme just ask in the forums.
* The CSS background-color of the popup can be specified in the format #5AF or #55AAFF or it can be copied from the `body` of the page.
* To exclude certain posts and pages from displaying the "Translate" button put the post and page ID numbers into the field marked "Exclude posts and pages", for example 4, 5, 21. If you use permalinks you may not know your post and page ID's. The post or page ID is the number at the end of the URL when editing a post or page.
* To exclude a section of a page from being translated enter a jQuery selector (which works just like a CSS selector) in the field marked "Do not translate". For example enter `code` to exclude the HTML `code` tag, enter `.notranslate` to exclude any element where `class="notranslate"`, or enter `code, .notranslate` to exclude both. See <a href="http://api.jquery.com/category/selectors/basic-css-selectors/">this page</a> for more information or ask any questions in the forums here.
* Deleting the plugin from the Administration Panel (Plugins > Installed) also deletes the options from the wp_options table in the database.
* Most formatting, font, color, etc. changes can be made in `google-ajax-translation.css` or you can override them with your own CSS file
* The included ajax throbber is black on a white background. You can make your own at <a href="http://www.ajaxload.info/">http://www.ajaxload.info/</a>. 16 by 16 pixels works best.

= Code =

* This plugin uses the <a href="http://code.google.com/apis/ajaxlanguage/">Google AJAX Language API</a> and the <a href="http://code.google.com/p/jquery-translate/">jquery-translate plugin</a>.
* The "[" and "]" characters in the "Translate" button can be changed in the variables `$before_translate` and `$after_translate`
* The `google-ajax-translation.js` file is included for reference. It is minified and appended to the file `jquery.translate-1.4.1.min.js`
* This plugin automatically uses the jQuery library supplied by your WordPress installation (version 1.3.2 as of April 2010). If your theme or another plugin has another copy of jQuery hard coded into it this plugin may not work.
* This plugin places the popup and JavaScript into the footer with the wp_footer() function. If your theme doesn't have this function it will not work. See the `footer.php` file in the default theme to see what it should look like.
* If you customize your theme you can position the "Translate" button anywhere within the WordPress loop as shown below:

<pre>
&lt;?php
if( method_exists( $GoogleTranslation, 'google_ajax_translate_button' ) ) {
    $GoogleTranslation -> google_ajax_translate_button();
}
?&gt;
</pre>


== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Support ==

TODO:
put the contact info here.

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`

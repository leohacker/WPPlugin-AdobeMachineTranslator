<?php
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

$languages_mapping = array(
    'ar' => 'ar_AE',
    'bg' => 'bg_BG',
    'ca' => 'ca_ES',
    'zh-CHS' => 'zh_CN',
    'zh-CHT' => 'zh_TW',
    'cs' => 'cs_CZ',
    'da' => 'da_DK',
    'nl' => 'nl_NL',
    'en' => 'en_US',
    'et' => 'et_EE',
    'fi' => 'fi_FI',
    'fr' => 'fr_FR',
    'de' => 'de_DE',
    'el' => 'el_GR',
    'ht' => 'ht_HT',
    'he' => 'he_IL',
    'hi' => 'hi_IN',
    'hu' => 'hu_HU',
    'id' => 'id_ID',
    'it' => 'it_IT',
    'ja' => 'ja_JP',
    'ko' => 'ko_KR',
    'lv' => 'lv_LV',
    'lt' => 'lt_LT',
    'no' => 'no_NO',
    'pl' => 'pl_PL',
    'pt' => 'pt_BR',
    'ro' => 'ro_RO',
    'ru' => 'ru_RU',
    'sk' => 'sk_SK',
    'sl' => 'sl_SI',
    'es' => 'es_ES',
    'sv' => 'sv_SE',
    'th' => 'th_TH',
    'tr' => 'tr_TR',
    'uk' => 'uk_UA',
    'vi' => 'vi_VN',
);

$languages_English = array(
    'ar' => 'Arabic',
    'bg' => 'Bulgarian',
    'ca' => 'Catalan',
    'zh-CHS' => 'Chinese (Simplified)',
    'zh-CHT' => 'Chinese (Traditional)',
    'cs' => 'Czech',
    'da' => 'Danish',
    'nl' => 'Dutch',
    'en' => 'English',
    'et' => 'Estonian',
    'fi' => 'Finnish',
    'fr' => 'French',
    'de' => 'German',
    'el' => 'Greek',
    'ht' => 'Haitian Creole',
    'he' => 'Hebrew',
    'hi' => 'Hindi',
    'hu' => 'Hungarian',
    'id' => 'Indonesian',
    'it' => 'Italian',
    'ja' => 'Japanese',
    'ko' => 'Korean',
    'lv' => 'Latvian',
    'lt' => 'Lithuanian',
    'no' => 'Norwegian',
    'pl' => 'Polish',
    'pt' => 'Portuguese',
    'ro' => 'Romanian',
    'ru' => 'Russian',
    'sk' => 'Slovak',
    'sl' => 'Slovenian',
    'es' => 'Spanish',
    'sv' => 'Swedish',
    'th' => 'Thai',
    'tr' => 'Turkish',
    'uk' => 'Ukrainian',
    'vi' => 'Vietnamese',
);

$target_languages = array(
    'ar',     // Arabic
    'bg',     // Bulgarian
    'ca',     // Catalan
    'cs',     // Czech
    'da',     // Danish
    'de',     // German
    'el',     // Greek
    'en',     // English
    'et',     // Estonian
    'fa',     // Persian
    'fi',     // Finnish
    'fr',     // French
    'ga',     // Irish
    'gl',     // Galician
    'he',     // Hebrew
    'hi',     // Hindi
    'hr',     // Croatian
    'hu',     // Hungarian
    'id',     // Indonesian
    'is',     // Icelandic
    'it',     // Italian
    'ja',     // Japanese
    'ko',     // Korean
    'lt',     // Lithuanian
    'lv',     // Latvian
    'mk',     // Macedonian
    'ms',     // Malay
    'mt',     // Maltese
    'nl',     // Dutch
    'no',     // Norwegian
    'pl',     // Polish
    'pt',     // Portuguese
    'ro',     // Romanian
    'ru',     // Russian
    'sk',     // Slovak
    'sl',     // Slovenian
    'sq',     // Albanian
    'sr',     // Serbian
    'sv',     // Swedish
    'sw',     // Swahili
    'th',     // Thai
    'tl',     // Filipino
    'tr',     // Turkish
    'uk',     // Ukrainian
    'vi',     // Vietnamese
    'yi',     // Yiddish
    'zh-CHS',  // Chinese (Simplified)
    'zh-CHT'   // Chinese (Traditional)
);

$languages_localized = array(
    'af' => 'Afrikaans',
    'ar' => 'العربية',
    'be' => 'Беларуская',
    'bg' => 'български',
    'ca' => 'català',
    'cs' => 'česky',
    'cy' => 'Cymraeg',
    'da' => 'dansk',
    'de' => 'Deutsch',
    'el' => 'ελληνική',
    'en' => 'English',
    'es' => 'español',
    'et' => 'eesti',
    'fa' => 'فارسی',
    'fi' => 'suomi',
    'fr' => 'français',
    'ga' => 'Gaeilge',
    'gl' => 'galego',
    'he' => 'עברית',
    'hi' => 'हिन्दी',
    'hr' => 'hrvatski',
    'hu' => 'magyar',
    'id' => 'bahasa Indonesia',
    'is' => 'íslenska',
    'it' => 'italiano',
    'ja' => '日本語',
    'ko' => '한국어',
    'lt' => 'lietuvių',
    'lv' => 'latviešu',
    'mk' => 'македонски',
    'ms' => 'bahasa Melayu',
    'mt' => 'Malti',
    'nl' => 'Nederlands',
    'no' => 'norsk',
    'pl' => 'polski',
    'pt' => 'português',
    'ro' => 'română',
    'ru' => 'русский',
    'sk' => 'slovenčina',
    'sl' => 'slovenščina',
    'sq' => 'shqipe',
    'sr' => 'српски',
    'sv' => 'svenska',
    'sw' => 'Kiswahili',
    'th' => 'ภาษาไทย',
    'tl' => 'Filipino',
    'tr' => 'Türkçe',
    'uk' => 'українська',
    'vi' => 'tiếng Việt',
    'yi' => 'ייִדיש',
    'zh-CHS' => '中文 (简体)',
    'zh-CHT' => '中文 (繁體)'
);

$translate_message = array(
    'af' => 'Vertaal',
    'ar' => 'ترجمة',
    'be' => 'Перакладаць',
    'bg' => 'Преводач',
    'ca' => 'Traductor',
    'cs' => 'Překladač',
    'cy' => 'Cyfieithu',
    'da' => 'Oversæt',
    'de' => 'Übersetzung',
    'el' => 'Μετάφραση',
    'en' => 'Translate',
    'es' => 'Traductor',
    'et' => 'Tõlkima',
    'fa' => 'ترجمه',
    'fi' => 'Kääntäjä',
    'fr' => 'Traduction',
    'ga' => 'Aistrigh',
    'gl' => 'Traducir',
    'he' => 'תרגם',
    'hi' => 'अनुवाद करें',
    'hr' => 'Prevoditelj',
    'hu' => 'Fordítás',
    'id' => 'Menerjemahkan',
    'is' => 'Þýða',
    'it' => 'Traduttore',
    'ja' => '翻訳',
    'ko' => '번역',
    'lt' => 'Versti',
    'lv' => 'Tulkotājs',
    'mk' => 'Преведува',
    'ms' => 'Menerjemahkan',
    'mt' => 'Traduċi',
    'nl' => 'Vertaal',
    'no' => 'Oversetter',
    'pl' => 'Tłumacz',
    'pt' => 'Tradutor',
    'ro' => 'Traducere',
    'ru' => 'Переводчик',
    'sk' => 'Prekladač',
    'sl' => 'Prevajalnik',
    'sq' => 'Përkthej',
    'sr' => 'преводилац',
    'sv' => 'Översätt',
    'sw' => 'Tafsiri',
    'th' => 'แปล',
    'tl' => 'Pagsasalin',
    'tr' => 'Tercüme etmek',
    'uk' => 'Перекладач',
    'vi' => 'Dịch',
    'yi' => 'זעץ',
    'zh-CHS' => '翻译',
    'zh-CHT' => '翻譯',
);
?>
<?php
global $wpdb;

define('RD_DEBUG', $_SERVER['REMOTE_ADDR'] == '127.0.0.1');

$rdetector_config['plugin_name'] = 'Referrer Detector';
$rdetector_config['text_domain'] = 'referrer_detector';
$rdetector_config['plugin_dir'] = get_option('siteurl') . '/wp-content/plugins/referrer-detector/';

$rdetector_config['entries_table_name'] = "{$wpdb->prefix}referrer_detector";
$rdetector_config['stats_table_name'] = "{$wpdb->prefix}referrer_detector_stats";
$rdetector_config['db_version'] = '3.0';

$rdetector_config['wpadmin_index'] = get_option('siteurl') . '/wp-admin/index.php';
$rdetector_config['help_url'] = 'http://www.phoenixheart.net/wp-plugins/referrer_detector';

$rdetector_config['custom_tag'] = '{referrer_detector}';
$rdetector_config['nonce_action'] = md5($rdetector_config['plugin_name'] . $rdetector_config['wpadmin_index']);

$rdetector_config['options'] = array(
    'rdetector_add_to_every_post' => array(
        'type'            => 'select',
        'label'         => __('Automatically display welcome message on every post? *', $rdetector_text_domain),
        'options'        => array ('yes', 'no'),
        'default_value'    => 'yes',
    ),
    'rdetector_add_to_every_page' => array(
        'type'            => 'select',
        'label'         => __('Automatically display welcome message on every page? *', $rdetector_text_domain),
        'options'        => array ('yes', 'no'),
        'default_value'    => 'no',
    ),
    'rdetector_message_position' => array(
        'type'            => 'select',
        'label'         => __('The position / style of the welcome message (related to page/post content)', $rdetector_text_domain),
        'options'        => array ('before', 'after', 'both', 'lightbox'),
        'default_value'    => 'before',
    ),
    'rdetector_close_icon'     => array(
        'type'            => 'select',
        'label'         => __('Display an icon to let user close the message? **', $rdetector_text_domain),
        'options'        => array ('yes', 'no'),
        'default_value'    => 'yes',
    ),
    'rdetector_related_posts'     => array(
        'type'            => 'select',
        'label'         => __('If available, maximum how many related posts to show? (0 to disable)', $rdetector_text_domain),
        'options'        => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
        'default_value'    => 5,
    ),
    'rdetector_powered'     => array(
        'type'            => 'select',
        'label'         => __('Support this plugin - display a *tiny* "Powered by Referrer Detector" link?', $rdetector_text_domain),
        'options'        => array ('yes', 'no'),
        'default_value'    => 'yes',
    ),
);

$rdetector_config['countries'] = array(
	'AD' => 'Andorra',
	'AE' => 'United Arab Emirates',
	'AF' => 'Afghanistan',
	'AG' => 'Antigua and Barbuda',
	'AI' => 'Anguilla',
	'AL' => 'Albania',
	'AM' => 'Armenia',
	'AN' => 'Netherlands Antilles',
	'AO' => 'Angola',
	'AP' => 'Asia/Pacific Region',
	'AQ' => 'Antarctica',
	'AR' => 'Argentina',
	'AS' => 'American Samoa',
	'AT' => 'Austria',
	'AU' => 'Australia',
	'AW' => 'Aruba',
	'AX' => 'Aland Islands',
	'AZ' => 'Azerbaijan',
	'BA' => 'Bosnia and Herzegovina',
	'BB' => 'Barbados',
	'BD' => 'Bangladesh',
	'BE' => 'Belgium',
	'BF' => 'Burkina Faso',
	'BG' => 'Bulgaria',
	'BH' => 'Bahrain',
	'BI' => 'Burundi',
	'BJ' => 'Benin',
	'BM' => 'Bermuda',
	'BN' => 'Brunei Darussalam',
	'BO' => 'Bolivia',
	'BR' => 'Brazil',
	'BS' => 'Bahamas',
	'BT' => 'Bhutan',
	'BV' => 'Bouvet Island',
	'BW' => 'Botswana',
	'BY' => 'Belarus',
	'BZ' => 'Belize',
	'CA' => 'Canada',
	'CC' => 'Cocos (Keeling) Islands',
	'CD' => 'Congo, The Democratic Republic of the',
	'CF' => 'Central African Republic',
	'CG' => 'Congo',
	'CH' => 'Switzerland',
	'CI' => 'Cote d Ivoire',
	'CK' => 'Cook Islands',
	'CL' => 'Chile',
	'CM' => 'Cameroon',
	'CN' => 'China',
	'CO' => 'Colombia',
	'CR' => 'Costa Rica',
	'CU' => 'Cuba',
	'CV' => 'Cape Verde',
	'CX' => 'Christmas Island',
	'CY' => 'Cyprus',
	'CZ' => 'Czech Republic',
	'DE' => 'Germany',
	'DJ' => 'Djibouti',
	'DK' => 'Denmark',
	'DM' => 'Dominica',
	'DO' => 'Dominican Republic',
	'DZ' => 'Algeria',
	'EC' => 'Ecuador',
	'EE' => 'Estonia',
	'EG' => 'Egypt',
	'EH' => 'Western Sahara',
	'ER' => 'Eritrea',
	'ES' => 'Spain',
	'ET' => 'Ethiopia',
	'EU' => 'Europe',
	'FI' => 'Finland',
	'FJ' => 'Fiji',
	'FK' => 'Falkland Islands (Malvinas)',
	'FM' => 'Micronesia, Federated States of',
	'FO' => 'Faroe Islands',
	'FR' => 'France',
	'GA' => 'Gabon',
	'GB' => 'United Kingdom',
	'GD' => 'Grenada',
	'GE' => 'Georgia',
	'GF' => 'French Guiana',
	'GG' => 'Guernsey',
	'GH' => 'Ghana',
	'GI' => 'Gibraltar',
	'GL' => 'Greenland',
	'GM' => 'Gambia',
	'GN' => 'Guinea',
	'GP' => 'Guadeloupe',
	'GQ' => 'Equatorial Guinea',
	'GR' => 'Greece',
	'GS' => 'South Georgia and the South Sandwich Islands',
	'GT' => 'Guatemala',
	'GU' => 'Guam',
	'GW' => 'Guinea-Bissau',
	'GY' => 'Guyana',
	'HK' => 'Hong Kong',
	'HM' => 'Heard Island and McDonald Islands',
	'HN' => 'Honduras',
	'HR' => 'Croatia',
	'HT' => 'Haiti',
	'HU' => 'Hungary',
	'ID' => 'Indonesia',
	'IE' => 'Ireland',
	'IL' => 'Israel',
	'IM' => 'Isle of Man',
	'IN' => 'India',
	'IO' => 'British Indian Ocean Territory',
	'IQ' => 'Iraq',
	'IR' => 'Iran, Islamic Republic of',
	'IS' => 'Iceland',
	'IT' => 'Italy',
	'JE' => 'Jersey',
	'JM' => 'Jamaica',
	'JO' => 'Jordan',
	'JP' => 'Japan',
	'KE' => 'Kenya',
	'KG' => 'Kyrgyzstan',
	'KH' => 'Cambodia',
	'KI' => 'Kiribati',
	'KM' => 'Comoros',
	'KN' => 'Saint Kitts and Nevis',
	'KP' => "Korea, Democratic People\'s Republic of",
	'KR' => 'Korea, Republic of',
	'KW' => 'Kuwait',
	'KY' => 'Cayman Islands',
	'KZ' => 'Kazakhstan',
	'LA' => "Lao People's Democratic Republic",
	'LB' => 'Lebanon',
	'LC' => 'Saint Lucia',
	'LI' => 'Liechtenstein',
	'LK' => 'Sri Lanka',
	'LR' => 'Liberia',
	'LS' => 'Lesotho',
	'LT' => 'Lithuania',
	'LU' => 'Luxembourg',
	'LV' => 'Latvia',
	'LY' => 'Libyan Arab Jamahiriya',
	'MA' => 'Morocco',
	'MC' => 'Monaco',
	'MD' => 'Moldova, Republic of',
	'ME' => 'Montenegro',
	'MG' => 'Madagascar',
	'MH' => 'Marshall Islands',
	'MK' => 'Macedonia',
	'ML' => 'Mali',
	'MM' => 'Myanmar',
	'MN' => 'Mongolia',
	'MO' => 'Macao',
	'MP' => 'Northern Mariana Islands',
	'MQ' => 'Martinique',
	'MR' => 'Mauritania',
	'MS' => 'Montserrat',
	'MT' => 'Malta',
	'MU' => 'Mauritius',
	'MV' => 'Maldives',
	'MW' => 'Malawi',
	'MX' => 'Mexico',
	'MY' => 'Malaysia',
	'MZ' => 'Mozambique',
	'NA' => 'Namibia',
	'NC' => 'New Caledonia',
	'NE' => 'Niger',
	'NF' => 'Norfolk Island',
	'NG' => 'Nigeria',
	'NI' => 'Nicaragua',
	'NL' => 'Netherlands',
	'NO' => 'Norway',
	'NP' => 'Nepal',
	'NR' => 'Nauru',
	'NU' => 'Niue',
	'NZ' => 'New Zealand',
	'OM' => 'Oman',
	'PA' => 'Panama',
	'PE' => 'Peru',
	'PF' => 'French Polynesia',
	'PG' => 'Papua New Guinea',
	'PH' => 'Philippines',
	'PK' => 'Pakistan',
	'PL' => 'Poland',
	'PM' => 'Saint Pierre and Miquelon',
	'PN' => 'Pitcairn',
	'PR' => 'Puerto Rico',
	'PS' => 'Palestinian Territory',
	'PT' => 'Portugal',
	'PW' => 'Palau',
	'PY' => 'Paraguay',
	'QA' => 'Qatar',
	'RE' => 'Reunion',
	'RO' => 'Romania',
	'RS' => 'Serbia',
	'RU' => 'Russian Federation',
	'RW' => 'Rwanda',
	'SA' => 'Saudi Arabia',
	'SB' => 'Solomon Islands',
	'SC' => 'Seychelles',
	'SD' => 'Sudan',
	'SE' => 'Sweden',
	'SG' => 'Singapore',
	'SH' => 'Saint Helena',
	'SI' => 'Slovenia',
	'SJ' => 'Svalbard and Jan Mayen',
	'SK' => 'Slovakia',
	'SL' => 'Sierra Leone',
	'SM' => 'San Marino',
	'SN' => 'Senegal',
	'SO' => 'Somalia',
	'SR' => 'Suriname',
	'ST' => 'Sao Tome and Principe',
	'SV' => 'El Salvador',
	'SY' => 'Syrian Arab Republic',
	'SZ' => 'Swaziland',
	'TC' => 'Turks and Caicos Islands',
	'TD' => 'Chad',
	'TF' => 'French Southern Territories',
	'TG' => 'Togo',
	'TH' => 'Thailand',
	'TJ' => 'Tajikistan',
	'TK' => 'Tokelau',
	'TL' => 'Timor-Leste',
	'TM' => 'Turkmenistan',
	'TN' => 'Tunisia',
	'TO' => 'Tonga',
	'TR' => 'Turkey',
	'TT' => 'Trinidad and Tobago',
	'TV' => 'Tuvalu',
	'TW' => 'Taiwan',
	'TZ' => 'Tanzania, United Republic of',
	'UA' => 'Ukraine',
	'UG' => 'Uganda',
	'UM' => 'United States Minor Outlying Islands',
	'US' => 'United States',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VA' => 'Holy See (Vatican City State)',
	'VC' => 'Saint Vincent and the Grenadines',
	'VE' => 'Venezuela',
	'VG' => 'Virgin Islands, British',
	'VI' => 'Virgin Islands, U.S.',
	'VN' => 'Vietnam',
	'VU' => 'Vanuatu',
	'WF' => 'Wallis and Futuna',
	'WS' => 'Samoa',
	'YE' => 'Yemen',
	'YT' => 'Mayotte',
	'ZA' => 'South Africa',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe'
);

asort($rdetector_config['countries']);

$rdetector_config['add_help'] = '<p>To add or edit an entry, you&#8217;re provided with a simple form with 5 fields.</p>
<ul>
    <li><strong>Name</strong>: The name of the entry. Digg, Google, Yahoo&#8230; are good names.</li>
    <li>
        <p><strong>URL</strong>: The URL of the site where your users come from. For this to work, the URL must not contain those prefix and suffix like &#8220;http:/www&#8221; and &#8220;/?q=etc.&#8221;. To be clear, it should be &#8220;digg.com&#8221; instead of &#8220;http://digg.com&#8221;, and &#8220;google.com&#8221; instead of &#8220;http://www.google.com/search?hl=en&amp;q=some+keywords&#8221;. Furthermore, the domain and subdomain are treated differently, which means different greeting boxes (if any) will be shown for &#8220;yahoo.com&#8221; and &#8220;search.yahoo.com&#8221; users.</p>
        <p>[IMPORTANT] Version 3.2 introduces two new features:</p>
        <ul>
            <li>The asterisk (*) character is supported as a wildcard. For example, google.* matches ALL Google localized domains too.</li>
            <li>Several related URLs can be combined into one, seperated by commas. For example a URL "del.icio.us,delicious.com" matches both domains.</li>
        </ul>
    </li>
    <li>
        <p><strong>Default welcome message</strong>: The default greetings for this entry. These HTML tags are allowed: <code>&lt;div&gt;&lt;h1&gt;&lt;h2&gt;&lt;h3&gt;&lt;p&gt;&lt;span&gt;&lt;a&gt;&lt;ul&gt;&lt;ol&gt;&lt;li&gt;&lt;hr&gt;&lt;br&gt;&lt;table&gt;&lt;thead&gt;&lt;tbody&gt;&lt;tfoot&gt;&lt;tr&gt;&lt;td&gt;&lt;th&gt;&lt;strong&gt;&lt;em&gt;</code> Also, these tags may be used:</p>
        <ul>
            <li><strong>{url} </strong>- Full URL to your WordPress post, for example http://yousite.com/2008/10/my-first-post. Very handy if you want to ask your users to digg your post.</li>
            <li><strong>{title} </strong>- Title of the post, for example My First Post.</li>
            <li><strong>{link}</strong> - Link to the post, for example <a href="http://yousite.com/2008/10/my-first-post">My First Post</a>. It&#8217;s just an combination of {url} and {title}.</li>
            <li><strong>{categorynames}</strong> - Names of the categories under which the post is listed, separated by a comma. Example: Cats, Dogs, Pets.</li>
            <li><strong>{categorylinks}</strong> - Links to the categories under which the post is listed, separated by a comma. Example: <a href="http://yousite.com/categories/cats">Cats</a>, <a href="http://yousite.com/categories/dogs">Dogs</a>, <a href="http://yousite.com/categories/pets">Pets</a></li>
            <li><strong>{authorname}</strong> - Name of the author of the post, for example Mr. Cool.</li>
            <li><strong>{authorurl}</strong> - URL of the author, for example http://mrcool.com.</li>
            <li><strong>{authorlink}</strong> - Link to the author, for example <a href="http://mrcool.com">Mr. Cool</a></li>
            <li><strong>{search-terms}</strong> - The search terms that user uses to find your blog, <strike>seperated by commas</strike>.</li>
        </ul>
		<div style="background: #feffbf; padding: 4px">
			<p>[IMPORTANT] Version 4.0 introduces the ability to add localized welcome messages. To add one, follow these step:</p>
			<ol>
				<li>Click "Add" button</li>
				<li>From the select box on the left, choose the country(s) to apply the localize message</li>
				<li>Type in the message on the right. The rules and tags are exactly the same with the default message.</li>
				<li>Now if the user is visiting your site from the selected country, s/he will be welcomed with the localized message instead of the default one.</li>
			</ol>
			<p>Note that the country is automatically detected using HostIP service, so I am not taking responsibility on its accuracy.</p>
		</div>
    </li>
    <li><strong>Icon</strong>: The URL of the icon to display beside the greeting text. If this is left blank, a default RSS icon will be shown. Though any image will do, it&#8217;s recommended to use a small one (48&#215;48 px should be perfect).</li>
    <li><strong>This entry is active</strong>: The name says it all. Naturally, this should be checked.</li>
</ul>';

$rdetector_config['generic_help'] = '<p>Those options are used to specify if you want to show the greeting box on every post,
page, before or after&#8230; Notice that if you can include a custom tag <strong>{referrer_detector}</strong> anywhere
in your post to show the welcome text. If the visitor didn&#8217;t come from any of the referrers,
don&#8217;t worry, the plugin is smart enough to hide that ugly tag from your post.</p>
<p>Starting from version 2.0, a template tag <em>&lt;?php if (function_exists(\'referrer_detector\')) referrer_detector(); ?&gt;</em>
can be placed somewhere in the theme files (most likely index.php) to display a greet box too.</p>';

$rdetector_config['exclude_help'] = '<p>Here you can specify a list of urls (comma-seperated). If the visitor is comming from one of these urls, the welcome box will not be show. <br />
For example, by adding <em>google.com/reader</em> into the list, you make sure that the readers won\'t see the box,
which is good since they should have registered to your feed already.</p>
<p>Starting from version 3.2, as of referrer URL, you can use the wildcard character (*) on this field too: <em>google.*/reader</em> matches the readers of all localized Google domains.</p>';

$rdetector_config['backup_help'] = '<p>Here you can backup almost everything: entries, excluded urls, and options. This especially comes in handy if you have many WordPress powered blogs
and want all to share the same Referrer Detector setup - just make a backup here and restore it there.</p>';

$rdetector_config['support_text'] = sprintf('<p>I spent countless hours to develop and improve this plugin - free, as in beer and freedom.
So if by any chance you find it any useful please consider:</p>
<ul>
<li>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</li>
<li>Blog or <a href="http://twitter.com/home/?status=RT+%%40tweetmeme+Referrer+Detector+by+%%40Phoenixheart+http%%3A%%2F%%2Fbit.ly%%2F178Djf" target="_blank">Tweet about it</a>.
You can also <a href="http://twitter.com/Phoenixheart" target="_blank">follow me on Twitter</a>.</li>
<li>Help with translating the greetings into your own language.</li>
<li>Give this plugin a good rating on <a href="http://wordpress.org/extend/plugins/referrer-detector/" target="_blank">WordPress Codex</a>.</li>
<li>Check out <a href="http://www.phoenixheart.net/wp-plugins/" target="_blank">my other plugins</a>.</li>
<li><a href="http://feeds.feedburner.com/phoenixheart" target="_blank">Subscribe</a> to my RSS.</li>
<li>Send me at phoenixheart (Gmail) an email telling that you like my plugin ;) .</li>
</ul>
<p>Of course, none of these is mandatory - it is totally up to you. It\'s fine if you are not in the mood!</p>
<p>I am reachable at<p>
<p>
<a href="http://twitter.com/Phoenixheart" target="_blank"><img src="%simages/icons/twitter.png" alt="Twitter" /></a>
<a href="http://digg.com/users/Phoenixheart" target="_blank"><img src="%simages/icons/digg.png" alt="Digg" /></a>
<a href="http://phoenixheart.stumbleupon.com/public/" target="_blank"><img src="%simages/icons/stumbleupon.png" alt="Twitter" /></a>
<a href="http://feeds.feedburner.com/phoenixheart" target="_blank"><img src="%simages/icons/rss.png" alt="Twitter" /></a>
</p>', $rdetector_config['plugin_dir'], $rdetector_config['plugin_dir'], $rdetector_config['plugin_dir'], $rdetector_config['plugin_dir']);









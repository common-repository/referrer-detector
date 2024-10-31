<?php
class ReferrerDetector_Entry
{
    var $table_name;
    var $config;

    function ReferrerDetector_Entry()
    {

        $this->__construct();
    }

    function __construct()
    {
        global $rdetector_config;
        $this->config = $rdetector_config;
    }

    function create_table()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;

        if ($wpdb->get_var("SHOW TABLES LIKE '{$this->config['entries_table_name']}'") != $this->config['entries_table_name'])
        {
            $q = "CREATE TABLE {$this->config['entries_table_name']} (
              `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
              `name` varchar(50) NOT NULL,
              `url` varchar(255) NOT NULL,
              `icon` varchar(255) NOT NULL,
              `welcome_text` text NOT NULL,
              `enabled` tinyint(4) NOT NULL default '1',
              PRIMARY KEY (`id`),
              KEY `url` (`url`)
            );";

            dbDelta($q);
            $this->add_default();
        }
    }

    /**
    * @desc     Adds default entries
    */
    function add_default()
    {
        global $wpdb;

        $rss_link = get_bloginfo('rss2_url');

        $sql = "INSERT INTO `{$this->config['entries_table_name']}` (`name`, `url`, `icon`, `welcome_text`, `enabled`) VALUES
        ('Google', 'google.*', '{$this->config['plugin_dir']}images/icons/google.png', 'Welcome <strong>Googler</strong>! If you find this page useful, why not <a href=\"$rss_link\">subscribe to the RSS feed</a> for more interesting posts in the future?', 1),
        ('Bing', 'bing.*', '{$this->config['plugin_dir']}images/icons/bing.png', '<strong>Bingo</strong>, and welcome! If you find this page useful, why not <a href=\"$rss_link\">subscribe to the RSS feed</a> for more interesting posts in the future?', 1),
        ('BuySellAds', 'buysellads.com', '{$this->config['plugin_dir']}images/icons/buysellads.png', 'Welcome! If you are a BuySellAds advertiser, you have come to the right place!', 1),
        ('Digg', 'digg.com', '{$this->config['plugin_dir']}images/icons/digg.png', 'Hello fellow <strong>Digger</strong>! If you find this story useful, please <a href=\"http://digg.com/submit?url={url}\">digg it</a>!', 1),
        ('StumbleUpon', 'stumbleupon.com', '{$this->config['plugin_dir']}images/icons/stumbleupon.png', 'Hello fellow <strong>Stumbler</strong>! Don\'t forget to <a href=\"http://www.stumbleupon.com/submit?url={url}\" rel=\"nofollow\">give me a thumbs up</a> if you like this page!', 1),
        ('Technograti', 'technorati.com', '{$this->config['plugin_dir']}images/icons/technorati.png', 'Hello fellow <strong>Technorati</strong> user! Don\'t forget to <a href=\"http://technorati.com/faves?add={url}\" rel=\"nofollow\">favorite this blog</a> if you like it!', 1),
        ('Twitter', 'twitter.com', '{$this->config['plugin_dir']}images/icons/twitter.png', 'Hello fellow <strong>Twitter</strong> user! Don\'t forget to <a href=\"http://twitthis.com/twit?url={url}\" rel=\"nofollow\">Twit this post</a> if you like it, or <a href=\"http://twitter.com/\" rel=\"nofollow\">follow me</a> on Twitter if you find me interesting.', 1),
        ('Del.icio.us', 'del.icio.us,delicious.com', '{$this->config['plugin_dir']}images/icons/delicious.png', 'Hello fellow <strong>Delicious</strong> user! Feel free to <a href=\"http://delicious.com/post?url={url}\" rel=\"nofollow\">bookmark this page</a> for future reference if you like it!', 1),
        ('Yahoo', '*.yahoo.*', '{$this->config['plugin_dir']}images/icons/yahoo.png', 'Welcome fellow <strong>Yahooligan</strong>! If you find this page useful, you might want to <a href=\"$rss_link\" rel=\"nofollow\">subscribe to the RSS feed</a> for updates on this topic.', 1),
        ('Live', 'search.live.com', '{$this->config['plugin_dir']}images/icons/live.png', 'Welcome fellow <strong>Live Search</strong> user! If you find this page useful, you might want to <a href=\"$rss_link\" rel=\"nofollow\">subscribe to the RSS feed</a> for updates on this topic.', 1),
        ('Lifehacker', 'lifehacker.com', '', 'Hello fellow <strong>Lifehacker</strong> reader! If you find this page useful, you might want to <a href=\"$rss_link\" rel=\"nofollow\">subscribe to the RSS feed</a> for updates on this topic.', 1),
        ('TechCrunch', 'techcrunch.com', '{$this->config['plugin_dir']}images/icons/techcrunch.png', 'Hello fellow <strong>TechCrunch</strong> reader! If you find this page useful, you might want to <a href=\"$rss_link\" rel=\"nofollow\">subscribe to the RSS feed</a> for updates on this topic.', 1),
        ('9rules', '9rules.com', '{$this->config['plugin_dir']}images/icons/9rules.png', 'Hello fellow <strong>9rules</strong> reader! If you find this page useful, you might want to <a href=\"$rss_link\" rel=\"nofollow\">subscribe to the RSS feed</a> for updates on this topic.', 1),
        ('Gizmodo', 'gizmodo.com', '', 'Hello fellow <strong>Gizmodo</strong> reader! If you find this page useful, you might want to <a href=\"$rss_link\" rel=\"nofollow\">subscribe to the RSS feed</a> for updates on this topic.', 1),
        ('TechRadar', 'techradar.com', '', 'Hello fellow <strong>TechRadar</strong> reader! If you find this page useful, you might want to <a href=\"$rss_link\" rel=\"nofollow\">subscribe to the RSS feed</a> for updates on this topic.', 1),
        ('Reddit', 'reddit.com', '{$this->config['plugin_dir']}images/icons/reddit.png', 'Hello fellow <strong>Reddit</strong> user! If you find this story useful, please <a href=\"http://www.reddit.com/submit?url={url}\">vote it up</a>!', 1);";

        $results = $wpdb->query($sql);
    }

    function get_table()
    {
        global $wpdb;

        $sql = "SELECT * FROM {$this->config['entries_table_name']} ORDER BY `name`";

        $entries = $wpdb->get_results($sql);
        $table = sprintf('
        <table class="widefat" id="referrerDetectorEntries">
                <thead>
                    <tr>
                        <th class="check-column" scope="col"><input type="checkbox" autocomplete="off" /></th>
                        <th>%s</th>
                        <th>%s</th>
                        <th>%s</th>
                        <th>%s</th>
                        <th style="width: 200px; text-align: right">%s</th>
                    </tr>
                </thead>
                <tbody>',
            __('Name', $this->config['text_domain']),
            __('Url', $this->config['text_domain']),
            __('Icon', $this->config['text_domain']),
            __('Default Welcome Message', $this->config['text_domain']),
            __('Action', $this->config['text_domain']));

        foreach ($entries as $entry)
        {
        	if ($messages = unserialize(base64_decode($entry->welcome_text)))
        	{
        		$default_msg = $messages['default'];
        	}
        	else
        	{
        		$default_msg = $entry->welcome_text;
        	}
            $table .= $this->format_html($entry->id, $entry->name, $entry->url, $entry->icon, $default_msg, $entry->enabled == 1);
        }

        $table .= '
                </tbody>
            </table>';

        return $table;
    }

    function format_html($id, $name, $url, $icon, $welcome_text, $enabled)
    {
        return sprintf('
        <tr class="%s" id="entry_%s">
            <td><input name="checked[]" class="entry-check" value="%s" type="checkbox" autocomplete="off" /></td>
            <td>%s</td>
            <td>%s</td>
            <td><img src="%s" alt="" /></td>
            <td>%s</td>
            <td style="text-align: right">
                <a href="#" entry="%s" class="ajax" action="%s" >%s</a> |
                <a href="#" entry="%s" class="ajax" action="edit">%s</a> |
                <a href="#" entry="%s" class="ajax need_confirm" confirmtext="%s" action="delete">%s</a>
                <span id="loading_%s" style="display:none"><img src="%s/images/loading.gif" alt="Loading..." /></span>
            </td>
        </tr>%s',
        $enabled ? __('active', $this->config['text_domain']) : __('inactive', $this->config['text_domain']), $id,
        $id,
        stripslashes($name),
        $url,
        $icon ? $icon : "{$this->config['plugin_dir']}images/icons/default.png",
        stripslashes($welcome_text),
        $id, $enabled ? 'deactivate' : 'activate', $enabled ? __('Deactivate', $this->config['text_domain']) : __('Activate', $this->config['text_domain']),
        $id, __('Edit/View', $this->config['text_domain']),
        $id, __('Are you sure you want to delete this entry?', $this->config['text_domain']), __('Delete', $this->config['text_domain']),
        $id, $this->config['plugin_dir'],
        PHP_EOL);
    }

    function get_list_panel()
    {
        return sprintf('
        <div id="divListContainer">
        <div class="tablenav">
            <div class="alignleft actions">
                <select name="action" id="bulkSelect" autocomplete="off">
                    <option value="nope">Bulk Action</option>
                    <option value="bulk-activate">Activate</option>
                    <option value="bulk-deactivate">Deactivate</option>
                    <option value="bulk-merge">Merge</option>
                    <option value="bulk-delete">Delete</option>
                </select>
                <input class="button-secondary action" id="bulkSubmit" type="submit" value="Apply" autocomplete="off" />
            </div>
        </div>
        <div class="clear"></div>
            %s
        <p class="rdetector_restore"><a class="ajax need_confirm" confirmtext="%s" href="#" action="restore" entry="nope"><input type="button" class="button-secondary action" value="%s" /></a></p>
        <div id="loading_nope" style="display: none"><img src="%s/images/loading.gif" alt="Loading..." /></div>
    </div>',
        $this->get_table(),
        __('Warning: This will delete ALL of your custom entries (if any)! Are you sure?', $this->config['text_domain']),
        __('Restore default entries', $this->config['text_domain']),
        $this->config['plugin_dir']);
    }

    function get_add_panel()
    {
        return sprintf('
        <div id="divAddContainer">
            <p class="toggler"><span style="font-size:11px; font-weight:normal">%s</span></p>
            <div class="toggled help">%s</div>
            <form action="index.php" method="post" id="formReferrerDetector_Add" autocomplete="off">
                <p>
                    <label for="nameAdd">%s</label><br />
                    <input type="text" name="name" id="nameAdd" class="required" />
                </p>
                <p>
                    <label for="urlAdd">%s</label><br />
                    <input type="text" name="url" id="urlAdd" class="required" />
                </p>
                <p>
                    <label for="messageAdd">%s</label><br />
                    <textarea name="message" id="messageAdd" class="required"></textarea>
                </p>
				<fieldset>
					<legend>Localized Messages</legend>
					<div id="locMessagesAdd"></div>
					<button class="add_loc_msg_add button">Add</button>
				</fieldset>
                <p>
                    <label for="iconAdd">%s</label><br />
                    <input type="text" name="icon" id="iconAdd" /> (%s)
                </p>
                <p>
                    <input type="checkbox" name="enabled" id="enabledAdd" checked="checked" />
                    <label for="enabledAdd">%s</label>
                    <input type="hidden" name="rdetector_action" value="add" />
                </p>
                <div class="submit">
                    <input type="submit" value="%s" />
                </div>
            </form>
			<div id="locMessageTemplate" style="display: none">
				<div style="padding: 5px 0">
					%s
					<textarea name="loc_messages[]" style="float: left"></textarea>
					<button class="remove_loc_msg button" style="float: left">Remove</button>
					<br style="clear: both" />
				</div>
			</div>
            <div style="display: none" id="divLoadingAdd">
                <img src="%s/images/loading.gif" alt="Loading..." />
            </div>
        </div>
        ',
            __('Get help with this', $this->config['text_domain']),
            __($this->config['add_help'], $this->config['text_domain']),
            __('Name', $this->config['text_domain']),
            __('Url', $this->config['text_domain']),
            __('Default welcome message', $this->config['text_domain']),
            __('Icon', $this->config['text_domain']),
            __('Optional', $this->config['text_domain']),
            __('This entry is active', $this->config['text_domain']),
            __('Add this!', $this->config['text_domain']),
            ReferrerDetector::countries_select(),
            $this->config['plugin_dir']
        );
    }

    function get_edit_panel()
    {
        return sprintf('
        <div id="divEditContainer" style="display:none">
            <p id="editInstruction">%s</p>
            <p class="toggler"><span style="font-size:11px; font-weight:normal">%s</span></p>
            <div class="toggled help">%s</div>
            <form action="index.php" method="post" id="formReferrerDetector_Edit" autocomplete="off">
            </form>
            <div style="display:none" id="divLoadingEdit">
                <img src="%s/images/loading.gif" alt="Loading..." />
            </div>
        </div>',
            __('Click "View/Edit" on the entry list to edit.', $this->config['text_domain']),
            __('Get help with this', $this->config['text_domain']),
            __($this->config['add_help'], $this->config['text_domain']),
            $this->config['plugin_dir']
        );
    }

    function get_excluded_panel()
    {
        return sprintf('
            <div id="divExcluded">
                <p class="toggler"><span style="font-size:11px; font-weight:normal">%s</span></p>
                <div class="toggled help">%s</div>

                <form action="index.php" method="post" autocomplete="off" class="generic_form">
                    <p><textarea name="rdetector_excluded_urls">%s</textarea></p>
                    <div class="submit">
                        <input type="hidden" name="rdetector_action" value="exclude" />
                        <input type="submit" value="%s" />
                    </div>
                </form>
            </div>',
            __('Get help with this', $this->config['text_domain']),
            __($this->config['exclude_help'], $this->config['text_domain']),
            get_option('rdetector_excluded_urls'),
            __('Exclude these out!', $this->config['text_domain'])
        );
    }

    function add()
    {
    	// a little validation
        if (!$errors = $this->validate_post_data())
        {
            ReferrerDetector::send_fake_error();
        }

        global $wpdb;

        $messages = array(
        	'default'	=> ReferrerDetector::striptags($_POST['message']),
        );

        if (is_array($_POST['countries']))
        {
	        $countries = array_values($_POST['countries']);

	        for ($i = 0; $i < count($countries); ++$i)
	        {
	        	if (!$countries[$i]) continue;
	        	if (!trim($_POST['loc_messages'][$i]))continue;

	        	$messages['countries'][] = $countries[$i];
				$messages['loc_messages'][] = ReferrerDetector::striptags($_POST['loc_messages'][$i]);
	        }
        }

        $sql = $wpdb->prepare("INSERT INTO `{$this->config['entries_table_name']}`(`name`, `url`, `icon`, `welcome_text`, `enabled`) VALUES(%s, %s, %s, %s, %d)",
                $_POST['name'], $_POST['url'], $_POST['icon'], base64_encode(serialize($messages)), isset($_POST['enabled']));

        $wpdb->query($sql);

        echo $this->format_html($wpdb->insert_id, $_POST['name'], $_POST['url'], $_POST['icon'], $messages['default'], isset($_POST['enabled']));
    }

    function activate($is_activating, $bulk = false)
    {
        global $wpdb;

        if ($bulk)
        {
            $sql = sprintf('UPDATE `%s` SET `enabled`=%d WHERE `id` IN (%s)', $this->config['entries_table_name'], $is_activating ? 1 : 0, mysql_escape_string($_POST['ids']));
        }
        else
        {
            $sql = sprintf('UPDATE `%s` SET `enabled`=%d WHERE `id`=%d', $this->config['entries_table_name'], $is_activating ? 1 : 0, $_POST['id']);
        }

        $wpdb->query($sql);

        if (!mysql_affected_rows())
        {
            ReferrerDetector::send_fake_error();
        }

        // rdetector_generate_js_data();

        if ($bulk)
        {
            die ($this->get_table());
        }
        else
        {
            $is_activating ? _e('Deactivate', $this->config['text_domain']) : _e('Activate', $this->config['text_domain']);
        }
    }

    function edit($is_updating = false)
    {
		global $wpdb;

		$id = intval($_POST['id']);

		if (!$is_updating)
	    {
	        $sql = "SELECT * FROM `{$this->config['entries_table_name']}` WHERE `id`=$id";
	        $entry = $wpdb->get_row($sql);
	        if (empty($entry))
	        {
	            ReferrerDetector::send_fake_error();
	        }

	        // get the localized messages
	        if ($messages = unserialize(base64_decode($entry->welcome_text)))
	        {
	        	$default_msg = $messages['default'];
	        	$localized_msgs = '';
	        	for ($i = 0; $i < count($messages['countries']); ++$i)
	        	{
					$localized_msgs .= '<div style="padding: 5px 0">' .
					ReferrerDetector::countries_select($messages['countries'][$i], $i) .
					'	<textarea name="loc_messages[]" style="float: left">' . stripslashes($messages['loc_messages'][$i]) . '</textarea>
						<button class="remove_loc_msg button" style="float: left">Remove</button>
						<br style="clear: both" />
					</div>';
	        	}
	        }
	        else
	        {
	        	$default_msg = $entry->welcome_text;
	        	$localized_msgs = '';
	        }

	        printf('<p>
	            <label for="name">%s</label><br />
	            <input type="text" name="name" id="name" class="required" value="%s" />
	        </p>
	        <p>
	            <label for="url">%s</label><br />
	            <input type="text" name="url" id="url" class="required" value="%s" />
	        </p>
	        <p>
	            <label for="message">%s</label><br />
	            <textarea name="message" id="message" class="required">%s</textarea>
	        </p>
			<fieldset>
				<legend>Localized Messages</legend>
				<div id="locMessagesEdit">%s</div>
				<button class="add_loc_msg_edit button">Add</button>
			</fieldset>
	        <p>
	            <label for="icon">%s</label><br />
	            <input type="text" name="icon" id="icon" value="%s" /> (%s)
	        </p>
	        <p>
	            <input type="checkbox" name="enabled" id="enabled" %s />
	            <label for="enabled">%s</label>
	            <input type="hidden" name="rdetector_action" value="update" />
	            <input type="hidden" name="id" value="%s" />
	        </p>
	        <div class="submit">
	            <input type="submit" value="%s" />
	            <input type="button" id="buttonCancel" value="%s" />
	        </div>',
	        __('Name', $this->config['text_domain']),
	        addslashes($entry->name),
	        __('Url', $this->config['text_domain']),
	        $entry->url,
	        __('Default welcome message', $this->config['text_domain']),
	        stripslashes($default_msg),
	        $localized_msgs,
	        __('Icon', $this->config['text_domain']),
	        $entry->icon, __('Optional', $this->config['text_domain']),
	        $entry->enabled == 1 ? 'checked="checked"' : '',
	        __('This entry is active', $this->config['text_domain']),
	        $entry->id,
	        __('Save it!', $this->config['text_domain']),
	        __('Cancel', $this->config['text_domain']));
	    }
	    else
	    {
	        if (!$errors = $this->validate_post_data(true))
	        {
	            return false;
	        }

	        $messages = array(
        		'default'	=> ReferrerDetector::striptags($_POST['message']),
	        );

	        if (is_array($_POST['countries']))
	        {
		        $countries = array_values($_POST['countries']);

		        for ($i = 0; $i < count($countries); ++$i)
		        {
		        	if (!$countries[$i]) continue;
		        	if (!trim($_POST['loc_messages'][$i]))continue;

		        	$messages['countries'][] = $countries[$i];
					$messages['loc_messages'][] = ReferrerDetector::striptags($_POST['loc_messages'][$i]);
		        }
	        }

	        $sql = $wpdb->prepare("UPDATE `{$this->config['entries_table_name']}` SET `name`=%s, `url`=%s, `icon`=%s, `welcome_text`=%s, `enabled`=%d WHERE `id`=%d",
	            $_POST['name'], $_POST['url'], $_POST['icon'], base64_encode(serialize($messages)), isset($_POST['enabled']), intval($_POST['id']));

	        $wpdb->query($sql);

	        echo $this->format_html(intval($_POST['id']), $_POST['name'], $_POST['url'], $_POST['icon'], $messages['default'], isset($_POST['enabled']));
	    }
    }

    function merge()
    {
        global $wpdb;

        $sql = sprintf("SELECT * FROM `{$this->config['entries_table_name']}` WHERE `id` IN (%s)", mysql_escape_string($_POST['ids']));
        $result = $wpdb->get_results($sql);

        if (count($result) < 2)
        {
            ReferrerDetector::send_fake_error();
        }

        $to_delete = array();
        $new_url = array();
        foreach ($result as $entry)
        {
            $new_url[] = $entry->url;
            $to_delete[] = $entry->id;
        }

        $first_id = array_shift($to_delete);
        $wpdb->query(sprintf("DELETE FROM `{$this->config['entries_table_name']}` WHERE `id` IN (%s)", implode(',', $to_delete)));
        $wpdb->query(sprintf("UPDATE `{$this->config['entries_table_name']}` SET url='%s' WHERE `id`=$first_id", implode(',', $new_url)));

        // rdetector_generate_js_data();
        echo $this->get_table();
    }

    /**
     * @desc	Get special entries (those with wildcards and/or commas)
     * 			like "google.*", "delicious.com,del.icio.us"
     */
    function get_special_entries()
    {
    	global $wpdb;
    	$q = "SELECT url, icon, welcome_text FROM `{$this->config['entries_table_name']}` WHERE url REGEXP'[*,]'";

    	return $wpdb->get_results($q);
    }

    function del($bulk = false)
    {
        global $wpdb;

        if ($bulk)
        {
            $sql = sprintf("DELETE FROM `{$this->config['entries_table_name']}` WHERE `id` IN (%s)", mysql_escape_string($_POST['ids']));
        }
        else
        {
            $sql = $wpdb->prepare("DELETE FROM `{$this->config['entries_table_name']}` WHERE `id`=%d LIMIT 1", $_POST['id']);
        }

        $wpdb->query($sql);

        if (!mysql_affected_rows())
        {
            ReferrerDetector::send_fake_error();
        }
    }

    function restore_default()
    {
        $this->del_all();
        $this->add_default();

        echo $this->get_table();
    }

    function validate_post_data($is_editing = false)
    {
        $errors = array();
        if (!isset($_POST['name']) || !trim($_POST['name']))
        {
            $errors[] = __('Entry name is required', $this->config['text_domain']);
        }
        if (!isset($_POST['url']) || !trim($_POST['url']))
        {
            $errors[] = __('URL is required', $this->config['text_domain']);
        }
        if (!isset($_POST['message']) || !trim($_POST['message']))
        {
            $errors[] = __('Default welcome message is required', $this->config['text_domain']);
        }
        if ($is_editing && (!isset($_POST['id']) || !trim($_POST['id'])))
        {
            $errors[] = __('You must specify an ID', $this->config['text_domain']);
        }

        if (empty($errors)) return true;

        printf ('<p>%s</p>
        <ul>
            <li>%s</li>
        </ul>', __('Your input is not valid: ', $this->config['text_domain']),
        implode('</li><li>', $errors));

        return false;
    }

    function del_all()
    {
    	global $wpdb;
    	$wpdb->query("TRUNCATE {$this->config['entries_table_name']}");
    }

    function restore_one($entry)
    {
    	global $wpdb;

		$q = $wpdb->prepare("INSERT INTO `{$this->config['entries_table_name']}`(`id`, `name`, `url`, `icon`, `welcome_text`, `enabled`)
							VALUES(%d, %s, %s, %s, %s, %d)",
                			$entry->id, $entry->name, $entry->url, $entry->icon, $entry->welcome_text, $entry->enabled);

        $wpdb->query($q) or die(mysql_error());
    }

    function get_by_url($url)
    {
		global $wpdb;

		// first check for normal entries
		$q = $wpdb->prepare("SELECT icon, welcome_text FROM {$this->config['entries_table_name']}
							WHERE url=%s AND enabled=1", $url);

		$row = $wpdb->get_row($q);
		if (!empty($row)) return $row;

		// now check for special entries (wildcards, compound...)
		$special_entries = $this->get_special_entries();
		if (empty($special_entries))
    	{
    		return false;
    	}

		foreach ($special_entries as $entry)
		{
			if (strpos($entry->url, ',') !== false)
			{
				// this is a compound key
				$urls = explode(',', $entry->url);
				if (in_array($url, $urls))
				{
					return $entry;
				}
			}
			else if (ReferrerDetector::matches($url, $entry->url))
			{
				return $entry;
			}
			else
			{
			}
		}

		return false;
    }
}
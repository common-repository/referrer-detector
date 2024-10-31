<?php
class ReferrerDetector
{
    var $config;
    var $entry;
    var $path;
    var $stats;
    var $option;
    var $support;

    function ReferrerDetector()
    {
        $this->__construct();
    }

    function __construct()
    {
        global $rdetector_config;
        $this->path = dirname(__FILE__);

        require_once("{$this->path}/config.php");
        require_once("{$this->path}/entry.class.php");
        require_once("{$this->path}/option.class.php");
        require_once("{$this->path}/stats.class.php");
        require_once("{$this->path}/support.class.php");

        $this->config = $rdetector_config;

        $this->entry = ReferrerDetector::get_instance('ReferrerDetector_Entry');
        $this->stats = ReferrerDetector::get_instance('ReferrerDetector_Stats');
        $this->option = ReferrerDetector::get_instance('ReferrerDetector_Option');
        $this->support = ReferrerDetector::get_instance('ReferrerDetector_Support');
    }

    /**
    * @desc
    */
    function install()
    {
        $this->entry->create_table();
        $this->stats->create_table();

        $this->option->update_or_add('referrer_detector_db_version', $this->config['db_version']);
    }

    function register_menu()
    {
        add_options_page(
            __('Referrer Detector', $this->config['text_domain']),
            __('Referrer Detector', $this->config['text_domain']),
            8,
            basename(__FILE__),
            array($this, 'build_options_form')
        );
    }

    function build_options_form()
    {
        $this->include_scripts();

        printf('
        <div class="wrap" id="rdOptionsHolder">

            <input type="hidden" id="rdetectorNonce" name="nonce" value="%s" />
            <div id="divGenericResult" class="updated fade" style="display:none"></div>

            <h2>%s</h2>
            <ul>
                <li><a href="#divListContainer">%s</a></li>
                <li><a href="#divAddContainer">%s</a></li>
                <li><a href="#divEditContainer">%s</a></li>
                <li><a href="#divExcluded">%s</a></li>
                <li><a href="#divGenericOptions">%s</a></li>
                <li><a href="#divStats">%s</a></li>
				<li><a class="emphasized_link" href="#divSupport">%s</a></li>
            </ul>

            <!-- LIST START -->%s<!-- LIST END -->
            <!-- ADD START -->%s<!-- ADD END -->
            <!-- EDIT START -->%s<!-- EDIT END -->
            <!-- EXCLUDE START -->%s<!-- EXCLUDE END -->
            <!-- OPTIONS START -->%s<!-- OPTIONS END -->
            <!-- STATS START -->%s<!-- STATS END -->
			<!-- SUPPORT START -->%s<!-- SUPPORT END -->

             <div id="divGenericLoading" style="display: none">
                <img src="%s/images/loading.gif" alt="Loading..." />
            </div>

        </div>',
            wp_create_nonce($this->config['nonce_action']),
            __('Referrer Detector Options', $this->config['text_domain']),
            __('Entry List', $this->config['text_domain']),
            __('Add Entry', $this->config['text_domain']),
            __('Edit Entry', $this->config['text_domain']),
            __('Excluded URLs', $this->config['text_domain']),
            __('Generic Options', $this->config['text_domain']),
            __('Stats', $this->config['text_domain']),
            __('Support this plugin!', $this->config['text_domain']),

            $this->entry->get_list_panel(),
            $this->entry->get_add_panel(),
            $this->entry->get_edit_panel(),
            $this->entry->get_excluded_panel(),
            $this->option->get_panel(),
            $this->stats->get_panel(),
            $this->support->get_panel(),

            $this->config['plugin_dir']
        );
    }

    /**
    * @desc
    */
    function include_scripts()
    {
        // hell with wp_enqueue_script()!
        // JUST DO THIS!

        echo(
            '<link rel="stylesheet" href="' . $this->config['plugin_dir'] . 'css/admin_style.css" type="text/css" media="screen" />' .
            '<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>' .
            '<script type="text/javascript" src="' . $this->config['plugin_dir'] . 'js/jquery-ui-personalized.js"></script>' .
            '<script type="text/javascript" src="' . $this->config['plugin_dir'] . 'js/admin_functions.js"></script>' .
            '<script type="text/javascript" src="' . $this->config['plugin_dir'] . 'js/admin_onload.js"></script>'
        );
    }

    function get_entry_table()
    {
        $this->entry->get_table();
    }

    function handle_request()
    {
        if (!isset($_POST['rdetector_action'])) return false;
        if ($_POST['rdetector_action'] == 'stats_insert' || $_POST['rdetector_action'] == 'get_box') return false;

        if (!wp_verify_nonce($_POST['nonce'], $this->config['nonce_action']))
        {
            die(__('Security check failed. Please try refreshing.', $this->config['text_domain']));
        }

        switch($_POST['rdetector_action'])
        {
            case 'activate':
            case 'deactivate':
                $this->entry->activate($_POST['rdetector_action'] == 'activate');
                break;
            case 'bulk-activate':
            case 'bulk-deactivate':
                $this->entry->activate($_POST['rdetector_action'] == 'bulk-activate', true);
                break;
            case 'bulk-merge':
                $this->entry->merge();
                break;
            case 'add':
                $this->entry->add();
                break;
            case 'edit':
                $this->entry->edit();
                break;
            case 'update':
                $this->entry->edit(true);
                break;
            case 'delete':
                $this->entry->del();
                break;
            case 'bulk-delete':
                $this->entry->del(true);
                break;
            case 'restore':
                $this->entry->restore_default();
                break;
            case 'exclude':
                // for now treat the excluded data as generic
            case 'generic':
                $this->option->save();
                break;
            case 'stats_show':
                $this->stats->show();
                break;
            case 'stat-details':
                $this->stats->show_details();
                break;
            default:
                return false;
        }

        exit();
    }

    function handle_public_request()
    {
		switch ($_POST['rdetector_action'])
	    {
	        case 'stats_insert':
	            $this->stats->insert();
	            break;
	        case 'get_box':
	        	$this->get_box();
	        	break;
	        default:
	            return false;
	    }
	    exit();
    }


	function head()
	{
	    if (file_exists('./wp-content/plugins/referrer-detector-style.css'))
	    {
	        $stylesheet = get_option('siteurl') . '/wp-content/plugins/referrer-detector-style.css';
	    }
	    else
	    {
	        $stylesheet = "{$this->config['plugin_dir']}css/style.css";
	    }

	    printf('<link rel="stylesheet" type="text/css" media="screen" href="%s" />', $stylesheet);
	    print('<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>');
	    printf('<script type="text/javascript" src="%sjs/utils.js"></script>', $this->config['plugin_dir']);
	    printf('<script type="text/javascript" src="%sjs/onload.js"></script>', $this->config['plugin_dir']);
        
        if ($this->option->get('rdetector_message_position') == 'lightbox')
        {
            printf('<script type="text/javascript" src="%sjs/jquery.simplemodal.js"></script>', $this->config['plugin_dir']);
        }
	}

	function prepare_post_data($post_content)
	{
		// don't display the greeting if it's a feed
	    if (is_feed() || is_home())
	    {
	        return str_replace(RDETECTOR_CUSTOM_TAG, '', $post_content);
	    }

	    global $post;
	    $url = get_permalink($post->ID);
	    $title = get_the_title();
	    $author_name = get_the_author();
	    $author_url = get_the_author_url();
	    $category_names = array();
	    $category_links = array();

	    foreach((get_the_category()) as $category)
	    {
	        $category_names[] = $category->cat_name;
	        $category_links[] = sprintf('<a href="%s">%s</a>', get_category_link($category->cat_ID), $category->cat_name);
	    }

	    $data = array(
	    	'tags'	=> array(
	    		'url'             => $url,
		        'title'           => $title,
		        'link'            => sprintf('<a href="%s">%s</a>', $url, $title),
		        'categorynames'   => implode(', ', $category_names),
		        'categorylinks'   => implode(', ', $category_links),
		        'authorname'      => $author_name,
		        'authorurl'       => $author_url,
		        'authorlink'      => sprintf('<a href="%s">%s</a>', $author_url, $author_name),
	    	),
	    	'post_or_page'	=> is_page() ? 'page' : 'post',
	    	'id'			=> $post->ID,
	    );

	    // merge all the data into a hidden input's value
	    $data = sprintf('<input type="hidden" id="rdetector_data" value="%s" name="rdetector_data" />', base64_encode(serialize($data)));

	    $post_content .= $data;

	    $post_content = str_replace(RDETECTOR_CUSTOM_TAG, '<span class="rdetector_placeholder"></span>', $post_content);

	    return '<span class="rdetector_placeholder_before"></span>' . $post_content . '<span class="rdetector_placeholder_after"></span>';
	}


	function get_box()
	{
		$referrer = base64_decode(str_replace(' ', '+', $_POST['r']));

		$data = unserialize(base64_decode(str_replace(' ', '+', $_POST['data'])));

		//
		if ($data['post_or_page'] == 'post' && $this->option->get('rdetector_add_to_every_post') != 'yes')
		{
			return false;
		}

		if ($data['post_or_page'] == 'page' && $this->option->get('rdetector_add_to_every_page') != 'yes')
		{
			return false;
		}

		// check if the referrer is excluded
		// remove the http://www. from the referrer if any
		$referrer = preg_replace('/^http:\/\//i', '', $referrer);
		$referrer = preg_replace('/^www\./i', '', $referrer);

		if ($this->is_excluded($referrer))
		{
			return false;
		}

		$data['tags']['search-terms'] = $this->get_search_phrase($referrer);

		// trim all trailing parameters
		$referrer = preg_replace('/\/(.*)/', '', $referrer);

		// now check if the referrer is an entry
		if (!$entry = $this->entry->get_by_url($referrer))
		{
			return false;
		}
        
        switch ($position = $this->option->get('rdetector_message_position'))
        {
            case 'both':
                $script = '$(".rdetector_placeholder_before, .rdetector_placeholder_after").after(box).remove();';
                break;
            case 'lightbox':
                $script = '';
                break;
            case 'before':
            case 'after':
                $script = '$(".rdetector_placeholder_' . $position . '").after(box).remove();';
                break;
            default: 
                return false;
        }
        
        if ($position != 'lightbox')
        {
		    $script = sprintf('var box = $(Base64.decode("%s"));
			    if ($(".rdetector_placeholder_special").length == 1)
			    {
				    $(".rdetector_placeholder_special").after(box).remove();
                    
			    }
			    else if ($(".rdetector_placeholder_before").length == 1)
			    {
				    %s
			    }
			    else
			    {
				    $(".rdetector_placeholder").after(box).remove();
			    }',
			    base64_encode($this->generate_box($entry, $data)),
			    $script
            );
        }
        else
        {
            // lightbox
            $script = sprintf('$.modal($(Base64.decode("%s")),{
                onOpen: function(d){
                    d.overlay.fadeIn("normal", function(){
                        d.container.slideDown("normal", function(){
                            d.data.slideDown();
                        });
                    });
                },
                onClose: function(d){
                    d.overlay.fadeOut("normal", function(){
                        $.modal.close(); 
                    });
                }
            });', base64_encode($this->generate_box($entry, $data)));
        }
            
        ReferrerDetector::write_log($script);

		echo str_replace(PHP_EOL, '', $script);

		//
		$this->stats->insert($referrer);
	}

	function generate_box($entry, $data)
	{
		// default is a Vietnam IP
		$ip = $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? '203.191.48.1' : $_SERVER['REMOTE_ADDR'];

		ReferrerDetector::write_log("IP: $ip");

		$country_code = @file_get_contents("http://api.hostip.info/country.php?ip=$ip");

		ReferrerDetector::write_log("Country: $country_code");

		//
		if (!$messages = unserialize(base64_decode($entry->welcome_text)))
		{
			$msg = $entry->welcome_text;
		}
		else
        {
        	// default to the default
        	$msg = $messages['default'];

			// find the localized
			if (isset($messages['countries']) && is_array($messages['countries']))
			{
				for ($i = 0; $i < count($messages['countries']); ++$i)
	        	{
					if (in_array($country_code, array_values($messages['countries'][$i])))
					{
						$msg = $messages['loc_messages'][$i];
						break;
					}
	        	}
			}
        }

        ReferrerDetector::write_log("Message: $msg");

		foreach ($data['tags'] as $key => $value)
		{
			$msg = str_replace('{' . $key . '}', $value, $msg);
		}

		$icon = $entry->icon ? $entry->icon : "{$this->config['plugin_dir']}images/icons/default.png";

		if ($this->option->get('rdetector_close_icon') != "yes")
		{
			$box = sprintf('<div class="rd_box">
                    <img class="rd_icon" src="%s" alt="" />
                    <div class="rd_text">
                    %s
                        <div class="rd_meta">
                            %s
                            %s
                            <div style="clear:both; font-size: 0;"></div>
                        </div>
                    </div>
                    <div style="clear:both; font-size: 0;"></div>
                </div>', 
                $icon,
                stripslashes($msg), 
                $this->option->get('rdetector_related_posts') > 0 ? 
                    $this->get_related_posts($data['id'], $this->option->get('rdetector_related_posts')) : '',
                $this->option->get('rdetector_powered') != 'no' ? 
                    '<span class="rd_powered">Powered by <a href="http://www.phoenixheart.net/wp-plugins/referrer-detector/">Referrer Detector</a></span>' : ''
            );
		}
		else
		{
			$box = sprintf('<div class="rd_box">
					<img class="rd_icon" src="%s" alt="" />
                    <a class="rd_close" href="#">X</a>
                    <div class="rd_text">
                    %s
                        <div class="rd_meta">
                            %s
                            %s
                            <div style="clear:both; font-size: 0;"></div>
                        </div>
                    </div>
                    <div style="clear:both; font-size: 0;"></div>
				</div>',
                $icon,
                stripslashes($msg), 
                $this->option->get('rdetector_related_posts') > 0 ? 
                    $this->get_related_posts($data['id'], $this->option->get('rdetector_related_posts')) : '',
                $this->option->get('rdetector_powered') != 'no' ? 
                    '<span class="rd_powered">Powered by <a href="http://www.phoenixheart.net/wp-plugins/referrer-detector/">Referrer Detector</a></span>' : ''
            );
		}

		return $box;
	}

	function is_excluded($referrer)
	{
		$excluded_urls = explode(',', $this->option->get('rdetector_excluded_urls'));

		foreach ($excluded_urls as $url)
		{
            // deal with "empty delimiter" warning
            if (!$url) continue;
            
			if (strpos(strtolower($referrer), strtolower($url)) === 0)
			{
				return true;
			}

			if (ReferrerDetector::matches($referrer, $url))
			{
				return true;
			}
		}

		return false;
	}
    
    /**
    * @desc     Get a list of related posts
    */
    function get_related_posts($id, $count)
    {
        $tags = wp_get_post_tags($id);
        if (!$tags) return false;
        
        
        $tag_ids = array();
        foreach ($tags as $individual_tag) 
        {
            $tag_ids[] = $individual_tag->term_id;
        }

        $args=array(
            'tag__in'           => $tag_ids,
            'post__not_in'      => array($id),
            'showposts'         => $count,
            'caller_get_posts'  => 1
        );
        
        $my_query = new wp_query($args);
        if (!$my_query->have_posts()) return false;
        
        ob_start();
        echo '
            <span class="rd_related_toggler">Related Posts</span>
            <ul style="display: none; clear: both">';
        while ($my_query->have_posts()) 
        {
            $my_query->the_post();
        ?>
            <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
        <?php
        }
        echo '</ul>';
        
        $str = ob_get_contents();
        ob_clean();
        return $str;
    }


	// ----------------------- STATIC FUNCTIONS -------------------------- //
	// ------------------ TODO: A HELPER CLASS, MAYBE? ------------------- //

	function matches($str, $pattern)
	{
		if (strpos($pattern, '*') === false)
		{
			return false;
		}

		// there is an asterik. Check it
		$pattern = str_replace('/', '\/', $pattern);
		$pattern = str_replace('.', '\.', $pattern);
		$pattern = str_replace('*', '.*', $pattern);

		preg_match("/$pattern/", $str, $matches);

		return $matches;
	}

    function get_instance($class)
    {
        static $instances = array();
        if (!array_key_exists($class, $instances))
        {
            $instances[$class] =& new $class;
        }

        $instance =& $instances[$class];
        return $instance;
    }

    function striptags($msg)
    {
        $allowable_tags = '<div><h1><h2><h3><p><span><a><ul><ol><li><hr><br><table><thead><tbody><tfoot><tr><td><th><strong><em>';
        return $message = strip_tags(html_entity_decode($msg), $allowable_tags);
    }

    function trim_value(&$value)
    {
         $value = trim($value);
    }

    function save_file($name, $content)
    {
        if (false === $handle = fopen($name, 'w+')) return false;
        fwrite($handle, $content);
        fclose($handle);
        return true;
    }

    function send_fake_error()
    {
        header('HTTP/1.0 404 Not Found');
        exit();
    }

    function get_search_phrase($referrer)
    {
		$key_start = 0;
	  	$search_phrase = '';

		// used by dogpile, excite, webcrawler, metacrawler
		if (strpos($referrer, '/search/web/') !== false) $key_start = strpos($referrer, '/search/web/') + 12;

		// used by chubba
		if (strpos($referrer, 'arg=') !== false) $key_start = strpos($referrer, 'arg=') + 4;

		// used by dmoz
		if (strpos($referrer, 'search=') !== false) $key_start = strpos($referrer, 'query=') + 7;

		// used by looksmart
		if (strpos($referrer, 'qt=') !== false) $key_start = strpos($referrer, 'qt=') + 3;

		// used by scrub the web
		if (strpos($referrer, 'keyword=') !== false) $key_start = strpos($referrer, 'keyword=') + 8;

		// used by overture, hogsearch
		if (strpos($referrer, 'keywords=') !== false) $key_start = strpos($referrer, 'keywords=') + 9;

		// used by mamma, lycos, kanoodle, snap, whatuseek
		if (strpos($referrer, 'query=') !== false) $key_start = strpos($referrer, 'query=') + 6;

		// don't allow encrypted key words by aol
		if (strpos($referrer, 'encquery=') !== false) $key_start = 0;

		// used by ixquick
		if (strpos($referrer, '&query=') !== false) $key_start = strpos($referrer, '&query=') + 7;

		// used by aol
		if (strpos($referrer, 'qry=') !== false) $key_start = strpos($referrer, 'qry=') + 4;

		// used by yahoo, hotbot
		if (strpos($referrer, 'p=') !== false) $key_start = strpos($referrer, 'p=') + 2;

		// used by google, msn, alta vista, ask jeeves, all the web, teoma, wisenut, search.com
		if (strpos($referrer, 'q=') !==  false) $key_start = strpos($referrer, 'q=') + 2;

		// if present, get the search phrase from the referer
		if ($key_start > 0)
		{
			if (strpos($referrer, '&', $key_start) !== false)
			{
				$search_phrase = substr($referrer, $key_start, (strpos($referrer, '&', $key_start) - $key_start));
			}
			elseif (strpos($referrer, '/search/web/') !== false)
			{
				if (strpos($referrer, '/', $key_start) !== false)
				{
					$search_phrase = urldecode(substr($referrer, $key_start, (strpos($referrer, '/', $key_start) - $key_start)));
		        }
		        else
		        {
		        	$search_phrase = urldecode(substr($referrer, $key_start));
		        }
		    }
		    else
		    {
		    	$search_phrase = substr($referrer, $key_start);
		    }
		}

		$search_phrase = urldecode($search_phrase);
		return $search_phrase;
	}

	function write_log($msg)
	{
		if (!RD_DEBUG) return;
		$handle = fopen(dirname(__FILE__) . '/rd.log', 'a');
		fwrite($handle, $msg . PHP_EOL);
		fclose($handle);
	}

	function countries_select($selected_codes = array(), $id = null)
	{
		$id = $id ? "no$id" : 'no__id__';
		$select = '<select name="countries[' . $id . '][]" multiple="multiple" style="height: 200px; float: left">';
		foreach ($this->config['countries'] as $code => $name)
		{
			$select .= sprintf('<option value="%s"%s>%s</option>',
				$code, in_array($code, $selected_codes) ? ' selected="selected"' : '', $name
			);
		}
		$select .= '</select>';
		return $select;
	}

	function printr($array)
	{
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}

	function random_color()
	{
	    $c = '';
	    while (strlen($c)<6){
	        $c .= sprintf("%02X", mt_rand(0, 255));
	    }
	    return $c;
	}
}
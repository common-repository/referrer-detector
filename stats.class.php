<?php
class ReferrerDetector_Stats
{
    var $config;

    function ReferrerDetector_Stats()
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

        if ($wpdb->get_var("SHOW TABLES LIKE '{$this->config['stats_table_name']}'") != $this->config['stats_table_name'])
        {
            $sql = "CREATE TABLE `{$this->config['stats_table_name']}` (
              `id` bigint(20) NOT NULL auto_increment,
              `referrer` varchar(50) collate latin1_general_ci NOT NULL,
              `time` datetime NOT NULL,
              PRIMARY KEY  (`id`),
              KEY `referrer` (`referrer`)
            );";

            dbDelta($sql);
        }
    }

    function get_panel()
    {
        return sprintf('
        <div id="divStats">
            <div id="statsHolder"></div>
            <div id="statsLoading" style="display: none"><img src="%simages/loading.gif" alt="Loading" /></div>
            <form id="formStats" method="post" action="index.php" autocomplete="off">
                <p>Time range:
                    <select id="timeRange" name="time_range">
                        <option value="today" selected="selected">%s</option>
                        <option value="yesterday">%s</option>
                        <option value="this_week">%s</option>
                        <option value="this_month">%s</option>
                        <option value="this_year">%s</option>
                        <option value="all_time">%s</option>
                        <option value="specified">%s</option>
                    </select>
                    <span id="specifiedSpan" style="display:none">
                        %s <input type="text" title="YYYY-MM-DD" style="width:100px" name="specified_from" class="text" value="YYYY-DD-MM" />
                        %s <input type="text" title="YYYY-MM-DD" style="width:100px" name="specified_to" class="text" value="YYYY-DD-MM" />
                    </span>
                    <input type="hidden" name="rdetector_action" value="stats_show" />
                    <input type="submit" class="button-secondary action" value="%s" />
                </p>
            </form>
        </div>',
            $this->config['plugin_dir'],
            __('Today', $this->config['text_domain']),
            __('Yesterday', $this->config['text_domain']),
            __('This week', $this->config['text_domain']),
            __('This month', $this->config['text_domain']),
            __('This year', $this->config['text_domain']),
            __('All time (may take a while!)', $this->config['text_domain']),
            __('Specified', $this->config['text_domain']),
            __('From', $this->config['text_domain']),
            __('To', $this->config['text_domain']),
            __('Show Stats', $this->config['text_domain'])
        );
    }

    function show()
    {
        global $wpdb;

        switch ($_POST['time_range'])
        {
            case 'today':
                $start_date = date('Y-m-d 00:00:00');
                $end_date =  date('Y-m-d H:i:s', strtotime('tomorrow'));
                break;
            case 'yesterday':
                $start_date = date('Y-m-d H:i:s', strtotime('yesterday'));
                $end_date = date('Y-m-d 00:00:00'); // today
                break;
            case 'this_week':
                $start_date = date('Y-m-d H:i:s', strtotime('today') - (date('w') - 1)*60*60*24);
                $end_date =  date('Y-m-d H:i:s', strtotime('tomorrow'));
                break;
            case 'this_month':
                $start_date = date('Y-m-01 00:00:00');
                $end_date =  date('Y-m-d H:i:s', strtotime('tomorrow'));
                break;
            case 'this_year':
                $start_date = date('Y-m-01 00:00:00');
                $end_date =  date('Y-m-d H:i:s', strtotime('tomorrow'));
                break;
            case 'all_time':
                $start_date = '2008-01-01 00:00:00';
                $end_date =  date('Y-m-d H:i:s', strtotime('tomorrow'));
                break;
            case 'specified':
                preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})#', $_POST['specified_to'], $matches);
                if (empty($matches))
                {
                    $the_date_after = strtotime('tomorrow');
                }
                  else
                  {
                      $the_date_after = mktime(0, 0, 0, $matches[2], $matches[3], $matches[1]) + 24*60*60;
                  }

                $start_date = mysql_escape_string($_POST['specified_from']);
                $end_date = date('Y-m-d 00:00:00', $the_date_after);
                break;
            default:
                break;
        }

        $sql = "SELECT `referrer`, COUNT(`id`) AS 'views'
            FROM `{$this->config['stats_table_name']}`
            WHERE `time` >= '$start_date'
            AND `time` < '$end_date'
            GROUP BY `referrer`
            ORDER BY `referrer`";

        // echo "$sql <br />";

        $entries = $wpdb->get_results($sql, ARRAY_A);
        if (count($entries) == 0)
        {
            die(__('No stats data for the time range, or the range is not so valid.', $this->config['text_domain']));
        }


        // merge TLDs
        $optimized_data = array(
			'Google' => array(
				'referrer'	=> 'Google',
				'views' 	=> 0
			),
			'Yahoo'	=> array(
				'referrer'	=> 'Yahoo',
				'views' 	=> 0
			)
        );

        $total_views = 0;

        foreach ($entries as $entry)
        {
        	$total_views += (int)$entry['views'];

			if (ReferrerDetector::matches($entry['referrer'], 'google.*') || ReferrerDetector::matches($entry['referrer'], '*.google.*'))
			{
				$optimized_data['Google']['views'] += (int)$entry['views'];
				continue;
			}

			if (ReferrerDetector::matches($entry['referrer'], 'yahoo.*') || ReferrerDetector::matches($entry['referrer'], '*.yahoo.*'))
			{
				$optimized_data['Yahoo']['views'] += (int)$entry['views'];
				continue;
			}

			$optimized_data[] = $entry;
        }

        if (!$optimized_data['Google']['views']) unset($optimized_data['Google']);
        if (!$optimized_data['Yahoo']['views']) unset($optimized_data['Yahoo']);

        $i = 0;
        $chd = '';
        $chl = '';
        $chco = '';
        foreach ($optimized_data as $entry)
        {
        	$chd .= round($entry['views'] / $total_views, 2) . ',';
        	$chl .= sprintf('%s (%s)|', $entry['referrer'], $entry['views']);
        	$chco .= ReferrerDetector::random_color() . ',';
        }

        $chd = rtrim($chd, ',');
        $chl = rtrim($chl, '|');
        $chco = rtrim($chco, ',');

        printf('<img src="http://chart.apis.google.com/chart?cht=p3&chd=t:%s&chs=600x200&chl=%s&chco=%s" alt="Chart" />',
        	$chd, $chl, $chco);
    }

    function insert($referrer)
    {
    	global $wpdb;
    	$wpdb->query("INSERT INTO {$this->config['stats_table_name']}(`referrer`, `time`) VALUES('$referrer', now())");
    }
}
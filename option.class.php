<?php
class ReferrerDetector_Option
{
    var $config;

    function ReferrerDetector_Option()
    {
        $this->__construct();
    }

    function __construct()
    {
        global $rdetector_config;
        $this->config = $rdetector_config;
    }

    function get_panel()
    {
        // the generic options div
        $ret = sprintf('
        <div id="divGenericOptions">
            <p class="toggler"><span style="font-size:11px; font-weight:normal">%s</span></p>
            <div class="toggled help">%s</div>
            <form action="index.php" method="post" autocomplete="off" class="generic_form">',
            __('Get help with this', $this->config['text_domain']),
            __($this->config['generic_help'], $this->config['text_domain']));

        foreach ($this->config['options'] as $key => $config)
        {
            $value = get_option($key);
            if (!$value) $value = $config['default_value'];

            $select_options = '';
            foreach ($config['options'] as $o)
            {
                $select_options .= sprintf('
                <option value="%s"%s>%s</option>
                ', $o, $o == $value ? 'selected="selected"' : '', $o);
            }

            $ret .= sprintf('
            <p>
                <label for="%s">%s</label><br />
                <select name="%s" id="%s">
                %s
                </select>
            </p>', $key, $config['label'], $key, $key, $select_options);
        }

        $ret .= sprintf('
                <div class="help">
                    %s
                    <input type="hidden" name="rdetector_action" value="generic" />
                </div>
                <div class="submit">
                    <input type="submit" value="%s" />
                </div>
            </form>
        </div>',
        __("(*) If set to No, you may use a <a href=\"{$this->config['help_url']}#custom_tag\">custom tag</a> to manually display the welcome message on your posts.<br />
        Alternatively, you may put a <a href=\"http://www.phoenixheart.net/2008/11/here-it-is-referrer-detector-20/#template_tag\">template tag</a> in your template files (most likely index.php).<br />
		(**) If Lightbox style is chosen, Referrer Detector will display a close icon regardless of this setting."),
        __('Save these options', $this->config['text_domain']));

        return $ret;
    }

    function save()
    {
        // we have 2 cases
        if (isset($_POST['rdetector_excluded_urls']))
        {
            // 1. Excluded URLS
            $urls = explode(',', $_POST['rdetector_excluded_urls']);
            array_walk($urls, array('ReferrerDetector', 'trim_value'));
            update_option('rdetector_excluded_urls', implode(',', $urls));
        }
        else
        {
            // 2. "Real" generic options
            foreach ($this->config['options'] as $key => $config)
            {
                if (isset($_POST[$key]))
                {
                    update_option($key, $_POST[$key]);
                }
            }
        }

        // rdetector_generate_js_data();
        _e("Settings saved.", $this->config['text_domain']);
    }

    function update_or_add($key, $value)
    {
    	get_option($key) === false ? add_option($key, $value) : update_option($key, $value);
    }

    function get($key)
    {
    	return get_option($key);
    }
}
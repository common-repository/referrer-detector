<?php
class ReferrerDetector_Support
{
	var $config;

	function ReferrerDetector_Support()
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
		return sprintf('<div id="divSupport">
			<div class="help">%s</div>
		</div>', $this->config['support_text']
		);
	}
}
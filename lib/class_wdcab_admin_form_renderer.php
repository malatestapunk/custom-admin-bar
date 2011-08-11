<?php
class Wdcab_AdminFormRenderer {

	function _get_option ($key=false, $pfx='wdcab') {
		$opts = get_site_option($pfx);
		if (!$key) return $opts;
		return @$opts[$key];
	}

	function _create_checkbox ($name, $pfx='wdcab') {
		$opt = $this->_get_option($name, $pfx);
		$value = @$opt[$name];
		return
			"<input type='radio' name='{$pfx}[{$name}]' id='{$name}-yes' value='1' " . ((int)$value ? 'checked="checked" ' : '') . " /> " .
				"<label for='{$name}-yes'>" . __('Yes', 'wdcab') . "</label>" .
			'&nbsp;' .
			"<input type='radio' name='{$pfx}[{$name}]' id='{$name}-no' value='0' " . (!(int)$value ? 'checked="checked" ' : '') . " /> " .
				"<label for='{$name}-no'>" . __('No', 'wdcab') . "</label>" .
		"";
	}

	function _create_radiobox ($name, $value) {
		$opt = $this->_get_option($name);
		$checked = (@$opt == $value) ? true : false;
		return "<input type='radio' name='wdcab[{$name}]' id='{$name}-{$value}' value='{$value}' " . ($checked ? 'checked="checked" ' : '') . " /> ";
	}

	function create_enabled_box () {
		echo $this->_create_checkbox('enabled');
	}

	function create_title_box () {
		$value = $this->_get_option('title');
		echo "<input type='text' class='widefat' name='wdcab[title]' value='{$value}' />";
		_e('<p>If you\'d like to use an image instead of text, please paste the full URL of your image in the box (starting with <code>http://</code> - e.g. <code>http://example.com/myimage.gif</code>).</p><p>For best results, make sure your image has trasnparent background and is no more then 28px tall.</p>', 'wdcab');
	}

	function create_links_box () {
		$steps = $this->_get_option('links');
		$steps = is_array($steps) ? $steps : array();

		echo "<ul id='wdcab_steps'>";
		$count = 1;
		foreach ($steps as $step) {
			echo '<li class="wdcab_step">' .
				'<h4>' .
					'<span class="wdcab_step_count">' . $count . '</span>' .
					':&nbsp;' .
					'<span class="wdcab_step_title">' . $step['title'] . '</span>' .
				'</h4>' .
				'<div class="wdcab_step_actions">' .
					'<a href="#" class="wdcab_step_delete">' . __('Delete', 'wdcab') . '</a>' .
					'&nbsp;|&nbsp;' .
					'<a href="#" class="wdcab_step_edit">' . __('Edit', 'wdcab') . '</a>' .
				'</div>' .
				'<input type="hidden" class="wdcab_step_url" name="wdcab[links][' . $count . '][url]" value="' . $step['url'] . '" />' .
				'<input type="hidden" class="wdcab_step_url_type" name="wdcab[links][' . $count . '][url_type]" value="' . $step['url_type'] . '" />' .
				'<input type="hidden" class="wdcab_step_title" name="wdcab[links][' . $count . '][title]" value="' . $step['title'] . '" />' .
			"</li>\n";
			$count++;
		}
		echo "</ul>";
		_e('<p>Drag and drop steps to sort them in the order you want.</p>', 'wdcab');
	}

	function create_add_link_box () {
		// URL
		echo '<label for="wdcab_last_wizard_step_url">' . __('URL:', 'wdcab') . '</label><br />';
		echo '<select id="wdcab_last_wizard_step_url_type" name="wdcab[links][_last_][url_type]">';
		echo '<option value="admin">' . __('Administrative page (e.g. "post-new.php" or "themes.php")', 'wdcab') . '</option>';
		echo '<option value="site">' . __('Site page (e.g. "/" or "/2007-06-05/an-old-post")', 'wdcab') . '</option>';
		echo '<option value="external">' . __('External page (e.g. "http://www/example.com/2007-06-05/an-old-post")', 'wdcab') . '</option>';
		echo '</select> <span id="wdcab_url_preview">Preview: <code></code></span><br />';
		echo "<input type='text' class='widefat' id='wdcab_last_wizard_step_url' name='wdcab[links][_last_][url]' /> <br />";

		// Title
		echo '<label for="wdcab_last_wizard_step_title">' . __('Title:', 'wdcab') . '</label>';
		echo "<input type='text' class='widefat' id='wdcab_last_wizard_step_title' name='wdcab[links][_last_][title]' /> <br />";

		echo "<input type='submit' value='" . __('Add', 'wdcab') . "' />";
	}
}
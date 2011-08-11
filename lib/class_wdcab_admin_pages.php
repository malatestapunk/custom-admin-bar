<?php
/**
 * Handles all Admin access functionality.
 */
class Wdcab_AdminPages {

	function Wdcab_AdminPages () { $this->__construct(); }

	function __construct () {

	}

	/**
	 * Main entry point.
	 *
	 * @static
	 */
	function serve () {
		$me = new Wdcab_AdminPages;
		$me->add_hooks();
	}

	function create_admin_menu_entry () {
		if (@$_POST && isset($_POST['option_page'])) {
			$changed = false;
			if ('wdcab_options' == @$_POST['option_page']) {
				if (isset($_POST['wdcab']['links']['_last_'])) {
					$last = $_POST['wdcab']['links']['_last_'];
					unset($_POST['wdcab']['links']['_last_']);
					//$last['url'] = rtrim($last['url_type'], '/') . $last['url'];
					//unset($last['url_type']);
					if (@$last['url'] && @$last['title']) $_POST['wdcab']['links'][] = $last;
				}
				if (isset($_POST['wdcab']['links'])) {
					$_POST['wdcab']['links'] = array_filter($_POST['wdcab']['links']);
				}
				update_site_option('wdcab', $_POST['wdcab']);
				$changed = true;
			}

			if ($changed) {
				$goback = add_query_arg('settings-updated', 'true',  wp_get_referer());
				wp_redirect($goback);
				die;
			}
		}
		add_submenu_page('settings.php', __('Custom Admin Bar', 'wdcab'), __('Custom Admin Bar', 'wdcab'), 'manage_network_options', 'wdcab', array($this, 'create_admin_page'));
	}

	function register_settings () {
		$form = new Wdcab_AdminFormRenderer;

		register_setting('wdcab', 'wdcab');
		add_settings_section('wdcab_settings', __('Settings', 'wdcab'), create_function('', ''), 'wdcab_options');
		add_settings_field('wdcab_enable', __('Enable Custom entry', 'wdcab'), array($form, 'create_enabled_box'), 'wdcab_options', 'wdcab_settings');
		add_settings_field('wdcab_title', __('Entry title <br /><small>(text or image)</small>', 'wdcab'), array($form, 'create_title_box'), 'wdcab_options', 'wdcab_settings');
		add_settings_field('wdcab_links', __('Configure Links', 'wdcab'), array($form, 'create_links_box'), 'wdcab_options', 'wdcab_settings');
		add_settings_field('wdcab_add_step', __('Add new link', 'wdcab'), array($form, 'create_add_link_box'), 'wdcab_options', 'wdcab_settings');
	}

	function create_admin_page () {
		include(WDCAB_PLUGIN_BASE_DIR . '/lib/forms/plugin_settings.php');
	}

	function js_print_scripts () {
		if (!WP_NETWORK_ADMIN) return;
		if (!isset($_GET['page']) || 'wdcab' != $_GET['page']) return false;
		wp_enqueue_script( array("jquery", "jquery-ui-core", "jquery-ui-sortable", 'jquery-ui-dialog') );
	}

	function css_print_styles () {

	}



	function add_hooks () {
		add_action('admin_init', array($this, 'register_settings'));
		add_action('network_admin_menu', array($this, 'create_admin_menu_entry'));

		add_action('admin_print_scripts', array($this, 'js_print_scripts'));
		add_action('admin_print_styles', array($this, 'css_print_styles'));

	}
}
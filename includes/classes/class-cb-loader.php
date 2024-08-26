<?php
/**
 * Loader Class.
 *
 * @package cb-invoice
 */

if ( ! class_exists( 'CB_Loader' ) ) {

	/**
	 * Class CB_Loader.
	 */
	class CB_Loader {

		/**
		 * Intialize plugin.
		 */
		public function __construct() {
			$this->load();
		}

		/**
		 * Load files.
		 */
		public function load() {
			include_once CB_PLUGIN_DIR . '/includes/classes/class-cb-setting.php';
			include_once CB_PLUGIN_DIR . '/includes/classes/class-cb-main.php';

		}

	}
	new CB_Loader();
}

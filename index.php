<?php 
	/**
	* Protection
	*
	* This string of code will prevent hacks from accessing the file directly.
	*/
	defined('ABSPATH') or die("Cannot access pages directly.");

	define("PIE_IMAGE_ORIGINAL_PATH", plugin_dir_path(__FILE__)."editimagesoriginal/");
	define("PIE_IMAGE_WORK_WITH_PATH", plugin_dir_path(__FILE__)."editimagesworkwith/");
	define("PIE_IMAGE_PNG_PATH", plugin_dir_path(__FILE__)."editimagespng/");
	define("PIE_PLUGINS_URL_FROM_INDEX", plugins_url('', __FILE__));
?>
<?php include plugin_dir_path(__FILE__).'lite/shared/index.php'; ?>
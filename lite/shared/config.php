<?php
    
	/**
	* Protection
	*
	* This string of code will prevent hacks from accessing the file directly.
	*/
	defined('ABSPATH') or die("Cannot access pages directly.");

    define("PIE_IMAGE_MAX_WIDTH", 900);
	define("PIE_IMAGE_MAX_HEIGHT", 1400);
	define("PIE_DEFAULT_LANGUAGE", "en-GB");
	define("PIE_AJAX_POST_TIMEOUT_MS", 20000);

	define("PIE_RESIZE_ENABLED", true);
	define("PIE_ROTATE_ENABLED", true);
	define("PIE_CROP_ENABLED", true);
	define("PIE_EFFECTS_ENABLED", true);
	
	/*	
		PIE_START_PANEL can have any of these values.
		The panel must be enabled which is set above.
		
		PIE_MENU_RESIZE
		PIE_MENU_ROTATE
		PIE_MENU_CROP
		PIE_MENU_EFFECTS
	*/
	define("PIE_START_PANEL", PIE_MENU_RESIZE);
	
?>
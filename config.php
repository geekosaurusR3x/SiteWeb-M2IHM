<?php

/*
// Override any of the default settings below:

$config['site_title'] = 'Pico';			// Site title
$config['base_url'] = ''; 				// Override base URL (e.g. http://example.com)
*/
// Force locale
setLocale(LC_ALL, 'fr_FR');
$config['site_title'] = 'Skad.co';
$config['theme'] = 'skad.co_material'; 			// Set the theme (defaults to "default")
$config['date_format'] = '%A %e %b %G';		// Set the PHP date format
/*
$config['twig_config'] = array(			// Twig settings
	'cache' => false,					// To enable Twig caching change this to CACHE_DIR
	'autoescape' => false,				// Autoescape Twig vars
	'debug' => false					// Enable Twig debug
);
*/
$config['pages_order_by'] = 'alpha';	// Order pages by "alpha" or "date"
$config['pages_order'] = 'asc';			// Order pages "asc" or "desc"
/*
$config['excerpt_length'] = 50;			// The pages excerpt length (in words)

// To add a custom config setting:

$config['custom_setting'] = 'Hello'; 	// Can be accessed by {{ config.custom_setting }} in a theme

*/

//At Navigation
$config['at_navigation']['id'] = 'slide-out';
$config['at_navigation']['class'] = 'side-nav fixed collapsible';
$config['at_navigation']['class_li'] = 'bold';
$config['at_navigation']['class_a'] = 'collapsible-header waves-effect waves-teal';
$config['at_navigation']['class_under'] = '';
$config['at_navigation']['activeClass'] = 'active teal';


//social button
$config['social']['class_prefix'] = 'btn btn-';

//google Analytic
$config['google_tracking_id'] = 'UA-47794679-1';

//pico_slider
$config['pico_slider']['image_path'] = 'content/images';
$config['pico_slider']['image_ext'] = '.jpeg';

//mcb_table_of_content
$config['mcb_toc_top_txt'] = 'Vers le haut';
$config['mcb_toc_caption'] = 'Sommaire';

/* PIWIK */
/* Piwik Hostname and Piwik install folder */
// For example : www.example.com or example.com
// If Piwik is install on the same domain as your Pico Site,
// you can let that empty or remove it from config.php
$config['piwik']['domain'] = 'piwik.skad.co';
// If empty or not present, we take "piwik" as default value
$config['piwik']['folder'] = '';
/* Website ID */
//If empty or not present, 1 is the default value
$config['piwik']['id'] = 1;
/* Tracking options */
/* Settings --> Tracking code */
// Track visitors across all subdomains of SITE NAME
$config['piwik']['subdom'] = false; /* true or false */
// Prepend the site domain to the page title when tracking
$config['piwik']['prepend'] = false; /* true or false */
// In the "Outlinks" report, hide clicks to known alias URLs of SITE NAME
$config['piwik']['outlinks'] = false; /* true or false */
// Advanced : Enable client side DoNotTrack detection
$config['piwik']['DNT'] = false; /* true or false */


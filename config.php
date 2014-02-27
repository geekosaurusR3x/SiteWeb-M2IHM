<?php

/*
// Override any of the default settings below:

$config['site_title'] = 'Pico';			// Site title
$config['base_url'] = ''; 				// Override base URL (e.g. http://example.com)
*/
// Force locale
setLocale(LC_ALL, 'fr_FR');
$config['site_title'] = 'Skad.co';
$config['theme'] = 'pico-flatui-blog'; 			// Set the theme (defaults to "default")
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
$config['at_navigation']['id'] = 'bmenu';
$config['at_navigation']['class'] = 'bmenu';
$config['at_navigation']['class_li'] = 'bemnu_li';
$config['at_navigation']['class_a'] = 'bmenu_a';

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


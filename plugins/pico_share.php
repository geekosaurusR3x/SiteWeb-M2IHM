
<?php

/**
 * Social plugin for Pico CMS
 * Adds social media buttons to posts and pages
 *
 * @author Narcis Radu
 * @link http://narcisradu.ro
 * @license http://opensource.org/licenses/MIT
 */
class Pico_Share {

	public $templates = array(
			'twitter' => 'https://twitter.com/intent/tweet?text=__TITLE__&amp;url=__URL__',
			'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=__URL__',
			'google' => 'https://plus.google.com/share?url=__URL__',
			'linkedin' => 'http://www.linkedin.com/shareArticle?mini=true&amp;url=__URL__&amp;title=__TITLE__&amp;summary=__EXCERPT__&amp;source=__URL__'
		);

	public function __construct() {
		$this->config = array(
				'services' => array(
						'twitter' => true,
						'facebook' => true,
						'google' => true,
						'linkedin' => true
					),
				'output' => 'list',
				'class_prefix' => 'btn-',
			    'icon_class_prefix' => 'icon-'
			);
	}

	public function config_loaded(&$settings) {
		if(isset($settings['social']['services'])) {
			$this->config['services'] = $settings['social']['services'];
		};
		if(isset($settings['social']['output'])) {
			$this->config['output'] = $settings['social']['output'];
		};
		if(isset($settings['social']['class_prefix'])) {
			$this->config['class_prefix'] = $settings['social']['class_prefix'];
		};
		if(isset($settings['social']['icon_class_prefix'])) {
			$this->config['icon_class_prefix'] = $settings['social']['icon_class_prefix'];
		};
	}

	public function before_render(&$twig_vars, &$twig, &$template) {
		$pageTitle = rawurlencode($twig_vars['current_page']['title']);
		$pageURL = $twig_vars['current_page']['url'];
		$pageExcerpt = rawurlencode($twig_vars['current_page']['excerpt']);
		$activeServices = array();

		foreach($this->config['services'] as $key => $value) {
			if(is_bool($value) && $value) {
				$activeServices[$key] = '<a class="'.$this->config['class_prefix'].$key.'" title="'.$key.' sharing" target="_blank" href="'.
				preg_replace(array('/__TITLE__/', '/__URL__/', '/__EXCERPT__/'), array($pageTitle, $pageURL, $pageExcerpt), $this->templates[$key]).
				'"><i class="'.$this->config['icon_class_prefix'].$key.'"></i></a>';
			};
		};
		switch($this->config['output']) {
			case 'link':
				$twig_vars['social_share'] = '<div id="share">'.implode('', array_values($activeServices)).'</div>';
				break;
			default:
				//show as list by default
				$twig_vars['social_share'] = '<ul id="share"><li>'.implode('</li><li>', array_values($activeServices)).'</li></ul>';
				break;
		};
	}
}

?>

<?php

/**
 * Slider plugin for Pico
 *
 * @author James Doyle
 * @link http://ohdoylerules.com
 * @license http://opensource.org/licenses/MIT
 */
class Pico_Slider {

	private $plugin_path;
	private $image_path;
	private $image_ext;

	public function __construct()
	{
		$this->plugin_path = dirname(__FILE__);
	}

    public function config_loaded(&$settings) {
		if(isset($settings['pico_slider']['image_path'])) {
			$this->image_path = $settings['pico_slider']['image_path'];
		};
		if(isset($settings['pico_slider']['image_ext'])) {
			$this->image_ext = $settings['pico_slider']['image_ext'];
		};						
	}
	
    
	// get_files function stolen from the pico.php lib
	private function get_files($directory, $ext = '')
	{
		$array_items = array();
		if($handle = opendir($directory)){
			while(false !== ($file = readdir($handle))){
				if($file != "." && $file != ".."){
					if(is_dir($directory. "/" . $file)){
						$array_items = array_merge($array_items, $this->get_files($directory. "/" . $file, $ext));
					} else {
						$file = $directory . "/" . $file;
						if(!$ext || strstr($file, $ext)) $array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			closedir($handle);
		}
		return $array_items;
	}

	public function before_render(&$twig_vars, &$twig)
	{
		// assign the images to the twig_vars
		$twig_vars['images'] = $this->get_files($this->image_path, $this->image_ext);
		foreach ($twig_vars['images'] as &$image) {
			$temp_array = array();
			// lazy link to the image
			$temp_array['url'] = $twig_vars['base_url'].'/'.$image;
			// read the image info and assign the width and height
			$image_info = getimagesize($image);
			$temp_array['width'] = $image_info[0];
			$temp_array['height'] = $image_info[1];
			// strip the folder names and just leave the end piece without the extension
			$temp_array['name'] = preg_replace('/\.(jpg|jpeg|png|gif|webp)/', '', str_replace($this->image_path.'/', '', $image));
			$image = $temp_array;
		}
		return;
	}

}

?>
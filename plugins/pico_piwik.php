<?php
/**
 * Pico Piwik
 *
 * Adds automatically Piwik to your pages
 *
 * @author Brice Boucard
 * @link https://github.com/bricebou/pico_piwik
 * @license http://bricebou.mit-license.org/
 */

class Pico_Piwik {

	public function config_loaded(&$settings)
	{
		if (isset($settings['piwik']['domain']) && $settings['piwik']['domain'] != '')
		{
			$this->piwik_domain = $settings['piwik']['domain'];
		}
		elseif (!isset($settings['piwik']['domain']) || (isset($settings['piwik']['domain']) && $settings['piwik']['domain'] == ''))
		{
			$this->piwik_domain = $_SERVER['HTTP_HOST'];
		}
		if (isset($settings['piwik']['folder']) && $settings['piwik']['folder'] != '')
		{
			$this->piwik_folder = $settings['piwik']['folder'];
		}
		elseif (!isset($settings['piwik']['folder']) || (isset($settings['piwik']['folder']) && $settings['piwik']['folder'] == ''))
		{
			$this->piwik_folder = 'piwik';
		}
		if (isset($settings['piwik']['id']) && $settings['piwik']['id'] != '')
		{
			$this->piwik_id = $settings['piwik']['id'];
		}
		elseif (!isset($settings['piwik']['id']) || (isset($settings['piwik']['id']) && $settings['piwik']['id'] == ''))
		{
			$this->piwik_id = 1;
		}
		/* get options settings */
		if (isset($settings['piwik']['subdom']))
		{
			$this->piwik_subdom = $settings['piwik']['subdom'];
		}
		if (isset($settings['piwik']['prepend']))
		{
			$this->piwik_prepend = $settings['piwik']['prepend'];
		}
		if (isset($settings['piwik']['outlinks']))
		{
			$this->piwik_outlinks = $settings['piwik']['outlinks'];
		}
		if (isset($settings['piwik']['DNT']))
		{
			$this->piwik_dnt = $settings['piwik']['DNT'];
		}
	}

	public function build_piwik()
	{
		$piscript = '
		<!-- Piwik -->
		<script type="text/javascript">
			var _paq = _paq || [];';
		if(isset($this->piwik_subdom) && $this->piwik_subdom === true)
		{
			$piscript .= '
			_paq.push(["setCookieDomain", "*.'.$this->piwik_domain.'"]);';
		}
		if(isset($this->piwik_prepend) && $this->piwik_prepend === true)
		{
			$piscript .= '
			_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);';
		}
		if(isset($this->piwik_outlinks) && $this->piwik_outlinks === true)
		{
			$piscript .= '
			_paq.push(["setDomains", ["*.'.$this->piwik_domain.'"]]);';
		}
		if(isset($this->piwik_dnt) && $this->piwik_dnt === true)
		{
			$piscript .= '
			_paq.push(["setDoNotTrack", true]);';
		}
		$piscript .= '
			_paq.push([\'trackPageView\']);
			_paq.push([\'enableLinkTracking\']);
			(function() {
				var u=(("https:" == document.location.protocol) ? "https" : "http") + "://'.$this->piwik_domain.'/";
				_paq.push([\'setTrackerUrl\', u+\'piwik.php\']);
				_paq.push([\'setSiteId\', '.$this->piwik_id.']);
				var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0]; g.type=\'text/javascript\';
				g.defer=true; g.async=true; g.src=u+\'piwik.js\'; s.parentNode.insertBefore(g,s);
			})();
		</script>
		<noscript><p><img src="http://'.$this->piwik_domain.'/piwik.php?idsite='.$this->piwik_id.'" style="border:0;" alt="" /></p></noscript>
		';
		return $piscript;
	}

	public function after_render(&$output)
	{
		$output = str_replace('</body>', PHP_EOL.$this->build_piwik().'</body>', $output);
	}
}
?>

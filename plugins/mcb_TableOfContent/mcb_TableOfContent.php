<?php

/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_TableOfContent
 * @version 0.1 alpha
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 */
class mcb_TableOfContent {

   // default settings
   private $depth = 3;
   private $min_headers = 3;
   private $top_txt = 'Top';
   private $caption = '';
   private $anchor = false;
   private $top_link;
   private $classs = '';

	// internal
   private $toc = '';
   private $xpQuery;

   private function makeToc(&$content)
   {
      //get the headings
      if(preg_match_all('/<h[1-'.$this->depth.']{1,1}[^>]*>.*?<\/h[1-'.$this->depth.']>/s',$content,$headers) === false)
         return "";

      //create the toc
      $heads = implode("\n",$headers[0]);
      $heads = preg_replace('/<a.+?\/a>/','',$heads);
      $heads = preg_replace('/<h([1-6]) id="?/','<li class="toc$1"><a href="#',$heads);
      $heads = preg_replace('/<\/h[1-6]>/','</a></li>',$heads);

      $cap = $this->caption =='' ? "" :  '<p id="toc-header">'.$this->caption.'</p>';

      return '<div id="toc" class="'.$this->classs.'">'.$cap.'<ul class="section table-of-contents">'.$heads.'</ul></div>';
   }

   public function config_loaded(&$settings)
   {
      if(isset($settings['mcb_toc_depth'      ])) $this->depth       = &$settings['mcb_toc_depth'];
      if(isset($settings['mcb_toc_min_headers'])) $this->min_headers = &$settings['mcb_toc_min_headers'];
      if(isset($settings['mcb_toc_top_txt'    ])) $this->top_txt     = &$settings['mcb_toc_top_txt'];
      if(isset($settings['mcb_toc_caption'    ])) $this->caption     = &$settings['mcb_toc_caption'];
      if(isset($settings['mcb_toc_anchor'     ])) $this->anchor      = &$settings['mcb_toc_anchor'];
      if(isset($settings['top_link'           ])) $this->top_link    = &$settings['top_link'];
      if(isset($settings['mcb_toc_main_class' ])) $this->classs    = &$settings['mcb_toc_main_class'];

      for ($i=1; $i <= $this->depth; $i++) {
         $this->xpQuery[] = "//h$i";
      }
      $this->xpQuery = join("|", $this->xpQuery);

      $this->top_link = '<a title="Retourner en haut de la page" href="#top" class="toc-nav">'.$this->top_txt.'</a>';
   }

   public function after_parse_content(&$content)
   {
      if(trim($content)=="")
        return;
      // Workaround from cbuckley:
      // "... an alternative is to prepend the HTML with an XML encoding declaration, provided that the
      // document doesn't already contain one:
      //
      // http://stackoverflow.com/questions/8218230/php-domdocument-loadhtml-not-encoding-utf-8-correctly
      $domdoc = new DOMDocument();
      $domdoc->loadHTML('<?xml encoding="utf-8" ?>' . $content);
      $xp = new DOMXPath($domdoc);

      $nodes =$xp->query($this->xpQuery);

      if($nodes->length < $this->min_headers)
         return;

      // add missing id's to the h tags
      $id = 0;
      foreach($nodes as $i => $sort)
      {
          if (isset($sort->tagName) && $sort->tagName !== '')
          {
             if($sort->getAttribute('id') === "")
             {
                ++$id;
                $sort->setAttribute('id', "mcb_toc_head$id");
             }
             $a = $domdoc->createElement('a', $this->top_txt);
             $a->setAttribute('href', '#top');
             $a->setAttribute('title', 'Retourner en haut de la page');
             $a->setAttribute('class', 'toc-nav');
             $sort->appendChild($a);
          }
      }
      // add top anchor
      if($this->anchor)
      {
         $body = $xp->query("//body/node()")->item(0);
         $a = $domdoc->createElement('a');
         $a->setAttribute('name', 'top');
         $body->parentNode->insertBefore($a, $body);
      }

      $content = preg_replace(
                     array("/<(!DOCTYPE|\?xml).+?>/", "/<\/?(html|body)>/"),
                     array(                         "",                   ""),
                     $domdoc->saveHTML()
                              );

      $this->toc = $this->makeToc($content);
   }

   public function before_render(&$twig_vars, &$twig)
   {
      $twig_vars['mcb_toc'] = $this->toc;
      $twig_vars['mcb_toc_top'] = $this->anchor ? "" : '<a id="top"></a>';
      $twig_vars['mcb_top_link'] = $this->top_link;
   }

   /* debug
   public function after_render(&$output)
   {
      $output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
   }*/
}

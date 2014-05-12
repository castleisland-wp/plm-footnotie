<?php
/*
Plugin Name: Footnotie
Plugin URI: http://www.paulmcelligott.com
Description: The title says it all.
Version: 0.1
Author: Paul McElligott
Author URI: http://www.paulmcelligott.com
License: GPL
*/
class PLM_footnotie {

	public function __construct() {
		add_filter('the_content', array($this,'footie_filter'));
		add_action('wp_enqueue_scripts',  array($this, 'styles_and_scripts'));
	}
	
	public function footie_filter($content) {
		$pattern= '%\s\[\[(.*?)\]\]%';
	
		preg_match_all($pattern, $content, $footnotes);

		$notes = $footnotes[1];
	
		$patterns = $footnotes[0];

		$links = array();

		$notelist = '<ol class="notelist">';

		for($i=0; $i < count($notes); $i++) {
			$c = $i + 1;
			$links[$i] = '<span class="notelink">[<a id="link-' . $c . '"></a><a href="#note-' . $c . '">' . $c . '</a>]</span> ';
			$patterns[$i] = '%' . preg_quote($patterns[$i], '%') . '%';
			$notelist .= "\n" . '<li><a id="note-' . $c . '"></a>' . $notes[$i] . ' [<span class="notelink"><a href="#link-' . $c . '">&#x2b0f;</a></span>]' . "\n";
		}
		$notelist .= '</ol>';

		if(is_single() | is_page()) {
			$content = preg_replace($patterns, $links, $content);

			$content .= $notelist;
		} else {
			$content = preg_replace($pattern, ' ', $content);
		}

		

		return  $content;
	}

	public function styles_and_scripts() {
		wp_enqueue_style('plm_footie_style', plugins_url('style.css', __FILE__));
	}

}

$plm_footie = new PLM_footnotie;
?>
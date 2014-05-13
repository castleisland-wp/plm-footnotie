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
include(plugin_dir_path(__FILE__) . '/plm-footnotie-settings.php');

class PLM_footnotie {

	public function __construct() {
		add_filter('the_content', array($this,'footie_filter'));
		add_action('wp_enqueue_scripts',  array($this, 'styles_and_scripts'));
	}
	
	public function footie_filter($content) {
		$pattern= '%\s\[\[(.*?)\]\]%';

		$options = get_option('plm-footie-settings');

		$link_classes = implode(' ', array('notelink', $options['placement']));

		if ( $options['placement'] == 'drop') {
			$drop_classes = implode(' ', array($options['background'], $options['corners'], 'dropbox'));
			$note_classes = 'notelist no-list';
		} else {
			$note_classes = 'notelist';
		}
	
		preg_match_all($pattern, $content, $footnotes);

		$notes = $footnotes[1];
	
		$patterns = $footnotes[0];

		$links = array();

		$notelist = sprintf('<ol class="%s">', $note_classes);

		for($i=0; $i < count($notes); $i++) {
			$c = $i + 1;
			$links[$i] = sprintf('<span class="%2$s">[<a id="link-%1$s"></a><a href="#note-%1$s">%1$s</a>]', $c, $link_classes);
			
			if($options['placement'] == 'drop') {
				$links[$i] .= sprintf('<span class="%1$s">%3$s. %2$s</span>', $drop_classes, $notes[$i], $c);
			}

			$links[$i] .= '</span>'; 

			$patterns[$i] = '%' . preg_quote($patterns[$i], '%') . '%';

			$notelist .= "\n" . sprintf('<li><a id="note-%1$s"></a>%2$s [<span class="notelink"><a href="#link-%1$s">&#x2b0f;</a></span>]', $c, $notes[$i]) . "\n";
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
		wp_register_script('plm_footie_jquery', plugins_url('js/footie.js', __FILE__), 'jquery', '1.0', true);
		wp_enqueue_script('plm_footie_jquery');
	}

}

$plm_footie = new PLM_footnotie;
?>
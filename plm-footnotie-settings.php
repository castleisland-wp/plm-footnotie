<?php 

function plm_footie_admin() {
	add_options_page(
	"Footnotes Configuration",
	"Footnote Options",
	'manage_options',
	'plm_footnote_admin',
	'plm_footnote_admin_display'
	);
}

add_action('admin_menu', 'plm_footie_admin');


function plm_footnote_admin_display() {
?>
		<div class="wrap">
			<pre><?php print_r(get_option('plm-footie-settings'), true) ?></pre>
			<h2><?php echo $GLOBALS['title'] ?></h2>
			<form method="POST" action="options.php">
				<?php settings_fields('plm:footie_option_group'); ?>
				<?php do_settings_sections('plm_footnote_admin'); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php 
}

function plm_footnote_settings() {

	$option_name = 'plm-footie-settings';

	$current_config = get_option($option_name);

	$default_config = array(
		'placement' => 'foot',
		'corners' => 'round',
		'background' => 'trans',
		'ver' => '0.1'
		);

	$config = shortcode_atts($default_config, $current_config);

	register_setting(
		'plm:footie_option_group',
		$option_name,
		'plm_footie_validate'
		);

	add_settings_section(
		'plm:footie_section_1',
		'Select from options below:',
		'plm_render_section_1',
		'plm_footnote_admin'
		);

	add_settings_field(
		'plm:footie_section_1_1_place',
		'Placement of footnote',
		'plm_footie_1_1_render',
		'plm_footnote_admin',
		'plm:footie_section_1',
		array(
			'label_for' => 'placement',
			'name' => 'placement',
			'value' => esc_attr ($config['placement'] ),
			'options' => array(
				'foot' => 'Bottom of post or page',
				'drop' => 'As a dropdown box.'
				),
			'option_name' => $option_name,
			'label' => 'Select placement of footnote.'
		)
	);
 
add_settings_field(
		'plm:footie_section_1_2_corner',
		'Corners',
		'plm_footie_1_1_render',
		'plm_footnote_admin',
		'plm:footie_section_1',
		array(
			'label_for' => 'corners',
			'name' => 'corners',
			'value' => esc_attr ($config['corners'] ),
			'options' => array(
				'round' => 'Round corners',
				'square' => 'Square corners'
				),
			'option_name' => $option_name,
			'label' => 'Select type of corner.'
		)
	);

add_settings_field(
		'plm:footie_section_1_3_corner',
		'Corners',
		'plm_footie_1_1_render',
		'plm_footnote_admin',
		'plm:footie_section_1',
		array(
			'label_for' => 'background',
			'name' => 'background',
			'value' => esc_attr ($config['background'] ),
			'options' => array(
				'trans' => 'Translucent',
				'square' => 'Solid'
				),
			'option_name' => $option_name,
			'label' => 'Select background opacity.'
		)
	);
 
}

if ( ! empty ( $GLOBALS['pagenow'] )
   and ( 'options-general.php' === $GLOBALS['pagenow']
       or 'options.php' === $GLOBALS['pagenow']
   )
)
{
    add_action( 'admin_init', 'plm_footnote_settings' );
}


function plm_render_section_1() {

	echo '<p>Configure the placement and appearance of footnotes.</p>';

}

function plm_footie_1_1_render($args) {

	printf(
		'<select name="%1$s[%2$s]" id="%3$s">', 
		$args['option_name'],
		$args['name'],
		$args['label_for']
		);

	foreach($args['options'] as $val => $title) {
		printf(
			'<option value="%1$s" %2$s>%3$s</option>',
			$val,
			selected($val, $args['value'], FALSE),
			$title
			);
	}
	echo '</select>';
	printf('<label for="%1$s">&nbsp;%2$s</label>',
		$args['label_for'],
		$args['label']
		);
}

function plm_footie_validate($values) {

	$default_config = array(
		'placement' => 'foot',
		'corners' => 'round',
		'background' => 'trans',
		'ver' => '0.1'
	);

	$output = array();

	if(!is_array($values)) {
		$output = $default_config;
	} 

	foreach ( $values as $key => $value ) {
		$output[$key] = $value;
	}

	$out = shortcode_atts($default_config,$output);

	return $out;

}

function plm_footie_activate() {
	$option_name = 'plm-footie-settings';


	if (!get_option($option_name)) {
		add_option($option_name, 
			array(
				'placement' => 'foot',
				'corners' => 'round',
				'background' => 'trans',
				'ver' => '0.1'
			)
		);	
	}
}

register_activation_hook(__FILE__ , 'plm_footie_activate');
?>
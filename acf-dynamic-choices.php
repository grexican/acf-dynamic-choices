<?php
/**
 * Plugin Name: Advanced Custom Fields Dynamic Choices
 * Plugin URI: http://steppingback.com/project/acf-dynamic-choices/
 * Description: Allows for dynamic choices (through SQL Queries) in ACF
 * Version: .5
 * Stable tag: .5
 * Author: Eli Gassert
 * Author URI: http://steppingback.com
 * License: MIT
 */
 
global $acf_dynamic_choices_needs_values;
$acf_dynamic_choices_needs_values = false;

// I'm sure there's an ACF way to know if you're on the "setup" page vs the "in action" page, but I wasn't finding it, so I sniff it myself.
function acf_dynamic_choices_acf_admin_head()
{
	global $acf_dynamic_choices_needs_values;
	$acf_dynamic_choices_needs_values = true;
}
add_filter('acf/input/admin_head', 'acf_dynamic_choices_acf_admin_head');

function acf_dynamic_choices_acf_load_field($field)
{
	global $acf_dynamic_choices_needs_values;
	global $wpdb;
	
	if(!$acf_dynamic_choices_needs_values) return $field;
	
	$choices = $field['choices'];
	
	$newChoices = array();
	
	foreach($choices as $key => $choice)
	{
		if(strtolower($key) == '%%query%%')
		{
			$results = $wpdb->get_results($choice);// or die(mysql_error());
			
			foreach($results as $r)
			{
				$newChoices[$r->value] = $r->text;
			}
		}
		else
		{
			$newChoices[$key] = $choice;
		}
	}
	
	$field['choices'] = $newChoices;
	
	return $field;
}
add_filter('acf/load_field/type=select', 'acf_dynamic_choices_acf_load_field');


function action_function_name( $field ) {
 
	echo '<pre>Some extra HTML</pre>';
 
}
add_filter( 'acf/render_field', 'action_function_name', 10, 1 );
// END ACF QUERY SELECT PLUGIN

?>
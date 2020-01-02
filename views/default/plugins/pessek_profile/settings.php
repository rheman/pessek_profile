<?php
$entity = elgg_extract('entity', $vars);

$enable_linkedin_profile = elgg_view_field(array(
		   '#type' => 'select',
		   '#label' => elgg_echo('pessek_linkedin:settings:pesseklinkedin'),
		   'required' => true,
		   'name' => 'params[pesseklinkedin]',
		   'value' => $entity->pesseklinkedin,
		   'options_values' => array(
		      'false' => elgg_echo('option:no'),
		      'true' => elgg_echo('option:yes'),
		   ),
		   'class' => 'mls',
	));

echo elgg_format_element('div', [], $enable_linkedin_profile);

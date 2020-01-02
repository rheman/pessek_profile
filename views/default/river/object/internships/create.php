<?php
/**
 * Update profile river view
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$subject = $item->getSubjectEntity();

$object_guid = $vars['item']->object_guid;
$interships = get_entity($object_guid);
$company_name = elgg_view('output/url', array(
	'href' => '#',
	'text' => $interships->companyname,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));
$jobtitle = elgg_view('output/url', array(
	'href' => '#',
	'text' => $interships->title,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));
$country_place = elgg_view('output/url', array(
	'href' => '#',
	'text' => $interships->country.'('.$interships->place.')',
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$view_changes = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => elgg_echo('gcconnex_profile:about_me:click:here'),
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$summary = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => elgg_echo('gcconnex_profile:internship:lib:lib'),
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$string = elgg_echo('gcconnex_profile:internship:river:add', [$subject_link, $summary, $country_place, $company_name, $jobtitle, $view_changes]); //$string = elgg_echo('gcconnex_profile:about_me:add:summary', {[@^]array($subject_link));

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'summary' => $string,
	//'responses' => false,
));
 

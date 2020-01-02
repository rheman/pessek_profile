<?php
/**
 * Update profile river view
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$subject = $item->getSubjectEntity();

$object_guid = $vars['item']->object_guid;
$portfolio = get_entity($object_guid);


$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
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
	'text' => elgg_echo('gcconnex_profile:portfolio'),
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$string = elgg_echo('gcconnex_profile:portfolio:river:add', [$subject_link, $summary, $view_changes]); //$string = elgg_echo('gcconnex_profile:about_me:add:summary', {[@^]array($subject_link));

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'summary' => $string,
	//'responses' => false,
));
 

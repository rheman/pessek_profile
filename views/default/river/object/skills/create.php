<?php
/**
 * Update profile river view
 */

$item = $vars['item'];

$skills = "les competences";
/* @var ElggRiverItem $item */

$subject = $item->getSubjectEntity();

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
	'text' => $skills,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$string = elgg_echo('gcconnex_profile:skills:add:river', [$subject_link, $view_changes]); //$string = elgg_echo('gcconnex_profile:about_me:add:summary', {[@^]array($subject_link));

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'summary' => $string,
	//'responses' => false,
));
 
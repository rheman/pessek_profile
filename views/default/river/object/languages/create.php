<?php
/**
 * Update profile river view
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$subject = $item->getSubjectEntity();

$object_guid = $vars['item']->object_guid;
$languages = get_entity($object_guid);
$language_level = array(
                        '' => elgg_echo('gcconnex_profile:langs:select'),
                        '0' => elgg_echo('gcconnex_profile:langs:elementory'),
                        '1' => elgg_echo('gcconnex_profile:langs:limited:working'),
                        '2' => elgg_echo('gcconnex_profile:langs:professional:working'),
                        '3' => elgg_echo('gcconnex_profile:langs:full:professional'),
                        '4' => elgg_echo('gcconnex_profile:langs:native'));
$language_title = elgg_view('output/url', array(
	'href' => '#',
	'text' => $languages->langs,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));
$language_level = elgg_view('output/url', array(
	'href' => '#',
	'text' => $language_level[$languages->level],
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));


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
	'text' => elgg_echo('gcconnex_profile:langs:title'),
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$string = elgg_echo('gcconnex_profile:langs:river:add', [$subject_link, $summary, $language_title, $language_level, $view_changes]); //$string = elgg_echo('gcconnex_profile:about_me:add:summary', {[@^]array($subject_link));

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'summary' => $string,
	//'responses' => false,
));
 

<?php
/**
 * Update profile river view
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$subject = $item->getSubjectEntity();

$object_guid = $vars['item']->object_guid;
$projects = get_entity($object_guid);
$month_selected =    array(
                            '0' => elgg_echo('pessek_profile:January:lib'), 
                            '1' => elgg_echo('pessek_profile:February:lib'),
                            '2' => elgg_echo('pessek_profile:March:lib'),
                            '3' => elgg_echo('pessek_profile:April:lib'),
                            '4' => elgg_echo('pessek_profile:May:lib'),
                            '5' => elgg_echo('pessek_profile:June:lib'),
                            '6' => elgg_echo('pessek_profile:July:lib'),
                            '7' => elgg_echo('pessek_profile:August:lib'),
                            '8' => elgg_echo('pessek_profile:September:lib'),
                            '9' => elgg_echo('pessek_profile:October:lib'),
                            '10' => elgg_echo('pessek_profile:November:lib'),
                            '11' => elgg_echo('pessek_profile:December:lib'));
$project_title = elgg_view('output/url', array(
	'href' => '#',
	'text' => $projects->title,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$start_date = ' ' . $month_selected[$projects->startmonth]  .' ' . $projects->startyear . ' - ';
            $end_date = elgg_echo('gcconnex_profile:projects:certexpired') . ' ';
            
if ( $projects->ongoing != true ) {
    $end_date = ' ' . $month_selected[$projects->endmonth]  .' ' . $projects->endyear . ' ';
}

$project_duration = elgg_view('output/url', array(
	'href' => '#',
	'text' => $start_date . '  ' .$end_date,
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
	'text' => elgg_echo('gcconnex_profile:projects'),
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$string = elgg_echo('gcconnex_profile:projects:river:add', [$subject_link, $summary, $project_title, $project_duration, $view_changes]); //$string = elgg_echo('gcconnex_profile:about_me:add:summary', {[@^]array($subject_link));

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'summary' => $string,
	//'responses' => false,
));
 

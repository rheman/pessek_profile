<?php

/**
* Profile Manager
*
* Category delete action
*
* @package profile_manager
* @author ColdTrick IT Solutions
* @copyright Coldtrick IT Solutions 2009
* @link http://www.coldtrick.com/
*/

$projects_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($projects_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$projects = get_entity($projects_guid);


if ($projects->delete()) {
	system_message(elgg_echo('gcconnex_profile:projects:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:projects:delete:error:delete'));
}

$user = get_user($user_guid);
$projects_list = $user->projects;

if (is_array($projects_list)) {
    if (($key = array_search($projects_guid, $projects_list)) !== false) {
        unset($projects_list[$key]);
    }
} elseif ($projects_list == $projects_guid) {
    $projects_list = null;
}

$user->projects = $projects_list;

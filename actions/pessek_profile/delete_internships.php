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

$internships_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($internships_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$internships = get_entity($internships_guid);


if ($internships->delete()) {
	system_message(elgg_echo('gcconnex_profile:internship:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:internship:delete:error:delete'));
}

$user = get_user($user_guid);
$internships_list = $user->internships;

if (is_array($internships_list)) {
    if (($key = array_search($internships_guid, $internships_list)) !== false) {
        unset($internships_list[$key]);
    }
} elseif ($internships_list == $internships_guid) {
    $internships_list = null;
}

$user->internships = $internships_list;

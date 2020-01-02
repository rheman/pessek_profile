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

$experiences_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($experiences_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$experiences = get_entity($experiences_guid);


if ($experiences->delete()) {
	system_message(elgg_echo('gcconnex_profile:experience:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:experience:delete:error:delete'));
}

$user = get_user($user_guid);
$experiences_list = $user->experiences;

if (is_array($experiences_list)) {
    if (($key = array_search($experiences_guid, $experiences_list)) !== false) {
        unset($experiences_list[$key]);
    }
} elseif ($experiences_list == $experiences_guid) {
    $experiences_list = null;
}

$user->experiences = $experiences_list;

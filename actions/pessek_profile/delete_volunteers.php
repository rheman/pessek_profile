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

$volunteers_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($volunteers_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$volunteers = get_entity($volunteers_guid);


if ($volunteers->delete()) {
	system_message(elgg_echo('gcconnex_profile:volunteer:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:mooc:volunteer:error:delete'));
}

$user = get_user($user_guid);
$volunteers_list = $user->volunteers;

if (is_array($volunteers_list)) {
    if (($key = array_search($volunteers_guid, $volunteers_list)) !== false) {
        unset($volunteers_list[$key]);
    }
} elseif ($volunteers_list == $volunteers_guid) {
    $volunteers_list = null;
}

$user->volunteers = $volunteers_list;

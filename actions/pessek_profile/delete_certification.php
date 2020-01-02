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

$certification_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($certification_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$certification = get_entity($certification_guid);


if ($certification->delete()) {
	system_message(elgg_echo('gcconnex_profile:certification:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:portfolio:certification:error:delete'));
}

$user = get_user($user_guid);
$certification_list = $user->certification;

if (is_array($certification_list)) {
    if (($key = array_search($certification_guid, $certification_list)) !== false) {
        unset($certification_list[$key]);
    }
} elseif ($certification_list == $certification_guid) {
    $certification_list = null;
}

$user->certification = $certification_list;

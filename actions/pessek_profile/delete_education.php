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

$education_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($education_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$education = get_entity($education_guid);


if ($education->delete()) {
	system_message(elgg_echo('gcconnex_profile:education:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:delete'));
}

$user = get_user($user_guid);
$education_list = $user->education;

if (is_array($education_list)) {
    if (($key = array_search($education_guid, $education_list)) !== false) {
        unset($education_list[$key]);
    }
} elseif ($education_list == $education_guid) {
    $education_list = null;
}

$user->education = $education_list;

forward(REFERER);

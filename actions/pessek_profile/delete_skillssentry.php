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

$skills_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($skills_guid)) {
	//register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	//forward(REFERER);
}

$skills = get_entity($skills_guid);


if ($skills->delete()) {
	//system_message(elgg_echo('gcconnex_profile:langs:delete:succes'));
} else {
	//register_error(elgg_echo('gcconnex_profile:langs:delete:error:delete'));
}

$user = get_user($user_guid);
$skills_list = $user->skills;

if (is_array($skills_list)) {
    if (($key = array_search($skills_guid, $skills_list)) !== false) {
        unset($skills_list[$key]);
    }
} elseif ($skills_list == $skills_guid) {
    $skills_list = null;
}

$user->skills = $skills_list;

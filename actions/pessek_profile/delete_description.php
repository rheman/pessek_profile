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

$description_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($description_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$description = get_entity($description_guid);


if ($description->delete()) {
	system_message(elgg_echo('gcconnex_profile:about_me:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:about_me:delete:error:delete'));
}

$user = get_user($user_guid);
$description_list = $user->description;

if (is_array($description_list)) {
    if (($key = array_search($description_guid, $description_list)) !== false) {
        unset($description_list[$key]);
    }
} elseif ($description_list == $description_guid) {
    $description_list = null;
}

$user->description = $description_list;

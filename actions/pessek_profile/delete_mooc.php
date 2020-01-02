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

$mooc_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($mooc_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$mooc = get_entity($mooc_guid);


if ($mooc->delete()) {
	system_message(elgg_echo('gcconnex_profile:mooc:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:mooc:delete:error:delete'));
}

$user = get_user($user_guid);
$mooc_list = $user->mooc;

if (is_array($mooc_list)) {
    if (($key = array_search($mooc_guid, $mooc_list)) !== false) {
        unset($mooc_list[$key]);
    }
} elseif ($mooc_list == $mooc_guid) {
    $mooc_list = null;
}

$user->mooc = $mooc_list;

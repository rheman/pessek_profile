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

$languages_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($languages_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$languages = get_entity($languages_guid);


if ($languages->delete()) {
	system_message(elgg_echo('gcconnex_profile:langs:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:langs:delete:error:delete'));
}

$user = get_user($user_guid);
$languages_list = $user->languages;

if (is_array($languages_list)) {
    if (($key = array_search($languages_guid, $languages_list)) !== false) {
        unset($languages_list[$key]);
    }
} elseif ($languages_list == $languages_guid) {
    $languages_list = null;
}

$user->languages = $languages_list;

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

$publications_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($publications_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$publications = get_entity($publications_guid);


if ($publications->delete()) {
	system_message(elgg_echo('gcconnex_profile:publications:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:mooc:publications:error:delete'));
}

$user = get_user($user_guid);
$publications_list = $user->publications;

if (is_array($publications_list)) {
    if (($key = array_search($publications_guid, $publications_list)) !== false) {
        unset($publications_list[$key]);
    }
} elseif ($publications_list == $publications_guid) {
    $publications_list = null;
}

$user->publications = $publications_list;

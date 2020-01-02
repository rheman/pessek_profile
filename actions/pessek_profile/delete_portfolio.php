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

$portfolio_guid = (int) get_input('guide');
$user_guid = (int) get_input('guidp');

if (empty($portfolio_guid)) {
	register_error(elgg_echo('gcconnex_profile:education:delete:error:guid'));
	forward(REFERER);
}

$portfolio = get_entity($portfolio_guid);


if ($portfolio->delete()) {
	system_message(elgg_echo('gcconnex_profile:portfolio:delete:succes'));
} else {
	register_error(elgg_echo('gcconnex_profile:portfolio:delete:error:delete'));
}

$user = get_user($user_guid);
$portfolio_list = $user->portfolio;

if (is_array($portfolio_list)) {
    if (($key = array_search($portfolio_guid, $portfolio_list)) !== false) {
        unset($portfolio_list[$key]);
    }
} elseif ($portfolio_list == $portfolio_guid) {
    $portfolio_list = null;
}

$user->portfolio = $portfolio_list;

//forward(REFERER);
//$portfolio_guid = (string) get_input('guide');register_error($portfolio_guid);
//$user_guid = (string) get_input('guidp');register_error($user_guid);
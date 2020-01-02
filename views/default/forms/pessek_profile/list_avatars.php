<?php

$user_guid = get_input('user_guid');

$var = unserialize(urldecode($user_guid));

echo '<span><strong>' . elgg_echo('gcconnex_profile:skills:recommended') . '</strong></span><br>';

echo '<br>' . list_avatars(array(
        'guids' => $var,
        'size' => 'medium',//medium
        'limit' => 0
    )). '<br>';

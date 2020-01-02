<?php
//session_start();
/*
$as = new \SimpleSAML\Auth\Simple('linkedinauf');
  $as->requireAuth(array(
    'ReturnTo' => 'https://esfam-simplesamlphp.auf.org/profile/admin'
));
  $attributes = $as->getAttributes();
*/

$user_guid = (int) get_input("guid");
$user = get_user($user_guid);

//picture profile

    $user_icon = $user->getIconURL('medium');

// basic profile

    ($user->phone != null) ? $phone = $user->phone: $phone='';
    ($user->mobile != null) ? $mobile = $user->mobile: $mobile='';

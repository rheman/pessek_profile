<?php
/*
 * Author: Bryden Arndt
 * Date: 01/07/2015
 * Purpose: Create the ajax view for editing the certification entries.
 * Requires: gcconnex-profile.js in order to handle the add more and delete buttons which are triggered by js calls
 */

if (elgg_is_xhr()) {  //This is an Ajax call!
    // allow the user to edit the access settings for certification entries
    echo elgg_echo('gcconnex_profile:certification:access');

    $access_id = $user->certification_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel['certification']",
        'value' => $access_id,
        'class' => 'gcconnex-certification-access'
    );

    echo elgg_view('input/access', $params);
    //$user_guid = $_GET["user"];
    $user_guid = $_GET["guid"];
    $user = get_user($user_guid);

    //get the array of user certification entities
    $certification_guid = $user->certification;
    
    echo '<br><br><strong>' .elgg_echo('gcconnex_profile:certification:listening'). '</strong>';
    
    echo '<div class="gcconnex-certification-all">';

    // handle $certification_guid differently depending on whether it's an array or not
    if (is_array($certification_guid)) {
        foreach ($certification_guid as $guid) { // display the input/certification view for each certification entry
            if ( $guid != null ) {
                echo elgg_view('input/certification', array('guid' => $guid));
            }
        }
    }
    else {
        if ($certification_guid != null && !empty($certification_guid)) {
            echo elgg_view('input/certification', array('guid' => $certification_guid));
        }
    }


    echo '</div>';

    // create an "add more" button at the bottom of the certification input fields so that the user can continue to add more certification entries as needed
    echo '<div class="gcconnex-certification-add-another elgg-button elgg-button-action btn" data-type="certification" onclick="addMore(this)">' . elgg_echo('gcconnex_profile:certification:add') . '</div>';
}

else {  // In case this view will be called via elgg_view()
    echo 'An error has occurred. Please ask the system administrator to grep: DZZZNSJ662277';
}

?>
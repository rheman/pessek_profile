<?php
/*
 * Author: Bryden Arndt
 * Date: 01/07/2015
 * Purpose: Ajax view for editing the about-me entry for user profiles
 * Requires: tinyMCE (which is loaded as a plugin in the prod GCconnex environment)
 */

if (elgg_is_xhr()) {  //This is an Ajax call!

    // load the user entity
    $user_guid = $_GET["guid"];
    $user = get_user($user_guid);

    // get the about-me text (saved in ->description)
    $value = $user->description;

    // setup the about-me longtext input
    $params = array(
        'name' => 'description',
        'id' => 'aboutme',
        'class' => 'mceContentBody about-me-longtext',
        'value' => $value,
    );

    // about-me longtext input
    echo elgg_view("input/text", $params);//longtext

    $access_id = ACCESS_DEFAULT; // @todo: set this access based on user settings

}

else {  // In case this view will be called via elgg_view()
    echo 'ERROR: Tell sys admin to grep for: AFJ367FAXB'; // random alphanumeric string to grep later if needed
}
?>

<!-- initialize and load the longtext wysiwyg editor

<script type="text/javascript">
    tinyMCE.init({
        mode : "exact",
        elements: "aboutme"
    });
</script>

-->
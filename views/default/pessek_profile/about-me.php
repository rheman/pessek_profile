<?php
/**
 * Created by PhpStorm.
 * User: barndt
 * Date: 23/03/15
 * Time: 2:02 PM
 */

if (elgg_is_xhr()) {
    $user_guid = $_GET["guid"];
}
else {
    $user_guid = elgg_get_page_owner_guid();
}

$section = 'description';
$user_guid2 = elgg_get_logged_in_user_entity()->guid;
$user2 = get_user($user_guid2);
$deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user2->getDisplayName() ; //$user2->username

$user = get_user($user_guid);

$description_guid = $user->description;

echo '<div class="gcconnex-description">';

    echo '<div class="gcconnex-profile-description-display">';

    echo '<table width="100%">';

    if ($user->canEdit() && ($description_guid == NULL || empty($description_guid))) {
        echo elgg_echo('gcconnex_profile:about_me:empty');
    }
    else { //$user->description = NULL; //echo '<h1>123456789</h1>';
        if (!(is_array($description_guid))) {
            $description_guid = array($description_guid);
        }
        usort($description_guid, "sortDate");
        
        foreach ($description_guid as $guid) {

            if ($entry = get_entity($guid)) {
            
                echo '<tr>
                    <td width="100%">';

                            echo '<div class="gcconnex-profile-description-display gcconnex-description-' . $entry->guid . '">';
                                echo '<div class="gcconnex-profile-label education-dates">';
            
                                echo '</div>';
                            echo '</div>';
                            
                            echo'<p class="paraph-justify">' . nl2br($entry->descriptionname) . '</p>';
                            
                    echo '</td>';
                    if ($user->canEdit()){
                                
                    echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:about_me:delete') .'">
                                <span class="btn btn-danger btn-xs glyphicon glyphicon-trash" onclick="entryDeletion(\''.$user_guid.'\',\''.$guid.'\',\''.elgg_echo('gcconnex_profile:about_me:delete').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\')"></span></p></td>';
                            
                    }

            }
        }
    }


    echo '</tr></table>';

    echo '</div>'; 
echo '</div>';

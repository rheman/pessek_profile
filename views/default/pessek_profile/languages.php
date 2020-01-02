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

$section = 'languages';
$user_guid2 = elgg_get_logged_in_user_entity()->guid;
$user2 = get_user($user_guid2);
$deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user2->getDisplayName() ; //$user2->username

$language_level = array(
                        '' => elgg_echo('gcconnex_profile:langs:select'),
                        '0' => elgg_echo('gcconnex_profile:langs:elementory'),
                        '1' => elgg_echo('gcconnex_profile:langs:limited:working'),
                        '2' => elgg_echo('gcconnex_profile:langs:professional:working'),
                        '3' => elgg_echo('gcconnex_profile:langs:full:professional'),
                        '4' => elgg_echo('gcconnex_profile:langs:native'));

$languages_Image = elgg_get_site_url() . "mod/pessek_profile/img/languages.png";

$user = get_user($user_guid);

$languages_guid = $user->languages;

//
//$pessek1 = get_subtype_id('user', 'languages'); echo '<h1>'.$pessek1.'</h1>';
//

echo '<div class="gcconnex-languagess">';

    echo '<div class="gcconnex-profile-languagess-display language_readmore">';

    echo '<table width="100%">';

    if ($user->canEdit() && ($languages_guid == NULL || empty($languages_guid))) {
        echo elgg_echo('gcconnex_profile:langs:empty');
    }
    else {
        if (!(is_array($languages_guid))) {
            $languages_guid = array($languages_guid);
        }
        usort($languages_guid, "sortDate");
        
        foreach ($languages_guid as $guid) {

            if ($entry = get_entity($guid)) {
                echo '<tr>
                    <td width="100%">';

                            echo '<div class="gcconnex-profile-languagess-display gcconnex-languagess-' . $entry->guid . '">';
                            
                            echo '<div class="gcconnex-profile-label education-dates">' . $entry->langs .  ' : ' . $language_level[$entry->level] . ' ';
                            
                            echo '</div>';
                            echo '</div>';
                            
                            echo'<p class="paraph-justify"><img src="' . $languages_Image .'" style="float: left; width: 60px; height: 60px; margin-left: 10px; margin-right: 10px;"><strong>' . $entry->langs .'</strong><br>' . $language_level[$entry->level] . '</p>';
                            
                    echo '</td>';
                    if ($user->canEdit()){

                                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:langs:edit') .'">'. elgg_view('output/url', [
                                        'text' => '',
                                        'href' => 'ajax/view/forms/pessek_profile/languages?guidp='.$user_guid.'&guide='.$guid,
                                        'class' => 'btn btn-primary btn-xs glyphicon glyphicon-pencil elgg-lightbox',
                                        'data-colorbox-opts' => json_encode(['maxHeight' => '85%', 'maxWidth' => '100%']),
                                ]). '</p></td>';
                
                                
                    echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:langs:delete') .'">
                                <span class="btn btn-danger btn-xs glyphicon glyphicon-trash" onclick="entryDeletion(\''.$user_guid.'\',\''.$guid.'\',\''.elgg_echo('gcconnex_profile:langs:delete').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\')"></span></p></td>';
                            
                    }

            }
        }
    }


    echo '</tr></table>';

    echo '</div>'; 
echo '</div>';

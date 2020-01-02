<?php
/**
 * Created by Puewe Pessek Hermand .
 * Date: 06/09/2017
 * Time: 7:02 PM
 */

if (elgg_is_xhr()) {
    $user_guid = $_GET["guid"];
}
else {
    $user_guid = elgg_get_page_owner_guid();
}

$section = 'skillssentry';
$user_guid2 = elgg_get_logged_in_user_entity()->guid;
$user2 = get_user($user_guid2);

$deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user2->getDisplayName() ; //$user2->username


$skills_Image = elgg_get_site_url() . "mod/pessek_profile/img/skills.png";

$Like_Image = elgg_get_site_url() . "mod/pessek_profile/img/like.png";

$disLike_Image = elgg_get_site_url() . "mod/pessek_profile/img/dislike.png";

$user = get_user($user_guid);

$skills_guid = $user->skills;

echo '<div class="gcconnex-skillss">';

    echo '<div class="gcconnex-profile-skillss-display skills_readmore">';

    echo '<table width="100%">';

    if ($user->canEdit() && ($skills_guid == NULL || empty($skills_guid))) {
        echo elgg_echo('gcconnex_profile:langs:empty');
    }
    else {
        if (!(is_array($skills_guid))) {
            $skills_guid = array($skills_guid);
        }
    
    usort($skills_guid, "sortSkills");
    
       // usort($languages_guid, "sortDate");
        
       /* foreach ($skills_guid as $guid) {

            if ($entry = get_entity($guid)) {*/
                
                echo '<tr>';
                        echo '<td colspan="2" width="100%">';
                        
                            echo '<div class="gcconnex-profile-label education-dates"></div>';
                        
                        echo '</td>';
                echo '</tr>';
                
                echo '<tr>';
                        /*
                        echo' <td style="float:left">';
                        
                            echo '<img src="' . $skills_Image .'" style="float: left; width: 96px; height: 96px; margin-left: 0px; margin-right: 0px;">';
                        
                        echo '</td>';
                        */
                        echo '<td colspan="2">';
                        
        foreach ($skills_guid as $guid) {

            if ($entry = get_entity($guid)) { 
                        
                        $nbr_endorsements = 0 ;
                        
                        $endorsements = $entry->endorsements;
                        
                        if (!(is_array($endorsements))) {
                            $endorsements = array($endorsements);
                        }
                        
                        if(isset($entry->endorsements)){
                            $stack = $entry->endorsements;
                            if (!(is_array($stack))) { $stack = array($stack); }
                            $nbr_endorsements = count($stack);
                        }
                           echo '<div class="gcconnex-skill-entry temporarily-added" data-skill="' . $entry->title . '" data-guid="' . $entry->guid . '" data-guid-skillfind="' . $entry->id . '"><br>';
                        
                                echo '<span title="Number of endorsements" class="gcconnex-endorsements-count" data-skill="' . $entry->title . '"> ' . $nbr_endorsements . '</span>';
                                echo '<span data-skill="' . $entry->title . '" class="gcconnex-endorsements-skill">' . $entry->title . '</span>';
                                
                                if ($user->canEdit()){
                                
                                   echo '<span class="delete-skill-pessek btn btn-danger btn-xs glyphicon glyphicon-trash" data-type="skill" onclick="Delete_Skills_Entry(\''.$user_guid.'\', \''.$guid.'\', \''.$section.'\', this)"></span>';
                                
                                }
                                if (!($user->canEdit()) && $user->isFriendsWith(elgg_get_logged_in_user_guid())) {
                                
                                    if (in_array(elgg_get_logged_in_user_guid(), $endorsements) == false || empty($endorsements)) {
                                            // user has not yet endorsed this skill for this user.. present the option to endorse
                                        //echo '';
                                        //<img src="' . $Like_Image .'" title="' . elgg_echo('gcconnex_profile:skills:endorse') . '" style="float: left">

                                        echo '<span class="gcconnex-endorsement-add elgg-button" onclick="Add_Endorsement(this)" data-guid="' . $entry->guid . '" data-skill="' . $entry->title . '">
                                                <i class="elgg-icon elgg-icon-thumbs-o-up interactions-icon far fa-thumbs-up fa-lg" style="color:#50C28C;float: left" aria-hidden="true" title="' . elgg_echo('gcconnex_profile:skills:endorse') . '"></i>
                                            &nbsp;</span>';
                                    } else {
                                        // user has endorsed this skill for this user.. present the option to retract endorsement
                                        //<img src="' . $disLike_Image .'" title="' . elgg_echo('gcconnex_profile:skills:retractendorsement') . '" style="float: left">
                                        echo '<span class="gcconnex-endorsement-retract elgg-button" onclick="Retract_Endorsement(this)" data-guid="' . $entry->guid . '" data-skill="' . $entry->title . '">
                                                <i class="elgg-icon elgg-icon-thumbs-o-down interactions-icon far fa-thumbs-down fa-lg" style="color:#ff6707;float: left" aria-hidden="true" title="' . elgg_echo('gcconnex_profile:skills:retractendorsement') . '"></i>
                                             &nbsp;</span>';

                                    }
                                
                                }
                                        
                                echo '<div class="gcconnex-skill-endorsements">';
                                        echo list_avatars(array(
                                            'guids' => $entry->endorsements,
                                            'size' => 'small',
                                            'limit' => 5
                                        ), $entry->endorsements);
                                echo '</div>';
                                
                            echo '<br></div>';
                       
                }
                    
        }            
                   
                     echo '</td>';
                    
                echo '</tr>';
                
                /*
                echo '<tr>
                    <td width="100%">';

                            echo '<div class="gcconnex-profile-languagess-display gcconnex-languagess-' . $entry->guid . '">';
                            
                            echo '<div class="gcconnex-profile-label education-dates">' . $entry->langs .  ' : ' . $language_level[$entry->level] . ' ';
                            
                            echo '</div>';
                            echo '</div>';
                            
                            echo'<p class="paraph-justify"><img src="' . $languages_Image .'" style="float: left; width: 60px; height: 60px; margin-left: 10px; margin-right: 10px;"><strong>' . $entry->langs .'</strong><br>' . $language_level[$entry->level] . '</p>';
                            
                    echo '</td>';
                    if ($user->canEdit()){

                                echo '<td><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:langs:edit') .'">'. elgg_view('output/url', [
                                        'text' => '',
                                        'href' => 'ajax/view/forms/pessek_profile/languages?guidp='.$user_guid.'&guide='.$guid,
                                        'class' => 'btn btn-primary btn-xs glyphicon glyphicon-pencil elgg-lightbox',
                                        'data-colorbox-opts' => json_encode(['maxHeight' => '85%', 'maxWidth' => '100%']),
                                ]). '</p></td>';
                
                                
                    echo '<td><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:langs:delete') .'">
                                <button type="button" class="btn btn-danger btn-xs glyphicon glyphicon-trash" onclick="entryDeletion(\''.$user_guid.'\',\''.$guid.'\',\''.elgg_echo('gcconnex_profile:langs:delete').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\')"></button></p></td>';
                            
                    }*/

           // }
       // }
    }


    echo '</tr></table>';
        echo '
        <div class="ajax_auf_loading" align="center" >
            <img align="absmiddle" src="' . elgg_get_site_url() . 'mod/pessek_profile/img/ajax-loader.gif">&nbsp;'.elgg_echo('pessek_profile:in:progress').'...
        </div>';
    echo '</div>'; 
echo '</div>';


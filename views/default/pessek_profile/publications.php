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

$section = 'publications';
$user_guid2 = elgg_get_logged_in_user_entity()->guid;
$user2 = get_user($user_guid2);
$deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user2->getDisplayName() ; //$user2->username

//register_error($logged_in_user_guid);

$publications_Image = elgg_get_site_url() . "mod/pessek_profile/img/publications.png";

$user = get_user($user_guid);

$publications_guid = $user->publications;

$day_selected = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
//$month_selected = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$month_selected =    array(
                            '0' => elgg_echo('pessek_profile:January:lib'), 
                            '1' => elgg_echo('pessek_profile:February:lib'),
                            '2' => elgg_echo('pessek_profile:March:lib'),
                            '3' => elgg_echo('pessek_profile:April:lib'),
                            '4' => elgg_echo('pessek_profile:May:lib'),
                            '5' => elgg_echo('pessek_profile:June:lib'),
                            '6' => elgg_echo('pessek_profile:July:lib'),
                            '7' => elgg_echo('pessek_profile:August:lib'),
                            '8' => elgg_echo('pessek_profile:September:lib'),
                            '9' => elgg_echo('pessek_profile:October:lib'),
                            '10' => elgg_echo('pessek_profile:November:lib'),
                            '11' => elgg_echo('pessek_profile:December:lib'));
                            
echo '<div class="gcconnex-profile-publications-display publications_readmore">';

echo '<table width="100%">';

if ($user->canEdit() && ($publications_guid == NULL || empty($publications_guid))) {
    echo elgg_echo('gcconnex_profile:publications:empty');
}
else {
    if (!(is_array($publications_guid))) {
        $publications_guid = array($publications_guid);
    }
    usort($publications_guid, "sortDatePortfolio");
    
    foreach ($publications_guid as $guid) {

        if ($entry = get_entity($guid)) {
        
            $the_link = NULL;
            $the_editor = NULL;
            
            $published_date = $day_selected[$entry->startday] . ' ' . $month_selected[$entry->startmonth]  .' ' . $entry->startyear . ' - ';
            $published_on = '<br><strong>' .elgg_echo('gcconnex_profile:portfolio:publishedon'). ' : </strong>' . $day_selected[$entry->startday] . ' ' . $month_selected[$entry->startmonth]  .' ' . $entry->startyear ;
            
            $thedescription = '<br><strong>' . elgg_echo('gcconnex_profile:portfolio:description'). ' : </strong> <br>' . $entry->thedescription .'<br>';
            
            if(isset($entry->links) && !is_null($entry->links) && $entry->links != ''){
                
                $the_link = '<br>'.'<a href="'. $entry->links .'" target="_blank"><span class="glyphicon glyphicon-link"></span>' . elgg_echo('gcconnex_profile:publications:see:publications') . '</a>';
            
            }
            
            if(isset($entry->editor) && !is_null($entry->editor) && $entry->editor != ''){
                        
                $the_editor = '<br><strong>' . elgg_echo('gcconnex_profile:publications:editor') .'</strong>'. $entry->editor .'';
            }
            
        
            echo '<tr>
                <td width="100%">';

                        echo '<div class="gcconnex-profile-publications-display gcconnex-publications-' . $entry->guid . '">';
                        
                        echo '<div class="gcconnex-profile-label education-dates">' . $published_date .  ' ' . $entry->thetitle . ' ';
                        
                        echo '</div>';
                        echo '</div>';
                        
                        echo'<p class="paraph-justify"><img src="' . $publications_Image .'" style="float: left;  margin-left: 10px; margin-right: 10px;"><strong>' . elgg_echo('gcconnex_profile:portfolio:title'). ' : ' . $entry->thetitle .'</strong>' . $the_editor . '' . $the_link .''. $published_on . '' . nl2br($thedescription) . '</p>';
                        
                        
                        //echo '</div>'; // close div class="gcconnex-profile-portfolio-display gcconnex-portfolio-'...
                echo '</td>';
                if ($user->canEdit()){

                            echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:edit') .'">'. elgg_view('output/url', [
                                    'text' => '',
                                    'href' => 'ajax/view/forms/pessek_profile/publications?guidp='.$user_guid.'&guide='.$guid,
                                    'class' => 'btn btn-primary btn-xs glyphicon glyphicon-pencil elgg-lightbox',
                                    'data-colorbox-opts' => json_encode(['maxHeight' => '85%', 'maxWidth' => '100%']),
                            ]). '</p></td>';
            /*
                            echo '<td><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:delete') .'">'. elgg_view('output/url', array(
                                    'href' => 'action/pessek_profile/portfolio/delete?guidp='.$user_guid.'&guide='.$guid,
                                    'text' => '',
                                    'class' => 'btn btn-danger btn-xs glyphicon glyphicon-trash',
                                    'confirm' => true,
                            )). '</p></td>';*/
                            
                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:publications:delete') .'">
                            <span class="btn btn-danger btn-xs glyphicon glyphicon-trash" onclick="entryDeletion(\''.$user_guid.'\',\''.$guid.'\',\''.elgg_echo('gcconnex_profile:publications:delete').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\')"></span></p></td>';
                        
                }
                if(isset($entry->coauthor) && !is_null($entry->coauthor) && $entry->coauthor != ''){
                    echo '<tr><td>';
                        echo '<div class="gcconnex-profile-label work-experience-colleagues"><strong>' . elgg_echo('gcconnex_profile:publications:co:author') .'</strong>';
                            $coauthor = $entry->coauthor;
                            
                            if (!(is_array($coauthor))) {
                                $coauthor = array($coauthor);
                            }

                            echo list_avatars(array(
                                'guids' => $coauthor,
                                'size' => 'medium',
                                'limit' => 0,
                            ));
                        echo '</div>'; // close div class="gcconnex-profile-label work-experience-colleagues"
                    echo '</td><td></td></tr>';
                }

        }
    }
}


echo '</tr></table>';

echo '</div>'; // close div class="gcconnex-profile-portfolio-display"
//echo '</div>'; // close div class="gcconnex-profile-section-wrapper gcconnex-portfolio

$thedescription = NULL;
$published_on = NULL;
$the_link = NULL;
$the_editor = NULL;
$published_date = NULL;

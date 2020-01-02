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

$section = 'projects';
$user_guid2 = elgg_get_logged_in_user_entity()->guid;
$user2 = get_user($user_guid2);
$deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user2->getDisplayName() ; //$user2->username

//register_error($logged_in_user_guid);

$projects_Image = elgg_get_site_url() . "mod/pessek_profile/img/projects.png";

$user = get_user($user_guid);

$projects_guid = $user->projects;

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
                            
echo '<div class="gcconnex-profile-projects-display projects_readmore">';

echo '<table width="100%">';

if ($user->canEdit() && ($projects_guid == NULL || empty($projects_guid))) {
    echo elgg_echo('gcconnex_profile:projects:empty');
}
else {
    if (!(is_array($projects_guid))) {
        $projects_guid = array($projects_guid);
    }
    usort($projects_guid, "sortDate");
    
    foreach ($projects_guid as $guid) {

        if ($entry = get_entity($guid)) {
            
            $start_date = ' ' . $month_selected[$entry->startmonth]  .' ' . $entry->startyear . ' - ';
            $end_date = elgg_echo('gcconnex_profile:projects:certexpired') . ' ';
            
            if ( $entry->ongoing != true ) {
                $end_date = ' ' . $month_selected[$entry->endmonth]  .' ' . $entry->endyear . ' ';
            }
            
            $project_date = '<br>' . $start_date . '  ' .$end_date;
            
            
            $the_link = NULL;
            
            
            if(isset($entry->links) && !is_null($entry->links) && $entry->links != ''){
                
                $the_link = '<br>'.'<a href="'. $entry->links .'" target="_blank"><span class="glyphicon glyphicon-link"></span>' . elgg_echo('gcconnex_profile:projects:see:publications') . '</a>';
            
            }
            
            $thedescription = '<br><strong>' . elgg_echo('gcconnex_profile:projects:description'). '</strong> <br>' . $entry->thedescription .'<br>';
            
            
        
            echo '<tr>
                <td width="100%">';

                        echo '<div class="gcconnex-profile-projects-display gcconnex-projects-' . $entry->guid . '">';
                        
                        echo '<div class="gcconnex-profile-label education-dates">' . $start_date .  ' ' . $end_date . ' ' . $entry->thetitle . ' ';
                        
                        echo '</div>';
                        echo '</div>';
                        
                        echo'<p class="paraph-justify"><img src="' . $projects_Image .'" style="float: left; width: 110px; height: 110px; margin-left: 10px; margin-right: 10px;"><strong>' . elgg_echo('gcconnex_profile:projects:title'). '' . $entry->thetitle .'<br>' . elgg_echo('gcconnex_profile:projects:role') . ' : '. $entry->function .'</strong>' . $project_date . '' . $the_link .'' . nl2br($thedescription) . '</p>';
                        
                        
                        //echo '</div>'; // close div class="gcconnex-profile-portfolio-display gcconnex-portfolio-'...
                echo '</td>';
                if ($user->canEdit()){

                            echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:edit') .'">'. elgg_view('output/url', [
                                    'text' => '',
                                    'href' => 'ajax/view/forms/pessek_profile/projects?guidp='.$user_guid.'&guide='.$guid,
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
                            
                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:projects:delete') .'">
                            <span class="btn btn-danger btn-xs glyphicon glyphicon-trash" onclick="entryDeletion(\''.$user_guid.'\',\''.$guid.'\',\''.elgg_echo('gcconnex_profile:projects:delete').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\')"></span></p></td>';
                        
                }
                if(isset($entry->contributors) && !is_null($entry->contributors) && $entry->contributors != ''){
                    echo '<tr><td>';
                        echo '<div class="gcconnex-profile-label work-experience-colleagues"><strong>' . elgg_echo('gcconnex_profile:projects:contributor') .'</strong>';
                            $contributors = $entry->contributors;
                            
                            if (!(is_array($contributors))) {
                                $contributors = array($contributors);
                            }

                            echo list_avatars(array(
                                'guids' => $contributors,
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

$project_date = NULL;


$thedescription = NULL;
$published_on = NULL;
$the_link = NULL;
$the_editor = NULL;
$published_date = NULL;

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

$section = 'volunteers';
$user_guid2 = elgg_get_logged_in_user_entity()->guid;
$user2 = get_user($user_guid2);
$deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user2->getDisplayName() ; //$user2->username

//register_error($logged_in_user_guid);

$internships_Image = elgg_get_site_url() . "mod/pessek_profile/img/volunteers.png";

$user = get_user($user_guid);

$internships_guid = $user->volunteers;

$month_selected = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

echo '<div class="gcconnex-volunteers">';

    echo '<div class="gcconnex-profile-volunteers-display volunteers_readmore">';

    echo '<table width="100%">';

    if ($user->canEdit() && ($internships_guid == NULL || empty($internships_guid))) {
        echo elgg_echo('gcconnex_profile:volunteers:empty');
    }
    else {
        if (!(is_array($internships_guid))) {
            $internships_guid = array($internships_guid);
        }
        usort($internships_guid, "sortDate");
        
        foreach ($internships_guid as $guid) {

            if ($entry = get_entity($guid)) {
                
                $start_date = ' ' . $month_selected[$entry->startmonth]  .' ' . $entry->startyear . ' - ';
                $end_date = elgg_echo('gcconnex_profile:experience:certexpired') . ' ';
                
                if ( $entry->ongoing != true ) {
                    $end_date = ' ' . $month_selected[$entry->endmonth]  .' ' . $entry->endyear . ' ';
                }
                
                $internships_date = '<br>' . $start_date . '  ' .$end_date;
                
                
                $the_link = NULL;
                
                
                if(isset($entry->companyurl) && !is_null($entry->companyurl) && $entry->companyurl != ''){
                    
                    $the_link = '<br><span style="float: left;">'.'<a href="'. $entry->companyurl .'" target="_blank"><span class="glyphicon glyphicon-link"></span>' . elgg_echo('gcconnex_profile:experience:see:company') . '</a></span>';
                
                }
                
                $work_duration = experience_year_month_day ($entry->startyear, $entry->endyear, $entry->startmonth, $entry->endmonth, $entry->ongoing, elgg_echo('gcconnex_profile:experience:workduration:year:lib'), elgg_echo('gcconnex_profile:experience:workduration:month:lib'), elgg_echo('gcconnex_profile:experience:workduration:year:libs'), elgg_echo('gcconnex_profile:experience:workduration:month:libs'));
                
                $thedescription = '<br><strong>' . elgg_echo('gcconnex_profile:experience:description'). '</strong><br>' . $entry->jobdescription .'<br>';
                
                $flag = elgg_get_site_url() . "mod/pessek_profile/img/flags/" . $entry->flag . ".png";
                
                $country = '<br><img src="' . $flag .'" style="float: left; width: 30px; height: 20px; margin-right: 5px;">' .$entry->country . ' , ' . $entry->place .'';
            
                echo '<tr>
                    <td width="100%">';

                            echo '<div class="gcconnex-profile-volunteers-display gcconnex-volunteers-' . $entry->guid . '">';
                            
                            echo '<div class="gcconnex-profile-label education-dates">' . $start_date .  ' ' . $end_date . ' ' . $entry->jobtitle . ' ';
                            
                            echo '</div>';
                            echo '</div>';
                            
                            echo'<p class="paraph-justify"><img src="' . $internships_Image .'" style="float: left; width: 110px; height: 110px; margin-left: 10px; margin-right: 10px;"><strong>' . $entry->jobtitle .'</strong><br>' . $entry->companyname . ' ' . $internships_date . ' , ' . $work_duration. '' . $country . '' . $the_link .'' . nl2br($thedescription) . '</p>';
                            
                            
                            //echo '</div>'; // close div class="gcconnex-profile-portfolio-display gcconnex-portfolio-'...
                    echo '</td>';
                    if ($user->canEdit()){

                                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:edit') .'">'. elgg_view('output/url', [
                                        'text' => '',
                                        'href' => 'ajax/view/forms/pessek_profile/experience?guidp='.$user_guid.'&guide='.$guid,
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
                                
                    echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:volunteer:delete') .'">
                                <span class="btn btn-danger btn-xs glyphicon glyphicon-trash" onclick="entryDeletion(\''.$user_guid.'\',\''.$guid.'\',\''.elgg_echo('gcconnex_profile:volunteer:delete').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\')"></span></p></td>';
                            
                    }

            }
        }
    }


    echo '</tr></table>';

    echo '</div>'; // close div class="gcconnex-profile-portfolio-display"
echo '</div>';
//echo '</div>'; // close div class="gcconnex-profile-section-wrapper gcconnex-portfolio

$internships_date = NULL;
$country = NULL;
$flag = NULL;


$thedescription = NULL;
$published_on = NULL;
$the_link = NULL;
$the_editor = NULL;
$published_date = NULL;

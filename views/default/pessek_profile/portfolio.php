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
// ma parenthese pessek
/*
     $options = [ 'type' => 'user', 'relationship' => 'friend', 'relationship_guid' => $user_guid, 'inverse_relationship' => true, ];
     $testval = elgg_get_entities_from_relationship($options);
     $lolo = elgg_list_entities_from_relationship($options);
     
     $rows = array();
     $i = 0 ;
     if(!empty($testval)){
	foreach($testval as $entity){
		$rows[$i]=$entity->guid; //echo $entity->guid .'_pessek';
		$i++;
		
		$user = get_user($entity->guid);
		
		$user_guid3 = $entity->guid;
		$user_display_name = $user->getDisplayName();
		$user_pic = $user->getIconURL('tiny');
		
		//echo $user->getDisplayName() .'_pessek';
		//echo $user_pic;
		//echo '<img src="'. $user->getIconURL('tiny') .'" class="avatar-project-publication">';
		$query = 'pes';
		if (strpos(strtolower($user_display_name), strtolower($query)) !== FALSE) {
                    $result[] = array('value' => $user_display_name, 'guid' => $user_guid3 ,'pic' => $user_pic, 'avatar' => $user_pic);
                }
        }
     }
echo json_encode($result);
  */   
     
     
     
     //print_r($rows);
     //var_dump($testval);
     //var_dump($lolo);
     //print_r ($lolo); echo $lolo;
//
$section = 'portfolio';
$user_guid2 = elgg_get_logged_in_user_entity()->guid;
$user2 = get_user($user_guid2);
$deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user2->getDisplayName() ; //$user2->username

//register_error($logged_in_user_guid);

$portfolio_Image = elgg_get_site_url() . "mod/pessek_profile/img/portfolio.png";

$user = get_user($user_guid);

$portfolio_guid = $user->portfolio;

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
echo '<div class="gcconnex-profile-portfolio-display portfolio_readmore">';

echo '<table width="100%">';

if ($user->canEdit() && ($portfolio_guid == NULL || empty($portfolio_guid))) {
    echo elgg_echo('gcconnex_profile:portfolio:empty');
}
else {
    if (!(is_array($portfolio_guid))) {
        $portfolio_guid = array($portfolio_guid);
    }
    usort($portfolio_guid, "sortDatePortfolio");
    
    foreach ($portfolio_guid as $guid) {

        if ($entry = get_entity($guid)) {
        
$thedescription = '<br><strong>' . elgg_echo('gcconnex_profile:portfolio:description'). '</strong> : <br>' . $entry->thedescription .'<br>';
$published_on = NULL;
$the_link = NULL;
$published_date = NULL;

if(isset($entry->links) && !is_null($entry->links) && $entry->links != ''){
            
    //$the_link = '<br><strong>' . elgg_echo('gcconnex_profile:portfolio:link') .'</strong>'. elgg_view('output/url', array('href' => $entry->links,'text' => $entry->links,'target' => _blank));
    
    $the_link = '<br>'.'<a href="'. $entry->links .'" target="_blank"><span class="glyphicon glyphicon-link"></span>' . elgg_echo('gcconnex_profile:certification:see:portfolio') . '</a>';
}
if ( $entry->datestamped != true ) {

    $published_on = '<br><strong>' .elgg_echo('gcconnex_profile:portfolio:publishedon'). '</strong>' . $day_selected[$entry->startday] . ' ' . $month_selected[$entry->startmonth]  .' ' . $entry->startyear ;
    
    $published_date = $day_selected[$entry->startday] . ' ' . $month_selected[$entry->startmonth]  .' ' . $entry->startyear . ' - ';
}
        
echo '<tr>
        <td width="100%">';

            echo '<div class="gcconnex-profile-portfolio-display gcconnex-portfolio-' . $entry->guid . '">';
            
            echo '<div class="gcconnex-profile-label education-dates">' . $published_date .  ' ' . $entry->thetitle . ' ';
            
            echo '</div>';
            echo '</div>';
            
            echo'<p class="paraph-justify"><img src="' . $portfolio_Image .'" style="float: left; width: 110px; height: 110px; margin-left: 10px; margin-right: 10px;"><strong>' . elgg_echo('gcconnex_profile:portfolio:title'). '' . $entry->thetitle .'</strong>'. $the_link .''. $published_on . '' . nl2br($thedescription) . '</p>';
            
            
            //echo '</div>'; // close div class="gcconnex-profile-portfolio-display gcconnex-portfolio-'...
    echo '</td>';
    if ($user->canEdit()){

                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:edit') .'">'. elgg_view('output/url', [
                        'text' => '',
                        'href' => 'ajax/view/forms/pessek_profile/portfolio?guidp='.$user_guid.'&guide='.$guid,
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
                
                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:delete') .'">
                <span class="btn btn-danger btn-xs glyphicon glyphicon-trash" onclick="entryDeletion(\''.$user_guid.'\',\''.$guid.'\',\''.elgg_echo('gcconnex_profile:portfolio:delete').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\')"></span></p></td>';
               
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
$published_date = NULL;

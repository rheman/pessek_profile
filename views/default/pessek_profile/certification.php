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

$section = 'certification';
$user_guid2 = elgg_get_logged_in_user_entity()->guid;
$user2 = get_user($user_guid2);
$deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user2->getDisplayName() ; //$user2->username

//register_error($logged_in_user_guid);

$portfolio_Image = elgg_get_site_url() . "mod/pessek_profile/img/certification.png";

$user = get_user($user_guid);

$certification_guid = $user->certification;

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
                            
echo '<div class="gcconnex-profile-certification-display cerfification_readmore">';

echo '<table width="100%">';

if ($user->canEdit() && ($certification_guid == NULL || empty($certification_guid))) {
    echo elgg_echo('gcconnex_profile:certification:empty');
}
else {
    if (!(is_array($certification_guid))) {
        $certification_guid = array($certification_guid);
    }
    usort($certification_guid, "sortDate");
    
    foreach ($certification_guid as $guid) {

        if ($entry = get_entity($guid)) {
      
            $start_date = ' ' . $month_selected[$entry->startmonth]  .' ' . $entry->startyear . ' - ';
            $end_date = elgg_echo('gcconnex_profile:certification:certexpired') . ' ';
            $certification_licence = NULL;
            $certification_url = NULL;
            
            if ( $entry->ongoing != true ) {
                $end_date = ' ' . $month_selected[$entry->endmonth]  .' ' . $entry->endyear . ' ';
            }
            
            if(isset($entry->licence) && !is_null($entry->licence) && $entry->licence != ''){
            
                $certification_licence = '<br>' . elgg_echo('gcconnex_profile:certification:licence') .': '. $entry->licence;
            }
            
            if(isset($entry->certurl) && !is_null($entry->certurl) && $entry->certurl != ''){
            
                $certification_url = '<br>'.'<a href="'. $entry->certurl .'" target="_blank"><span class="glyphicon glyphicon-link"></span>' . elgg_echo('gcconnex_profile:certification:see:certification') . '</a>';
            }
            
            $certification_date = '<br>' . $start_date . '  ' .$end_date;
            
                  
            echo '<tr>
                    <td width="100%">';

                        echo '<div class="gcconnex-profile-certification-display gcconnex-certification-' . $entry->guid . '">';
                        
                        echo '<div class="gcconnex-profile-label education-dates">' . $start_date .  ' ' . $end_date . ' ' . $entry->name . ' ';
                        
                        echo '</div>';
                        echo '</div>';
                        
                        echo'<p class="paraph-justify"><img src="' . $portfolio_Image .'" style="float: left; width: 64px; height: 64px; margin-left: 10px; margin-right: 10px;"><strong>'. $entry->name .'</strong>'. $certification_date .''. $certification_licence . '' . $certification_url . '</p>';
            
                echo '</td>';
            if ($user->canEdit()){

                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:edit') .'">'. elgg_view('output/url', [
                        'text' => '',
                        'href' => 'ajax/view/forms/pessek_profile/certification?guidp='.$user_guid.'&guide='.$guid,
                        'class' => 'btn btn-primary btn-xs glyphicon glyphicon-pencil elgg-lightbox',
                        'data-colorbox-opts' => json_encode(['maxHeight' => '85%', 'maxWidth' => '100%']),
                ]). '</p></td>';
                
                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:delete') .'">
                <span class="btn btn-danger btn-xs glyphicon glyphicon-trash" onclick="entryDeletion(\''.$user_guid.'\',\''.$guid.'\',\''.elgg_echo('gcconnex_profile:certification:delete').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\')"></span></p></td>';
               
            }

        }
    }
}


echo '</tr></table>';

echo '</div>';

$start_date = NULL;
$end_date = NULL;
$certification_licence = NULL;
$certification_url = NULL;
$certification_date = NULL;
            

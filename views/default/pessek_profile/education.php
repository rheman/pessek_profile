<?php

if (elgg_is_xhr()) {
    $user_guid = $_GET["guid"];
}
else {
    $user_guid = elgg_get_page_owner_guid();
}

$section = 'education';
$user_guid2 = elgg_get_logged_in_user_entity()->guid;
$user2 = get_user($user_guid2);
$deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user2->getDisplayName() ; //$user2->username 

$user = get_user($user_guid);
$education_guid = $user->education;
echo '<br>';
echo '<div class="gcconnex-profile-education-display education_readmore">';

echo '<table width="100%">';

if ($user->canEdit() && ($education_guid == NULL || empty($education_guid))) {
    echo elgg_echo('gcconnex_profile:education:empty');
}
else {
    if (!(is_array($education_guid))) {
        $education_guid = array($education_guid);
    }
    usort($education_guid, "sortDate");

    foreach ($education_guid as $guid) {


        if ($education = get_entity($guid)) { 
        
echo '<tr><td width="100%">';

            $yearstart = (string) $education->startyear;
            $yearend = (string) $education->endyear;

            if(strcmp($yearstart, $yearend) > 0 && $education->ongoing != 'true') {
            
                $yearstart = (string) $education->endyear;
                $yearend = (string) $education->startyear;
                
            }
            echo '<div class="gcconnex-profile-education-display gcconnex-education-' . $education->guid . '">';
            echo '<div class="gcconnex-profile-label education-dates">' . $yearstart . ' - ';

            if ($education->ongoing == 'true') {
                echo elgg_echo('gcconnex_profile:education:present');
                $yearend = elgg_echo('gcconnex_profile:education:present');
            } else {
                echo $yearend;
                //$yearend = $education->endyear;
            }
            
            if(isset($education->resultobtain) && !is_null($education->resultobtain)){
            
                $resultobtain = ' , ' . $education->resultobtain;
            }
            
            if(isset($education->educationurl) && !is_null($education->educationurl) && !empty($education->educationurl)){
            
                $educationurl =' '.'<a href="'. $education->educationurl .'" target="_blank"><span class="glyphicon glyphicon-link"></span></a>';
            }
            
            if(isset($education->activity) && !is_null($education->activity) && !empty($education->activity) && isset($education->trainingd) && !is_null($education->trainingd) && !empty($education->trainingd)){
                
                $activity = '<strong>'. elgg_echo('gcconnex_profile:education:activity').'</strong> : <br>'. $education->activity ;
                $trainingd ='<strong>'. elgg_echo('gcconnex_profile:education:trainingd').'</strong> : <br>'. $education->trainingd ;
               
                $activity_trainingd = '<br><span style="float: left; margin-left: 80px;margin-bottom: 15px;">' . $activity .'<br>'. $trainingd .'</span><br>';
                
            }elseif(isset($education->activity) && !is_null($education->activity) && !empty($education->activity)){
                
                $activity = '<strong>'. elgg_echo('gcconnex_profile:education:activity').'</strong> : <br>'. $education->activity ;
                
                $activity_trainingd = '<br><span style="float: left; margin-left: 80px;margin-bottom: 15px;">' . $activity .'</span><br>';
                
            }elseif(isset($education->trainingd) && !is_null($education->trainingd) && !empty($education->trainingd)){
            
                $trainingd = '<strong>'. elgg_echo('gcconnex_profile:education:trainingd').'</strong> : <br>'. $education->trainingd ;
                
                $activity_trainingd = '<br><span style="float: left; margin-left: 80px;margin-bottom: 15px;">' . $trainingd .'</span><br>';
                
            }
            
            echo ' : '.$education->school;
            
            echo '</div>';

            echo '</div>';
                        
            echo'<p class="paraph-justify"><strong><img alt="'. $education->school .'" src="' . $education->imagesite .'" style="float: left; width: 60px; height: 60px; margin-left: 10px; margin-right: 10px;">'. $education->school .' '.  $educationurl .'</strong><br>'. $education->degree .', '. $education->field .', '. $education->diploma .' '.$resultobtain .'<br>' . $yearstart . ' – '. $yearend .' ' . nl2br($activity_trainingd) . '<br /></p>';
            
            $yearend = NULL;
            $yearstart = NULL;
            $educationurl = NULL;
            $activity_trainingd = NULL;
            
       
        echo '</td>';

            if ($user->canEdit()){

                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:edit') .'">'. elgg_view('output/url', [
                        'text' => '',
                        'href' => 'ajax/view/forms/pessek_profile/education?guidp='.$user_guid.'&guide='.$guid,
                        'class' => 'btn btn-primary btn-xs glyphicon glyphicon-pencil elgg-lightbox',
                        'data-colorbox-opts' => json_encode(['maxHeight' => '85%', 'maxWidth' => '100%']),
                ]). '</p></td>';
                
                echo '<td class="button-delete-update-profile"><p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:delete') .'">
                <span class="btn btn-danger btn-xs glyphicon glyphicon-trash" onclick="entryDeletion(\''.$user_guid.'\',\''.$guid.'\',\''.elgg_echo('gcconnex_profile:education:delete').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\')"></span></p></td>';

            }
        }

    }
}

echo '</tr></table>';

echo '</div>'; 

<?php
/**
* Pessek Profile
*
* @author Hermand PESSEK
*/
//$guid1 = elgg_get_logged_in_guid();

$guid = elgg_get_logged_in_user_entity()->guid;


if (elgg_is_admin_logged_in()) {

   $guid = (string) get_input('guidp');// $guid = (string) $_GET["guidp"];
}

$guide = (string) get_input('guide');

$projects = get_entity($guide);

$guidPessek = ($projects != NULL)? $guide : "new";

$guidE = ($projects != NULL)? $guide : '0';


//$b = $guid.' / '.$guidE; 

$start_year_array = array();
$end_year_array = array();
$annee = date("Y");
    
for($i=1958; $i<=$annee; $i++){
    $start_year_array[$i] = strval($i);
};
    
for($i=1958; $i<=$annee + 7; $i++){
    $end_year_array[$i] = strval($i);
};

$section = 'projects';

echo '<div class="gcconnex-publications-entry ' . $guidPessek . ' well" data-guid="' . $guidPessek . '">'; // certification entry wrapper for css styling

$form_title = elgg_echo('pessek_profile:projects:training');

$formbody = '';

$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:projects:access').'</td>';

    $user = get_user($guid);
    $access_id = $user->projects_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel_projects",//'name' => 'accesslevel_certification',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-projects-access'
    );

    $formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';


    $formbody .= elgg_view_field(array(
                '#type' => 'text',
                '#label' => elgg_echo('gcconnex_profile:projects:title'),
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:projects:title:help'),
                'required' => true,
                'name' => 'thetitle',
                'value' => $projects->thetitle,
                'class' => 'form-control gcconnex-projects-title',
            ));
    
    
    $formbody .=  '<table><tr><td>'. elgg_view_field(array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:certification:startmonth'),
                'required' => true,
                'name' => 'startmonth',
                'value' => $projects->startmonth,
                //'options_values' =>  array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                'options_values' =>  array(
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
                                        '11' => elgg_echo('pessek_profile:December:lib')),
                'class' => 'form-control gcconnex-projects-startmonth',
            )) ;

    $formbody .= elgg_view_field(array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:certification:startyear'),
                'name' => 'startyear',
                'value' => $projects->startyear,
                'options_values' => $start_year_array,
                'class' => 'form-control gcconnex-projects-startyear',
            )) .'</td><td></td>';

            
    $endyear_array = array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:education:endyear'),
                'name' => 'endyear',
                'value' => $projects->endyear,
                'options_values' => $end_year_array,
                'class' => 'form-control gcconnex-projects-end-year',
            );
            
    
$endyear_array = array(
            '#type' => 'select',
            '#label' => elgg_echo('gcconnex_profile:certification:endyear'),
            'name' => 'endyear',
            'value' => $projects->endyear,
            'options_values' => $end_year_array,
            'class' => 'form-control gcconnex-projects-endyear',
	);
    
$endmonth_array = array(
            '#type' => 'select',
            '#label' => elgg_echo('gcconnex_profile:certification:endmonth'),
            'required' => true,
            'name' => 'endmonth',
            'value' => $projects->endmonth,
            //'options_values' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
            'options_values' =>  array(
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
                                        '11' => elgg_echo('pessek_profile:December:lib')),
            'class' => 'form-control gcconnex-projects-endmonth',
	);
    
if ($projects->ongoing == 'true') {
            $params['disabled'] = 'true';
            $endyear_array['disabled'] = 'true';
            $endmonth_array['disabled'] = 'true';
}
        
        unset($params);
    
    $formbody .=   '</tr><tr><td>'. elgg_view_field($endmonth_array) ;
        

        $params = array(
            'name' => 'ongoing',
            'class' => 'gcconnex-projects-ongoing',
            'onclick' => 'toggleEndDate("projects", this)',
	    'switch' => true,
        );
        if ($projects->ongoing == 'true') {
            $params['checked'] = $projects->ongoing;
        }

    $formbody .=   elgg_view_field($endyear_array) .'</td>';

    $formbody .=   '<td>&nbsp;&nbsp;<label>' . elgg_view('input/checkbox', $params). ' ' .elgg_echo('gcconnex_profile:projects:certexpired'). '</label></td></tr></table>';
     
    $formbody .= '<strong>' .elgg_echo('gcconnex_profile:projects:author'). '</strong>';

    $formbody .=  '<div><table></tr>';
    
    //$formbody .=  '<td><p><img src="'. $user->getIconURL('tiny') .'" class="avatar-project-publication">'. $user->getDisplayName() .'</p></td>';
    
    $formbody .= '<td width="62%"><div class="pessek-suggest-avatar"><img src="'. $user->getIconURL('small') .'" class="avatar-project-publication"></div><div class="pessek-suggest-username"><strong>'. $user->getDisplayName() .'</strong></div></td>';
    
    $formbody .=  '<td><div class="pessek-add-coauthor"><button type="button" id="add-co-author" class="btn btn-primary btn-sm" onclick="UserContributors(\''.$section.'\')">
                    <span class="glyphicon glyphicon-user"></span> '. elgg_echo('gcconnex_profile:projects:contributors') .'
                 </button></div></td></tr>';
    
    $formbody .=  '</table>';
    
    $formbody .=  elgg_view_field(array(
                                        '#type' => 'text',
                                        '#label' => elgg_echo('gcconnex_profile:projects:role'),
                                        '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:projects:role:help'),
                                        'required' => true,
                                        'name' => 'function',
                                        'value' => $projects->function,
                                        'class' => 'form-control gcconnex-projects-function',
                                    ));
    $formbody .=  '<hr class="publication-line-after-author"></hr></div>';
    
    $formbody .=  '<div class="colleagues-list">';
    
    
    if(isset($projects->contributors) && !is_null($projects->contributors) && $projects->contributors != ''){
    
        $formbody .=  '<div class="list-avatars">';
        
        $contributors = $projects->contributors;
                            
        if (!(is_array($contributors))) {
                $contributors = array($contributors);
        }
        
        for($i=0; $i<count($contributors); $i++){
        
            if(!empty($contributors[$i])){
                
                $userContributors = get_user($contributors[$i]);
                
                $formbody .=  '<div class="gcconnex-avatar-in-list temporarily-added" data-guid="' . $contributors[$i] . '" data-guid-userfind="' . $contributors[$i] . '" onclick="removeCoauthor(this)">';
                $formbody .=  '<div class="remove-colleague-from-list">X</div><img src="'. $userContributors->getIconURL('small') .'" alt=" '. $userContributors->getDisplayName() .' "  class="avatar-project-publication"> <input type="hidden" value="' . $contributors[$i] . '" name="user@' . $contributors[$i] . '" id="user@' . $contributors[$i] . '" class="user@' . $contributors[$i] . '"> </div>';
                
            }   
            
        }
        
        $formbody .=  '</div>';
        
    }
    else{
    
        $formbody .=  '<div class="list-avatars"></div>';
        
    }
  
    $formbody .=  '</div>';
    
    $formbody .=  '<div class="pessek-publication-co-author">';
    $formbody .=  '</div>';
            
    $formbody .= elgg_view_field(array(
                '#type' => 'url',
                '#label' => elgg_echo('gcconnex_profile:projects:link'),
                'name' => 'links',
                'value' => $projects->links,
                'class' => 'form-control gcconnex-projects-link',
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:url:help'),
            ));
	
    $formbody .= elgg_view_field(array(
                '#type' => 'plaintext',
                '#label' => elgg_echo('gcconnex_profile:projects:description'),
                'required' => true,
                'maxlength' => '1500',
                'name' => 'thedescription',
                'value' => html_entity_decode($projects->thedescription),
                'class' => 'form-control gcconnex-education-trainingd',
            ));
            
	
    $formbody .=  '<div class="[ form-group ]">
                <input type="checkbox" name="sharechanges" id="fancy-checkbox-success" autocomplete="off" />
                <div class="[ btn-group ]">
                    <label for="fancy-checkbox-success" class="[ btn btn-success ]">
                        <span class="[ glyphicon glyphicon-ok ]"></span>
                        <span> </span>
                    </label>
                    <label for="fancy-checkbox-success" class="[ btn btn-default active shared-profile-changes ]">
                        '. elgg_echo('pessek_profile:education:sharechange') .'
                    </label>
                </div>
            </div>';

$formbody .= elgg_view('input/hidden', [
	'name' => 'guid',
	'value' => $guid,
]);

$formbody .= elgg_view('input/hidden', [
	'name' => 'guide',
	'value' => $guidE,
]);
	
$formbody .= elgg_view('input/submit', [
	'name' => elgg_echo('save'),
	'value' => elgg_echo('save'),
]);

$formbody .= '<div class="ajax_auf_loading" align="center" >
            <img align="absmiddle" src="' . elgg_get_site_url() . 'mod/pessek_profile/img/ajax-loader.gif">&nbsp;'.elgg_echo('pessek_profile:in:progress').'...
</div>';

$form = elgg_view('input/form', [
	'body' => $formbody,
	'class' => 'projects-form',
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

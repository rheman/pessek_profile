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

$publications = get_entity($guide);

$guidPessek = ($publications != NULL)? $guide : "new";

$guidE = ($publications != NULL)? $guide : '0';


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

$section = 'publications';

echo '<div class="gcconnex-publications-entry ' . $guidPessek . ' well" data-guid="' . $guidPessek . '">'; // certification entry wrapper for css styling

$form_title = elgg_echo('pessek_profile:publications:training');

$formbody = '';

$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:publications:access').'</td>';

    $user = get_user($guid);
    $access_id = $user->publications_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel_publications",//'name' => 'accesslevel_certification',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-publications-access'
    );

    $formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';


    $formbody .= elgg_view_field(array(
                '#type' => 'text',
                '#label' => elgg_echo('gcconnex_profile:publications:title'),
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:publications:title:help'),
                'required' => true,
                'name' => 'thetitle',
                'value' => $publications->thetitle,
                'class' => 'form-control gcconnex-publications-title',
            ));
    

    $formbody .= elgg_view_field(array(
                '#type' => 'text',
                '#label' => elgg_echo('gcconnex_profile:publications:editor'),
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:publications:editor:help'),
                'name' => 'editor',
                'value' => $publications->editor,
                'class' => 'form-control gcconnex-publications-editor',
            ));

    $array_startday = array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:portfolio:day'),
                'required' => true,
                'name' => 'startday',
                'value' => $publications->startday,
                'options_values' =>  array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'),
                'class' => 'form-control gcconnex-publications-startday',
            );
            
    $array_startmonth = array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:portfolio:startmonth'),
                'required' => true,
                'name' => 'startmonth',
                'value' => $publications->startmonth,
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
                'class' => 'form-control gcconnex-publications-startmonth',
            );

    $array_startyear = array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:portfolio:startyear'),
                'required' => true,
                'name' => 'startyear',
                'value' => $publications->startyear,
                'options_values' => $start_year_array,
                'class' => 'form-control gcconnex-publications-startyear',
            );           

    $formbody .= '<strong>' .elgg_echo('gcconnex_profile:publications:publication:date'). '</strong>';
    
    $formbody .=  '<table><tr><td>'. elgg_view_field($array_startday) .'</td><td></td></tr>';
            
    $formbody .=  '<tr><td>'. elgg_view_field($array_startmonth) .'</td><td></td></tr>';

    $formbody .= '<tr><td>'. elgg_view_field($array_startyear) .'</td><td></td></tr>';
    
    $formbody .=  '</table>';
            
    $formbody .= '<strong>' .elgg_echo('gcconnex_profile:publications:author'). '</strong>';

    $formbody .=  '<div><table></tr>';
    
    //$formbody .=  '<td><p><img src="'. $user->getIconURL('tiny') .'" class="avatar-project-publication">'. $user->getDisplayName() .'</p></td>';
    
    $formbody .= '<td width="65%"><div class="pessek-suggest-avatar"><img src="'. $user->getIconURL('small') .'" class="avatar-project-publication"></div><div class="pessek-suggest-username"><strong>'. $user->getDisplayName() .'</strong></div></td>';
    
    $formbody .=  '<td><div class="pessek-add-coauthor"><button type="button" id="add-co-author" class="btn btn-primary btn-sm" onclick="UserFriend(\''.$section.'\')">
                    <span class="glyphicon glyphicon-user"></span> '. elgg_echo('gcconnex_profile:publications:addauthor') .'
                 </button></div></td></tr>';
    
    $formbody .=  '</table><hr class="publication-line-after-author"></hr></div>';
    
    $formbody .=  '<div class="colleagues-list">';
    
    if(isset($publications->coauthor) && !is_null($publications->coauthor) && $publications->coauthor != ''){
    
        $formbody .=  '<div class="list-avatars">';
        
        $coauthor = $publications->coauthor;
                            
        if (!(is_array($coauthor))) {
                $coauthor = array($coauthor);
        }
        
        for($i=0; $i<count($coauthor); $i++){
        
            if(!empty($coauthor[$i])){
                
                $userCoauthor = get_user($coauthor[$i]);
                
                $formbody .=  '<div class="gcconnex-avatar-in-list temporarily-added" data-guid="' . $coauthor[$i] . '" data-guid-userfind="' . $coauthor[$i] . '" onclick="removeCoauthor(this)">';
                $formbody .=  '<div class="remove-colleague-from-list">X</div><img src="'. $userCoauthor->getIconURL('small') .'" class="avatar-project-publication"> <input type="hidden" value="' . $coauthor[$i] . '" name="user@' . $coauthor[$i] . '" id="user@' . $coauthor[$i] . '" class="user@' . $coauthor[$i] . '"> </div>';
                
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
                '#label' => elgg_echo('gcconnex_profile:publications:link'),
                'name' => 'links',
                'value' => $publications->links,
                'class' => 'form-control gcconnex-publications-link',
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:url:help'),
            ));
	
    $formbody .= elgg_view_field(array(
                '#type' => 'plaintext',
                '#label' => elgg_echo('gcconnex_profile:portfolio:description'),
                'required' => true,
                'name' => 'thedescription',
                'maxlength' => '1500',
                'value' => html_entity_decode($publications->thedescription),
                'class' => 'form-control gcconnex-education-trainingd',
            ));
            
	
    $formbody .=  '<div class="[ form-group ]">
                <input type="checkbox" name="sharechanges" id="fancy-checkbox-success" autocomplete="off"  />
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
	'class' => 'publications-form',
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

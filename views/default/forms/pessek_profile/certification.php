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

$certification = get_entity($guide);

$guidE = ($certification != NULL)? $guide : '0';


//$b = $guid.' / '.$guide; 

$start_year_array = array();
$end_year_array = array();
$annee = date("Y");
    
for($i=1958; $i<=$annee; $i++){
    $start_year_array[$i] = strval($i);
};
    
for($i=1958; $i<=$annee + 7; $i++){
    $end_year_array[$i] = strval($i);
};

echo '<div class="gcconnex-certification-entry well" data-guid="' . $guid . '">'; // certification entry wrapper for css styling

$form_title = elgg_echo('pessek_profile:certification:training');

$formbody = '';

$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:certification:access').'</td>';

    $user = get_user($guid);
    $access_id = $user->certification_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel_certification",//'name' => 'accesslevel_certification',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-certification-access'
    );

$formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';

    //get the array of user education entities
    $education_guid = $user->certification;

//$formbody .= '<br><br><strong>' .elgg_echo('gcconnex_profile:certification:listening'). '</strong><br><br>'; //a voir dans certif et education

$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:certification:name'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:certification:name:help'),
	    'required' => true,
	    'name' => 'certification',
	    'value' => $certification->name,
	    'placeholder' => elgg_echo('gcconnex_profile:certification:name'),
	    'class' => 'form-control gcconnex-certification-name',
	    'onfocus' => 'testtest(this)',
	));
	
$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:certification:authority'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:certification:authority:help'),
	    'required' => true,
	    'name' => 'authority',
	    'value' => $certification->authority,
	    'placeholder' => elgg_echo('gcconnex_profile:certification:authority'),
	    'class' => 'form-control gcconnex-certification-authority',
	    //'id' => 'data-json',
	));
	
$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:certification:licence'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:certification:licence:help'),
	    'name' => 'licence',
	    'value' => $certification->licence,
	    'placeholder' => elgg_echo('gcconnex_profile:certification:licence'),
	    'class' => 'form-control gcconnex-certification-licence',
	));
	
$formbody .= '<table><tr><td>'. elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:certification:startmonth'),
	    'required' => true,
	    'name' => 'startmonth',
	    'value' => $certification->startmonth,
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
	    'class' => 'form-control gcconnex-certification-startmonth',
	)) ;

$formbody .= elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:certification:startyear'),
	    'name' => 'startyear',
	    'value' => $certification->startyear,
	    'options_values' => $start_year_array,
	    'class' => 'form-control gcconnex-certification-startyear',
	)) .'</td><td></td>';


    
$endyear_array = array(
            '#type' => 'select',
            '#label' => elgg_echo('gcconnex_profile:certification:endyear'),
            'name' => 'endyear',
            'value' => $certification->endyear,
            'options_values' => $end_year_array,
            'class' => 'form-control gcconnex-certification-endyear',
	);
    
$endmonth_array = array(
            '#type' => 'select',
            '#label' => elgg_echo('gcconnex_profile:certification:endmonth'),
            'required' => true,
            'name' => 'endmonth',
            'value' => $certification->endmonth,
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
            'class' => 'form-control gcconnex-certification-endmonth',
	);
	
    if ($certification->ongoing == 'true') {
        $params['disabled'] = 'true';
        $endyear_array['disabled'] = 'true';
        $endmonth_array['disabled'] = 'true';
    }
    
    unset($params);	
	
$formbody .=  '</tr><tr><td>'. elgg_view_field($endmonth_array) ;
    
/*
    if ($certification->certexpired == 'true') {
        $params['disabled'] = 'true';
    }
    
    unset($params);*/

    $params = array(
        'name' => 'ongoing',
        'class' => 'gcconnex-education-ongoing',
        'onclick' => 'toggleEndDate("certification", this)',
	'switch' => true,
    );

    if ($certification->ongoing == 'true') {
        $params['checked'] = $certification->ongoing;
    }

$formbody .=   elgg_view_field($endyear_array) .'</td>';

$formbody .=   '<td>&nbsp;&nbsp;<label>' . elgg_view('input/checkbox', $params). ' ' .elgg_echo('gcconnex_profile:certification:certexpired'). '</label></td></tr></table>';
/*
$formbody .=  '<td>'. elgg_view_field(array(
            '#type' => 'checkbox',
	    '#label' => elgg_echo('gcconnex_profile:certification:certexpired'),
	    'name' => 'certexpired',
	    'value' => $certification->certexpired,
	    'onclick' => 'toggleEndDate("certification", this)',
	    'class' => 'gcconnex-certification-ongoing',
	)) .'</td></tr></table>';*/
    // enter activity and association
$formbody .= elgg_view_field(array(
            '#type' => 'url',
	    '#label' => elgg_echo('gcconnex_profile:certification:certurl'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:certification:certurl:help'),
	    'name' => 'certurl',
	    'value' => $certification->certurl,
	    'placeholder' => elgg_echo('gcconnex_profile:certification:certurl'),
	    'class' => 'form-control gcconnex-certification-certurl',
	));
	

/*$formbody .= '	<div class="btn-group" data-toggle="buttons"><label class="btn btn-success active">
				<input type="checkbox" autocomplete="off" checked>
				<span class="glyphicon glyphicon-ok"></span>
			</label></div>';*/
			
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
	'class' => 'certification-form',
	//'action' => 'action/pessek_profile/education/add',
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

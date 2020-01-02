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

$education = get_entity($guide);

$guidE = ($education != NULL)? $guide : '0';


$start_year_array = [];
$end_year_array = [];
$annee = date("Y");

for($i=1958; $i<=$annee; $i++){
    $start_year_array[$i] = strval($i);
}
    
for($i=1958; $i<=$annee + 7; $i++){
    $end_year_array[$i] = strval($i);
}


//$b = $guid.' / '.$guide; 

echo '<div class="gcconnex-education-entry well" data-guid="' . $guid . '">'; // education entry wrapper for css styling

$form_title = elgg_echo('pessek_profile:education:training');


$formbody = '';


$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:education:access').'</td>';

    $user = get_user($guid);
    $access_id = $user->education_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => 'accesslevel_education',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-education-access'
    );

$formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';

    //get the array of user education entities
    $education_guid = $user->education;


$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:school'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:school:help'),
	    'required' => true,
	    'name' => 'education',
	    'value' => $education->school,
	    'placeholder' => elgg_echo('gcconnex_profile:education:school'),
	    'class' => 'form-control salo',// gcconnex-education-school',
	));
	
$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:diploma'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:diploma:help'),
	    'required' => true,
	    'name' => 'diploma',
	    'value' => $education->diploma,
	    'placeholder' => elgg_echo('gcconnex_profile:education:diploma'),
	    'class' => 'form-control gcconnex-education-diploma',
	));
	
$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:field'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:field:help'),
	    'required' => true,
	    'name' => 'fieldofstudy',
	    'value' => $education->field,
	    'placeholder' => elgg_echo('gcconnex_profile:education:field'),
	    'class' => 'form-control gcconnex-education-field',
	));

$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:degree'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:degree:help'),
	    'required' => true,
	    'name' => 'degree',
	    'value' => $education->degree,
	    'placeholder' => elgg_echo('gcconnex_profile:education:degree'),
	    'class' => 'form-control gcconnex-education-degree',
	));
	
$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:resultobtain'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:resultobtain:help'),
	    'name' => 'resultobtain',
	    'value' => $education->resultobtain,
	    'placeholder' => elgg_echo('gcconnex_profile:education:resultobtain'),
	    'class' => 'form-control gcconnex-education-resultobtain',
	));

$formbody .= '<table><tr><td>'. elgg_view_field([
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:education:year'),
	    'required' => true,
	    'name' => 'startyear',
	    'value' => $education->startyear,
	    'options_values' => $start_year_array,
	    'class' => 'form-control gcconnex-education-start-year',
]) .'</td><td></td></tr>';

	
$endyear_array = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:education:endyear'),
	    'required' => true,
	    'name' => 'endyear',
	    'value' => $education->endyear,
	    'options_values' => $end_year_array,
	    'class' => 'form-control gcconnex-education-end-year',
);
	
    if ($education->ongoing == 'true') {
        $params['disabled'] = 'true';
        $endyear_array['disabled'] = 'true';
    }
    
    unset($params);

$formbody .=  '<tr><td>'. elgg_view_field($endyear_array) .'</td>';

/*
$formbody .=  '<tr><td>'. elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:education:endyear'),
	    'required' => true,
	    'name' => 'endyear',
	    'value' => $education->endyear,
	    'options_values' => $end_year_array,
	    'class' => 'gcconnex-education-end-year',
	)) .'</td>';*/

    if ($education->ongoing == 'true') {
        $params['disabled'] = 'true';
    }
    
    unset($params);

    $params = array(
        'name' => 'ongoing',
        'class' => 'gcconnex-education-ongoing',
        'onclick' => 'toggleEndDate("education", this)',
	'switch' => true,
    );

    if ($education->ongoing == 'true') {
        $params['checked'] = $education->ongoing;
    }

$formbody .=   '<td>&nbsp;&nbsp;<label>' . elgg_view('input/checkbox', $params). ' ' .elgg_echo('gcconnex_profile:education:ongoing'). '</label></td></tr></table>';

$formbody .= elgg_view_field(array(
            '#type' => 'url',
	    '#label' => elgg_echo('gcconnex_profile:education:url'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:url:help'),
	    'name' => 'educationurl',
	    'value' => $education->educationurl,
	    'placeholder' => elgg_echo('gcconnex_profile:education:url'),
	    'class' => 'form-control gcconnex-education-educationurl',
	));
    // enter activity and association
$formbody .= elgg_view_field(array(
            '#type' => 'plaintext',
	    '#label' => elgg_echo('gcconnex_profile:education:activity'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:activity:help'),
	    'name' => 'activity',
	    'maxlength' => '200',
	    'value' => html_entity_decode($education->activity),
	    'placeholder' => elgg_echo('gcconnex_profile:education:activity'),
	    'class' => 'form-control gcconnex-education-activity',
	));
    // enter Training description
$formbody .= elgg_view_field(array(
            '#type' => 'plaintext',
	    '#label' => elgg_echo('gcconnex_profile:education:trainingd'),
	    'name' => 'trainingd',
	    'maxlength' => '1500',
	    'value' => html_entity_decode($education->trainingd),
	    'placeholder' => elgg_echo('gcconnex_profile:education:trainingd'),
	    'class' => 'form-control gcconnex-education-trainingd',
	));

	
$formbody .=  '<br><div class="[ form-group ]">
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
	'class' => 'education-form',
	//'action' => 'action/pessek_profile/education/add', 
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

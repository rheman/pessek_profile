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

$languages = get_entity($guide);

$guidE = ($languages->level != NULL)? $guide : '0';


//$b = $guid.' / '.$guidE; 

$start_year_array = array();
$end_year_array = array();
$annee = date("Y");
    
for($i=1958; $i<=$annee; $i++){
    $start_year_array[$i] = $i;
};
    
for($i=1958; $i<=$annee + 7; $i++){
    $end_year_array[$i] = $i;
};

echo '<div class="gcconnex-languages-entry well" data-guid="' . $guid . '">'; // certification entry wrapper for css styling

$form_title = elgg_echo('pessek_profile:languages:training');

$formbody = '';

$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:languages:access').'</td>';

    $user = get_user($guid);
    $access_id = $user->languages_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel_languages",//'name' => 'accesslevel_certification',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-languages-access'
    );

$formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';

$formbody .= '<br><br>';

$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:langs:title'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:langs:title:help'),
	    'required' => true,
	    'name' => 'langs',
	    'value' => $languages->langs,
	    'placeholder' => elgg_echo('gcconnex_profile:langs:title'),
	    'class' => 'form-control gcconnex-languages-langs',
	));
	
$langs_level = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:langs:level'),
	    'required' => true,
	    'name' => 'level',
	    'value' => $languages->level,
	    'options_values' =>  array(
                                        '' => elgg_echo('gcconnex_profile:langs:select'),
                                        '0' => elgg_echo('gcconnex_profile:langs:elementory'),
                                        '1' => elgg_echo('gcconnex_profile:langs:limited:working'),
                                        '2' => elgg_echo('gcconnex_profile:langs:professional:working'),
                                        '3' => elgg_echo('gcconnex_profile:langs:full:professional'),
                                        '4' => elgg_echo('gcconnex_profile:langs:native')),
	    'class' => 'form-control selectwidth gcconnex-languages-level',
	);

$formbody .= '<br><table><tr><td>'.elgg_view_field($langs_level).'</td><td></td></tr></table>';

$formbody .= '<br><br>';

	
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
	//'onclick' => 'ajaxPortfolio()',
]);

$formbody .= '<div class="ajax_auf_loading" align="center" >
            <img align="absmiddle" src="' . elgg_get_site_url() . 'mod/pessek_profile/img/ajax-loader.gif">&nbsp;'.elgg_echo('pessek_profile:in:progress').'...
</div>';

$form = elgg_view('input/form', [
	'body' => $formbody,
	'class' => 'languages-form',
	'onclick' => 'LoadLanguages()',
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

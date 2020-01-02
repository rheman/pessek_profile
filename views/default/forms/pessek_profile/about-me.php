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

$guidE = ($languages != NULL)? $guide : '0';

echo '<div class="gcconnex-languages-entry well" data-guid="' . $guid . '">'; // certification entry wrapper for css styling

$form_title = elgg_echo('pessek_profile:about_me:training');

$formbody = '';

$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:about_me:access').'</td>';

    $user = get_user($guid);
    
    $description_guid = $user->description;

    if (!(is_array($description_guid))) {
                $description_guid = array($description_guid);
    }

    foreach ($description_guid as $guida) {
        if ($description = get_entity($guida)) {
            $guidE = $guida;
        }
        
    }
    
    $access_id = $user->description_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel_description",//'name' => 'accesslevel_certification',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-languages-access'
    );
  
$formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';

$formbody .= '<br><br>';

$formbody .= elgg_view_field(array(
            '#type' => 'plaintext',
	    '#label' => elgg_echo('gcconnex_profile:about_me'),
	    'required' => true,
	    'name' => 'descriptionname',
	    'maxlength' => '500',
	    'value' => html_entity_decode($description->descriptionname),
	    'class' => 'form-control gcconnex-education-diploma',
	));

	
$formbody .=  '<br><div class="[ form-group ]">
            <input type="checkbox" name="sharechanges" class="form-control" id="fancy-checkbox-success" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-success" class="[ btn  btn-success ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-success" class="[ btn  btn-default active shared-profile-changes ]">
                    '. elgg_echo('pessek_profile:education:sharechange') .'
                </label>
            </div>
        </div>';
//[ btn  btn-default active shared-profile-changes ]
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
	'class' => 'description-form',
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

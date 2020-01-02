<?php
/**
* Profile Manager
*
* Category add form
*
* @package profile_manager
* @author ColdTrick IT Solutions
* @copyright Coldtrick IT Solutions 2009
* @link http://www.coldtrick.com/
*/
//$guid1 = elgg_get_logged_in_guid();

$guid = elgg_get_logged_in_user_entity()->guid;


if (elgg_is_admin_logged_in()) {

   $guid = (string) get_input('guidp');// $guid = (string) $_GET["guidp"];
}

$guide = (string) get_input('guide');

$skills = get_entity($guide);

$guidE = ($skills != NULL)? $guide : '0';


echo '<div class="gcconnex-skills-entry well" data-guid="' . $guid . '">'; // certification entry wrapper for css styling

$form_title = elgg_echo('pessek_profile:skills:add');

$formbody = '';

$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:skills:access').'</td>';

    $user = get_user($guid);
    $access_id = $user->skills_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel_skills",//'name' => 'accesslevel_certification',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-skills-access'
    );

$formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';

$formbody .= '<br><br>';

$formbody .=  '<div class="skills-list">';

$skills_guid = $user->skills;

if (!(is_array($skills_guid))) {
            $skills_guid = array($skills_guid);
}

    if(count($skills_guid) > 0){
    
        $formbody .=  '<div class="list-skills">';
        
        foreach ($skills_guid as $guids) {
            
            if ($entry = get_entity($guids)) {
                
                $skills_val_cat = $entry->guid . '@' . $entry->id . '@' . $entry->title . '' ;
               
                $formbody .=   '<div class="gcconnex-skill-entry temporarily-added" data-skill="' . $entry->title . '" data-guid="' . $entry->id . '" data-guid-skillfind="' . $entry->id . '">';
                $formbody .=   '<span title="Number of endorsements" class="gcconnex-endorsements-count" data-skill="' . $entry->title . '">&nbsp;</span>';
                $formbody .=   '<span data-skill="' . $entry->title . '" class="gcconnex-endorsements-skill">' . $entry->title . '</span>';
                $formbody .=   '<span class="delete-skill-pessek btn btn-danger btn-xs glyphicon glyphicon-trash" data-type="skill" onclick="deleteEntry(this)"></span>';
                $formbody .=   '<input type="hidden" value="' . $skills_val_cat . '" name="user@' . $entry->id . '" id="user@' . $entry->id . '" class="user@' . $entry->id . '"></div>';
                
            }
        }
        
        $formbody .=  '</div>';
        
    }
    else{
    
        $formbody .=  '<div class="list-skills"></div>';
        
    }
    
$formbody .=  '</div>';

$formbody .= '<br>';

$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:skills:title'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:skills:title:help'),
	    //'required' => true,
	    'name' => 'skill',
	    'value' => $skills->skill,
	    'placeholder' => elgg_echo('gcconnex_profile:skills:title'),
	    'class' => 'gcconnex-skills-skill',
	));

$formbody .= '<br><br>';

if ($languages->sharechanges == 'true') {
        $sharechanges_checked = 'checked';
}

	
$formbody .=  '<br><div class="[ form-group ]">
            <input type="checkbox" name="sharechanges" id="fancy-checkbox-success" autocomplete="off" '.$sharechanges_checked.' />
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

$formbody .= '<br>';
	
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
	'class' => 'skills-form',
	'onclick' => 'LoadSkills()',
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

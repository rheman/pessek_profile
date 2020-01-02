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

$mooc = get_entity($guide);

$guidE = ($mooc != NULL)? $guide : '0';


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

    echo '<div class="gcconnex-mooc-entry well" data-guid="' . $guid . '">'; // mooc entry wrapper for css styling

    $form_title = elgg_echo('pessek_profile:mooc:training');

    $formbody = '';

    $formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:mooc:access').'</td>';

    $user = get_user($guid);
    $access_id = $user->mooc_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel_mooc",//'name' => 'accesslevel_certification',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-certification-access'
    );

    $formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';

    //get the array of user education entities
    $education_guid = $user->mooc;

    $formbody .= '<br>'; //a voir dans certif et education

    $formbody .= elgg_view_field(array(
                '#type' => 'text',
                '#label' => elgg_echo('gcconnex_profile:mooc:name'),
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:mooc:name:help'),
                'required' => true,
                'name' => 'mooc',
                'value' => $mooc->name,
                'placeholder' => elgg_echo('gcconnex_profile:mooc:name'),
                'class' => 'form-control gcconnex-mooc-name',
            ));
	
    $formbody .= elgg_view_field(array(
                '#type' => 'number',
                '#label' => elgg_echo('gcconnex_profile:mooc:ects'),
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:mooc:ects:help'),
                'name' => 'etcs',
                'value' => $mooc->etcs,
                'placeholder' => elgg_echo('gcconnex_profile:mooc:ects'),
                'class' => 'form-control gcconnex-mooc-ects',
                //'id' => 'data-json',
            ));
            
    $formbody .= elgg_view_field(array(
                '#type' => 'number',
                '#label' => elgg_echo('gcconnex_profile:mooc:hours'),
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:mooc:hours:help'),
                'name' => 'hours',
                'value' => $mooc->hours,
                'placeholder' => elgg_echo('gcconnex_profile:mooc:hours:placeholder'),
                'class' => 'form-control gcconnex-mooc-hours',
            ));
            
    $formbody .= '<table><tr><td>'. elgg_view_field(array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:certification:startmonth'),
                'required' => true,
                'name' => 'startmonth',
                'value' => $mooc->startmonth,
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
                'class' => 'form-control gcconnex-mooc-startmonth',
            ));

    $formbody .= elgg_view_field(array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:certification:startyear'),
                'name' => 'startyear',
                'value' => $mooc->startyear,
                'options_values' => $start_year_array,
                'class' => 'form-control gcconnex-mooc-startyear',
            )) .'</td><td></td>';

	
    $endyear_array = array(
            '#type' => 'select',
	    '#label' => '&nbsp;',
	    'name' => 'endyear',
	    'value' => $mooc->endyear,
	    'options_values' => $end_year_array,
	    'class' => 'form-control gcconnex-mooc-end-year',
	);
    
    
    $endmonth_array = array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:certification:endmonth'),
                'required' => true,
                'name' => 'endmonth',
                'value' => $mooc->endmonth,
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
                'class' => 'form-control gcconnex-mooc-endmonth',
            );
    
/*
    if ($certification->certexpired == 'true') {
        $params['disabled'] = 'true';
    }
    
    unset($params);*/

    $params = array(
        'name' => 'ongoing',
        'class' => 'gcconnex-education-ongoing',
        'onclick' => 'toggleEndDate("mooc", this)',
	'switch' => true,
    );
    
    if ($mooc->ongoing == 'true') {
        $params['checked'] = $mooc->ongoing;
        $endyear_array['disabled'] = 'true';
        $endmonth_array['disabled'] = 'true';
    }
    
    $formbody .=  '</tr><tr><td>'. elgg_view_field($endmonth_array) ;

    /*$formbody .=   '<td>'. elgg_view_field(array(
                '#type' => 'select',
                '#label' => elgg_echo('gcconnex_profile:certification:endyear'),
                'value' => $mooc->endyear,
                'options_values' => $end_year_array,
                'class' => 'gcconnex-mooc-endyear',
            )) .'</td>';*/
    $formbody .=   elgg_view_field($endyear_array) .'</td>';

    $formbody .=   '<td>&nbsp;&nbsp;<label>' . elgg_view('input/checkbox', $params). ' ' .elgg_echo('gcconnex_profile:present'). '</label></td></tr></table>';

    $formbody .= elgg_view_field(array(
                '#type' => 'plaintext',
                '#label' => elgg_echo('gcconnex_profile:mooc:competences'),
                'required' => true,
                'name' => 'competences',
                'maxlength' => '1500',
                'value' => html_entity_decode($mooc->competences),
                'placeholder' => elgg_echo('gcconnex_profile:mooc:competences'),
                'class' => 'form-control gcconnex-mooc-competences',
            ));
    
    $formbody .= elgg_view_field(array(
                '#type' => 'url',
                '#label' => elgg_echo('gcconnex_profile:mooc:moocutl'),
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:mooc:moocutl:help'),
                'required' => true,
                'name' => 'moocurl',
                'value' => $mooc->moocurl,
                'placeholder' => elgg_echo('gcconnex_profile:mooc:moocutl'),
                'class' => 'form-control gcconnex-mooc-moocurl',
            ));
	
    $formbody .= elgg_view_field(array(
                '#type' => 'url',
                '#label' => elgg_echo('gcconnex_profile:mooc:certurl'),
                '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:certification:certurl:help'),
                'name' => 'certurl',
                'value' => $mooc->certurl,
                'placeholder' => elgg_echo('gcconnex_profile:mooc:certurl'),
                'class' => 'form-control gcconnex-mooc-certurl',
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
	'class' => 'mooc-form',
	//'action' => 'action/pessek_profile/education/add',
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

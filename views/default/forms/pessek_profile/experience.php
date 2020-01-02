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

$experience = get_entity($guide);

$guidPessek = ($publications != NULL)? $guide : "new";

$guidE = ($experience != NULL)? $guide : '0';


//$b = $guid.' / '.$guide; 

$start_year_array = array();
$end_year_array = array();
$annee = date("Y");
    
for($i=1958; $i<=$annee; $i++){
    $start_year_array[$i] = strval($i);
};
    
for($i=1958; $i<=$annee; $i++){
    $end_year_array[$i] = strval($i);
};

echo '<div class="gcconnex-experience-entry ' . $guidPessek . ' well" data-guid="' . $guidPessek . '">'; // experience entry wrapper for css styling

$form_title = elgg_echo('pessek_profile:experience:training');

$formbody = '';

$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:experience:access').'</td>';

    $user = get_user($guid);
    $access_id = $experience->access_id;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel_experience",//'name' => 'accesslevel_experience',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-experience-access'
    );

$formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';

    //get the array of user education entities
$education_guid = $user->experience;


//$formbody .=  '<input type="text" name="testpessek"><span>focus fire</span>';

$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:experience:jobtitle'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:experience:jobtitle:help'),
	    'required' => true,
	    'name' => 'jobtitle',
	    'value' => $experience->jobtitle,
	    'placeholder' => elgg_echo('gcconnex_profile:experience:jobtitle'),
	    'class' => 'form-control gcconnex-experience-jobtitle',
	    //'onfocus' => 'testtest(this)',
	    'autofocus' => true,
	));
	
$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:experience:companyname'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:experience:companyname:help'),
	    'required' => true,
	    'name' => 'companyname',
	    'value' => $experience->companyname,
	    'placeholder' => elgg_echo('gcconnex_profile:experience:companyname'),
	    'class' => 'form-control gcconnex-experience-companyname',
	    //'id' => 'data-json',
	)) ;

$experience_type_array = array(
    '#type' => 'select',
    '#label' => elgg_echo('gcconnex_profile:experience:experiencetype'),
    'required' => true,
    'name' => 'experience_type',
    'value' => $experience->experience_type,
    'class' => 'form-control selectwidth',
    'options_values' => array(
                                '' => elgg_echo('gcconnex_profile:experience:experiencetype:select'), 
                                '0' => elgg_echo('gcconnex_profile:internship:lib:lib'), 
                                '1' => elgg_echo('gcconnex_profile:volunteer:lib:lib'), 
                                '2' => elgg_echo('gcconnex_profile:experience:lib:lib')), 
);

$formbody .=  '<table><tr><td>'. elgg_view_field($experience_type_array) .'</td><td></td></tr></table>';

if(isset($experience->country) && !is_null($experience->country) && $experience->country != ''){

    $formbody .=  '<div class="list-country">';
    
    $countryconcat = '' . $experience->flag . '@' . $experience->country . '';
    
    $formbody .=  '<div class="gcconnex-country-in-list temporarily-added"><input type="hidden" value="' . $countryconcat . '" name="thecountry" id="thecountry" class="thecountry"></div>';
    
    $formbody .=  '</div>';
    

}else{

    $formbody .=  '<div class="list-country">';
    $formbody .=  '</div>';

}


//$formbody .=  '<div class="list-country">';
//$formbody .=  '</div>';

$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:experience:country'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:experience:country:help'),
	    'required' => true,
	    'name' => 'country',
	    'value' => $experience->country,
	    'placeholder' => elgg_echo('gcconnex_profile:experience:country'),
	    'class' => 'form-control gcconnex-experience-country countryfind',
	)) ;
	


$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:experience:place'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:experience:place:help'),
	    'required' => true,
	    'name' => 'place',
	    'value' => $experience->place,
	    'placeholder' => elgg_echo('gcconnex_profile:experience:place'),
	    'class' => 'form-control gcconnex-experience-place',
	)) ;
	
$formbody .= '<table><tr><td>'. elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:experience:startmonth'),
	    'required' => true,
	    'name' => 'startmonth',
	    'value' => $experience->startmonth,
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
	    'class' => 'form-control gcconnex-experience-startmonth',
	)) ;

$formbody .= elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:experience:startyear'),
	    //'required' => true,
	    'name' => 'startyear',
	    'value' => $experience->startyear,
	    'options_values' => $start_year_array,
	    'class' => 'form-control gcconnex-experience-startyear',
	)) .'</td><td></td>';

    
$endyear_array = array(
            '#type' => 'select',
            '#label' => elgg_echo('gcconnex_profile:experience:endyear'),
            //'required' => true,
            'name' => 'endyear',
            'value' => $experience->endyear,
            'options_values' => $end_year_array,
            'class' => 'form-control gcconnex-experience-endyear',
	);
    
$endmonth_array = array(
            '#type' => 'select',
            '#label' => elgg_echo('gcconnex_profile:experience:endmonth'),
            'required' => true,
            'name' => 'endmonth',
            'value' => $experience->endmonth,
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
            'class' => 'form-control gcconnex-experience-endmonth',
	);

	
    if ($experience->ongoing == 'true') {
        $params['disabled'] = 'true';
        $endyear_array['disabled'] = 'true';
        $endmonth_array['disabled'] = 'true';
    }
    
    unset($params);	
	
$formbody .=  '</tr><tr><td>'. elgg_view_field($endmonth_array) ;

    $params = array(
        'name' => 'ongoing',
        'class' => 'gcconnex-education-ongoing',
        'onclick' => 'toggleEndDate("experience", this)',
	'switch' => true,
    );
    if ($experience->ongoing == 'true') {
        $params['checked'] = $experience->ongoing;
    }

$formbody .=   elgg_view_field($endyear_array) .'</td>';

$formbody .=   '<td>&nbsp;&nbsp;<label>' . elgg_view('input/checkbox', $params). ' ' .elgg_echo('gcconnex_profile:experience:currentposition'). '</label></td></tr></table>';


$formbody .= elgg_view_field(array(
            '#type' => 'url',
	    '#label' => elgg_echo('gcconnex_profile:experience:certurl'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:experience:certurl:help'),
	    'name' => 'companyurl',
	    'value' => $experience->companyurl,
	    'placeholder' => elgg_echo('gcconnex_profile:experience:certurl'),
	    'class' => 'form-control gcconnex-experience-certurl',
	));

$formbody .=    elgg_view_field(array(
                '#type' => 'plaintext',
                '#label' => elgg_echo('gcconnex_profile:experience:jobdescription'),
                'required' => true,
                'name' => 'jobdescription',
                'maxlength' => '1500',
                'value' => html_entity_decode($experience->jobdescription),
                'placeholder' => elgg_echo('gcconnex_profile:experience:jobdescription'),
                'class' => 'form-control gcconnex-experience-jobdescription',
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
	'class' => 'experience-form',
	//'id' => 'experience-form1',
	'onclick' => 'LoadCountryLocation()',
	//'action' => 'action/pessek_profile/education/add',
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

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

$basic = get_entity($guide);

$guidE = ($basic != NULL)? $guide : '0';



$fields = array('Name', 'Title', 'Department', 'Phone', 'Mobile', 'Email', 'Website');

echo '<div class="gcconnex-portfolio-entry well" data-guid="' . $guid . '">'; // certification entry wrapper for css styling

$form_title = elgg_echo('gcconnex_profile:basic:header');

$formbody = '';

$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:basic:access').'</td>';

    $user = get_user($guid);
    $access_id = $user->basic_access;
    $params = array(
        'name' => "accesslevel_basic",
        'value' => $access_id,
        'class' => 'form-control gcconnex-basic-access'
    );

$formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';


$formbody .= '<br>';

//country of origin

if(isset($user->countryoforigin) && !is_null($user->countryoforigin) && $user->countryoforigin != ''){

    $formbody .=  '<div class="list-country-origin">';
    
    $countryconcat = '' . $user->flag . '@' . $user->countryoforigin . '';
    
    $formbody .=  '<div class="gcconnex-country-in-list-origin temporarily-added"><input type="hidden" value="' . $countryconcat . '" name="thecountryoforigin" id="thecountryoforigin" class="thecountryoforigin"></div>';
    
    $formbody .=  '</div>';
    

}else{

    $formbody .=  '<div class="list-country-origin">';
    $formbody .=  '</div>';

}
//end country of origin

//country of residence

if(isset($user->countryofresidence) && !is_null($user->countryofresidence) && $user->countryofresidence != ''){

    $formbody .=  '<div class="list-country-residence">';
    
    $thecountryofresidence = '' . $user->flag . '@' . $user->countryofresidence . '';
    
    $formbody .=  '<div class="gcconnex-country-in-list-residence temporarily-added"><input type="hidden" value="' . $thecountryofresidence . '" name="thecountryofresidence" id="thecountryofresidence" class="thecountryofresidence"></div>';
    
    $formbody .=  '</div>';
    

}else{

    $formbody .=  '<div class="list-country-residence">';
    $formbody .=  '</div>';

}
//end country of residence

$formbody .=  '<div class="basic-profile">'; // outer container for all content (except the form title above) for css styling
    $formbody .=  '<div class="basic-profile-standard-field-wrapper">'; // container for css styling, used to group profile content and display them seperately from other fields
    
    $formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:name'),
	    'required' => true,
	    'name' => 'name',
	    'value' => $user->getDisplayName(),
	    'placeholder' => elgg_echo('gcconnex_profile:basic:name'),
	    'class' => 'form-control gcconnex-basic-name',
	));
    
    $formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:title'),
	    'required' => true,
	    'name' => 'Title',
	    'value' => $user->titles,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:title'),
	    'class' => 'form-control gcconnex-basic-title',
	));

    $formbody .= '<table><tr><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('profile:countryoforigin'),
	    'required' => true,
	    'name' => 'CountryOfOrigin',
	    'value' => $user->countryoforigin,
	    'placeholder' => elgg_echo('profile:countryoforigin'),
	    'class' => 'form-control gcconnex-country-origin countryfind',
	)).'</td>';

     $formbody .= '</td><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('profile:countryofresidence'),
	    'required' => true,
	    'name' => 'CountryOfResidence',
	    'value' => $user->countryofresidence,
	    'placeholder' => elgg_echo('profile:countryofresidence'),
	    'class' => 'form-control gcconnex-country-residence countryfind',
	)).'</td>';
	
    $formbody .= '</td><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('profile:townofresidence'),
	    'required' => true,
	    'name' => 'TownOfResidence',
	    'value' => $user->townofresidence,
	    'placeholder' => elgg_echo('profile:townofresidence'),
	    'class' => 'form-control gcconnex-country-townofresidence',
	)).'</td></tr></table>';
/*	
if (elgg_is_admin_logged_in()) {	
        $formbody .= '<table><tr><td>'.elgg_view_field(array(
                '#type' => 'text',
                '#label' => elgg_echo('profile:anneeacademique'),
                'name' => 'anneeacademique',
                'required' => true,
                'value' => $user->anneeacademique,
                'placeholder' => elgg_echo('profile:anneeacademique'),
                'class' => 'form-control gcconnex-basic-phone',
            ));
        
        $formbody .= '</td><td>'.elgg_view_field(array(
                '#type' => 'text',
                '#label' => elgg_echo('profile:masteresfam'),
                'name' => 'masteresfam',
                'required' => true,
                'value' => $user->masteresfam,
                'placeholder' => elgg_echo('profile:masteresfam'),
                'class' => 'form-control gcconnex-basic-mobile',
            )).'</td></tr>';
    }else{
    
        $formbody .= '<table><tr><td>'.elgg_view_field(array(
                '#type' => 'text',
                '#label' => elgg_echo('profile:anneeacademique'),
                'name' => 'anneeacademique',
                'readonly' => true,
                'required' => true,
                'value' => $user->anneeacademique,
                'placeholder' => elgg_echo('profile:anneeacademique'),
                'class' => 'form-control gcconnex-basic-phone',
            ));
        
        $formbody .= '</td><td>'.elgg_view_field(array(
                '#type' => 'text',
                '#label' => elgg_echo('profile:masteresfam'),
                'name' => 'masteresfam',
                'readonly' => true,
                'required' => true,
                'value' => $user->masteresfam,
                'placeholder' => elgg_echo('profile:masteresfam'),
                'class' => 'form-control gcconnex-basic-mobile',
            )).'</td></tr>';
    
}
*/
    
    $formbody .= '<table><tr><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:phone'),
	    'name' => 'Phone',
	    'required' => true,
	    'value' => $user->phone,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:phone'),
	    'class' => 'form-control gcconnex-basic-phone',
	));
    
    $formbody .= '</td><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:mobile'),
	    'name' => 'Mobile',
	    'value' => $user->mobile,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:mobile'),
	    'class' => 'form-control gcconnex-basic-mobile',
	)).'</td></tr>';
	
    
    $formbody .= '<tr><td>'.elgg_view_field(array(
            '#type' => 'email',
	    '#label' => elgg_echo('gcconnex_profile:basic:email'),
	    'name' => 'Email',
	    'readonly' => true,
	    'required' => true,
	    'value' => $user->email,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:email'),
	    'class' => 'form-control gcconnex-basic-email',
	));
    
    
    $formbody .= '</td><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:pinterest'),
	    'name' => 'pinterest',
	    'value' => $user->pinterest,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:pinterest:placeholder'),
	    'class' => 'form-control gcconnex-basic-field gcconnex-social-media gcconnex-basic-pinterest',
	)).'</td></tr>';
	
    /*
    $formbody .= '</td><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:website'),
	    'name' => 'Website',
	    'value' => $user->website,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:website'),
	    'class' => 'form-control gcconnex-basic-website',
	)).'</td></tr>';
	*/
    $formbody .=  '</div>';
    
    $formbody .=  '<div class="basic-profile-social-media-wrapper">';
    
    $formbody .= '<tr><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:facebook'),
	    'name' => 'Facebook',
	    'value' => $user->facebook,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:facebook:placeholder'),
	    'class' => 'form-control gcconnex-basic-field gcconnex-social-media gcconnex-basic-facebook',
	)).'</td>';
    
    $formbody .= '<td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:instagram'),
	    'name' => 'instagram',
	    'value' => $user->instagram,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:instagram:placeholder'),
	    'class' => 'form-control gcconnex-basic-field gcconnex-social-media gcconnex-basic-instagram',
	)).'</td></tr>';
    
    $formbody .= '<tr><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:github'),
	    'name' => 'github',
	    'value' => $user->github,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:github:placeholder'),
	    'class' => 'form-control gcconnex-basic-field gcconnex-social-media gcconnex-basic-github',
	)).'</td>';
    
    $formbody .= '<td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:twitter'),
	    'name' => 'twitter',
	    'value' => $user->twitter,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:twitter:placeholder'),
	    'class' => 'form-control gcconnex-basic-field gcconnex-social-media gcconnex-basic-twitter',
	)).'</td></tr>';
	
    $formbody .= '<tr><td>'.elgg_view_field(array(
            '#type' => 'url',
	    '#label' => elgg_echo('gcconnex_profile:basic:linkedin'),
	    'name' => 'linkedin',
	    'value' => $user->linkedin,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:linkedin:placeholder'),
	    'class' => 'form-control gcconnex-basic-linkedin',
	)).'</td>';
    
    $formbody .= '<td>'.elgg_view_field(array(
            '#type' => 'url',
	    '#label' => elgg_echo('gcconnex_profile:basic:website'),
	    'name' => 'Website',
	    'value' => $user->website,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:website'),
	    'class' => 'form-control gcconnex-basic-website',
	)).'</td></tr>';
	
    $formbody .= '<tr><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:tumblr'),
	    'name' => 'tumblr',
	    'value' => $user->tumblr,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:tumblr:placeholder'),
	    'class' => 'form-control gcconnex-basic-field gcconnex-social-media gcconnex-basic-tumblr',
	)).'</td>';

    $formbody .= '</td><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('profile:youtube'),
	    'name' => 'youtube',
	    'value' => $user->youtube,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:youtube:placeholder'),
	    'class' => 'form-control gcconnex-basic-field gcconnex-social-media gcconnex-basic-youtube',
	)).'</td></tr>';
	
    $formbody .= '<tr><td>'.elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:basic:flickr'),
	    'name' => 'flickr',
	    'value' => $user->flickr,
	    'placeholder' => elgg_echo('gcconnex_profile:basic:flickr:placeholder'),
	    'class' => 'form-control gcconnex-basic-field gcconnex-social-media gcconnex-basic-flickr',
	)).'</td>';
	
    $formbody .= '</td><td></td></tr></table>';
	
//for celebration plugin
$start_year_array = array();
$end_year_array = array();
$annee = date("Y");

$start_year_array['0'] = elgg_echo('gcconnex_profile:notset');
for($i=1919; $i<=$annee-15; $i++){
    $start_year_array[$i] = strval($i);
}

$end_year_array['0'] = elgg_echo('gcconnex_profile:notset');
for($i=$annee; $i>=1919; $i--){
    $end_year_array[$i] = strval($i);
}

if($user->celebrations_birthdate!=0){

    $birthdate = $user->celebrations_birthdate;
    $b_ybdate = (int)gmdate('Y', $birthdate);
    $b_mbdate = (int)gmdate('m', $birthdate);
    $b_dbdate = (int)gmdate('d', $birthdate);
    
}

if($user->celebrations_weddingdate!=0){

    $weddingdate = $user->celebrations_weddingdate;
    $w_ybdate = (int)gmdate('Y', $weddingdate);
    $w_mbdate = (int)gmdate('m', $weddingdate);
    $w_dbdate = (int)gmdate('d', $weddingdate);
    
}
//echo 'PIWAL'.$b_ybdate;
$startday_anniv = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:day'),
	    'name' => 'startday_anniv',
	    'id' => 'startday_anniv',
	    'value' => $b_dbdate,
	    'options_values' =>  array('0' => elgg_echo('gcconnex_profile:notset'),'1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', 
                                       '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20',
                                       '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'
                                        ),
	    'class' => 'form-control gcconnex-portfolio-startday',
	);
$startday_wedding = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:day'),
	    'name' => 'startday_wedding',
	    'id' => 'startday_wedding',
	    'value' => $w_dbdate,
	    'options_values' =>  array('0' => elgg_echo('gcconnex_profile:notset'),'1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', 
                                       '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20',
                                       '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'
                                        ),
	    'class' => 'form-control gcconnex-portfolio-startday',
	);
	
$startmonth_anniv = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:startmonth'),
	    'name' => 'startmonth_anniv',
	    'id' => 'startmonth_anniv',
	    'value' => $b_mbdate,
	    'options_values' =>  array(
                                        '0' => elgg_echo('gcconnex_profile:notset'),
                                        '1' => elgg_echo('pessek_profile:January:lib'), 
                                        '2' => elgg_echo('pessek_profile:February:lib'),
                                        '3' => elgg_echo('pessek_profile:March:lib'),
                                        '4' => elgg_echo('pessek_profile:April:lib'),
                                        '5' => elgg_echo('pessek_profile:May:lib'),
                                        '6' => elgg_echo('pessek_profile:June:lib'),
                                        '7' => elgg_echo('pessek_profile:July:lib'),
                                        '8' => elgg_echo('pessek_profile:August:lib'),
                                        '9' => elgg_echo('pessek_profile:September:lib'),
                                        '10' => elgg_echo('pessek_profile:October:lib'),
                                        '11' => elgg_echo('pessek_profile:November:lib'),
                                        '12' => elgg_echo('pessek_profile:December:lib')),
	    'class' => 'form-control gcconnex-portfolio-startmonth',
	);
$startmonth_wedding = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:startmonth'),
	    'name' => 'startmonth_wedding',
	    'id' => 'startmonth_wedding',
	    'value' => $w_mbdate,
	    'options_values' =>  array(
                                        '0' => elgg_echo('gcconnex_profile:notset'),
                                        '1' => elgg_echo('pessek_profile:January:lib'), 
                                        '2' => elgg_echo('pessek_profile:February:lib'),
                                        '3' => elgg_echo('pessek_profile:March:lib'),
                                        '4' => elgg_echo('pessek_profile:April:lib'),
                                        '5' => elgg_echo('pessek_profile:May:lib'),
                                        '6' => elgg_echo('pessek_profile:June:lib'),
                                        '7' => elgg_echo('pessek_profile:July:lib'),
                                        '8' => elgg_echo('pessek_profile:August:lib'),
                                        '9' => elgg_echo('pessek_profile:September:lib'),
                                        '10' => elgg_echo('pessek_profile:October:lib'),
                                        '11' => elgg_echo('pessek_profile:November:lib'),
                                        '12' => elgg_echo('pessek_profile:December:lib')),
	    'class' => 'form-control gcconnex-portfolio-startmonth',
	);

$startyear_anniv = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:startyear'),
	    'name' => 'startyear_anniv',
	    'id' => 'startyear_anniv',
	    'value' => $b_ybdate,
	    'options_values' => $start_year_array,
	    'class' => 'form-control gcconnex-portfolio-startyear',
	);
$startyear_wedding = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:startyear'),
	    'name' => 'startyear_wedding',
	    'id' => 'startyear_wedding',
	    'value' => $w_ybdate,
	    'options_values' => $end_year_array,
	    'class' => 'form-control gcconnex-portfolio-startyear',
	);
/*
$params_birthday = array(
        'name' => 'accesslevel_birthday',
	'#label' => elgg_echo('access'),
        'value' => $access_id,
        'class' => 'form-control gcconnex-basic-access'
    );

$params_wedding = array(
        'name' => 'accesslevel_wedding',
        'label' => elgg_echo('access'),
        'value' => $access_id,
        'class' => 'form-control gcconnex-basic-access'
    );
*/

$params_birthday =  elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('access'),
	'name' => 'accesslevel_birthday',
	'value' => $access_id,
        'class' => 'form-control gcconnex-basic-access'
]);

$params_wedding =  elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('access'),
	'name' => 'accesslevel_wedding',
	'value' => $access_id,
        'class' => 'form-control gcconnex-basic-access'
]);

$formbody .= '<div class="gcconnex-profile-section-frame-wrapper">';
$formbody .= '<div class="gcconnex-profile-title">'.elgg_echo('pessek_profile:Birthday').'</div>';

$formbody .= '<table>';
$formbody .=  '<tr><td>'. elgg_view_field($startday_anniv) .'</td><td></td></tr>';	
//$formbody .=  '<tr><td>'. elgg_view_field($startmonth_anniv) .'</td><td class="inthemiddle"><p><br>'. elgg_view('input/access', $params_birthday) .'</p></td></tr>';
$formbody .=  '<tr><td>'. elgg_view_field($startmonth_anniv) .'</td><td class="inthemiddle">'. $params_birthday .'</td></tr>';
$formbody .=  '<tr><td>'. elgg_view_field($startyear_anniv) .'</td><td></td></tr>';
$formbody .= '</table>';

$formbody .= '</div>';

$formbody .= '<div class="gcconnex-profile-section-frame-wrapper">';
$formbody .= '<div class="gcconnex-profile-title">'.elgg_echo('pessek_profile:Wedding').'</div>';

$formbody .= '<table>';

$formbody .=  '<tr><td>'. elgg_view_field($startday_wedding) .'</td><td></td></tr>';
	
//$formbody .=  '<tr><td>'. elgg_view_field($startmonth_wedding) .'</td><td class="inthemiddle"><p><br>'. elgg_view('input/access', $params_wedding) .'</p></td></tr>';
$formbody .=  '<tr><td>'. elgg_view_field($startmonth_wedding) .'</td><td class="inthemiddle">'. $params_wedding .'</td></tr>';

$formbody .= '<tr><td>'. elgg_view_field($startyear_wedding) .'</td><td></td></tr>';

$formbody .= '</table>';

$formbody .= '</div>';

//end celebration plugin
	
	
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

        
        /*
        $formbody .= elgg_view("input/date", array(
		"name" => "anniversary_date",
		'id' => 'anniversary_date'));
        $formbody .= elgg_view("input/date", array(
		"name" => "wedding_date",
		'id' => 'wedding_date',));*/
        
        
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
	'class' => 'basic-form',
	'onclick' => 'LoadCountryOfOrigin()',
]);

$celebrationfields = array();
	$prefix = elgg_get_config('profile_celebrations_prefix');
	$profile_fields = elgg_get_config('profile_fields');
	if (is_array($profile_fields) && sizeof($profile_fields) > 0) {
		foreach($profile_fields as $shortname => $valtype) {
			$match = '/^'.$prefix.'.*$/';
			if (preg_match($match, $shortname)) {
				$varcelebration = $shortname;
				$celebrationfields[$varcelebration] = $valtype;
			}
		}
	}
	
	//var_dump($celebrationfields);
foreach($celebrationfields as $key => $valtype) {
    
    $celebrationday = $user->$key;
    //echo $key.'/'.$celebrationday.'<br>';
}

$birthdate = '-1608508800';//483667200
$ybdate = (int)gmdate('Y', $birthdate);
$mbdate = (int)gmdate('m', $birthdate);
$dbdate = (int)gmdate('d', $birthdate);

//echo $dbdate.'∕'.$mbdate.'∕'.$ybdate;

echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

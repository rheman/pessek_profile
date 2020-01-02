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

$portfolio = get_entity($guide);

$guidE = ($portfolio != NULL)? $guide : '0';


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

echo '<div class="gcconnex-portfolio-entry well" data-guid="' . $guid . '">'; // certification entry wrapper for css styling

$form_title = elgg_echo('pessek_profile:portfolio:training');

$formbody = '';

$formbody .= '<table><tr><td width="330px" class="toto">'.elgg_echo('gcconnex_profile:portfolio:access').'</td>';

    $user = get_user($guid);
    $access_id = $user->portfolio_access;
    //echo 'Access: ';
    //var_dump($access_id);
    $params = array(
        'name' => "accesslevel_portfolio",//'name' => 'accesslevel_certification',
        //'value' => $education->access_id,
        'value' => $access_id,
        'class' => 'form-control gcconnex-portfolio-access'
    );

$formbody .= '<td width="170px" align="right">'.elgg_view('input/access', $params).'</td></tr></table>';


$formbody .= '<br>'; //a voir dans certif et education

$formbody .= elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:title'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:portfolio:title:help'),
	    'required' => true,
	    'name' => 'portfolio',
	    'value' => $portfolio->thetitle,
	    'placeholder' => elgg_echo('gcconnex_profile:portfolio:title'),
	    'class' => 'form-control gcconnex-portfolio-title',
	));
	
$formbody .= elgg_view_field(array(
            '#type' => 'url',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:link'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:portfolio:link:help'),
	    'name' => 'portfoliolink',
	    'value' => $portfolio->links,
	    'placeholder' => elgg_echo('gcconnex_profile:portfolio:link'),
	    'class' => 'form-control gcconnex-portfolio-link',
	));
	
$formbody .= elgg_view_field(array(
            '#type' => 'plaintext',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:description'),
	    'name' => 'description',
	    'required' => true,
	    'maxlength' => '1500',
	    'value' => html_entity_decode($portfolio->thedescription),
	    'placeholder' => elgg_echo('gcconnex_profile:portfolio:description'),
	    'class' => 'form-control gcconnex-education-trainingd',
	));

$array_startday = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:day'),
	    'required' => true,
	    'name' => 'startday',
	    'value' => $portfolio->startday,
	    'options_values' =>  array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'),
	    'class' => 'form-control gcconnex-portfolio-startday',
	);
	
$array_startmonth = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:startmonth'),
	    'required' => true,
	    'name' => 'startmonth',
	    'value' => $portfolio->startmonth,
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
	    'class' => 'form-control gcconnex-portfolio-startmonth',
	);

$array_startyear = array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:portfolio:startyear'),
	    'required' => true,
	    'name' => 'startyear',
	    'value' => $portfolio->startyear,
	    'options_values' => $start_year_array,
	    'class' => 'form-control gcconnex-portfolio-startyear',
	);

 if ($portfolio->datestamped == 'true') {
        $array_startday['disabled'] = 'true';
        $array_startmonth['disabled'] = 'true';
        $array_startyear['disabled'] = 'true';
    }



    unset($params);

    $params = array(
        'name' => 'datestamped',
        'class' => 'gcconnex-portfolio-datestamped',
        'onclick' => 'toggleEndDate("portfolio", this)',
        'switch' => true,
    );

    if ($portfolio->datestamped == 'true') {
        $params['checked'] = $portfolio->datestamped;
    }
	
$formbody .=  '<br>'.'<table><tr><td>'. elgg_view_field($array_startday) .'</td>';

$formbody .=  '<td>&nbsp;&nbsp;'. elgg_view('input/checkbox', $params) .'<strong>'. elgg_echo('gcconnex_profile:portfolio:datestamp') . '</strong></td></tr>';
	
$formbody .=  '<tr><td>'. elgg_view_field($array_startmonth) .'</td><td></td></tr>';

$formbody .= '<tr><td>'. elgg_view_field($array_startyear) .'</td><td></td></tr></table>';

    
//checked

	
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
	'class' => 'custom-form',
	//'action' => 'action/pessek_profile/portfolio/add',
]);


echo elgg_view_module('inline', $form_title, $form, ['class' => 'mvn', 'id' => 'custom_fields_category_form']);

$guid = NULL;

echo '</div>'; // close div class="gcconnex-education-entry"

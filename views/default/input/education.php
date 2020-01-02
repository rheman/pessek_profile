<?php
/*
 * Author: Bryden Arndt
 * Date: 15/12/14
 * Time: 1:33 PM
 * Purpose: This is a collection of input fields that are grouped together to create an entry for education (designed to be entered for a user's profile).
 */


$education = get_entity($vars['guid']); // get the guid of the education entry that is being requested for display

$guid = ($education != NULL)? $vars['guid'] : "new"; // if the education guid isn't given, this must be a new entry

echo '<div class="gcconnex-education-entry well" data-guid="' . $guid . '">'; // education entry wrapper for css styling

    // enter school name
   /*
    echo '<span class="gcconnex-profile-field-title">';
    echo elgg_echo('gcconnex_profile:education:school') . '</span>';
    echo elgg_view("input/text", array(
            'name' => 'education',
            'class' => 'gcconnex-education-school',
            'value' => $education->school));
    */
echo '<div id="remote">
        <input class="typeahead" type="text" placeholder="Oscar winners for Best Picture">
      </div>';
    // enter school name
    echo elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:school'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:school:help'),
	    'required' => true,
	    'name' => 'education',
	    'value' => $education->school,
	    'class' => 'gcconnex-education-school',
	));
    
    // enter diploma
    echo elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:diploma'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:diploma:help'),
	    'required' => true,
	    'name' => 'diploma',
	    'value' => $education->diploma,
	    'class' => 'gcconnex-education-diploma',
	));
    
    // enter field  of study
    echo elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:field'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:field:help'),
	    'required' => true,
	    'name' => 'fieldofstudy',
	    'value' => $education->field,
	    'class' => 'gcconnex-education-field',
	));
	
    // enter degree
    echo elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:degree'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:degree:help'),
	    'required' => true,
	    'name' => 'degree',
	    'value' => $education->degree,
	    'class' => 'gcconnex-education-degree',
	));
    // enter result obtain
    echo elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:education:resultobtain'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:resultobtain:help'),
	    'name' => 'resultobtain',
	    'value' => $education->resultobtain,
	    'class' => 'gcconnex-education-resultobtain',
	));
  
    $start_year_array = array();
    $end_year_array = array();
    $annee = date("Y");
    
    for($i=1958; $i<=$annee; $i++){
        $start_year_array[$i] = $i;
    };
    
    for($i=1958; $i<=$annee + 7; $i++){
        $end_year_array[$i] = $i;
    };
    // enter start date
    echo elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:education:year'),
	    'required' => true,
	    'name' => 'start-year',
	    'value' => $education->startyear,
	    'options_values' => $start_year_array,
	    'class' => 'gcconnex-education-start-year',
	));
    // enter end date
    /*
    echo elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:education:endyear'),
	    'required' => true,
	    'name' => 'end-year',
	    'value' => $education->endyear,
	    'options_values' => $end_year_array,
	    'class' => 'gcconnex-education-end-year',
	));*/

    echo '<table><tr><td>'. elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:education:endyear'),
	    'required' => true,
	    'name' => 'end-year',
	    'value' => $education->endyear,
	    'options_values' => $end_year_array,
	    'class' => 'gcconnex-education-end-year',
	)) .'</td>';
    
    if ($education->ongoing == 'true') {
        $params['disabled'] = 'true';
    }
    
    unset($params);

    $params = array(
        'name' => 'ongoing',
        'class' => 'gcconnex-education-ongoing',
        'onclick' => 'toggleEndDate("education", this)',
    );
    if ($education->ongoing == 'true') {
        $params['checked'] = $education->ongoing;
    }

    echo  '<td><label>' . elgg_view('input/checkbox', $params). ' ' .elgg_echo('gcconnex_profile:education:ongoing'). '</label></td></tr></table>';
    //echo elgg_echo('gcconnex_profile:education:ongoing') . '</label>';
    /*   
    echo elgg_echo('gcconnex_profile:education:year') . elgg_view("input/text", array(
            'name' => 'start-year',
            'class' => 'gcconnex-education-start-year',
            'maxlength' => 4,
            'onkeypress' => "return isNumberKey(event)",
            'value' => $education->startyear));*/
            
    // enter university web site url
    echo elgg_view_field(array(
            '#type' => 'url',
	    '#label' => elgg_echo('gcconnex_profile:education:url'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:url:help'),
	    'name' => 'educationurl',
	    'value' => $education->educationurl,
	    'class' => 'gcconnex-education-educationurl',
	));
    // enter activity and association
    echo elgg_view_field(array(
            '#type' => 'plaintext',
	    '#label' => elgg_echo('gcconnex_profile:education:activity'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:education:activity:help'),
	    'name' => 'activity',
	    'value' => $education->activity,
	    'class' => 'gcconnex-education-activity',
	));
    // enter Training description
    echo elgg_view_field(array(
            '#type' => 'plaintext',
	    '#label' => elgg_echo('gcconnex_profile:education:trainingd'),
	    'name' => 'trainingd',
	    'value' => $education->trainingd,
	    'class' => 'gcconnex-education-trainingd',
	));
    
    // enter start date
   // echo '<br><span class="gcconnex-profile-field-title">' . elgg_echo('gcconnex_profile:education:start_month') . '</span>';

    /*echo elgg_view("input/pulldown", array(
            'name' => 'startdate',
            'class' => 'gcconnex-education-startdate',
            'options' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
            'value' => $education->startdate));
    
    echo elgg_echo('gcconnex_profile:education:year') . elgg_view("input/text", array(
            'name' => 'start-year',
            'class' => 'gcconnex-education-start-year',
            'maxlength' => 4,
            'onkeypress' => "return isNumberKey(event)",
            'value' => $education->startyear));
    */
    /*
    $params = array(
        'name' => 'enddate',
        'class' => 'gcconnex-education-enddate gcconnex-education-enddate-' . $education->guid,
        'options' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
        'value' => $education->enddate);
    */

    //echo '<br><span class="gcconnex-profile-field-title">' . elgg_echo('gcconnex_profile:education:end_month') . '</span>';
    //echo elgg_view("input/pulldown", $params);

    //unset($params);

/*
    $params = array('name' => 'end-year',
        'class' => 'gcconnex-education-end-year gcconnex-education-end-year-' . $education->guid,
        'maxlength' => 4,
        'onkeypress' => "return isNumberKey(event)",
        'value' => $education->endyear);
    if ($education->ongoing == 'true') {
        $params['disabled'] = 'true';
    }
*/
    //echo elgg_echo('gcconnex_profile:education:year') .  elgg_view("input/text", $params);


// enter degree
/*
echo '<br><span class="gcconnex-profile-field-title">' . elgg_echo('gcconnex_profile:education:degree') . '</span>';
echo elgg_view("input/text", array(
    'name' => 'degree',
    'class' => 'gcconnex-education-degree',
    'value' => $education->degree));
*/

/*
    // enter program
    echo '<br><span class="gcconnex-profile-field-title">' . elgg_echo('gcconnex_profile:education:program') . '</span>';
    echo elgg_view("input/text", array(
            'name' => 'program',
            'class' => 'gcconnex-education-program',
            'value' => $education->program));
*/

    // enter field  of study
    /*
    echo '<br><span class="gcconnex-profile-field-title">' . elgg_echo('gcconnex_profile:education:field') . '</span>';
    echo elgg_view("input/text", array(
            'name' => 'fieldofstudy',
            'class' => 'gcconnex-education-field',
            'value' => $education->field));*/

    // create a delete button for each education entry
    echo '<br><div class="elgg-button elgg-button-action btn" onclick="deleteEntry(this)" data-type="education">' . elgg_echo('gcconnex_profile:education:delete') . '</div>';

echo '</div>'; // close div class="gcconnex-education-entry"
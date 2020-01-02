<?php
/*
 * Author: Bryden Arndt
 * Date: 15/12/14
 * Time: 1:33 PM
 * Purpose: This is a collection of input fields that are grouped together to create an entry for certification (designed to be entered for a user's profile).
 */


$certification = get_entity($vars['guid']); // get the guid of the certification entry that is being requested for display

$guid = ($certification != NULL)? $vars['guid'] : "new"; // if the certification guid isn't given, this must be a new entry

$start_year_array = array();
$end_year_array = array();
$annee = date("Y");
    
for($i=1958; $i<=$annee; $i++){
    $start_year_array[$i] = $i;
};
    
for($i=1958; $i<=$annee + 7; $i++){
    $end_year_array[$i] = $i;
};

echo '<div class="gcconnex-certification-entry well" data-guid="' . $guid . '">'; // certification entry wrapper for css styling
    
    // enter certification name
    echo elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:certification:name'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:certification:name:help'),
	    'required' => true,
	    'name' => 'certification',
	    'value' => $certification->name,
	    'class' => 'gcconnex-certification-name',
	));
    
    // enter certification authority
    echo elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:certification:authority'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:certification:authority:help'),
	    'required' => true,
	    'name' => 'authority',
	    'value' => $certification->authority,
	    'class' => 'gcconnex-certification-authority',
	));
    
    // enter certification licence number
    echo elgg_view_field(array(
            '#type' => 'text',
	    '#label' => elgg_echo('gcconnex_profile:certification:licence'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:certification:licence:help'),
	    'required' => true,
	    'name' => 'licence',
	    'value' => $certification->licence,
	    'class' => 'gcconnex-certification-licence',
	));
	
    // enter start month
    echo '<table><tr><td>'. elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:certification:startmonth'),
	    'required' => true,
	    'name' => 'startmonth',
	    'value' => $certification->startmonth,
	    'options_values' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
	    'class' => 'gcconnex-certification-startmonth',
	)) .'</td>';
    
    // enter start year
    echo '<td>'. elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:certification:startyear'),
	    'required' => true,
	    'name' => 'startyear',
	    'value' => $certification->startyear,
	    'options_values' => $start_year_array,
	    'class' => 'gcconnex-certification-startyear',
	)) .'</td><td></td>';
    
    // enter end month
    echo '</tr><tr><td>'. elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:certification:endmonth'),
	    'required' => true,
	    'name' => 'endmonth',
	    'value' => $certification->endmonth,
	    'options_values' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
	    'class' => 'gcconnex-certification-endmonth',
	)) .'</td>';
    
    // enter end year
    echo '<td>'. elgg_view_field(array(
            '#type' => 'select',
	    '#label' => elgg_echo('gcconnex_profile:certification:endyear'),
	    'required' => true,
	    'name' => 'endyear',
	    'value' => $certification->endyear,
	    'options_values' => $end_year_array,
	    'class' => 'gcconnex-certification-endyearr',
	)) .'</td>';
    
    // never expired
    echo '<td>'. elgg_view_field(array(
            '#type' => 'checkbox',
	    '#label' => elgg_echo('gcconnex_profile:certification:certexpired'),
	    'name' => 'certexpired',
	    'value' => $certification->certexpired,
	    'class' => 'gcconnex-certification-end-year',
	)) .'</td></tr></table>';
    
    // enter certification url
    echo elgg_view_field(array(
            '#type' => 'url',
	    '#label' => elgg_echo('gcconnex_profile:certification:certurl'),
	    '#help' => elgg_view_icon('help') . elgg_echo('gcconnex_profile:certification:certurl:help'),
	    'required' => true,
	    'name' => 'certurl',
	    'value' => $certification->certurl,
	    'class' => 'gcconnex-certification-certurl',
	));
	

    // create a delete button for each certification entry
    echo '<br><div class="elgg-button elgg-button-action btn" onclick="deleteEntry(this)" data-type="education">' . elgg_echo('gcconnex_profile:education:delete') . '</div>';

echo '</div>'; // close div class="gcconnex-education-entry"
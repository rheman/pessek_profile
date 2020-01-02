<?php

function init_ajax_block($title, $section, $user) { 

    $user_guid = (string) $user->guid; 

    echo '<div class="gcconnex-profile-section-frame-wrapper">';
    echo '<div class="gcconnex-profile-section-wrapper gcconnex-' . $section . '">'; 
    echo '<div class="gcconnex-profile-title">' . $title . '</div>'; 

    if ($user->canEdit()) {
        echo '<span class="gcconnex-profile-edit-controls">';


	echo elgg_view('output/url', [
		'text' => ' ', //'text' => elgg_echo('add'),
		'href' => 'ajax/view/forms/pessek_profile/'.$section.'?guidp='.$user_guid,
		'class' => 'btn btn-primary btn-xs glyphicon glyphicon-plus elgg-lightbox', //'class' => 'elgg-button elgg-button-action man pvn float-alt elgg-lightbox',
		'data-colorbox-opts' => json_encode(['maxHeight' => '85%', 'maxWidth' => '100%']),
		//'rel' => 'popup',
	]);

        echo '</span>';

    }
}

function finit_ajax_block($section) {
    echo '</div>';

    echo '<br><span class="gcconnex-profile-edit-controls">';

    echo elgg_view('input/submit', [
	   'id' => 'elgg-button-submit', 
	   'class' => 'elgg-button elgg-button-submit save-'. $section .'',//'class' => 'btn btn-success btn-sm save-'. $section .'',
	   'style' => 'display:none',
	   'name' => null, 
	   'value' => 'Click Me!',
    ]);
    
    echo '</span>';
    echo '</div>';
}

function has_content($user, $section) {
    if ( $user->$section != null ) {
        return true;
    }
    else {
        if ( $user->canEdit() ) {
            return true;
        }
        else {
            return false;
        }
    }
}

function list_avatars($options, $optional = 'link') {
    
    $gcconnex_avatar_right_left = 'gcconnex-avatar-in-list';
    
    $list = "";
    $list .= '<div class="list-avatars' . $options['class'] . '">';

    if ( $options['limit'] == 0 ) {
        $options['limit'] = 999;
    }
    else {
        
        
        if($optional != 'link'){
            
            $gcconnex_avatar_right_left = 'gcconnex-avatar-in-list-right';
            
            $user_guid = urlencode(serialize($optional));
            
            $list .= elgg_view('output/url', [
                'text' => '...', //'text' => elgg_echo('add'),
                'href' => 'ajax/view/forms/pessek_profile/list_avatars?user_guid='.$user_guid,
                'class' => 'float-alt btn gcconnex-avatars-expand elgg-lightbox',//btn btn-primary btn-xs glyphicon glyphicon-plus elgg-lightbox
                'data-colorbox-opts' => json_encode(['maxHeight' => '85%', 'maxWidth' => '100%']),
	     ]);
		

        }else{
        
            $list .= '<a class="btn gcconnex-avatars-expand" data-toggle="modal" href="#myModal" >...</a>';
        
        }
        /*
        $link = elgg_view('output/url', array(
            'href' => 'ajax/view/pessek_profile/edit_basic',
            'class' => 'elgg-lightbox gcconnex-basic-profile-edit elgg-button',
            'text' => elgg_echo('gcconnex_profile:edit_profile')
        ));
        */
    }


    if ( $options['use_hover'] === null ) {
        $options['use_hover'] = true;
    }

    if ( $options['guids'] == null ) {
        return false;
    }
    else {
        if (!is_array($options['guids'])) {
            $options['guids'] = array($options['guids']);
        }

        $guids = $options['guids'];

        // display each avatar, up until the limit is reached
        for ( $i=0; $i<$options['limit']; $i++) {
            if( ($user = get_user($guids[$i])) == true ) {
                if ( $options['edit_mode'] == true ) {
                    $list .= '<div class="' . $gcconnex_avatar_right_left . '" data-guid-userfind="' . $guids[$i] . '" data-guid="' . $guids[$i] . '" onclick="removeCoauthor(this)">';
                    $list .= '<div class="remove-colleague-from-list">X';
                    $list .= '</div>'; // close div class="remove-colleague-from-list"

                    $list .= elgg_view_entity_icon($user, $options['size'], array(
                        'use_hover' => $options['use_hover'],
                        'href' => false
                    ));
                    $list .= '</div>'; // close div class="gcconnex-avatar-in-list"
                }
                else {
                    $list .= '<div class="' . $gcconnex_avatar_right_left . '" data-guid="' . $guids[$i] . '">';
                    $list .= elgg_view_entity_icon($user, $options['size'], array(
                        'use_hover' => $options['use_hover'],
                    ));
                    $list .= '</div>'; // close div class="gcconnex-avatar-in-list"
                }
            }
            else {
                break;
            }
        }
    }

    $list .= '</div>'; // close div class="list-avatars"
    return $list;
}

function cmpStartDate($foo, $bar)
{
    $a = get_entity($foo);
    $b = get_entity($bar);

    if ($a->startyear == $b->startyear) {
        return (0);
    }
    else if ($a->startyear > $b->startyear) {
        return (-1);
    }
    else if ($a->startyear < $b->startyear) {
        return (1);
    }
}

function sortDate($foo, $bar)
{

    $a = get_entity($foo);
    $b = get_entity($bar);

    if ($a->ongoing == "true" && $b->ongoing == "true") {
        return (0);
    }
    else if ($a->ongoing == "true" && $b->ongoing != "true") {
        return (-1);
    }
    else if ($a->ongoing != "true" && $b->ongoing == "true") {
        return (1);
    }
    else {
        if ($a->endyear == $b->endyear) {
            // @todo: sort by enddate entry (months, saved as strings..)
            return (cmpStartDate($a, $b));
        }
        else if ($a->endyear > $b->endyear) {
            return (-1);
        }
        else if ($a->endyear < $b->endyear) {
            return (1);
        }
    }
}

function sortDatePortfolio($foo, $bar)
{

    $a = get_entity($foo);
    $b = get_entity($bar);
    
    $c = strtotime($a->startyear);
    $d = strtotime($b->startyear);
    
    if ($c == $d) {
        return 0;
    }
    return ($c < $d) ? -1 : 1;
}


function sortSkills($foo, $bar)
{

    $a = get_entity($foo);
    $b = get_entity($bar);
    
    (isset($a->endorsements)) ? $c = $a->endorsements : $c='';
    (isset($b->endorsements)) ? $d = $b->endorsements : $d='';
    
    /*$c = $a->endorsements;
    $d = $b->endorsements;*/
    
    if (!(is_array($c))) { $c = array($c); }
    
    if (!(is_array($d))) { $d = array($d); }
    
    $x = count($c);
    
    $y = count($d);
    
    if ($x == $y) {
        return 0;
    }
    return ($x < $y) ? 1 : -1;
}


function experience_year_month_day ($startyear, $endyear, $startmonth, $endmonth, $ongoing, $yeaar_lib, $month_lib, $yeaar_lib1, $month_lib1){ 
    
    if($ongoing == 'true'){
        
        $date2 = date("Y-m-d");
        $date2 = date_create($date2);
            
    }else{
        
        $endmonth = (int)$endmonth + 1;
        $end_date = $endyear .'-'. $endmonth .'-30';
        
        $date2 = date_create($end_date);
    }    
    
    
    $startmonth = (int)$startmonth + 1;
    $start_date = $startyear .'-'. $startmonth .'-01';
    
    $date1 = date_create($start_date);
    $diff = date_diff($date1, $date2);
    
    $convert = $diff->format("%a");

    $years = ($convert / 365) ; 
    $years = floor($years); 
    
    $month = ($convert % 365) / 30.5; 
    $month = floor($month); 
    
    //$days = ($convert % 365) % 30.5; 
    
    if($years == 0){
       
        $yeaar_lib = '';
        $years = '';
        
    }
    
    if($month == 0){
        
        $month_lib = '';
        $month = '';
        
    }
    
    if($years > 1){
       
        $yeaar_lib = $yeaar_lib1;
        
    }
    
    if($month > 1){
        
        $month_lib = $month_lib1;
        
    }
    
    /*
    $date1 = date_create("2013-03-15");
    $date2 = date_create("2013-12-12");
    $diff = date_diff($date1,$date2);
    */
    
    return '<i>' .$years.' ' . $yeaar_lib  .' '. $month .' ' . $month_lib . '</i>' ;
    
}

function get_user_friends($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10, $offset = 0)
{

      return $items = elgg_get_entities([
		'types' => 'user',
		'limit' => $limit,
		'subtype' => $subtype,
		'offset' => $offset,
		'relationship_guid' => $user_guid,
		'relationship' => 'friend',
      ]);
}


		
		
		

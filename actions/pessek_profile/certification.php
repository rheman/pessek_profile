<?php

elgg_ajax_gatekeeper();
/*
system_message(elgg_echo('gcconnex_profile:portfolio:update:succes'));
$arg1 = (string)get_input('guid');  register_error($arg1);
$arg2 = (string)get_input('guide');  register_error($arg2);

// will be rendered client-side
system_message('We did it!');
*/

    $on = 'on';
    $zero = '0';
    $user_guid = (int) get_input('guid'); //register_error($user_guid);
    $guide = (int) get_input('guide'); //register_error($guide);
    $guidE = (string) get_input('guide');
    $user = get_user($user_guid);
    $accesslevel_certification = (int) get_input('accesslevel_certification');  //register_error($accesslevel_certification);
    
    $certification_name = get_input('certification');           //register_error($certification_name);
    $certification_authority = get_input('authority');            //register_error($certification_authority);
    $certification_licence = get_input('licence');                         //register_error($certification_licence);
    
    $certification_startmonth = get_input('startmonth');              //register_error($certification_startmonth);
    $certification_startyear = get_input('startyear');  //register_error($certification_startyear);
    $certification_endmonth = get_input('endmonth');        //register_error($certification_endmonth);
    $certification_endyear = get_input('endyear');        //register_error($certification_endyear);
    
    $ongoing = get_input('ongoing');                //register_error($ongoing);
    if(strcmp ($ongoing , $on)==0){$ongoing = 'true';} //register_error($ongoing);
    
    $certification_certurl = get_input('certurl');        //register_error($certification_certurl);
        
    $sharechanges = get_input('sharechanges'); //register_error($sharechanges); //on --good et 0 non
    
    if(strcmp($certification_startyear, $certification_endyear) > 0 && $ongoing != 'true') {
            
                $certification_startyear = get_input('endyear');
                $certification_endyear = get_input('startyear');
                
                $certification_startmonth = get_input('endmonth');
                $certification_endmonth = get_input('startmonth');
                
    }
    
    if(strcmp($certification_startyear, $certification_endyear) == 0 && $ongoing != 'true' && strcmp($certification_startmonth, $certification_endmonth) > 0) {
                
                $certification_startmonth = get_input('endmonth');
                $certification_endmonth = get_input('startmonth');
                
    }
    
    $certification_guids = array();
    
        if(strcmp($guide , $zero) > 0){

        $certification = get_entity($guide);
        $certification->subtype = "certification";
        $certification->owner_guid = $user_guid;
        
        $certification->title = htmlentities($certification_name);
        $certification->description = htmlentities($certification_name);
                    
        $certification->name = htmlentities($certification_name);
        $certification->authority = htmlentities($certification_authority);
        $certification->licence = htmlentities($certification_licence);
            
        $certification->startmonth = htmlentities($certification_startmonth);       
        $certification->startyear = htmlentities($certification_startyear);  
        $certification->endmonth = htmlentities($certification_endmonth);   
        $certification->endyear = htmlentities($certification_endyear);     
            
        $certification->ongoing = $ongoing;
        $certification->certurl = $certification_certurl;
            
        $certification->access_id = $accesslevel_certification; 
        
	//-- notification when sharing
	if(strcmp ($sharechanges , $on)==0){
        	$certification->sharingNotify = "1";        
        }else{
        	$certification->sharingNotify = "0"; 
	}
    
        $user->certification_access = $accesslevel_certification;
        $user->save();
        $certification->save();
        
        system_message(elgg_echo('gcconnex_profile:certification:update:succes'));
        
    }else{
    
        if(!empty($user_guid)){
        
            $certification = new ElggObject();
            $certification->subtype = "certification";
            $certification->owner_guid = $user_guid;
            
            $certification->title = htmlentities($certification_name);
            $certification->description = htmlentities($certification_name);
        
            $certification->name = htmlentities($certification_name);
            $certification->authority = htmlentities($certification_authority);
            $certification->licence = htmlentities($certification_licence);
            
            $certification->startmonth = htmlentities($certification_startmonth);      
            $certification->startyear = htmlentities($certification_startyear);   
            $certification->endmonth = htmlentities($certification_endmonth);  
            $certification->endyear = htmlentities($certification_endyear);     
            
            $certification->ongoing = $ongoing;
            $certification->certurl = $certification_certurl;
            
            
            $certification->access_id = $accesslevel_certification; 
            
	    //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
            	$certification->sharingNotify = "1";        
            }else{
            	$certification->sharingNotify = "0"; 
	    }

            $certification_guids[] = $certification->save();
            
            if ($user->certification == NULL) {
                $user->certification = $certification_guids;
            }
            else {
                $stack = $user->certification;
                    if (!(is_array($stack))) { $stack = array($stack); }

                    if ($certification_guids != NULL) {
                        $user->certification = array_merge($stack, $certification_guids);
                    }

                }
            
            $user->certification_access = $accesslevel_certification;
            $user->save();
            
            system_message(elgg_echo('gcconnex_profile:certification:save:succes'));

        }
    }
    
//forward(REFERER);

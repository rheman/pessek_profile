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
    $user_guid = (int) get_input('guid');   //register_error($user_guid);
    $guide = (int) get_input('guide');      //register_error($guide);
    $guidE = (string) get_input('guide');
    $user = get_user($user_guid);
    
    $accesslevel_mooc = (int) get_input('accesslevel_mooc');  //register_error($accesslevel_mooc);
    
    $mooc_name = get_input('mooc');            //register_error($mooc_name);
    $mooc_etcs = get_input('etcs');            //register_error($mooc_etcs);
    $mooc_hours = get_input('hours');                         //register_error($mooc_hours);
    
    $mooc_startmonth = get_input('startmonth');              //register_error($mooc_startmonth);
    $mooc_startyear = get_input('startyear');  //register_error($mooc_startyear);
    $mooc_endmonth = get_input('endmonth');        //register_error($mooc_endmonth);
    $mooc_endyear = get_input('endyear');        //register_error($mooc_endyear);
    
    $ongoing = get_input('ongoing');                //register_error($ongoing);
    if(strcmp ($ongoing , $on)==0){$ongoing = 'true';} //register_error($ongoing);
    
    $mooc_competences = get_input('competences');        //register_error($mooc_competences);
    
    $mooc_moocurl = get_input('moocurl');        //register_error($mooc_moocurl);
    $mooc_certurl = get_input('certurl');        //register_error($mooc_certurl);
        
    $sharechanges = get_input('sharechanges'); //register_error($sharechanges); //on --good et 0 non
    
    if(strcmp($mooc_startyear, $mooc_endyear) > 0 && $ongoing != 'true') {
            
                $mooc_startyear = get_input('endyear');
                $mooc_endyear = get_input('startyear');
                
                $mooc_startmonth = get_input('endmonth');
                $mooc_endmonth = get_input('startmonth');
                
    }
    
    if(strcmp($mooc_startyear, $mooc_endyear) == 0 && $ongoing != 'true' && strcmp($mooc_startmonth, $mooc_endmonth) > 0) {
                
                $mooc_startmonth = get_input('endmonth');
                $mooc_endmonth = get_input('startmonth');
                
    }
    
        $mooc_guids = array();
    
        if(strcmp($guide , $zero) > 0){

            $mooc = get_entity($guide);
            $mooc->subtype = "mooc";
            $mooc->owner_guid = $user_guid;
            
            $mooc->title = htmlentities($mooc_name);
            $mooc->description = htmlentities($mooc_competences);
            
            $mooc->name = htmlentities($mooc_name);
            $mooc->etcs = htmlentities($mooc_etcs);
            $mooc->hours = htmlentities($mooc_hours);
                
            $mooc->startmonth = htmlentities($mooc_startmonth);       
            $mooc->startyear = htmlentities($mooc_startyear);  
            $mooc->endmonth = htmlentities($mooc_endmonth);   
            $mooc->endyear = htmlentities($mooc_endyear);     
                
            $mooc->ongoing = $ongoing;
            
            $mooc->competences = htmlentities($mooc_competences);  
            
            $mooc->moocurl = $mooc_moocurl;
            $mooc->certurl = $mooc_certurl;
            
                
            $mooc->access_id = $accesslevel_mooc; 
            	    
            //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$mooc->sharingNotify = "1";        
            }else{
            	$mooc->sharingNotify = "0"; 
	    }

            $user->mooc_access = $accesslevel_mooc;
            $user->save();
            $mooc->save();
           
            system_message(elgg_echo('gcconnex_profile:mocc:update:succes'));
                   
    }else{
    
        if(!empty($user_guid)){

            $mooc = new ElggObject();
            $mooc->subtype = "mooc";
            $mooc->owner_guid = $user_guid;
            
            $mooc->title = htmlentities($mooc_name);
            $mooc->description = htmlentities($mooc_competences);
            
            $mooc->name = htmlentities($mooc_name);
            $mooc->etcs = htmlentities($mooc_etcs);
            $mooc->hours = htmlentities($mooc_hours);
                
            $mooc->startmonth = htmlentities($mooc_startmonth);       
            $mooc->startyear = htmlentities($mooc_startyear);  
            $mooc->endmonth = htmlentities($mooc_endmonth);   
            $mooc->endyear = htmlentities($mooc_endyear);     
                
            $mooc->ongoing = $ongoing;
            
            $mooc->competences = htmlentities($mooc_competences);  
            
            $mooc->moocurl = $mooc_moocurl;
            $mooc->certurl = $mooc_certurl;
            
                
            $mooc->access_id = $accesslevel_mooc; 
                        
            //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$mooc->sharingNotify = "1";        
            }else{
            	$mooc->sharingNotify = "0"; 
	    }

            $mooc_guids[] = $mooc->save();
            
            if ($user->mooc == NULL) {
                $user->mooc = $mooc_guids;
            }
            else {
                $stack = $user->mooc;
                    if (!(is_array($stack))) { $stack = array($stack); }

                    if ($mooc_guids != NULL) {
                        $user->mooc = array_merge($stack, $mooc_guids);
                    }

                }
            
            $user->mooc_access = $accesslevel_mooc;
            $user->save();

            system_message(elgg_echo('gcconnex_profile:mooc:save:succes'));
        }
    }
    
//forward(REFERER);

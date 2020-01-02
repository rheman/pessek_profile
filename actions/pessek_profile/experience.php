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
    $internship = '0';
    $volunteer = '1';
    $real_experience = '2';
    $user_guid = (int) get_input('guid');   //register_error($user_guid);
    $guide = (int) get_input('guide');      //register_error($guide);
    $guidE = (string) get_input('guide');   //register_error($guidE);
    $user = get_user($user_guid);
    
    
    $accesslevel_experience = (int) get_input('accesslevel_experience');  //register_error($accesslevel_experience);
    
    $jobtitle = get_input('jobtitle');                          //register_error($jobtitle);
    $companyname = get_input('companyname');                    //register_error($companyname);
    $experience_type = (string)get_input('experience_type');                      //register_error($experience_type);
    $thecountry = get_input('thecountry');                        //register_error($thecountry);
    $place = get_input('place');                        //register_error($place);
    
    $startmonth = get_input('startmonth');                        //register_error($startmonth);
    $endmonth = get_input('endmonth');                          //register_error($endmonth);
    $startyear = get_input('startyear');                        //register_error($startyear);
    $endyear = get_input('endyear');                            //register_error($endyear);
    
    $ongoing = get_input('ongoing');                            //register_error($ongoing);
    if(strcmp ($ongoing , $on)==0){$ongoing = 'true';}          //register_error($ongoing);
    
    
    $companyurl = get_input('companyurl');                                //register_error($companyurl);
    $jobdescription = get_input('jobdescription');              //register_error($jobdescription);
        
    $sharechanges = get_input('sharechanges');                  //register_error($sharechanges); //on --good et 0 non
    
    if(strcmp($startyear, $endyear) > 0 && $ongoing != 'true') {
            
        $startyear = get_input('endyear');
        $endyear = get_input('startyear');
                
        $startmonth = get_input('endmonth');
        $endmonth = get_input('startmonth');
                
    }
    
    if(strcmp($startyear, $endyear) == 0 && $ongoing != 'true' && strcmp($startmonth, $endmonth) > 0) {
                
        $startmonth = get_input('endmonth');
        $endmonth = get_input('startmonth');
                
    }
    
    
    if(strcmp($experience_type , $internship) == 0){
    
        $internship_guids = array();
        
        if(strcmp($guide , $zero) > 0){
            
            $internships = get_entity($guide);
            $internships->subtype = "internships";
            $internships->owner_guid = $user_guid;
            
            $internships->title = htmlentities($jobtitle);
            $internships->description = htmlentities($jobdescription);
                        
            $internships->jobtitle = htmlentities($jobtitle);
            $internships->companyname = htmlentities($companyname);
            $internships->experience_type = htmlentities($experience_type);
            $internships->place = htmlentities($place);
            $internships->startmonth = htmlentities($startmonth);       
            $internships->startyear = htmlentities($startyear);  
            $internships->endyear = htmlentities($endyear);
            $internships->endmonth = htmlentities($endmonth); 
            $internships->ongoing = $ongoing;
            $internships->companyurl = htmlentities($companyurl); 
            $internships->jobdescription = htmlentities($jobdescription);  
            $internships->access_id = $accesslevel_experience; 

            //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$internships->sharingNotify = "1";        
            }else{
            	$internships->sharingNotify = "0"; 
	    }
            
            if(!empty($thecountry)){
                        
                $country_settings = array();
                $country_settings = explode("@", $thecountry); 
                $internships->country = $country_settings[1];
                $internships->flag = $country_settings[0];
                
            }
            
            $user->internships_access = $accesslevel_experience;
            $user->save();
            
            $internships->save();
            
            system_message(elgg_echo('gcconnex_profile:internship:update:succes'));

        }else{
            if(!empty($user_guid)){
                
                    $internships = new ElggObject();
                    $internships->subtype = "internships";
                    $internships->owner_guid = $user_guid;
                    
                    $internships->title = htmlentities($jobtitle);
                    $internships->description = htmlentities($jobdescription);
            
                    $internships->jobtitle = htmlentities($jobtitle);
                    $internships->companyname = htmlentities($companyname);
                    $internships->experience_type = htmlentities($experience_type);
                    $internships->place = htmlentities($place);
                        
                    $internships->startmonth = htmlentities($startmonth);       
                    $internships->startyear = htmlentities($startyear);  
                    $internships->endyear = htmlentities($endyear);
                    $internships->endmonth = htmlentities($endmonth); 
                    
                    $internships->ongoing = $ongoing;
                    
                    $internships->companyurl = htmlentities($companyurl); 
                    
                    $internships->jobdescription = htmlentities($jobdescription);  
                        
                    $internships->access_id = $accesslevel_experience; 
                    

            	    //-- notification when sharing
	            if(strcmp ($sharechanges , $on)==0){
           	   	$internships->sharingNotify = "1";        
                    }else{
            	   	$internships->sharingNotify = "0"; 
	            }

                    if(!empty($thecountry)){
                        
                        $country_settings = array();
                        
                        $country_settings = explode("@", $thecountry); 
                        
                        $internships->country = $country_settings[1];
                        
                        $internships->flag = $country_settings[0];
                    }
                    
                    $internship_guids[] = $internships->save();
                    
                    if ($user->internships == NULL) {
                        $user->internships = $internship_guids;
                    }
                    else {
                        $stack = $user->internships;
                            if (!(is_array($stack))) { $stack = array($stack); }

                            if ($internship_guids != NULL) {
                                $user->internships = array_merge($stack, $internship_guids);
                            }

                        }
                    
                    $user->internships_access = $accesslevel_experience;
                    $user->save();

                    system_message(elgg_echo('gcconnex_profile:experience:save:succes'));
                }
            
        }
    
    }elseif(strcmp($experience_type , $volunteer) == 0){
    
        $volunteers_guids = array();
        
        if(strcmp($guide , $zero) > 0){
        
                    $volunteers = get_entity($guide);
                    $volunteers->subtype = "volunteers";
                    $volunteers->owner_guid = $user_guid;
                    
                    $volunteers->title = htmlentities($jobtitle);
                    $volunteers->description = htmlentities($jobdescription);
                    
                    $volunteers->jobtitle = htmlentities($jobtitle);
                    $volunteers->companyname = htmlentities($companyname);
                    $volunteers->experience_type = htmlentities($experience_type);
                    $volunteers->place = htmlentities($place);
                    $volunteers->startmonth = htmlentities($startmonth);       
                    $volunteers->startyear = htmlentities($startyear);  
                    $volunteers->endyear = htmlentities($endyear);
                    $volunteers->endmonth = htmlentities($endmonth); 
                    $volunteers->ongoing = $ongoing;
                    $volunteers->companyurl = htmlentities($companyurl); 
                    $volunteers->jobdescription = htmlentities($jobdescription);  
                    $volunteers->access_id = $accesslevel_experience; 
                    

            	    //-- notification when sharing
	            if(strcmp ($sharechanges , $on)==0){
           	   	$volunteers->sharingNotify = "1";        
                    }else{
            	   	$volunteers->sharingNotify = "0"; 
	            }

                    if(!empty($thecountry)){
                                
                        $country_settings = array();
                        $country_settings = explode("@", $thecountry); 
                        $volunteers->country = $country_settings[1];
                        $volunteers->flag = $country_settings[0];
                        
                    }
                    
                    $user->volunteers_access = $accesslevel_experience;
                    $user->save();
                    
                    $volunteers->save();
                    
                    system_message(elgg_echo('gcconnex_profile:volunteer:update:succes'));
        
        }else{
            if(!empty($user_guid)){
                
                    $volunteers = new ElggObject();
                    $volunteers->subtype = "volunteers";
                    $volunteers->owner_guid = $user_guid;
                    
                    $volunteers->title = htmlentities($jobtitle);
                    $volunteers->description = htmlentities($jobdescription);
                    
                    $volunteers->jobtitle = htmlentities($jobtitle);
                    $volunteers->companyname = htmlentities($companyname);
                    $volunteers->experience_type = htmlentities($experience_type);
                    $volunteers->place = htmlentities($place);
                        
                    $volunteers->startmonth = htmlentities($startmonth);       
                    $volunteers->startyear = htmlentities($startyear);  
                    $volunteers->endyear = htmlentities($endyear);
                    $volunteers->endmonth = htmlentities($endmonth); 
                    
                    $volunteers->ongoing = $ongoing;
                    
                    $volunteers->companyurl = htmlentities($companyurl); 
                    
                    $volunteers->jobdescription = htmlentities($jobdescription);  
                        
                    $volunteers->access_id = $accesslevel_experience; 
                    
            	    //-- notification when sharing
	            if(strcmp ($sharechanges , $on)==0){
           	   	$volunteers->sharingNotify = "1";        
                    }else{
            	   	$volunteers->sharingNotify = "0"; 
	            }

                    if(!empty($thecountry)){
                        
                        $country_settings = array();
                        
                        $country_settings = explode("@", $thecountry); 
                        
                        $volunteers->country = $country_settings[1];
                        
                        $volunteers->flag = $country_settings[0];
                    }
                    
                    $volunteers_guids[] = $volunteers->save();
                    
                    if ($user->volunteers == NULL) {
                        $user->volunteers = $volunteers_guids;
                    }
                    else {
                        $stack = $user->volunteers;
                            if (!(is_array($stack))) { $stack = array($stack); }

                            if ($volunteers_guids != NULL) {
                                $user->volunteers = array_merge($stack, $volunteers_guids);
                            }

                        }
                    
                    $user->volunteers_access = $accesslevel_experience;
                    $user->save();

                    system_message(elgg_echo('gcconnex_profile:experience:save:succes'));

                }
            
        }
    
    }elseif(strcmp($experience_type , $real_experience) == 0){
    
        $experiences_guids = array();
        
        if(strcmp($guide , $zero) > 0){
        
                    $experiences = get_entity($guide);
                    $experiences->subtype = "experiences";
                    $experiences->owner_guid = $user_guid;
                    
                    $experiences->title = htmlentities($jobtitle);
                    $experiences->description = htmlentities($jobdescription);
                    
                    $experiences->jobtitle = htmlentities($jobtitle);
                    $experiences->companyname = htmlentities($companyname);
                    $experiences->experience_type = htmlentities($experience_type);
                    $experiences->place = htmlentities($place);
                    $experiences->startmonth = htmlentities($startmonth);       
                    $experiences->startyear = htmlentities($startyear);  
                    $experiences->endyear = htmlentities($endyear);
                    $experiences->endmonth = htmlentities($endmonth); 
                    $experiences->ongoing = $ongoing;
                    $experiences->companyurl = htmlentities($companyurl); 
                    $experiences->jobdescription = htmlentities($jobdescription);  
                    $experiences->access_id = $accesslevel_experience; 
                    
            	    //-- notification when sharing
	            if(strcmp ($sharechanges , $on)==0){
           	   	$experiences->sharingNotify = "1";        
                    }else{
            	   	$experiences->sharingNotify = "0"; 
	            }

                    if(!empty($thecountry)){
                                
                        $country_settings = array();
                        $country_settings = explode("@", $thecountry); 
                        $experiences->country = $country_settings[1];
                        $experiences->flag = $country_settings[0];
                        
                    }
                    
                    $user->experiences_access = $accesslevel_experience;
                    $user->save();
                    
                    $experiences->save();
                    
                    system_message(elgg_echo('gcconnex_profile:experience:update:succes'));
        
        }else{
            if(!empty($user_guid)){
                
                    $experiences = new ElggObject();
                    $experiences->subtype = "experiences";
                    $experiences->owner_guid = $user_guid;
                    
                    $experiences->title = htmlentities($jobtitle);
                    $experiences->description = htmlentities($jobdescription);
                    
                    $experiences->jobtitle = htmlentities($jobtitle);
                    $experiences->companyname = htmlentities($companyname);
                    $experiences->experience_type = htmlentities($experience_type);
                    $experiences->place = htmlentities($place);
                        
                    $experiences->startmonth = htmlentities($startmonth);       
                    $experiences->startyear = htmlentities($startyear);  
                    $experiences->endyear = htmlentities($endyear);
                    $experiences->endmonth = htmlentities($endmonth); 
                    
                    $experiences->ongoing = $ongoing;
                    
                    $experiences->companyurl = htmlentities($companyurl); 
                    
                    $experiences->jobdescription = htmlentities($jobdescription);  
                        
                    $experiences->access_id = $accesslevel_experience; 
                    
            	    //-- notification when sharing
	            if(strcmp ($sharechanges , $on)==0){
           	   	$experiences->sharingNotify = "1";        
                    }else{
            	   	$experiences->sharingNotify = "0"; 
	            }

                    if(!empty($thecountry)){
                        
                        $country_settings = array();
                        
                        $country_settings = explode("@", $thecountry); 
                        
                        $experiences->country = $country_settings[1];
                        
                        $experiences->flag = $country_settings[0];
                    }
                    
                    $experiences_guids[] = $experiences->save();
                    
                    if ($user->experiences == NULL) {
                        $user->experiences = $experiences_guids;
                    }
                    else {
                        $stack = $user->experiences;
                            if (!(is_array($stack))) { $stack = array($stack); }

                            if ($experiences_guids != NULL) {
                                $user->experiences = array_merge($stack, $experiences_guids);
                            }

                        }
                    
                    $user->experiences_access = $accesslevel_experience;
                    $user->save();

                    system_message(elgg_echo('gcconnex_profile:experience:save:succes'));
                }
            
        }
    }

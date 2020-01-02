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
    $guidE = (string) get_input('guide');   //register_error($guidE);
    $user = get_user($user_guid);
    
    $HidCoauthor = (string) get_input('HidCoauthor');           //register_error($HidCoauthor);
    $HidCoauthorVal = (string) get_input('HidCoauthorVal');     //register_error($HidCoauthorVal);
    
    $accesslevel_projects = (int) get_input('accesslevel_projects');  //register_error($accesslevel_projects);
    
    $thetitle = get_input('thetitle');                          //register_error($thetitle);
    
    $startmonth = get_input('startmonth');                      //register_error($startmonth);
    $startyear = get_input('startyear');                        //register_error($startyear);
    $endyear = get_input('endyear');                            //register_error($endyear);
    $endmonth = get_input('endmonth');                          //register_error($endmonth);
    
    $function = get_input('function');                          //register_error($function);
    
    $ongoing = get_input('ongoing');                            //register_error($ongoing);
    if(strcmp ($ongoing , $on)==0){$ongoing = 'true';}          //register_error($ongoing);
    
    
    $links = get_input('links');                                //register_error($links);
    $thedescription = get_input('thedescription');              //register_error($thedescription);
        
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
    
        $projects_guids = array();
    
        if(strcmp($guide , $zero) > 0){

            $projects = get_entity($guide);
            $projects->subtype = "projects";
            $projects->owner_guid = $user_guid;
            
            $projects->title = htmlentities($thetitle);
            $projects->description = htmlentities($thedescription);
                    
            $projects->thetitle = htmlentities($thetitle);
            $projects->startmonth = htmlentities($startmonth);       
            $projects->startyear = htmlentities($startyear);  
            $projects->endyear = htmlentities($endyear);
            $projects->endmonth = htmlentities($endmonth); 
            
            $projects->function = htmlentities($function); 
            
            $projects->ongoing = $ongoing;
            
            $projects->links = htmlentities($links);  
            
            $projects->thedescription = htmlentities($thedescription);  

                
            unset($projects->access_id);
            
            $projects->access_id = $accesslevel_projects; 
            
	    //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$projects->sharingNotify = "1";        
            }else{
            	$projects->sharingNotify = "0"; 
	    }

            if($projects->contributors == NULL){
                $contributors_tab = array();
            }else{
                $contributors_tab = $projects->contributors;
                if (!(is_array($contributors_tab))) {
                    $contributors_tab = array($contributors_tab);
                }
            }
            
            $contributors_size = count($contributors_tab);
            $test_contributors = 0;
            
            if(!empty($HidCoauthorVal)){
                
                $contributors_guids = array();
                
                $contributors_guid = explode(",", $HidCoauthorVal); 
                
                for( $i=0; $i < count($contributors_guid); $i++){
                
                    $guid_val = (string) $contributors_guid[$i];
                    
                    if(!empty($contributors_guid[$i])){   //if(!is_null($contributors_guid[$i])){
                        
                        $contributors_guids[$i] = $guid_val;
                        $test_contributors++;
                        
                        if (in_array($guid_val, $contributors_tab) == false){
                            
                            $contributors_tab[$contributors_size] = $guid_val; 
                            $contributors_size++;
                            notify_contributors($guid_val, $user_guid, $projects);
                        }
                    }
                }
                
                if($test_contributors!=0){
                    unset($projects->contributors);
                    $projects->contributors = $contributors_guids;
                }
                
            }
            
            unset($user->projects_access);
            
            $user->projects_access = $accesslevel_projects;
            $user->save();
            
            $projects->save();
            
            system_message(elgg_echo('gcconnex_profile:projects:update:succes'));
        
    }else{
    
        if(!empty($user_guid)){

            $projects = new ElggObject();
            $projects->subtype = "projects";
            $projects->owner_guid = $user_guid;
            
            $projects->title = htmlentities($thetitle);
            $projects->description = htmlentities($thedescription);
            
            $projects->thetitle = htmlentities($thetitle);
            $projects->startmonth = htmlentities($startmonth);       
            $projects->startyear = htmlentities($startyear);  
            $projects->endyear = htmlentities($endyear);
            $projects->endmonth = htmlentities($endmonth); 
            
            $projects->function = htmlentities($function); 
            
            $projects->ongoing = $ongoing;
            
            $projects->links = htmlentities($links);  
            
            $projects->thedescription = htmlentities($thedescription);  
                
            $projects->access_id = $accesslevel_projects; 
            
	    //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$projects->sharingNotify = "1";        
            }else{
            	$projects->sharingNotify = "0"; 
	    }

            if(!empty($HidCoauthorVal)){
                
                $contributors_guids = array();
                
                $test_contributors = 0;
                $contributors_guid = explode(",", $HidCoauthorVal); 
                
                for( $i=0; $i < count($contributors_guid); $i++){
                
                    $guid_val = (string) $contributors_guid[$i];
                    
                    if(!empty($contributors_guid[$i])){   //if(!is_null($contributors_guid[$i])){
                        $test_contributors++;
                        
                        $contributors_guids[$i] = $guid_val;
                        //notify contributor
                        notify_contributors($guid_val, $user_guid, $projects);
                    }
                }
                if($test_contributors!=0){
                    $projects->contributors = $contributors_guids;
                }
            }
            
            $projects_guids[] = $projects->save();
            
            if ($user->projects == NULL) {
                $user->projects = $projects_guids;
            }
            else {
                $stack = $user->projects;
                    if (!(is_array($stack))) { $stack = array($stack); }

                    if ($projects_guids != NULL) {
                        $user->projects = array_merge($stack, $projects_guids);
                    }

                }
            
            $user->projects_access = $accesslevel_projects;
            $user->save();

            system_message(elgg_echo('gcconnex_profile:projects:save:succes'));        
        }
    }

function notify_contributors($guid_val, $user_guid, $projects){

    $owner = get_user($guid_val);
    $user = get_user($user_guid);
    $owner_link = elgg_view('output/url', [
                        'text' => $owner->getDisplayName(),
                        'href' => $owner->getURL(),
                                ]);
    $user_link = elgg_view('output/url', [
                        'text' => $user->getDisplayName(),
                        'href' => $user->getURL(),
                                ]);
    $user_links = elgg_view('output/url', [
                        'text' => $user->getDisplayName()."'s",
                        'href' => $user->getURL(),
                                ]);
                                
    $subject = elgg_echo('gcconnex_profile:projects:co:contributors:add', [$user->name, $projects->thetitle], $owner->language);
    $body = elgg_echo('gcconnex_profile:projects:co:contributors:body', [$owner_link, $user_link, $projects->thetitle, $user->getURL(), $user_links, $user->getURL()], $owner->language);
    $params = array(
                    'object' => $projects,
                    'action' => 'update',
                    );
    notify_user($owner->getGUID(),  $user->getGUID(), $subject, $body, $params);
    
}
//forward(REFERER);

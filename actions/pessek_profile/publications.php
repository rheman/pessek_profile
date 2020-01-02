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
    
    $HidCoauthor = (string) get_input('HidCoauthor'); //register_error($HidCoauthor);
    $HidCoauthorVal = (string) get_input('HidCoauthorVal'); //register_error($HidCoauthorVal);
    
    $accesslevel_publications = (int) get_input('accesslevel_publications');  //register_error($accesslevel_publications);
    
    $thetitle = get_input('thetitle');            //register_error($thetitle);
    $editor = get_input('editor');            //register_error($editor);
    
    $startday = get_input('startday');              //register_error($startday);
    $startmonth = get_input('startmonth');              //register_error($startmonth);
    $startyear = get_input('startyear');  //register_error($startyear);
    
    $links = get_input('links');        //register_error($links);
    $thedescription = get_input('thedescription');        //register_error($thedescription);
        
    $sharechanges = get_input('sharechanges'); //register_error($sharechanges); //on --good et 0 non
    
        $publications_guids = array();
    
        if(strcmp($guide , $zero) > 0){

            $publications = get_entity($guide);
            $publications->subtype = "publications";
            $publications->owner_guid = $user_guid;
            
            $publications->title = htmlentities($thetitle);
            $publications->description = htmlentities($thedescription);
            
            $publications->thetitle = htmlentities($thetitle);
            $publications->editor = htmlentities($editor);
                
            $publications->startday = htmlentities($startday);       
            $publications->startmonth = htmlentities($startmonth);  
            $publications->startyear = htmlentities($startyear);   
            
            $publications->links = htmlentities($links);  
            $publications->thedescription = htmlentities($thedescription);  
                
            $publications->access_id = $accesslevel_publications; 
            
            //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$publications->sharingNotify = "1";        
            }else{
            	$publications->sharingNotify = "0"; 
	    }

            if($publications->coauthor == NULL){
                $publications_tab = array();
            }else{
                $publications_tab = $publications->coauthor;
                if (!(is_array($publications_tab))) {
                    $publications_tab = array($publications_tab);
                }
            }
            
            $publications_size = count($publications_tab);
            $test_publications = 0;
            
            if(!empty($HidCoauthorVal)){
                
                $coauthor_guids = array();
                
                $coauthor_guid = explode(",", $HidCoauthorVal); 
                
                for( $i=0; $i < count($coauthor_guid); $i++){
                
                    $guid_val = (string) $coauthor_guid[$i];
                    
                    if(!empty($coauthor_guid[$i])){   //if(!is_null($coauthor_guid[$i])){
                        $coauthor_guids[$i] = $guid_val;
                        $test_publications++;
                        
                        if (in_array($guid_val, $publications_tab) == false){
                            
                            $publications_tab[$publications_size] = $guid_val; 
                            $publications_size++;
                            notify_coauthors($guid_val, $user_guid, $publications);
                        }
                    }
                }
                
                if($test_publications!=0){
                    unset($publications->coauthor);
                    $publications->coauthor = $coauthor_guids;
                }
            }
            
            $user->publications_access = $accesslevel_publications;
            $user->save();
            $publications->save();
            
            system_message(elgg_echo('gcconnex_profile:publications:update:succes'));
        
    }else{
    
        if(!empty($user_guid)){

            $publications = new ElggObject();
            $publications->subtype = "publications";
            $publications->owner_guid = $user_guid;
            
            $publications->title = htmlentities($thetitle);
            $publications->description = htmlentities($thedescription);
            
            $publications->thetitle = htmlentities($thetitle);
            $publications->editor = htmlentities($editor);
                
            $publications->startday = htmlentities($startday);       
            $publications->startmonth = htmlentities($startmonth);  
            $publications->startyear = htmlentities($startyear);   
            
            $publications->links = htmlentities($links);  
            $publications->thedescription = htmlentities($thedescription);  
                
            $publications->access_id = $accesslevel_publications; 
              
            //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$publications->sharingNotify = "1";        
            }else{
            	$publications->sharingNotify = "0"; 
	    }

            if(!empty($HidCoauthorVal)){
                $test_coauthor = 0;
                $coauthor_guids = array();
                
                
                $coauthor_guid = explode(",", $HidCoauthorVal); 
                
                for( $i=0; $i < count($coauthor_guid); $i++){
                
                    $guid_val = (string) $coauthor_guid[$i];
                    
                    if(!empty($coauthor_guid[$i])){   //if(!is_null($coauthor_guid[$i])){
                        $coauthor_guids[$i] = $guid_val;
                        $test_coauthor++;
                        notify_coauthors($guid_val, $user_guid, $publications);
                    }
                }
                
                if($test_coauthor!=0){
                    $publications->coauthor = $coauthor_guids;
                }
                
            }
            
            $publications_guids[] = $publications->save();
            
            if ($user->publications == NULL) {
                $user->publications = $publications_guids;
            }
            else {
                $stack = $user->publications;
                    if (!(is_array($stack))) { $stack = array($stack); }

                    if ($publications_guids != NULL) {
                        $user->publications = array_merge($stack, $publications_guids);
                    }

                }
            
            $user->publications_access = $accesslevel_publications;
            $user->save();

            system_message(elgg_echo('gcconnex_profile:publications:save:succes'));
            
            if(strcmp ($sharechanges , $on)==0){
                    
                    $river_id = elgg_create_river_item(array(
                        'view' => 'river/object/publications/create',
                        'action_type' => 'create',
                        'subject_guid' => $publications->owner_guid,
                        'object_guid' => $publications->getGUID(),
                    ));
                    
                    //mail to friend
                
            }
        
        }
    }
    
 function notify_coauthors($guid_val, $user_guid, $publications){
    
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
                                
    $subject = elgg_echo('gcconnex_profile:publications:co:contributors:add', [$user->name, $publications->thetitle], $owner->language);
    $body = elgg_echo('gcconnex_profile:publications:co:contributors:body', [$owner_link, $user_link, $publications->thetitle, $user->getURL(), $user_links, $user->getURL()], $owner->language);
    $params = array(
                    'object' => $publications,
                    'action' => 'update',
            );
    notify_user($owner->getGUID(),  $user->getGUID(), $subject, $body, $params);
    
 }   
//forward(REFERER);

<?php

elgg_ajax_gatekeeper();
/*
system_message(elgg_echo('gcconnex_profile:portfolio:update:succes'));
$arg1 = (string)get_input('guid');  register_error($arg1);
$arg2 = (string)get_input('guide');  register_error($arg2);

// will be rendered client-side
system_message('We did it!');
*/
use pessekprofile\Languages;

    $on = 'on';
    $zero = '0';
    $user_guid = (int) get_input('guid');   //register_error($user_guid);
    $guide = (int) get_input('guide');      //register_error($guide);
    $guidE = (string) get_input('guide');
    $user = get_user($user_guid);
    
    $accesslevel_languages = (int) get_input('accesslevel_languages');  //register_error($accesslevel_languages);
    
    $languages_langs = get_input('langs');            //register_error($languages_langs);
    $languages_level = get_input('level');            //register_error($languages_level);
    
        
    $sharechanges = get_input('sharechanges'); //register_error($sharechanges); //on --good et 0 non
    
        $languages_guids = array();
        $languages_titles = array();
        $languages_guid = $user->languages;
        foreach ($languages_guid as $guid) {
            if ($entry = get_entity($guid)) {
                $languages_titles[] = $entry->langs;
            }
        }
        
        if(strcmp($guide , $zero) > 0){ system_message(elgg_echo('gcconnex_profile:langs:update:succes'));
            
            if(in_array($languages_langs, $languages_titles) == false){
            
                $languages = get_entity($guide);
                $languages->subtype = "languages";
                $languages->owner_guid = $user_guid;
                
                $languages->title = htmlentities($languages_langs);
                $languages->description = htmlentities($languages_langs);
                        
                $languages->langs = htmlentities($languages_langs);
                $languages->level = htmlentities($languages_level);
                
                    
                $languages->access_id = $accesslevel_languages; 

	        //-- notification when sharing
	        if(strcmp ($sharechanges , $on)==0){
           		$languages->sharingNotify = "1";        
                }else{
            		$languages->sharingNotify = "0"; 
	        }

                $user->languages_access = $accesslevel_languages;
                
                $user->save();
                $languages->save();
                
             }else{

                $languages = get_entity($guide);
                $languages->subtype = "languages";
                $languages->owner_guid = $user_guid;
                $languages->level = htmlentities($languages_level);
                
                    
                $languages->access_id = $accesslevel_languages; 

	        //-- notification when sharing
	        if(strcmp ($sharechanges , $on)==0){
           		$languages->sharingNotify = "1";        
                }else{
            		$languages->sharingNotify = "0"; 
	        }

                $user->languages_access = $accesslevel_languages;
                
                $user->save();
                $languages->save();
      	               
            }
        
    }else{
    
        if(!empty($user_guid)){
            
            if ($user->languages == NULL || in_array($languages_langs, $languages_titles) == false){

                //
		if (!elgg_set_entity_class('user', 'languages', \pessekprofile\Languages::class)) {
		   elgg_set_entity_class('user', 'languages', \pessekprofile\Languages::class); system_message(elgg_echo('gcconnex_profile:langs:save:succes'));
		}
                //
                
                $languages = new Languages();
                $languages->subtype = 'languages';
                
                /*$languages = new ElggObject();
                $languages->subtype = "languages";*/
                
                $languages->owner_guid = $user_guid;
                
                $languages->title = htmlentities($languages_langs);
                $languages->description = htmlentities($languages_langs);
                
                $languages->langs = htmlentities($languages_langs);
                $languages->level = htmlentities($languages_level);
                    
                $languages->access_id = $accesslevel_languages;

	        //-- notification when sharing
	        if(strcmp ($sharechanges , $on)==0){
           		$languages->sharingNotify = "1";        
                }else{
            		$languages->sharingNotify = "0"; 
	        } 

	        //-- notification when sharing
	        if(strcmp ($sharechanges , $on)==0){
           		$languages->sharingNotify = "1";        
                }else{
            		$languages->sharingNotify = "0"; 
	        }                

                $languages_guids[] = $languages->save();
                
                if ($user->languages == NULL) {
                    $user->languages = $languages_guids;
                }
                else {
                    $stack = $user->languages;
                        if (!(is_array($stack))) { $stack = array($stack); }

                        if ($languages_guids != NULL) {
                            $user->languages = array_merge($stack, $languages_guids);
                        }

                    }
                
                $user->languages_access = $accesslevel_languages;
                $user->save();

                system_message(elgg_echo('gcconnex_profile:langs:save:succes'));
                
            }
        
        }
    }
    
//forward(REFERER);

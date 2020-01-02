<?php

elgg_ajax_gatekeeper();

    $on = 'on';
    $zero = '0';
    $user_guid = (string) get_input('guid');   //register_error($user_guid);
    $guide = (int) get_input('guide');      //register_error($guide);
    $guidE = (string) get_input('guide');   //register_error($guidE);
    $user = get_user($user_guid);
    
    $descriptionname = htmlentities(get_input('descriptionname'));
    
    $accesslevel_description = (int) get_input('accesslevel_description');  //register_error($accesslevel_description);
    
    $sharechanges = get_input('sharechanges');                  //register_error($sharechanges); //on --good et 0 non
    
    
    $description_guids = array();
    
    if(!empty($user_guid)){
    
        if ($user->description == NULL) {
        
            $description = new ElggObject();
            $description->subtype = "description";
            $description->owner_guid = $user_guid;
            
            $description->title = elgg_echo('gcconnex_profile:about_me:summary');
            $description->description = htmlentities($descriptionname);
                    
            $description->descriptionname = $descriptionname;
            $description->access_id = $accesslevel_description;
            
	    //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$description->sharingNotify = "1";        
            }else{
            	$description->sharingNotify = "0"; 
	    }

            $description_guids[] = $description->save();
            $user->description = $description_guids;
            
            $user->description_access = $accesslevel_description;
            $user->save();
            
            system_message(elgg_echo('gcconnex_profile:about_me:save:succes'));
            
        }else {
        
            $description = get_entity($guide);
            $description->subtype = "description";
            $description->owner_guid = $user_guid;
            
            $description->title = elgg_echo('gcconnex_profile:about_me:summary');
            $description->description = htmlentities($descriptionname);
            
            $description->descriptionname = $descriptionname;
            $description->access_id = $accesslevel_description;

            //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$description->sharingNotify = "1";        
            }else{
            	$description->sharingNotify = "0"; 
	    }

            $user->description_access = $accesslevel_description;
            $user->save();
            $description->save();
            
            system_message(elgg_echo('gcconnex_profile:about_me:update:succes'));


	   


	    $newsletters = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'description',
				'limit' => false,
				'metadata_name_value_pairs' => [
					'name' => 'sharingNotify',
					'value' => '1',
				],
				'batch' => true,
			]);
	foreach ($newsletters as $newsletter) {
				system_message($newsletter->description);
			}
        
        }
        if(strcmp ($sharechanges , $on)==0){
                    
            $river_id = elgg_create_river_item(array(
                'view' => 'river/object/description/create',
                'action_type' => 'create',
                'subject_guid' => $user->guid,
                'object_guid' => $description->getGUID(),
            ));
        }
    }

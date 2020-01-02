<?php

elgg_ajax_gatekeeper();

    $on = 'on';
    $zero = '0';
    $user_guid = (string) get_input('guid');   //register_error($user_guid);
    $guide = (int) get_input('guide');      //register_error($guide);
    $guidE = (string) get_input('guide');   //register_error($guidE);
    $user = get_user($user_guid);
    
    $HidSkills = (string) get_input('HidSkills');           //register_error($HidSkills);
    $HidSkillsVal = (string) get_input('HidSkillsVal');     //register_error($HidSkillsVal);
    
    $HidSkills = htmlentities($HidSkills);
    $HidSkillsVal = htmlentities($HidSkillsVal);
    $accesslevel_skills = (int) get_input('accesslevel_skills');  //register_error($accesslevel_skills);
        
    $sharechanges = get_input('sharechanges');                  //register_error($sharechanges); //on --good et 0 non
    
    $skills_guids = array();
    
    if(!empty($HidSkillsVal)){
            
            $a = 0 ;
            $concat_title = '';
            
            $all_skills_guids = array();
                
            $skills_guid = explode(",", $HidSkillsVal);  
                
            for( $i=0; $i < count($skills_guid); $i++){
                
                $skills_cat_val = (string) $skills_guid[$i];
                    
                if(!empty($skills_guid[$i])){  
                        
                    $skills_val = explode("@", $skills_cat_val);
                    
                    $skills_chek = (string) $skills_val[0];
                    
                    if($skills_chek == '0'){
                    
                        $skills_id = (string) $skills_val[1];
                            
                        $skills_title = (string) $skills_val[2];
                            
                        SkillsSaver($user, $guide, $zero, $user_guid, $accesslevel_skills, $skills_guids, $skills_id, $skills_title);
                        
                        if($a == 0){
                            $concat_title = $skills_title;
                        }else{
                            $concat_title = $concat_title .', '. $skills_title;
                        }
                    
                    }
                    $a++;
                    
                }
            }
            
            if($a != 0){
            
                system_message(elgg_echo('gcconnex_profile:skills:save:succes'));
                
            }else{
            
                system_message(elgg_echo('gcconnex_profile:internal::error'));
                
            }
                
    }
  
  
function SkillsSaver($user, $guide, $zero, $user_guid, $accesslevel_skills, $skills_guids, $skills_id, $skills_title){

    if(strcmp($guide , $zero) > 0){
    
     }else{
     
     if(!empty($user_guid)){
            
            $skills = new ElggObject();
            $skills->subtype = "skills";
            
            $skills->container_guid = $user_guid;
            $skills->owner_guid = $user_guid;
            
            $skills->id = $skills_id;
            $skills->title = $skills_title;
            $skills->description = $skills_title;
            $skills->endorsements = NULL;
            
                
            $skills->access_id = $accesslevel_skills; 
	    
            //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
           	$skills->sharingNotify = "1";        
            }else{
            	$skills->sharingNotify = "0"; 
	    }

            $skills_guids[] = $skills->save();
            
            if ($user->skills == NULL) {
                $user->skills = $skills_guids;
            }
            else {
                $stack = $user->skills;
                    if (!(is_array($stack))) { $stack = array($stack); }

                    if ($skills_guids != NULL) {
                        $user->skills = array_merge($stack, $skills_guids);
                    }

                }
            
            $user->skills_access = $accesslevel_skills;
            $user->save();
        
        }
    }
}

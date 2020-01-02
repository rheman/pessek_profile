<?php


function UnivImageog($educationurl){
    
    $context = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $context = stream_context_create($context);
    
    $univImage = elgg_get_site_url() . "mod/pessek_profile/img/university.png";
   
   if(isset($educationurl) && !is_null($educationurl)){
   
        libxml_use_internal_errors(true);
        $doc = new DomDocument();
        $doc->loadHTML(file_get_contents($educationurl,false,$context));
        $xpath = new DOMXPath($doc);
        $query = '//*/meta[starts-with(@property, \'og:image\')]';
        $metas = $xpath->query($query);
        foreach ($metas as $meta) {
            $content = $meta->getAttribute('content');
        }
        
        if(isset($content) && !is_null($content)){
            $univImage = $content;
        }
        
   }
   /*
    if(isset($educationurl) && !is_null($educationurl)){
    
        $page_content = file_get_contents($educationurl);
        $dom_obj = new DOMDocument();
        $dom_obj->loadHTML($page_content);
        $meta_val = null;

        foreach($dom_obj->getElementsByTagName('meta') as $meta) {

            if($meta->getAttribute('property')=='og:image'){ 
            
                $meta_val = $meta->getAttribute('content');
            }
        
        }
        
        if(isset($meta_val) && !is_null($meta_val)){
            $univImage = $meta_val;
        }
    }
    */
    return $univImage;
}
/*
$accesslevel_certification = "Puewe Pessek Hermand Fulbert";
$a = (string)$accesslevel_certification;
register_error($a);*/

    $on = 'on';
    $zero = '0';
    $user_guid = (int) get_input('guid');
    $guide = (int) get_input('guide'); 
    
    $user = get_user($user_guid);
    $section = get_input('section');
    $accesslevel_education = (int) get_input('accesslevel_education');  
    
    $school = get_input('education');          
    $diploma = get_input('diploma');            
    $fieldofstudy = get_input('fieldofstudy'); 
    $degree = get_input('degree');              
    $resultobtain = get_input('resultobtain');  
    $startyear = get_input('startyear');        
    $endyear = get_input('endyear');            
    
    $ongoing = get_input('ongoing');            
    if(strcmp ($ongoing , $on)==0){$ongoing = 'true';} 
    
    if(strcmp($startyear, $endyear) > 0 && $ongoing != 'true') {
            
                $startyear = get_input('endyear');
                $endyear = get_input('startyear');
                
    }
    
    $educationurl = get_input('educationurl');  
    $activity = get_input('activity');         
    $trainingd = get_input('trainingd');        
    
    $imagesite = UnivImageog($educationurl);    
        
    $sharechanges = get_input('sharechanges');  
    
    $education_guids = array();
    
    if(strcmp($guide , $zero) > 0){
        
        $education = get_entity($guide);
        $education->subtype = "education";
        $education->owner_guid = $user_guid;
        
        $education->title = htmlentities($school);
        $education->description = htmlentities($trainingd);
                    
        $education->titles = htmlentities($school);
        $education->school = htmlentities($school);
        $education->descriptions = htmlentities($degree);
        
        $education->diploma = htmlentities($diploma);
        $education->resultobtain = htmlentities($resultobtain);
        $education->educationurl = htmlentities($educationurl);
        $education->activity = htmlentities($activity);
        $education->trainingd = htmlentities($trainingd);
        $education->field = htmlentities($fieldofstudy);
        $education->imagesite = $imagesite;
        
        $education->startyear = $startyear;
        $education->endyear = $endyear;
        $education->ongoing = $ongoing;
        //$education->sharechanges = $sharechanges;
        
        $education->degree = htmlentities($degree);
        $education->access_id = $accesslevel_education; 
        
        //-- notification when sharing
	if(strcmp ($sharechanges , $on)==0){
        	$education->sharingNotify = "1";        
        }else{
        	$education->sharingNotify = "0"; 
	}
 
        $user->education_access = $accesslevel_education;
        $user->save();
        $education->save();
        
        system_message(elgg_echo('gcconnex_profile:education:update:succes'));
        
    }else{
    
        if(!empty($user_guid)){
        
            $education = new ElggObject();
            $education->subtype = "education";
            //$education = new Education();
            
            $education->container_guid = $user_guid;
            $education->owner_guid = $user_guid;
            
            $education->title = htmlentities($school);
            $education->description = htmlentities($trainingd);
        
            $education->titles = htmlentities($school);
            $education->school = htmlentities($school);
            $education->descriptions = htmlentities($degree);
            
            $education->diploma = htmlentities($diploma);
            $education->resultobtain = htmlentities($resultobtain);
            $education->educationurl = htmlentities($educationurl);
            $education->activity = htmlentities($activity);
            $education->trainingd = htmlentities($trainingd);
            $education->field = htmlentities($fieldofstudy);
            $education->imagesite = $imagesite;
            
            $education->startyear = $startyear;
            $education->endyear = $endyear;
            $education->ongoing = $ongoing;
            //$education->sharechanges = $sharechanges;
            
            $education->degree = htmlentities($degree);
            $education->access_id = $accesslevel_education;
            
            //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
            	$education->sharingNotify = "1";        
            }else{
            	$education->sharingNotify = "0"; 
	    }

            $education_guids[] = $education->save();
            
            if ($user->education == NULL) {
                $user->education = $education_guids;
            }
            else {
                $stack = $user->education;
                    if (!(is_array($stack))) { $stack = array($stack); }

                    if ($education_guids != NULL) {
                        $user->education = array_merge($stack, $education_guids);
                    }

                }
            
            $user->education_access = $accesslevel_education;
            $user->save();
            system_message(elgg_echo('gcconnex_profile:education:save:succes'));
        }
    }
    

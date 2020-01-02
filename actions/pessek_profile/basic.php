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
    
    $accesslevel_basic = (int) get_input('accesslevel_basic'); 
    
    $DisplayName = htmlentities(get_input('name')); 
    
    $title = htmlentities(get_input('Title'));            
    $phone = htmlentities(get_input('Phone')); 
    $mobile = htmlentities(get_input('Mobile'));
    
    $website = htmlentities(get_input('Website'));            
    $facebook = htmlentities(get_input('Facebook')); 
    $googlePlus = htmlentities(get_input('GooglePlus'));
    
    $github = htmlentities(get_input('github'));            
    $twitter = htmlentities(get_input('twitter')); 
    $linkedin = htmlentities(get_input('linkedin'));
    
    $pinterest = htmlentities(get_input('pinterest'));            
    $tumblr = htmlentities(get_input('tumblr')); 
    $instagram = htmlentities(get_input('instagram'));
    
    $flickr = htmlentities(get_input('flickr')); 
    
    $youtube = htmlentities(get_input('youtube'));
    
    $thecountryoforigin = htmlentities(get_input('thecountryoforigin')); //register_error($thecountryoforigin);
    $thecountryofresidence = htmlentities(get_input('thecountryofresidence')); //register_error($thecountryofresidence);
    
    //$masteresfam = htmlentities(get_input('masteresfam')); 
    //$anneeacademique = htmlentities(get_input('anneeacademique'));
        
    $sharechanges = get_input('sharechanges'); //register_error($sharechanges); //on --good et 0 non
    
    //-- notification when sharing
    if(strcmp ($sharechanges , $on)==0){
       $user->sharingNotify = "1";        
    }else{
       $user->sharingNotify = "0"; 
    }
    
    $user->basic_access = $accesslevel_basic;
    $user->titles = $title; unset($user->title);
    $user->briefdescription = $title;
    $user->townofresidence = htmlentities(get_input('TownOfResidence'));
    $user->phone = $phone;
    $user->mobile = $mobile;
    
    $user->setDisplayName($DisplayName);

    
    $user->website = $website;
    $user->facebook = $facebook;
    $user->googlePlus = $googlePlus;
    
    $user->github = $github;
    $user->twitter = $twitter;
    $user->linkedin = $linkedin;
    
    $user->pinterest = $pinterest;
    $user->tumblr = $tumblr;
    $user->instagram = $instagram;
    
    $user->flickr = $flickr;
    $user->youtube = $youtube;
    
    //$user->masteresfam = $masteresfam;
    //$user->anneeacademique = $anneeacademique;
    
    $user->celebrations_birthdate = htmlentities(get_input('timestamp_anniv')); //celebrations plugin
    $user->celebrations_weddingdate = htmlentities(get_input('timestamp_wedding')); //celebrations plugin
    
    if(!empty($thecountryoforigin)){
                        
        $country_origin_settings = array();
                        
        $country_origin_settings = explode("@", $thecountryoforigin); 
                        
        $user->countryoforigin = $country_origin_settings[1];
                        
        $user->countryoforiginflag = $country_origin_settings[0];
    }
    
    if(!empty($thecountryofresidence)){
                        
        $country_residence_settings = array();
                        
        $country_residence_settings = explode("@", $thecountryofresidence); 
                        
        $user->countryofresidence = $country_residence_settings[1];//register_error($country_residence_settings[1]);
                        
        $user->countryofresidenceflag = $country_residence_settings[0];//register_error($country_residence_settings[0]);
    }
    
    $user->setLocation(implode(', ', array(htmlentities(get_input('TownOfResidence')), $user->countryofresidence)));
    
    $user->save();

    system_message(elgg_echo('gcconnex_profile:basic:update:succes')); 
    
    //$timestamp_anniv = (string)get_input('timestamp_anniv');register_error($timestamp_anniv);
    //$timestamp_wedding = (string)get_input('timestamp_wedding');register_error($timestamp_wedding);
    
    // $publication_date = (string)get_input('publication_date');register_error($publication_date);
    //forward(REFERER);



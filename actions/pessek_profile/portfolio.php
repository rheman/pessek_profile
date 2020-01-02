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
    $accesslevel_portfolio = (int) get_input('accesslevel_portfolio');  //register_error($accesslevel_portfolio);
    
    $portfolio_title = get_input('portfolio');           //register_error($portfolio_title);
    $portfolio_link = get_input('portfoliolink');            //register_error($portfolio_link);
    $portfolio_description = get_input('description');  //register_error($portfolio_description);
    $portfolio_startday = get_input('startday');              //register_error($portfolio_startday);
    $portfolio_startmonth = get_input('startmonth');  //register_error($portfolio_startmonth);
    $portfolio_startyear = get_input('startyear');        //register_error($portfolio_startyear);
    
    $ongoing = get_input('datestamped');            //register_error($ongoing);
    if(strcmp ($ongoing , $on)==0){$ongoing = 'true';} //register_error($ongoing);
    
        
    $sharechanges = get_input('sharechanges'); //register_error($sharechanges); //on --good et 0 non
   
    
    $portfolio_guids = array();
    
        if(strcmp($guide , $zero) > 0){

        $portfolio = get_entity($guide);
        $portfolio->subtype = "portfolio";
        $portfolio->owner_guid = $user_guid;
        
        $portfolio->title = htmlentities($portfolio_title);
        $portfolio->description = htmlentities($portfolio_description);
            
        $portfolio->thetitle = htmlentities($portfolio_title);//register_error($portfolio_title);register_error($guidE);
        $portfolio->links = htmlentities($portfolio_link);
        $portfolio->thedescription = htmlentities($portfolio_description);//register_error($portfolio_description);
            
        $portfolio->startday = htmlentities($portfolio_startday);       //register_error($portfolio_startday);
        $portfolio->startmonth = htmlentities($portfolio_startmonth);   //register_error($portfolio_startmonth);
        $portfolio->startyear = htmlentities($portfolio_startyear);     //register_error($portfolio_startyear);
            
        $portfolio->datestamped = $ongoing;
        
            
        $portfolio->access_id = $accesslevel_portfolio; 
            
        //-- notification when sharing
	if(strcmp ($sharechanges , $on)==0){
        	$portfolio->sharingNotify = "1";        
        }else{
        	$portfolio->sharingNotify = "0"; 
	}

        $user->portfolio_access = $accesslevel_portfolio;
        $user->save();
        $portfolio->save();
        
        system_message(elgg_echo('gcconnex_profile:portfolio:update:succes'));
        
    }else{
    
        if(!empty($user_guid)){
        
            $portfolio = new ElggObject();
            $portfolio->subtype = "portfolio";
            $portfolio->owner_guid = $user_guid;
            
            $portfolio->title = htmlentities($portfolio_title);
            $portfolio->description = htmlentities($portfolio_description);
                    
            $portfolio->thetitle = htmlentities($portfolio_title);
            $portfolio->links = htmlentities($portfolio_link);
            $portfolio->thedescription = htmlentities($portfolio_description);
            
            $portfolio->startday = htmlentities($portfolio_startday);
            $portfolio->startmonth = htmlentities($portfolio_startmonth);
            $portfolio->startyear = htmlentities($portfolio_startyear);
            
            $portfolio->datestamped = $ongoing;
            
            $portfolio->access_id = $accesslevel_portfolio;
                    
            //-- notification when sharing
	    if(strcmp ($sharechanges , $on)==0){
        	$portfolio->sharingNotify = "1";        
            }else{
        	$portfolio->sharingNotify = "0"; 
	    }

            $portfolio_guids[] = $portfolio->save();
            
            if ($user->portfolio == NULL) {
                $user->portfolio = $portfolio_guids;
            }
            else {
                $stack = $user->portfolio;
                    if (!(is_array($stack))) { $stack = array($stack); }

                    if ($portfolio_guids != NULL) {
                        $user->portfolio = array_merge($stack, $portfolio_guids);
                    }

                }
            
            $user->portfolio_access = $accesslevel_portfolio;
            $user->save();
            
            system_message(elgg_echo('gcconnex_profile:portfolio:save:succes'));
        
        }
    }
    
//forward(REFERER);

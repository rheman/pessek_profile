<?php
//session_start();
/*
$as = new \SimpleSAML\Auth\Simple('linkedinauf');
  $as->requireAuth(array(
    'ReturnTo' => 'https://esfam-simplesamlphp.auf.org/profile/admin'
));
  $attributes = $as->getAttributes();
*/

$user_guid = (int) get_input("guid");
$confirm_message =  get_input("confirm_message");
$source = 'linkedinauf';
$test_auth = false;

$ReturnTo = elgg_get_site_url().'linkedin_by_pessek?guid='.$user_guid.'&confirm_message='.$confirm_message;


try {

    $saml_auth = new \SimpleSAML\Auth\Simple($source);
    
} catch (Exception $e) {

    register_error(elgg_echo('simplesaml:error:class', [$e->getMessage()]));
    forward(REFERER);
    
}

if (!$saml_auth->isAuthenticated()) {
            
    $saml_auth->requireAuth(array(
        'ReturnTo' => $ReturnTo,
    ));
    $result = $saml_auth->getAttributes();
            
}else{

    $result = $saml_auth->getAttributes();
    
}
        

if(isset($saml_auth)){
        
    if (!empty($user_guid)) {
        if ($user = get_user($user_guid)) {
        
            if(is_array($result)){
            //user summary
                $object_title = elgg_echo('gcconnex_profile:about_me:summary');
                set_user_summary($user, $user_guid, '1', $result, $object_title);
            //end user summary
            //Current Position
                current_set_user_current_position($user, $user_guid, '1', $result);
            //urrent Position
                $confirm_message = (string) get_input("confirm_message");
                system_message($confirm_message);
                //forward($user->getURL());
                $saml_auth->logout($user->getURL());
            }
        
        }
    }

}

//MES FONCTIONS

function set_user_summary($user, $user_guid, $accesslevel_summary, $user_profiles, $object_title) 
{
// $object_title should be elgg_echo('gcconnex_profile:about_me:summary')
// $accesslevel_summary should be 1 for logged in access
   if (array_key_exists('linkedin.summary', $user_profiles)) {
   
        if(isset($user->description)){
            
            unset($description_guids);
            unset($user->description);
        }
            
        $description = new ElggObject();
        $description->subtype = "description";
        $description->owner_guid = $user_guid;
            
        $description->title = $object_title;
        $description->description = htmlentities($user_profiles['linkedin.summary'][0]);
        (array_key_exists('linkedin.publicProfileUrl', $user_profiles)) ? $profileURL=$user_profiles['linkedin.publicProfileUrl'][0] : $profileURL='';
        $description->descriptionname = $user_profiles['linkedin.summary'][0];
        
        $description->access_id = $accesslevel_summary;
            
        $description_guids[] = $description->save();
        $user->description = $description_guids;
        $user->linkedin = $profileURL;
        $user->description_access = $accesslevel_summary;
        $user->save();
            
   }
   
   if (array_key_exists('linkedin.headline', $user_profiles)) {
         unset($user->title);unset($user->titles);
         $user->titles = htmlentities($user_profiles['linkedin.headline'][0]);;
   }
}

function current_set_user_current_position($user, $user_guid, $accesslevel_experience, $user_profiles){

    if (array_key_exists('linkedin.positions._total', $user_profiles)) {
    
        if((int)$user_profiles['linkedin.positions._total'][0]>=1){
        
            if(isset($user->internships)){
                unset($internship_guids);
                unset($user->internships);
            }
            
            if(isset($user->volunteers)){
                unset($volunteers_guids);
                unset($user->volunteers);
            }
            
            if(isset($user->experiences)){
                unset($experiences_guids);
                unset($user->experiences);
            }
            
            
            $experiences = new ElggObject();
            $experiences->subtype = "experiences";
            $experiences->owner_guid = $user_guid;
            
            (array_key_exists('linkedin.positions.values.0.title', $user_profiles)) ? $jobtitle=$user_profiles['linkedin.positions.values.0.title'][0] : $jobtitle='';
            (array_key_exists('linkedin.positions.values.0.company.name', $user_profiles)) ? $companyname=$user_profiles['linkedin.positions.values.0.company.name'][0] : $companyname='';
            
            (array_key_exists('linkedin.positions.values.0.location.country.name', $user_profiles)) ? $country=$user_profiles['linkedin.positions.values.0.location.country.name'][0] : $country='';
            (array_key_exists('linkedin.positions.values.0.location.name', $user_profiles)) ? $place=$user_profiles['linkedin.positions.values.0.location.name'][0] : $place='';
            (array_key_exists('linkedin.positions.values.0.location.country.code', $user_profiles)) ? $flag=$user_profiles['linkedin.positions.values.0.location.country.code'][0] : $flag='';
            //($code!='') ? $flag=elgg_get_site_url().'mod/pessek_profile/img/flags/'.$flag.'.png' : $flag='';
            
            if(array_key_exists('linkedin.positions.values.0.isCurrent', $user_profiles)){
                ($user_profiles['linkedin.positions.values.0.isCurrent'][0]==1) ? $ongoing='true' : $ongoing='';
            }
            
            (array_key_exists('linkedin.positions.values.0.startDate.month', $user_profiles)) ? $startmonth=$user_profiles['linkedin.positions.values.0.startDate.month'][0] - 1 : $startmonth='';
            
            (array_key_exists('linkedin.positions.values.0.startDate.year', $user_profiles)) ? $startyear=$user_profiles['linkedin.positions.values.0.startDate.year'][0] : $startyear='';
            
            (array_key_exists('linkedin.positions.values.0.endDate.month', $user_profiles)) ? $endmonth=$user_profiles['linkedin.positions.values.0.endDate.month'][0] - 1 : $endmonth='';
            
            (array_key_exists('linkedin.positions.values.0.endDate.year', $user_profiles)) ? $endyear=$user_profiles['linkedin.positions.values.0.endDate.year'][0] : $endyear='';
            
            (array_key_exists('linkedin.positions.values.0.summary', $user_profiles)) ? $jobdescription=$user_profiles['linkedin.positions.values.0.summary'][0] : $jobdescription='';
            
            $experience_type = '2';
            
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
            $experiences->jobdescription = htmlentities($jobdescription);  
            $experiences->access_id = $accesslevel_experience; 
            
            $experiences->country = $country;
            $experiences->flag = $flag;
            
            $experiences_guids[] = $experiences->save();
            
            if ($user->experiences == NULL) {
                $user->experiences = $experiences_guids;
            }else {
                $stack = $user->experiences;
                if (!(is_array($stack))) { 
                    $stack = array($stack); 
                }

                if ($experiences_guids != NULL) {
                    $user->experiences = array_merge($stack, $experiences_guids);
                }
            }
                    
            $user->experiences_access = $accesslevel_experience;
            $user->save();
        
        }
    
    }
    
}

















        /*
$user_guid = (int) get_input("guid");

if (!empty($user_guid)) {
    if ($user = get_user($user_guid)) {

        $objectUser = get_entity($user_guid);

        $source = 'linkedinauf';
        if (empty($source)) {
            register_error(elgg_echo('simplesaml:error:no_source'));
            forward(REFERER);
        }

        try {
            $saml_auth = new \SimpleSAML\Auth\Simple($source);
        } catch (Exception $e) {
            register_error(elgg_echo('simplesaml:error:class', [$e->getMessage()]));
            forward(REFERER);
        }

        if (!$saml_auth->isAuthenticated()) {
            // not logged in on IDP, so do that
            $saml_auth->requireAuth(array(
                                    'ReturnTo' => 'https://esfam-simplesamlphp.auf.org/linkedin_by_pessek'
                                    ));
            $result = $saml_auth->getAttributes();
            
        }
        else{
            $result = $saml_auth->getAttributes();
        }
    }
}

/*$var = print_r($result, true);
register_error($var);*/
//forward(REFERER);
/*
//print_r($DatePosition);
//current Position
/*
        CurrentPositionSaver($LinkCurrPosList, $AttrCurrList, $result, $objectUser);
        DatePosition($result, "cuurentfromdate", $objectUser, $DatePosition[0], $DatePosition[1]);
        
        $Montvalue = elgg_extract($DatePosition[0], $result);
        $Yearvalue = elgg_extract($DatePosition[1], $result);
        if (is_array($Montvalue) && count($Montvalue) > 0 && is_array($Yearvalue) && count($Yearvalue) > 0) {
                saveAttribute("currenttodate", elgg_echo("pessek_linkedin:user:nowadays"), $objectUser);
                saveAttribute("importeddlinkedin", elgg_echo("pessek_linkedin:user:nowadays"), $objectUser); 
        }
//End current Position 
    } 
    else {
        register_error("pas connecte");
    }
} 
else {
    register_error("Parametre invalide");
}

/*
$LinkedIn_summary = array("linkedin.summary");

$AttrCurrList_summary = array("description");


$LinkCurrPosList = array("linkedin.positions.values.0.title", "linkedin.positions.values.0.summary", "linkedin.positions.values.0.company.name", "linkedin.positions.values.0.location.country.name", "linkedin.positions.values.0.startDate.month", "linkedin.positions.values.0.startDate.year");

$AttrCurrList = array("title", "description", "companyname", "country", "startmonth", "startyear");*/

//pessek linkedin authentication.

/*
$LinkCurrPosList = array("linkedin.positions.values.0.title", "linkedin.positions.values.0.company.name", "linkedin.positions.values.0.location.country.name", "linkedin.positions.values.0.summary", "linkedin.summary");

$AttrCurrList = array("currentposition", "currentcompanyname", "currentlocationname", "currentjobescription", "summary");

$DatePosition = array("linkedin.positions.values.0.startDate.month","linkedin.positions.values.0.startDate.year");

$user_guid = (int) get_input("guid");

if (!empty($user_guid)) {
    if ($user = get_user($user_guid)) {

        $objectUser = get_entity($user_guid);

        $source = 'linkedinauf';
        if (empty($source)) {
            register_error(elgg_echo('simplesaml:error:no_source'));
            forward(REFERER);
        }

        $label = simplesaml_get_source_label($source);

        try {
            $saml_auth = new SimpleSAML_Auth_Simple($source);
        } catch (Exception $e) {
            register_error(elgg_echo('simplesaml:error:class', [$e->getMessage()]));
            forward(REFERER);
        }

        if (!$saml_auth->isAuthenticated()) {
            // not logged in on IDP, so do that
            $saml_auth->requireAuth();
            $result = $saml_auth->getAttributes();
        }
        else{
            $result = $saml_auth->getAttributes();
        }
*/
//print_r($DatePosition);
//current Position
/*
        CurrentPositionSaver($LinkCurrPosList, $AttrCurrList, $result, $objectUser);
        DatePosition($result, "cuurentfromdate", $objectUser, $DatePosition[0], $DatePosition[1]);
        
        $Montvalue = elgg_extract($DatePosition[0], $result);
        $Yearvalue = elgg_extract($DatePosition[1], $result);
        if (is_array($Montvalue) && count($Montvalue) > 0 && is_array($Yearvalue) && count($Yearvalue) > 0) {
                saveAttribute("currenttodate", elgg_echo("pessek_linkedin:user:nowadays"), $objectUser);
                saveAttribute("importeddlinkedin", elgg_echo("pessek_linkedin:user:nowadays"), $objectUser); 
        }
//End current Position 
    } 
    else {
        register_error("pas connecte");
    }
} 
else {
    register_error("Parametre invalide");
}

function CurrentPositionSaver($CurrPosList, $AttrCurrList, $result, $objectUser){
    $i = 0;
    foreach ($CurrPosList as $key => $value) {
        currentPosition($result, $value, $objectUser, $AttrCurrList[$i]);
        $i++;
    }
}

function userSkills($result){
    
  //cette fonction est à verifier
    $skill = elgg_extract('skill', $saml_attributes);
    
    if (is_array($skill)) {
            $skill = $skill[0];
    }
}

function currentPosition($result, $attributeName, $objectUser, $linkedinAtrribute){
    
        $value = elgg_extract($attributeName, $result);
        if (is_array($value) && count($value) > 0) {
                $value = $value[0];
                saveAttribute($linkedinAtrribute, $value, $objectUser); 
        }   
}

function saveAttribute($attributeName, $value, $objectUser){

    $objectUser->$attributeName = $value;

}

function DatePosition($result, $attributeName, $objectUser, $startMonth, $startYear){
    
    $Montvalue = elgg_extract($startMonth, $result);
    $Yearvalue = elgg_extract($startYear, $result);
    if (is_array($Montvalue) && count($Montvalue) > 0 && is_array($Yearvalue) && count($Yearvalue) > 0) {
            $Montvalue = $Montvalue[0];
            $Yearvalue = $Yearvalue[0];
            $date = $Yearvalue."-".$Montvalue."-"."01";
            saveAttribute($attributeName, $date, $objectUser); 
    } 
    //$date = "2014-02-24";
    //echo date("t", strtotime($date)); return the last day of the month
}
//add summary field





//currentPosition($result, 'currentposition', $objectUser, 'linkedin.positions.values.0.title');


//print_r($result);

$params = array(
    'title' => 'Setting for LinkedIn Profile Import Plugin',
    'content' => 'My first page!',
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

$t = elgg_get_config("profile_fields");

echo elgg_view_page('Setting for LinkedIn Profile Import Plugin', $body);
echo $objectUser->cuurentfromdate;

*/
//forward(REFERER);

//print_r($t);
/*
$countryofcitizen = $user->countyofcitizen;
$placeofbirth = $user->placeofbirth;
$celebrations_birthdate = $user->celebrations_birthdate;//voir la conversion
$promotion = $user->promotion;
$master = $user->master;

$cuurentfromdate = $user->cuurentfromdate;
*/
//TURN ON GROUP NOTIFICATION
//CODE A UTILISER LORS DE LA CREATION DU COMPTE VIA LA SERVEUR D'AUTHENTIFICATION
// TOUT RECUPERER DU LDAP ET FAIRE UN TRIM
/*
$fecha = '  19/04/1985  ';
$fecha = trim($fecha);
list($dayss, $monthss, $years) = explode("/", $fecha);
$celebration = $years.'/'.$monthss.'/'.$dayss;

$userObj = get_user_by_username('christophe.kiridis');
$objectUser = get_entity($userObj->guid);

$objectUser->countyofcitizen='Gabon1';
$objectUser->placeofbirth='Douala1';
$objectUser->celebrations_birthdate=strtotime($celebration);
$objectUser->promotion='2017-2018';
$objectUser->master='Management de carriere1';
*/
//if ($objectUser->save()) {
	//system_message(elgg_echo('profile_manager:action:profile_types:add:succes'));
	//register_error("Bon");
//} else {
	//register_error(elgg_echo('profile_manager:action:profile_types:add:error:save'));
//	register_error("Pas Bon");
//}
/*
function profileUserEsfam($fecha, $uid_ldap, $countyofcitizen, $placeofbirth, $anneeAcad, $etudEtap){

    $etudEtap = ParcoursFormation($etudEtap);

    $fecha = trim($fecha);
    list($dayss, $monthss, $years) = explode("/", $fecha);
    $celebration = $years.'/'.$monthss.'/'.$dayss;

    $userObj = get_user_by_username($uid_ldap);
    $objectUser = get_entity($userObj->guid);

    $objectUser->countyofcitizen = $countyofcitizen;
    $objectUser->placeofbirth = $placeofbirth;
    $objectUser->celebrations_birthdate=strtotime($celebration);
    $objectUser->promotion = $anneeAcad;
    $objectUser->master = $etudEtap;
}

//FIN CREATION DU COMPTE VIA LA SERVEUR D'AUTHENTIFICATION
function UserGroupSbuscription($elgg_admin_username, $user_ldap_affiliation, $uid_ldap, $anneeAcad, $etudEtap, $dateDeNaissance, $countyofcitizen,  $placeofbirth){
    $ia = elgg_set_ignore_access(TRUE);

    $student='student';

    $user_ldap_affiliation = trim($user_ldap_affiliation);

    $uid_ldap = trim($uid_ldap);
    $anneeAcad = trim($anneeAcad);
    $uid_ldap = trim($uid_ldap);
    $etudEtap = trim($etudEtap);
    $dateDeNaissance = trim($dateDeNaissance);
    $countyofcitizen = trim($countyofcitizen);
    $placeofbirth = trim($placeofbirth);

    $userObj = get_user_by_username($uid_ldap);
    $objectUser = get_entity($userObj->guid);

    $elgg_admin_username = trim($elgg_admin_username);
    $elggAdminObj = get_user_by_username($elgg_admin_username);
    $elggAdmin_guid = $elggAdminObj->guid;
    $AdminUser = get_entity($elggAdminObj->guid);


    if(strcasecmp($user_ldap_affiliation,$student) == 0){
        
        //inscription au groupe famille esfam le guid de ce groupe est: 218
        //
        $famille_esfam_guid = '218';
        
        profileUserEsfam($dateDeNaissance, $uid_ldap, $countyofcitizen, $placeofbirth, $anneeAcad, $etudEtap);

        addFriendToAdmin($AdminUser, $userObj->guid);
        approveFriendShip($userObj->guid, $elggAdminObj->guid);

        famillyEsfamGroup($objectUser, $famille_esfam_guid);
        promtionMasterGroup($elggAdmin_guid, $objectUser, $anneeAcad, $etudEtap);
        //$aa = PrivateEsfamGroup($elggAdmin_guid, $objectUser, 220);
        //return $aa;
        groupeNotification ($objectUser);
        friendPersonnalNotification($objectUser);
    }
    elgg_set_ignore_access($ia);
}

function promtionMasterGroup($elggAdmin_guid, $user, $anneeAcad, $etudEtap){
    
    if(strcasecmp($anneeAcad,'2016/2017') == 0){

        $etudEtap = strtolower($etudEtap);
        PrivateEsfamGroup($elggAdmin_guid, $user, '233'); //inscription au groupe de la promotion

        switch ($etudEtap) {
            case "mmt":
                PrivateEsfamGroup($elggAdmin_guid, $user, '220');
                break;
            case "mae":
                PrivateEsfamGroup($elggAdmin_guid, $user, '223');
                break;
            case "mess":
                PrivateEsfamGroup($elggAdmin_guid, $user, '224');
                break;
            case "mca":
                PrivateEsfamGroup($elggAdmin_guid, $user, '227');
                break;
            case "ceap_p":
                PrivateEsfamGroup($elggAdmin_guid, $user, '228');
                break;
            case "ceap_r":
                PrivateEsfamGroup($elggAdmin_guid, $user, '228');
                break;
            case "mmp_ae":
                PrivateEsfamGroup($elggAdmin_guid, $user, '230');
                break;
            case "mmp_ip":
                PrivateEsfamGroup($elggAdmin_guid, $user, '230');
                break;
        }
    }
}

function PrivateEsfamGroup($elggAdmin_guid, $user, $groupUID){
    //inscription au closed groupe
    $ia = elgg_set_ignore_access(TRUE);

    $group = get_entity($groupUID);

    if($group && !$group->isMember($user)) {

        $result = $group->join($user);

        if ($result) {
            // flush user's access info so the collection is added
            get_access_list($user->guid, 0, true);

            // Remove any invite or join request flags
            remove_entity_relationship($group->guid, 'invited', $user->guid);
            remove_entity_relationship($user->guid, 'membership_request', $group->guid);

            elgg_create_river_item(array(
                'view' => 'river/relationship/member/create',
                'action_type' => 'join',
                'subject_guid' => $user->guid,
                'object_guid' => $group->guid,
            ));

            //return 1;
        }

        //return 2;
    }

    
    elgg_set_ignore_access($ia);

    //return 0;
}

function famillyEsfamGroup($user, $groupUID){
    //inscription au public group
    $ia = elgg_set_ignore_access(true);
    $groupEnt_famille_esfam = get_entity($groupUID);
    //elgg_set_ignore_access($ia);

    if($groupEnt_famille_esfam && !$groupEnt_famille_esfam->isMember($user)){

        if ($groupEnt_famille_esfam->join($user)) {
                    // Remove any invite or join request flags
            elgg_delete_metadata(array('guid' => $user->guid, 'metadata_name' => 'group_invite', 'metadata_value' => $groupEnt_famille_esfam->guid, 'limit' => false));
            elgg_delete_metadata(array('guid' => $user->guid, 'metadata_name' => 'group_join_request', 'metadata_value' => $groupEnt_famille_esfam->guid, 'limit' => false));
        }
    }

    elgg_set_ignore_access($ia);
}

function groupeNotification ($user){
    //activation de la notification des groupes (email et site)
    $groups = array();
    $options = array(
        'relationship' => 'member',
        'relationship_guid' => $user->guid,
        'type' => 'group',
        'limit' => false,
    );

    if ($groupmemberships = elgg_get_entities_from_relationship($options)) {
        foreach ($groupmemberships as $groupmembership) {
            $groups[] = $groupmembership->guid;
        }
    }

    if (!empty($groups)) {

        $NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
        foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
            
            foreach ($groups as $group) {
                    elgg_add_subscription($user->guid, $method, $group);
            }
        }
    }
}

function friendPersonnalNotification($user){
    //activation de la notification personnelle (email et site)
    $NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();

    $subscriptions = array();

    foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
        $user->setNotificationSetting($method, true);

        $collections[$method] = '-1';
        $metaname = 'collections_notifications_preferences_' . $method;
        $user->$metaname = $collections[$method];

        $subscriptions[$method] = $method;
        remove_entity_relationships($user->guid, 'notify' . $method, false, 'user');
    }

    foreach ($subscriptions as $method => $subscription) {
        if (is_array($subscription) && !empty($subscription)) {
            foreach ($subscription as $subscriptionperson) {
                elgg_add_subscription($user->guid, $method, $subscriptionperson);
            }
        }
    }

    $friends = elgg_get_entities_from_relationship([
            'types' => 'user',
            'relationship' => 'friend',
            'relationship_guid' => $user->guid,
            'limit' => false,
            //'callback' => false,
            //'batch' => true,
    ]);

    foreach ($friends as $friend) {
        foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
            elgg_add_subscription($user->guid, $method, $friend->guid);
        }
    }
    
}

function addFriendToAdmin($user, $friend_guid){
    
    $friend = get_user($friend_guid);
    // Now we need to attempt to create the relationship
    if (empty($user) || empty($friend)) {
        register_error(elgg_echo('friend_request:add:failure'));
        forward(REFERER);
    }

    // New for v1.1 - If the other user is already a friend (fan) of this user we should auto-approve the friend request...
    if (check_entity_relationship($friend->getGUID(), 'friend', $user->getGUID())) {
        try {
            
            $user->addFriend($friend->getGUID());
            
            //system_message(elgg_echo('friends:add:successful', [$friend->name]));
            forward(REFERER);
        } catch (Exception $e) {
            register_error(elgg_echo('friends:add:failure', [$friend->name]));
            forward(REFERER);
        }
    } elseif (check_entity_relationship($friend->getGUID(), 'friendrequest', $user->getGUID())) {
        // Check if your potential friend already invited you, if so make friends
        if (remove_entity_relationship($friend->getGUID(), 'friendrequest', $user->getGUID())) {
            
            // Friends mean reciprical...
            $user->addFriend($friend->getGUID());
            $friend->addFriend($user->getGUID());
            
            //system_message(elgg_echo('friend_request:approve:successful', [$friend->name]));
            
            // add to river
            friend_request_create_river_events($user->getGUID(), $friend->getGUID());
            
            forward(REFERER);
        } else {
            register_error(elgg_echo('friend_request:approve:fail', [$friend->name]));
            forward(REFERER);
        }
    } else {
        try {
            if (!add_entity_relationship($user->getGUID(), 'friendrequest', $friend->getGUID())) {
                register_error(elgg_echo('friend_request:add:exists', [$friend->name]));
                forward(REFERER);
            }
        } catch (Exception $e) {
            // add_entity_relationship calls insert_data which CAN raise Exceptions.
            register_error(elgg_echo('friend_request:add:exists', [$friend->name]));
            forward(REFERER);
        }
    }
}

function approveFriendShip($user_guid, $friend_guid){
    $friend = get_user($friend_guid);
    if (empty($friend)) {
        register_error(elgg_echo('error:missing_data'));
        forward(REFERER);
    }

    $user = get_user($user_guid);
    if (!($user instanceof \ElggUser) || !$user->canEdit($user_guid)) {
        register_error(elgg_echo('action:unauthorized'));
        forward(REFERER);
    }

    if (!remove_entity_relationship($friend->getGUID(), 'friendrequest', $user->getGUID())) {
        register_error(elgg_echo('friend_request:approve:fail', [$friend->name]));
        forward(REFERER);
    }

    $user->addFriend($friend->getGUID());
    $friend->addFriend($user->getGUID()); //Friends mean reciprocal...

    // notify the user about the acceptance
    $subject = elgg_echo('friend_request:approve:subject', [$user->name], $friend->language);
    $message = elgg_echo('friend_request:approve:message', [$friend->name, $user->name], $friend->language);

    $params = [
        'action' => 'add_friend',
        'object' => $user,
    ];
    notify_user($friend->getGUID(), $user->getGUID(), $subject, $message, $params);

    // add to river
    friend_request_create_river_events($user->getGUID(), $friend->getGUID());
}

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
    //echo ($method);
    $subscriptions[$method] = $method;

    echo($subscriptions[$method]);
}

function ParcoursFormation($etudEtap){
    
    $etudEtap = strtolower($etudEtap);
    $parcours = "";

    switch ($etudEtap) {
            case "mmt":
                $parcours = "Master Développement Térritorial Durable - Spécialité Management du Tourisme et des Loisirs ";
                break;
            case "mae":
                $parcours = "Master Droit-Economie-Gestion, Spécialité Administration des Entreprises";
                break;
            case "mess":
                $parcours = "Master Droit-Economie-Gestion Mention Economie Sociale et Solidaire";
                break;
            case "mca":
                $parcours = "Master Management de l'Innovation Parcours Management des Carrières d'Artistes";
                break;
            case "ceap_p":
                $parcours = "Master Conceil et Expertise en Action Publique, Parcours Professionnel";
                break;
            case "ceap_r":
                $parcours = "Master Conceil et Expertise en Action Publique, Parcours Recherche";
                break;
            case "mmp_ae":
                $parcours = "Master Science de Gestion - Spécialité Management Public - Parcours Management des affaires européennes ";
                break;
            case "mmp_ip":
                $parcours = "Master Science de Gestion - Spécialité Management Public - Parcours Management des institutions publiques";
                break;
        }

        return $parcours;
}
//$user = get_user($user_guid);
//groupeNotification ($user);
//friendPersonnalNotification($user);
//UserGroupSbuscription(elgg_admin_username, 'studenT', 'sidoineginim', '2016/2017', 'MMT', '30/04/1987', 'Gabon1',  'Yaounde1');
//echo UserGroupSbuscription('pessek', 'studenT', 'sidoineginim', '2016/2017', 'mmT', '30/04/1991', 'Kameroun',  'Bafang');
*/
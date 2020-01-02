<?php

if (elgg_is_active_plugin('GC_profileStrength')) {
    elgg_load_css("circliful");
    elgg_require_js('circliful-init');
}

$user = elgg_get_page_owner_entity();

$user_guid = $user->guid; 

$profile_fields = elgg_get_config('profile_fields');

// display the username, title, phone, mobile, email, website
// fa classes are the font-awesome icons

if (!$user) {
    // no user so we quit view
    echo elgg_echo('viewfailure', array(__FILE__));
    return TRUE;
}

$linkedin_link = "linkedin/import";

if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()){

    if(elgg_get_plugin_setting('pesseklinkedin', 'pessek_profile')){

        $current_user = elgg_get_logged_in_user_entity()->guid;

        $providers = unserialize(elgg_get_plugin_setting('providers', 'linkedin_profile_importer'));

        foreach ($providers as $provider => $settings) {

            if ($settings['enabled']) {

                $adapter = false;

                $ha = new ElggHybridAuth();

                try {
                    $adapter = $ha->getAdapter($provider);
                } catch (Exception $e) {
                    // do nothing
                }

                if ($adapter) {
                    $title = elgg_view_icon(strtolower("auth-$provider-large")) . " " . $provider;

                    if (elgg_get_plugin_user_setting("$provider:uid", $user->guid, 'linkedin_profile_importer')) {
                        $content = '<div class="ptm">' . elgg_view('output/url', array(
                            'href' => 'linkedin/import',
                            'text' =>  elgg_view_icon(strtolower("auth-$provider-large")),
                            'title' => elgg_echo('linkedin:import-linkedin'),
                        )) . '</div>';
                        $linkedin_link = "linkedin/import";
                    } else {
                        $forward_url = urlencode(elgg_normalize_url("linkedin/import"));
                        $content = '<div class="ptm">' . elgg_view('output/url', array(
                            'href' => "linkedin/authenticate?provider=$provider&elgg_forward_url=$forward_url",
                            'text' =>  elgg_view_icon(strtolower("auth-$provider-large")),
                            'title' => elgg_echo('linkedin:import-linkedin'),
                        )) . '</div>';
                        
                        $linkedin_link = "linkedin/authenticate?provider=$provider&elgg_forward_url=$forward_url";
                    
                    }

                    //echo $content;
                }
            }
        }
    
        $section = 'linkedin';
        $confirm_message = elgg_echo('gcconnex_profile:experience:import:success');
        $deletionmessage = elgg_echo('gcconnex_profile:pessek:hello'). '' . $user->getDisplayName() ; //style="margin:auto;width: 60%;position: relative;"
            $linkedin_import_link ='
            <div class="linkedin_import">
                    <button type="button" class="btn btn-primary" onclick="confirmLinkedin(\''.$linkedin_link.'\',\''.$linkedin_link.'\',\''.elgg_echo('gcconnex_profile:experience:import:linkedin:confirm').'\',\''.$section.'\',\''.$deletionmessage.'\',\''.elgg_echo('gcconnex_profile:pessek:yes').'\',\''.elgg_echo('gcconnex_profile:pessek:cancel').'\',\''.$confirm_message.'\')">
                        <span class="glyphicon glyphicon-link"></span> '.elgg_echo('pessek_linkedin:settings:pesseklinkedin:import').'
                    </button>
            </div>';
            
            /*$linkedin_import_link ='
            <div style="margin:auto;width: 35%;">
                <a href="'.elgg_get_site_url().'linkedin_by_pessek?guid='.$user_guid.'">
                    <button type="button" class="btn btn-primary">
                        <span class="glyphicon glyphicon-link"></span> '.elgg_echo('pessek_linkedin:settings:pesseklinkedin:import').'
                    </button>
                </p>
            </div>';*/
            
        }
}


$edit_basic = '';

$menu = elgg_trigger_plugin_hook('register', "menu:user_hover", array('entity' => $user), array());
$builder = new ElggMenuBuilder($menu);
$menu = $builder->getMenu();
$actions = elgg_extract('action', $menu, array());
$admin = elgg_extract('admin', $menu, array());

$admin_links = '';
if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
    
    $admin_links = '<div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'.elgg_echo('admin:options').'
                    <span class="caret"></span></button>
                        <ul class="dropdown-menu friendaction-pessek">';
                        
    foreach ($admin as $menu_item) {
        $admin_links .= '<li><a href="#">'. elgg_view('navigation/menu/elements/item', array('item' => $menu_item)) .'</a></li>';
        //$admin_links .= '<li class="divider"></li>';
    }


    $admin_links .= '</ul></div>'; 
}

$profile_actions = '';

if (elgg_get_page_owner_guid() != elgg_get_logged_in_user_guid()) {
    
    $profile_actions = '<div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'.elgg_echo('friend:actions').'
                        <span class="caret"></span></button>
                            <ul class="dropdown-menu friendaction-pessek">';
                            
    $user_logged = elgg_get_logged_in_user_entity();
    
    $user_page_owner = get_user($user_guid);
    $user_page_owner_username = $user_page_owner->username;
                
    $i = 0;
    
    if (elgg_is_logged_in() && $actions) {
    
        foreach ($actions as $action) {
        
            if($i == 0 && !$user_logged->isFriendsWith($user_guid)){

                $profile_actions .= '<li><a href="'.$action->getHref().'">'. $action->getText(). '</a></li>';
                
            }else if($i == 1 && $user_logged->isFriendsWith($user_guid)){
            
                $profile_actions .= '<li><a href="'.$action->getHref().'">'. $action->getText(). '</a></li>';
                
            }else if($i == 4 && $user_logged->isFriendsWith($user_guid) ){
            
               $profile_actions .= '<li><a href="'.$action->getHref().'">'. $action->getText() .'</a></li>';
                
            }else if($i == 5 && !$user_logged->isFriendsWith($user_guid) ){
            
               $profile_actions .= '<li><a href="'.$action->getHref().'">'. $action->getText() .'</a></li>';
                
            }else if($i != 0 && $i != 1 && $i != 4 && $i != 5 ){
            
               $profile_actions .= '<li><a href="'.$action->getHref().'">'. $action->getText() .'</a></li>';
                
            }
            
            $i++;
            //$profile_actions .= '<li><a href="#">'. $action->getContent().'</a></li>';
        }
        
    $profile_actions .= '<li><a href="'.elgg_get_site_url()."friends/".$user_page_owner_username.'">'.elgg_echo('pessek_profile:action:seefriends').'</a></li>';
     
    if(!elgg_is_admin_logged_in() && $user_logged->isFriendsWith($user_guid)){
        $profile_actions .= '<li><a href="'.elgg_get_site_url()."activity/owner/".$user_page_owner_username.'">'.elgg_echo('pessek_profile:action:viewactivity').'</a></li>'; 
    }
       // $i = 0;
    
    }
    
    $profile_actions .= '</ul></div>'; 

}

/*$titi = '
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">WebSiteName</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Home</a></li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Page 1 <span class="caret"></span></a>
        <ul class="lololo dropdown-menu">
          <li><a href="#">Page 1-1</a></li>
          <li><a href="#">Page 1-2</a></li>
          <li><a href="#">Page 1-3</a></li>
        </ul>
      </li>
      <li><a href="#">Page 2</a></li>
      <li><a href="#">Page 3</a></li>
    </ul>
  </div>
';*/


if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid() || elgg_is_admin_logged_in()) {

    $iconimg = '<div class="avatar-hover-edit"> ' .elgg_echo('gcconnex_profile:basic:edit:avatar'). '</div>';
    //$iconimg .= '<img src="' . $user->getIconURL('large') . '" alt="" class="avatar-profile-page"/>';
    $iconimg .= '<img src="" alt="" class="avatar-profile-page"/>';
    
    $icon = elgg_view('output/url', array(
            'text' => $iconimg,
            'href' => 'avatar/edit/' . $user->username,
            'class' => "avatar-profile-edit"
        )
    ). '<span class="badge-box"><i class="fa fa-check"></i></span>';
    
    $check = '<span class="badge-box"><i class="fa fa-check"></i></span>';
    
    $edit_basic = '<p data-placement="top" data-toggle="tooltip" title="'. elgg_echo('gcconnex_profile:education:edit') .'">'. elgg_view('output/url', array(
                    'href' => 'ajax/view/forms/pessek_profile/edit_basic?guidp='.$user_guid,
                    'class' => 'btn btn-primary btn-md glyphicon glyphicon-edit elgg-lightbox',
                    'data-colorbox-opts' => json_encode(['maxHeight' => '85%', 'maxWidth' => '100%']),
                    'text' => elgg_echo('gcconnex_profile:edit_profile')
                )). '</p>';
    
}
else {

    $user_logged = elgg_get_logged_in_user_entity();
    
    $check = '<span class="badge-box"><i class="fa fa-plus"></i></span>';
    
    if($user_logged->isFriendsWith($user_guid)){

        $check = '<span class="badge-box"><i class="fa fa-check"></i></span>';
    }
    
    $icon = '<img src="';
    //$icon .= $user->getIconURL('large') . '" class="avatar-profile-page">' .$check. '';
    $icon .= '" class="avatar-profile-page">' .$check. '';

}

$phones = '';

if ($user->phone != null) {
    //$phones = '<a href="#"> <span class="btn btn-default btn-xs glyphicon glyphicon-earphone">' . $user->phone . '</span>&nbsp;&nbsp;</a>';
    $phones = '<span class="card-title"><span class="glyphicon glyphicon-earphone"></span> <strong>' . $user->phone . '</strong></span>&nbsp;&nbsp;';
}

$mobiles = '';

if ($user->mobile != null) {
     //$mobiles = '<a href="#"><span class="btn btn-default btn-xs glyphicon glyphicon-phone">' . $user->mobile . '</span>&nbsp;&nbsp;</a>';
     $mobiles = '<span class="card-title"><span class="glyphicon glyphicon-phone"></span> <strong>' . $user->mobile . '</strong></span>&nbsp;&nbsp;';
}

$emails = '';

if ($user->email != null) {
    //$emails = '<a href="mailto:' . $user->email . '"> <span class="btn btn-default btn-xs glyphicon glyphicon-envelope">' . $user->email . '</span>&nbsp;&nbsp;</a>';
    $emails = '<span class="card-title"><a style="color:white" href="mailto:' . $user->email . '"><span class="glyphicon glyphicon-envelope"></span> <strong>' . $user->email . '</strong></a></span>&nbsp;&nbsp;';

}
//class="btn btn-default btn-xs glyphicon glyphicon-envelope"
$websites = '';
if ($user->website != null) {
    //$websites = '<a href="' . $user->website . '" target="_blank"> <span class="btn btn-default btn-xs glyphicon glyphicon-link">' . $user->website . '</span>&nbsp;&nbsp;</a>';
    $websites = '<span class="card-title"><a style="color:white" href="' . $user->website . '" target="_blank"><span class="glyphicon glyphicon-link"></span> <strong>' . $user->website . '</strong></a></span>&nbsp;&nbsp;';
}

/*
$master_esfam = '';
if ($user->masteresfam != null) {
    $master_esfam = '<span class="card-title"><span class="glyphicon glyphicon-education"></span> <strong>' . $user->masteresfam . '</strong></span>&nbsp;&nbsp;';
}

$anneeacademique = '';
if ($user->anneeacademique != null) {
    $anneeacademique = '<span class="card-title"><span class="glyphicon glyphicon-calendar"></span> <strong>' . $user->anneeacademique . '</strong></span>&nbsp;&nbsp;';
}
*/
$countryofresidence = '';
if ($user->countryoforigin != null) {
    $countryofresidence = '<span class="card-title"><span class="glyphicon glyphicon-flag"></span> <strong><i>' . $user->townofresidence. ','. $user->countryofresidence . '</i></strong></span>&nbsp;&nbsp;';
}

$social = array('facebook', 'github', 'twitter', 'linkedin', 'pinterest', 'tumblr', 'instagram', 'flickr', 'youtube');
//$social = array('facebook', 'googlePlus', 'github', 'twitter', 'linkedin', 'pinterest', 'tumblr', 'instagram', 'flickr', 'youtube');
//<br><p> '.$phones.''.$mobiles.''.$emails.''.$websites.'<br><br>'.$master_esfam.''.$anneeacademique.''.$countryofresidence.'
        echo '  <div class="card">
                        <canvas class="header-bg" width="50" height="70" id="header-blur"></canvas>
                        <div class="avatar">
                            '.$icon.'
                        </div>
                        <div class="content">
                        '.$editBasic1.'
                            <br><p> '.$phones.''.$mobiles.''.$emails.''.$websites.'<br><br>'.$countryofresidence.'
                                <h1 class="whitecolor">'.$user->name.'</h1><br> 
                            
                                <span class="whitecolor"><i>' .$user->titles. '</i></span><br><br>';
                            
                            foreach ($social as $media) {

                                if ($link = $user->$media) {//$link = $user->get($media)
                                    if ($media == 'facebook') { $link = "http://www.facebook.com/" . $link; }
                                    //if ($media == 'google') { $link = "http://plus.google.com/" . $link; }
                                    if ($media == 'github') { $link = "https://github.com/" . $link; }
                                    if ($media == 'twitter') { $link = "https://twitter.com/" . $link; }
                                    if ($media == 'linkedin') { $link = $link; }
                                    if ($media == 'pinterest') { $link = "http://www.pinterest.com/" . $link; }
                                    if ($media == 'tumblr') { $link = "https://www.tumblr.com/blog/" . $link; }
                                    if ($media == 'instagram') { $link = "http://instagram.com/" . $link; }
                                    if ($media == 'flickr') { $link = "http://flickr.com/" . $link; }
                                    if ($media == 'youtube') { $link = "http://www.youtube.com/" . $link; }

                                    //if ($media == 'googlePlus') { $media = 'google-plus'; } // the google font-awesome class is called "google-plus", so convert "google" to that..
                                    echo '<a href="' . $link . '" target="_blank" title="'.$media.'" ><img class="social-media-icons" src="' . elgg_get_site_url() . 'mod/pessek_profile/img/social-media/' . $media . '.png"></a>';
                                    
                                    $media = NULL;
                                }
                            }
                            
                            if($user->canEdit()){
                            
                                echo $edit_basic;
                                
                            }
                            // echo '<p><button type="button" class="btn btn-default">Contact</button></p>
                            echo '<p> </p>
                        </div>';
        
        /*echo '<div class="row">
                
                    <div class="col-sm-2">'.$profile_actions.'
                    </div>
                    <div class="col-sm-6">'.$titi.'
                    </div>
                    <div class="col-sm-2">'.$admin_links.'
                    </div>
                
            </div>';*/
        /*echo '<div style="float:left">'.$profile_actions.'</div>';
        echo '<div style="float:right">'.$admin_links.'</div>';*/
echo '</div>';
/*echo '<div class="row">
                
                    <div class="col-sm-2">'.$profile_actions.'
                    </div>
                    <div class="col-sm-6">'.$titi.'
                    </div>
                    <div class="col-sm-2">'.$admin_links.'
                    </div>
                
            </div>';*/
echo '<div style="margin:auto;width: 15%;">'.$profile_actions.'</div>';//echo '<div style="float:left">'.$profile_actions.'</div>';
echo '<div style="float:right">'.$admin_links.'</div>';
echo '<br>'; echo $linkedin_import_link;

$value = elgg_trigger_plugin_hook('elgg.data', 'site', 'profileStrength_config_user_page');

if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()){

    if($value['pessek_profile_strength']['profile_strength']!=100){

        echo '<div id="complete" style="margin-left:40px;display:inline-block;float:left;width:100px;height:100px;"></div>';

    }else{
        echo '<div style="display:inline-block;float:left;">';
        echo elgg_view('output/img', array(
                'src' => 'mod/GC_profileStrength/graphics/completeBadgeLvl01.png',
                'style' => 'width:105px;'
                ));
        echo '<span style="color:#055959;font-weight:bold;">'.elgg_echo('ps:all-star').'</span>';
        echo '</div>';
    }

}
//o.elgg_echo('ps:youhave', ['10'],$user->language).'
/*echo '<div style="display:inline-block;float:right;width:100px;height:100px;">fdfdfd</div>';*/

/*
$cookiesP = array("Accept-language: en\r\n" . "Cookie: testcookie=blah; path=/; domain=auf.org;");

$context = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ),
    'http' => array('method'=>"GET",'header' => $cookiesP)
);
$context = stream_context_create($context);
$encoded_data = 'data:image/jpeg;base64,'.base64_encode(file_get_contents($user->getIconURL('large'),false,$context));
$encoded_data = 'data:image/jpeg;base64,'.base64_encode($user->getIconURL('large'));
echo '<img class="src-image"  src="'.$encoded_data.'" />';
echo '<img class="src-image"  src="'.$user->getIconURL('large').'" />';
echo $encoded_data;*/

echo '<img class="src-image" src="'.$user->getIconURL('large').'" />';
/*
$tabavatar = explode("/", $user->getIconURL());
$max = sizeof($tabavatar);
echo $max;*/
//echo '</div>';
//echo $user->getIconURL('large'); 


        

    


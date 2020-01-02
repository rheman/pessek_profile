<?php


$user = elgg_get_page_owner_entity();

$user_guid = (string) $user->guid; 

$profile_fields = elgg_get_config('profile_fields');

// display the username, title, phone, mobile, email, website
// fa classes are the font-awesome icons

if (!$user) {
    // no user so we quit view
    echo elgg_echo('viewfailure', array(__FILE__));
    return TRUE;
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
                    <button class="btn btn-success loading dropdown-toggle" type="button" data-toggle="dropdown">'.elgg_echo('admin:options').'
                    <span class="caret"></span></button>
                        <ul class="dropdown-menu">';
                        
    foreach ($admin as $menu_item) {
        $admin_links .= '<li><a href="#">'. elgg_view('navigation/menu/elements/item', array('item' => $menu_item)) .'</a></li>';
        //$admin_links .= '<li class="divider"></li>';
    }

    $admin_links .= '</ul></div>'; 
}

$profile_actions = '';

if (elgg_get_page_owner_guid() != elgg_get_logged_in_user_guid()) {
    
    $profile_actions = '<div class="dropdown">
                        <button class="btn btn-success loading dropdown-toggle" type="button" data-toggle="dropdown">'.elgg_echo('friend:actions').'
                        <span class="caret"></span></button>
                            <ul class="dropdown-menu">';
                            
    $user_logged = elgg_get_logged_in_user_entity();
    
    $i = 0;
    
    if (elgg_is_logged_in() && $actions) {
    
        foreach ($actions as $action) {
        
            if($i == 0 && !$user_logged->isFriendsWith($user_guid)){

                $profile_actions .= '<li><a href="#">'. $action->getContent(). '</a></li>';
                
            }else if($i == 1 && $user_logged->isFriendsWith($user_guid)){
            
                $profile_actions .= '<li><a href="#">'. $action->getContent(). '</a></li>';
                
            }else if($i != 0 && $i != 1 ){
            
               $profile_actions .= '<li><a href="#">'. $action->getContent(). '</a></li>';
                
            }
            
            $i++;
        }
        
       // $i = 0;
    
    }
    
    $profile_actions .= '</ul></div>'; 

}



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
                    'class' => 'btn btn-success loading btn-md glyphicon glyphicon-edit elgg-lightbox',
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

    $phones = '<a href="#"> <span class="btn btn-default btn-xs glyphicon glyphicon-earphone">' . $user->phone . '</span>&nbsp;&nbsp;</a>';
}

$mobiles = '';

if ($user->mobile != null) {

     $mobiles = '<a href="#"><span class="btn btn-default btn-xs glyphicon glyphicon-phone">' . $user->mobile . '</span>&nbsp;&nbsp;</a>';
}

$emails = '';

if ($user->email != null) {
    
    $emails = '<a href="mailto:' . $user->email . '"> <span class="btn btn-default btn-xs glyphicon glyphicon-envelope">' . $user->email . '</span>&nbsp;&nbsp;</a>';

}

$websites = '';

if ($user->website != null) {
    
    $websites = '<a href="' . $user->website . '" target="_blank"> <span class="btn btn-default btn-xs glyphicon glyphicon-link">' . $user->website . '</span>&nbsp;&nbsp;</a>';
}

$social = array('facebook', 'googlePlus', 'github', 'twitter', 'linkedin', 'pinterest', 'tumblr', 'instagram', 'flickr', 'youtube');

echo '<div class="container">';
        echo '<div class="row">
                
                <div class="col-sm-10">
                    <div class="card">
                        <canvas class="header-bg" width="50" height="70" id="header-blur"></canvas>
                        <div class="avatar">
                            '.$icon.'
                        </div>
                        <div class="content">
                        '.$editBasic1.'
                            <p> '.$phones.''.$mobiles.''.$emails.''.$websites.'
                                <h1 class="whitecolor">'.$user->name.'</h1><br> 
                            
                                <span class="whitecolor"><i>' .$user->titles. '</i></span><br><br>';
                            
                            foreach ($social as $media) {

                                if ($link = $user->get($media)) {
                                    if ($media == 'facebook') { $link = "http://www.facebook.com/" . $link; }
                                    if ($media == 'google') { $link = "http://plus.google.com/" . $link; }
                                    if ($media == 'github') { $link = "https://github.com/" . $link; }
                                    if ($media == 'twitter') { $link = "https://twitter.com/" . $link; }
                                    if ($media == 'linkedin') { $link = "http://ca.linkedin.com/in/" . $link; }
                                    if ($media == 'pinterest') { $link = "http://www.pinterest.com/" . $link; }
                                    if ($media == 'tumblr') { $link = "https://www.tumblr.com/blog/" . $link; }
                                    if ($media == 'instagram') { $link = "http://instagram.com/" . $link; }
                                    if ($media == 'flickr') { $link = "http://flickr.com/" . $link; }
                                    if ($media == 'youtube') { $link = "http://www.youtube.com/" . $link; }

                                    if ($media == 'googlePlus') { $media = 'google-plus'; } // the google font-awesome class is called "google-plus", so convert "google" to that..
                                    echo '<a href="' . $link . '" target="_blank" title="'.$media.'" ><img class="social-media-icons" src="' . elgg_get_site_url() . 'mod/pessek_profile/img/social-media/' . $media . '.png"></a>';
                                    
                                    $media = NULL;
                                }
                            }
                            
                            if($user->canEdit()){
                            
                                echo $edit_basic;
                                
                            }
                            // echo '<p><button type="button" class="btn btn-default">Contact</button></p>
                            echo '<p> </p>
                        </div>
                    </div>
                </div>
                
             </div>';
        
        echo '<div class="row">
                
                    <div class="col-sm-2">'.$profile_actions.'
                    </div>
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-2">'.$admin_links.'
                    </div>
                
            </div>';
echo '</div>';
echo '<br>';

$encoded_data = 'data:image/jpeg;base64,'.base64_encode(file_get_contents($user->getIconURL('large')));
echo '<img class="src-image"  src="'.$encoded_data.'" />';
//echo $encoded_data;

//echo '</div>';


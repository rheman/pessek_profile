<?php
/*
 * Author: Bryden Arndt
 * Date: 01/07/2015
 * Purpose: Wrap the user profile details in divs for css styling and for jQuery elements (edit/save/cancel controls below)
 * Requires: Sections must be pre-populated in the $sections array below. The view for that section must be registered in start.php, and the file has
 * to be in lowercases, named the same as what you populate $sections with, but replacing spaces with dashes.
 * IE: "About Me" becomes "about-me.php"
 */

elgg_load_js("bootstrap");
elgg_load_css("bootstrap");

elgg_load_js('readmore');

elgg_load_js('typeahead');
elgg_load_js('gcconnex-profile'); // js file for handling the edit/save/cancel toggles
elgg_load_js('pessek-portfolio');
elgg_load_css('gcconnex-css'); // main css styling sheet
elgg_load_css('font-awesome'); // font-awesome icons for social media and some of the basic profile fields
elgg_load_js('lightbox'); // overlay for editing the basic profile fields
elgg_load_css('lightbox'); // css for it..
elgg_load_js('jquery-confirm'); 
elgg_load_js('loadingoverlay');
//elgg_load_js('basicprofile');
//elgg_load_js('bootstrap-tour');
//elgg_load_css('bootstrap-tour-css');
elgg_load_css("jquery-confirm-css");


?>
<div class="profile elgg-col-3of3">
    <div class="clearfix"> <!--elgg-inner -->
        <?php  echo elgg_view('profile/details'); ?>
    </div>
</div>

<div class="profile elgg-col-3of3">
    
    <div class="gcconnex-profile-wire-post">
        <?php $user = get_user(elgg_get_page_owner_guid());
            $params = array(
                'type' => 'object',
                'subtype' => 'thewire',
                'owner_guid' => $user->guid,
                'limit' => 1,
            );
        $latest_wire = elgg_get_entities($params);
        if ($latest_wire && count($latest_wire) > 0) {
            //echo '<img class="profile-icons double-quotes" src="' . elgg_get_site_url() . 'mod/pessek_profile/img/double-quotes.png">';
           // echo elgg_view("profile/status", array("entity" => $user));
        }
        ?>
    </div>
    
    <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
    <!--
        <div class="btn-group" role="group">
            <button type="button" id="stars" class="btn btn-primary" href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                <div class="hidden-xs"><?php //echo elgg_echo('gcconnex_profile:widgets'); ?></div>
            </button>
        </div> 
    -->
        <div class="btn-group" role="group">
            <button type="button" id="profile" class="btn btn-primary" href="#tab2" data-toggle="tab"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                <div class="hidden-xs"><?php echo elgg_echo('gcconnex_profile:profile'); ?></div>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" id="language" class="btn btn-default" href="#tab3" data-toggle="tab"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                <div class="hidden-xs"><?php echo elgg_echo('gcconnex_profile:portfolio:language'); ?></div>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" id="publication" class="btn btn-default" href="#tab4" data-toggle="tab"><span class="glyphicon glyphicon-object-align-right" aria-hidden="true"></span>
                <div class="hidden-xs"><?php echo elgg_echo('gcconnex_profile:projects:publication'); ?></div>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" id="experience" class="btn btn-default" href="#tab5" data-toggle="tab"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>
                <div class="hidden-xs"><?php echo elgg_echo('gcconnex_profile:experience'); ?></div>
            </button>
        </div>
    </div>

    <div class="well">
      <div class="tab-content">
   <!--
        <div class="tab-pane fade in active" id="tab1">
          <h3>This is tab 78945632 </h3>
        </div>
    -->
        <div class="tab-pane fade in active" id="tab2">
<?php
            if ( has_content($user, 'description') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:about_me'), 'about-me', $user);
                echo elgg_view('pessek_profile/about-me');
                finit_ajax_block('about-me');
            }
            
            if ( has_content($user, 'skills') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:gc_skills'), 'skills', $user);
                echo elgg_view('pessek_profile/skills');
                finit_ajax_block('skills');
            }

            if ( has_content($user, 'education') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:education'), 'education', $user);
                echo elgg_view('pessek_profile/education');
                finit_ajax_block('education');
            }
            
            if ( has_content($user, 'mooc') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:pessek:mocc'), 'mooc', $user);
                echo elgg_view('pessek_profile/mooc');
                finit_ajax_block('mooc');
            }
            
            if ( has_content($user, 'certification') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:certification'), 'certification', $user);
                echo elgg_view('pessek_profile/certification');
                finit_ajax_block('certification');
            }

?>
        </div>
        <div class="tab-pane fade in" id="tab3">
<?php
            if ( has_content($user, 'portfolio') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:portfolio'), 'portfolio', $user);
                echo elgg_view('pessek_profile/portfolio'); // call the proper view for the section
                finit_ajax_block('portfolio');
                
            }
            
            if ( has_content($user, 'languages')) {
                init_ajax_block(elgg_echo('gcconnex_profile:langs'), 'languages', $user);
                echo elgg_view('pessek_profile/languages');
                finit_ajax_block('languages');
            }
?>
        </div>
        <div class="tab-pane fade in" id="tab4">
<?php
            if ( has_content($user, 'projects') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:projects:lib'), 'projects', $user);
                echo elgg_view('pessek_profile/projects');
                finit_ajax_block('projects');
            }
                
            if ( has_content($user, 'publications') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:publications:lib'), 'publications', $user);
                echo elgg_view('pessek_profile/publications');
                finit_ajax_block('publications');
            }
?>
        </div>
        <div class="tab-pane fade in" id="tab5">
<?php
            if ( has_content($user, 'experiences') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:experience:lib'), 'experience', $user);
                echo elgg_view('pessek_profile/experiences');
                finit_ajax_block('experiences');
            }
                
            if ( has_content($user, 'volunteers') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:volunteer:lib'), 'experience', $user);
                echo elgg_view('pessek_profile/volunteers');
                finit_ajax_block('volunteers');
            }
            
            if ( has_content($user, 'internships') ) {
                init_ajax_block(elgg_echo('gcconnex_profile:internship:lib'), 'experience', $user);
                echo elgg_view('pessek_profile/internships');
                finit_ajax_block('internships');
            }

?>
        </div>
      </div>
    </div>
    
</div>

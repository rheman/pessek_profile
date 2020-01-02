<?php
    
    elgg_ajax_gatekeeper();
    
    $user_guid = get_input('guid');

    $skill_guid = get_input('skill');
    $skills = get_entity($skill_guid);

    if ($skills->endorsements == NULL) {
        $skills->endorsements = $user_guid;
    }
    else {
        $stack = $skills->endorsements;
        if (!(is_array($stack))) { $stack = array($stack); }

        $stack[] = $user_guid;
        $skills->endorsements = $stack;
    }

    // Subject of the notification
    
    $owner = get_user($skills->owner_guid);
    $user = get_user($user_guid);
    
    $owner_link = elgg_view('output/url', [
                        'text' => $owner->getDisplayName(),
                        'href' => $owner->getURL(),
                                ]);
                                
    $user_link = elgg_view('output/url', [
                        'text' => $user->getDisplayName(),
                        'href' => $user->getURL(),
                                ]);
                                
    $user_links = elgg_view('output/url', [
                        'text' => $user->getDisplayName()."'s",
                        'href' => $user->getURL(),
                                ]);
    $skills_title = elgg_view('output/url', [
                        'text' => $skills->title,
                        'href' => '#',
                                ]);
    
    $subject = elgg_echo('gcconnex_profile:skills:endorse:skills:subject:add_endorsement', [$user->name, $skills->title], $owner->language);
    
    $body = elgg_echo('gcconnex_profile:skills:endorse:skills:body:add_endorsement', [$owner_link, $user_link, $skills_title, $owner->getURL(), $user_links, $user->getURL()], $owner->language);
    
    // Summary of the notification
    
    //$summary = $user_link .' '. elgg_echo('gcconnex_profile:skills:endorse:skills:summary', array($user->name), $owner->language);
    $params = array(
                'object' => $skills,
                'action' => 'update',
                //'summary' => $summary
                );
                
    $skills->save();
    
    notify_user($owner->getGUID(),  $user->getGUID(), $subject, $body, $params);
    

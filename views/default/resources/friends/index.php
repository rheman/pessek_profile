<?php
/**
 * Elgg friends page
 *
 * @package Elgg.Core
 * @subpackage Social.Friends
 */

$owner = elgg_get_page_owner_entity();

$title = elgg_echo("friends:owned", array($owner->name));

$dbprefix = elgg_get_config('dbprefix');
$options = array(
	'relationship' => 'friend',
	'relationship_guid' => $owner->getGUID(),
	'inverse_relationship' => false,
	'type' => 'user',
	'joins' => array("JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"),
	'order_by' => 'ue.name ASC',
	'full_view' => false,
	'no_results' => elgg_echo('friends:none'),
);
$content = elgg_list_entities_from_relationship($options);

//pessek
    $contenu = ' ';
    $my_friends = elgg_get_entities_from_relationship($options);
    foreach($my_friends as $val){
        $USer = get_user($val->guid);
        $user_displayname = ucwords($USer->getDisplayName());
                          $a = '&nbsp;';  
        $contenu .= '
        <div class="col-lg-4">
            <div style="margin: auto;text-align:center;">
                <a href="'.$USer->getURL().'"> <img class="umedia-object dp img-circle" style="width: 100px;height:100px;" src="'.$USer->getIconURL('medium').'"></a>
                <div class="media-body">
                    <h4 class="media-heading"> <a href="'.$USer->getURL().'">'. $user_displayname. ' </a></h4>
                    </div>
                <hr style="margin:3px auto">
            </div>
        ';
        $contenu .= '</div>';
    }
//pessek


$params = array(
	'content' => $contenu, //pessek old was $content
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
<?php
namespace Pessek\PessekProfile;
use Elgg\Hook;
use ElggMenuItem;

class PessekProfileConfig {
	
	/**
	 * Add menu items to the topbar
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value the current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function pessek_profile_config_user_site(Hook $hook) {
		$return_value = $hook->getValue();
	
		$return_value['pessek_profile']['showmore'] = elgg_echo('pessek_profile:education:showmore');
    		$return_value['pessek_profile']['showless'] = elgg_echo('pessek_profile:education:showless');

    		return $return_value;
	}

	public static function pessek_profile_config_user_page(Hook $hook) {
		$return_value = $hook->getValue();
		
		$return_value['pessek_profile']['showmore'] = elgg_echo('pessek_profile:education:showmore');
    		$return_value['pessek_profile']['showless'] = elgg_echo('pessek_profile:education:showless');

    		return $return_value;
	}

	public static function pessek_profile_fields_plugin_handler(Hook $hook) {
		$return_value = $hook->getValue();
		
		$return_value['countryoforigin'] = 'text';
		$return_value['countryofresidence'] = 'text';
	
		$return_value['anneeacademique'] = 'text';
		$return_value['masteresfam'] = 'text';

    		return $return_value;
	}

	public static function site_menu(Hook $hook) {
		$return_value = $hook->getValue();
		
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return;
		}

		$elgg_username = $user->username;

		$return_value[] = \ElggMenuItem::factory([
			'name' => 'all_notification_pessek',
			'text' => elgg_echo('pessek_profile:notification:all'),
			'icon' => 'bell',
			'href' => elgg_get_site_url().'notifications/all',
			'section' => 'alt',
			'parent_name' => 'account',
	       ]);

		$return_value[] = \ElggMenuItem::factory([
			'name' => 'friend_request_pessek',
			'text' => elgg_echo('pessek_profile:friend:request'),
			'icon' => 'user',
			'href' => elgg_get_site_url().'friend_request/'.$elgg_username,
			'section' => 'alt',
			'parent_name' => 'account',
	       ]);

		$return_value[] = \ElggMenuItem::factory([
			'name' => 'my_profile_pessek',
			'text' => elgg_echo('pessek_profile:my_profile:lib'),
			'icon' => 'user',
			'href' => elgg_get_site_url().'profile/'.$elgg_username,
			'section' => 'alt',
			'parent_name' => 'account',
	       ]);

		return $return_value;
	}

	public static function extended_profile_page_handler($page) {

	    if (isset($page[0])) {
		$username = $page[0];//register_error($username);
		$user = get_user_by_username($username); $a = (string) $user->guid;
		elgg_set_page_owner_guid($user->guid); $pessek_profile_page_owner_guid =$a; //register_error($pessek_profile_page_owner_guid);
	    } elseif (elgg_is_logged_in()) {
		forward(elgg_get_logged_in_user_entity()->getURL());
	    }

	    // short circuit if invalid or banned username
	    if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
		register_error(elgg_echo('profile:notfound'));
		forward();
	    }

	    $action = NULL;
	    if (isset($page[1])) {
		$action = $page[1];
	    }

	    if ($action == 'edit') {
		// use the core profile edit page
		$base_dir = elgg_get_root_path();
		//require "{$base_dir}pages/profile/edit.php";
		forward(elgg_get_logged_in_user_entity()->getURL());
		//return true;
	    }

	    // main profile page
	    $params = array(
		'content' => elgg_view('profile/wrapper'),
		'num_columns' => 3,
	    );
	    /*$content = elgg_view_layout('profile_widgets', $params);
	    $body = elgg_view_layout('one_column', array('content' => $content));
	    echo elgg_view_page($user->name, $body);
	    return true;*/
	    
	    $area2 = elgg_view('profile/wrapper');
	    $sidebar = elgg_view('profile/sidebar', array('entity' => $user)); //for test
	    //$body = elgg_view_layout('content', array('content' => $area2, 'filter' => '', 'title' => '', 'sidebar' => $sidebar)); //for test
	    $body = elgg_view_layout('content', array('content' => $area2, 'filter' => '', 'title' => '', 'sidebar' => ''));
	    echo elgg_view_page(elgg_echo('gcconnex_profile:profile:professional'), $body);
	    return true;
	    
	}

	public static function linkedin_by_pessek(){
		
		echo elgg_view_resource('linkedin_by_pessek');
		
	}

	public static function userfind_page_handler() {
	    //$user_friends = elgg_get_entities_from_relationship(array('guid' => elgg_get_logged_in_user_guid()));

	    $user_friends = get_user_friends(elgg_get_logged_in_user_guid(), null, 0);

	    //$user = elgg_get_logged_in_user_entity();
	    //$user_friends = $user->getFriends(['limit' => 10]);
	    $query = htmlspecialchars($_GET['query']);
	    $result = array();

	    foreach ($user_friends as $u) {

		// Complete match for first name
		if (strpos(strtolower(' ' . $u->getDisplayName()) . ' ', ' ' . strtolower($query) . ' ') === 0 ) {
		    $result[] = array(
		        'value' => $u->getDisplayName(),
		        'guid' => $u->guid,
		        'pic' => elgg_view_entity_icon($u, 'tiny', array(
		            'use_hover' => false,
		            'href' => false)),
		        'avatar' => elgg_view_entity_icon($u, 'small', array(
		            'use_hover' => false,
		            'href' => false)),
		        'pos' => 0
		    );
		    //error_log('Result1: ' . var_dump($result));
		}

		// Complete match for name (first, middle or last)
		elseif (strpos(strtolower(' ' . $u->getDisplayName()) . ' ', ' ' . strtolower($query) . ' ') !== FALSE) {
		    $result[] = array(
		        'value' => $u->getDisplayName(),
		        'guid' => $u->guid,
		        'pic' => elgg_view_entity_icon($u, 'tiny', array(
		            'use_hover' => false,
		            'href' => false)),
		        'avatar' => elgg_view_entity_icon($u, 'small', array(
		            'use_hover' => false,
		            'href' => false)),
		        'pos' => 1
		    );
		    //error_log('Result-2: ' . var_dump($result));
		    //error_log('Result-2: ' . $u->getDisplayName());
		   //error_log('Result-2: ' . $u->guid);
		}

		// Partial match beginning at start of first name
		elseif (strpos(strtolower(' ' . $u->getDisplayName()), ' ' . strtolower($query)) === 0 ) {
		    $result[] = array(
		        'value' => $u->getDisplayName(),
		        'guid' => $u->guid,
		        'pic' => elgg_view_entity_icon($u, 'tiny', array(
		            'use_hover' => false,
		            'href' => false)),
		        'avatar' => elgg_view_entity_icon($u, 'small', array(
		            'use_hover' => false,
		            'href' => false)),
		        'pos' => 2
		    );
		    //error_log('Result3: ' . var_dump($result));
		}

		// Partial match beginning at start of some name (middle, last)
		elseif (strpos(strtolower(' ' . $u->getDisplayName()), ' ' . strtolower($query)) !== FALSE) {
		    $result[] = array(
		        'value' => $u->getDisplayName(),
		        'guid' => $u->guid,
		        'pic' => elgg_view_entity_icon($u, 'tiny', array(
		            'use_hover' => false,
		            'href' => false)),
		        'avatar' => elgg_view_entity_icon($u, 'small', array(
		            'use_hover' => false,
		            'href' => false)),
		        'pos' => 3
		    );
		    //error_log('Result4: ' . var_dump($result));
		}

		// Partial match somewhere within some name
		elseif (strpos(strtolower($u->getDisplayName()), strtolower($query)) !== FALSE) {
		    $result[] = array(
		        'value' => $u->getDisplayName(),
		        'guid' => $u->guid,
		        'pic' => elgg_view_entity_icon($u, 'tiny', array(
		            'use_hover' => false,
		            'href' => false)),
		        'avatar' => elgg_view_entity_icon($u, 'small', array(
		            'use_hover' => false,
		            'href' => false)),
		        'pos' => 4
		    );
		    //error_log('Result5: ' . var_dump($result));
		}
	    }

	    $highest_relevance = array();
	    $high_relevance = array();
	    $med_relevance = array();
	    $low_relevance = array();
	    $lowest_relevance = array();

	    foreach ( $result as $r ) {
		if ( $r['pos'] == 0 ) {
		    $highest_relevance[] = $r;
		}
		elseif ( $r['pos'] == 1 ) {
		    $high_relevance[] = $r;
		}
		elseif ( $r['pos'] == 2 ) {
		    $med_relevance[] = $r;
		}
		elseif ( $r['pos'] == 3 ) {
		    $low_relevance[] = $r;
		}
		elseif ( $r['pos'] == 4 ) {
		    $lowest_relevance[] = $r;
		}
	    }

	    $result = array_merge($highest_relevance, $high_relevance, $med_relevance, $low_relevance, $lowest_relevance);
	   //error_log(json_encode($result));
	    //error_log(print_r('Result: ' . $result, TRUE));
	    //error_log(print_r($med_relevance, true));
	   echo json_encode($result, JSON_FORCE_OBJECT);
	   return json_encode($result);
	}

}


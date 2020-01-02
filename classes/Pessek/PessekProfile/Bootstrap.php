<?php
namespace Pessek\PessekProfile;
use Elgg\Includer;
use Elgg\PluginBootstrap;

class Bootstrap extends PluginBootstrap {
	
	/**
	 * Get plugin root
	 * @return string
	 */
	protected function getRoot() {
		return $this->plugin->getPath();
	}
	
	public function load() {
		Includer::requireFileOnce($this->getRoot() . '/lib/functions.php');
	}
	/**
	 * {@inheritdoc}
	 */
	public function boot() {
	}
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		//Menus
		$hooks = $this->elgg()->hooks;

		$hooks->registerHandler('elgg.data', 'site', '\Pessek\PessekProfile\PessekProfileConfig::pessek_profile_config_user_site');
		$hooks->registerHandler('elgg.data', 'page', '\Pessek\PessekProfile\PessekProfileConfig::pessek_profile_config_user_page');

		elgg_register_entity_type("object", "skills");
		elgg_register_entity_type("object", "education");

		$hooks->registerHandler('profile:fields', 'profile', '\Pessek\PessekProfile\PessekProfileConfig::pessek_profile_fields_plugin_handler');
		$hooks->registerHandler('register', 'menu:topbar', '\Pessek\PessekProfile\PessekProfileConfig::site_menu');

		$this->registerViews();
		$this->registerActions();

		elgg_register_page_handler('userfind', '\Pessek\PessekProfile\PessekProfileConfig::userfind_page_handler');
		elgg_register_page_handler('linkedin_by_pessek', '\Pessek\PessekProfile\PessekProfileConfig::linkedin_by_pessek');
		elgg_register_page_handler('profile', '\Pessek\PessekProfile\PessekProfileConfig::extended_profile_page_handler');
		
		$hooks->registerHandler('cron', 'minute', '\Pessek\PessekProfile\Cron::send_notifications_after_sharing');

		/*
		$hooks->registerHandler('register', 'menu:topbar', '\ColdTrick\FriendRequest\TopbarMenu::register');
		$hooks->registerHandler('register', 'menu:page', '\ColdTrick\FriendRequest\PageMenu::registerCleanup', 900);
		$hooks->registerHandler('register', 'menu:page', '\ColdTrick\FriendRequest\PageMenu::register');
		$hooks->registerHandler('register', 'menu:entity', '\ColdTrick\FriendRequest\Users::registerEntityMenu');
		*/
		// Events
		//$this->elgg()->events->unregisterHandler('create', 'relationship', '_elgg_send_friend_notification');
		//$this->elgg()->events->registerHandler('create', 'relationship', '\ColdTrick\FriendRequest\Relationships::createFriendRequest');
	}

	protected function registerActions() {

		$action_path = elgg_get_plugins_path() . 'pessek_profile/actions/pessek_profile/';
    		elgg_register_action('pessek_profile/edit_profile', $action_path . 'edit_profile.php');
    		elgg_register_action('pessek_profile/education/add', $action_path . 'education.php');
    		elgg_register_action('pessek_profile/portfolio/add', $action_path . 'portfolio.php');
    		elgg_register_action('pessek_profile/certification/add', $action_path . 'certification.php');
    		elgg_register_action('pessek_profile/mooc/add', $action_path . 'mooc.php');
    		elgg_register_action('pessek_profile/publications/add', $action_path . 'publications.php');
    		elgg_register_action('pessek_profile/projects/add', $action_path . 'projects.php');
    		elgg_register_action('pessek_profile/experience/add', $action_path . 'experience.php');
    		elgg_register_action('pessek_profile/languages/add', $action_path . 'languages.php');
    		elgg_register_action('pessek_profile/skills/add', $action_path . 'skills.php');
    		elgg_register_action('pessek_profile/endorsements/add', $action_path . 'add_endorsement.php');
 
    		elgg_register_action('pessek_profile/basic/add', $action_path . 'basic.php');
    
    		elgg_register_action('pessek_profile/description/add', $action_path . 'description.php');
    
    		//elgg_register_action('portfolio', $action_path . 'portfolio.php');
    		elgg_register_action('pessek_profile/education/delete', $action_path . 'delete_education.php');
    		elgg_register_action('pessek_profile/portfolio/delete', $action_path . 'delete_portfolio.php');
    		elgg_register_action('pessek_profile/certification/delete', $action_path . 'delete_certification.php');
    		elgg_register_action('pessek_profile/mooc/delete', $action_path . 'delete_mooc.php');
    		elgg_register_action('pessek_profile/publications/delete', $action_path . 'delete_publications.php');
    		elgg_register_action('pessek_profile/projects/delete', $action_path . 'delete_projects.php');
    		elgg_register_action('pessek_profile/internships/delete', $action_path . 'delete_internships.php');
    		elgg_register_action('pessek_profile/volunteers/delete', $action_path . 'delete_volunteers.php');
    		elgg_register_action('pessek_profile/experiences/delete', $action_path . 'delete_experiences.php');
    		elgg_register_action('pessek_profile/languages/delete', $action_path . 'delete_languages.php');
    		elgg_register_action('pessek_profile/skillssentry/delete', $action_path . 'delete_skillssentry.php');
    		elgg_register_action('pessek_profile/endorsements/delete', $action_path . 'retract_endorsements.php');
    		elgg_register_action('pessek_profile/description/delete', $action_path . 'delete_description.php');
    
    
    		elgg_register_action('pessek_profile/user_find', $action_path . 'userfind.php', "public");
	}

	protected function registerViews() {
		//elgg_extend_view('admin.css', 'css/group_tools/admin.css');
		
		$gcconnex_url = elgg_get_site_url() . "mod/pessek_profile/js/endorsements/";
		elgg_register_js('gcconnex-profile', $gcconnex_url . "gcconnex-profile.js");

		//pessek
		elgg_register_js('pessek-portfolio', $gcconnex_url . "portfolio.js");
		elgg_register_js('jquery-confirm', $gcconnex_url . "jquery-confirm.min.js");
		elgg_register_js('basicprofile', $gcconnex_url . "basic-profile.js");
		elgg_register_js('readmore', $gcconnex_url . "jquery.morelines.js");

		$url = 'mod/pessek_profile/vendors/';

		elgg_register_js('fancybox', 'vendors/jquery/fancybox/jquery.fancybox-1.3.4.pack.js');
		elgg_register_js('typeahead', $url . 'typeahead/dist/typeahead.bundle.min.js');

		$css_url = 'mod/pessek_profile/css/gcconnex-profile.css';
		elgg_register_css('gcconnex-css', $css_url);

		//pessek
		$css_jquery_confirm = 'mod/pessek_profile/css/jquery-confirm.min.css';
		elgg_register_css('jquery-confirm-css', $css_jquery_confirm);

    
    		$bower_path = elgg_get_site_url() . "mod/pessek_profile/bower_components/";
   
    		elgg_register_js("bootstrap", $bower_path . "bootstrap/dist/js/bootstrap.min.js");
    		/*elgg_load_js("bootstrap");*/
    
    		elgg_register_js("jqueryusertimeout", $bower_path . "jqueryusertimeout/jquery.userTimeout.js");
    		elgg_register_js("usertimeout", $bower_path . "jqueryusertimeout/userTimeout.js");
     
    		elgg_register_css("bootstrap", $bower_path . "bootstrap/dist/css/bootstrap.css", 0);

	    	if (elgg_is_logged_in()) {
	    
			elgg_define_js('jqueryusertimeout', [
		                'src' => 'mod/pessek_profile/bower_components/jqueryusertimeout/jquery.userTimeout.js',
		                'exports' => 'jqueryusertimeout',
		        ]);

			elgg_define_js('usertimeout', [
		            	'src' => 'mod/pessek_profile/bower_components/jqueryusertimeout/userTimeout.js',
		            	'deps' => ['jqueryusertimeout'],
		            	'exports' => 'usertimeout',
		    	]);
		
		    
			elgg_require_js('jqueryusertimeout'); 
			elgg_require_js('usertimeout'); 
	    	}
		
	    	elgg_register_ajax_view('pessek_profile/about-me');
	    	elgg_register_ajax_view('pessek_profile/education');
	   	elgg_register_ajax_view('pessek_profile/certification');
	  	elgg_register_ajax_view('pessek_profile/mooc');
	   	elgg_register_ajax_view('pessek_profile/portfolio');
	    	elgg_register_ajax_view('pessek_profile/projects');
	   	elgg_register_ajax_view('pessek_profile/publications');
	    	elgg_register_ajax_view('pessek_profile/experience');
	    	elgg_register_ajax_view('pessek_profile/internships');
	    	elgg_register_ajax_view('pessek_profile/volunteers');
	    	elgg_register_ajax_view('pessek_profile/experiences');
	    	elgg_register_ajax_view('pessek_profile/languages');
	    	elgg_register_ajax_view('pessek_profile/skills');

    		elgg_register_ajax_view('pessek_profile/edit_basic');
    		elgg_register_ajax_view('pessek_profile/retrieve_user');

		// edit views
    		elgg_register_ajax_view('pessek_profile/edit_about-me');
    		elgg_register_ajax_view('pessek_profile/edit_education');
    		elgg_register_ajax_view('pessek_profile/edit_certification');

    		// input views
   		elgg_register_ajax_view('input/education');
    		elgg_register_ajax_view('input/certification');

	    	//modal
	    	elgg_register_ajax_view('forms/pessek_profile/education'); 
	    	elgg_register_ajax_view('forms/pessek_profile/certification');
	   	elgg_register_ajax_view('forms/pessek_profile/mooc');
	    	elgg_register_ajax_view('forms/pessek_profile/portfolio');
	    	elgg_register_ajax_view('forms/pessek_profile/projects');
	    	elgg_register_ajax_view('forms/pessek_profile/publications');
	    	elgg_register_ajax_view('forms/pessek_profile/experience');
	    	elgg_register_ajax_view('forms/pessek_profile/languages');
	    	elgg_register_ajax_view('forms/pessek_profile/skills');
	    	elgg_register_ajax_view('forms/pessek_profile/list_avatars');
	    	elgg_register_ajax_view('forms/pessek_profile/edit_basic');
	    	elgg_register_ajax_view('forms/pessek_profile/about-me');
	    	elgg_register_ajax_view('profile/details');
	}
	/**
	 * {@inheritdoc}
	 */
	public function ready() {
		elgg_unregister_plugin_hook_handler('register', 'menu:user_hover', '_elgg_friends_setup_user_hover_menu');
		elgg_register_plugin_hook_handler('register', 'menu:user_hover', '\ColdTrick\FriendRequest\Users::registerUserHoverMenu');
	}
	/**
	 * {@inheritdoc}
	 */
	public function shutdown() {
	}
	/**
	 * {@inheritdoc}
	 */
	public function activate() {
	}
	/**
	 * {@inheritdoc}
	 */
	public function deactivate() {
	}
	/**
	 * {@inheritdoc}
	 */
	public function upgrade() {
	}
}

<?php
namespace Pessek\PessekProfile;
use Elgg\Hook;

class Cron {
	
	public static function send_notifications_after_sharing(Hook $hook) {
		
		echo "Starting sending Notifications to friend when sharing changes" . PHP_EOL;
		elgg_log("Starting sending Notifications to friend when sharing changes", 'NOTICE');

     
		$pessek_profile_field = ["description", "skills", "publications", "projects", "portfolio", "mooc", "languages", "internships", "volunteers", "experiences", "education", "certification", "user"];

		foreach ($pessek_profile_field as $value) {
          
    			$pessek_entity = elgg_get_entities([
				'type' => 'object',
				'subtype' => $value,
				'limit' => false,
				'metadata_name_value_pairs' => [
					'name' => 'sharingNotify',
					'value' => '1',
				],
				'batch' => true,
			]);
			if($value == "user"){
				$value = "profile";
			}
			foreach ($pessek_entity as $the_entity) {
				$owner = $the_entity->getOwnerEntity();
				$user_friends = get_user_friends($owner->getGUID(), null, 0);
				foreach ($user_friends as $u) {
					$subject = elgg_echo('gcconnex_profile:about_me:summary:update:subject', [$owner->getDisplayName(), $value]);
					$message = elgg_echo('gcconnex_profile:about_me:summary:update', [$owner->getDisplayName(), elgg_echo('gcconnex_profile:profile'), $owner->getURL()]);
					$notification_params = array(
                				'object' => $the_entity,
                				'action' => 'create'
                			);
				notify_user($u->guid, $owner->getGUID(), $subject, $message, $notification_params);
				}
				$the_entity->sharingNotify = "0";
			}
                 }
		echo "Finishing sending Notifications to friend when sharing changes" . PHP_EOL;
		elgg_log("Finishing sending Notifications to friend when sharing changes", 'NOTICE');
	}
}

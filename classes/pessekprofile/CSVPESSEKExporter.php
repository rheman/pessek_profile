<?php

namespace pessekprofile;

class CSVPESSEKExporter {
	
	/**
	 * Add last editor information to static export
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function pessek_profile($hook, $type, $return_value, $params) {
		
		if (elgg_extract('subtype', $params)) {
			return;
		}
		
		$values = [
			elgg_echo('gcconnex_profile:education') => 'education',
			elgg_echo('gcconnex_profile:pessek:mocc') => 'mooc',
			elgg_echo('gcconnex_profile:certification') => 'certification',
			elgg_echo('item:object:skills') => 'skills',
			elgg_echo('gcconnex_profile:portfolio') => 'portfolio',
			elgg_echo('gcconnex_profile:languages:language:skills') => 'languages',
			elgg_echo('gcconnex_profile:projects:lib') => 'projects',
			elgg_echo('gcconnex_profile:publications:lib') => 'publications',
			elgg_echo('gcconnex_profile:internship:lib:lib') => 'internships',
			elgg_echo('gcconnex_profile:volunteer:volunteer') => 'volunteers',
			elgg_echo('gcconnex_profile:experience') => 'experiences',
		];
		
		if (!(bool) elgg_extract('readable', $params)) {
			$values = array_values($values);
		}
		
		return array_merge($return_value, $values);
	}
	
	public static function exportLanguages($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		$language_level = array(
                        '0' => elgg_echo('gcconnex_profile:langs:elementory'),
                        '1' => elgg_echo('gcconnex_profile:langs:limited:working'),
                        '2' => elgg_echo('gcconnex_profile:langs:professional:working'),
                        '3' => elgg_echo('gcconnex_profile:langs:full:professional'),
                        '4' => elgg_echo('gcconnex_profile:langs:native'));
		
		switch ($exportable_value) {
			case 'languages':
                            
                            $userLanguages ='';
                            $i = 1;
                            $languages_guid = $entity->languages;
                            
                            if ($languages_guid == NULL || empty($languages_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($languages_guid))) {
                                    $languages_guid = array($languages_guid);
                                }
                                usort($languages_guid, "sortDate");
                                foreach ($languages_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userLanguages = $i.'- '.html_entity_decode($entry->langs).', '.html_entity_decode($language_level[$entry->level]);
                                        $i++;
                                        
                                    }else{
                                        $userLanguages = $userLanguages.', '.$i.'- '.html_entity_decode($entry->langs).', '.html_entity_decode($language_level[$entry->level]);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userLanguages;
                            break;
		}
	}
	
	public static function exportVolunteers($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$month_selected = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'volunteers':
                            
                            $userInternships ='';
                            $i = 1;
                            $internships_guid = $entity->volunteers;
                            
                            if ($internships_guid == NULL || empty($internships_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($internships_guid))) {
                                    $internships_guid = array($internships_guid);
                                }
                                usort($internships_guid, "sortDate");
                                foreach ($internships_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userInternships = $i.'- '.html_entity_decode($entry->jobtitle).', '.html_entity_decode($entry->companyname).', '.html_entity_decode($entry->country).', '.html_entity_decode($entry->place).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear).' - '.html_entity_decode($month_selected[$entry->endmonth]).'/'.html_entity_decode($entry->endyear);
                                        $i++;
                                        
                                    }else{
                                        $i.'- '.html_entity_decode($entry->jobtitle).', '.html_entity_decode($entry->companyname).', '.html_entity_decode($entry->country).', '.html_entity_decode($entry->place).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear).' - '.html_entity_decode($month_selected[$entry->endmonth]).'/'.html_entity_decode($entry->endyear);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userInternships;
                            break;
		}
	}
	
	public static function exportExperiences($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$month_selected = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'experiences':
                            
                            $userInternships ='';
                            $i = 1;
                            $internships_guid = $entity->experiences;
                            
                            if ($internships_guid == NULL || empty($internships_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($internships_guid))) {
                                    $internships_guid = array($internships_guid);
                                }
                                usort($internships_guid, "sortDate");
                                foreach ($internships_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userInternships = $i.'- '.html_entity_decode($entry->jobtitle).', '.html_entity_decode($entry->companyname).', '.html_entity_decode($entry->country).', '.html_entity_decode($entry->place).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear).' - '.html_entity_decode($month_selected[$entry->endmonth]).'/'.html_entity_decode($entry->endyear);
                                        $i++;
                                        
                                    }else{
                                        $i.'- '.html_entity_decode($entry->jobtitle).', '.html_entity_decode($entry->companyname).', '.html_entity_decode($entry->country).', '.html_entity_decode($entry->place).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear).' - '.html_entity_decode($month_selected[$entry->endmonth]).'/'.html_entity_decode($entry->endyear);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userInternships;
                            break;
		}
	}
	
	public static function exportInternships($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$month_selected = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'internships':
                            
                            $userInternships ='';
                            $i = 1;
                            $internships_guid = $entity->internships;
                            
                            if ($internships_guid == NULL || empty($internships_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($internships_guid))) {
                                    $internships_guid = array($internships_guid);
                                }
                                usort($internships_guid, "sortDate");
                                foreach ($internships_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userInternships = $i.'- '.html_entity_decode($entry->jobtitle).', '.html_entity_decode($entry->companyname).', '.html_entity_decode($entry->country).', '.html_entity_decode($entry->place).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear).' - '.html_entity_decode($month_selected[$entry->endmonth]).'/'.html_entity_decode($entry->endyear);
                                        $i++;
                                        
                                    }else{
                                        $i.'- '.html_entity_decode($entry->jobtitle).', '.html_entity_decode($entry->companyname).', '.html_entity_decode($entry->country).', '.html_entity_decode($entry->place).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear).' - '.html_entity_decode($month_selected[$entry->endmonth]).'/'.html_entity_decode($entry->endyear);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userInternships;
                            break;
		}
	}
	
	public static function exportPublications($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$month_selected = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'publications':
                            
                            $userPublications ='';
                            $i = 1;
                            $publications_guid = $entity->publications;
                            
                            if ($publications_guid == NULL || empty($publications_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($publications_guid))) {
                                    $publications_guid = array($publications_guid);
                                }
                                usort($publications_guid, "sortDate");
                                foreach ($publications_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userPublications = $i.'- '.html_entity_decode($entry->thetitle).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear);
                                        $i++;
                                        
                                    }else{
                                        $userPublications = $userPublications.', '.$i.'- '.html_entity_decode($entry->thetitle).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userPublications;
                            break;
		}
	}
	
	public static function exportProjects($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$month_selected = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'projects':
                            
                            $userProjects ='';
                            $i = 1;
                            $projects_guid = $entity->projects;
                            
                            if ($projects_guid == NULL || empty($projects_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($projects_guid))) {
                                    $projects_guid = array($projects_guid);
                                }
                                usort($projects_guid, "sortDate");
                                foreach ($projects_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userProjects = $i.'- '.html_entity_decode($entry->thetitle).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear);
                                        $i++;
                                        
                                    }else{
                                        $userProjects = $userProjects.', '.$i.'- '.html_entity_decode($entry->thetitle).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userProjects;
                            break;
		}
	}
	
	public static function exportSkills($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'skills':
                            
                            $userSkills ='';
                            $i = 1;
                            $skills_guid = $entity->skills;
                            
                            if ($skills_guid == NULL || empty($skills_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($skills_guid))) {
                                    $skills_guid = array($skills_guid);
                                }
                                usort($skills_guid, "sortSkills");
                                foreach ($skills_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userSkills = html_entity_decode($entry->title);
                                        $i++;
                                        
                                    }else{
                                        $userSkills = $userSkills.', '.html_entity_decode($entry->title);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userSkills;
                            break;
		}
	}
	public static function exportEducation($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'education':
                            
                            $userEducation ='';
                            $i = 1;
                            $education_guid = $entity->education;
                            
                            if ($education_guid == NULL || empty($education_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($education_guid))) {
                                    $education_guid = array($education_guid);
                                }
                                usort($education_guid, "sortDate");
                                foreach ($education_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userEducation = $i.'- '.html_entity_decode($entry->school).': '.html_entity_decode($entry->degree).', '.html_entity_decode($entry->field).', '.html_entity_decode($entry->diploma).', '.html_entity_decode($entry->startyear).' - '.html_entity_decode($entry->endyear);
                                        $i++;
                                        
                                    }else{
                                        $userEducation = $userEducation.', '.$i.'- '.html_entity_decode($entry->school).': '.html_entity_decode($entry->degree).', '.html_entity_decode($entry->field).', '.html_entity_decode($entry->diploma).', '.html_entity_decode($entry->startyear).' - '.html_entity_decode($entry->endyear);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userEducation;
                            break;
		}
	}
	
	public static function exportPortfolio($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$month_selected = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'portfolio':
                            
                            $userPortfolio ='';
                            $i = 1;
                            $portfolio_guid = $entity->portfolio;
                            
                            if ($portfolio_guid == NULL || empty($portfolio_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($portfolio_guid))) {
                                    $portfolio_guid = array($portfolio_guid);
                                }
                                usort($portfolio_guid, "sortDatePortfolio");
                                foreach ($portfolio_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userPortfolio = $i.'- '.html_entity_decode($entry->thetitle).', '.html_entity_decode($entry->startday + 1).'/'.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear);
                                        $i++;
                                        
                                    }else{
                                        $userPortfolio = $userPortfolio.', '.$i.'- '.html_entity_decode($entry->thetitle).', '.html_entity_decode($entry->startday + 1).'/'.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userPortfolio;
                            break;
		}
	}
	
	public static function exportMooc($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$entity = elgg_extract('entity', $params);
        
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$month_selected = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'mooc':
                            
                            $userMocc ='';
                            $i = 1;
                            $mooc_guid = $entity->mooc;
                            
                            if ($mooc_guid == NULL || empty($mooc_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($mooc_guid))) {
                                    $mooc_guid = array($mooc_guid);
                                }
                                usort($mooc_guid, "sortDate");
                                foreach ($mooc_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userMocc = $i.'- '.html_entity_decode($entry->name).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear).' - '.html_entity_decode($month_selected[$entry->endmonth]).'/'.html_entity_decode($entry->endyear);
                                        $i++;
                                        
                                    }else{
                                        $userMocc = $userMocc.', '.$i.'- '.html_entity_decode($entry->name).', '.html_entity_decode($month_selected[$entry->startmonth]).'/'.html_entity_decode($entry->startyear).' - '.html_entity_decode($month_selected[$entry->endmonth]).'/'.html_entity_decode($entry->endyear);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userMocc;
                            break;
		}
	}
	
	public static function exportCertification($hook, $type, $return_value, $params) {
		
		if (!is_null($return_value)) {
			// someone already provided output
			return;
		}
		
		$entity = elgg_extract('entity', $params);
		
		if (!($entity instanceof \ElggUser)) {
			return;
		}
		
		$exportable_value = elgg_extract('exportable_value', $params);
		
		switch ($exportable_value) {
			case 'certification':
                            
                            $userCertification ='';
                            $i = 1;
                            $certification_guid = $entity->certification;
                            
                            if ($certification_guid == NULL || empty($certification_guid)) {
                                break;
                            }
                            else {
                                if (!(is_array($certification_guid))) {
                                    $certification_guid = array($certification_guid);
                                }
                                usort($certification_guid, "sortDate");
                                foreach ($certification_guid as $guid) {
                                    $entry = get_entity($guid);
                                   if($i==1){
                                        $userCertification = $i.'- '.html_entity_decode($entry->name);
                                        $i++;
                                        
                                    }else{
                                        $userCertification = $userCertification.', '.$i.'- '.html_entity_decode($entry->name);
                                        $i++;
                                    }
                                    
                                }
                            }
                            
                            return $userCertification;
                            break;
		}
	}
	
	/**
	 * Export last editor information
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @retrun void|string
	 */
	
}

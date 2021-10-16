<?php

$externaljs = dirname(__FILE__);

return [
	'bootstrap' => \Pessek\PessekProfile\Bootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'skills',
			'class' => PessekSkills::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'education',
			'class' => PessekEducation::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'mooc',
			'class' => PessekMooc::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'certification',
			'class' => PessekCertification::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'portfolio',
			'class' => PessekPortfolio::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'languages',
			'class' => PessekLanguages::class,
			'searchable' => true,
		],
[
			'type' => 'object',
			'subtype' => 'volunteers',
			'class' => PessekVolunteer::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'internships',
			'class' => PessekInternship::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'experiences',
			'class' => PessekExperience::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'publications',
			'class' => PessekPublications::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'projects',
			'class' => PessekProjects::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'description',
			'class' => PessekDescription::class,
			'searchable' => true,
		],

	],
	'views' => [
		'default' => [
			'externaljs/' => $externaljs . '/vendors/',
		],
	],
	'actions' => [
                'pessek_profile/edit_basic' => [],
                'pessek_profile/description' => [],
                'pessek_profile/education' => [],
		'pessek_profile/mooc' => [],
		'pessek_profile/certification' => [],
		'pessek_profile/portfolio' => [],
		'pessek_profile/languages' => [],
		'pessek_profile/projects' => [],
		'pessek_profile/publications' => [],
		'pessek_profile/experience' => [],
		'pessek_profile/skills' => [],
		'pessek_profile/add_endorsement' => [],
		'pessek_profile/retract_endorsements' => [],
		'pessek_profile/delete_resume' => [],
        ],
	'routes' => [
		'edit:user' => [
			'path' => '/profile/{username}/edit',
			'resource' => 'pessek_profile/pessek_profile',
		],
		'view:user' => [
			'path' => '/profile/{username}',
			'resource' => 'pessek_profile/pessek_profile',
		],
		'view:user:redirect' => [
			'path' => '/profile',
			'resource' => 'pessek_profile/redirect',
		],
		'collection:object:pessek_profile:deleteresume' => [
			'path' => '/pessek_profile/delete_resume',
			'resource' => 'pessek_profile/delete_resume',
		],
	]
];

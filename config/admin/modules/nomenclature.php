<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'a2' => array(
		'resources' => array(
			'nomenclature_element_controller' => 'module_controller',
			'nomenclature' => 'module',
		),
		'rules' => array(
			'allow' => array(
				'controller_access_1' => array(
					'role' => 'main',
					'resource' => 'nomenclature_element_controller',
					'privilege' => 'access',
				),
			
				'nomenclature_add_1' => array(
					'role' => 'main',
					'resource' => 'nomenclature',
					'privilege' => 'add',
				),
				'nomenclature_edit_1' => array(
					'role' => 'main',
					'resource' => 'nomenclature',
					'privilege' => 'edit',
				),
			),
			'deny' => array(
			)
		)
	),
);
<?php
return array(
	'view_helpers' => array(
		'invokables' => array(
			'fzyForm' => 'FzyForm\View\Helper\FzyForm',
		),
	),
	'view_manager' => array(
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
);
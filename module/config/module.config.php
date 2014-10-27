<?php
return array(
	'service_manager' => array(
		'invokables' => array(
			'AutoForm\Service\EntityToForm' => 'AutoForm\Service\EntityToForm'
		),
		'factories' => array(
			'doctrine.cli' => 'DoctrineModule\Service\CliFactory',
		),
		'abstract_factories' => array(
			'DoctrineModule' => 'DoctrineModule\ServiceFactory\AbstractDoctrineServiceFactory',
		),
	),
);
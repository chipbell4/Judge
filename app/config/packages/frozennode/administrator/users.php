<?php

return array(
	'title' => 'Users',

	'single' => 'User',

	'model' => 'User',

	'columns' => array(
		'username' => array(
			'title' => 'User'
			),
		'admin' => array(
			'title' => 'Is Admin'
			),
		'judge' => array(
			'title' => 'Is Judge'
			),
		'team' => array(
			'title' => 'Is Team'
			),
		),
	'edit_fields' => array(
		'username' => array(
			'title' => 'Username',
			'type' => 'text'
			),
		'password' => array(
			'title' => 'Password',
			'type' => 'password'
			),
		'admin' => array(
			'title' => 'Is Admin',
			'type' => 'bool'
			),
		'judge' => array(
			'title' => 'Is Judge',
			'type' => 'bool'
			),
		'team' => array(
			'title' => 'Is Team',
			'type' => 'bool'
			),
		)
	);
<?php

return array(
	'title' => 'Problems',

	'single' => 'Problem',

    'model' => 'Judge\Models\Problem',

    'rules' => Judge\Models\Problem::$rules,

	'columns' => array(
		'name' => array(
			'title' => 'Problem Name'
        ),
		'contest' => array(
			'title' => 'Contest',
			'relationship' => 'contest',
			'select' => '(:table).name'
        ),
        'tags' => array(
            'title' => 'Tags',
            'relationship' => 'tags',
            'select' => "GROUP_CONCAT((:table).name, ' ')"
        ),
    ),
	'edit_fields' => array(
		'name' => array(
			'title' => 'Problem Name',
			'type' => 'text'
        ),
		'contest' => array(
			'title' => 'Contest',
			'type' => 'relationship',
			'name_field' => 'name',
        ),
        'tags' => array(
            'title' => 'Tags',
            'type' => 'relationship',
            'name_field' => 'name'
        ),
		'judging_input' => array(
			'title' => 'Judging Input',
			'type' => 'file',
			'location' => storage_path() . "/judging_input/",
        ),
		'judging_output' => array(
			'title' => 'Judging Output',
			'type' => 'file',
			'location' => storage_path() . "/judging_output/",
        ),
        'difficulty' => array(
            'title' => 'Difficulty',
            'type' => 'number',
        ),
    )
);

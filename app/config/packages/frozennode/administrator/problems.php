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
        )
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
			'options_sort_field' => 'starts_at',
			'options_sort_direction' => 'desc',
        ),
		'judging_input' => array(
			'title' => 'Judging Input',
			'type' => 'textarea',
        ),
		'judging_output' => array(
			'title' => 'Judging Output',
			'type' => 'textarea',
        ),
    )
);

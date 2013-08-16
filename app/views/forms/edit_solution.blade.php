@extends('layout')

@section('content')
Solution for {{ $solution->problem->name }} <br/>
Submitted by {{ $solution->user->username }} at {{ $solution->created_at }} <br/>

Submitted in {{$solution->language->name}} <br/>

Download {{ link_to_route('solution_package', 'Solution Package', array($solution->id), array('_target'=>'blank'))}}

{{ Form::open(array('route'=>array("edit_solution", $solution->id))) }}
{{ Form::label('solution_state_id', 'Solution State: ') }}
{{ Form::select('solution_state_id', $solution_states)}}
{{ Form::submit('Save')}}
{{ Form::close() }}

{{ Form::open(array('route'=>array("unclaim_solution", $solution->id)))}}
{{ Form::submit('Unclaim') }}
{{ Form::close() }}

@stop
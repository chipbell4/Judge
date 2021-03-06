@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4">
        <h3>Post Global Message</h3>
        @include('forms.create_judge_message')
    </div>
    <div class="col-md-4">
        <h3>Your Messages</h3>
        @foreach($my_messages as $message)
          @include('Messages.single_message')
        @endforeach
    </div>
    <div class="col-md-4">
        <h3>Unresponded Messages</h3>
        @foreach($unresponded_messages as $message)
            @include('Messages.single_message', ['show_sender' => true, 'allow_response' => true])
        @endforeach
    </div>
</div>
@stop

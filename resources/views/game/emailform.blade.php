@extends('game.base')

@section('css')
    .form-group{
    min-height:60px;
    margin-bottom:0;
    padding: 15px;
    }
@endsection
@section('content')
    <div class="col-xs-12">
        <h3 class="click-hide col-xs-12">
            <i class="fa fa-eye hidden-able" aria-hidden="true"></i>
            <i class="fa fa-eye-slash hidden-able hidden" aria-hidden="true"></i>
                Markdown formatting
        </h3>
        <div class="markdown-referencephp hidden hidden-able">
        </div>
    </div>
    <div class="col-xs-12">
        {{ Form::open(array('id' => "email_getter", 'action' => array('Backend\Manage\EmailController@email_send'), 'class' => 'form-horizontal')) }}
        <div class="form-group">
            <h1 style="text-align: center;">Super awesome email sender.</h1>
            <h4 style="text-align: center;"><strong>“With great power there must also come — great
                    responsibility.”</strong><br/><span> Amazing Fantasy #15 (August 1962) </span><br/>-use the form
                responsibly-</h4>
        </div>

        <div class="form-group">
            <label for="emailList" class="control-label col-xs-3">Email to: </label>
            <div class="col-xs-9 @if(isset($names_get) and $names_get=='') bg-danger @endif">
                <input class="form-control" value="@if(isset($names_get)){{$names_get}}@endif" disabled="disabled">
                <input name="emails" id="emails" class="form-control hidden"
                       value="@if(isset($names_get)){{$names_get}}@endif">
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="control-label col-xs-3">Subject: </label>
            <div class="col-xs-9 @if(isset($user_subject) and $user_subject=='') bg-danger @endif">
                <input type="text" name="subject" id="subject" class="form-control"
                       @if(isset($user_subject)) value="{{$user_subject}}"@endif>
            </div>
        </div>
        <div class="form-group">
            <label for="message" class="control-label col-xs-3">Message: </label>
            <div class="col-xs-9 @if(isset($user_message) and $user_message=='') bg-danger @endif">
                <textarea name="message" id="message" rows="10" class="form-control"
                          value="@if(isset($user_message)){{$user_message}}@endif"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-6">
                {{ Html::link('/manage/email',
                            '<- Go back', array( 'class' => 'btn btn-default form-control')) }}
            </div>
            <div class="col-xs-6">
                {!! Form::submit( 'Send Email', array('class'=>'btn btn-danger list fa fa-search form-control', 'name'=>'send', 'id'=>'send')) !!}
            </div>
        </div>
        <input name="emailList" id="emailList" rows="10" class="hidden" value="@if(isset($ids_get)){{$ids_get}}@endif">

        {{ Form::close() }}
    </div>
@endsection
@section('js')
    $(document).ready(function() {
        $(".click-hide").click(function(){
            $(".hidden-able").toggleClass("hidden");
        });
    });
@endsection
@extends('game.base')

@section('css')
    .form-group{
        min-height:60px;
        margin-bottom:0;
        padding: 15px;
    }
    ul#onlyThisOne:before,
    ul#onlyThisOne:after
    {
        display:none;
    }
    ul#onlyThisOne{
        height:40px!important;
        margin:0!important;
        padding:0!important;
    }
    ul#onlyThisOne li{
        padding:0;
        height:30px!important;
    }
    ul#onlyThisOne li label#preview,
    ul#onlyThisOne li label#compose{
        height:40px!important;
        width:100%;
        border-radius: 4px 4px 0 0;
        border:1px solid #ddd;
        padding: 10px 0 0 0!important;
        margin-top: 0!important;
    }
    ul#onlyThisOne li.not-active{
        {{--padding:0!important;--}}
        {{--border-bottom:0!important;--}}
        {{--margin:0!important;--}}
    }
    ul#onlyThisOne li.not-active label#preview,
    ul#onlyThisOne li.not-active label#compose
    {
        height:40px!important;
        background:#f9f9f9!important;
    }

    ul#onlyThisOne li.active label#preview,
    ul#onlyThisOne li.active label#compose
    {
        height:40px!important;
        border-bottom:0;
        background:#fff!important;
    }
    textarea.form-control,
    .message_lock.disabled{
        padding:10px 15px;
        width:100%;
        border-radius: 4px 4px 0 0;
        border-radius: 0 0 4px 4px;
        border:1px solid #ddd;
        border-top:none!important;
        min-height:200px;
        max-height:200px;
        overflow-y: scroll;
        background:#ffffff!important;
        text-align:left;
    }
@endsection
@section('content')
    <div class="col-xs-12">
        {{ Form::open(array('id' => "email-getter", 'action' => array('Backend\Manage\EmailController@email_send'), 'class' => 'form-horizontal')) }}

        <div id="emailCreator" class="col-xs-12 rest-form">
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
                <div class="col-xs-9">
                    <ul id="onlyThisOne" class="nav nav-tabs nav-justified">
                        <li class="@if(!isset($preview_message)) active @else not-active @endif compose">
                            {!! Form::label('none', 'Compose E-Mail', array('class'=>'form-control', 'id'=>'compose')) !!}
                        </li>
                        <li class="@if(!isset($preview_message)) not-active @else active @endif">
                            {!! Form::label('preview', 'E-Mail Preview',  array('class'=>'form-control', 'id'=>'preview')) !!}
                        </li>
                    </ul>
                </div>
                <div class="col-md-offset-3 col-xs-9 @if(isset($user_message) and $user_message=='') bg-danger @endif @if(isset($preview_message)) hidden @endif">
                    <textarea name="message" id="message" rows="10"
                              class="form-control message_lock col-xs-12">@if(isset($user_message)){{$user_message}}@endif</textarea>
                </div>
                <div class="col-md-offset-3 col-xs-9 @if(isset($user_message) and $user_message=='') bg-danger @endif @if(!isset($preview_message)) hidden @endif">
                    <div class="message_lock disabled">@if(isset($preview_message)){!! $preview_message !!}@endif</div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-6 col-md-3 col-md-offset-3">
                    {{ Html::link('/manage/email', '<- Go back', array( 'class' => 'btn btn-default form-control')) }}
                </div>
                <div class="col-xs-6 col-md-3">
                    {!! Form::submit( 'Send Email', array('class'=>'btn btn-danger list fa fa-search form-control', 'name'=>'send', 'id'=>'send')) !!}
                </div>
            </div>
            <div class="form-group">
                <a href="http://commonmark.org/help/" class="form-control" target="_blank">Information/help/typing
                    references for commonmark.</a>
            </div>
            <input name="emailList" id="emailList" rows="10" class="hidden"
                   value="@if(isset($ids_get)){{$ids_get}}@endif">

            {!! Form::submit( 'Preview Email', array('class'=>'hidden', 'name'=>'preview', 'id'=>'previewSubmit')) !!}
            {{ Form::close() }}
        </div>
    </div>
@endsection
@section('js-sheet')

@endsection
@section('js')
    $(document).ready(function() {
        $("#compose").click(function(){
            $("#compose").parent('li').removeClass("not-active").addClass("active");
            $("#preview").parent('li').removeClass("active").addClass("not-active");
            $(".message_lock").parent('div').toggleClass("hidden");
        });
        $("#preview").click(function(){
            $("#previewSubmit").click();
        });
    });
@endsection
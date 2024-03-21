@extends('layout.app')
@section('title','Allot Counters')
@section('allot_counter','active')
@section('content')
<div id="main" style="width:99%;">
    <div id="breadcrumbs-wrapper">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title col s5"><b>{{__('messages.allot_counter_page.allot_counters')}}</b></h5>
                    <ol class="breadcrumbs col s7 right-align">
                        <a class="btn-floating waves-effect waves-light teal tooltipped" href="{{route('counters.index')}}" data-position=top data-tooltip="{{__('messages.common.go back')}}"><i class="material-icons">arrow_back</i></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m8  offset-m2">
                    <div class="card-panel">
                        <div class="row">
                            <form id="allot_counter_form" method="post" action="{{route('allotment.store')}}">
                                {{@csrf_field()}}
                                <div class="row">
                                    <div class="row form_align">
                                        <div class="input-field col s6">
                                            <select name="user_name" id="user_name">
                                                <option value="" disabled selected>{{__('messages.allot_counter_page.select user name')}}</option>
                                                @foreach ($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}} </option>
                                                @endforeach
                                            </select>
                                            <label>{{__('messages.allot_counter_page.user name')}}</label>
                                            <div class="name">
                                                @if ($errors->has('user_name'))
                                                <span class="text-danger errbk">{{ $errors->first('user_name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <select name="counter" id="counter_id">
                                                <option value="" disabled selected>{{__('messages.allot_counter_page.select counter')}}</option>
                                                @foreach ($counters as $counter)
                                                <option value="{{$counter->id}}">{{$counter->name}} </option>
                                                @endforeach
                                            </select>
                                            <label>{{__('messages.allot_counter_page.counter')}}</label>
                                            <div class="counter">
                                                @if ($errors->has('counter'))
                                                <span class="text-danger errbk">{{ $errors->first('counter') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <select name="service" id="service_id">
                                                <option value="" disabled selected>{{__('messages.allot_counter_page.select service')}}</option>
                                                @foreach ($services as $service)
                                                <option value="{{$service->id}}">{{$service->name}} </option>
                                                @endforeach
                                            </select>
                                            <label>{{__('messages.allot_counter_page.service')}}</label>
                                            <div class="service">
                                                @if ($errors->has('service'))
                                                <span class="text-danger errbk">{{ $errors->first('service') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-field col s12">
                                        <button class="btn waves-effect waves-light right submit" type="submit">{{__('messages.common.submit')}}
                                            <i class="mdi-content-send right"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<script src="{{asset('app-assets/js/vendors.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script>
    $(function() {
        $(document).ready(function() {
            $('body').addClass('loaded');
        });
        $('#counter_form').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>
@endsection
@endsection

@extends('layouts.master')



@section('title', 'User Create')





@section('content')
<x-mm.styles />
<x-mm.page class="mm-perm mm-perm-user" title="Add new user" description="Create a login for staff who are not linked to an employee record.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ route('permitted.users') }}" title="Add New User"> <i class="fa fa-list-alt"></i> User List </a>
    </x-slot>
    <x-mm.panel>
                    <form action="{{ route('settings.store-user') }}" method="POST">
                        @csrf


                        @include('partials._alert_message')

                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1 mt-2">


                                <!-- User Name -->
                                <div class="form-group row">
                                    <label class="col-md-3 control-label" for="name">
                                        User Name 
                                        <span class="text-danger">*</span> :
                                    </label>

                                    <div class="col-md-8">
                                        <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="User Name" required />
                                    </div>
                                </div>








                                <!-- Email-->
                                <div class="form-group row">
                                    <label class="col-md-3 control-label" for="email">
                                        Email
                                        <span class="text-danger">*</span> :
                                    </label>

                                    <div class="col-md-8">
                                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" autocomplete="off" required />
                                    </div>
                                </div>






                                <!-- Mobile Number -->
                                <div class="form-group row">
                                    <label class="col-md-3 control-label" for="mobile_number">
                                        Mobile :
                                    </label>

                                    <div class="col-md-8">
                                        <input class="form-control" type="text" name="mobile_number" value="{{ old('mobile_number') }}" placeholder="Mobile Number"/>
                                    </div>
                                </div>







                                <!-- Password -->
                                <div class="form-group row">
                                    <label class="col-md-3 control-label" for="password">
                                        Password 
                                        <span class="text-danger">*</span> :
                                    </label>

                                    <div class="col-md-8">
                                        <input class="form-control" type="password" name="password" placeholder="............" autocomplete="off" required />
                                    </div>
                                </div>






                                <!-- Confirm Password -->
                                <div class="form-group row">
                                    <label class="col-md-3 control-label" for="password">
                                        Confirm Password 
                                        <span class="text-danger">*</span> :
                                    </label>

                                    <div class="col-md-8">
                                        <input class="form-control" type="password" name="confirm_password" placeholder="............" autocomplete="off" required />
                                    </div>
                                </div>






                                <!-- Action -->
                                <div class="form-group row">
                                    <div class="col-sm-11 text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('permitted.users') }}" class="btn btn-sm btn-danger p-2"><i class="fa fa-times"></i> Close</a>                                            
                                            <button type="submit" class="btn btn-sm btn-primary p-2"><i class="fa fa-save"></i> Save</button>                                             
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

@stop

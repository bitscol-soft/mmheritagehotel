<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="col-md-12">
                <div class="row">
                    <form class="form-horizontal" action="{{ route('bar.suppliers.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        @include('partials._alert_message')

                        <input type="hidden" name="group_id" value="1">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label add_asterisk" for="form-field-1-1"> Supplier
                                        Name </label>

                                    <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name') }}" placeholder="Supplier Name">

                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1-1"> Attention </label>

                                    <div class="col-xs-12 col-sm-8">
                                        <input type="text" class="form-control" name="attention"
                                            value="{{ old('attention') }}" placeholder="Supplier Attention">
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Phone</label>

                                    <div class="col-xs-12 col-sm-8 @error('phone') has-error @enderror">
                                        <input type="number" class="form-control" name="phone" value=""
                                            placeholder="Phone">

                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Email</label>

                                    <div class="col-xs-12 col-sm-8 @error('email') has-error @enderror">
                                        <input type="email" class="form-control" name="email" placeholder="Email">

                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Address</label>

                                    <div class="col-xs-12 col-sm-8 @error('address') has-error @enderror">
                                        <textarea name="address" id="" class="form-control"></textarea>

                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">


                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Head Office</label>

                                    <div class="col-xs-12 col-sm-8 @error('head_office') has-error @enderror">
                                        <input type="text" class="form-control" name="head_office"
                                            placeholder="Head Office">

                                        @error('head_office')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>


                            </div>
                        </div>


                        <div class="form-group">
                            <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>

                            <div class="col-xs-12 col-sm-6">

                                <button class="btn btn-success"> <i class="fa fa-save"></i> Save</button>
                                <button class="btn btn-gray" type="Reset"> <i class="fa fa-refresh"></i>
                                    Reset</button>
                                @if (hasPermission('suppliers.view', $slugs))
                                    <a href="{{ route('suppliers.index') }}" class="btn btn-info"> <i
                                            class="fa fa-list"></i> List</a>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('guest-uploads.store') }}" class="form-horizontal"
    enctype="multipart/form-data">
    @csrf


    <input type="hidden" name="store_type" value="upload">

    <div class="row">
        <div class="col-sm-12">

            <!-- file upload -->
            {{-- <div class="col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <label class="col-sm-3 control-label add_asterisk">Company</label>

                    <div class="col-xs-12 col-sm-8">
                        <select name="company_id" class="form-control chosen-select" id="company_id">
                            <option value=""></option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->org_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div> --}}

            <!-- file upload -->
            <div class="col-sm-8 col-sm-offset-2">
                <input type="file" class="form-control ace-file-upload" name="csv_file">
            </div>

            <!-- Action -->
            <div class="col-sm-8 col-sm-offset-2 text-right">
                <a href="{{ asset('assets/upload-guest.csv') }}" download
                    class="btn btn-primary btn-sm">
                    <span class="translate">
                        Download Sample
                    </span>
                    <i class="fa fa-download"></i>
                </a>
                <button class="btn btn-inverse btn-sm" type="submit">
                    <span class="translate">
                        Import Guests
                    </span>
                    <i class="fa fa-upload"></i>
                </button>
            </div>
        </div>
    </div>
</form>

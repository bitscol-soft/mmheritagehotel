<div class="company-info" style="display: flex">
    <div style="width: 25%" class="text-left">
        @if (file_exists('uploads/company/' . $company->logo))
            <img src="{{ asset('uploads/company/' . $company->logo) }}" alt="Company Logo" width="100%" height="80">
            {{-- <img src="{{ asset('uploads/company/' . $company->logo) }}" alt="Company Logo" width="150" height="80"> --}}
        @endif
    </div>
    <div class="text-center" style="width: 50%">
        <h4>{{ $company->name }}</h4>
        <p>{{ $company->head_office }}</p>
        <p>{{ $company->phone_number }},{{ $company->email }},</p>
    </div>
</div>

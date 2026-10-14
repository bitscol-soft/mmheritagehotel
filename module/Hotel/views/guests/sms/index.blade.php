@extends('layouts.master')
@section('title', 'Send SMS')

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-hotel-sms" title="Send SMS" description="Write one message and send it to the guests you picked.">
        <x-slot name="actions">
            <a class="mm-button mm-button-secondary" href="{{ route('guests.index') }}">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Guest List
            </a>
        </x-slot>

        @include('partials._alert_message')

        <form class="form-horizontal" id="companyForm" action="{{ route('guests.submit-sms') }}"
            method="post" enctype="multipart/form-data">
            @csrf

            <div class="mm-sms-grid">
                <x-mm.panel class="mobile-widget-box">
                    <h2 class="mm-sms-title">Mobile</h2>
                    <div class="inline">
                        @if ($phones != '')
                            <input type="hidden" name="isFromGuestList" value="1">
                            <input class="multiple-phone-input" type="text" name="phone_no"
                                id="form-field-tags" value="{{ $phones }}"
                                placeholder="Enter Mobile Numbers..." />
                        @else
                            <input class="multiple-phone-input" type="text" name="phone_no"
                                id="form-field-tags" value=""
                                placeholder="Enter Mobile Numbers..." />
                        @endif
                    </div>
                </x-mm.panel>

                <x-mm.panel class="message-widget-box">
                    <h2 class="mm-sms-title">Message</h2>
                    <textarea name="message" class="textarea message-area" placeholder="Write Messages..." rows="10" cols="40"
                        autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"></textarea>
                </x-mm.panel>

                <x-mm.panel class="sms-widget-box">
                    <h2 class="mm-sms-title">SMS configuration</h2>
                    <dl class="mm-sms-stats">
                        <dt>Balance</dt>
                        <dd>{{ $smsbal }}</dd>
                        <dt>Characters</dt>
                        <dd><span class="total-character-count">0</span></dd>
                        <dt>SMS</dt>
                        <dd><span class="part-count">0</span></dd>
                    </dl>
                </x-mm.panel>
            </div>

            <div class="mm-sms-actions">
                <a href="{{ route('guests.index') }}" class="mm-button mm-button-secondary">
                    <i class="fa fa-backward" aria-hidden="true"></i> Back List
                </a>
                <button type="submit" class="mm-button">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Send SMS
                </button>
            </div>
        </form>
    </x-mm.page>
@endsection

@section('js')
    @include('guests.include.script')
@endsection

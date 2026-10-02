<style>
    .room-details-tbody tr:first-child td .btn-danger {
        pointer-events: none;
    }

    .input-group-addon {
        background: transparent;
    }

    .chosen-single {
        background: transparent !important;
        border-color: #ccc !important;
    }

    .width-27 {
        width: 27%;
    }

    .border-none {
        border: none;
    }

    .form-group input[readonly] {
        background: transparent !important;
    }

    select {
        background: transparent !important;
    }
</style>
<style>
    .did-floating-label-content {
        position: relative;
        margin-bottom: 20px;
    }

    .did-floating-label {
        color: #1e4c82;
        font-size: 13px;
        font-weight: normal;
        position: absolute;
        pointer-events: none;
        left: 15px;
        top: 11px;
        padding: 0 5px;
        background: #fff;
        transition: 0.2s ease all;
        -moz-transition: 0.2s ease all;
        -webkit-transition: 0.2s ease all;
    }

    .did-floating-input,
    .did-floating-select {
        font-size: 12px;
        display: block;
        width: 100%;
        height: 36px;
        padding: 0 20px;
        background: #fff;
        color: #323840;
        border: 1px solid #3D85D8;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .did-floating-input:focus,
    .did-floating-select:focus {
        outline: none;
    }

    .did-floating-input:focus~.did-floating-label,
    .did-floating-select:focus~.did-floating-label {
        top: -8px;
        font-size: 13px;
    }

    select.did-floating-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    select.did-floating-select::-ms-expand {
        display: none;
    }

    .did-floating-input:not(:placeholder-shown)~.did-floating-label {
        top: -8px;
        font-size: 13px;
    }

    .did-floating-select:not([value=""]):valid~.did-floating-label {
        top: -8px;
        font-size: 13px;
    }

    .did-floating-select[value=""]:focus~.did-floating-label {
        top: 11px;
        font-size: 13px;
    }

    .did-floating-select:not([multiple]):not([size]) {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='6' viewBox='0 0 8 6'%3E%3Cpath id='Path_1' data-name='Path 1' d='M371,294l4,6,4-6Z' transform='translate(-371 -294)' fill='%23003d71'/%3E%3C/svg%3E%0A");
        background-position: right 15px top 50%;
        background-repeat: no-repeat;
    }

    .did-error-input .did-floating-input,
    .did-error-input .did-floating-select {
        border: 2px solid #9d3b3b;
        color: #9d3b3b;
    }

    .did-error-input .did-floating-label {
        font-weight: 600;
        color: #9d3b3b;
    }

    .did-error-input .did-floating-select:not([multiple]):not([size]) {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='6' viewBox='0 0 8 6'%3E%3Cpath id='Path_1' data-name='Path 1' d='M371,294l4,6,4-6Z' transform='translate(-371 -294)' fill='%239d3b3b'/%3E%3C/svg%3E%0A");
    }
</style>


<style>
    .show-all-room {
        font-size: 16px;
        display: inline-block;
        cursor: pointer;
    }

    .select2-container .select2-selection--multiple .select2-selection__rendered {
        margin: 0;
        display: flex;
        flex-wrap: wrap;
    }

    .show-category-room-modal .modal-dialog.modal-sm {
        width: 410px !important
    }

    .show-category-room-modal .select2-container {
        width: 380px !important
    }

    .show-category-room-modal .select2-container--default.select2-container--focus .select2-selection {
        border-width: 3px;
    }

    .show-category-room-modal .select2-container--default .select2-selection {
        border: 3px solid #4492C9 !important;
    }
</style>
<style>
    /* round-5: booking view readonly field rows */
    .booking-view .input-group > input[readonly] {
        border: none;
        background: transparent;
        box-shadow: none;
        font-weight: 600;
        color: #37536a;
        padding: 2px 4px;
        width: auto;
        height: auto;
    }
    .booking-view .guest-info .info-title .title {
        font-weight: 600;
        color: #37536a;
    }

</style>

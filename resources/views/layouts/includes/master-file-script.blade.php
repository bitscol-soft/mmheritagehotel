<!-- jquery -->
<script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.query-object.js') }}"></script>


<!-- ace scripts -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>


<script src="{{ asset('assets/js/toastr.min.js') }}"></script>


<!-- sweetalert2 -->
<script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>


<!-- ace element control -->
<script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('assets/js/ace.min.js') }}"></script>

<!-- DEBOUNCE JS -->
<script src="{{ asset('assets/js/jquery.ba-throttle-debounce.js') }}"></script>

{{-- Tag Input --}}
<script>
    var tag_input = $('#form-field-tags');
    try {
        tag_input.tag({
            placeholder: tag_input.attr('placeholder'),
        });

    } catch (e) {
        tag_input.after('<textarea id="' + tag_input.attr('id') + '" name="' + tag_input.attr('name') + '" rows="3">' +
            tag_input.val() + '</textarea>').remove();
    }
</script>



<script type="text/javascript">
    function withDefault(value, default_value) {
        return value ? value : default_value
    }
</script>







<!-- Autocomplete script -->
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/custom_js/loadDetails.js') }}"></script>
{{-- <script src="{{ asset('assets/custom_js/patient_filter.js') }}?v={{ fdate(now(), 'Y-m-d H:i') }}"></script> --}}
<script src="{{ asset('assets/custom_js/reference_filter.js') }}"></script>


{{-- LOADING OVERLAY ICON --}}
<script src="{{ asset('assets/custom_js/loadingoverlay.min.js') }}"></script>





<!-- datepicker & timepicker script -->
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
<script src="{{ asset('assets/custom_js/ajax-rendering-view.js') }}?v={{ time() }}"></script>



<!-- chosen script -->
<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>



<!-- SELECT2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script src="{{ asset('assets/custom_js/loadSelect2.js') }}"></script>


<!-- custom toster -->
<script src="{{ asset('assets/custom_js/message-display.js') }}"></script>



<!-- ACE FILE UPLOADER -->
<script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>



<!-- delete confirm dialog -->
<script type="text/javascript" src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>

<script src="https://unpkg.com/axios@1.1.2/dist/axios.min.js"></script>

<script>
    function optimizeClear()
    {
        window.location.href = `{{ route('optimize-clear') }}`;
    }
</script>

<script>
    function log(message) {
        console.log(message)
    }


    jQuery(function($) {
        let path = window.location.href

        path = path.replace('#', '')

        let selector = "a[href='" + path + "']"

        if (!$(selector).closest('li').hasClass('hasQuery')) {
            path = path.split('?')[0]
            selector = "a[href='" + path + "']"
        }

        if ($(selector).length < 1) {

            selector = selector.substring(0, selector.lastIndexOf('/'))

            if ($(selector).length < 1) {

                selector = selector.substring(0, selector.lastIndexOf('/'))

                if ($(selector).length < 1) {

                    if ($(selector).length < 1) {

                        selector = selector.substring(0, selector.lastIndexOf('/'))
                    }

                    selector = selector.substring(0, selector.lastIndexOf('/'))
                }

            }
        }


        let a_tag = $(selector)



        let li_tag = a_tag.closest('li')

        li_tag.addClass('active')

        li_tag.parents('li').add(this).each(function() {

            $(this).addClass('open')

        });

    });







    /*
     *--------------------------------------------------------------
     * DYNAMICALLY HANDLE SIDE MENU CHILDREN
     * DOM REMOVE IF DOES NOT HAVE ANY CHILDREN
     * ONLY WORKS IN NAVBAR ELEMENT
     *--------------------------------------------------------------
     */
    $(document).on('ready', function() {
        $('.nav-list>li>ul').each(function() {


            $.each($(this).find('li>ul'), function() {

                $.each($(this).find('li>ul'), function() {

                    if ($(this).children().length == 0) {
                        $(this).parent().remove()
                    }
                })

                if ($(this).children().length == 0) {
                    $(this).parent().remove()
                }
            })

            if ($(this).find('li').length == 0) {
                $(this).parent().remove()
            }

        })
    })
</script>

<script>
    $('select').each(function() {
        let selected = $(this).data('selected');
        if (typeof selected === "undefined") {
            return;
        }
        $(this).val(selected).prop('selected', 'selected')

    })
</script>


<!-- global scripts -->
<script type="text/javascript">
    // <!-- popover -->
    $('[data-rel=popover]').popover({
        html: true,
        container: 'body'
    });





    // auto fadeout success message, when redirect with success message
    $('.success').fadeIn('slow').delay(10000).fadeOut('slow');




    // alert message display
    function showAlertMessage(message, time = 1000, type = 'error') {
        swal.fire({
            title: type.toUpperCase(),
            html: "<b>" + message + "</b>",
            type: type,
            timer: time
        })
    }



    // success alert message like popup window
    @if (session()->get('message'))

        swal.fire({
            title: "Success",
            html: "<b>{{ session()->get('message') }}</b>",
            type: "success",
            timer: 1000
        });



        // success alert message like popup window
    @elseif (session()->get('success'))

        swal.fire({
            title: "Success",
            html: "<b>{{ session()->get('success') }}</b>",
            type: "success",
            timer: 1000
        });


        // error alert message like popup window
    @elseif (session()->get('error'))

        swal.fire({
            title: "Error",
            html: "<b>{{ session()->get('error') }}</b>",
            type: "error",
            timer: 1000
        });
    @endif


    $('.select2').select2()

    // input field only number accept method
    function onlyNumber(evnt) {
        let keyCode = evnt.charCode
        let str = evnt.target.value
        let n = str.includes(".")

        if (keyCode == 13) {
            evnt.preventDefault();
        }

        if (keyCode == 46) {
            return false
        }

        if (str.length > 12) {
            showAlertMessage('Number length out of range', 3000)
            return false
        }
        return (keyCode >= 48 && keyCode <= 57) || keyCode == 13 || keyCode == 46
    }


    // input field only number accept class
    $('.only-number').keypress(function() {
        return onlyNumber(event)
    })





    function getPercentAmount(total_amount, percent) {
        return (total_amount * percent) / 100
    }





    function getPercent(total_amount, discount_amount) {
        if (total_amount < 1) {
            return 0;
        }

        return (discount_amount * 100) / total_amount
    }
</script>


<script>
    function formatProduct(data) {
        if (data.loading) {
            return data.text;
        }
        var $container = $(
            `<div class='select2-result clearfix'>
                    <div class='select2-result__title'>${data.name}</div>
                        <div class='select2-result__description'>${data.sale_price}</div>
                        </div>
                    </div>
                </div>`
        );

        return $container;
    }

    function formatSelection(data) {
        return data.name;
    }
</script>

<script>
    $(document).ready(function() {
        $(".currency-sign").text(`{!! currencySign() !!}`);
    });
</script>





<!-- dynamically include script -->
@yield('js')

@yield('script')


<!-- software payment notification script -->
@if (request()->segment(1) != 'em')
    {{-- @include('partials._payment_notification') --}}
@endif

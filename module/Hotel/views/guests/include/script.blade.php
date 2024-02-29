<script src="{{ asset('assets/js/jquery.selectallcheckbox.js') }}"></script>

<script>

    //----------------------------------------------------------------//
    //                     SELECT ALL CHECKBOX                        //
    //----------------------------------------------------------------//
    function onChange( checkboxes, checkedState ) {}
    $( document ).ready( function(){

       $( "#selectAll, #selectEveryone" ).selectAllCheckbox({
          checkboxesName   : "guest_id[]",
          onChangeCallback : onChange,
          useIndeterminate : false
       });

    });





    //----------------------------------------------------------------//
    //                  SELECT EVERYONE CHECKBOX                      //
    //----------------------------------------------------------------//
    function selectEveryone(){

        let val = $('#isAllSelected').val();

        if (val == 1) {
            $('#isAllSelected').val(0);
        }
        else{
            $('#isAllSelected').val(1);
        }
    };


</script>








<!--------------------------------------------------------------------
                    CHOOSE MULTIPLE PHONE NUMBER
--------------------------------------------------------------------->
<script src="{{ asset('assets/js/bootstrap-tag.min.js') }}"></script>

<script type="text/javascript">

    $('#chosen-multiple-style .btn').on('click', function(e){
        var target = $(this).find('input[type=radio]');
        var which = parseInt(target.val());
        if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
        else $('#form-field-select-4').removeClass('tag-input-style');
    });



    var tag_input = $('#form-field-tags');

    if(! ( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase())) ){
        tag_input.tag(
            {
                placeholder:tag_input.attr('placeholder'),
                source: ace.variable_US_STATES,
            }
        );
    }
    else {
        tag_input.after('<textarea id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" rows="3">'+tag_input.val()+'</textarea>').remove();
    }








    //--------------------------------------------------------------------//
    //                          COUNT SMS CHARACTER                       //
    //--------------------------------------------------------------------//

    $('.message-area').keyup(function () {
        let max = 640
        let part = 160 //max / 4;
        let len = $(this).val().length
        let partCount = (len/part) + 1
        if (partCount>4) {
            partCount = 4
        }
        if (len == 0) {
            partCount = 0
        }
        $('.part-count').text(parseInt(partCount))
        $('.total-character-count').text(len)
    });

</script>

<script src="{{ asset('assets/js/webcam.min.js') }}"></script>

<script language="JavaScript">
    $('.id-input-file-5').ace_file_input({
        style: 'well',
        btn_choose: 'Upload NID / Passport Photo',
        btn_change: null,
        no_icon: 'ace-icon fa fa-cloud-upload',
        droppable: true,
        thumbnail: 'small' //large | fit

    }).on('change', function() {});


    function configure(id){
        $('.old-image'+id).hide()
        Webcam.set({
            width: 446,
            height: 326,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera'+id);

    }

    function reset(){
        Webcam.reset();
    }

    function take_snapshot(id) {
        Webcam.snap( function(data_uri) {
            console.log(data_uri);
            // document.querySelector('.image-tag').setAttribute('src', "data:image/jpg;base64," + data_uri);
            document.getElementById('results').innerHTML = '<img widht="200" height="150" src="'+data_uri+'"/>';
            $('.image-tag'+id).val(data_uri);
            $('.is_web_cam_or_not'+id).val(1);
            $('.image'+id).prop('disabled', true);
            // $('.old-image').hide();

            Webcam.reset();
            document.getElementById('my_camera'+id).innerHTML = '<img src="'+data_uri+'"/>';
            $('.delete-snap'+id).show();
        } );
    }

    function deleteSnap(id){
        $('#results'+id).empty();
        $('.delete-snap'+id).hide();
        $('.image'+id).prop('disabled', false);
    }
    // $('.delete-snap').on('click', function(){
    // })




    // Guest Image Change from Booking List   -- Not Worked ( No Need This Function)


</script>

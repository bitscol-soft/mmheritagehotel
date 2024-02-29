
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>


    //-----------------------------------------------------------//
    //                   SUBMIT ROOM STORE FORM                  //
    //-----------------------------------------------------------//
    function submitRoomStoreForm(obj)
    {
        if ($('#category').val() == '' || $('#roomNumber').val() == '') {
            toastr.error('Please choose a Room & Room Number!');
            return;
        }

        $('.roomCreateForm').submit();
    }




    //-----------------------------------------------------------//
    //             CHECKING DUPLICATE ROOM NUMBER                //
    //-----------------------------------------------------------//
    function checkRoomNumber(obj)
    {
        let roomId       = $('#roomId').val();
        let roomCategory = $(obj).closest('.row').find('#category').val();
        let roomNumber   = $(obj).val();

        checkRoomNumberOnAxios(roomId,roomCategory,roomNumber);
    }






    //-----------------------------------------------------------//
    //         CHECKING DUPLICATE ROOM NUMBER BY CATEGORY        //
    //-----------------------------------------------------------//
    function checkRoomNumberByCategory(obj)
    {
        let roomId          = $('#roomId').val();
        let roomCategory    = $(obj).val();
        let roomNumber      = $(obj).closest('.row').find('#roomNumber').val();

        checkRoomNumberOnAxios(roomId,roomCategory,roomNumber);
    }





    //-----------------------------------------------------------//
    //             CHECKING DUPLICATE ROOM NUMBER                //
    //-----------------------------------------------------------//
    function checkRoomNumberOnAxios(roomId,roomCategory,roomNumber){

        const route = "{{ route('rooms.check-room-number') }}";

        axios.get(route, {
            params: {
                room_id: roomId,
                room_category: roomCategory,
                room_number: roomNumber
            }
        })
        .then(function (response) {
            console.log(response);
            if (response.data == 1) {
                $('#duplicateRoomError').show();
                $('#duplicateRoomError').text('Room Number Already Exists For This Category!');

                $('#submitRoomFormBtn').prop('disabled', true);

                toastr.error('Room Number Already Exists For This Category!');
                return;

            }
            else{
                $('#duplicateRoomError').hide();
                $('#duplicateRoomError').text('');

                $('#submitRoomFormBtn').prop('disabled', false);
            }
        })
        .catch(function (error) {
            toastr.error('Something went wrong :(');
            return;
        });
    }



</script>

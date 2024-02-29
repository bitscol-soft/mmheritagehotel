<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>

    //--------------------------------------------------------------------//
    //                      SHOW CUSTOMER MODAL METHOD                    //
    //--------------------------------------------------------------------//
    function showCustomerModal()
    {
        let id      = $("#customer_id").data('selected');
        const route = "{{ route('rooms.get-hotel-guest-info') }}";

        axios.get(route, {
            params: {
                id: id,
            }
        })
        .then(function (response) {
    
            if (response.data != '') {

                let data = response.data;

                $("#guestId").val(data.id);
                $("#name").val(data.name);
                $("#email").val(data.email);
                $("#phone_no").val(data.phone_no);
                $("#nid_no").val(data.nid_no);
                $("#passport_expiry_date").val(data.passport_expiry_date);
                $("#address").val(data.address);
                $("#spouse_name").val(data.spouse_name);

                $("#editGender").html(`
                    <option value="" selected>Select Gender</option>
                    <option value="1" ${ data.gender == 1 ? "selected" : '' }>Male</option>
                    <option value="2" ${ data.gender == 2 ? "selected" : '' }>Female</option>
                    <option value="0" ${ data.gender == 0 ? "selected" : '' }>Others</option>
                `);

                if(data.country_id != null){
                    $("#country_id").val(data.country_id).select2();
                }

                let baseUrl     = `{{ url('/') }}`;

                let nidFront        = data.nid_front.replace("./","/");
                let nidBack         = data.nid_back.replace("./","/");
                let spouseNidFront  = data.spouse_nid_front.replace("./","/");
                let spouseNidBack   = data.spouse_nid_back.replace("./","/");

                $("#nidFrontImg").attr('src', baseUrl + nidFront ?? '{{ asset("assets/images/default.png") }}' );
                $("#nidBackImg").attr('src', baseUrl + nidBack ?? '{{ asset("assets/images/default.png") }}' );
                $("#spouseNidFrontImg").attr('src', baseUrl + spouseNidFront ?? '{{ asset("assets/images/default.png") }}' );
                $("#spouseNidBackImg").attr('src', baseUrl + spouseNidBack ?? '{{ asset("assets/images/default.png") }}' );

            }
            else{
                toastr.error('No Guest Data Found :(');
                return;
            }
        })
        .catch(function (error) {
            toastr.error('Something went wrong :(');
            return;
        });

    }




    //-------------------------------------------------------------------//
    //                 SUBMIT GUEST INFO FORM BY AXIOS                   //
    //-------------------------------------------------------------------//
    function submitGuestFormAxios(obj, url){

		axios({
            method: "post",
            url: url,
            data: $(obj).serialize(),
        })
        .then(function (response) {
            console.log(response);

            let data = response.data.data;

            if (response.data.status == 1) {

                $("#customer_id option:selected").text(data.name);

                $('#editHotelGuestModal').modal('toggle');

                $("#guestId").val('');
                $("#name").val('');
                $("#email").val('');
                $("#phone_no").val('');
                $("#nid_no").val('');
                $("#passport_expiry_date").val('');
                $("#address").val('');
                $("#spouse_name").val('');
                $('#editGender').prop('selectedIndex','');
                $("#country_id").val('');

                toastr.success(response.data.message);
            }
            else{
                toastr.error(response.data.message);
            }

        })
        .catch(function (error) {
            console.log(error);
            toastr.error(error);
        });
    }




    //-------------------------------------------------------------------//
    //                  SUBMIT GUEST INFO FORM BY AJAX                   //
    //-------------------------------------------------------------------//
    function submitGuestFormAjax(obj, url){

		$.ajax({
			type:'POST',
			url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $(obj).serialize(),
			cache:false,
			contentType: false,
			processData: false,
			success:function(response){
				console.log(response);

                if (response.data.status == 1) {

                    let data = response.data.data;

                    $("#customer_id option:selected").text(data.name);

                    $('#editHotelGuestModal').modal('toggle');

                    $("#guestId").val('');
                    $("#name").val('');
                    $("#email").val('');
                    $("#phone_no").val('');
                    $("#nid_no").val('');
                    $("#passport_expiry_date").val('');
                    $("#address").val('');
                    $("#spouse_name").val('');
                    $('#editGender').prop('selectedIndex','');
                    $("#country_id").val('');

                    toastr.success(response.data.message);
                }
                else{
                    toastr.error(response.data.message);
                }

			},
			error: function(response){
                toastr.error("Failed, Something went wrong!");
			}
		});
    }



</script>

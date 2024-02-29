<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>

    //---------------------------------------------------------//
    //                        VIEW MODAL METHOD                //
    //---------------------------------------------------------//
    function showImagesModal(booking_id){
        $('#guest_image_change').toggle("modal");

        let id      = booking_id;
        const route = "{{ route('get-booking-details') }}";

        axios.get(route, {
            params: {
                id: id,
            }
        })
        .then(function (response) {

            if (response.data != '') {

                let data         = response.data;
                let guest_images = response.data.guest_image;
                var baseUrl      = window.location.origin;

                let html = ``;
                let old_image = ``;

                $('#cam_modal_content').html('');
                $('#old_image').html('');

                    html += `<div class="modal-header">
                                <button onclick="closesImagesModal()" type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="blue bigger"><i class="fa fa-eye"></i> Update image of ${data?.guest_info?.name}</h4>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <dl id="dt-list-1" class="dl-horizontal">
                                            <form action="{{ route('guest-image-update') }}" id="updateGuestImage${data.id}" class="change-image-of" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="booking_id" value="${data.id}">
                                                <input type="hidden" name="booking_number" value="${data.booking_number}">
                                                <input type="hidden" name="customer_id" value="${data.customer_id}">

                                                <div class="form-group" style="height: 300px">
                                                    <label class="col-sm-1 control-label" style="font-weight: bold; font-size: 16px; color: #007bff;">Image</label>
                                                    <div class="col-xs-5 col-sm-5 image-section"
                                                        style="position: relative">

                                                        <input type="file" name="image" class="image${data.id} id-input-file-5 btn-outline-success" style="border: 1px solid #28a745; padding: 4px; width:70%; border-radius: 4px; background-color: #fff; color: #28a745; cursor: pointer;">

                                                        <!-- Modal -->
                                                            <div class="modal fade webcamModal" id="webcam-modal${data.id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header" style="padding: 2px !important">
                                                                    <button onclick="closesCamModal()" type="button" class="close btn-sm web-cam-close" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </div>
                                                                    <div class="modal-body" style="text-align: center;">
                                                                        <div id="my_camera${data.id}" style="width: 600px !important; height: 135px !important;display: inline;"></div>
                                                                        <div>
                                                                            <a href="javascript:void(0)" onclick="take_snapshot(${data.id})" class="btn btn-success btn-sm" style="background-color: #28a745; color: #fff; border: 1px solid #28a745; padding: 5px 10px; border-radius: 4px; text-decoration: none;">
                                                                                <i class="fa fa-camera" style="margin-right: 5px;"></i> Capture
                                                                            </a>

                                                                        </div>
                                                                        {{-- <a href="javascript:void(0)" onClick="reset()" class="btn btn-danger btn-sm"><i class="fa fa-close"></i></a> --}}
                                                                    </div>
                                                                    <div class="modal-footer" style="padding: 0 !important; margin: 0 !important">
                                                                    <button onclick="closesCamModal()" type="button" class="btn btn-secondary btn-sm web-cam-close">Close</button>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </div>


                                                        <!-- Button trigger modal for WEBCAM -->
                                                        <button type="button" class="btn btn-primary btn-sm webcam-modal-btn" onclick="configure(${data.id})"
                                                            style="position: absolute; top: 1px; right: 14px; border: 1px solid #007bff; background-color: #007bff; color: #fff; border-radius: 4px; padding: 5px 10px; cursor: pointer;"
                                                            data-toggle="modal" data-target="#webcam-modal${data.id}">
                                                            <i class="fa fa-camera" style="margin-right: 5px;"></i> Camera
                                                        </button>



                                                        <input type="hidden" name="web_cam" value="0" class="is_web_cam_or_not${data.id}">
                                                        <input type="hidden" name="image" class="image-tag${data.id}">
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 image-section" style="position: relative">
                                                        <div id="results"></div>
                                                        <a href="javascript:void(0)" class="delete-snap${data.id}" onclick="deleteSnap(${data.id})"
                                                            style="display: none;position: absolute;top:0;right:55px"><i
                                                                class="fa fa-times"></i></a>
                                                    </div>
                                                    <div class="old-image${data.id}" id="old_image" style="margin-top: 10px; padding: 40px 0 0 0; border: 0px solid #ccc; border-radius: 4px;">
                                                        <!-- Your content goes here -->
                                                    </div>

                                                </div>

                                            </form>
                                        </dl>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button onclick="closesImagesModal()" class="btn btn-sm btn-outline-danger" data-dismiss="modal"><i
                                        class="ace-icon fa fa-times"></i>Cancel</button>
                                <button class="btn btn-sm btn-outline-success" form="updateGuestImage${data.id}" type="submit">
                                    <i class="ace-icon fa fa-pen-square"></i>Save
                                </button>
                            </div>`;



                    $.each(guest_images, function(i, bookimage){
                        console.log(baseUrl+(bookimage.image).replace("./", "/"));
                        old_image+=`<img height="150" width="190" src="${baseUrl + (bookimage.image).replace("./", "/")}" alt="${data.guest_info.name}" style="border: 1px solid #ccc; border-radius: 4px; margin-left:20px; margin-top: 10px;">`;
                    });

                    $('#cam_modal_content').append(html);
                    $('#old_image').append(old_image);
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

    //---------------------------------------------------------//
    //                        CLOSE MODAL METHOD                //
    //---------------------------------------------------------//
    function closesImagesModal(){
        $('#guest_image_change').toggle("modal");
    }

    //---------------------------------------------------------//
    //                        CLOSE MODAL METHOD                //
    //---------------------------------------------------------//
    function closesCamModal(){
        $('.webcamModal').modal('hide');
        // $('.webcamModal').toggle("modal");
        // $('.webcamModal').modal('dispose');
    }


</script>


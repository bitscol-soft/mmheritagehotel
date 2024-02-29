<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>

    //---------------------------------------------------------//
    //                        VIEW MODAL METHOD                //
    //---------------------------------------------------------//
    function showDetailsModal(booking_id){
        $('#booking-details').toggle("modal");

        let id      = booking_id;
        const route = "{{ route('get-booking-details') }}";

        axios.get(route, {
            params: {
                id: id,
            }
        })
        .then(function (response) {

            if (response.data != '') {

                let data = response.data;

                let bookingDetails  = data.booking_details;
                let booking_members = data.booking_members;

                let extra = 0;

                let total = 0;

                let html = ``;

                let footer = ``;

                let guest = ``;

                $('#booking-details-table tbody').html('');
                $('#booking-details-table tfoot').html('');
                $('#guest-details tbody').html('');

                $.each(bookingDetails, function(i, val){
                    html += `<tr>
                                <td>${i+1}</td>
                                <td>
                                    ${val?.room_category?.name}
                                </td>
                                <td>
                                    ${val?.room_number?.room_number ?? ''}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="javascript:void(0)" class="green bigger-140 show-details-btn"
                                            title="Show Details">
                                            <i class="ace-icon fa fa-angle-double-down"></i>
                                                    ${val?.guest_count}
                                            <span class="sr-only"> Details</span>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    ${val?.infant_count}
                                </td>
                                <td>
                                    ${val?.night_count}
                                </td>
                                <td>
                                    ${val?.discount_amount}
                                </td>
                                <td class="text-center">
                                    ${val.allow_breakfast ? `<span class="label label-xs label-primary arrowed arrowed-right">Yes</span>` : `<span
                                            class="label label-xs label-danger arrowed arrowed-right">No</span>`}
                                </td>
                                <td class="text-right">
                                    ${val?.total_amount}
                                </td>
                            </tr>
                            <tr class="detail-row guest-details-tr">
                                <td colspan="8">
                                    <table id="guest-details" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>`;
                })

                footer+= `<tr>
                            <td colspan="8" class="text-right">Extra Charge</td>
                            <td class="text-right">
                                ${data?.booking_extra_charge?.extra_amount ?? 0}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-right">Total Amount</td>
                            <td class="text-right">
                                ${data?.transection?.total_amount}
                            </td>
                        </tr>`;
                $.each(booking_members, function(i, member){
                        guest+=`
                            <tr>
                                <td>
                                    ${member.name ?? ''}
                                </td>
                                <td>
                                    ${member.phone ?? ''}
                                </td>
                                <td>
                                    ${member.email ?? ''}
                                </td>
                            </tr>`;
                    });
                $('#booking-details-table').append(html);
                $('#booking-details-table').append(footer);
                $('#guest-details tbody').append(guest);

                $('.show-details-btn').on('click', function(e) {
                    e.preventDefault();
                    $(this).closest('tr').next().toggleClass('open');
                    $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
                });
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
    function closeDetailsModal(){
        $('#booking-details').toggle("modal");

    }










</script>


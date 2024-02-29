$(document).on('keyup', '#reference-by', '#reference-by-agent', '#reference-by-internal-doctor', '#reference-by-external-doctor', function () {
    referencePersonFilter()
    alert('msg');
});


function referenceFilter(setRefferenceType = null) {

    // reference_by_agent
    loadDetails({
        type: 'reference_by_agent',
        selector: '#reference-by-agent',
        url: requestUrl({
            path: 'load-commission-agent',
        }),
        select: function (event, ui) {
            console.log(ui);
            $('#reference_by_agent_id').val(ui.item.data.id);

        },
        search: function () {
            $('#reference_by_agent_id').val('');
        }

    })

    // reference-by-internal-doctor
    loadDetails({
        type: 'internal_doctor',
        selector: '#reference-by-internal-doctor',
        url: requestUrl({
            path: 'load-internal-doctors',
        }),
        select: function (event, ui) {
            $('#reference_by_internal_doctor_id').val(ui.item.data.id);
        },
        search: function () {
            $('#reference_by_internal_doctor_id').val('');
        }
    })
    // reference-by-external-doctor
    loadDetails({
        type: 'external_doctor',
        selector: '#reference-by-external-doctor',
        url: requestUrl({
            path: 'load-refer-doctors',
        }),
        select: function (event, ui) {
            $('#reference_by_external_doctor_id').val(ui.item.data.id);
        },
        search: function () {
            $('#reference_by_external_doctor_id').val('');
        }

    })
    // load - commission -all- refers
    if (setRefferenceType == 'outdoor') {
        loadDetails({
            type: 'reference_by',
            selector: '#reference-by',
            url: requestUrl({
                path: 'load-commission-refers-outdoor',
            }),
            select: function (event, ui) {
                loadrefferance(ui)

            },
            search: function () {
                $('#reference_id').val('');
            }

        })
    } else if (setRefferenceType == 'indoor') {
        loadDetails({
            type: 'reference_by',
            selector: '#reference-by',
            url: requestUrl({
                path: 'load-commission-refers-indoor',
            }),
            select: function (event, ui) {
                loadrefferance(ui)

            },
            search: function () {
                $('#reference_id').val('');
            }

        })
    }



}


function loadrefferance(ui) {
    $('#reference_id').val(ui.item.data.id);
}
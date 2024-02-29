<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    //-----------------------------------------------------------//
    //                   SUBMIT ROOM STORE FORM                  //
    //-----------------------------------------------------------//
    function submitRoomStoreForm(obj) {
        if ($('#currencyId').val() == '') {
            toastr.error('Please choose a Currency!');
            return;
        }
        if ($('#rate').val() == '') {
            toastr.error('Please choose a Rate!');
            return;
        }
        if ($('#effectedDate').val() == '') {
            toastr.error('Please choose a Effected Date!');
            return;
        }

        $('.createCurrencyConversionForm').submit();
    }
</script>


<script>
    // Get the modal element
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("openModalBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // Function to open the modal when the button is clicked
    btn.onclick = function() {
        modal.style.display = "block";
    };

    // Function to close the modal when the span (close button) is clicked
    span.onclick = function() {
        modal.style.display = "none";
    };

    // Function to close the modal if the user clicks outside of it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // AJAX request to fetch dynamic data from the server
    // You need to set up a route and controller method to handle this request
    function fetchDataForModal() {
        // Replace 'your_route_name' with the actual route to fetch the data
        fetch('kitchen/details')
            .then((response) => response.json())
            .then((data) => {
                // Update the modal content with the fetched data
                document.getElementById('modalContent').innerHTML = data;
            })
            .catch((error) => console.error('Error fetching data:', error));
    }

    // Call the function to fetch data when the modal is opened
    btn.onclick = function() {
        fetchDataForModal();
        modal.style.display = "block";
    };
</script>

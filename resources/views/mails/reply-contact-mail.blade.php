<!DOCTYPE html>
<html>
<head>
    <title>MM Heritage Hotel</title>
    <style>
        .company-info {
            color: #000;
        }

        .company-info h3 {
            font-weight: bold;
            margin-bottom: 0;
        }

        .company-info p {
            margin-bottom: 2px;
        }

        table tbody tr th {
            font-weight: bold!important;
        }
        table tbody tr th, table tbody tr td {
            padding: 0!important;
        }
        .booking-confirm-title {
            font-size: 32px;
            color: green;
            line-height: normal;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .contact-number{
            background: #ddd;
            padding: 5px 15px;
            border-radius: 5px;
            color: #000;
            text-decoration: none;
        }
        .card{
            border: 1px solid #ddd; 
            border-radius: 8px; 
            padding: 15px;
        }
        .contact-card-body{
            display: flex; 
            justify-content: space-between; 
            align-items:center;
        }
    </style>
</head>
<body>
    <div style="display: flex; gap: 20px; flex-direction: column">
        <div class="company-info" style="display: flex; justify-content:center">
            <div class="text-center" style="width: 50%">
                <h4>{{ $replymailData['company_name'] }}</h4>
                <p>{{ $replymailData['company_headoffice'] }}</p>
                <p>{{ $replymailData['company_mobile'] }}, {{ $replymailData['company_email'] }},</p>
            </div>
        </div>


        <div class="card">
            <h5 class="card-title booking-confirm-title"><svg xmlns="http://www.w3.org/2000/svg" height="32" width="32" viewBox="0 0 512 512"><path fill="#008000" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg> Booking Confirmed</h5>
            <hr style="margin-top: 10px;">
            <p>Dear {{ $replymailData['name'] }},</p>
            <p>{{ $replymailData['content'] }}</p>
            <hr>
            <h3>Booking Info : </h3>
            <div class="company-info" style="display: flex; justify-content:center">
                <div class="customerInfo" style="width: 70%;float: left; ">
                    <p class="patient"><b>Name : </b>{{ $replymailData['name'] }}</p>
                    <p><b>Room : </b>{{ $replymailData['room_name'] }} - {{ $replymailData['room_no'] }}</p>
                    <p class="patient"><b>Address : </b>{{ $replymailData['address'] }}
                    </p>
                    <p class="patient"><b>Mobile : </b>{{ $replymailData['mobile'] }}
                    </p>
                </div>
                <div class="invoiceInfo" style="width: 30%;float: left; margin-top: 5px;">
                    <table class="table table-bordered" style="border: none !important;">
                        <tr>
                            <th width="50%" style="border: none !important;"> Booking No : </th>
                            <th style="border: none !important;">
                                BK-{{ $replymailData['booking_no'] }}</th>
                        </tr>
                        <tr>
                            <td style="border: none !important;"> Booking Date : </td>
                            <td style="border: none !important;">{{ $replymailData['booking_date'] }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none !important;"> Check IN Date : </td>
                            <td style="border: none !important;">{{ $replymailData['check_in_date)'] }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none !important;"> Check out Date : </td>
                            <td style="border: none !important;">{{ $replymailData['check_out_date'] }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 30px">
            <div class="contact-card-body">
                <div>
                    <h4 class="card-title">Need Our Help ?</h4>
                    <p class="card-text">Call us in case you face any issue in our services.</p>
                </div>
                <div>
                    <a class="contact-number" href="tel:+{{ $replymailData['company_mobile'] }}">{{ $replymailData['company_mobile'] }}</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

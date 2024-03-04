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
                <h4>{{ $mailData['company_name'] }}</h4>
                <p>{{ $mailData['company_headoffice'] }}</p>
                <p>{{ $mailData['company_mobile'] }}, {{ $mailData['company_email'] }},</p>
            </div>
        </div>


        <div class="card">
            <h3>Booking Info : </h3>
            <div class="company-info" style="display: flex; justify-content:center">
                <div class="customerInfo" style="width: 70%;float: left; ">
                    <p class="patient"><b>Name : </b>{{ $mailData['name'] }}</p>
                    <p><b>Room : </b>{{ $mailData['room_name'] }} - {{ $mailData['room_no'] }}</p>
                    <p class="patient"><b>Address : </b>{{ $mailData['address'] }}
                    </p>
                    <p class="patient"><b>Mobile : </b>{{ $mailData['mobile'] }}
                    </p>
                </div>
                <div class="invoiceInfo" style="width: 30%;float: left; margin-top: 5px;">
                    <table class="table table-bordered" style="border: none !important;">
                        <tr>
                            <th width="50%" style="border: none !important;"> Booking No : </th>
                            <th style="border: none !important;">
                                BK-{{ $mailData['booking_no'] }}</th>
                        </tr>
                        <tr>
                            <td style="border: none !important;"> Booking Date : </td>
                            <td style="border: none !important;">{{ $mailData['booking_date'] }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none !important;"> Check IN Date : </td>
                            <td style="border: none !important;">{{ $mailData['check_in_date)'] }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none !important;"> Check out Date : </td>
                            <td style="border: none !important;">{{ $mailData['check_out_date'] }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GUEST REGISTRATION FORM</title>

    <link href="https://fonts.googleapis.com/css2?family=Calistoga&display=swap" rel="stylesheet">
    <style>
        @page { header: page-header; footer: page-footer; sheet-size: Letter; margin: 0 !important; }

        * {margin: 0; padding: 0; box-sizing: border-box;}
        .col-1 {width:8%; }
        .col-2 {width:16%;}
        .col-3 {width:25%;}
        .col-4 {width:33%;}
        .col-5 {width:42%;}
        .col-6 {width:50%;}
        .col-7 {width:58%;}
        .col-8 {width:66%;}
        .col-9 {width:75%;}
        .col-10{width:83%;}
        .col-11{width:92%;}
        .col-12{width:100%;}

        .font-family{ font-family: 'Fira Sans', sans-serif !important; }
        .font-bold{font-weight: bold;}
        .col-2 { float: left; width: 16.6666666667%; }
        .col-3 { float: left; width: 25%; }
        .col-6 { float: left; width: 50%; }
        .col-9 { float: left; width: 75%; }
        .col-10 { float: left; width: 83.3333333333%; }

        body {
            width: 816px;
            height: 1056px;
            margin: 0px auto;
            background: rgb(224, 224, 224);
            font-family: 'Lato', sans-serif !important;
            font-size: 14px
        }
        .invoice {
            background: #ffffff;
            width: 100%;
            height: 100%;
            margin: 0 auto;
            margin-top: 50px;
            margin-bottom: 50px;
            padding: 30px;
        }
        .container { height: 100%; position: relative;}
        .border-2px{border: 2px solid #28282B;}
        .border-1px{border: 2px solid #28282B;}
        .logo { width: 100%; height: 100%; }
        .text-center {text-align: center;}
        .text-left {text-align: left;}
        .text-right {text-align: right;}
        .hr {width: 50%;float: right;height: 2px;background: #000000;}
        .company-info {color: #000;}
        .company-info h3 {font-weight: bold;margin-bottom: 0;}
        .company-info p {margin-bottom: 2px;}
        .m-auto{margin: 0 auto;}
        .company-name{text-transform: uppercase;font-weight: bold;}
        .invoice-title{text-align: center;text-transform: uppercase;font-family: 'Calistoga', cursive !important; margin: 25px 0 15px 0;}
        .border-top-none{border-top: none}
        .border-right-none{border-right: none}
        .border-bottom-none{border-bottom: none}
        .border-left-none{border-left: none}
        .padding-8px{padding: 5px}
        .d-flex{display: flex}
        .justify-content-around{justify-content: space-around}
        .justify-content-between{justify-content: space-between}
        .justify-content-evenly{justify-content: space-evenly}
        .justify-content-center{justify-content: center}
        .present-address{position: relative;}
        .present-address::after{
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            background: #28282B;
            width: 2px;
            height: 140px;
        }
        @media print {
            body{
                header: page-header;
                footer: page-footer;
                sheet-size: Letter;
                margin: 0 !important;
                background: #efefef;
                font-family: 'Fira Code';
                font-family: 'Lato', sans-serif !important;
                font-size: 15px;
            }
            .font-family{ font-family: 'Fira Sans', sans-serif !important; }
            .invoice {margin-top: 0 !important;margin-bottom: 0 !important;}
            .company-info h4 {font-weight: bold;margin-bottom: 0;}
            .company-info p { margin-bottom: 2px; }
        }
    </style>

</head>
<body>

    <section class="invoice">
        <div class="container border-2px">

            <h2 class="invoice-title font-family">Guest Registration Form</h2>

            <!----------- PART-1 [ GUEST ] --------->
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Guest Name</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ $guest->name }}
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Res No</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Spouse Name</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ $guest->spouse_name }}
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Child </b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Company Name</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ $guest->company_id != null ? getCrmCompany($guest->company_id) : '' }}
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Adult </b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Proffession</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ $guest->proffesion }}
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Infant </b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
            </div>


            <!----------- PART-2 [ PRESENT ADDRESS ] --------->
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none border-right-none padding-8px present-address">
                    <div class="">
                        <p class="text-center"><b>Present Address</b></p>
                        <span style="margin: 0 0 0 5px;">{{ $guest->address }}</span>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none">
                    <div class="row d-flex border-2px border-top-none border-left-none border-right-none border-bottom-none padding-8px">
                        <div class="col-4">
                            <b>Ref. Name</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ $guest->reference }}
                        </div>
                    </div>
                    <div class="row d-flex border-2px border-left-none border-right-none border-bottom-none padding-8px">
                        <div class="col-4">
                            <b>NID / Passport No</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ $guest->nid_no }}
                        </div>
                    </div>
                    {{-- <div class="row d-flex border-2px border-left-none border-right-none border-bottom-none padding-8px">
                        <div class="col-4">
                            <b>Passport No</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div> --}}
                    <div class="row d-flex border-2px border-left-none border-right-none border-bottom-none padding-8px">
                        <div class="col-4">
                            <b>Nationality</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ optional($guest->country)->name }}
                        </div>
                    </div>
                </div>
            </div>

            <!----------- PART-3 [ COUNTRY - CITY ] --------->
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Country</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ optional($guest->country)->name }}
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Date Of Birth</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>City</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ $guest->city_id }}
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>VISA No</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Email</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ $guest->email }}
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Pick UP</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Mobile</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">
                            {{ $guest->phone_no }}
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none padding-8px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Drop Off</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none padding-8px" style="height: 40px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Payment Mode</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none padding-8px" style="height: 40px">
                    <div class="row d-flex">
                        <div class="col-4">
                            <b>Guest Status</b>
                        </div>
                        <div class="col-1"><b>:</b></div>
                        <div class="col-7">

                        </div>
                    </div>
                </div>
            </div>

            <!----------- PART-4 [ ARRIVAL - DEPARTURE ] --------->
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none">
                    <div class="row d-flex">
                        <div class="col-4 text-center border-top-none border-left-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">Arrival Date</b>
                            <p></p>
                        </div>
                        <div class="col-4 text-center border-top-none border-left-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">Arrival Flight</b>
                            <p></p>
                        </div>
                        <div class="col-4 text-center border-top-none border-left-none border-right-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">ETA</b>
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none">
                    <div class="row d-flex">
                        <div class="col-4 text-center border-top-none border-left-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">Departure Date</b>
                            <p></p>
                        </div>
                        <div class="col-4 text-center border-top-none border-left-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">Dep Flight</b>
                            <p></p>
                        </div>
                        <div class="col-4 text-center border-top-none border-left-none border-right-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">ETD</b>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 border-2px border-left-none border-bottom-none">
                    <div class="row d-flex">
                        <div class="col-4 text-center border-top-none border-left-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">Room No</b>
                            <p></p>
                        </div>
                        <div class="col-4 text-center border-top-none border-left-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">Room Type</b>
                            <p></p>
                        </div>
                        <div class="col-4 text-center border-top-none border-left-none border-right-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">No of PAX</b>
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="col-6 border-2px border-left-none border-right-none border-bottom-none">
                    <div class="row d-flex">
                        <div class="col-4 text-center border-top-none border-left-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">Room Rate</b>
                            <p></p>
                        </div>
                        <div class="col-4 text-center border-top-none border-left-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">Disc %</b>
                            <p></p>
                        </div>
                        <div class="col-4 text-center border-top-none border-left-none border-right-none border-bottom-none border-2px" style="height: 50px;">
                            <b style="margin: 7px 0 4px 0; display:block">Plan Code</b>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>


            <!----------- PART-5 [ SPECIAL INSTRUCTION ] --------->
            <div class="d-flex" style="width: 100%">
                <div class="text-center border-left-none border-bottom-none border-2px" style="height: 50px; width: 16.4%">
                    <b style="margin: 7px 0 4px 0; display:block">Link Rooms</b>
                    <p></p>
                </div>
                <div class="text-center border-left-none border-bottom-none border-2px" style="height: 50px; width: 16.4%">
                    <b style="margin: 7px 0 4px 0; display:block">Extra Bed</b>
                    <p></p>
                </div>
                <div class="text-center border-left-none border-right-none border-bottom-none border-2px" style="height: 50px; width: 67.2%">
                    <div style="margin: 5px 0 0 10px;">
                        <p style="text-decoration:underline; text-align:left;"><b>Special Instruction:</b></p>
                        <span style="margin: 5px 0 0 0; display: block; text-align:left">BDT 100/AI per room per night with breakfast & Shuttle for 3 adults.</span>
                    </div>
                </div>
            </div>


            <!----------- PART-6 [ PAYMENT AREA ] --------->
            <div class="d-flex border-2px border-left-none border-right-none padding-8px" style="width: 100%; align-items:center">
                <b style="margin-right: 10px">Payment Through:</b>
                <div class="checkbox-item" style="display: flex; align-items: center; margin: 0 10px;">
                    <input type="checkbox" name="" style="border-radius: 0"> <span style="margin-left: 5px">Cash</span>
                </div>
                <div class="checkbox-item" style="display: flex; align-items: center; margin: 0 10px;">
                    <input type="checkbox" name="" style="border-radius: 0"> <span style="margin-left: 5px">Amex</span>
                </div>
                <div class="checkbox-item" style="display: flex; align-items: center; margin: 0 10px;">
                    <input type="checkbox" name="" style="border-radius: 0"> <span style="margin-left: 5px">Visa</span>
                </div>
                <div class="checkbox-item" style="display: flex; align-items: center; margin: 0 10px;">
                    <input type="checkbox" name="" style="border-radius: 0"> <span style="margin-left: 5px">Master</span>
                </div>
                <div class="checkbox-item" style="display: flex; align-items: center; margin: 0 10px;">
                    <input type="checkbox" name="" style="border-radius: 0"> <span style="margin-left: 5px">Bill to company</span>
                </div>
            </div>

            <p class="text-center" style="margin-top: 10px"><b>Note: 10% Service Charge and 15% VAT are included with the rate.</b></p>


            <!----------- PART-7 [ TERMS & CONDITION AREA ] --------->
            <p class="text-left" style="margin: 15px 0 0 10px; font-size: 16px"><b>Terms & Condition for Registration Card</b></p>
            <div class="description" style="height: 150px; overflow: hidden; margin: 5px 0 0px 20px;">
                <ul>
                    @foreach ($guestRegTerms ?? [] as $key => $item)
                        <li style="list-style: none; margin-bottom: 2px">
                            <b>{{ $key+1 }}. </b> {{ $item->title }}
                        </li>
                    @endforeach
                </ul>
            </div>



            <p style="padding: 0 10px; margin-top: 10px">By signing this registration card, I beign the registerd guest of the resort, agree and undertake to pay all charges and expenses incurred by me and my other guest during my stay at the restart, including withour limitation, all room rates, food and beverage charges, taxes and service charges, and to fully settle my account by cash or credit card upon departure or at any time as requrested by the resort.</p>

            <!----------- PART-8 [ FOOTER AREA ] --------->
            <div style="display: flex; justify-content: space-around; margin-top: 25px">
                <div class="block-item">
                    <b>______________________</b>
                    <p style="text-align:center;"><b>Receptionist</b></p>
                </div>
                <div class="block-item">
                    <b>______________________</b>
                    <p style="text-align:center;"><b>F.O.M</b></p>
                </div>
                <div class="block-item">
                    <b>______________________</b>
                    <p style="text-align:center;"><b>Guest Signature</b></p>
                </div>
            </div>


        </div>
    </section>

    <script type="text/javascript">
        window.print();
    </script>

</body>
</html>

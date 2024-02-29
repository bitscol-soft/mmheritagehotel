<style>
    :root {
        --header: rgb(143, 143, 218);
    }

    .widget-header {
        border-bottom: 2px solid var(--header) !important;
    }

    .card {
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;

    }

    .card-header {
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        color: white;

    }

    .card-title {
        padding: 5px;
        padding-top: 0;
        margin-top: 0;
        margin-bottom: 0;
    }

    .card-body {
        padding: 0;
        padding-left: 5px;
        padding-right: 5px;
    }

    .card-bg {
        background: #ced5e4;
    }

    th {
        background: #eaf4fa !important;
        color: black;
    }


    .tableContainer {
        max-height: 230px;
        overflow-y: scroll;
    }

    .invoiceTableContainer {
        max-height: 250px;
        overflow-y: scroll;
    }

    table {
        width: 100%;
    }

    thead {
        position: sticky;
        top: 0px;
        background-color: white;
    }

    .tableContainer>.table>tbody>tr>td {
        padding: 2px;
    }

    .invoiceTableContainer>.table>tbody>tr>td {
        padding: 2px;
    }

    .tableContainer tfoot {
        position: sticky;
        bottom: 0px;
        background-color: #d1e6f8;
        font-weight: bold;
        font-size: 16px;
    }

    .invoiceTableContainer tfoot {
        position: sticky;
        bottom: 0px;
        background-color: #bbdcf8;

    }

    #navbar {
        border-bottom: 2px solid var(--header)
    }

    .product .card-overlay {
        position: absolute;
        top: 35px;
        /* left: 0; */
        width: 97%;
        /* height: 100%; */
        border-radius: 5px;
        background-color: #efefef;
        text-align: center;
        color: #000000;
        opacity: 0;
        -webkit-transition: all 800ms;
        -moz-transition: all 800ms;
        -ms-transition: all 800ms;
        -o-transition: all 800ms;
        transition: all 800ms;
    }

    .product:hover .card-overlay {
        opacity: 0.8;
    }
    .calc-total-amount > span {
        font-size: 18px;
        font-weight: 500;
        margin-top: 8px;
        display: inline-block;
    }
    .paid-by-payment-method {
        color: #000;
        font-size: 14px;
        font-weight: 500;
    }
    .tableContainer tfoot {
        z-index: 99;
    }
    .tableContainer thead {
        z-index: 99;
    }
    .invoice-table{
        margin-bottom: 0 !important
    }

    /*------------ MEDIA QUERY ------------*/
    @media (min-width: 768px){
        #add-product{
            display: block;
        }
    }
    @media (min-width: 992px){
        #add-product{
            display: none;
        }
    }
    @media (max-width:575px){
        .col-m-10{
            width: 10%
        }
        .col-m-20{
            width: 20%
        }
        .col-m-30{
            width: 30%
        }
        .col-m-40{
            width: 40%
        }
        .col-m-50{
            width: 50%
        }
        .d-m-flex{
            display: flex
        }
        .row .margin-bottom-10{
            margin-bottom: 10px !important;
        }
        .margin-bottom-0{
            margin-bottom: 0 !important
        }
        .padding-m-none{
            padding: 0;
        }
        .border-m-right-none {
            border-right: 0 !important;
        }
        .padding-m-left-none{
            padding-left: 0
        }
        .card-date {
            margin: 0px 0 0 0;
            display: block;
            padding-top: 10px;
        }
        .card-header.widget-header{
            min-height: 29px;
        }
        .invoice-list-title {
            margin: 5px 0 0 0;
            font-size: 18px;
        }
        .card-time{
            font-size: 32px !important
        }
        .print-action-btn {
            width: 80px;
            border-width: 2px !important;
            padding: 2px 0 !important;
            font-size: 12px !important;
        }
        .footer-td{
            padding: 5px 5px !important;
        }
        .invoice-table{
            margin-bottom: 0 !important
        }
        .m-b-none{
            margin-bottom: 0
        }
        .row.d-m-flex.m-b-none{
            margin-bottom: 0 !important
        }
        .calc-total-amount {
            font-size: 16px;
            font-weight: 500;
            margin-top: 6px;
            margin-bottom: 20px;
        }
        .invoice-table thead tr th {
            padding: 3px 5px;
            font-size: 12px;
        }

        .tableContainer thead tr th {
            padding: 3px 5px;
            font-size: 12px;
        }
        .sales-price-th{
            width: 22% !important
        }
        .sales-quantity-th{
            width: 18%
        }
        .margin-top-40{
            margin-top: 40px
        }
        #add-product{
            display: block;
        }
        .tableContainer tfoot {
            z-index: 99;
        }
        .tableContainer thead {
            top: -1px;
            z-index: 99;
        }
        .custom-addon-width{
            padding: 3px 5px;
            font-size: 11px;
        }
        .width-40px{
            width: 40px;
        }
        .width-50px{
            width: 50px;
        }
        .width-60px{
            width: 60px;
        }
        .width-70px{
            width: 70px;
        }
        .width-80px{
            width: 80px;
        }
        .width-90px{
            width: 90px;
        }
        .width-100px{
            width: 100px;
        }
        .padding-right-18px{
            padding-right: 18px !important;
        }
        .padding-right-41px{
            padding-right: 41px !important;
        }
        .table-qty-decrease-btn {
            /* padding: 3px 4px !important; */
            /* line-height: 14px; */
            border-width: 2px !important;
        }
        .tableContainer table{
            margin-bottom: 0
        }
        .margin-bottom-none{
            margin-bottom: 0
        }
        .margin-bottom-10px{
            margin-bottom: 10px
        }
        .padding-bottom-15px{
            padding-bottom: 15px
        }
        .text-m-center{
            text-align: center;
        }
    }
    @media (min-width:576px) and (max-width:767px){
        .col-m-10{
            width: 10%
        }
        .col-m-20{
            width: 20%
        }
        .col-m-30{
            width: 30%
        }
        .col-m-40{
            width: 40%
        }
        .col-m-50{
            width: 50%
        }
        .width-50-per{
            width: 50%;
        }
        .tab-d-flex{
            display: flex;
            flex-wrap: wrap;
        }
        .tab-inline-block{
            display: inline-block;
        }
        .d-m-flex{
            display: flex
        }
        .row .margin-bottom-10{
            margin-bottom: 10px !important;
        }
        .margin-bottom-0{
            margin-bottom: 0 !important
        }
        .padding-m-none{
            padding: 0;
        }
        .border-m-right-none {
            border-right: 0 !important;
        }
        .padding-m-left-none{
            padding-left: 0
        }
        .card-date {
            margin: 0px 0 0 0;
            display: block;
            padding-top: 10px;
        }
        .card-header.widget-header{
            min-height: 29px;
        }
        .invoice-list-title {
            margin: 5px 0 0 0;
            font-size: 18px;
        }
        .card-time{
            font-size: 32px !important
        }
        .print-action-btn {
            width: 80px;
            border-width: 2px !important;
            padding: 2px 0 !important;
            font-size: 12px !important;
        }
        .footer-td{
            padding: 5px 5px !important;
        }
        .invoice-table{
            margin-bottom: 0 !important
        }
        .m-b-none{
            margin-bottom: 0
        }
        .row.d-m-flex.m-b-none{
            margin-bottom: 0 !important
        }
        .calc-total-amount {
            font-size: 16px;
            font-weight: 500;
            margin-top: 6px;
            margin-bottom: 20px;
        }
        .invoice-table thead tr th {
            padding: 3px 5px;
            font-size: 12px;
        }

        .tableContainer thead tr th {
            padding: 3px 5px;
            font-size: 12px;
        }
        .sales-price-th{
            width: 22% !important
        }
        .sales-quantity-th{
            width: 18%
        }
        .margin-top-40{
            margin-top: 40px
        }
        #add-product{
            display: block;
        }
        .tableContainer tfoot {
            z-index: 99;
        }
        .tableContainer thead {
            top: -1px;
            z-index: 99;
        }
        .custom-addon-width{
            padding: 3px 5px;
            font-size: 11px;
        }
        .width-40px{
            width: 40px;
        }
        .width-50px{
            width: 50px;
        }
        .width-60px{
            width: 60px;
        }
        .width-70px{
            width: 70px;
        }
        .width-80px{
            width: 80px;
        }
        .width-90px{
            width: 90px;
        }
        .width-100px{
            width: 100px;
        }
        .padding-right-18px{
            padding-right: 18px !important;
        }
        .padding-right-41px{
            padding-right: 41px !important;
        }
        .table-qty-decrease-btn {
            /* padding: 3px 4px !important; */
            /* line-height: 14px; */
            border-width: 2px !important;
        }
        .tableContainer table{
            margin-bottom: 0
        }
        .margin-bottom-none{
            margin-bottom: 0
        }
        .margin-bottom-10px{
            margin-bottom: 10px
        }
        .padding-bottom-15px{
            padding-bottom: 15px
        }
        .text-m-center{
            text-align: center;
        }
        .tab-product-units{
            margin-top: 0 !important;
        }
    }
    @media (min-width:768px) and (max-width:991px){
        #add-product{
            display: block;
        }
    }
    @media (min-width:992px) and (max-width:1199px){
        #add-product{
            display: none;
        }
    }
    @media (min-width:1200px){

    }
    /*------------ / MEDIA QUERY ------------*/



</style>

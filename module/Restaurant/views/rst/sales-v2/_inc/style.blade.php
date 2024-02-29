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
        #add-product{
            display: block;
        }
        .sales-price-th{
            width: 22% !important
        }
        .sales-quantity-th{
            width: 18%
        }
    }
    @media (min-width:576px) and (max-width:767px){
        #add-product{
            display: block;
        }
        .sales-price-th{
            width: 22% !important
        }
        .sales-quantity-th{
            width: 18%
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
</style>

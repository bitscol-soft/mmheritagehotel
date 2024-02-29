<div class="col-sm-3" style="border-right: 1px dotted var(--header);">
    @include('rst/sales-v2/_inc/date-time')

    <div class="card">
        <div class="card-header widget-header">
            <h3 class="invoice-list-title">Invoice List</h3>
        </div>
        <div class="card-body">
            <div class="row d-m-flex">
                <div class="col-m-30 col-sm-6">
                    <div class="radio">
                        <label>
                            <input name="payment_status" value="due" type="radio" class="ace payment_status duePaymentStatus" onchange="load_data('due')" checked>
                            <span class="lbl"> Due List</span>
                        </label>
                    </div>
                </div>
                <div class="col-m-30 col-sm-6 padding-m-left-none">
                    <div class="radio">
                        <label>
                            <input name="payment_status" value="paid" type="radio" class="ace payment_status paidPaymentStatus" onchange="load_data('paid')">
                            <span class="lbl"> Paid List</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row mb-1 mt-1 d-m-flex m-b-none">
                <div class="col-sm-6 sale-list-date">
                    <div class="form-grpup has-float-label">
                        <input type="text" value="{{ getSaleDate() ?? date('Y-m-d') }}" name="date" class="form-control date-picker invoice-date" onchange="load_data()">
                        <label for="date">Date</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group has-float-label">
                        <label for="">Invoice</label>
                        <input type="text" name="invoice_no" class="form-control invoice_no" onkeyup="load_data()">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">

        <div class="card-body">
            <div class="invoiceTableContainer">
                <table class="table table-bordered table-hover invoice-table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Guest</th>
                            <th>Table</th>
                            <th>Invoice No</th>
                            <th>Bill</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-list" class="due-tbody">

                    </tbody>
                    <tfoot style="display:nones">
                        <tr>
                            <td colspan="5" class="footer-td">
                                <div class="col-sm-12 d-m-flex padding-m-left-none">
                                    <div class="col-sm-6 padding-m-left-none">
                                        <button class="btn btn-whites btn-danger btn-xs btn-block print-action-btn">
                                            <i class="fa fa-print"></i> Print
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <a class="btn btn-whites btn-info btn-xs btn-block print-action-btn" href="{{ request()->url() }}">
                                            <i class="fa fa-refresh"></i> Refresh
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="card text-center total-due-amount calc-total-amount text-danger">
        <span>Total Due: </span>
        <span class="total-due-sum">0</span>
    </div>

    <div class="card text-center total-paid-amount calc-total-amount text-danger" style="display: none">
        <span>Total Paid: </span>
        <span class="total-paid-sum">0</span>
    </div>

</div>

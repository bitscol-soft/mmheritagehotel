<div class="form-group">
    <x-widget.text-input-group title="Subtotal(৳)" name="subtotal" value=0 readonly=1 />

</div>

<div class="form-group">
    <x-widget.text-input-group title="Discount(৳)" name="discount" value=0 />

</div>

<div class="form-group">
    <x-widget.text-input-group title="Total vat(৳)" name="total_vat" value=0 readonly=1 />

</div>

<div class="form-group">
    <x-widget.text-input-group title="Grand Total(৳)" name="grand_total" value=0 readonly=1 />

</div>

<div class="form-group">
    <x-widget.text-input-group title="Total Paid(৳)" name="paid_amount" value=0  />

</div>

<div class="form-group">
    <x-widget.text-input-group title="Total Due(৳)" name="due_amount" value=0 readonly=1 />
</div>

<div class="row">
    <div class="pull-right mx-3">
        <div class="form-grouptext-right">
            <button type="button" class="btn-sm btn-outline-danger">
                <i class="fa fa-refresh"></i> Cancel
            </button>
            <button type="button" class="btn-sm btn-outline-success save-purchase">
                <i class="fa fa-save"></i> Submit
            </button>
        </div>
    </div>
</div>

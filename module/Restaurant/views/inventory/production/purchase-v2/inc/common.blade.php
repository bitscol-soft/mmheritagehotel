<div class="row mb-3">
    <div class="col-md-4">
        <x-widget.select-input-group title="Supplier" name="supplier_id" :collections="$suppliers" />

    </div>
    <div class="col-md-4">
        <x-widget.select-input-group title="Account" name="account_id" :collections="$accounts" />


    </div>

    <div class="col-md-2">
        <x-widget.text-input-group title="Challan" name="challan_id" :value="$challan_id" readonly=1 />
    </div>


    <div class="col-md-2">
        <x-widget.text-input-group title="Date" name="date" :value="date('Y-m-d')" readonly=1 class="date-picker"/>
    </div>

</div>
<div class="row mb-3">
    <div class="col-md-8">
        <x-widget.product-select />
    </div>

</div>

<div class="input-group">
    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    <input type="text" name="from_date" class="form-control date-picker" placeholder="From Date" value="{{ request('from_date', date('Y-m-d')) }}">
    <span class="input-group-addon"><i class="fas fa-exchange-alt"></i></span>
    <input type="text" name="to_date" class="form-control date-picker" placeholder="To Date" value="{{ request('to_date', date('Y-m-d')) }}">
</div>

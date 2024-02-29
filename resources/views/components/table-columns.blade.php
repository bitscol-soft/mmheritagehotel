<style>
    .dropdown-check-list {
        display: inline-block;
    }

    .dropdown-check-list .anchor {
        position: relative;
        cursor: pointer;
        display: inline-block;
        padding: 5px 45px 5px 10px;
        border: 1px solid #ccc;
    }

    .dropdown-check-list .anchor:after {
        position: absolute;
        content: "";
        border-left: 2px solid black;
        border-top: 2px solid black;
        padding: 5px;
        right: 10px;
        top: 20%;
        -moz-transform: rotate(-135deg);
        -ms-transform: rotate(-135deg);
        -o-transform: rotate(-135deg);
        -webkit-transform: rotate(-135deg);
        transform: rotate(-135deg);
    }

    .dropdown-check-list .anchor:active:after {
        right: 8px;
        top: 21%;
    }

    .dropdown-check-list ul.items {
        z-index: 1000;
        padding: 2px;
        display: none;
        margin: 0;
        border: 1px solid #ccc;
        border-top: none;
    }
    #items{
        position: absolute;
        z-index: 100000;
        background-color: #fbf3f3; 
    }

    .dropdown-check-list ul.items li {
        list-style: none;
    }
</style>
<div class="row">
    <div class="col-sm-1 col-sm-offset-11 pull-right mb-1">

        <div id="tableColumns" class="dropdown-check-list" tabindex="100">
            <span class="anchor"><i class="fa fa-list fa-2x"></i></span>
            <ul id="items" class="items">
                @foreach ($collections ?? [] as $item)
                        <li>
                            <label class="inline" style="margin-left: 5px">
                                <input type="checkbox" class="ace table-li-items" data-name="{{ $item }}"
                                    {{ isVisibleColumn($table, $item) ? 'checked' : '' }}>
                                <span class="lbl"> {{ ucfirst($item) }}</span>
                            </label>
                        </li>
                    @endforeach
                    <li>
                        <a href="javascript:void(0)" class="btn btn-minier btn-success btn-block" onclick="saveTableColumn(`{{ $table }}`)">
                            <i class="fa fa-save"></i> Save
                        </a>
                    </li>
            </ul>
        </div>
    </div>
</div>


<script>
    var checkList = document.getElementById('tableColumns');
    var items = document.getElementById('items');
    checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
    if (items.classList.contains('visible')) {
        items.classList.remove('visible');
        items.style.display = "none";
    } else {
        items.classList.add('visible');
        items.style.display = "block";
    }

    }

    items.onblur = function(evt) {
    items.classList.remove('visible');
    }





function saveTableColumn(table) {
    console.log(table);
    
    var data = [];
    $('.table-li-items').map(function(item){
        if ($(this).is(':checked')) {
            data.push($(this).data('name'));
        }
    })
    $.get('/hrm/ajax/save-table-columns',{
        table_name: table,
        columns: data,
    },
        function (data) {
            if (data.status) {
                location.reload();
            }
        }
    )
}



</script>
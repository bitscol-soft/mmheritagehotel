<style>
    .md-modal {
        position: fixed;
        top: 40%;
        left: 50%;
        width: 50%;
        max-width: 500px;
        min-width: 300px;
        height: auto;
        z-index: 99999;
        visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        backface-visibility: hidden;
        -webkit-transform: translateX(-50%) translateY(-50%);
        -moz-transform: translateX(-50%) translateY(-50%);
        -ms-transform: translateX(-50%) translateY(-50%);
        transform: translateX(-50%) translateY(-50%);
    }
    .md-show {
        visibility: visible;
    }
    .md-overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        visibility: hidden;
        top: 0;
        left: 0;
        z-index: 9999;
        opacity: 0;
        background: rgba(40, 43, 49, 0.8);
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
    }
    .md-show ~ .md-overlay {
        opacity: 1;
        visibility: visible;
    }
    .md-content {
        color: #333;
        background: #fff;
    }
    .md-effect .md-content {
        -webkit-transform: scale(0.7);
        -ms-transform: scale(0.7);
        transform: scale(0.7);
        opacity: 0;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
    }
    .md-show.md-effect .md-content {
        -webkit-transform: scale(1);
        -ms-transform: scale(1);
        transform: scale(1);
        opacity: 1;
    }
    .extra-charge .main-body{
        border: 4px solid #f1f1f1;
    }
    .extra-charge .modal-action {
        display: flex;
        align-items: center;
        justify-content: end;
        padding-top: 9px;
        padding-bottom: 5px;
    }
    .extra-charge .modal-header{
        padding: 10px;
        text-transform: uppercase;
        font-size: 20px;
    }
    .extra-charge .modal-action .modal-btn {
        border-width: 2px;
        font-size: 13px;
        padding: 4px 9px;
        line-height: 1.38;
        border-radius: 3px;
        margin-left: 5px
    }
    .extra-charge .modal-action .hide-modal {
        border: 2px solid;
        background: #f1f1f1;
        cursor: pointer;
    }
    .extra-charge .modal-action .hide-modal:hover{
        border: 2px solid;
        background: #dc3545;
        border-color: #dc3545;
    }
    .extra-charge .charge-amount {
        padding-left: 10px;
        margin-bottom: 15px;
    }
    .extra-charge .charge-reason{

    }
</style>

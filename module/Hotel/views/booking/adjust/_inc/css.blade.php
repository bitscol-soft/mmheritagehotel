<style>
    .category-item{
        margin: 0 0 30px 12px;
        position: relative;
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .category-item::after{
        content: '';
        position: absolute;
        left: 0;
        bottom: -12px;
        height: 2px;
        width: 100%;
        max-width: 300px;
        background: #dbe5f1;
    }
    .main-row{
        padding: 0 15px;
        margin-bottom: 30px
    }
    .room-item {
        border: 2px solid #9585BF;
        border-radius: 6px;
        margin-bottom: 12px;
        cursor: pointer;
        height: 70px;
        text-align: center;
        font-weight: 500;
        transition: box-shadow .12s ease, transform .12s ease;
    }
    .room-item:hover {
        box-shadow: 0 3px 9px rgba(47, 99, 168, .18);
        transform: translateY(-1px);
    }
    .category-item b {
        background: #f4f7fb;
        border: 1px solid #dbe5f1;
        border-radius: 4px;
        padding: 4px 12px;
        font-size: 14px;
        display: inline-block;
    }
    .room-item .room-number {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        height: 100%;
    }
    .room-item.active {
        background-color: #9585BF !important;
        color: #fff;
    }





















</style>

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
        bottom: -10px;
        height: 4px;
        width: 300px;
        background: #609660;
    }
    .main-row{
        padding: 0 15px;
        margin-bottom: 30px
    }
    .room-item {
        border: 4px solid #9585BF;
        margin-bottom: 20px;
        cursor: pointer;
        height: 70px;
        text-align: center;
        font-weight: 500;
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

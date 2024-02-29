<style>
    .table {
        margin-bottom: 0 !important;
    }

    body {
        counter-reset: section;
        /* Set a counter named 'section', and its initial value is 0. */
    }

    .count:before {
        counter-increment: section;
        content: counter(section);
    }

    select:invalid {
        height: 0px !important;
        opacity: 0 !important;
        position: absolute !important;
        display: flex !important;
    }

    select:invalid[multiple] {
        margin-top: 15px !important;
    }


    .category-header {

        /* padding: 10px; */
        padding-bottom: 14px;
        font-size: 20PX;
        /* background-color: #eaf4fa; */
    }

    .room-info {
        border: 1px solid #606d99;
        text-align: center;
        height: 70px;
        margin-bottom: 20px;
        cursor: pointer;
    }
    .booked-room-info {
        border: 1px solid #606d99;
        text-align: center;
        height: 70px;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .room-info.active {
        background-color: #82af6f;
        border-color: #82af6f;
        color: #fff;
    }

    .booked-room-info span {
            padding-top: 18px;
            font-size: 24px;
            text-transform: uppercase;
        }

    .booked {
        background-color: red;
        color: #fff;
        pointer-events: none;
    }

    .orange {
        background-color: #9585BF;
        color: #fff;
        /* pointer-events: none; */
    }

    .reservation,
    .label-yellow {
        background-color: #ffae00 !important;
        color: #fff;
        pointer-events: none;
    }

    .store {
        background-color: rgb(37, 70, 131);
        color: #fff;
        pointer-events: none;
    }

    .room-info p {
        padding-top: 18px;
        font-size: 24px;
        text-transform: uppercase;
    }

    .booked-room-info span {
            padding-top: 18px;
            font-size: 24px;
            text-transform: uppercase;
    }

    .search-available .btn {
        padding: 2px 15px;
        border-radius: 3px;
    }
    .room-heading-right{
            float: right;
            right: 0;
            top: 0;
            padding: 0 7px;
            margin-right: 8px;
            width: 10%;
            position: relative;
            cursor: all-scroll;
        }
</style>

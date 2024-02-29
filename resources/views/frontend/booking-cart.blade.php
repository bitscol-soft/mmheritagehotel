@extends('frontend.layouts.master')

@push('custom_css')
    <style>
        .footer-title {
            text-align: left !important;
            font-size: 16px;
            font-weight: 600;
            border-right: 1px solid #ccc;
            padding-left: 20px !important;
        }

        .table {
            margin-bottom: 0
        }

        .booking-close {
            cursor: pointer;
        }
    </style>
@endpush

@section('frontend-content')


    @php
        if (Cookie::get('booking_cart')) {
            $get_cookie = \stripslashes(Cookie::get('booking_cart'));
            $cart_data = \json_decode($get_cookie, true);
        } else {
            $cart_data = [];
        }

        $subtotal = 0;
    @endphp

    {{-- Cart Page Start --}}

    <div class="booking-cart">
        <div class="container">
            <div class="booking-cart-body">
                <h1>Booking Cart</h1>
                <div class="cart-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Room Type</th>
                                <th>Guest (qty)</th>
                                <th>Check-in</th>
                                <th>Check-Out</th>
                                <th>Night</th>
                                <th>Gross Total</th>
                            </tr>
                        </thead>
                        <tbody class="cart-list">
                            @if (!empty($cart_data))
                                @foreach ($cart_data as $booking)
                                    @php
                                        $line_price = $booking['item_price'];
                                        $subtotal += $line_price;
                                    @endphp
                                    <tr class="cart-items">
                                        <td class="booking-close"><i class="fa fa-times"
                                                onclick="bookingRemove(this,{{ $booking['item_id'] }})"></i></td>
                                        <td><b>{{ $booking['item_name'] }}</b></td>
                                        <td>{{ $booking['guest_capacity'] }}</td>
                                        <td>{{ date('M d, Y', strtotime($booking['check_in'])) }}</td>
                                        <td>{{ date('M d, Y', strtotime($booking['check_out'])) }}</td>
                                        <td>{{ $booking['nights'] }}</td>
                                        <td>{{ number_format($booking['item_price'], 2) }}</td>
                                    </tr>
                                @endforeach

                                {{-- Table Footer --}}

                                @php
                                    $vat = hotelVat()->hotel_vat;
                                    $vat_amount = ($subtotal * $vat) / 100;
                                    $grand_total = $subtotal + $vat_amount;

                                @endphp
                                <tr>
                                    <td colspan="6" class="footer-title">Subtotal</td>
                                    <td>{{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="footer-title">Vat ({{ $vat }}%)</td>
                                    <td>{{ number_format($vat_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="footer-title">Grand Total</td>
                                    <td>{{ number_format($grand_total, 2) }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="7">Your Cart is empty!!</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="checkout text-right" style="margin-top: 20px">
                    <button type="submit" class="btn btn-checkout">Checkout</button>
                </div>
            </div>
        </div>
    </div>









@endsection

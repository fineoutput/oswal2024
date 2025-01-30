<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Css file include -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <title>Oswal Soap Delivery Challan</title>
</head>
<body style="padding-top:75px;">
    <div class="container main_container">
        <div class="row">
            <div class="col-sm-6 oswal_logo">
                <img src="{{ asset('assets/frontend/main_css/images/logo-customizer-white/oswal-logo.png') }}" class="img-fluid" style="width:90px;">
            </div>
        </div>
        <br>
        <div class="container" style="font-size:20px;">
            <div class="">Customer Name: 
                {{ $user->first_name ?? '' }}
            </div>
            <div class=""><br>Customer Address:<br>
                Address:
                @php
                // dd($user);
                    $address = $order1_data->address ?? '';
                    $addres = $address->address ?? '' ;
                    $location_addres = $address->location_address ?? '';
                    $doorflat = $address->doorflat ?? '';
                    $landmark = $address->landmark ?? '';
                    $zipcode = $address->zipcode ?? '';
                    $state = $address->state->state_name ?? '';
                    $city = $address->city->city_name ?? '';
                @endphp
                {{ empty($location_addres) ? $addres : $doorflat . ", " . $landmark . ", " . $location_addres }}
                <br>State/UT Code : RJ14<br>
                Place of supply: {{ $city }}<br>
                Place of delivery: {{ $city }}, {{ $state }}<br>
                Zipcode: {{ $zipcode }}<br>
            </div>
            <div class=""><br><br>
                <p><strong>Return to:</strong></p>
                <span class="seller_details">Oswal Soap Group<br>
                Uttam chand desraj 10/oB/35, oswal tower, Ring Road Project,<br> Near Reliance petrol Pump, Lakeshra, Agra Road, Jaipur-302006<br>
                +91-91161 71956<br>
                www.oswalsoap.com<br></span>
            </div>
            <input type="hidden" value="{{ $order1_data->sub_total ?? '' }}" id="tot_amnt">
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        window.onload = function() {
            var unit_mrp = $(".unit_mrp").text();
            var unit_qty = $(".qty").text();
            var total_unit_mrp = parseInt(unit_mrp) * parseInt(unit_qty);
            $('.net_unit_mrp').text(total_unit_mrp);

            var total_amount = document.getElementById("tot_amnt").value;
            inWords(total_amount);
            window.print();
        };

        var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
        var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

        function inWords (num) {
            if ((num = num.toString()).length > 9) return 'overflow';
            n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
            if (!n) return; var str = '';
            str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
            str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
            str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
            str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
            str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
            $("#checks123").text(str);
        }
    </script>
</body>
</html>

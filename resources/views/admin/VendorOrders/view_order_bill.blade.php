<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <!-- Css file include -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  <title>Oswal Soap Bill</title>
</head>

<body style="padding-top:75px;">
  <div class="container main_container">
    <div class="row">
      <div class="col-sm-6 oswal_logo">
        <img src="{{ asset('uploads/image/2024/7/31/Crm/112342.png') }}" class="img-fluid" style="width:90px;">
      </div>
      <div class="col-sm-6 content_part">Tax Invoice/Bill of Supply/Cash Memo
        <p>(Original for Recipient)</p>
      </div>
    </div><br>

    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <span class="font-weight-bold">Sold By</span><br>
          <span class="seller_details">
            Oswal Soap Pvt. Ltd.<br>
            Uttam chand desraj 10/oB/35, oswal tower, Ring Road Project,<br>
            Near Reliance petrol Pump, Lakeshra, Agra Road, Jaipur-303012<br>
            +91-91161 71956<br>
            www.oswalsoap.com<br>
          </span>
        </div>

        <div class="col-sm-6 billing_content">
          <span class="font-weight-bold">Billing Address:</span><br>
          User: {{ $user->first_name ?? 'N/A' }}<br>
          Email: {{ $user->email ?? 'N/A' }}<br>
          Contact: {{ $user->contact ?? 'N/A' }}<br><br>
          <span class="font-weight-bold">Address:</span><br>
          {{ $address->address ?? 'No address' }}<br>
          State/UT Code: RJ14<br>
          Place of supply: {{ $city ?? 'N/A' }}<br>
          Place of delivery: {{ $city ?? 'N/A' }}, {{ $state ?? 'N/A' }}<br>
          Zipcode: {{ $zipcode ?? 'N/A' }}<br>
        </div>
      </div>

      <br>
      <div class="row" style="margin-left: 0px;">
        PAN NO: AACCO9549F <br>
        GST REGISTRATION NO: 08AACCO9549F1Z4 <br>
      </div>
      <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6 shipping_content">
          <span class="font-weight-bold">Shipping Address:</span><br>
          Address: {{ $address->address ?? 'No address' }}<br>
          State/UT Code: RJ14<br>
          Place of supply: {{ $city ?? 'N/A' }}<br>
          Place of delivery: {{ $city ?? 'N/A' }}, {{ $state ?? 'N/A' }}<br>
          Zipcode: {{ $zipcode ?? 'N/A' }}<br>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          Order No: {{ $order->id ?? 'N/A' }}<br>
          <p>Order Date: {{ $order->created_at->format('F j, Y, g:i a') ?? 'N/A' }}</p>
        </div>
        <div class="col-sm-6 invoice_content">
          Invoice No: OSWAL-RJ-{{ $invoice->invoice_no < 10 ? '0'.$invoice->invoice_no : $invoice->invoice_no ?? '00' }}<br>
        </div>
      </div>
    </div>

    <div class="container">
      <table class="table table-black">
        <thead class="product_table">
          <tr>
            <th>SNo.</th>
            <th>Product</th>
            <th>HSN Code</th>
            <th>Unit Name</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Net Amount</th>
            <th>Tax Rate</th>
            <th>Tax Type</th>
            <th>Tax Amount</th>
            <th>Total Amount</th>
          </tr>
        </thead>
        <tbody>
          @php
            $total_tax = 0; 
          @endphp
          @foreach($orderItems as $key => $item)
          @php $total_tax += $item->vendortype->gst_percentage_price * $item->quantity @endphp
            <tr class="product_table2">
              <td>{{ $loop->iteration }}</td>
              <td>{{ $item->product->name ?? 'N/A' }}</td>
              <td>{{ $item->product->hsn_code ?? 'N/A' }}</td>
              <td>{{ $item->vendortype->type_name ?? 'N/A' }}</td>
              <td>Rs. {{ $item->vendortype->mrp ?? 'N/A' }}</td>
              <td>{{ $item->quantity }}</td>
              <td>Rs. {{ $item->vendortype->mrp * $item->quantity }}</td>
              <td>{{ $item->vendortype->gst_percentage ?? 18}}%</td>
              <td> @if($address->state == 29) CGST<br>SGST  @else IGST @endif</td>
              <td>
                @if($address->state == 29)
                  Rs. {{ ($item->vendortype->gst_percentage_price * $item->quantity) / 2 }}<br>
                  Rs. {{ ($item->vendortype->gst_percentage_price * $item->quantity) / 2 }}
                @else
                  Rs. {{ $item->vendortype->gst_percentage_price * $item->quantity }}
                @endif
              </td>
              <td>Rs. {{ $item->amount }}</td>
            </tr>

          @endforeach
          @if($order->gift_id != 0)
            <tr class="product_table2">
              <td>{{ $orderItems->count() + 1 }}</td>
              <td>{{ $giftCard->name ?? 'N/A' }}</td>
              <td></td>
              <td></td>
              <td>Rs. {{ $giftCard->price ?? 'N/A' }}</td>
              <td>1</td>
              <td>Rs. {{ $giftCard->price ?? 'N/A' }}</td>
              <td>%</td>
              <td>IGST</td>
              <td>Rs. {{ $order->gift_gst_amt }}</td>
              <td>Rs. {{ $order->gift_amt }}</td>
            </tr>
          @endif
          <th>Total</th>
          <th class="product_table" colspan="2"></th>
          {{-- <th class="product_table">{{ $order->total_order_weight }} kg/ltr</th> --}}
          <th class="product_table"></th>
          <th class="product_table" colspan="4"></th>
          <!-- <th class="product_table">36.0%</th> -->
          <th class="product_table" colspan="1"></th>
          <th class="product_table">₹ {{ $total_tax + (float)($order->gift_gst_amt ?? 0)}}</th>
          <th class="product_table">₹ {{( $order->gift_amt != null) ? $order->gift_amt + $order->sub_total: $order->sub_total }}</th>
        </tr>
          {{-- <tr>
            <th>Free Gift</th>
            <th class="product_table" colspan="2"></th>
            <th class="product_table">Oswal Chana Dal</th>
            <th class="product_table" colspan="4"></th>
            <!-- <th class="product_table">36.0%</th> -->
            <th class="product_table" colspan="1"></th>
            <th class="product_table"></th>
            <th class="product_table">+ ₹0</th>
          </tr> --}}

         <tr>
          <th colspan="9">Delivery Charge</th>
          <th class="product_table"> </th>
          <th class="product_table">+ ₹ {{$order->order_shipping_amount }}</th>
        </tr>
        <tr>
          <th colspan="9">COD Charge</th>
          <th class="product_table"> </th>
          <th class="product_table"> 
            @if($order->payment_type == 1)
            + ₹ {{ number_format($order->cod_charge , 2)}}
              @else 
              ₹. N/A  @endif
            </th>
        </tr>
    
        @if(isset($promocode) && is_object($promocode))
          <tr>
              <th colspan="9">Promocode: {{ $promocode->promocode }}</th>
              <th class="product_table"></th>
              <th class="product_table">
                  @if($order->promo_deduction_amount > 0)
                      - ₹ {{ $order->promo_deduction_amount ?? 0 }}
                  @endif
              </th>
          </tr>
      @else
          <tr>
              <th colspan="9">Promocode: N/A</th>
              <th class="product_table"></th>
              <th class="product_table">
                  
                - ₹ {{ $order->promo_deduction_amount ?? ₹. N/A }}
                  
              </th>
          </tr>
      @endif

        {{-- <tr>
          <th colspan="9"> Discount </th>
          <th class="product_table"> </th>
          <th class="product_table">-₹ {{ $order->extra_discount ?? 0  }}</th>
        </tr> --}}
        <tr>
          @php
          $sub_total = (float) ($order->sub_total ?? 0);
          $gift_amt = (float) ($order->gift_amt ?? 0);
          $cod_charge = (float) ($order->cod_charge ?? 0);
          $order_shipping_amount = (float) ($order->order_shipping_amount ?? 0);
          $promo_deduction_amount = (float) ($order->promo_deduction_amount ?? 0);
          $extra_discount = (float) ($order->extra_discount ?? 0);
      
          $total_amount = $sub_total + $gift_amt + $cod_charge + $order_shipping_amount - $promo_deduction_amount - $extra_discount;
      @endphp
      
          <th colspan="9">SubTotal</th>
          <th class="product_table"> </th>
          <th class="product_table" id="tot_amnt">₹ {{ $total_amount }}</th>
        </tr>
        </tbody>
      </table>
      <h6 class="amount_content ">Amount in Words:<br>
        <span id="checks123" style="text-transform: capitalize;font-style: revert;"> </span>
      </h6><br>
      <h4 class="oswal_head ">Oswal Soap Pvt. Ltd. :</h4>
      <div style="display:flex;justify-content:space-between; padding-top:8rem;">
        <h4 class="oswal_head" style="border-top:1px solid black">Authorized Signatory </h4>
        <h4 class="oswal_head" style="border-top:1px solid black"> Customers Signatory </h4>
      </div>
      <h5 class="warning">Whether tax is payable under reverse charge-No</h5>
    </div>
  </div>
  </div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
//alert('Changed!')
$('#gst_percentage').keyup(function() {
  // alert("Key up detected");
  var total_price = $("#mrp").val();
  //var gst_percentage = $("#gst_percentage").val();$(this).val
  var gst_percentage = $(this).val();
  var gst_price = (total_price * gst_percentage) / 100;
  var total_gst_price = parseInt(total_price) + parseInt(gst_price);
  //alert(total_gst_price);
  $('#gst_percentage_price').val(gst_price);
  $('#selling_price').val(total_gst_price);
});
</script>
<script>
    window.onload = function() {
      var unit_mrp = $(".unit_mrp").text();
      var unit_qty = $(".qty").text();
      //var gst_percentage = $("#gst_percentage").val();$(this).val
      var total_unit_mrp = parseInt(unit_mrp) * parseInt(unit_qty);
      //alert(total_gst_price);
      $('.net_unit_mrp').text(total_unit_mrp);
      var total_amount = document.getElementById("tot_amnt").value;
      //alert(total_amount);
      inWords(total_amount);
      window.print();
    };
    var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
    var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    function inWords(num) {
      if ((num = num.toString()).length > 9) return 'overflow';
      n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
      if (!n) return;
      var str = '';
      str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
      str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
      str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
      str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
      str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
      //return str;
      // alert(str);
      $("#checks123").text(str);
    }
  </script>
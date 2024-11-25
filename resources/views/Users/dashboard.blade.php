@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<style>
.star-rating {
  border: solid 1px #ccc;
  display: flex;
  flex-direction: row-reverse;
  font-size: 1.5em;
  justify-content: space-around;
  padding: 0 .2em;
  text-align: center;
  width: 5em;
}

.star-rating input {
  display: none;
}

.star-rating label {
  color: #ccc;
  cursor: pointer;
}

.star-rating :checked ~ label {
  color: #f90;
}

.star-rating label:hover,
.star-rating label:hover ~ label {
  color: #fc0;
}
</style>
<div class="container-fluid">

    <div class="neword-container d-flex">

        <div class="col-lg-2 col-md-4 col-12 neword-sidebar">

            <ul>
                <li class="asac_shradder menu-item" onclick="showSection('neword-orders')">
                    <i class="fa-solid fa-cart-plus"></i> Orders
                </li>
                <li class="asac_shradder menu-item" onclick="showSection('neword-address')">
                    <i class="fa-solid fa-location-dot"></i> Address
                </li>
                <li class="asac_shradder menu-item" onclick="showSection('neword-wallet')">
                    <i class="fa-solid fa-wallet"></i> Wallet
                </li>
                <li class="asac_shradder menu-item" onclick="showSection('neword-account')">
                    <i class="fa-solid fa-file-invoice"></i> Account
                </li>
                <li class="asac_shradder menu-item">
                    <i class="fa-solid fa-lock"></i> <a href="{{ route('logout') }}">
                        <button class="btn btn-danger btn-sm">Logout</button>
                    </a>
                </li>
                <li class="asac_shradder menu-item">
                <i class="fa-solid fa-map-location"></i>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#basicModal">Select State</button>
                </li>
            </ul>
        </div>

        <div class="col-lg-10 col-md-8 col-12 neword-content">

            <div class="table-responsive">

                <div id="neword-orders" class="neword-section">
                    <h2>Orders</h2>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Order Id</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Shipping Charge</th>
                                <th>COD Charge</th>
                                <th>Payment Status</th>
                                <th>Promocode Discount</th>
                                <th>Final Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Track</th>
                                <th>Cancel</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orderlists as $order)
                            <tr>
                                <td>{{ $order['order_id'] }}</td>
                                <td>{{ date('Y-m-d' , strtotime($order['date'])) }}</td>
                                <td>{{ formatPrice($order['sub_total']) }}</td>
                                <td>{{ formatPrice($order['delivery_charge']) }}</td>
                                <td>{{formatPrice($order['cod_charge'])}}</td>
                                <td>
                                    {{$order['payment_type']}}
                                </td>
                                <td>{{ formatPrice($order['promocode_discount']) }}</td>
                                <td>{{ formatPrice($order['amount']) }}</td>
                                <td>{{ $order['order_status'] }}</td>
                                <td><a href="{{ route('user.get-order-details',['id' => encrypt($order['order_id'])]) }}"><button class="btn btn-warning btn-sm">View</button< /a>
                                </td>
                                <td><button class="btn btn-warning btn-sm">Track</button></td>

                                <td onclick="window.alert()">
    @if(in_array($order['order_status'], ['Confirmed', 'Dispatched', 'Delivered', 'Rejected']))
        -----
    @else
        <a href="{{ route('user.cancle-order', ['id' => encrypt($order['order_id'])]) }}" onclick="return confirmCancel(event)">
            <button class="btn btn-danger btn-sm">Cancel</button>
        </a>
    @endif
</td>
<td>
    <div class="star-rating">
        <!-- Dynamically create unique IDs for each rating -->
        <input type="radio" id="5-stars-order{{ $order['order_id'] }}" name="rating-order{{ $order['order_id'] }}" value="5" />
        <label for="5-stars-order{{ $order['order_id'] }}" class="star">&#9733;</label>

        <input type="radio" id="4-stars-order{{ $order['order_id'] }}" name="rating-order{{ $order['order_id'] }}" value="4" />
        <label for="4-stars-order{{ $order['order_id'] }}" class="star">&#9733;</label>

        <input type="radio" id="3-stars-order{{ $order['order_id'] }}" name="rating-order{{ $order['order_id'] }}" value="3" />
        <label for="3-stars-order{{ $order['order_id'] }}" class="star">&#9733;</label>

        <input type="radio" id="2-stars-order{{ $order['order_id'] }}" name="rating-order{{ $order['order_id'] }}" value="2" />
        <label for="2-stars-order{{ $order['order_id'] }}" class="star">&#9733;</label>

        <input type="radio" id="1-star-order{{ $order['order_id'] }}" name="rating-order{{ $order['order_id'] }}" value="1" />
        <label for="1-star-order{{ $order['order_id'] }}" class="star">&#9733;</label>
    </div>
</td>

                            </tr>
                            @empty
                            No Order Found
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>

            <div id="neword-address" class="neword-section" style="display:none;">

                <div class="view_address">
                    <h2>Address</h2>
                    <a href="{{ route('user.add-address', ['redirect' => 'user']) }}">
                        <button type="button" class="animated-button">
                            <span>Add Address</span>
                            <span></span>
                        </button>
                    </a>

                </div>
                <hr>

                @forelse ($address_data as $key => $address)

                <div id="neword-address-info{{ ++$key }}" class="neword-address">

                    <p><strong>Name:</strong> {{ $address->name }}</p>
                    <p><strong>Address:</strong> {{ $address->custom_address }}</p>
                    <p><strong>State:</strong> {{ $address->states->state_name }}</p>
                    <p><strong>City:</strong> {{ $address->citys->city_name }}</p>
                    <p><strong>Landmark:</strong>{{ $address->landmark }}</p>
                    <p><strong>Pincode:</strong> {{ $address->zipcode }}</p>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('user.add-address',['redirect' => 'user', 'id' => base64_encode($address->id)]) }}">
                            <button class="animated-button">
                                <span><i class="fa-solid fa-pencil"></i></span>
                                <span></span>
                            </button>
                        </a>
                        <a href="{{ route('user.delete-address',['id' => base64_encode($address->id)]) }}">
                            <button class="animated-button">
                                <span><i class="fa-solid fa-trash"></i></span>
                                <span></span>
                            </button>
                        </a>
                    </div>

                </div>

                @endforeach
            </div>

            <div id="neword-wallet" class="neword-section" style="display:none;">
                <h2>Wallet</h2>
                <div class="wallet-container">
                    <div class="wallet-header">
                        <h1>My Wallet</h1>
                        <div class="balance">{{ formatPrice(Auth::user()->wallet_amount) }}</div>
                    </div>

                    <div class="transaction-list">

                        @foreach ($walletTransactions as $transaction)

                        @if ( $transaction['amount'] == "0.0")
                        @continue
                        @endif

                        <div class="transaction-card d-flex align-items-center">

                            @if ($transaction['transaction_type'] == 'debit' )

                            <div class="transaction-icon gone">
                                <i class="fas fa-arrow-up"></i>
                            </div>

                            <div class="transaction-details">
                                <h5>{{ $transaction['description'] }}</h5>
                                <span>{{ $transaction['date'] }}</span>
                            </div>

                            <div class="transaction-amount sent">- {{ formatPrice($transaction['amount']) }}</div>

                            @else

                            <div class="transaction-icon get">
                                <i class="fas fa-arrow-down"></i>
                            </div>

                            <div class="transaction-details">
                                <h5>{{ $transaction['description'] }}</h5>
                                <span>{{ $transaction['date'] }}</span>
                            </div>

                            <div class="transaction-amount received">+ {{ formatPrice($transaction['amount']) }}</div>

                            @endif


                        </div>

                        @endforeach

                    </div>

                </div>




            </div>

            <div id="neword-account" class="neword-section" style="display:none;">

                <div class="row justify-content-center">

                    <div class="col-lg-8 col-sm-12 col-md-8">

                        <div class="container mt-5">

                            <h2>Account Details</h2>

                            <form>

                                <div class="row">

                                    <div class="form-group col-md-6">

                                        <label>User name <span class="required">*</span></label>

                                        <input readonly="" class="form-control" value="{{ Auth::User()->first_name }}" name="name" type="text">

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label>email <span class="required">*</span></label>

                                        <input readonly="" class="form-control" value="{{ Auth::User()->email }}" name="email">

                                    </div>

                                    <div class="form-group col-md-12">

                                        <label>Phone Number <span class="required">*</span></label>

                                        <input required="" class="form-control" name="phonenumber" readonly="" value="{{Auth::user()->contact }}" type="number">

                                    </div>

                                    {{-- <div class="col-md-12">

                                            <button type="submit" class="animated-button">

                                                <span>Save</span>

                                                <span></span>

                                            </button>

                                        </div> --}}

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="neword-logout" class="neword-section" style="display:none;">
                <h2>
                    <a href="{{ route('logout') }}">
                        <button class="btn btn-danger btn-sm">Logout</button>
                    </a>
                </h2>

            </div>
        </div>

    </div>
</div>



@endsection

@push('scripts')

<script>
    window.onload = function() {

        showSection('neword-orders');
    };
</script>
<script>
    document.getElementById('cancelButton').addEventListener('click', function() {
        this.classList.add('canceled');
        this.textContent = 'Order Canceled';
    });

    function showSection(sectionId) {
        // Hide all sections
        var sections = document.querySelectorAll('.neword-section');
        sections.forEach(function(section) {
            section.style.display = 'none';
        });

        // Show the selected section
        var activeSection = document.getElementById(sectionId);
        if (activeSection) {
            activeSection.style.display = 'block';
        }

        // Remove active class from all list items
        var listItems = document.querySelectorAll('.neword-sidebar ul li');
        listItems.forEach(function(item) {
            item.classList.remove('active-list-item');
        });

        // Add active class to the clicked list item
        var activeItem = Array.from(listItems).find(item => {
            return item.getAttribute('onclick') === `showSection('${sectionId}')`;
        });

        if (activeItem) {
            activeItem.classList.add('active-list-item');
        }
    }

    function confirmCancel(event) {
        // Show confirmation dialog
        const userConfirmed = confirm('Are you sure you want to cancel this order?');
        
        // If user clicks 'Yes', return true to proceed with the link
        // If user clicks 'No', return false to prevent the action
        return userConfirmed;
    }
</script>
@endpush
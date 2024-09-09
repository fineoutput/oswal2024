@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

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
                        <i class="fa-solid fa-lock"></i>  <a href="{{ route('logout') }}">
                            <button class="btn btn-danger btn-sm">Logout</button>
                        </a>
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
                                    <th>Promocode Discount</th>
                                    <th>Final Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Track</th>
                                    <th>Cancel</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orderlists as $order)
                                <tr>
                                    <td>{{ $order['order_id'] }}</td>
                                    <td>{{ date('Y-m-d' , strtotime($order['date'])) }}</td>
                                    <td>{{ formatPrice($order['sub_total']) }}</td>
                                    <td>{{ formatPrice($order['delivery_charge']) }}</td>
                                    <td>{{ formatPrice($order['promocode_discount']) }}</td>
                                    <td>{{ formatPrice($order['amount']) }}</td>
                                    <td>{{ $order['order_status'] }}</td>
                                    <td><a href="{{ route('user.get-order-details',['id' => encrypt($order['order_id'])]) }}"><button class="btn btn-warning btn-sm">View</button< /a>
                                    </td>
                                    <td><button class="btn btn-warning btn-sm">Track</button></td>

                                    <td>

                                        @if(in_array($order['order_status'], ['Confirmed','Dispatched','Delivered' ,'Rejected']))
                                            -----
                                        @else
                                            <a href="{{ route('user.cancle-order',['id' => encrypt($order['order_id'])]) }}">
                                                <button class="btn btn-danger btn-sm">Cancel</button>
                                            </a>

                                        @endif
                                    
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
                        <a href="{{ route('user.add-address') }}">
                            <button class="animated-button">
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
                            <a href="{{ route('user.add-address',['id' => base64_encode($address->id)]) }}">
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

                                            <input required="" class="form-control" name="phonenumber" readonly="" value="9461937396" type="text">

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

        window.onload = function() {
          
            showSection('neword-orders');
        };
    </script>
@endpush

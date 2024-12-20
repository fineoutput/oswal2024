@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #db9822;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
            background-color: #218838;
        }
    </style>

    <div class="container">

        <h2>Add Address</h2>

        <form action="{{ route('user.stor-address') }}" method="post">

            @csrf

            @if ($address != null) <input type="hidden" name="address_id" value="{{$address->id}}"> @endif

            <input type="hidden" value="{{ $redirect }}" name="redirect">
            
            <div class="form-group">

                <input type="hidden" name="name" value="{{ Auth::user()->first_name }}" required>
                
            </div>

            <div class="form-group">

                <label for="lastName">Doorflat</label>

                <input type="text" id="doorflat" name="doorflat" value="{{ $address ? $address->doorflat : old('doorflat') }}" required>

                @error('doorflat')

                    <div style="color:red">{{ $message }}</div>

                @enderror
            </div>

            <div class="form-group">

                <label for="email">Landmark</label>

                <input type="text" id="landmark" name="landmark" value="{{ $address ? $address->landmark : old('landmark') }}" required>

                @error('landmark')

                    <div style="color:red">{{ $message }}</div>

                @enderror
            </div>

            <div class="form-group">

                <label for="state">State</label>

                <select class="form-control select2 p-0 pt-2" name="state" id="addressstate" onchange="getCity('{{ route('getcity') }}', 'city-container1')">
                    <option value="99999">Choose State</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}" {{ old('state') == $state->id || (isset($address) && $address->state == $state->id) ? 'selected' : '' }}>
                            {{ $state->state_name }}
                        </option>
                    @endforeach
                </select>

                @error('state')

                    <div style="color:red">{{ $message }}</div>

                @enderror
            </div>

            <div class="form-group">

                <label for="city">City</label>

                <select class="form-control select2 p-0 pt-2" name="city" id="city-container1">
                    <option value="">----- Select City -----</option>
                    @if ($address != null)
                        @foreach ($cities as $citie)
                            <option value="{{ $citie->id }}" {{ isset($address) && $address->city == $citie->id ? 'selected' : '' }}>
                                {{ $citie->city_name }}
                            </option>
                        @endforeach
                    @endif
                </select>

                @error('city')

                    <div style="color:red">{{ $message }}</div>

                @enderror

            </div>

            <div class="form-group">

                <label for="address">Address</label>

                <input type="text" id="address" name="address" value="{{ $address ? $address->address : old('address') }}" required>

                @error('address')

                    <div style="color:red">{{ $message }}</div>

                @enderror
            </div>

            <div class="form-group">

                <label for="zipcode">Zipcode</label>

                <input type="text" id="zipcode" name="zipcode" value="{{ $address ? $address->zipcode : old('zipcode') }}" required>

                @error('zipcode')

                    <div style="color:red">{{ $message }}</div>

                @enderror
            </div>

            <div class="form-group">

                <button type="">Update Information</button>

            </div>

        </form>

    </div>

@endsection


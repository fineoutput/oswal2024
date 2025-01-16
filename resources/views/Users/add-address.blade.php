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
        #map {
      height: 500px;
      width: 100%;
    }
    </style>

    <div class="container">

        <h2>Add Address</h2>

        <form action="{{ route('user.stor-address') }}" method="post">

            @csrf

            @if ($address != null)
             <input type="hidden" name="address_id" value="{{$address->id}}">
              @endif

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
            <input type="hidden" name="latitude" id="latitudeInput">
            <input type="hidden" name="longitude" id="longitudeInput">

            <div class="form-group">

                <button type="">Update Information</button>

            </div>

        </form>

        <h1>Google Maps Location Picker</h1>
<div id="map" style="height: 500px;"></div>
<p>Selected Location: <span id="location"></span></p>
<button class="animated-button" id="locateButton">Get current location</button>
</div>
<!-- Load Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZ5Ns4p7EPERf63-neMQvYI8EqYnW3Vns&callback=initMap&libraries=places" async defer></script>

  <!-- Main Script -->
  <script>
//   document.addEventListener("DOMContentLoaded", () => {
//         // Retrieve location from localStorage
//         const userLocation = localStorage.getItem("userLocation");
//         if (userLocation) {
//             const location = JSON.parse(userLocation);
//             const latitude = location.latitude;
//             const longitude = location.longitude;

//             console.log("Latitude:", latitude);
//             console.log("Longitude:", longitude);

//             // Populate hidden inputs
//             document.getElementById("latitudeInput").value = latitude;
//             document.getElementById("longitudeInput").value = longitude;
//         } else {
//             console.error("No location data found in localStorage.");
//         }
//     });

document.addEventListener("DOMContentLoaded", () => {
    // Ensure the DOM is fully loaded before attempting to manipulate elements

    // Retrieve location from localStorage
    const userLocation = localStorage.getItem("userLocation");

    // Check if the location exists in localStorage
    if (userLocation) {
        // Parse the JSON string to an object
        const location = JSON.parse(userLocation);

        // Extract latitude and longitude from the parsed object
        const latitude = location.latitude;
        const longitude = location.longitude;

        // Log the values to the console for debugging
        console.log("Latitude:", latitude);
        console.log("Longitude:", longitude);

        // Ensure that the input fields are available before setting their values
        const latitudeInput = document.getElementById("latitudeInput");
        const longitudeInput = document.getElementById("longitudeInput");

        if (latitudeInput && longitudeInput) {
            // Set the values for the hidden inputs
            latitudeInput.value = latitude;
            longitudeInput.value = longitude;

            console.log(latitudeInput.value, longitudeInput.value, 'Inputs Updated');
        } else {
            console.error("Hidden input elements not found.");
        }
    } else {
        console.error("No location data found in localStorage.");
    }
});

    document.addEventListener("DOMContentLoaded", () => {
    // Function to request and store location
    function requestLocation() {
        // Clear previously denied state if present
        if (navigator.permissions) {
            navigator.permissions
                .query({ name: "geolocation" })
                .then((permissionStatus) => {
                    // If permission was previously denied or prompt was dismissed, ask again
                    if (permissionStatus.state === "denied") {
                        console.log("Clearing previous denied state for Geolocation.");
                        localStorage.removeItem("userLocation"); // Remove stored location if any
                    }

                    // Request location
                    getLocation();
                })
                .catch((error) => {
                    console.error("Error checking permissions:", error);
                    // Fallback to requesting location
                    getLocation();
                });
        } else {
            // Fallback if `permissions` API is not supported
            getLocation();
        }
    }

    function getLocation() {
        // Check if location is already stored in local storage
        if (localStorage.getItem("userLocation")) {
            console.log("Location already stored:", localStorage.getItem("userLocation"));
            return; // Exit the function if location is already stored
        }

        // If location is not stored, request the user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Store the location in local storage
                    const userLocation = JSON.stringify({ latitude, longitude });
                    localStorage.setItem("userLocation", userLocation);

                    console.log("Location stored:", userLocation);
                },
                (error) => {
                    // Handle location access errors
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            alert('Please enable location access');
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert("Location information is unavailable.");
                            break;
                        case error.TIMEOUT:
                            alert("The request to get user location timed out.");
                            break;
                        default:
                            alert("An unknown error occurred.");
                    }
                }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    // Call the function to request location
    requestLocation();
});


    let map, marker;

function initMap() {
  const initialPosition = { lat: 37.7749, lng: -122.4194 }; // Default position (San Francisco)

  // Initialize the map
  map = new google.maps.Map(document.getElementById('map'), {
    center: initialPosition,
    zoom: 13,
  });

  // Add a draggable marker
  marker = new google.maps.Marker({
    position: initialPosition,
    map: map,
    draggable: true,
  });

  // Update location on marker drag
  marker.addListener('dragend', updateLocation);

  // Update location on map click
  map.addListener('click', (event) => {
    marker.setPosition(event.latLng);
    updateLocation();
  });

  // Display the initial location
  updateLocation();

  // Add the "Go to My Location" button functionality
  document.getElementById('locateButton').addEventListener('click', goToCurrentLocation);
}

function updateLocation() {
  const position = marker.getPosition();
  const lat = position.lat();
  const lng = position.lng();
    console.log(lng, lat, "thudjfbjasdjsdbhjsbdjbk");
    
  // Display the latitude and longitude
  document.getElementById('location').textContent = `Latitude: ${lat.toFixed(6)}, Longitude: ${lng.toFixed(6)}`;
}

// Function to navigate to the user's current location
function goToCurrentLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };

        // Set the map center to the user's current location
        map.setCenter(userLocation);
        map.setZoom(15);

        // Move the marker to the user's location
        marker.setPosition(userLocation);

        // Update the displayed location information
        updateLocation();
      },
      () => {
        alert('Please enable location access');
      }
    );
  } else {
    alert('Geolocation is not supported by this browser.');
  }
}
  </script>
   


    
    

@endsection


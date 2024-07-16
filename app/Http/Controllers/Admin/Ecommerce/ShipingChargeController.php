<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\State;
use App\Models\City;
use App\Models\ShippingCharge;

class ShipingChargeController extends Controller
{

    public function index () {

        $shippingCharges = ShippingCharge::with('state','city')->orderBy('id' ,'desc')->get();

        return view('admin.Ecommerce.ShippingCharge.shipping-charge-index' ,compact('shippingCharges'));

    }

    public function create(Request $request)  {

        $shippind_charge = null;

        $states = State::all();

        $cities = City::get();
        return view('admin.Ecommerce.ShippingCharge.shipping-charge-create', compact('shippind_charge' ,'states' ,'cities'));
    }

    public function edit($id) {

        $shippind_charge = ShippingCharge::where('id', base64_decode($id))->first();

        $states = State::all();

        $cities = City::where('state_id' , $shippind_charge->state_id)->get();
        return view('admin.Ecommerce.ShippingCharge.shipping-charge-create', compact('shippind_charge' ,'states' ,'cities'));
    }

    public function getCity(Request $request) {
        
        $stateId = $request->input('state_id');

        $cities = City::select('id', 'city_name')->where('state_id', $stateId)->get();
        
        return response()->json($cities, 200);
        
    }

    public function store(Request $request)  {

        $rules = [
            'state_id'           => 'required|string|max:100',

            'weight1'            => 'required|string|max:1000',

            'shipping_charge1'   => 'required|string|max:100',

            'weight2'            => 'required|string|max:100',

            'shipping_charge2'   => 'required|string|max:100',

            'weight3'            => 'required|string|max:100',

            'shipping_charge3'   => 'required|string|max:100',

            'weight4'            => 'required|string|max:100',

            'shipping_charge4'   => 'required|string|max:100',

            'weight5'            => 'required|string|max:100',

            'shipping_charge5'   => 'required|string|max:100',

            'weight6'            => 'required|string|max:100',

            'shipping_charge6'   => 'required|string|max:100',

        ];
       
        if (isset($request->shippind_charge_id)) {
           
            $shippingCharge = ShippingCharge::find($request->shippind_charge_id);
       
            if (!$shippingCharge) {

                return redirect()->route('shipping-charge.index')->with('error', 'Shipping charge not found.');

            }else{

                $rules['city_id'] = $request->city_id == $shippingCharge->city_id ? 'required|string|max:300' : 'required|string|max:300|unique:shipping_charges';

            }

        } else {
            
            $rules['city_id'] = 'required|string|max:300|unique:shipping_charges';

            $shippingCharge = new ShippingCharge;
        }

        $request->validate($rules);

        $shippingCharge->state_id = $request->state_id;

        $shippingCharge->city_id = $request->city_id;

        $shippingCharge->weight1 = $request->weight1;

        $shippingCharge->shipping_charge1 = $request->shipping_charge1;

        $shippingCharge->weight2 = $request->weight2;

        $shippingCharge->shipping_charge2 = $request->shipping_charge2;

        $shippingCharge->weight3 = $request->weight3;

        $shippingCharge->shipping_charge3 = $request->shipping_charge3;

        $shippingCharge->weight4 = $request->weight4;

        $shippingCharge->shipping_charge4 = $request->shipping_charge4;

        $shippingCharge->weight5 = $request->weight5;

        $shippingCharge->shipping_charge5 = $request->shipping_charge5;

        $shippingCharge->weight6 = $request->weight6;

        $shippingCharge->shipping_charge6 = $request->shipping_charge6;

        $shippingCharge->is_active = 1; 

        $shippingCharge->ip = $request->ip();

        $shippingCharge->date = now(); 

        $shippingCharge->added_by = Auth::user()->id;

        if ($shippingCharge->save()) {

            $message = isset($request->shipping_charge_id) ? 'Shipping charge updated successfully.' : 'Shipping charge inserted successfully.';

            return redirect()->route('shipping-charge.index')->with('success', $message);

        } else {

            return redirect()->route('shipping-charge.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id ,Request $request) {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $shippingCharge = ShippingCharge::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $shippingCharge->updateStatus(strval(1));
        } else {

            $shippingCharge->updateStatus(strval(0));
        }

        return  redirect()->route('shipping-charge.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id ,Request $request) {
        
        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $shippingCharge = ShippingCharge::find($id);

        // if ($admin_position == "Super Admin") {
  
            if ($shippingCharge->deleteShippingCharge()) {

                return  redirect()->route('shipping-charge.index')->with('success', 'shipping Charge deleted Successfully.');

            } else {

            return  redirect()->route('shipping-charge.index')->with('error', 'Something went wrong. Please try again later.');
            }

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }
    }

    public function create_city() {

        $states =  $states = State::all();

        return view('admin.Ecommerce.ShippingCharge.shipping-charge-city' , compact('states'));

    }

    public function store_city(Request $request)  {

        $request->validate(['state_id' => 'required|string|max:100', 'City_name' => 'required|string|max:300']);
            
        $city = new City;
    
        $city->state_id = $request->state_id;

        $city->city_name = $request->City_name;

        if ($city->save()) {

            return redirect()->route('shipping-charge.index')->with('success', 'City inserted successfully.');

        } else {

            return redirect()->route('shipping-charge.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function setAllshippingCharges () {

        $states =  $states = State::all();

        $weghit = ShippingCharge::where('is_active' , '!=' , 0)->get();

        $weghit1 =  $weghit->pluck('weight1')->unique();

        $weghit2 =  $weghit->pluck('weight2')->unique();

        $weghit3 =  $weghit->pluck('weight3')->unique();

        $weight4 =  $weghit->pluck('weight4')->unique();

        $weight5 =  $weghit->pluck('weight5')->unique();

        $weight6 =  $weghit->pluck('weight6')->unique();

        return view('admin.Ecommerce.ShippingCharge.add-all-shipping-charges' , compact('weghit1', 'weghit2', 'weghit3', 'weight4', 'weight5', 'weight6' ,'states'));
        
    }

    public function store_shipping_charge(Request $request) {
        
        $rules = [
            'state_id'           => 'required|string|max:100',

            'weight1'            => 'nullable|string',

            'shipping_charge1'   => 'required|string|max:100',

            'weight2'            => 'nullable|string',

            'shipping_charge2'   => 'required|string|max:100',

            'weight3'            => 'nullable|string',

            'shipping_charge3'   => 'required|string|max:100',

            'weight4'            => 'nullable|string',

            'shipping_charge4'   => 'required|string|max:100',

            'weight5'            => 'nullable|string',

            'shipping_charge5'   => 'required|string|max:100',

            'weight6'            => 'nullable|string',

            'shipping_charge6'   => 'required|string|max:100',

        ];

        $request->validate($rules);

        $state = trim($request->input('state_id'));

        $updated = false;

        for ($i = 1; $i <= 6; $i++) {

            $weight = trim($request->input('weight' . $i));

            $shippingCharge = trim($request->input('shipping_charge' . $i));

            if (!empty($shippingCharge)) {

                $record = ShippingCharge::where('state_id', $state)->where('weight' . $i, $weight)->first();

                if ($record) {

                    $record->{'shipping_charge' . $i} = $shippingCharge;

                    if ($record->save()) {

                        $updated = true;

                    } else {

                        return redirect()->route('shipping-charge.index')->with('error', "Something went wrong. Please try again later.");

                    }

                } else {

                    return redirect()->route('shipping-charge.index')->with('error', "Selected state and weight $i data not found.");

                }

            }

        }

        if ($updated) {

            return redirect()->route('shipping-charge.index')->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('shipping-charge.index')->with('error', 'No data updated. Selected state and weight data not found.');

        }

    }

}

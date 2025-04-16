<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer2;

class OfferSliderSecoundController extends Controller
{

   
    public function index()
    {

        $sliders = Offer2::orderBy('id', 'desc')->get();

        return view('admin.Offer2.view-slider', compact('sliders'));
    }

    public function create(Request $request, $id = null)

    {
        $slider = null;

        $products = null;

        $categories = sendCategory();
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('offerslidersecound.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $slider = Offer2::find(base64_decode($id));

            $products   = sendProduct($slider->category_id);
        }

        return view('admin.Offer2.add-slider', compact('slider' ,'categories' ,'products'));
    }

    public function GetProduct(Request $request){

        $category_id = $request->input('category_id');

        $products = sendProduct($category_id);
        
        return response()->json($products, 200);

    }

    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'category_id'              => 'required|string',
            'product_id'               => 'required|string',
            'offer_name'              => 'required|string',
        ];

        if (!isset($request->slider_id)) {

            $rules['img'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $slider = new Offer2;

        } else {
            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $slider = Offer2::find($request->slider_id);
            
            if (!$slider) {
                
                return redirect()->route('offerslidersecound.index')->with('error', 'slider not found.');
                
            }

        }

        $request->validate($rules);

        $slider->fill($request->all());

        if($request->hasFile('img')){

            $slider->image = uploadImage($request->file('img'), 'offerslider2');

        }

        $slider->offer_name_hi = lang_change($request->input('offer_name'));

        $slider->ip = $request->ip();

        $slider->date = now();

        $slider->added_by = Auth::user()->id;

        $slider->is_active = 1;

        if ($slider->save()) {

            $message = isset($request->slider_id) ? 'slider updated successfully.' : 'slider inserted successfully.';

            return redirect()->route('offerslidersecound.index')->with('success', $message);

        } else {

            return redirect()->route('offerslidersecound.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $slider = Offer2::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $slider->updateStatus(strval(1));
        } else {

            $slider->updateStatus(strval(0));
        }

        return  redirect()->route('offerslidersecound.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('offerslidersecound.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Offer2::where('id', $id)->delete()) {

            return  redirect()->route('offerslidersecound.index')->with('success', 'slider Deleted Successfully.');
        } else {
            return redirect()->route('offerslidersecound.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('offerslidersecound.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
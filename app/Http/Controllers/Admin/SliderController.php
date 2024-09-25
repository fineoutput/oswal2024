<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Slider;

class SliderController extends Controller
{

    public function index()
    {

        $sliders = Slider::orderBy('id', 'desc')->get();

        return view('admin.Sliders.view-slider', compact('sliders'));
    }

    public function create(Request $request, $id = null)

    {
        $slider = null;

        $products = null;

        $categories = sendCategory();
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('slider.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $slider = Slider::find(base64_decode($id));

            $products   = sendProduct($slider->category_id);
        }

        return view('admin.Sliders.add-slider', compact('slider' ,'categories' ,'products'));
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
            'slider_name'              => 'nullable|string',
            'app_slider_name'          => 'nullable|string',
            'vendor_slider_name'       => 'nullable|string',
        ];

        if (!isset($request->slider_id)) {

            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['img2'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['img3'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $slider = new Slider;

        } else {

            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['img2'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['img3'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $slider = Slider::find($request->slider_id);
            
            if (!$slider) {
                
                return redirect()->route('slider.index')->with('error', 'slider not found.');
                
            }

        }

        $request->validate($rules);

        $slider->fill($request->all());

        if($request->hasFile('img')){

            $slider->image = uploadImage($request->file('img'), 'slider' ,'web');

        }

        if($request->hasFile('img2')){

            $slider->app_image = uploadImage($request->file('img2'), 'slider' ,'app');

        }

        if($request->hasFile('img3')){

            $slider->vendor_image = uploadImage($request->file('img3'), 'slider' ,'vendor');

        }

        $slider->slider_name_hi = lang_change($request->input('slider_name'));

        $slider->app_slider_name_hi = lang_change($request->input('app_slider_name'));

        $slider->vendor_slider_name_hi = lang_change($request->input('vendor_slider_name'));

        $slider->ip = $request->ip();

        $slider->date = now();

        $slider->added_by = Auth::user()->id;

        $slider->is_active = 1;

        if ($slider->save()) {

            $message = isset($request->slider_id) ? 'slider updated successfully.' : 'slider inserted successfully.';

            return redirect()->route('slider.index')->with('success', $message);

        } else {

            return redirect()->route('slider.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $slider = Slider::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $slider->updateStatus(strval(1));
        } else {

            $slider->updateStatus(strval(0));
        }

        return  redirect()->route('slider.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('slider.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Slider::where('id', $id)->delete()) {

            return  redirect()->route('slider.index')->with('success', 'slider Deleted Successfully.');
        } else {
            return redirect()->route('slider.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('slider.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
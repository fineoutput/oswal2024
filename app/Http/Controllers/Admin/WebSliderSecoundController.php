<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Websliders2;

class WebSliderSecoundController extends Controller
{

    public function index()
    {

        $sliders = Websliders2::orderBy('id', 'desc')->get();

        return view('admin.Webslider2.view-slider', compact('sliders'));
    }

    public function create(Request $request, $id = null)

    {
        $slider = null;

        $products = null;

        $categories = sendCategory();
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('webslidersecound.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $slider = Websliders2::find(base64_decode($id));

            $products   = sendProduct($slider->category_id);
        }

        return view('admin.Webslider2.add-slider', compact('slider' ,'categories' ,'products'));
    }

    public function GetProduct(Request $request){

        $category_id = $request->input('category_id');

        $products = sendProduct($category_id);
        
        return response()->json($products, 200);

    }

    public function store(Request $request)

    {

        $rules = [
            'link'              => 'nullable|string',
            'apptext'           => 'nullable|string',
            'vendortext'        => 'nullable|string',
        ];

        if (!isset($request->slider_id)) {

            $rules['img']  = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            
            $rules['img2'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['img3'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $slider = new Websliders2;

        } else {
            
            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            
            $rules['img2'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            
            $rules['img3'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $slider = Websliders2::find($request->slider_id);
            
            if (!$slider) {
                
                return redirect()->route('webslidersecound.index')->with('error', 'slider not found.');
                
            }

        }

        $request->validate($rules);

        $slider->link = $request->link;

        $slider->app_link = $request->apptext;

        $slider->vendor_link = $request->vendortext;

        if($request->hasFile('img')){

            $slider->image = uploadImage($request->file('img'), 'webslider2' ,'web');

        }

        if($request->hasFile('img2')){

            $slider->app_img = uploadImage($request->file('img2'), 'webslider2' ,'app');

        }

        if($request->hasFile('img3')){

            $slider->vendor_image = uploadImage($request->file('img3'), 'webslider2' ,'vendor');

        }

        $slider->ip = $request->ip();

        $slider->date = now();

        $slider->added_by = Auth::user()->id;

        $slider->is_active = 1;

        if ($slider->save()) {

            $message = isset($request->slider_id) ? 'slider updated successfully.' : 'slider inserted successfully.';

            return redirect()->route('webslidersecound.index')->with('success', $message);

        } else {

            return redirect()->route('webslidersecound.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $slider = Websliders2::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $slider->updateStatus(strval(1));
        } else {

            $slider->updateStatus(strval(0));
        }

        return  redirect()->route('webslidersecound.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('webslidersecound.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Websliders2::where('id', $id)->delete()) {

            return  redirect()->route('webslidersecound.index')->with('success', 'slider Deleted Successfully.');
        } else {
            return redirect()->route('webslidersecound.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('webslidersecound.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
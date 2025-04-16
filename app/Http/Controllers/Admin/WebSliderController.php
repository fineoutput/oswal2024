<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Slider2;

class WebSliderController extends Controller
{

    public function index()
    {

        $sliders = Slider2::orderBy('id', 'desc')->get();

        return view('admin.Webslider.view-slider', compact('sliders'));
    }

    public function create(Request $request, $id = null)

    {
        $slider = null;

        $products = null;

        $categories = sendCategory();
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('webslider.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $slider = Slider2::find(base64_decode($id));

            $products   = sendProduct($slider->category_id);
        }

        return view('admin.Webslider.add-slider', compact('slider' ,'categories' ,'products'));
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
            'slider_name'              => 'nullable|string',
            'app_slider_name'          => 'nullable|string',
            'vendor_slider_name'       => 'nullable|string',
        ];

        if (!isset($request->slider_id)) {

            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['img2'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['img3'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $slider = new Slider2;

        } else {

            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['img2'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['img3'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $slider = Slider2::find($request->slider_id);
            
            if (!$slider) {
                
                return redirect()->route('webslider.index')->with('error', 'image not found.');
                
            }

        }

        $request->validate($rules);

        $slider->fill($request->all());

        if($request->hasFile('img')){

            $slider->image = uploadImage($request->file('img'), 'webslider' , 'web');

        }

        if($request->hasFile('img2')){

            $slider->app_image = uploadImage($request->file('img2'), 'webslider' , 'app');

        }

        if($request->hasFile('img3')){

            $slider->vendor_image = uploadImage($request->file('img3'), 'webslider' , 'vendor');

        }

        $slider->slider_name_hi = lang_change($request->input('slider_name'));

        $slider->app_slider_name_hi = lang_change($request->input('app_slider_name'));

        $slider->vendor_slider_name_hi = lang_change($request->input('vendor_slider_name'));

        $slider->ip = $request->ip();

        $slider->date = now();

        $slider->added_by = Auth::user()->id;

        $slider->is_active = 1;

        if ($slider->save()) {

            $message = isset($request->slider_id) ? 'image updated successfully.' : 'image inserted successfully.';

            return redirect()->route('webslider.index')->with('success', $message);

        } else {

            return redirect()->route('webslider.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $slider = Slider2::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $slider->updateStatus(strval(1));
        } else {

            $slider->updateStatus(strval(0));
        }

        return  redirect()->route('webslider.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('webslider.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Slider2::where('id', $id)->delete()) {

            return  redirect()->route('webslider.index')->with('success', 'image Deleted Successfully.');
        } else {
            return redirect()->route('webslider.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('webslider.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;

class TestimonialController extends Controller
{

    public function index()
    {

        $footerimages = Testimonial::orderBy('id', 'desc')->get();

        return view('admin.Testimonial.view-testimonial', compact('footerimages'));
    }

    public function create(Request $request, $id = null)

    {
        $footerimage = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('testimonial.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $footerimage = Testimonial::find(base64_decode($id));

        }

        return view('admin.Testimonial.add-testimonial', compact('footerimage'));
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
            'name'              => 'required|string',
            'description'       => 'required|string',
            'rating'            => 'required|string',
        ];

        if (!isset($request->footerimage_id)) {

            $rules['img'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $footerimage = new Testimonial;

        } else {
            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $footerimage = Testimonial::find($request->footerimage_id);
            
            if (!$footerimage) {
                
                return redirect()->route('testimonial.index')->with('error', 'Testimonial not found.');
                
            }

        }

        $request->validate($rules);

        $footerimage->fill($request->all());

        if($request->hasFile('img')){

            $footerimage->image = uploadImage($request->file('img'), 'testimonial');

        }

        $footerimage->ip = $request->ip();

        $footerimage->date = now();

        $footerimage->added_by = Auth::user()->id;

        $footerimage->is_active = 1;

        if ($footerimage->save()) {

            $message = isset($request->footerimage_id) ? 'testimonial updated successfully.' : 'testimonial inserted successfully.';

            return redirect()->route('testimonial.index')->with('success', $message);

        } else {

            return redirect()->route('testimonial.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $footerimage = Testimonial::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $footerimage->updateStatus(strval(1));
        } else {

            $footerimage->updateStatus(strval(0));
        }

        return  redirect()->route('testimonial.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('testimonial.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Testimonial::where('id', $id)->delete()) {

            return  redirect()->route('testimonial.index')->with('success', 'Testimonial deleted Successfully.');
        } else {
            return redirect()->route('testimonial.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('testimonial.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
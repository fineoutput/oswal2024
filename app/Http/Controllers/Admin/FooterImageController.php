<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FooterImage;

class FooterImageController extends Controller
{

    public function index()
    {

        $footerimages = FooterImage::orderBy('id', 'desc')->get();

        return view('admin.FooterImage.view-footer-image', compact('footerimages'));
    }

    public function create($id = null, Request $request)

    {
        $footerimage = null;

        $products = null;

        $categories = sendCategory();
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('footerimage.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $footerimage = FooterImage::find(base64_decode($id));

            $products   = sendProduct($footerimage->category_id);
        }

        return view('admin.FooterImage.add-footer-image', compact('footerimage' ,'categories' ,'products'));
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
            'image_name'              => 'required|string',
        ];

        if (!isset($request->footerimage_id)) {

            $rules['img'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $footerimage = new FooterImage;

        } else {
            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $footerimage = FooterImage::find($request->footerimage_id);
            
            if (!$footerimage) {
                
                return redirect()->route('footerimage.index')->with('error', 'footerimage not found.');
                
            }

        }

        $request->validate($rules);

        $footerimage->fill($request->all());

        if($request->hasFile('img')){

            $footerimage->image = uploadImage($request->file('img'), 'footerimage');

        }

        $footerimage->image_name_hi = lang_change($request->input('image_name'));

        $footerimage->ip = $request->ip();

        $footerimage->date = now();

        $footerimage->added_by = Auth::user()->id;

        $footerimage->is_active = 1;

        if ($footerimage->save()) {

            $message = isset($request->footerimage_id) ? 'footerimage updated successfully.' : 'footerimage inserted successfully.';

            return redirect()->route('footerimage.index')->with('success', $message);

        } else {

            return redirect()->route('footerimage.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $footerimage = FooterImage::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $footerimage->updateStatus(strval(1));
        } else {

            $footerimage->updateStatus(strval(0));
        }

        return  redirect()->route('footerimage.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('footerimage.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (FooterImage::where('id', $id)->delete()) {

            return  redirect()->route('footerimage.index')->with('success', 'footerimage Deleted Successfully.');
        } else {
            return redirect()->route('footerimage.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('footerimage.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
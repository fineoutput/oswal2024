<?php

namespace App\Http\Controllers\Admin\Major;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\MajorCategory;
use App\Models\MajorProduct;


class MajorProductController extends Controller
{

    public function index() {
        
        $products = MajorProduct::with('majorcategory')->OrderBy('id' ,'Desc')->get();

        return view('admin.majorCategory.view-product' , compact('products'));
        
    }

    public function create(Request $request, $id =null)
    {
        $product = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('category.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $product = MajorProduct::find(base64_decode($id));
            
        }

        $categories = MajorCategory::where('is_active', 1)->get();
        
        return view('admin.majorCategory.add-product', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'category'          => 'required',
            'name'              => 'required',
            'short_dis'         => 'required',
            'long-description'  => 'required', 
            'long-description2' => 'nullable', 
            'reguler_price'     => 'required', 
            'sale_price'        => 'nullable', 
            'video'             => 'nullable',
            'status'            => 'required|numeric',

        ];

        if (!isset($request->product_id)) {
            
            $rules['img1'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img2'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img3'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';             
            $product = new MajorProduct;

        } else {

            $rules['img1'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img2'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img3'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            
            $product = MajorProduct::find($request->product_id);
            
            if (!$product) {
                
                return redirect()->route('majorproduct.index')->with('error', 'Category not found.');
                
            }
            
        }

        $request->validate($rules);

        $product->fill([

            'name'          => $request->name,

            'short_dis'     => $request->input('short_dis'),

            'long_dis'      => $request->input('long-description'),

            'long_desc'     => $request->input('long-description2'),

            'url'           => str_replace(' ', '-', trim($request->name)),

            'reguler_price' => $request->reguler_price,

            'video'         => $request->video,

            'sale_price'    => $request->sale_price,

            'is_active'     => $request->status,

            'cur_date'      => now(),

            'ip'            => $request->ip(),

            'added_by'      => Auth::user()->id,

        ]);

        $product->major_id = $request->category;

        if ($request->hasFile('img1')) {
            $product->img1 = uploadImage($request->file('img1'), 'major', 'product', 'img1');
        }

        if ($request->hasFile('img2')) {
            $product->img2 = uploadImage($request->file('img2'), 'major', 'product', 'img2');
        }

        if ($request->hasFile('img3')) {
            $product->img3 = uploadImage($request->file('img3'), 'major', 'product', 'img3');
        }

        if ($product->save()) {

            if(isset($request->product_id)){

                return redirect()->route('majorproduct.index')->with('success', 'product updated successfully.');

            }else{

                return redirect()->route('majorproduct.index')->with('success', 'Product inserted successfully.');
                
            }
            
        } else {

            return redirect()->route('majorproduct.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)
    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $product = MajorProduct::find($id);
        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $product->updateStatus(strval(1));
        } else {

            $product->updateStatus(strval(0));
        }
     
        return  redirect()->route('majorproduct.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('majorproduct.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (MajorProduct::where('id', $id)->delete()) {

            return  redirect()->route('majorproduct.index')->with('success', 'Product Deleted Successfully.');
        } else {
            return redirect()->route('majorproduct.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('majorcategory.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }
}

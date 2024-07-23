<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\EcomProductCategory;
use Illuminate\Http\Request;
use App\Models\EcomCategory;
use App\Models\EcomProduct;
use App\Models\Cart;

class ProductController extends Controller
{

    public function category () {

       $product_categorys = EcomProductCategory::all();

       return view('admin.Ecommerce.Products.product-category' , compact('product_categorys'));

    }

    public function index($id) {
        
        $products = EcomProduct::where('product_category_id' , decrypt($id))->get();

        return view('admin.Ecommerce.Products.product-index' , compact('products'));
        
    }

    public function create($id =null, Request $request)
    {
        $product = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('category.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $product = EcomProduct::find(base64_decode($id));
            
        }

        $categories = EcomCategory::where('is_active', 1)->get();
        
        $productCategories = EcomProductCategory::get();

        return view('admin.Ecommerce.Products.product-create', compact('product', 'categories', 'productCategories'));
    }

    public function store(Request $request)
    {

        $rules = [
            'name'              => 'required',
            'hsn_code'          => 'required',
            'category'          => 'required',
            'long-description'  => 'required', 
            'video'             => 'nullable',
            'status'            => 'required|numeric',
            'productCategorie'  => 'required',
            'hot_selling'       => 'required',
        ];

        if (!isset($request->product_id)) {
            
            $rules['img1'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img2'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img3'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img4'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            
            $rules['img_app1'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['img_app2'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['img_app3'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['img_app4'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            
            $product = new EcomProduct;

        } else {

            $rules['img1'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img2'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img3'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            $rules['img4'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'; 
            
            $rules['img_app1'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['img_app2'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['img_app3'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['img_app4'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            
            $product = EcomProduct::find($request->product_id);
            
            if (!$product) {
                
                return redirect()->route('product.index')->with('error', 'Category not found.');
                
            }
            
        }

        $request->validate($rules);

        $product->fill([

            'name'          => $request->name,

            'long_desc'     => $request->input('long-description'),

            'name_hi'       => lang_change($request->name),

            'long_desc_hi'  => lang_change($request->input('long-description')),

            'url'           => str_replace(' ', '-', trim($request->name)),

            'hsn_code'      => $request->hsn_code,

            'video'         => $request->video,

            'is_active'     => $request->status,

            'cur_date'      => now(),

            'ip'            => $request->ip(),

            'added_by'      => Auth::user()->id,

            'is_hot'        => $request->hot_selling,

        ]);

        $product->category_id = $request->category;

        $product->product_category_id = $request->productCategorie;

        if ($request->hasFile('img1')) {
            $product->img1 = uploadImage($request->file('img1'), 'ecomm', 'product', 'img1');
        }

        if ($request->hasFile('img2')) {
            $product->img2 = uploadImage($request->file('img2'), 'ecomm', 'product', 'img2');
        }

        if ($request->hasFile('img3')) {
            $product->img3 = uploadImage($request->file('img3'), 'ecomm', 'product', 'img3');
        }

        if ($request->hasFile('img4')) {
            $product->img4 = uploadImage($request->file('img4'), 'ecomm', 'product', 'img4');
        }


        if ($request->hasFile('img_app1')) {
            $product->img_app1 = uploadImage($request->file('img_app1'), 'ecomm', 'product', 'img_app1');
        }

        if ($request->hasFile('img_app2')) {
            $product->img_app2 = uploadImage($request->file('img_app2'), 'ecomm', 'product', 'img_app2');
        }

        if ($request->hasFile('img_app3')) {
            $product->img_app3 = uploadImage($request->file('img_app3'), 'ecomm', 'product', 'img_app3');
        }

        if ($request->hasFile('img_app4')) {
            $product->img_app4 = uploadImage($request->file('img_app4'), 'ecomm', 'product', 'img_app4');
        }


        if ($product->save()) {

            if(isset($request->product_id)){

                return redirect()->route('product.index',encrypt($request->productCategorie))->with('success', 'product updated successfully.');

            }else{

                return redirect()->route('type.index', [
                    'pid' => encrypt($product->id),
                    'cid' => encrypt($product->category_id),
                    'pcid' => encrypt($product->product_category_id)
                ])->with('success', 'Product inserted successfully.');
                
            }
            
        } else {

            return redirect()->route('product.index' ,encrypt($request->productCategorie))->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)
    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $product = EcomProduct::find($id);
        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $product->updateStatus(strval(1));
        } else {

            $product->updateStatus(strval(0));
        }
     
        return  redirect()->route('product.index' , encrypt($product->product_category_id))->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($pid ,$id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $ecomproduct = EcomProduct::find($id);
        // if ($admin_position == "Super Admin") {

        if ($ecomproduct->delete()) {

            return  redirect()->route('product.index',$pid)->with('success', 'Product Deleted Successfully.');
        } else {
            return redirect()->route('product.index',$pid)->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }
    public function view_cart() {
        
        $carts = Cart::with('user' ,'product','type')->where('user_id', '!=' , null)->OrderBy('id', 'Desc')->get();
    
        return view('admin.Ecommerce.view-cart' , compact('carts'));

    }
}

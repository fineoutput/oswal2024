<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EcomCategory;
use App\Models\User;

class CategoryController extends Controller
{

    public function index()
    {

        $categorys = EcomCategory::orderby('id', 'desc')->get();

        return view('admin.Ecommerce.Categorys.category-index', compact('categorys'));
    }

    function testuser(Request $request){
         // Step 1: Fetch all contacts
        $contacts = User::pluck('contact')->toArray();

        // Step 2: Count occurrences of each contact
        $contactCounts = array_count_values($contacts);

        // Step 3: Separate unique and repeated contacts
        $repeatedContacts = [];
        $allContacts = [];

        foreach ($contactCounts as $contact => $count) {
            if ($count > 1) {
                // Add to repeated contacts if more than 1 occurrence
                $repeatedContacts[] = $contact;
            }
            // Add the contact to allContacts (even if repeated, only once)
            $allContacts[] = $contact;
        }

        // Step 4: Return the results
        return response()->json([
            'all_contacts' => array_unique($allContacts),  // Unique contacts only
            'repeated_contacts' => $repeatedContacts,  // Repeated contacts
        ]);
    }

    public function create(Request $request, $id = null)

    {
        $category = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('category.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $category = EcomCategory::find(base64_decode($id));
            
        }

        return view('admin.Ecommerce.Categorys.category-create', compact('category'));
    }

    public function store(Request $request)

    {
      
        $rules = [
            'name'              => 'required|string|max:100',
            'short-description' => 'required|string|max:300',
            'long-description'  => 'required|string|max:1000',
            'sequence'          => 'required|integer',
            'status'            => 'required|integer',
            'slider-image1'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'slider-image2'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'slider-image3'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
        ];
       
        if (!isset($request->category_id)) {
            
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            
            $rules['app-image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $rules['icon'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            
            
            $category = new EcomCategory;

            if($this->checkSequence($request->sequence) != true){
               
                return back()->with(['error' => 'Add another sequence']);

            }

        } else {

            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            
            $rules['app-image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            
            $rules['icon'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $category = EcomCategory::find($request->category_id);
            
            if (!$category) {
                
                return redirect()->route('category.index')->with('error', 'Category not found.');
                
            }

            if($this->checkSequence($request->sequence , $request->category_id) != true){

                return back()->with(['error' => 'Add another sequence']);

            }
            
        }
      
        $request->validate($rules);

        // dd($category);
        $category->fill([

            'name'          => $request->name,

            'long_desc'     => $request->input('long-description'),

            'sequence'      => $request->sequence,

            'is_active'     => $request->status,

            'name_hi'       => lang_change($request->name),

            'long_desc_hi'  => lang_change($request->input('long-description')),

            'url'           => str_replace(' ', '-', trim($request->name)),

            'ip'            => $request->ip(),

            'cur_date'      => now(),

            'added_by'      => Auth::user()->id,

        ]);

        $category->short_disc = $request->input('short-description');

        $category->short_disc_hi = lang_change($request->input('short-description'));

        if ($request->hasFile('image')) {
            $category->image = uploadImage($request->file('image'), 'ecomm', 'category', 'img');
        }

        if ($request->hasFile('app-image')) {
            $category->app_image = uploadImage($request->file('app-image'), 'ecomm', 'category', 'app_img');
        }

        if ($request->hasFile('icon')) {
            $category->icon = uploadImage($request->file('icon'), 'ecomm', 'category', 'icon');
        }

        if ($request->hasFile('slider-image1')) {
            $category->slide_img1 = uploadImage($request->file('slider-image1'), 'ecomm', 'category', 'slider_img1');
        }

        if ($request->hasFile('slider-image2')) {
            $category->slide_img2 = uploadImage($request->file('slider-image2'), 'ecomm', 'category', 'slider_img2');
        }

        if ($request->hasFile('slider-image3')) {
            $category->slide_img3 = uploadImage($request->file('slider-image3'), 'ecomm', 'category', 'slider_img3');
        }

        if ($category->save()) {

            $message = isset($request->category_id) ? 'Category updated successfully.' : 'Category inserted successfully.';

            return redirect()->route('category.index')->with('success', $message);

        } else {

            return redirect()->route('category.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');
        $category =   EcomCategory::find($id);
        // if ($admin_position == "Super Admin") {

        if ($category->delete()) {
            
            return  redirect()->route('category.index')->with('success', 'Category Deleted Successfully.');
        } else {
            return redirect()->route('category.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $category = EcomCategory::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $category->updateStatus(strval(1));
        } else {

            $category->updateStatus(strval(0));
        }

        return  redirect()->route('category.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    private function checkSequence($sequence , $id = false)

    {
        if ($id) {

            $category = EcomCategory::where('id', $id)->where('is_active', 1)->first();

            if (!empty($category) && $category->sequence != $sequence) {

                $categoryWithSequence = EcomCategory::where('sequence', $sequence)->where('is_active', 1)->first();

                if (!empty($categoryWithSequence)) {

                    return false;
                }
            }
            
        } else {
            
            $categoryWithSequence = EcomCategory::where('sequence', $sequence)->where('is_active', 1)->first();

            if (!empty($categoryWithSequence)) {

              return false;

            }

        }

        return true; 
    }

}

<?php

namespace App\Http\Controllers\Admin\Major;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MajorCategory;

class MajorCategoryController extends Controller
{

    public function index()
    {

        $categorys = MajorCategory::orderby('id', 'desc')->get();

        return view('admin.majorCategory.view-category', compact('categorys'));
    }

    public function create(Request $request, $id = null)

    {
        $category = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('majorcategory.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $category = MajorCategory::find(base64_decode($id));
            
        }

        return view('admin.majorCategory.add-category', compact('category'));
    }

    public function store(Request $request)

    {
       
        $rules = [
            'name'              => 'required|string|max:100',
            'short-description' => 'nullable|string|max:300',
            'long-description'  => 'nullable|string|max:1000',
            'status'            => 'required|integer',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
         
        ];

        if (!isset($request->category_id)) {
            
            $category = new MajorCategory;

        } else {

            $category = MajorCategory::find($request->category_id);
            
            if (!$category) {
                
                return redirect()->route('majorcategory.index')->with('error', 'Category not found.');
                
            }

        }

        $request->validate($rules);

        $category->fill([

            'name'          => $request->name,

            'short_dis'     => $request->input('short-description'),

            'long_desc'     => $request->input('long-description'),

            'is_active'     => $request->status,

            'url'           => str_replace(' ', '-', trim($request->name)),

            'ip'            => $request->ip(),

            'cur_date'      => now(),

            'added_by'      => Auth::user()->id,

        ]);


        if ($request->hasFile('image')) {
            $category->image = uploadImage($request->file('image'), 'major', 'category', 'img');
        }

        if ($category->save()) {

            $message = isset($request->category_id) ? 'Category updated successfully.' : 'Category inserted successfully.';

            return redirect()->route('majorcategory.index')->with('success', $message);

        } else {

            return redirect()->route('majorcategory.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $majorCategory = MajorCategory::find($id);
        // if ($admin_position == "Super Admin") {

        if ($majorCategory->delete()) {

            return  redirect()->route('majorcategory.index')->with('success', 'Category Deleted Successfully.');
        } else {
            return redirect()->route('majorcategory.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('majorcategory.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $category = MajorCategory::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $category->updateStatus(strval(1));
        } else {

            $category->updateStatus(strval(0));
        }

        return  redirect()->route('majorcategory.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('majorcategory.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }


}
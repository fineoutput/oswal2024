<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sticker;

class StickerController extends Controller
{

    public function index()
    {

        $stickers = Sticker::orderBy('id', 'desc')->get();

        return view('admin.Sticker.view-sticker', compact('stickers'));
    }

    public function create($id = null, Request $request)

    {
        $sticker = null;
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('sticker.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $sticker = Sticker::find(base64_decode($id));
        }

        return view('admin.Sticker.add-sticker', compact('sticker'));
    }


    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'name'              => 'required|string',
        ];

        if (!isset($request->sticker_id)) {

            $rules['img'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $sticker = new Sticker;

        } else {
            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $sticker = Sticker::find($request->sticker_id);
            
            if (!$sticker) {
                
                return redirect()->route('sticker.index')->with('error', 'Sticker not found.');
                
            }

        }

        $request->validate($rules);

        $sticker->fill($request->all());

        if($request->hasFile('img')){

            $sticker->image = uploadImage($request->file('img'), 'sticker');

        }

        $sticker->ip = $request->ip();

        $sticker->date = now();

        $sticker->added_by = Auth::user()->id;

        $sticker->is_active = 1;

        if ($sticker->save()) {

            $message = isset($request->sticker_id) ? 'Sticker updated successfully.' : 'Sticker inserted successfully.';

            return redirect()->route('sticker.index')->with('success', $message);

        } else {

            return redirect()->route('sticker.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $sticker = Sticker::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $sticker->updateStatus(strval(1));
        } else {

            $sticker->updateStatus(strval(0));
        }

        return  redirect()->route('sticker.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('sticker.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Sticker::where('id', $id)->delete()) {

            return  redirect()->route('sticker.index')->with('success', 'Sticker Deleted Successfully.');
        } else {
            return redirect()->route('sticker.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('sticker.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
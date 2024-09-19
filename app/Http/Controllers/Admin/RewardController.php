<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reward;

class RewardController extends Controller
{

    public function index()
    {

        $stickers = Reward::orderBy('id', 'desc')->get();

        return view('admin.Rewards.view-reward', compact('stickers'));
    }

    public function create($id = null, Request $request)

    {
        $sticker = null;
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('sticker.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $sticker = Reward::find(base64_decode($id));
        }

        return view('admin.Rewards.add-reward', compact('sticker'));
    }


    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'name'              => 'required|string',
            'quantity'          => 'required|integer',
            'type'              => 'required|integer',
            'weight'            => 'required|integer',
        ];

        if (!isset($request->sticker_id)) {

            $rules['img'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $sticker = new Reward;

        } else {
            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $sticker = Reward::find($request->sticker_id);
            
            if (!$sticker) {
                
                return redirect()->route('reward.index')->with('error', 'Sticker not found.');
                
            }

        }

        $request->validate($rules);

        $sticker->fill($request->all());

        if($request->hasFile('img')){

            $sticker->image = uploadImage($request->file('img'), 'reward');

        }

        $sticker->ip = $request->ip();

        $sticker->date = now();

        $sticker->added_by = Auth::user()->id;

        $sticker->is_active = 1;

        if ($sticker->save()) {

            $message = isset($request->sticker_id) ? 'Reward updated successfully.' : 'Reward inserted successfully.';

            return redirect()->route('reward.index')->with('success', $message);

        } else {

            return redirect()->route('reward.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $sticker = Reward::find($id);

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

        if (Reward::where('id', $id)->delete()) {

            return  redirect()->route('sticker.index')->with('success', 'Sticker Deleted Successfully.');
        } else {
            return redirect()->route('sticker.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('sticker.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
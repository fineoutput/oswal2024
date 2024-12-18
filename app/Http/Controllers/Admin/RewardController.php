<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reward;
use App\Models\User;
use App\Models\Order;
use App\Models\VendorReward;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{

    public function index()
    {

        $stickers = Reward::orderBy('id', 'desc')->get();

        return view('admin.Rewards.view-reward', compact('stickers'));
    }

    public function create(Request $request, $id=null)

    {
        $sticker = null;
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('reward.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

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
            'price'              => 'required|integer',
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

        return  redirect()->route('reward.index')->with('success', 'Status Updated Successfully.');

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

            return  redirect()->route('reward.index')->with('success', 'Reward Deleted Successfully.');
        } else {
            return redirect()->route('reward.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('sticker.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function applied(Request $request) {

        // $rewards = VendorReward::select('vendor_rewards') ->get();
        // $rewards = User::select('users')->where('id',$rewards->user_id)->get();
        // $rewards = Order::select('tbl_order1') ->get();
        
        // $rewards = VendorReward::select('vendor_rewards.*', 'users.first_name', 'tbl_order1.id')
        // ->join('users', 'vendor_rewards.vendor_id', '=', 'users.id') 
        // ->join('tbl_order1', 'vendor_rewards.order_id', '=', 'tbl_order1.id')
        // ->get();

        $rewards = VendorReward::select('vendor_rewards.*', 'users.first_name', 'tbl_order1.id', 'tbl_order1.total_order_weight')
        ->join('users', 'vendor_rewards.vendor_id', '=', 'users.id')
        ->join('tbl_order1', 'vendor_rewards.order_id', '=', 'tbl_order1.id')
        ->get();

      return view('admin.Rewards.applied-reward' , compact('rewards'));

    }

    public function accepted($status, $id, Request $request)
    {
        $id = base64_decode($id);
        $sticker = DB::table('vendor_rewards')->where('reward_id', $id)->first();
        if ($sticker) {
            $newStatus = ($status == "accepted") ? VendorReward::STATUS_ACCEPTED : VendorReward::STATUS_REJECTED;
            DB::table('vendor_rewards')
                ->where('reward_id', $id)
                ->update(['status' => $newStatus]);
            return redirect()->route('reward.applied')->with('success', 'Status Updated Successfully.');
        } else {
            return redirect()->route('reward.applied')->with('error', 'Reward not found.');
        }

    }
}
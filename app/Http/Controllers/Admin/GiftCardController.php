<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\GiftCard;

class GiftCardController extends Controller
{

    public function index()
    {

        $giftcards = GiftCard::orderBy('id', 'desc')->get();

        return view('admin.GiftCard.view-giftcard', compact('giftcards'));
    }

    public function create($id = null, Request $request)

    {
        $giftcard = null;
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('gift-card.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $giftcard = GiftCard::find(base64_decode($id));
        }

        return view('admin.GiftCard.add-giftcard', compact('giftcard'));
    }


    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'name'              => 'required',
            'description'       => 'required',
            'price'             => 'required|regex:^[1-9][0-9]+|not_in:0',
            'img'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];

        if (!isset($request->giftcard_id)) {

            $giftcard = new GiftCard;

        } else {

            $giftcard = GiftCard::find($request->giftcard_id);
            
            if (!$giftcard) {
                
                return redirect()->route('gift-card.index')->with('error', 'Gift Card not found.');
                
            }

        }

        $request->validate($rules);

        $giftcard->fill($request->all());


        if($request->hasFile('img')){

            $giftcard->image = uploadImage($request->file('img'), 'GiftCard');

        }

        $giftcard->ip = $request->ip();

        $giftcard->date = now();

        $giftcard->added_by = Auth::user()->id;

        $giftcard->is_active = 1;

        if ($giftcard->save()) {

            $message = isset($request->giftcard_id) ? 'Gift Card updated successfully.' : 'Gift Card inserted successfully.';

            return redirect()->route('gift-card.index')->with('success', $message);

        } else {

            return redirect()->route('gift-card.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $giftcard = GiftCard::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $giftcard->updateStatus(strval(1));
        } else {

            $giftcard->updateStatus(strval(0));
        }

        return  redirect()->route('gift-card.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('gift-card.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (GiftCard::where('id', $id)->delete()) {

            return  redirect()->route('gift-card.index')->with('success', 'Gift Card Deleted Successfully.');
        } else {
            return redirect()->route('gift-card.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('gift-card.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
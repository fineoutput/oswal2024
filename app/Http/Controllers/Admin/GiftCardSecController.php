<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\GiftCardSec;
use App\Models\Type;

class GiftCardSecController extends Controller
{

    public function index()
    {

        $giftcards = GiftCardSec::with('product' , 'type')->orderBy('id', 'desc')->get();

        return view('admin.GiftCardSec.view-giftcard', compact('giftcards'));
    }

    public function create(Request $request, $id = null)

    {
        $giftcard = null;
      
        $products = sendProduct();

        $types = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('gift-card-1.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $giftcard = GiftCardSec::find(base64_decode($id));

            $types = Type::where('product_id', $giftcard->product_id)->select('id', 'type_name')->groupBy('type_name')->get();
        }
        
        return view('admin.GiftCardSec.add-giftcard', compact('giftcard' ,'products' ,'types'));
    }

    public function getType(Request $request) {
        
        $id = $request->product_id;

        $types = Type::where('product_id', $id)
        ->select('id', 'type_name')
        ->groupBy('type_name')
        ->get();

        return response()->json($types, 200);
    }

    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'product_id'    => 'required',
            'type_id'       => 'required',
            'price'         => 'required|integer',
            'img'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'appimg'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];

        if (!isset($request->giftcard_id)) {

            $giftcard = new GiftCardSec;

        } else {

            $giftcard = GiftCardSec::find($request->giftcard_id);
            
            if (!$giftcard) {
                
                return redirect()->route('gift-card-1.index')->with('error', 'Gift Card not found.');
                
            }

        }

        $request->validate($rules);

        $giftcard->fill($request->all());


        if($request->hasFile('img')){

            $giftcard->image = uploadImage($request->file('img'), 'GiftCardsec', 'img');

        }

        if($request->hasFile('appimg')){

            $giftcard->appimage = uploadImage($request->file('appimg'), 'GiftCardsec', 'appimg');

        }

        $giftcard->ip = $request->ip();

        $giftcard->date = now();

        $giftcard->added_by = Auth::user()->id;

        $giftcard->is_active = 1;

        if ($giftcard->save()) {

            $message = isset($request->giftcard_id) ? 'Gift Card updated successfully.' : 'Gift Card inserted successfully.';

            return redirect()->route('gift-card-1.index')->with('success', $message);

        } else {

            return redirect()->route('gift-card-1.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $giftcard = GiftCardSec::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $giftcard->updateStatus(strval(1));
        } else {

            $giftcard->updateStatus(strval(0));
        }

        return  redirect()->route('gift-card-1.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('gift-card-1.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (GiftCardSec::where('id', $id)->delete()) {

            return  redirect()->route('gift-card-1.index')->with('success', 'Gift Card Deleted Successfully.');
        } else {
            return redirect()->route('gift-card-1.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('gift-card-1.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
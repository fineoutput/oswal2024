<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Crm_settings;
use App\Models\Popup;
class HomeController extends Controller
{

    public function career()
    {

        $careers = DB::table('careers')->orderby('id', 'desc')->get();

        return view('admin.career', compact('careers'));
    }


    public function carrerDestroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

            if (DB::table('careers')->where('id', $id)->delete()) {

                return  redirect()->route('home.career')->with('success', 'Data Deleted Successfully.');

            } else {

                return redirect()->route('home.career')->with('error', 'Some Error Occurred.');

            }

        // } else {

        // 	return  redirect()->route('contact-us.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }   

    public function view_popup()
    {

        $popups = Popup::orderby('id', 'desc')->get();

        return view('admin.Popup.view-popup', compact('popups'));
    }

    public function popupCreate($id=null ,Request $request)
    {

        $popup = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('home.view-popup')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $popup = Popup::find(base64_decode($id));
            
        }

        return view('admin.Popup.add-popup', compact('popup'));
    }

    public function popupStore(Request $request)  {

        $rules = [
            'name'         => 'required|string',
            'img'         =>  'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
        
        $request->validate($rules);

        if (!isset($request->popup_id)) {

            $popup = new Popup;

        } else {

            $popup = Popup::find($request->popup_id);
            
            if (!$popup) {
                
                return redirect()->route('home.view-popup')->with('error', 'notifaction not found.');
                
            }

        }
        
        $popup->fill($request->all());

        if($request->hasFile('img')){

            $popup->image = uploadImage($request->file('img'), 'popup');

        }

        $popup->ip = $request->ip();

        $popup->date = now();

        $popup->added_by = Auth::user()->id;

        $popup->is_active = 1;

        if ($popup->save()) {

            $message = isset($request->popup_id) ? 'Popup updated successfully.' : 'Popup inserted successfully.';
            
            return redirect()->route('home.view-popup')->with('success', $message);

        } else {

            return redirect()->route('home.view-popup')->with('error', 'Something went wrong. Please try again later.');

        }

    }

    public function Popup_update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $popup = Popup::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $popup->updateStatus(strval(1));
        } else {

            $popup->updateStatus(strval(0));
        }

        return  redirect()->route('home.view-popup')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('shop.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function view_gift_promo()
    
    {

        $view_gift_promos = DB::table('gift_promo_status')->orderby('id', 'desc')->get();

        return view('admin.view-gift-promo', compact('view_gift_promos'));

    }

    public function gift_promo_status($status, $id, Request $request)

    {
        $id = base64_decode($id); 

        $admin_position = $request->session()->get('position');

        if ($admin_position !== "Super Admin") {

            return redirect()->route('home.view-gift-promo')->with('error', "Sorry, you don't have permission to change the status. Only Super Admin can change status.");

        }

        try {

            DB::table('gift_promo_status')->where('id', $id)->update(['is_active' => ($status == "active") ? 1 : 0]);

            return redirect()->route('home.view-gift-promo')->with('success', 'Status updated successfully.');

        } catch (\Exception $e) {

            return redirect()->route('home.view-gift-promo')->with('error', 'Error updating status: ' . $e->getMessage());

        }

    }

    public function constant(Request $request){

        if($request->constant_id != null){    

            DB::table('constants')->where('id', $request->constant_id)->update(   [
                "cod_charge"        => $request->cod_charge,
                "wallet_use_amount" => $request->wallet_use_amount,
                "gift_min_amount"   => $request->gift_min_amount,
                "cod_max_process_amount"  => $request->cod_max_process_amount,
                "quantity"          => $request->quantity,
                "referrer_amount"   => $request->referrer_amount,
                "referee_amount"    => $request->referee_amount
              ]);

             $request->session()->flash('success', 'Constants updated successfully!');
        }

        $constant = DB::table('constants')->first();

        
        return view('admin.Setting.constant' , compact('constant'));
    }

    public function crm(Request $request){

        $crm =  DB::table('admin_settings')->first();

        if($request->crm_id != null){    

            if($request->hasFile('img')){

                $fullImagePath = uploadImage($request->file('img'), 'Crm');
    
            }else{
                $fullImagePath =  $crm->logo;
            }

            DB::table('admin_settings')->where('id', $request->crm_id)->update([

                'sitename'       => ucwords($request->input('sitename')),
                'instagram_link' => $request->input('instagram_link'),
                'facebook_link'  => $request->input('facebook_link'),
                'youtube_link'   => $request->input('youtube_link'),
                'twitter_link'   => $request->input('twitter_link'),
                'linkedin_link'  => $request->input('linkedin_link'),
                'address'        => $request->input('address'),
                'phone'          => $request->input('phone'),
                'logo'           => $fullImagePath,
                'ip'             => $request->ip(),
    
            ]);

             $request->session()->flash('success', 'crm updated successfully!');
        }


        return view('admin.Setting.crm' , compact('crm'));
    }
}
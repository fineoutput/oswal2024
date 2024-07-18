<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trending;

class TrendingController extends Controller
{

    public function index()
    {
       
        $trendings = Trending::with('product')->orderBy('id', 'desc')->get();

        $tranding_url = 'trending';

        return view('admin.Trending.view-trending', compact('trendings' , 'tranding_url'));
    }

    public function create($id = null, Request $request)

    {
        $trending = null;

        $products   = sendProduct();

        $tranding_url = route('trending.store');

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('trending.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $trending = Trending::find(base64_decode($id));

        }

        return view('admin.Trending.add-trending', compact('trending' ,'products' ,'tranding_url'));
    }

    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'product_id'              => 'required|string',
            'trending'              => 'required|integer',
        ];

        if (!isset($request->trending_id)) {

            $trending = new Trending;

        } else {

            $trending = Trending::find($request->trending_id);
            
            if (!$trending) {
                
                return redirect()->route('trending.index')->with('error', 'Trending not found.');
                
            }

        }

        $request->validate($rules);

        $trending->fill($request->all());

        $trending->ip = $request->ip();

        $trending->date = now();

        $trending->added_by = Auth::user()->id;

        $trending->is_active = 1;

        if ($trending->save()) {

            $message = isset($request->trending_id) ? 'Trending updated successfully.' : 'Trending inserted successfully.';

            return redirect()->route('trending.index')->with('success', $message);

        } else {

            return redirect()->route('trending.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $trending = Trending::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $trending->updateStatus(strval(1));
        } else {

            $trending->updateStatus(strval(0));
        }

        return  redirect()->route('trending.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('trending.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Trending::where('id', $id)->delete()) {

            return  redirect()->route('trending.index')->with('success', 'Trending Deleted Successfully.');
        } else {
            return redirect()->route('trending.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('trending.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
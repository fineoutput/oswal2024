<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ComboProduct;
use App\Models\Type;

class ComboProductController extends Controller
{

    public function index()
    {

        $comboproducts = ComboProduct::with('mainproduct' ,'comboproduct' ,'maintype' ,'combotype')->orderBy('id', 'desc')->get();

        return view('admin.ComboProducts.view-comboproduct', compact('comboproducts'));
    }

    public function create(Request $request, $id = null)

    {
        $comboproduct = null;
       
        $products = sendProduct();

        $maintypes = null;

        $combotypes = null ;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('comboproduct.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $comboproduct = ComboProduct::find(base64_decode($id));

            $maintypes =  Type::where('product_id', $comboproduct->main_product)->select('id', 'type_name')->groupBy('type_name')->get();

            $combotypes =  Type::where('product_id', $comboproduct->combo_product)->select('id', 'type_name')->groupBy('type_name')->get();

        }

        return view('admin.ComboProducts.add-comboproduct', compact('comboproduct' ,'products' ,'maintypes' ,'combotypes'));
    }


    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'main_product'              => 'required',
            'main_type'                 => 'required',
            'combo_product'             => 'required',
            'combo_type'                => 'required',
        ];

        if (!isset($request->comboproduct_id)) {

            $comboproduct = new ComboProduct;

        } else {

            $comboproduct = ComboProduct::find($request->comboproduct_id);
            
            if (!$comboproduct) {
                
                return redirect()->route('comboproduct.index')->with('error', 'Combo product not found.');
                
            }

        }

        $request->validate($rules);

        $comboproduct->fill($request->all());

        $comboproduct->ip = $request->ip();

        $comboproduct->date = now();
        
        $comboproduct->user_type = $request->user_type;

        $comboproduct->added_by = Auth::user()->id;

        $comboproduct->is_active = 1;

        if ($comboproduct->save()) {

            $message = isset($request->comboproduct_id) ? 'Combo Product updated successfully.' : 'Combo Product inserted successfully.';

            return redirect()->route('comboproduct.index')->with('success', $message);

        } else {

            return redirect()->route('comboproduct.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $comboproduct = ComboProduct::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $comboproduct->updateStatus(strval(1));
        } else {

            $comboproduct->updateStatus(strval(0));
        }

        return  redirect()->route('comboproduct.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('comboproduct.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (ComboProduct::where('id', $id)->delete()) {

            return  redirect()->route('comboproduct.index')->with('success', 'Combo Product Deleted Successfully.');

        } else {

            return redirect()->route('comboproduct.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('comboproduct.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}
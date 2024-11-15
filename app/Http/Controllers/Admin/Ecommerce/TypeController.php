<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Type;
use App\Models\Type_sub;
use App\Models\VendorType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class TypeController extends Controller
{

    public function index($pid,$cid,$pcid) {
        

        $p_id  = decrypt($pid);

        $c_id  = decrypt($cid);

        $pc_id = decrypt($pcid);
        
        $types = Type::with('state', 'city')
        ->where('product_id', $p_id)
        ->where('category_id', $c_id)
        ->orderBy('id', 'DESC')
        ->get();


        return view('admin.Ecommerce.Type.type-index', compact('types', 'p_id', 'c_id', 'pc_id'));

    }

    public function create($pid, $cid , $pcid) {

        $p_id  = decrypt($pid);

        $c_id  = decrypt($cid);
        
        $pc_id = decrypt($pcid);

        $type = null;

        return view('admin.Ecommerce.Type.type-create' , compact('p_id', 'c_id', 'pc_id', 'type'));
    }

    public function edit($pid, $cid , $pcid , $tid) {

        $p_id  = decrypt($pid);

        $c_id  = decrypt($cid);
        
        $pc_id = decrypt($pcid);

        $t_id = decrypt($tid);

        $type = Type::with('state','city')->where('id', $t_id)->where('product_id', $p_id)->where('category_id', $c_id)->first();

        return view('admin.Ecommerce.Type.type-create' , compact('p_id', 'c_id', 'pc_id', 'type'));
    }

    public function store(Request $request) {

        $rules = [
            'name'                 => 'required|string|max:255',
            'del_mrp'              => 'required|numeric',
            'gst_percentage'       => 'required|numeric',
            'mrp'                  => 'required|numeric',
            'gst_percentage_price' => 'required|numeric',
            'selling_price'        => 'required|numeric',
            'weight'               =>'required|string|max:255',
            'rate'                 => 'required|string|max:255',
        ];

        $request->validate($rules);

        $type = isset($request->type_id) ? Type::find($request->type_id) : new VendorType;

        $type->fill([

            'type_name'     => $request->name,

            'type_name_hi'  => lang_change($request->name),

            'del_mrp'       => $request->del_mrp,

            'mrp'           => $request->mrp,

            'gst_percentage'=> $request->gst_percentage,

            'gst_percentage_price' => $request->gst_percentage_price,

            'selling_price' => $request->selling_price,

            'weight'        => $request->weight,

            'rate'          => $request->rate,

            'ip'            => $request->ip(),

            'date'          => now(),

            'added_by'      => Auth::user()->id,

            'is_active'     => 1,

        ]);

        $type->product_id = $request->product_id;

        $type->category_id = $request->category_id;

        if (isset($request->type_id)) {

            return $this->updateType($type, $request);

        } else {

            return $this->createTypeWithStatesAndCities($type, $request);

        }
        
    }

    private function updateType($type, $request) {

        $type->state_id = $request->state_id;

        $type->city_id = $request->city_id;
        
        if ($type->save()) {

            $routeParameters = [
                'pid'  => encrypt($type->product_id),
                'cid'  => encrypt($type->category_id),
                'pcid' => encrypt($request->product_category_id),
            ];

            return redirect()->route('type.index', $routeParameters)->with('success', 'Type updated successfully.');

        } else {

            return redirect()->route('product.index', encrypt($request->product_category_id))->with('error', 'Something went wrong. Please try again later.');

        }
 
    }

    private function createTypeWithStatesAndCities($type, $request) {

        $type->state_id = "99999";

        $type->city_id = "99999";

        DB::beginTransaction();

        try {

            if (!$type->save()) {

                throw new \Exception('Something went wrong while saving the type.');

            }

            $states = State::with('cities')->get();

            if ($states->isEmpty()) {

                throw new \Exception('No states found.');

            }

            foreach ($states as $state) {

                if ($state->cities->isEmpty()) {

                    continue;

                }

                foreach ($state->cities as $city) {

                    $newType = $type->replicate();

                    $newType->state_id = $state->id;

                    $newType->city_id = $city->id;
                    
                    if (!$newType->save()) {

                        throw new \Exception('Something went wrong while saving the type for state and city.');

                    }
                }
            }

            $type->delete();

            DB::commit();

            $routeParameters = [

                'pid'  => encrypt($type->product_id),

                'cid'  => encrypt($type->category_id),

                'pcid' => encrypt($request->product_category_id),

            ];

            $message = 'Type inserted successfully.';

            return redirect()->route('type.index', $routeParameters)->with('success', $message);

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->route('product.index', encrypt($request->product_category_id))->with('error', 'An error occurred: ' . $e->getMessage());

        }

    }

    public function update_status($pid, $cid , $pcid ,$tid ,$status, Request $request) {

        $id = decrypt($tid);

        $admin_position = $request->session()->get('position');

        $type = Type::find($id);
        
        // if ($admin_position == "Super Admin") {

            if ($status == "active") {

                $type->updateStatus(strval(1));
            } else {

                $type->updateStatus(strval(0));
            }

            $routeParameters = [
                'pid'  => $pid,
                'cid'  => $cid,
                'pcid' => $pcid,
            ];

            return redirect()->route('type.index', $routeParameters)->with('success', 'Status Updated Successfully.');
      

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }
    }

    public function destroy($pid, $cid , $pcid ,$tid) {

        $id = decrypt($tid);

        $type = Type::find($id);

        // $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        
            $routeParameters = [
                'pid'  => $pid,
                'cid'  => $cid,
                'pcid' => $pcid,
            ];

            if ($type->destory()) {
        
                return redirect()->route('type.index', $routeParameters)->with('success', 'Type Deleted Successfully.');

            } else {

                return redirect()->route('type.index', $routeParameters)->with('success', 'Some Error Occurred.');
            }

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function updateCityType(Request $request) {

        // $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

            $request->validate([
                'type_id' => 'required|array',
                'type_id.*' => 'required|integer|exists:types,id',
            ]);

            $type_ids = $request->input('type_id');

            $category_id = encrypt($request->input('category_id'));

            $product_id = encrypt($request->input('product_id'));

            $product_category_id = encrypt($request->input('product_category_id'));

            foreach ($type_ids as $type_id) {

                $type = Type::find($type_id);

                $type->fill([
        
                    'del_mrp'       => round($request->input('del_mrp' . $type_id)),
        
                    'mrp'           => round($request->input('mrp' . $type_id)),
        
                    'gst_percentage'=> round($request->input('gst_percentage' . $type_id)),
        
                    'gst_percentage_price' =>  round($request->input('gst_percentage_price' . $type_id)),
        
                    'selling_price' => round($request->input('selling_price' . $type_id)),

                    'date'          => now(),

                    'added_by'      => Auth::user()->id,

                ]);
                
                $type->save();

            }

            $routeParameters = [
                'pid'  => $product_id,
                'cid'  => $category_id,
                'pcid' => $product_category_id,
            ];

            return redirect()->route('type.index', $routeParameters)->with('success', 'Type updated Successfully.');

        // } else {
        //     return redirect()->route('login')->with('error', 'Please log in as an admin');
        // }
    }

    public function updateAll ($pid, $cid , $pcid) {

        $p_id  = decrypt($pid);

        $typeData1 = Type::where('product_id', $p_id)->get();
        
        $typeData = $typeData1->firstOrFail();

        $arr1 = $typeData1->pluck('type_name')->unique();
        
        return view('admin.Ecommerce.Type.type-update-all', ['pc_id' => $pcid, 'type' => $typeData, 'p_id' => $pid,'c_id' => $cid, 'type_data1' => $arr1 ]);
    }

    public function updateAllData(Request $request) {

        $rules = [
            'type_name'            => 'required|string|max:255',
            'del_mrp'              => 'required|numeric',
            'gst_percentage'       => 'required|numeric',
            'mrp'                  => 'required|numeric',
            'gst_percentage_price' => 'required|numeric',
            'selling_price'        => 'required|numeric',
            'weight'               =>'required|string|max:255',
            'rate'                 => 'required|string|max:255',
        ];

        $request->validate($rules);

        $typeData = Type::where('product_id', decrypt($request->product_id))->first();

        if (!$typeData) {

            return redirect()->back()->with('error', 'Type not found.');
        }

        $dataInsert = [

            'del_mrp' => $request->input('del_mrp'),

            'mrp' => $request->input('mrp'),

            'gst_percentage' => $request->input('gst_percentage'),

            'gst_percentage_price' => $request->input('gst_percentage_price'),

            'selling_price' => $request->input('selling_price'),

            'weight' => $request->input('weight'),

            'rate' => $request->input('rate'),

            'ip' => $request->ip(),

            'date' => now(),

            'added_by' => Auth::User()->id,

        ];

        $updated = Type::where('product_id', decrypt($request->product_id))->where('type_name', $request->input('type_name'))->update($dataInsert);

        $routeParameters = [ 'pid'  => $request->product_id, 'cid'  => $request->category_id, 'pcid' => $request->product_category_id];

        if ($updated) {

            return redirect()->route('type.index', $routeParameters)->with('success', 'Data updated successfully.');
          
        } else {

            return redirect()->back()->with('emessage', 'Sorry, an error occurred');
        }
        
    }


    /*****************Vendor Type Function ********************/

    public function vendorIndex($pid, $cid=null, $pcid=null) {
        $p_id  = decrypt($pid);

        $c_id  = decrypt($cid);

        $pc_id = decrypt($pcid);
        
        $types = VendorType::with('state', 'city')
        ->where('product_id', $p_id)
        // ->where('category_id', $c_id)
        ->orderBy('id', 'DESC')
        ->get();


        return view('admin.Ecommerce.Vendor-Type.type-index', compact('types', 'p_id', 'c_id', 'pc_id'));
    }
    

    public function vendorCreate($pid, $cid , $pcid) {

        $p_id  = decrypt($pid);

        $c_id  = decrypt($cid);
        
        $pc_id = decrypt($pcid);

        $type = null;

        return view('admin.Ecommerce.Vendor-Type.type-create' , compact('p_id', 'c_id', 'pc_id', 'type'));
    }

    public function vendorsubCreate($id) {
        $id  = decrypt($id);
        return view('admin.Ecommerce.Vendor-Type.type-sub-create' ,compact('id'));
    }

    public function vendorsubView($id,$p_id = null)
{
    $id = decrypt($id);
    // dd($p_id); 
    $types = DB::table('type_subs')
                 ->where('type_id', $id)
                 ->get();
    return view('admin.Ecommerce.Vendor-Type.type-sub-index', compact('id', 'types', 'p_id'));
}


    public function vendorEdit($pid, $cid , $pcid , $tid) {

        $p_id  = decrypt($pid);

        $c_id  = decrypt($cid);
        
        $pc_id = decrypt($pcid);

        $t_id = decrypt($tid);

        $type = VendorType::with('state','city')->where('id', $t_id)->where('product_id', $p_id)->where('category_id', $c_id)->first();

        return view('admin.Ecommerce.Vendor-Type.type-create' , compact('p_id', 'c_id', 'pc_id', 'type'));
    }

    public function vendorStore(Request $request) 
    {
    //  dd('hehh');
        $rules = [
            'name'                 => 'required|string|max:255',
            'min_qty'              => 'required|numeric',
        ];
        // $rules = [
        //     'name'                 => 'required|string|max:255',
        //     'del_mrp'              => 'required|numeric',
        //     'min_qty'              => 'required|numeric',
        //     'start_range'          => 'required|numeric',
        //     'end_range'            => 'required|numeric',
        //     'gst_percentage'       => 'required|numeric',
        //     'mrp'                  => 'required|numeric',
        //     'gst_percentage_price' => 'required|numeric',
        //     'selling_price'        => 'required|numeric',
        //     'weight'               =>'required|string|max:255',
        //     'rate'                 => 'required|string|max:255',
        // ];

        $request->validate($rules);

        $type = isset($request->type_id) ? VendorType::find($request->type_id) : new VendorType;

        $type->fill([

            'type_name'     => $request->name,
            'min_qty'       => $request->min_qty,

        ]);

        $type->product_id = $request->product_id;

        $type->category_id = $request->category_id;

        $routeParameters = [
            'pid'  => encrypt($type->product_id),
            'cid'  => encrypt($type->category_id),
            'pcid' => encrypt($request->product_category_id),
        ];

        if($type->save()){

            if (isset($request->type_id)) {

                return redirect()->route('vendor.type.index', $routeParameters)->with('success', 'Type updated successfully.');
    
            } else {
    
               return redirect()->route('vendor.type.index', $routeParameters)->with('success', 'Type Create successfully.');
    
            }

        }else{

            return redirect()->route('product.index', encrypt($request->product_category_id))->with('error', 'Something went wrong. Please try again later.');
        }
        
    }
    

    public function VendorSubStore(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
           'start_range' => 'required|numeric',
           'end_range' => 'required|numeric',
           'type_id' => 'required|numeric',
           'gst_percentage' => 'nullable|numeric',
           'gst_percentage_price' => 'nullable|numeric',
           'mrp' => 'nullable|numeric',
           'selling_price' => 'nullable|numeric',
           'selling_price_gst' => 'nullable|numeric',
          ]);
          
        Type_sub::create($validated);
        // $encryptedId = Crypt::encryptString($request->id);
        $crid = Crypt::encrypt($request->type_id);
        return redirect()->route('vendor.type.subtype.view', ['id' => $crid])->with('success', 'Range added successfully!');
    }

    public function subedit($id)
    {
        // dd('$id = Crypt::decrypt($id)');
        // $data = Type_sub::find($id); // Find the type by ID
        $decryptedId = Crypt::decrypt($id);
        $data = Type_sub::find($decryptedId);
        if (!$data) {
            return redirect()->route('vendor.type.index')->with('error', 'Type not found');
        }

        return view('admin.Ecommerce.Vendor-Type.type-sub-create', compact('data'));
    }

    public function vendor_update_status($pid, $cid , $pcid ,$tid ,$status, Request $request) {

        $id = decrypt($tid);

        $admin_position = $request->session()->get('position');

        $type = VendorType::find($id);
        
        // if ($admin_position == "Super Admin") {

            if ($status == "active") {

                $type->updateStatus(strval(1));
            } else {

                $type->updateStatus(strval(0));
            }

            $routeParameters = [
                'pid'  => $pid,
                'cid'  => $cid,
                'pcid' => $pcid,
            ];

            return redirect()->route('vendor.type.index', $routeParameters)->with('success', 'Status Updated Successfully.');
      

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }
    }

    public function vendor_destroy($pid, $cid , $pcid ,$tid) {

        $id = decrypt($tid);

        $type = VendorType::find($id);

        // $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        
            $routeParameters = [
                'pid'  => $pid,
                'cid'  => $cid,
                'pcid' => $pcid,
            ];

            if ($type->destory()) {
        
                return redirect()->route('vendor.type.index', $routeParameters)->with('success', 'Type Deleted Successfully.');

            } else {

                return redirect()->route('vendor.type.index', $routeParameters)->with('success', 'Some Error Occurred.');
            }

        // } else {

        // 	return  redirect()->route('category.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function Subdestroy($id){
    $decryptedId = Crypt::decrypt($id);
    $typeSub = Type_sub::find($decryptedId);
    if ($typeSub) {
        $typeSub->delete();
        return redirect()->back()->with('success', 'Record deleted successfully!');
    }
    return redirect()->back()->with('error', 'Record not found!');
    }
    public function subupdate(Request $request)
    {
        // Validation
        // DD($request);
        $validated = $request->validate([
            'start_range' => 'required|numeric',
            'end_range' => 'required|numeric',
            'mrp' => 'required|numeric',
            'gst_percentage' => 'required|numeric',
            'selling_price_gst' => 'required|numeric',
            'gst_percentage_price' => 'nullable|numeric',
            'selling_price' => 'required|numeric',
            
        ]);

        // Find and update the type
        if (!$request->has('sub_type_id')) {
            return back()->withErrors('Sub Type ID is missing.');
        }
        
        // Find the type by the sub_type_id
        $type = Type_sub::find($request->sub_type_id);
        
        if (!$type) {
            return redirect()->back()->with('error', 'Type not found');
        }

        $type->update([
            'start_range' => $request->start_range,
            'end_range' => $request->end_range,
            'mrp' => $request->mrp,
            'gst_percentage' => $request->gst_percentage,
            'selling_price_gst' => $request->selling_price_gst,
            'gst_percentage_price' => $request->gst_percentage_price,
            'selling_price' => $request->selling_price,
        ]);
        $crid = Crypt::encrypt($request->type_id);
        return redirect()->route('vendor.type.subtype.view', ['id' => $crid])->with('success', 'Range updated successfully!');
    }
}

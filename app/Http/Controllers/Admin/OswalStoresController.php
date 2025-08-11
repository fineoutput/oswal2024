<?php
namespace App\Http\Controllers\Admin;

use App\Models\WalletTransactionHistory;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Popupimage;
use App\Models\OswalStores;
use App\Models\State;

use App\Models\City;

class OswalStoresController extends Controller
{



public function updateStatus($encodedId)
{
    $id = base64_decode($encodedId);
    $store = OswalStores::findOrFail($id);

    $store->status = ($store->status == '1') ? '2' : '1';
    $store->save();

    return redirect()->back()->with('success', 'Store status updated successfully!');
}
    
    
    public function index()
    {
        
        $pageTittle = 'Oswal Stores';

            $users = OswalStores::orderBy('id', 'desc')->get();

            return view('admin.Stores.index', compact('users', 'pageTittle'));

    }

    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)->get();
        return response()->json($cities);
    }

   public function create(Request $request)
    {
        $data['pageTittle'] = 'Create Store';
        $data['states'] = State::all();
        return view('admin.Stores.create',$data);
    }


    public function store(Request $request)

    {

            $request->validate([
                'store_name'     => 'required|string|max:255',
                'operator_name'  => 'required|string|max:255',
                'phone_no'       => 'required|string|max:15',
                'GST_No'         => 'nullable|string|max:20',
                'address'        => 'required|string',
                'state_id'       => 'required|integer',
                'city_id'        => 'required|integer',
                'locality'       => 'required|string|max:255',
                'shop_code'      => 'required|string|max:100|unique:oswal_stores,shop_code',
            ]);

            // Create new store entry
            OswalStores::create([
                'store_name'    => $request->store_name,
                'operator_name' => $request->operator_name,
                'phone_no'      => $request->phone_no,
                'GST_No'        => $request->GST_No,
                'address'       => $request->address,
                'state_id'      => $request->state_id,
                'city_id'       => $request->city_id,
                'locality'      => $request->locality,
                'shop_code'     => $request->shop_code,
                'status'     => 1,
            ]);

            return redirect()->route('store.index')->with('success', 'Store added successfully.');

    }

            public function edit(Request $request,$id)
            {
                $id = base64_decode($id);

            $store = OswalStores::findOrFail($id);

            $data['pageTittle'] = 'Edit Store';
            $data['oswalstore'] = $store;
            $data['states'] = State::all();

            // 👇 Add this line to load cities of the selected state
            $data['cities'] = City::where('state_id', $store->state_id)->get();

            return view('admin.Stores.edit', $data);
        }


    //   public function destroy($id, Request $request)
    //     {
    //         $id = base64_decode($id);

    //         $store = OswalStores::findOrFail($id);
    //         $store->delete();

    //         return redirect()->route('store.index')->with('success', 'Store deleted successfully.');
    //     }

    public function destroy($id, Request $request)
{
    $id = base64_decode($id);

    $store = OswalStores::findOrFail($id);
    $store->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Store deleted successfully.'
    ]);
}

    public function update(Request $request, $id)
    {
        $id = base64_decode($id);

        $request->validate([
            'store_name'     => 'required|string|max:255',
            'operator_name'  => 'required|string|max:255',
            'phone_no'       => 'required|string|max:15',
            'GST_No'         => 'nullable|string|max:20',
            'address'        => 'required|string',
            'state_id'       => 'required|integer',
            'city_id'        => 'required|integer',
            'locality'       => 'required|string|max:255',
            'shop_code'      => 'required',
        ]);

        $store = OswalStores::findOrFail($id);
        $store->update([
            'store_name'    => $request->store_name,
            'operator_name' => $request->operator_name,
            'phone_no'      => $request->phone_no,
            'GST_No'        => $request->GST_No,
            'address'       => $request->address,
            'state_id'      => $request->state_id,
            'city_id'       => $request->city_id,
            'locality'      => $request->locality,
            'shop_code'     => $request->shop_code,
        ]);

        return redirect()->route('store.index')->with('success', 'Store updated successfully.');


    }
}
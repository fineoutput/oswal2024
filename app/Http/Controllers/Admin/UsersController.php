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

class UsersController extends Controller
{


    public function popupimage(Request $request){
        $data['popup'] = Popupimage::orderBy('id','DESC')->get();
        return view('admin/popupimage',$data);
    }

    public function addpopupimage(Request $request)
{
    if ($request->method() == 'POST') {

        // Validate the incoming request to ensure the file is an image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Check if an image file is uploaded
        if ($request->hasFile('image')) {

            // Store the image to public/uploads/popup_images
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); // Create a unique name for the image

            // Move the image to the 'public/uploads/popup_images' directory
            $image->move(public_path('uploads/popup_images'), $imageName);

            // Save the image path to the database
            $popupImage = Popupimage::create([
                'image' => 'uploads/popup_images/' . $imageName // Store the relative path in the database
            ]);

            // Optionally, you can show a success message or return a response
            return redirect('admin/popupimage')->with('success', 'Image uploaded successfully');
        }

        // If no file was uploaded, return with an error
        return redirect('admin/popupimage')->with('error', 'No image file uploaded');
    }
    return view('admin/addpopupimage');
}


public function destroypopup($id)
    {
        $popupImage = Popupimage::find($id);

        if ($popupImage) {
            Storage::delete($popupImage->image);
            $popupImage->delete();

            return redirect()->back()->with('success', 'Image deleted successfully');
        }

        // In case the image doesn't exist, redirect with error message
        return redirect()->back()->with('error', 'Image not found');
    }

    public function editpopup($id)
    {
        $popup = Popupimage::findOrFail($id); // Find the popup image by ID
        return view('admin.editpopupimage', compact('popup'));
    }

    // Update the popup image in the database
    public function updatepopup(Request $request, $id)
    {
        // Validate the uploaded image
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    
        // Find the existing popup image record
        $popup = Popupimage::findOrFail($id);
    
        // If a new image is uploaded, delete the old one and store the new image
        if ($request->hasFile('image')) {
            // Delete the old image from the public/uploads/popup_images directory
            $oldImagePath = public_path('uploads/popup_images/' . basename($popup->image));
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Delete the old image
            }
    
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); // Create a unique name for the image
    
            // Store the new image in 'uploads/popup_images' directory inside the public directory
            $image->move(public_path('uploads/popup_images'), $imageName);
    
            // Update the image path in the database (save only the relative path)
            $popup->image = 'uploads/popup_images/' . $imageName;
        }
    
        // Save the updated record
        $popup->save();
    
        // Redirect with a success message
        return redirect('admin/popupimage')->with('success', 'Popup image updated successfully!');
    }
    
    
    public function index()
    {
        
        $currentRouteName = Route::currentRouteName();

        if($currentRouteName == 'user.vendor.approve') {

            $pageTittle = 'Vendor Users';

            $users = User::with('vendor')->where('role_type', 2)->where('is_active', 1)->orderBy('id', 'desc')->get();

            return view('admin.Vendor.view-user', compact('users', 'pageTittle'));

        }elseif($currentRouteName == 'user.index'){

            $pageTittle = 'Customer Users';

            $users = User::where('role_type', '!=' , 2)->orderBy('id', 'desc')->get();

            return view('admin.Users.view-user', compact('users', 'pageTittle'));

        }elseif($currentRouteName == 'user.vendor.pending') {

            $pageTittle = 'Vendor Users';

            $users = User::with('vendor')->where('role_type', 2)->where('is_active', 0)->orderBy('id', 'desc')->get();

            return view('admin.Vendor.view-user', compact('users', 'pageTittle'));

        }

    }

    public function create(Request $request, $id = null)

    {
        $user = null;
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('user.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $user = User::find(base64_decode($id));
        }

        return view('admin.Users.add-user', compact('user'));
    }


    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'first_name'              => 'required',
            'email'                   => 'required|email',
            'contact'                 => 'required|digits:10',
            'password'                => 'nullable',
            'img'                     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];

        if (!isset($request->user_id)) {

            $user = new User;

        } else {

            $user = User::find($request->user_id);
            
            if (!$user) {
                
                return redirect()->route('user.index')->with('error', 'User not found.');
                
            }

        }

        $request->validate($rules);

        $user->fill($request->all());

        if ($request->password) {

            $user->password = Hash::make($request->password);

        }

        if($request->hasFile('img')){

            $user->image = uploadImage($request->file('img'), 'User');

        }

        $user->first_name_hi = lang_change($request->first_name);

        $user->ip = $request->ip();

        $user->date = now();

        $user->added_by = Auth::user()->id;

        $user->is_active = 1;

        if ($user->save()) {

            $message = isset($request->user_id) ? 'User updated successfully.' : 'User inserted successfully.';

            return redirect()->route('user.index')->with('success', $message);

        } else {

            return redirect()->route('user.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $currentRouteName = Route::currentRouteName();

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $user = User::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $user->updateStatus(strval(1));
        } else {

            $user->updateStatus(strval(0));
        }

        if($currentRouteName == 'user.vendor.update-status'){

            return  redirect()->route('user.vendor.approve')->with('success', 'Status Updated Successfully.');

        }else{

            return  redirect()->route('user.index')->with('success', 'Status Updated Successfully.');
        }

        // } else {

        // 	return  redirect()->route('user.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (User::where('id', $id)->delete()) {

            return  redirect()->route('user.index')->with('success', 'User Deleted Successfully.');
        } else {
            return redirect()->route('user.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('user.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function updateWallet(Request $request) {

        $request->validate([
            'wallet_amount' => 'required|numeric',
            'type'          => 'required|in:credit,debit',
            'description'   => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);

        $transactionData = [
            'user_id' =>  $user->id,
            'transaction_type' => $request->type, 
            'amount' => $request->wallet_amount,
            'transaction_date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
            'status' => WalletTransactionHistory::STATUS_COMPLETED,
            'description' => $request->description,
        ];
        
        WalletTransactionHistory::createTransaction($transactionData);
 
        if ($request->type == 'credit') {
            $user->wallet_amount += $request->wallet_amount;
        } else {
            $user->wallet_amount -= $request->wallet_amount;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Wallet amount updated successfully!');
    }
}
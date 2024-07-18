<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{

    public function index()
    {

        $users = User::orderBy('id', 'desc')->get();

        return view('admin.Users.view-user', compact('users'));
    }

    public function create($id = null, Request $request)

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

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $user = User::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $user->updateStatus(strval(1));
        } else {

            $user->updateStatus(strval(0));
        }

        return  redirect()->route('user.index')->with('success', 'Status Updated Successfully.');

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

}
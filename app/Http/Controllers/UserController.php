<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LinkedAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return view('user.pages.index');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.pages.profile.index', compact('user'));
    }

    public function profileupdate(Request $request)
    {
        // Validate the form data including the optional image upload
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'address1'      => 'nullable|string|max:255',
            'address2'      => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:255',
            'postal'        => 'nullable|string|max:20',
            'user_profile'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Find the user
        $user = User::findOrFail($request->id);

        // Update user text information
        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->address1     = $request->address1;
        $user->address2     = $request->address2;
        $user->city         = $request->city;
        $user->postal_code  = $request->postal;

        // Check if a new profile image was uploaded
        if ($request->hasFile('user_profile')) {
            // Get the uploaded file instance
            $image = $request->file('user_profile');

            // Create a unique file name
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Define the destination path (e.g., public/uploads)
            $destinationPath = public_path('/uploads');

            // Move the image to the destination path
            $image->move($destinationPath, $imageName);

            // Optionally, delete the old image if it exists (not shown here)
            // Update the user's profile image field with the new file name
            $user->user_profile = $imageName;
        }

        // Save the updated user data
        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'User information updated successfully.');
    }

    public function switchBack()
    {
        if (!session()->has('original_user_id')) {
            abort(403, 'Not allowed');
        }

        $originalId = session()->pull('original_user_id');
        Auth::loginUsingId($originalId);

        return redirect()->route('user.dashboard')->with('message', 'Switched back to original account.');
    }

     public function switch($id)
    {
          if (!session()->has('original_user_id')) {
                // Prevent switch back if user is logged in as guest directly
                return redirect()->route('user.dashboard')->with('error', 'Direct guest login cannot switch account.');
            }

            $originalUserId = session('original_user_id');

            Auth::loginUsingId($originalUserId);

            session()->forget('original_user_id');

            return redirect()->route('admin.dashboard')->with('message', 'Switched back to main account.');
            }

    public function switchRole()
    {
        $user = auth()->user();

        $activeRole = $user->roles()->wherePivot('is_active', true)->first();
        $otherRole = $user->roles()->wherePivot('is_active', false)->first();
        if ($activeRole) {
            // Deactivate current
            DB::table('role_user')
                ->where('user_id', $user->id)
                ->where('role_id', $activeRole->id)
                ->update(['is_active' => false]);

            // Activate other
            DB::table('role_user')
                ->where('user_id', $user->id)
                ->where('role_id', $otherRole->id)
                ->update(['is_active' => true]);

            return redirect()->route('admin.dashboard')->with('message', 'Switched role to ' . $otherRole->name);
        }

        return redirect()->back()->with('error', 'No other role to switch to.');
    }


    public function logindetails()
    {
        $user = Auth::user();
        return view('user.pages.logindetails.index', compact('user'));
    }

    public function loginupdate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->id);

        // Check if the old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The old password is incorrect.']);
        }

        // Update user details
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Login Details updated successfully.');
    }


    public function accountLink()
    {
        if (!session()->has('original_user_id')) {
            // Option 1: Redirect if no original_user_id exists
            return redirect()->route('admin.dashboard')->with('error', 'No account context found.');

            // Option 2: Or show empty users list (uncomment this if you prefer)
            // return view('user.pages.account.index', ['users' => collect()]);
        }

        $originalUserId = session('original_user_id');

        $users = User::whereIn('id', function ($query) use ($originalUserId) {
            $query->select('user_id')
                ->from('linked_accounts')
                ->where('user_id', $originalUserId);
        })->paginate(10);

        return view('user.pages.account.index', compact('users'));
    }



}

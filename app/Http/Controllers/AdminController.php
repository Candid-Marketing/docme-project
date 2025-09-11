<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Invitation;
use App\Mail\GuestFileInviteMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Mail\UserAddedMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\UserStructureFolder;
use App\Models\LinkedAccount;
use App\Models\FolderInvitation;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Invoice;
use App\Models\StripeConfigurations;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.pages.index');
    }

   public function manage()
    {
        $users = Auth::user()
        ->usersAdded()
        ->whereHas('roles', function ($query) {
            $query->where('role_id', 3);
        })
        ->paginate(10);

        return view('admin.pages.user.index', compact('users'));
    }


    public function accountLink()
    {
        $users = User::whereIn('id', function ($query) {
            $query->select('linked_user_id')
                  ->from('linked_accounts')
                  ->where('user_id', auth()->id());
        })->paginate(10);

        return view('admin.pages.account.index', compact('users'));
    }

   public function switch($id)
    {
        $mainUser = auth()->user();

        // ✅ Only allow switching if account is linked
        $linked = LinkedAccount::where('user_id', $mainUser->id)
            ->where('linked_user_id', $id)
            ->firstOrFail();

        // ✅ Retrieve the user you want to switch to
        $user = User::with('roles')->findOrFail($id);

        // ✅ Ensure the linked user has Guest role (role_id = 3)
        $isGuest = $user->roles->contains('id', 3);

        if (! $isGuest) {
            abort(403, 'You can only switch into Guest accounts.');
        }


       // ✅ Activate Guest role (role_id = 3)
        DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('role_id', 3)
            ->update(['is_active' => true]);

        // ✅ Deactivate User role (role_id = 2)
        DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('role_id', 2)
            ->update(['is_active' => false]);


        // ✅ Store original user session and switch
        session(['original_user_id' => $mainUser->id]);

        Auth::loginUsingId($id);

        return redirect()->route('admin.dashboard')->with('message', 'Switched account.');
    }



   public function switchRole($roleId)
    {
        $user = auth()->user();

        // Step 1: Check if this user has the selected role
        if (!$user->roles()->where('role_id', $roleId)->exists()) {
            abort(403, 'You do not have access to this role.');
        }

        // Step 2: Deactivate all roles
        $user->roles()->updateExistingPivot(
            $user->roles->pluck('id')->toArray(),
            ['is_active' => false]
        );

        // Step 3: Activate the selected role (e.g. Guest)
        $user->roles()->updateExistingPivot($roleId, ['is_active' => true]);

        return redirect()->back()->with('message', 'Switched to new role!');
    }



    public function switchBack()
    {
        if (!session()->has('original_user_id')) {
            abort(403, 'Not allowed');
        }

        $originalId = session()->pull('original_user_id');
        Auth::loginUsingId($originalId);

        return redirect()->route('admin.dashboard')->with('message', 'Switched back to original account.');
    }

    public function billing()
    {
        $users = User::where('id',AUth::user()->id)->paginate(5);
        return view('admin.pages.billing.index', compact('users'));
    }

    public function invoice()
    {
          $users = User::where('id',AUth::user()->id)->paginate(5);
         $stripe = StripeConfigurations::first();
         Stripe::setApiKey($stripe->stripe_secret);

            // Retrieve session
            $session = CheckoutSession::retrieve($user->session_id);

            // Get customer ID
            $customerId = $session->customer;

            // Retrieve invoices
            $invoices = Invoice::all([
                'customer' => $customerId,
                'limit' => 100,
            ]);

        return view('admin.pages.invoice.index', compact('users'));
    }

    public function searchInvoice(Request $request)
    {
        // Optional: Validate the 'query' parameter
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        // Get the search query input
        $query = $request->input('query');

        // Check if query is empty, return users where user status is not '1' (admin)
        $usersQuery = User::where('id', Auth::user()->id)
                        ->where('user_status', '!=', '1');  // Always check for user status

        // If there is a search query, apply additional search filters
        if ($query) {
            $usersQuery = $usersQuery->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
            });
        }

        // Get the users after applying the filters
        $users = $usersQuery->get();

        // Format the user data for response
        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at ? true : false,
                'user_status' => $user->user_status,
                'role' => $user->user_status == 1 ? "Admin" : ($user->user_status == 2 ? "User" : "Guest"),
                'is_verified' => $user->is_verified,
                'status' => $user->status,
                'invoice' => $user->invoice ? asset('receipts/' . basename($user->invoice->invoice_path)) : null,
                'invoice_date' => $user->invoice ? $user->invoice->invoice_date : null,
                'amount' => $user->invoice ? $user->invoice->amount : null,
            ];
        });

        // Return the formatted response
        return response()->json(['users' => $formattedUsers]);
    }


    public function profile()
    {
        $user = Auth::user();
        return view('admin.pages.profile.index', compact('user'));
    }

   public function createUser(Request $request)
    {
        $user = User::where('email', $request->email)
                    ->where('user_status', '!=', 1)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User does not exist .'], 422);
        }

        // Check if already linked to avoid duplicates
        $alreadyLinked = LinkedAccount::where('user_id', Auth::id())
                                    ->where('linked_user_id', $user->id)
                                    ->exists();

        if ($alreadyLinked) {
            return response()->json(['message' => 'This user is already linked to your account.'], 409);
        }

        // Create the linked account
        LinkedAccount::create([
            'user_id'        => Auth::id(),
            'linked_user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Guest account created and linked successfully!',
            'user'    => $user  // Optional: send back the user data
        ]);
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

    public function logindetails()
    {
        $user = Auth::user();
        return view('admin.pages.profile.login-details', compact('user'));
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

    public function searchGuest(Request $request)
    {
        $query = $request->input('query');

        // Check if query is empty, return all users
        if (!$query) {
            $users = User::all();
        } else {
            // Fetch users matching search query
            $users = User::where('first_name', 'LIKE', "%{$query}%")
                         ->orWhere('last_name', 'LIKE', "%{$query}%")
                         ->orWhere('email', 'LIKE', "%{$query}%")
                         ->where('user_status', 3)
                         ->get();
        }

        // Format response (including invoice URL if exists)
        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at ? true : false,
                'user_status' => $user->user_status,
                'role' => $user->user_status == 1 ? "Admin" : ($user->user_status == 2 ? "User" : "Guest"),
                'is_verified' => $user->is_verified,
                'status' => $user->status,
                'invoice' => $user->invoice ? asset('receipts/' . basename($user->invoice->invoice_path)) : null,
            ];
        });

        return response()->json(['users' => $formattedUsers]);
    }

    public function file(Request $request)
    {
        session()->forget('current_step');
        session()->forget('loan_type');
        $allFolders = UserStructureFolder::where('user_id', auth()->id())
        ->whereNull('parent_id')
        ->with('childrenRecursive')
        ->orderBy('created_at', 'desc') // newest first
        ->get();

        return view('admin.pages.settings.file',compact('allFolders'));
    }

    public function loan()
    {
        return view('admin.pages.settings.loan');
    }

    public function store_invite(Request $request): JsonResponse
    {
        $request->validate([
            'file_id' => 'required|exists:files,id',
            'guest_email' => 'required|email',
            'message' => 'nullable|string',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
        ]);

        $user = auth()->user();
        $plan = $user->plan;

        // Step 1: Count both file and folder invitations for this month
        $fileInvites = Invitation::where('inviter_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $folderInvites = FolderInvitation::where('inviter_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalInvites = $fileInvites + $folderInvites;


        // Step 2: Check if user exceeded share limit
      if ($plan && !is_null($plan->share_limit) && $totalInvites >= $plan->share_limit) {

            return response()->json([
                'success' => false,
                'error' => 'You have reached your guest sharing limit for this month.',
            ], 403);
        }

        // Step 3: Create and send invitation
        $invite = Invitation::create([
            'file_id' => $request->file_id,
            'inviter_id' => $user->id,
            'guest_email' => $request->guest_email,
            'message' => $request->message,
            'available_from' => $request->available_from,
            'available_until' => $request->available_until,
            'token' => Str::uuid(),
        ]);

        Mail::to($invite->guest_email)->send(new \App\Mail\GuestFileInviteMail($invite));

        return response()->json([
            'success' => true,
            'message' => 'Invitation sent successfully!',
            'data' => $invite
        ]);
    }


   public function addUser(Request $request)
    {
        $password = 'password123';

        $validatedData = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email',
            'user_status'  => 'required|integer|in:1,2,3', // 1=Admin, 2=User, 3=Guest
        ]);

        // 🚫 Prevent linking yourself
        if (Auth::user()->email === $validatedData['email']) {
            return response()->json(['message' => 'Cannot link your own account.'], 400);
        }

        // ✅ Create or get the user
       $user = User::where('email', $validatedData['email'])->first();

        if (!$user) {
            // Create user if not found
            $user = User::create([
                'email'       => $validatedData['email'],
                'first_name'  => $validatedData['first_name'],
                'last_name'   => $validatedData['last_name'],
                'user_status' => $validatedData['user_status'],
                'is_verified' => 1,
                'status'      => 1,
                'password'    => Hash::make($password),
            ]);
        }


        // ✅ If user was just created, attach role as active
        if ($user->wasRecentlyCreated) {
            $user->roles()->attach($validatedData['user_status'], ['is_active' => true]);
        } else {

            if ($user->user_status == 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only guest and users can be linked. This user is already registered .'
                ], 403);
            }

            if ($user->user_status == 2) {
                // ✅ Ensure guest role is attached if not already
                $hasGuestRole = DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->where('role_id', 3)
                    ->exists();
                if (! $hasGuestRole) {
                    DB::table('role_user')->insert([
                        'user_id'    => $user->id,
                        'role_id'    => 3,
                        'is_active'  => false, // Inactive if added later
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // ✅ Create linked account if not already
        LinkedAccount::firstOrCreate([
            'user_id'        => Auth::id(),
            'linked_user_id' => $user->id,
        ]);

        // ✅ Sync in pivot table that this user added them
        Auth::user()->usersAdded()->syncWithoutDetaching([$user->id]);

        // ✅ Email password only if user is new
        if ($user->wasRecentlyCreated) {
            Mail::to($user->email)->send(new UserAddedMail($user, $password));
        }

        // ✅ Return JSON response
        return response()->json([
            'message' => $user->wasRecentlyCreated
                ? 'New user created and linked successfully!'
                : 'Existing user linked successfully!',
            'user' => [
                'id'                => $user->id,
                'first_name'        => $user->first_name,
                'last_name'         => $user->last_name,
                'email'             => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'role'              => match ($user->user_status) {
                    1 => 'Admin',
                    2 => 'User',
                    3 => 'Guest',
                    default => 'Unknown',
                },
                'is_verified' => $user->is_verified,
                'status'      => $user->status,
                'invoice'     => optional($user->invoice)->invoice_path
                    ? asset('receipts/' . basename($user->invoice->invoice_path))
                    : null,
            ]
        ]);
    }




    public function editUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$request->user_id,
            'user_status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the user by ID and update
        $user = User::findOrFail($request->user_id);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'user_status' => $request->user_status,
        ]);

        return response()->json(['message' => 'User updated successfully!']);
    }

    public function deleteUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully!']);
    }

    public function searchUser(Request $request)
    {
        $query = $request->input('query');

        // Check if query is empty, return all users
        if (!$query) {
            $users = User::all();
        } else {
            // Fetch users matching search query
            $users = User::where('user_status', 3)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->get();

        }

        // Format response (including invoice URL if exists)
        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at ? true : false,
                'user_status' => $user->user_status,
                'role' => $user->user_status == 1 ? "Admin" : ($user->user_status == 2 ? "User" : "Guest"),
                'is_verified' => $user->is_verified,
                'status' => $user->status,
                'invoice' => $user->invoice ? asset('receipts/' . basename($user->invoice->invoice_path)) : null,
            ];
        });

        return response()->json(['users' => $formattedUsers]);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\StripeConfigurations;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\HomePage;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Jobs\GenerateInvoicePdfJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use App\Models\Invoice;
use App\Mail\InvoiceMail;
use App\Mail\UserAddedMail;
use App\Models\UserPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use App\Mail\PasswordResetCode;
use Illuminate\Support\Facades\Validator;
use Stripe\Customer;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        Auth::logout();
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->is_verified == 0 || $user->is_verified == null) {
                return redirect()->route('send-otp', ['email' => $user->email]);
            }
            if ($user->status == 0 || $user->status == null) {
                return redirect()->route('stripe.payment');
            }

            if ($user->expiration_date < now() && $user->expiration_date != null) {
                $user->status = 0;
                $user->save();
                return redirect()->route('stripe.payment');
            }

            switch ($user->user_status) {
                case 1: // Super Admin
                    return redirect()->route('superadmin.dashboard');
                case 2: // Admin
                    return redirect()->route('admin.dashboard');
                case 3: // Regular User
                    return redirect()->route('user.dashboard');
            }
        }

        return view('authentication.login');
    }

    public function basicPlan()
    {
        $homepage = HomePage::all();
         $stripe = StripeConfigurations::first();
        return view('authentication.basic-plan', compact('homepage','stripe'));
    }

     public function standardPlan()
    {
        $homepage = HomePage::all();
         $stripe = StripeConfigurations::first();
        return view('authentication.standard-plan', compact('homepage','stripe'));
    }

      public function premiumPlan()
    {
        $homepage = HomePage::all();
         $stripe = StripeConfigurations::first();
        return view('authentication.premium-plan', compact('homepage','stripe'));
    }


    public function showResetForm()
    {
         $email = session('email');
        return view('authentication.reset-password', compact('email'));
    }

    public function forgotPassword()
    {
        return view('authentication.forgot-password');
    }


     public function showVerifyCodeForm()
    {
        return view('authentication.verify-code');
    }


   public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ], [
            'email.exists' => 'This email is not registered.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 6 characters.'
        ]);


        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('status', 'Password reset successful. You can now log in.');
    }


     public function sendResetLink (Request $request)
    {
       $request->validate([
        'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'This email is not registered in our system.'
        ]);


        $code = rand(100000, 999999); // 6-digit code

       $code = rand(100000, 999999);
        session([
            'reset_email' => $request->email,
            'reset_code' => $code,
            'reset_code_expiry' => now()->addMinutes(10)
        ]);

        // Send email (create a Mailable class)
       Mail::to($request->email)->send(new PasswordResetCode($code, $request->email));


        return redirect()->route('verify.code.form')->with([
            'email' => $request->email,
            'success' => 'We’ve sent a verification code to your email.'
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $email = session('reset_email');
        $storedCode = session('reset_code');
        $expiry = session('reset_code_expiry');

        if (!$email || !$storedCode || !$expiry || now()->greaterThan($expiry)) {
            return back()->with('error', 'The code is invalid or expired.');
        }

        if ($request->code != $storedCode) {
            return back()->with('error', 'Incorrect verification code.');
        }

        // Clear session values if desired
        session()->forget(['reset_code', 'reset_code_expiry']);

        return redirect()->route('password.reset')->with('email', $email);
    }




    public function authenticate(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);


        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            Auth::login($user);
            if ($user->is_verified == 0 || $user->is_verified == null) {
                // Redirect the user if they are not verified
                return redirect()->route('send-otp', ['email' => $request->email]);
            }

            if($user->status == 0 || $user->status == null){
              return redirect()->route('stripe.payment');
            }

            if ($user->expiration_date < now() && $user->expiration_date != null) {
                $user->status = 0;
                $user->save();
                return redirect()->route('stripe.payment');
            }

            switch ($user->user_status) {
                case 1: // Super Admin
                    return redirect()->route('superadmin.dashboard');
                case 2: // Admin
                    return redirect()->route('admin.dashboard');
                case 3: // User
                    return redirect()->route('user.dashboard');
            }
        }

        // If authentication fails, redirect back with an error message
       return back()->withErrors([
            'auth' => 'The email or password you entered is incorrect.',
        ]);

    }


    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('login');
    }

   public function register(Request $request)
    {
        $validatedData = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pass' => 'required|string|min:6',
            'cpass' => 'required|string|same:pass',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'email.unique' => 'This email is already taken. Please choose another.',

            'pass.required' => 'Password is required.',
            'pass.string' => 'Password must be a valid string.',
            'pass.min' => 'Password must be at least 6 characters.',

            'cpass.required' => 'Confirm Password is required.',
            'cpass.same' => 'Password and Confirm Password did not match.',
        ]);

        $existingUser = User::where('email', $validatedData['email'])->first();

        if ($existingUser) {
            // ✅ Check if they have Guest role
            $hasGuestRole = $existingUser->roles->contains('id', 3);
            $hasUserRole  = $existingUser->roles->contains('id', 2);

            if ($hasGuestRole && ! $hasUserRole) {
                // 🔁 Promote Guest to User
                $existingUser->update([
                    'first_name'   => $validatedData['fname'],
                    'last_name'    => $validatedData['lname'],
                    'password'     => Hash::make($validatedData['pass']),
                    'user_status'  => 2,
                    'is_verified'  => 1,
                    'status'       => 0, // Set status to active
                ]);

                // 🔒 Deactivate Guest role (role_id = 3)
                DB::table('role_user')
                    ->where('user_id', $existingUser->id)
                    ->where('role_id', 3)
                    ->update(['is_active' => false]);

                // ✅ Attach User role (role_id = 2) and activate it
                $existingUser->roles()->attach(2, ['is_active' => true]);

                Mail::to($existingUser['email'])->send(new UserAddedMail($existingUser, $validatedData['pass']));

                return redirect()->route('login')->with('register_success', 'Welcome back! You’ve been upgraded to a full user.');
            }


            return redirect()->back()->withErrors(['email' => 'This email is already registered.']);
        }

        // ✅ New user registration
        $user = User::create([
            'first_name' => $validatedData['fname'],
            'last_name'  => $validatedData['lname'],
            'email'      => $validatedData['email'],
            'password'   => Hash::make($validatedData['pass']),
            'user_status'=> 2,
        ]);

        $user->roles()->attach(2, ['is_active' => true]);

        Mail::to($user['email'])->send(new UserAddedMail($user, $validatedData['pass']));

        return redirect()->route('login')->with('register_success', 'Registration successful! Please log in.');
    }


    public function sendotp(Request $request)
    {

        $otp = rand(100000, 999999);


        $email = $request->input('email');

        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
            $message->from('info@doc-me.com.au', 'Doc-Me')
                    ->to($email)
                    ->subject('Your OTP Code');
        });

        // Optionally store the OTP in the session or database
        Session::put('otp', $otp);
        // Return the verification view
        return redirect()->route('show-otp',['email' => $email]);
    }

    public function showotp(Request $request)
    {
        $email = $request->input('email');
        return view('authentication.verify-otp', compact('email'));
    }

    public function verifyotp(Request $request)
    {

        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);


        $storedOtp = Session::get('otp');
        $otpExpiry = Session::get('otp_expiry');
        // Check if OTP is expired
        if (now()->greaterThan($otpExpiry)) {
            return back()->withErrors(['otp' => 'The OTP has expired. Please request a new one.']);
        }

        if ($storedOtp == $request->input('otp')) {
            $user = User::where('email', $request->input('email'))->first();
            $user->is_verified = 1;
            $user->email_verified_at = now();
            $user->save();
            if($user->status == 0 || $user->status == null){
                return redirect()->route('stripe.payment');
              }
              if ($user->expiration_date !== null && $user->expiration_date < now()) {
                $user->status = 0;
                $user->save();
                return redirect()->route('stripe.payment');
            }

            Auth::login($user);
              switch ($user->user_status) {
                  case 1: // Super Admin
                      return redirect()->route('superadmin.dashboard');
                  case 2: // Admin
                      return redirect()->route('admin.dashboard');
                  case 3: // User
                      return redirect()->route('user.dashboard');
              }
        } else {
            // OTP is incorrect
            return back()->withErrors(['otp' => 'The OTP you entered is incorrect.']);
        }
    }

    public function proceedToPayment(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $price_day = floatval(str_replace('$', '', HomePage::where('name', 'price_day')->first()->content ?? 9.00));
        $price_month = floatval(str_replace('$', '', HomePage::where('name', 'price_month')->first()->content ?? 49.00));
        $price_year = floatval(str_replace('$', '', HomePage::where('name', 'price_year')->first()->content ?? 490.00));
        // Define all plans
        $plans = [
            'daily' => [
                'price' => $price_day,
                'product_id' => 'prod_SONbps4XZhwTQ6',
                'storage_limit' => 1099511627776, // 200 GB
                'share_limit' => null, // unlimited
                'expires_in' => now()->addDay(),
            ],
            'monthly' => [
                'price' => $price_month,
                'product_id' => 'prod_SONdB9LPPdGuMt',
                'storage_limit' => 214748364800,
                'share_limit' => 30, // limited to 30 guest shares
                'expires_in' => now()->addMonth(),
            ],
            'yearly' => [
                'price' => $price_year,
                'product_id' => 'prod_SONdM4gqkkDE1W',
                'storage_limit' => 1099511627776, // 1 TB in bytes
                'share_limit' => null, // unlimited guest shares
                'expires_in' => now()->addMonth(),
            ],
        ];


        // Determine selected plan
        $selectedPlan = null;
       foreach ($plans as $planName => $plan) {
            if (abs(floatval($request->amount) / 100 - $plan['price']) < 0.01) {
                $selectedPlan = $plan;
                $selectedPlan['name'] = $planName;
                break;
            }
        }


        if (!$selectedPlan) {
            return response()->json(['error' => 'Invalid payment amount.'], 400);
        }

        $stripe = StripeConfigurations::latest()->first();
        if (!$stripe || !$stripe->stripe_secret) {
            return response()->json(['error' => 'Stripe configuration is missing.'], 500);
        }

        try {
            Stripe::setApiKey($stripe->stripe_secret);
            $amountInCents = intval($selectedPlan['price'] * 100);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'metadata' => [
                    'product_id' => $selectedPlan['product_id'],
                    'plan' => $selectedPlan['name'],
                    'user_id' => $user->id,
                ],
            ]);

            $payment = Payment::create([
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $paymentIntent->amount / 100,
                'currency' => $paymentIntent->currency,
                'status' => $paymentIntent->status,
                'product_id' => $selectedPlan['product_id'],
                'user_id' => $user->id,
            ]);

            // ⏱ Update User Plan (or create new)
            UserPlan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_name'     => $selectedPlan['name'],
                    'product_id'    => $selectedPlan['product_id'],
                    'storage_limit' => $selectedPlan['storage_limit'],
                    'share_limit'   => $selectedPlan['share_limit'],
                    'expires_at'    => $selectedPlan['expires_in'],
                ]
            );

            $this->updateUserStatusAndExpiration($payment, $user); // optional business logic

            return redirect()->route('stripe.success')->with([
                'payment' => $payment,
                'user' => $user
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            \Log::error('General Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function showStripePaymentPage()
    {
        $stripe = StripeConfigurations::first();
        $homepage = HomePage::all();
        return view('superadmin.pages.stripe.stripe-payment',compact('stripe','homepage'));
    }

    public function landing()
    {
        $homepage = HomePage::all();
        return view('landing', compact('homepage'));
    }

    public function createPaymentIntent(Request $request)
    {
        $stripe = StripeConfigurations::latest()->first();
        if (!$stripe || !$stripe->stripe_secret) {
            return response()->json(['error' => 'Stripe configuration is missing.'], 500);
        }
        Stripe::setApiKey($stripe->stripe_secret);

        $amount = (int) $request->input('amount'); // Amount in cents
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
        ]);

        return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    private function updateUserStatusAndExpiration($payment, $user)
    {
        $price_day = floatval(str_replace('$', '', HomePage::where('name', 'price_day')->first()->content ?? 9.00));
        $price_month = floatval(str_replace('$', '', HomePage::where('name', 'price_month')->first()->content ?? 49.00));
        $price_year = floatval(str_replace('$', '', HomePage::where('name', 'price_year')->first()->content ?? 490.00));

        if ($payment->amount == $price_day) {
            $user->status = 1;
            $user->expiration_date = now()->addDays(1);
        } elseif ($payment->amount == $price_month) {
            $user->status = 1;
            $user->expiration_date = now()->addDays(30);
        } elseif ($payment->amount == $price_year) {
            $user->status = 1;
            $user->expiration_date = now()->addDays(30);
        }

        $user->save();
    }


    private function generateInvoicePdf($payment, $user)
    {
        $fileName = "{$user->last_name}_" . date('m-d-Y') . '.pdf';
        $directory = public_path('receipts');
        $filePath = "{$directory}/{$fileName}";

        // Ensure the directory exists before saving the PDF
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Generate and store the PDF
        $pdf = Pdf::loadView('payments.invoice', compact('payment', 'user'));

        // Save the PDF (update if it already exists)
        $pdf->save($filePath);

        return [
            'fileName' => $fileName,
            'filePath' => $filePath, // Relative path for access
        ];
    }


     public function stripeSuccess2(Request $request)
    {
        $payment = $request->session()->get('payment');
        $user = $request->session()->get('user');
        $invoiceDetails = $this->generateInvoicePdf($payment,$user);
        $fileName = $invoiceDetails['fileName'];
        $filePath = $invoiceDetails['filePath'];

        $invoice = Invoice::create([
            'user_id' => $user->id,
            'invoice_id' => $payment->id,
            'invoice_file' => $fileName,
            'invoice_date' => now(),
            'due_date' => $user->expiration_date,
            'amount' => $payment->amount,
            'invoice_path' => $filePath,
        ]);
        return response()->json([
            'success' => true,
            'payment' => $payment,
            'user' => $user,
            'invoice' => $invoice,
            'invoice_file' => $fileName,
            'invoice_path' => $filePath,
            'clientSecret'=>$user->client_secret,
            'redirect_url' => route('stripe.receipt2'),
        ]);
    }

    public function stripeSuccess(Request $request)
    {
        $payment = $request->session()->get('payment');
        $user = $request->session()->get('user');
        $invoiceDetails = $this->generateInvoicePdf($payment,$user);
        $fileName = $invoiceDetails['fileName'];
        $filePath = $invoiceDetails['filePath'];

        $invoice = Invoice::create([
            'user_id' => $user->id,
            'invoice_id' => $payment->id,
            'invoice_file' => $fileName,
            'invoice_date' => now(),
            'due_date' => $user->expiration_date,
            'amount' => $payment->amount,
            'invoice_path' => $filePath,
        ]);
        return response()->json([
            'success' => true,
            'payment' => $payment,
            'user' => $user,
            'invoice' => $invoice,
            'invoice_file' => $fileName,
            'invoice_path' => $filePath,
            'clientSecret'=>$user->client_secret,
            'redirect_url' => route('stripe.receipt'),
        ]);
    }

    public function stripeReceipt()
    {
        $user = Auth::user();
        $payment = Payment::where('user_id', $user->id)->latest()->first();
        return view('payments.receipt', compact('user', 'payment'));
    }

      public function stripeReceipt2()
        {
            $user = Auth::user();
            $payment = Payment::where('user_id', $user->id)->latest()->first();
            return view('payments.receipt2', compact('user', 'payment'));
        }

    public function stripeLogin(Request $request)
    {
        Auth::logout(); // Log the user out
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('login');
    }

     public function stripeLogin2(Request $request)
    {   $user = Auth::user();
        $user->is_verified = 0;
        $user->save();


        Auth::logout(); // Log the user out
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('login');
    }

    public function stripeDashboard()
    {
        $user = Auth::user();
        switch ($user->user_status) {
            case 1: // Super Admin
                return redirect()->route('superadmin.dashboard');
            case 2: // Admin
                return redirect()->route('admin.dashboard');
            case 3: // User
                return redirect()->route('user.dashboard');
        }
    }



   public function registerAndPay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:100',
            'lname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'pass' => 'required|string|min:6',
            'cpass' => 'required|same:pass',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0.01', // coming from frontend
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            // Stripe config
            $stripe = StripeConfigurations::latest()->first();
            if (!$stripe || !$stripe->stripe_secret) {
                return response()->json(['status' => 'error', 'message' => 'Stripe configuration is missing.']);
            }

            Stripe::setApiKey($stripe->stripe_secret);

            // Define plan pricing
            $price_day = floatval(str_replace('$', '', Homepage::where('name', 'price_day')->first()->content ?? 9.00));
            $price_month = floatval(str_replace('$', '', Homepage::where('name', 'price_month')->first()->content ?? 49.00));
            $price_year = floatval(str_replace('$', '', Homepage::where('name', 'price_year')->first()->content ?? 490.00));

            $plans = [
                'daily' => [
                    'price' => $price_day,
                    'product_id' => 'prod_SONbps4XZhwTQ6',
                    'storage_limit' => 1099511627776,
                    'share_limit' => null,
                    'expires_in' => now()->addDay(),
                ],
                'monthly' => [
                    'price' => $price_month,
                    'product_id' => 'prod_SONdB9LPPdGuMt',
                    'storage_limit' => 214748364800,
                    'share_limit' => 30,
                    'expires_in' => now()->addMonth(),
                ],
                'yearly' => [
                    'price' => $price_year,
                    'product_id' => 'prod_SONdM4gqkkDE1W',
                    'storage_limit' => 1099511627776,
                    'share_limit' => null,
                    'expires_in' => now()->addMonth(),
                ],
            ];

            // Determine selected plan
            $selectedPlan = null;
            foreach ($plans as $planName => $plan) {
                if (abs(floatval($request->amount) - $plan['price']) < 0.01) {
                    $selectedPlan = $plan;
                    $selectedPlan['name'] = $planName;
                    break;
                }
            }

            if (!$selectedPlan) {
                return response()->json(['status' => 'error', 'message' => 'Invalid payment amount.']);
            }

            // Create Stripe customer
            $customer = Customer::create([
                'name'  => $request->fname . ' ' . $request->lname,
                'email' => $request->email,
                'payment_method' => $request->payment_method,
                'invoice_settings' => ['default_payment_method' => $request->payment_method],
            ]);

            // Create payment intent
            $amountInCents = intval($selectedPlan['price'] * 100);
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'customer' => $customer->id,
                'payment_method' => $request->payment_method,
                'off_session' => true,
                'confirm' => true,
                'metadata' => [
                    'product_id' => $selectedPlan['product_id'],
                    'plan' => $selectedPlan['name'],
                ],
            ]);

            // Create user
            $user = User::create([
                'first_name' => $request->fname,
                 'last_name' => $request->lname,
                'email' => $request->email,
                'password' => Hash::make($request->pass),
                 'user_status'=> 2,
                 'is_verified' => 1,
            ]);
              $user->roles()->attach(2, ['is_active' => true]);
              Auth::login($user);
            Mail::to($user['email'])->send(new UserAddedMail($user, $request->pass));
            $payment = Payment::create([
                'payment_intent_id' => $paymentIntent->id,
                'client_secret'     => $paymentIntent->client_secret,
                'amount'            => $paymentIntent->amount / 100,
                'currency'          => $paymentIntent->currency,
                'status'            => $paymentIntent->status,
                'product_id'        => $selectedPlan['product_id'],
                'user_id'           => $user->id,
            ]);

            // Assign plan
            UserPlan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_name'     => $selectedPlan['name'],
                    'product_id'    => $selectedPlan['product_id'],
                    'storage_limit' => $selectedPlan['storage_limit'],
                    'share_limit'   => $selectedPlan['share_limit'],
                    'expires_at'    => $selectedPlan['expires_in'],
                ]
            );

            // Optional: status logic
            $this->updateUserStatusAndExpiration($payment, $user);
        session([
            'payment' => $payment,
            'user' => $user
        ]);

         return redirect()->route('stripe.success2')->with([
                'payment' => $payment,
                'user' => $user
            ]);


        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            \Log::error('General error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


   public function sucessPayment($CHECKOUT_SESSION_ID)
    {
        $password = "password123";
		  $stripe_key = StripeConfigurations::first();
		  $stripe = new \Stripe\StripeClient($stripe_key->stripe_secret);

		//CHECKOUT SESSION
		$session = $stripe->checkout->sessions->retrieve($CHECKOUT_SESSION_ID);
		$session_name = $session->customer_details->name;
		$session_phone = $session->customer_details->phone;
		$session_email = $session->customer_details->email;
		$session_city = $session->customer_details->address->city;
		$session_country = $session->customer_details->address->country;
		$session_line1 = $session->customer_details->address->line1;
		$session_postal_code = $session->customer_details->address->postal_code;
		$session_state = $session->customer_details->address->state;

		$session_subscription = $session->subscription;
		$session_customer = $session->customer;
		$session_payment_status = $session->payment_status;
		$session_amount_total = $session->amount_total;

		//SUBSCRIPTION
		$subscription = $stripe->subscriptions->retrieve($session_subscription);
		$subscription_object = $subscription->object;
		$subscription_product = $subscription->items->data[0]->price->product;
		$subscription_price = $subscription->items->data[0]->price->id;
		$subscription_latest_invoice = $subscription->latest_invoice;

		//PRODUCT
		$product = $stripe->products->retrieve($subscription_product);
		$product_name = $product->name;

		 echo $session_name.'<br>';
		echo $session_phone.'<br>';
		echo $session_email.'<br>';
		echo $subscription_product .'<br>';
		echo $subscription_price .'<br>';
		echo $session_country .'<br>';
		echo $session_subscription .'<br>';
		echo $product_name .'<br>';

		$user = User::where('email',$session_email)->first();
		 dd($user);
        $nameParts = explode(' ', $session_name);
        $first_name = array_shift($nameParts);
        $last_name = implode(' ', $nameParts);

    if ($product_name === 'Basic Plan') {
        $storage_limit = 1099511627776;
        $share_limit = null;
        $expires_in = now()->addDay();
    } elseif ($product_name === 'Standard Plan') {
        $storage_limit = 214748364800;
        $share_limit = 30;
        $expires_in = now()->addMonth();
    } elseif ($product_name === 'Premium Plan') {
        $storage_limit = 1099511627776;
        $share_limit = null;
        $expires_in = now()->addMonth();
    }


    if (!$user) {
        $user = User::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'user_status' => 2,
            'is_verified' => 0,
            'status' => 1,
            'expiration_date' => $expires_in,
            'session_id' => $CHECKOUT_SESSION_ID,
        ]);

        $user->roles()->attach(2, ['is_active' => true]);
         Mail::to($user['email'])->send(new UserAddedMail($user, $password));
    }

    if ($user->user_status == 3) {

          DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->where('role_id', 3)
                    ->update(['is_active' => false]);

        $user->roles()->attach(2, ['is_active' => true]);
        $user->status = 1;
        $user->expiration_date = $expires_in;
        $user->session_id = $CHECKOUT_SESSION_ID;
        $user->save();
    }
    else
    {
        $user->status = 1;
        $user->expiration_date = $expires_in;
        $user->session_id = $CHECKOUT_SESSION_ID;
        $user->save();
    }


     UserPlan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_name'     => $product_name,
                    'product_id'    => $subscription_product,
                    'storage_limit' => $storage_limit,
                    'share_limit'   => $share_limit,
                    'expires_at'    => $expires_in,
                ]
            );


    return view('thank_you');
    }
}


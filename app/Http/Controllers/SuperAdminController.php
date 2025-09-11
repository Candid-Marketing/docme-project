<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StripeConfigurations;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\HomePage;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserAddedMail;
use App\Models\Invoice;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Invoice as StripeInvoice;

class SuperAdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $registrations = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->toArray();
        // Pass both $users and $registrations to the view
        return view('superadmin.pages.index', compact('users', 'registrations'));
    }

    public function finance()
    {
        $stripe = StripeConfigurations::latest()->first();
        return view('superadmin.pages.stripe.index', compact('stripe'));
    }
    public function email()
    {
        return view('superadmin.pages.email.index');
    }
    public function homepage()
    {
        $homepage = HomePage::all();
        return view('superadmin.pages.homepage.index',compact('homepage'));
    }

    public function user()
    {
        $users = User::where('user_status','!=','3')->paginate(10);
        return view('superadmin.pages.user.index', compact('users'));
    }


    public function stripeupdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stripe_key' => 'required',
            'stripe_secret' => 'required',
        ]);

        $stripeConfig = StripeConfigurations::findOrFail($request->id);
        $stripeConfig->name = $request->name;
        $stripeConfig->stripe_key = $request->stripe_key;
        $stripeConfig->stripe_secret = $request->stripe_secret;
        $stripeConfig->save();

        return redirect()->back()->with('success', 'Stripe configuration updated successfully!');
    }
    public function homestore(Request $request)
    {
        $section = $request->input('section');
        // Initialize empty variables for image paths

        if ($section === 'home') {

        $faviconUrl = null;
        $naviconUrl = null;
        $homeimageUrl = null;
        $data = [];
        // Handle favicon image upload
        if ($request->hasFile('favicon_image')) {
            $favicon = $request->file('favicon_image');
            $originalName = $favicon->getClientOriginalName();
            $destinationPath = public_path('images');
            $favicon->move($destinationPath, $originalName);
            $faviconUrl = 'images/' . $originalName;
            $data[] = [
                'name' => 'favicon_image',
                'image_path' => $faviconUrl,
                'content' => 'favicon',
            ];
        }

        // Handle nav image upload
        if ($request->hasFile('nav_image')) {
            $navimage = $request->file('nav_image');
            $originalName = $navimage->getClientOriginalName();
            $destinationPath = public_path('images');
            $navimage->move($destinationPath, $originalName);
            $naviconUrl = 'images/' . $originalName;
            $data[] =  [
                'name' => 'nav_image',
                'image_path' => $naviconUrl,
                'content' => 'nav_image',
            ];
        }

        // Handle home image upload
        if ($request->hasFile('home_image')) {
            $homeimage = $request->file('home_image');
            $originalName = $homeimage->getClientOriginalName();
            $destinationPath = public_path('images');
            $homeimage->move($destinationPath, $originalName);
            $homeimageUrl = 'images/' . $originalName;
            $data[] = [
                'name' => 'home_image',
                'image_path' => $homeimageUrl,
                'content' => 'home_image',
            ];
        }

        for ($i = 1; $i <= 4; $i++) {
            $imageKey = 'icon_image' . $i;
            $titleKey = 'icon_title' . $i;
            $descKey  = 'icon_desc' . $i;

            // Save image if uploaded
            if ($request->hasFile($imageKey)) {
                $file = $request->file($imageKey);
                $originalName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('images');
                $file->move($destinationPath, $originalName);
                $filePath = 'images/' . $originalName;

                $data[] = [
                    'name' => $imageKey,
                    'image_path' => $filePath,
                    'content' => $imageKey,
                ];
            }

            if ($request->filled($titleKey)) {
                $data[] = [
                    'name' => $titleKey,
                    'image_path' => null,
                    'content' => $request->input($titleKey),
                ];
            }

            // Handle icon description
            if ($request->filled($descKey)) {
                $data[] = [
                    'name' => $descKey,
                    'image_path' => null,
                    'content' => $request->input($descKey),
                ];
            }

        }

        // Add favicon data if it exists
        $data = array_merge($data, [
            [
                'name' => 'home_title',
                'image_path' => null,
                'content' => $request->home_title,
            ],
            [
                'name' => 'home_subtitle',
                'image_path' => null,
                'content' => $request->home_description,
            ],
        ]);

        if (!empty($data)) {
            Homepage::upsert(
                $data,
                ['name'], // Ensure unique constraint by name
                ['image_path', 'content']
            );
        }
        return redirect()->back()->with('success', 'Homepage updated successfully!');
     }
     else if($section =="about_us")
     {
        $aboutusUrl = null;
        $aboutusUrl1 = null;
        $aboutusUrl2 = null;
        $data = [];

        if ($request->hasFile('about_us_image')) {
            $aboutus = $request->file('about_us_image');
            $originalName = $aboutus->getClientOriginalName();
            $destinationPath = public_path('images');
            $aboutus->move($destinationPath, $originalName);
            $aboutusUrl = 'images/' . $originalName;
            $data[] =  [
                'name' => 'about_us_image',
                'image_path' => $aboutusUrl,
                'content' => 'about_us_image',
            ];
        }

        // Handle nav image upload
        if ($request->hasFile('about_us_image1')) {
            $aboutus1 = $request->file('about_us_image1');
            $originalName = $aboutus1->getClientOriginalName();
            $destinationPath = public_path('images');
            $aboutus1->move($destinationPath, $originalName);
            $aboutusUrl1 = 'images/' . $originalName;
            $data[] = [
                'name' => 'about_us_image1',
                'image_path' => $aboutusUrl1,
                'content' => 'about_us_image1',
            ];
        }

        // Handle home image upload
        if ($request->hasFile('about_us_image2')) {
            $aboutus2 = $request->file('about_us_image2');
            $originalName = $aboutus2->getClientOriginalName();
            $destinationPath = public_path('images');
            $aboutus2->move($destinationPath, $originalName);
            $aboutusUrl2 = 'images/' . $originalName;
            $data[] = [
                'name' => 'about_us_image2',
                'image_path' => $aboutusUrl2,
                'content' => 'about_us_image2',
            ];
        }

        for ($i = 1; $i <= 6; $i++) {
            $key = "about_us_list{$i}";

            if ($request->filled($key)) {
                $data[] = [
                    'name' => $key,
                    'image_path' => null,
                    'content' => $request->input($key),
                ];
            }
        }


        $data = array_merge($data, [
            [
                'name' => 'about_us_title',
                'image_path' => null,
                'content' => $request->about_us_title,
            ],
            [
                'name' => 'about_us_description',
                'image_path' => null,
                'content' => $request->about_us_description,
            ],
            [
                'name' => 'founder_name',
                'image_path' => null,
                'content' => $request->founder_name,
            ],
            [
                'name' => 'founder_role',
                'image_path' => null,
                'content' => $request->founder_role,
            ],
            [
                'name' => 'mobile_num',
                'image_path' => null,
                'content' => $request->mobile_num,
            ],


        ]);
        if (!empty($data)) {
            Homepage::upsert(
                $data,
                ['name'], // Ensure unique constraint by name
                ['image_path', 'content']
            );
        }
        return redirect()->back()->with('success', 'About Us updated successfully!');
     }
     else if ($section =="feature")
     {
        $data = [];
        $navbarUrl1 = null;
        $navbarUrl2 = null;
        $navbarUrl3 = null;
        $mockUrl = null;

        if ($request->hasFile('mock_image')) {
            $homeimage = $request->file('mock_image');
            $originalName = $homeimage->getClientOriginalName();
            $destinationPath = public_path('images');
            $homeimage->move($destinationPath, $originalName);
            $mockUrl = 'images/' . $originalName;
            $data[] = [
                'name' => 'mock_image',
                'image_path' => $mockUrl,
                'content' => 'mock_image',
            ];
        }

        if ($request->hasFile('nav_image1')) {
            $nav_image1 = $request->file('nav_image1');
            $originalName = $nav_image1->getClientOriginalName();
            $destinationPath = public_path('images');
            $nav_image1->move($destinationPath, $originalName);
            $navbarUrl1 = 'images/' . $originalName;
            $data[] = [
                'name' => 'nav_image1',
                'image_path' => $navbarUrl1,
                'content' => 'nav_image1',
            ];

        }

        // Handle nav image upload
        if ($request->hasFile('nav_image2')) {
            $nav_image2 = $request->file('nav_image2');
            $originalName = $nav_image2->getClientOriginalName();
            $destinationPath = public_path('images');
            $nav_image2->move($destinationPath, $originalName);
            $navbarUrl2 = 'images/' . $originalName;
            $data[] = [
                'name' => 'nav_image2',
                'image_path' => $navbarUrl2,
                'content' => 'nav_image2',
            ];
        }

        // Handle home image upload
        if ($request->hasFile('nav_image3')) {
            $nav_image3 = $request->file('nav_image3');
            $originalName = $nav_image3->getClientOriginalName();
            $destinationPath = public_path('images');
            $nav_image3->move($destinationPath, $originalName);
            $navbarUrl3 = 'images/' . $originalName;
            $data[] =  [
                'name' => 'nav_image3',
                'image_path' => $navbarUrl3,
                'content' => 'nav_image3',
            ];
        }


        for ($i = 1; $i <= 3; $i++) {
            $key = "feat_nav{$i}";
            $key1 = "nav_title{$i}";
            $key2 = "nav_desc{$i}";

            if ($request->filled($key)) {
                $data[] = [
                    'name' => $key,
                    'image_path' => null,
                    'content' => $request->input($key),
                ];
            }

            if ($request->filled($key1)) {
                $data[] = [
                    'name' => $key1,
                    'image_path' => null,
                    'content' => $request->input($key1),
                ];
            }

            if ($request->filled($key2)) {
                $data[] = [
                    'name' => $key2,
                    'image_path' => null,
                    'content' => $request->input($key2),
                ];
            }

        }

        for ($i = 1; $i <= 9; $i++) {
            $key = "nav_check{$i}";

            if ($request->filled($key)) {
                $data[] = [
                    'name' => $key,
                    'image_path' => null,
                    'content' => $request->input($key),
                ];
            }
        }


        for ($i = 1; $i <= 4; $i++) {
            $imageKey = 'card_image' . $i;
            $titleKey = 'card_title' . $i;
            $descKey  = 'card_desc' . $i;

            // Save image if uploaded
            if ($request->hasFile($imageKey)) {
                $file = $request->file($imageKey);
                $originalName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('images');
                $file->move($destinationPath, $originalName);
                $filePath = 'images/' . $originalName;

                $data[] = [
                    'name' => $imageKey,
                    'image_path' => $filePath,
                    'content' => $imageKey,
                ];
            }

            if ($request->filled($titleKey)) {
                $data[] = [
                    'name' => $titleKey,
                    'image_path' => null,
                    'content' => $request->input($titleKey),
                ];
            }

            // Handle icon description
            if ($request->filled($descKey)) {
                $data[] = [
                    'name' => $descKey,
                    'image_path' => null,
                    'content' => $request->input($descKey),
                ];
            }

        }


        for ($i = 1; $i <= 6; $i++) {
            $imageKey = 'footer_image' . $i;
            $titleKey = 'footer_title' . $i;
            $descKey  = 'footer_desc' . $i;


            if ($request->hasFile($imageKey)) {
                $file = $request->file($imageKey);
                $originalName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('images');
                $file->move($destinationPath, $originalName);
                $filePath = 'images/' . $originalName;

                $data[] = [
                    'name' => $imageKey,
                    'image_path' => $filePath,
                    'content' => $imageKey,
                ];
            }

            if ($request->filled($titleKey)) {
                $data[] = [
                    'name' => $titleKey,
                    'image_path' => null,
                    'content' => $request->input($titleKey),
                ];
            }

            // Handle icon description
            if ($request->filled($descKey)) {
                $data[] = [
                    'name' => $descKey,
                    'image_path' => null,
                    'content' => $request->input($descKey),
                ];
            }

        }


        for ($i = 1; $i <= 6; $i++) {
            $imageKey = 'test_image' . $i;
            $testName = 'test_name' . $i;
            $testRole  = 'test_role' . $i;
            $testState  = 'test_state' . $i;

            if ($request->hasFile($imageKey)) {
                $file = $request->file($imageKey);
                $originalName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('images');
                $file->move($destinationPath, $originalName);
                $filePath = 'images/' . $originalName;

                $data[] = [
                    'name' => $imageKey,
                    'image_path' => $filePath,
                    'content' => $imageKey,
                ];
            }

            if ($request->filled($testName)) {
                $data[] = [
                    'name' => $testName,
                    'image_path' => null,
                    'content' => $request->input($testName),
                ];
            }

            // Handle icon description
            if ($request->filled($testRole)) {
                $data[] = [
                    'name' => $testRole,
                    'image_path' => null,
                    'content' => $request->input($testRole),
                ];
            }

            if ($request->filled($testState)) {
                $data[] = [
                    'name' => $testState,
                    'image_path' => null,
                    'content' => $request->input($testState),
                ];
            }
        }


        for ($i = 1; $i <= 6; $i++) {
            $imageKey = 'client_image' . $i;
            if ($request->hasFile($imageKey)) {
                $file = $request->file($imageKey);
                $originalName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('images');
                $file->move($destinationPath, $originalName);
                $filePath = 'images/' . $originalName;

                $data[] = [
                    'name' => $imageKey,
                    'image_path' => $filePath,
                    'content' => $imageKey,
                ];
            }

        }


        $data = array_merge($data, [
            [
                'name' => 'feature_title',
                'image_path' => null,
                'content' => $request->feature_title,
            ],
            [
                'name' => 'feature_description',
                'image_path' => null,
                'content' => $request->feature_description,
            ],
            [
                'name' => 'call_title',
                'image_path' => null,
                'content' => $request->call_title,
            ],
            [
                'name' => 'call_description',
                'image_path' => null,
                'content' => $request->call_description,
            ],
            [
                'name' => 'test_head',
                'image_path' => null,
                'content' => $request->test_head,
            ],
            [
                'name' => 'test_description',
                'image_path' => null,
                'content' => $request->test_description,
            ],
            [
                'name' => 'show_clients',
                'image_path' => null,
                'content' => $request->show_clients,
            ],
            [
                'name' => 'show_testimonials',
                'image_path' => null,
                'content' => $request->show_testimonials,
            ],

        ]);
        if (!empty($data)) {
            Homepage::upsert(
                $data,
                ['name'], // Ensure unique constraint by name
                ['image_path', 'content']
            );
        }
        return redirect()->back()->with('success', 'Feature updated successfully!');
     }
     else if($section == 'service')
     {
        $data = [];

        $data = [
            [
                'name' => 'service_title',
                'image_path' => null,
                'content' => $request->service_title,
            ],
            [
                'name' => 'service_description',
                'image_path' => null,
                'content' => $request->service_description,
            ],
            [
                'name' => 'services_title1',
                'image_path' => null,
                'content' =>  $request->services_title1,
            ],
            [
                'name' => 'services_title2',
                'image_path' => null,
                'content' => $request->services_title2,
            ],
            [
                'name' => 'services_title3',
                'image_path' => null,
                'content' => $request->services_title3,
            ],
            [
                'name' => 'services_title4',
                'image_path' => null,
                'content' => $request->services_title4,
            ],
            [
                'name' => 'services_desc1',
                'image_path' => null,
                'content' => $request->services_desc1,
            ],
            [
                'name' => 'services_desc2',
                'image_path' => null,
                'content' => $request->services_desc2,
            ],
            [
                'name' => 'services_desc3',
                'image_path' => null,
                'content' => $request->services_desc3,
            ],
            [
                'name' => 'services_desc4',
                'image_path' => null,
                'content' => $request->services_desc4,
            ],
        ];
        if (!empty($data)) {
            Homepage::upsert(
                $data,
                ['name'], // Ensure unique constraint by name
                ['image_path', 'content']
            );
        }
        return redirect()->back()->with('success', 'Service updated successfully!');
     }
     else if($section == 'price')
     {

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $key = "include_state{$i}";

            if ($request->filled($key)) {
                $data[] = [
                    'name' => $key,
                    'image_path' => null,
                    'content' => $request->input($key),
                ];
            }
        }

        for ($i = 1; $i <= 6; $i++) {
            $key = "quest_number{$i}";
            $keyan = "answer_number{$i}";
            if ($request->filled($key)) {
                $data[] = [
                    'name' => $key,
                    'image_path' => null,
                    'content' => $request->input($key),
                ];
            }

            if ($request->filled($keyan)) {
                $data[] = [
                    'name' => $keyan,
                    'image_path' => null,
                    'content' => $request->input($keyan),
                ];
            }
        }


        $data = array_merge($data, [
            [
                'name' => 'price_title',
                'image_path' => null,
                'content' => $request->price_title,
            ],
            [
                'name' => 'price_description',
                'image_path' => null,
                'content' => $request->price_description,
            ],
            [
                'name' => 'price_day',
                'image_path' => null,
                'content' =>  $request->price_day,
            ],
            [
                'name' => 'price_month',
                'image_path' => null,
                'content' => $request->price_month,
            ],
            [
                'name' => 'price_year',
                'image_path' => null,
                'content' => $request->price_year,
            ],
            [
                'name' => 'price_desc1',
                'image_path' => null,
                'content' => $request->price_desc1,
            ],
            [
                'name' => 'price_desc2',
                'image_path' => null,
                'content' => $request->price_desc2,
            ],
            [
                'name' => 'price_desc3',
                'image_path' => null,
                'content' => $request->price_desc3,
            ],

            [
                'name' => 'quest_title',
                'image_path' => null,
                'content' => $request->quest_title,
            ],
            [
                'name' => 'quest_description',
                'image_path' => null,
                'content' => $request->quest_description,
            ],
            [
                'name' => 'act_title',
                'image_path' => null,
                'content' => $request->act_title,
            ],
            [
                'name' => 'act_description',
                'image_path' => null,
                'content' => $request->act_description,
            ],


        ]);
        if (!empty($data)) {
            Homepage::upsert(
                $data,
                ['name'], // Ensure unique constraint by name
                ['image_path', 'content']
            );
        }
        return redirect()->back()->with('success', 'Price updated successfully!');
     }
     else if($section == 'contact')
     {
        $data = [];

        $data = [
            [
                'name' => 'contact_title',
                'image_path' => null,
                'content' => $request->contact_title,
            ],
            [
                'name' => 'contact_description',
                'image_path' => null,
                'content' => $request->contact_description,
            ],
            [
                'name' => 'location_ad',
                'image_path' => null,
                'content' =>  $request->location_ad,
            ],
            [
                'name' => 'contact_email',
                'image_path' => null,
                'content' => $request->contact_email,
            ],
            [
                'name' => 'phone_num1',
                'image_path' => null,
                'content' => $request->phone_num1,
            ],
            [
                'name' => 'phone_num2',
                'image_path' => null,
                'content' => $request->phone_num2,
            ],
            [
                'name' => 'touch_desc',
                'image_path' => null,
                'content' => $request->touch_desc,
            ],
            [
                'name' => 'contactinfo_title',
                'image_path' => null,
                'content' => $request->contactinfo_title,
            ],
            [
                'name' => 'contactinfo_description',
                'image_path' => null,
                'content' => $request->contactinfo_description,
            ],
            [
                'name' => 'get_title',
                'image_path' => null,
                'content' => $request->get_title,
            ],
            [
                'name' => 'show_phone',
                'image_path' => null,
                'content' => $request->show_phone,
            ],
        ];
        if (!empty($data)) {
            Homepage::upsert(
                $data,
                ['name'], // Ensure unique constraint by name
                ['image_path', 'content']
            );
        }
        return redirect()->back()->with('success', 'Contact updated successfully!');
     }

     else if($section =="footer")
     {
        $data = [];
        if ($request->hasFile('footer_title')) {
            $footer_image = $request->file('footer_title');
            $originalName = $footer_image->getClientOriginalName();
            $destinationPath = public_path('images');
            $footer_image->move($destinationPath, $originalName);
            $footerUrl = 'images/' . $originalName;
            $data[] = [
                'name' => 'footer_title',
                'image_path' => $footerUrl,
                'content' => 'footer_title',
            ];
        }


        $data = array_merge($data, [
            [
                'name' => 'footer_add',
                'image_path' => null,
                'content' => $request->footer_add,
            ],
            [
                'name' => 'footer_phone',
                'image_path' => null,
                'content' =>  $request->footer_phone,
            ],
            [
                'name' => 'footer_email',
                'image_path' => null,
                'content' => $request->footer_email,
            ],
        ]);
        if (!empty($data)) {
            Homepage::upsert(
                $data,
                ['name'], // Ensure unique constraint by name
                ['image_path', 'content']
            );
        }
        return redirect()->back()->with('success', 'Footer updated successfully!');

     }

    }

    public function invoice()
    {
         $stripe_key = StripeConfigurations::first();

        Stripe::setApiKey($stripe_key->stripe_secret);

        $invoices =  StripeInvoice::all([
            'customer' => $customerId,
            'limit' => 100
        ]);

        $users = User::where('email',Auth::user()->email)->paginate(5);
        return view('superadmin.pages.invoice.index', compact('users'));
    }

    public function information()
    {
        $user = Auth::user();
        return view('superadmin.pages.settings.information', compact('user'));
    }

    public function logindetails()
    {
        $user = Auth::user();
        return view('superadmin.pages.settings.login_details', compact('user'));
    }

    public function photo()
    {
        return view('superadmin.pages.settings.photo');
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

    public function informationupdate(Request $request)
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

    public function file_index()
    {
        return view('superadmin.pages.settings.file_index');
    }

    public function file()
    {
        session()->forget('current_step');
        return view('superadmin.pages.settings.file');
    }

    public function loan()
    {
        return view('superadmin.pages.settings.loan');
    }

    public function addUser(Request $request)
    {
        $password = 'password123';
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'user_status' => 'required|integer',
        ]);

        // ✅ Use `create()` to get the newly added user object
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'user_status' => $validatedData['user_status'],
            'is_verified' => 0, // Default: not verified
            'password' => Hash::make($password), // Hash the password
        ]);

                // Just assign the selected role as active
                $user->roles()->attach($user->user_status, ['is_active' => true]);

        // ✅ Send email notification
        Mail::to($validatedData['email'])->send(new UserAddedMail($user, $password));

        // ✅ Return proper JSON response with all required fields
        return response()->json([
            'message' => 'User added successfully!',
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'role' => $user->user_status == 1 ? 'Admin' : ($user->user_status == 2 ? 'User' : 'Guest'),
                'is_verified' => $user->is_verified,
                'status' => $user->status,
                'invoice' => $user->invoice ? asset('receipts/' . basename($user->invoice->invoice_path)) : null,
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
            $users = User::where('first_name', 'LIKE', "%{$query}%")
                         ->orWhere('last_name', 'LIKE', "%{$query}%")
                         ->orWhere('email', 'LIKE', "%{$query}%")
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

    public function searchInvoice (Request $request)
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
                'invoice_date' =>$user->invoice ? $user->invoice->invoice_date : null,
                'amount' => $user->invoice ? $user->invoice->amount : null,
            ];
        });
        return response()->json(['users' => $formattedUsers]);
    }
}

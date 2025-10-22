<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Folder;
use App\Models\Subfolder;
use Illuminate\Support\Facades\Auth;
use App\Models\InnerFolder;
use App\Models\ChildFolder;
use App\Models\InnerchildFolder;
use App\Models\LastLevelFolder;
use App\Models\MainFolder;
use App\Models\UserStructureFolder;
use App\Models\AdminFolderTemplate;

class LoanController extends Controller
{
    public function nextStep(Request $request)
    {
            $currentStep = session('current_step', 1);
            if ($currentStep == 1) {
                $request->validate([
                    'loan_type' => 'required',
                ]);

                session(['loan_type' => $request->input('loan_type')]);
            }
            if($currentStep == 2) {
                $loan_type = session('loan_type');

                if($loan_type == 'property') {
                    $request->validate([
                        'property_type' => 'required',
                        'property_address' => 'required',
                        'property_usage' => 'required'
                    ]);

                    session(['property_type' => $request->input('property_type')]);
                    session(['property_address' => $request->input('property_address')]);
                    session(['property_usage' => $request->input('property_usage')]);
                } elseif($loan_type == 'car') {
                    $request->validate([
                        'car_address' => 'required'
                    ]);

                    session(['car_address' => $request->input('car_address')]);
                } elseif($loan_type == 'home') {
                    $request->validate([
                        'home_address' => 'required'
                    ]);

                    session(['home_address' => $request->input('home_address')]);
                } elseif($loan_type == 'personal') {
                    $request->validate([
                        'personal_address' => 'required'
                    ]);

                    session(['personal_address' => $request->input('personal_address')]);
                } elseif($loan_type == 'refinance') {
                    $request->validate([
                        'refinance_address' => 'required'
                    ]);

                    session(['refinance_address' => $request->input('refinance_address')]);
                }
            }

            if ($currentStep == 3) {
                $request->validate([
                    'number_of_people' => 'required',
                ]);


                session(['number_of_people' => $request->input('number_of_people')]);
            }

            if ($currentStep == 4) {
                // Store applicant information arrays
                session(['title' => $request->input('title', [])]);
                session(['first_name' => $request->input('first_name', [])]);
                session(['last_name' => $request->input('last_name', [])]);
                session(['other_names' => $request->input('other_names', [])]);
                session(['employment_status' => $request->input('employment_status', [])]);
                session(['other_income_sources' => $request->input('other_income_sources', [])]);
                session(['income_sources' => $request->input('income_sources', [])]);
            }

            if ($currentStep == 5) {
                session(['assets' => $request->input('assets')]);
                session(['liabilities' => $request->input('liabilities')]);
                session(['other_assets_details' => $request->input('other_assets_details')]);
            }

            if ($currentStep == 6) {
                session(['business_ownership' => $request->input('business_ownership')]);
            }
            // Debug: Show all session data
            //dd(session()->all());
            // Final step processing
            if ($currentStep == 6) {
                $number_of_people = session('number_of_people');

                // Create the main parent folder once, outside the loop
                if (session('loan_type') == 'car')
                {
                    $folderLoan = 'Car Loan';
                }
                elseif (session('loan_type') == 'home')
                {
                    $folderLoan = 'Home Loan';
                }
                elseif (session('loan_type') == 'personal')
                {
                    $folderLoan = 'Personal Loan';
                }
                elseif (session('loan_type') == 'property')
                {
                    $folderLoan = 'Property Loan'. '-' . session('property_address');
                }
                elseif (session('loan_type') == 'refinance')
                {
                    $folderLoan = 'Refinance Loan';
                }

                $folderHeader = $folderLoan . ' Application';
                $mainFolder = UserStructureFolder::create([
                    'user_id' => Auth::id(),
                    'folder_name' => $folderHeader,
                    'parent_id' => null,
                    'linked_admin_code' => null,
                ]);

                // Loop through each applicant
                for ($i = 1; $i <= $number_of_people; $i++) {
                    $titleList = session('title', []);
                    $firstNameList = session('first_name', []);
                    $lastNameList = session('last_name', []);
                    $otherNamesList = session('other_names', []);
                    $incomeList = session('income', []);
                    $superAnList = session('super_an', []);
                    $employmentList = session('employment_status', []);
                    $liabilitiesList = session('liabilities', []);
                    $loanTypesList = session('loan_types', []);
                    $trustAccount = session('trust_account',[]);

                    // Get the appropriate address based on loan type
                    $loan_type = session('loan_type');
                    $address = null;
                    $property_type = null;
                    $property_usage = null;

                    if($loan_type == 'property') {
                        $address = session('property_address');
                        $property_type = session('property_type');
                        $property_usage = session('property_usage');
                    } elseif($loan_type == 'car') {
                        $address = session('car_address');
                    } elseif($loan_type == 'home') {
                        $address = session('home_address');
                    } elseif($loan_type == 'personal') {
                        $address = session('personal_address');
                    } elseif($loan_type == 'refinance') {
                        $address = session('refinance_address');
                    }

                    $personData = [
                        'loan_type' => $loan_type,
                        'property_address' => $address,
                        'property_type' => $property_type,
                        'property_usage' => $property_usage,
                        'number_of_people' => $number_of_people,
                        'title' => $titleList[$i - 1] ?? null,
                        'first_name' => $firstNameList[$i - 1] ?? null,
                        'last_name' => $lastNameList[$i - 1] ?? null,
                        'other_names' => $otherNamesList[$i - 1] ?? null,
                        'employment' => session('employment_status')[$i] ?? null,
                        'other_income_sources' => session('other_income_sources')[$i] ?? null,
                        'income_sources' => session('income_sources')[$i] ?? [],
                        'assets' => session('assets')[$i] ?? [],
                        'liabilities' => session('liabilities')[$i] ?? [],
                        'other_assets_details' => session('other_assets_details')[$i] ?? [],
                        'business_ownership' => session('business_ownership')[$i-1] ?? [],
                    ];
                    // Save applicant to database
                    $file = Applicant::create($personData);
                    // Create folder for each applicant under main folder
                    $applicantFolder = UserStructureFolder::create([
                        'user_id' => Auth::id(),
                        'folder_name' => 'Applicant ' . $i . (isset($firstNameList[$i-1]) ? ' ' . $firstNameList[$i-1] : ''),
                        'parent_id' => $mainFolder->id,
                        'linked_admin_code' => null,
                    ]);

                    // Loop over Admin parent folders
                    $adminParents = AdminFolderTemplate::whereNull('parent_code')->get();
                    foreach ($adminParents as $adminParent) {
                        $parentName = strtolower($adminParent->name);

                        $sectionFolder = UserStructureFolder::create([
                            'user_id' => Auth::id(),
                            'folder_name' => $adminParent->name,
                            'parent_id' => $applicantFolder->id,
                            'linked_admin_code' => $adminParent->unique_code,
                        ]);

                        if($parentName == 'general information')
                        {
                            if($file->loan_type == 'personal')
                            {
                                $this->generateSpecificAdminFolder('F2-011', $sectionFolder->id);
                            }
                            elseif($file->loan_type == 'refinance')
                            {
                                $this->generateSpecificAdminFolder('F2-012', $sectionFolder->id);
                            }
                        }

                        if($parentName == 'income') {
                            if($file->employment == 'employed') {
                                $this->generateSpecificAdminFolder('F2-013', $sectionFolder->id);
                                $this->generateSpecificAdminFolder('F2-014', $sectionFolder->id);
                                // Create folders for income sources that are "yes"
                                    $incomeSources = $personData->income_sources ?? [];
                                    $incomeSourceMapping = [
                                        'trust_distributions' => ['code' => 'F3-018', 'name' => 'Trust Distributions'],
                                        'government_income' => ['code' => 'F3-019', 'name' => 'Government Income'],
                                        'spousal_support' => ['code' => 'F3-020', 'name' => 'Spousal Support'],
                                        'gifts' => ['code' => 'F3-021', 'name' => 'Gifts'],
                                        'superannuation_income' => ['code' => 'F3-022', 'name' => 'Superannuation Income'],
                                        'foreign_income' => ['code' => 'F3-023', 'name' => 'Foreign Income'],
                                    ];

                                    foreach ($incomeSources as $source => $value) {
                                        if ($value === 'yes' && isset($incomeSourceMapping[$source])) {
                                            // Create new parent folder directly since it's not in admin template
                                            UserStructureFolder::create([
                                                'user_id' => Auth::id(),
                                                'folder_name' => $incomeSourceMapping[$source]['name'],
                                                'parent_id' => 'F3-018',
                                                'linked_admin_code' => 'F3-018',
                                            ]);
                                        }
                                    }
                            }
                        }
                        // Assets logic
                        if ($parentName === 'assets') {
                            if ($file->property_usage === 'investment') {
                                $this->generateSpecificAdminFolder('F2-003', $sectionFolder->id);
                            } elseif ($file->property_usage === 'construct') {
                                $this->generateSpecificAdminFolder('F2-004', $sectionFolder->id);
                            } elseif ($file->property_usage === 'residence') {
                                $this->generateSpecificAdminFolder('F2-002', $sectionFolder->id);
                            }

                            if (!empty($file->super_an)) {
                                $this->generateSpecificAdminFolder('F2-008', $sectionFolder->id);
                            }

                            if ($file->trust_account === 'yes') {
                                $this->generateSpecificAdminFolder('F2-006', $sectionFolder->id);
                            }

                            if ($file->share_account === 'yes') {
                                $this->generateSpecificAdminFolder('F2-007', $sectionFolder->id);
                            }

                            if (!empty($personData->assets)) {
                                // Create folders for assets that are "on"
                                    $assets = $personData->assets ?? [];
                                    $assetMapping = [
                                        'personal_home' => ['code' => 'F3-024', 'name' => 'Personal Home'],
                                        'investment_properties' => ['code' => 'F3-025', 'name' => 'Investment Properties'],
                                        'shares' => ['code' => 'F3-026', 'name' => 'Shares'],
                                        'bank_accounts' => ['code' => 'F3-027', 'name' => 'Bank Accounts'],
                                        'cryptocurrency' => ['code' => 'F3-028', 'name' => 'Cryptocurrency'],
                                        'other_assets' => ['code' => 'F3-029', 'name' => 'Other Assets'],
                                    ];

                                    foreach ($assets as $asset => $value) {
                                        if ($value === 'on' && isset($assetMapping[$asset])) {
                                            // Create new parent folder directly since it's not in admin template
                                            UserStructureFolder::create([
                                                'user_id' => Auth::id(),
                                                'folder_name' => $assetMapping[$asset]['name'],
                                                'parent_id' => 'F2-009',
                                                'linked_admin_code' => 'F2-009',
                                            ]);
                                        }
                                    }
                            }

                            if (!empty($personData->other_assets_details)) {
                                foreach ($personData->other_assets_details as $liability => $status) {
                                    if ($status === 'on' && in_array($liability, ['motor_vehicles', 'boats', 'jewellery', 'art'])) {
                                        $this->generateSpecificAdminFolder('F2-009', $sectionFolder->id);
                                        break;
                                    }
                                }
                            }
                        }

                        // Liabilities logic
                        elseif ($parentName === 'liabilities') {
                            $loansFolder = UserStructureFolder::create([
                                'user_id' => Auth::id(),
                                'folder_name' => 'Loans',
                                'parent_id' => $sectionFolder->id,
                                'linked_admin_code' => 'F2-015',
                            ]);

                            // Get liabilities for current applicant (index $i)
                            $applicantLiabilities = $personData->liabilities[$i] ?? [];

                            if (!empty($applicantLiabilities)) {
                                // Liabilities that have admin template structures
                                $adminTemplateLiabilities = [
                                    'home_loan' => 'F3-078',
                                    'investment_loans' => 'F3-079',
                                    'personal_loans' => 'F3-080',
                                    'car_loans' => 'F3-081',
                                    'credit_cards' => 'F2-016',
                                ];

                                // Liabilities that need to be created as new parent folders
                                $newParentLiabilities = [
                                    'private_loans' => ['code' => 'F3-082', 'name' => 'Private Loans'],
                                    'others' => ['code' => 'F3-083', 'name' => 'Others'],
                                ];

                                foreach ($applicantLiabilities as $liabilityType => $status) {
                                    if ($status === 'on') {
                                        // Check if it has admin template structure
                                        if (isset($adminTemplateLiabilities[$liabilityType])) {
                                            $this->generateSpecificAdminFolder($adminTemplateLiabilities[$liabilityType], $loansFolder->id);
                                        }
                                        // Check if it needs to be created as new parent folder
                                        elseif (isset($newParentLiabilities[$liabilityType])) {
                                            UserStructureFolder::create([
                                                'user_id' => Auth::id(),
                                                'folder_name' => $newParentLiabilities[$liabilityType]['name'],
                                                'parent_id' => $loansFolder->id, // Parent is the applicant folder
                                                'linked_admin_code' => $loansFolder->id,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }

                    }

                    $applicantBusinessOwnership = session('business_ownership')[$i-1] ?? [];
                    if (!empty($applicantBusinessOwnership)) {
                        // Create Business Structure folder once per applicant
                        $newBusinessStructureFolder = UserStructureFolder::create([
                            'user_id' => Auth::id(),
                            'folder_name' => 'Business Structure',
                            'parent_id' => $applicantFolder->id,
                            'linked_admin_code' => null,
                        ]);

                        // Business ownership types that need to be created as new parent folders
                        $businessOwnershipMapping = [
                            'sole_trader' => ['code' => 'F3-084', 'name' => 'Sole Trader Business'],
                            'partnerships' => ['code' => 'F3-085', 'name' => 'Partnerships'],
                            'pty_ltd' => ['code' => 'F3-086', 'name' => 'Pty Ltd Company'],
                            'trusts' => ['code' => 'F3-087', 'name' => 'Trusts'],
                        ];

                        foreach ($applicantBusinessOwnership as $businessType => $status) {
                            if ($status === 'on' && isset($businessOwnershipMapping[$businessType])) {
                                UserStructureFolder::create([
                                    'user_id' => Auth::id(),
                                    'folder_name' => $businessOwnershipMapping[$businessType]['name'],
                                    'parent_id' => $newBusinessStructureFolder->id,
                                    'linked_admin_code' => $newBusinessStructureFolder->id,
                                ]);
                            }
                        }
                    }

                }

                session()->forget([
                    'current_step',
                    'loan_type',
                    'property_type',
                    'property_address',
                    'property_usage',
                    'car_address',
                    'home_address',
                    'personal_address',
                    'refinance_address',
                    'number_of_people',
                    'title',
                    'first_name',
                    'last_name',
                    'other_names',
                    'employment_status',
                    'other_income_sources',
                    'income_sources',
                    'assets',
                    'liabilities',
                    'other_assets_details',
                    'business_ownership'
                ]);

                return redirect()->route('admin.folders.index');
            }


            $number_of_people = session('number_of_people');
            $loan_type = session('loan_type');

            $nextStep = min($currentStep + 1, 8);
            session(['current_step' => $nextStep]);

            return redirect()->back();
    }

    public function previousStep()
    {
        $currentStep = session('current_step', 1);
        $previousStep = max($currentStep - 1, 1);
        session(['current_step' => $previousStep]);
        if($previousStep == 1)
        {
            session()->forget('loan_type');
        }
        return redirect()->back();
    }


    public function generateAllChildren($adminCode, $userParentId)
    {
        $children = AdminFolderTemplate::where('parent_code', $adminCode)->get();

        foreach ($children as $child) {
            $newFolder = UserStructureFolder::create([
                'user_id' => Auth::id(),
                'folder_name' => $child->name,
                'parent_id' => $userParentId,
                'linked_admin_code' => $child->unique_code,
            ]);

            $this->generateAllChildren($child->unique_code, $newFolder->id);
        }
    }

    /**
     * Copy one specific admin folder and all of its children.
     */
    public function generateSpecificAdminFolder($uniqueCode, $userParentId)
    {
        $admin = AdminFolderTemplate::where('unique_code', $uniqueCode)->first();

        if (!$admin) return;

        $investmentFolder = UserStructureFolder::create([
            'user_id' => Auth::id(),
            'folder_name' => $admin->name,
            'parent_id' => $userParentId,
            'linked_admin_code' => $admin->unique_code,
        ]);

        $this->generateAllChildren($admin->unique_code, $investmentFolder->id);
    }

    /**
     * Optional: Sync newly added admin folders to existing user structure.
     */
    public function syncNewAdminFolders($userId, $adminParentCode, $userParentId)
    {
        $adminChildren = AdminFolderTemplate::where('parent_code', $adminParentCode)->get();
        $existingCodes = UserStructureFolder::where('user_id', $userId)
            ->where('parent_id', $userParentId)
            ->pluck('linked_admin_code')
            ->toArray();

        foreach ($adminChildren as $admin) {
            if (!in_array($admin->unique_code, $existingCodes)) {
                $newFolder = UserStructureFolder::create([
                    'user_id' => $userId,
                    'folder_name' => $admin->name,
                    'parent_id' => $userParentId,
                    'linked_admin_code' => $admin->unique_code,
                ]);

                $this->syncNewAdminFolders($userId, $admin->unique_code, $newFolder->id);
            }
        }
    }


}

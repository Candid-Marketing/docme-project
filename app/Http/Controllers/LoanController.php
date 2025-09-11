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
                $request->validate([
                    'property_type' => 'required',
                    'property_address'=>'required',
                    'property_usage' => 'required'
                ]);

                session(['property_type' => $request->input('property_type')]);
                session(['property_address' => $request->input('property_address')]);
                session(['property_usage' => $request->input('property_usage')]);
            }

            if ($currentStep == 3) {
                $request->validate([
                    'number_of_people' => 'required',
                ]);


                session(['number_of_people' => $request->input('number_of_people')]);
            }

            if ($currentStep == 4) {
                // $request->validate([
                //     'title' => 'required',
                //     'first_name' => 'required',
                //     'last_name' => 'required|email',
                //     'other_names' => 'required',
                //     'income' => 'required',
                //     'super_an' => 'required',
                //     'other_assets' => 'required',
                //     'employment'=>'required',
                // ]);

                session(['title' => $request->input('title')]);
                session(['first_name' => $request->input('first_name')]);
                session(['last_name' => $request->input('last_name')]);
                session(['other_names' => $request->input('other_names')]);
                session(['income' => $request->input('income')]);
                session(['super_an' => $request->input('super_an')]);
                session(['other_assets' => $request->input('other_assets')]);
                session(['employment' => $request->input('employment')]);
            }

            if($currentStep == 5) {
                if($request->input('trust_account') == 'yes') {
                    session(['trust_name' => $request->input('trust_name')]);
                }
                else {
                    session(['trust_name' => '']);
                }

                session(['trust_account' => $request->input('trust_account')]);
            }

            if($currentStep == 6) {
                session(['share_account' => $request->input('share_account')]);
            }

            if ($currentStep == 7) {
                session(['liabilities' => $request->input('liabilities')]);
                session(['loan_types' => $request->input('loan_types')]);
            // }
            // if ($currentStep == 8) {
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
                    $folderLoan = 'Property Loan';
                }
                elseif (session('loan_type') == 'refinance')
                {
                    $folderLoan = 'Refinance Loan';
                }

                $folderHeader = 'Real Estate ' . session('property_address') . ' - ' . $folderLoan;
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
                    $employmentList = session('employment', []);
                    $liabilitiesList = session('liabilities', []);
                    $loanTypesList = session('loan_types', []);
                    $trustAccount = session('trust_account',[]);

                    $personData = [
                        'loan_type' => session('loan_type'),
                        'property_type' => session('property_type'),
                        'property_address' => session('property_address'),
                        'property_usage' => session('property_usage'),
                        'number_of_people' => $number_of_people,
                        'title' => $titleList[$i - 1] ?? null,
                        'first_name' => $firstNameList[$i - 1] ?? null,
                        'last_name' => $lastNameList[$i - 1] ?? null,
                        'other_names' => $otherNamesList[$i - 1] ?? null,
                        'income' => $incomeList[$i - 1] ?? null,
                        'super_an' => $superAnList[$i - 1] ?? null,
                        'employment' => $employmentList[$i - 1] ?? null,
                        'trust_account' => $trustAccount[$i - 1] ?? null,
                        'share_account' => session('share_account'),
                        'liabilities' => $liabilitiesList[$i - 1] ?? [],
                        'loan_types' => $loanTypesList[$i - 1] ?? [],
                    ];
                    // Save applicant to database
                    $file = Applicant::create($personData);

                    // Create folder for each applicant under main folder
                    $applicantFolder = UserStructureFolder::create([
                        'user_id' => Auth::id(),
                        'folder_name' => 'Applicant ' . $i,
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

                            if (!empty($file->liabilities)) {
                                foreach ($file->liabilities as $liability => $status) {
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

                            $loanMapping = [
                                'home_loan' => 'F3-078',
                                'investment_loan' => 'F3-079',
                                'personal_loan' => 'F3-080',
                                'car_loan' => 'F3-081',
                            ];

                            if (!empty($file->loan_types)) {
                                foreach ($file->loan_types as $loanType => $status) {
                                    if ($status === 'on' && isset($loanMapping[$loanType])) {
                                        $this->generateSpecificAdminFolder($loanMapping[$loanType], $loansFolder->id);
                                    }
                                }
                            }
                        }
                    }
                }

                session()->forget('current_step');

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

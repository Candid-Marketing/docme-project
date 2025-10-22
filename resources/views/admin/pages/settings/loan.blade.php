@extends('admin.dashboard')

@section('content')
<div class="main  ">
    <div class="container table-container ">
        <!-- Step Wizard -->
         <ul class="step-nav">

                @php
                $loan_type = session('loan_type');

                // Dynamic step title based on loan type
                $step2Title = 'Property Type'; // Default
                if ($loan_type == 'car') {
                    $step2Title = 'Car Loan Type';
                } elseif ($loan_type == 'home') {
                    $step2Title = 'Home Loan Type';
                } elseif ($loan_type == 'personal') {
                    $step2Title = 'Personal Loan Type';
                } elseif ($loan_type == 'property') {
                    $step2Title = 'Property Type';
                } elseif ($loan_type == 'refinance') {
                    $step2Title = 'Refinance Loan Type';
                }

                $steps = [
                    1 => 'Type Of Loan',
                    2 => $step2Title,
                    3 => 'Total Applicants',
                    4 => 'Applicant Information',
                    5 => 'Assets and Liabilities',
                    6 => 'Business Structure',

                ];
                $currentStep = session('current_step', 1);
                $number_of_people = session('number_of_people');
                @endphp

                @foreach ($steps as $step => $title)
                    <li class="step-item text-center {{ $currentStep == $step ? 'active' : '' }}">
                        <div class="step-number">{{ $step }}</div>
                        <div class="step-title">{{ $title }}</div>
                    </li>
                @endforeach
            </ul>


            <div class="progress mb-4">
                <div class="progress-bar" role="progressbar" style=" background-color: #3c2464;width: {{ ($currentStep / count($steps)) * 100 }}%;" aria-valuenow="{{ $currentStep }}" aria-valuemin="1" aria-valuemax="{{ count($steps) }}"></div>
            </div>

            <!-- Step Content -->
            <div class="step-content">
                <form action="{{ route('admin.loan.nextStep') }}" method="POST" id="loanForm">
                    @csrf

                    @if ($currentStep == 1)
                        <div class="form-group p-4 border rounded bg-light">
                            <label class="h5">Select Loan Type</label>
                            <div class="row text-center justify-content-center">
                                @foreach ([
                                    ['car', 'Car Loan', 'car_loan2.png'],
                                    ['home', 'Home Loan', 'home_loan.png'],
                                    ['personal', 'Personal Loan', 'personal_loan.png'],
                                    ['property', 'Property Loan', 'property_loan.png'],
                                    ['refinance', 'Refinance', 'ref_loan.png']
                                ] as $loan)
                                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                                        <label class="loan-option card shadow-sm p-2 border-0 position-relative loan-type-card h-100">

                                            <input type="radio" name="loan_type" value="{{ $loan[0] }}" class="d-none loan-input">
                                            <img src="{{ asset('imgs/'.$loan[2]) }}" alt="{{ $loan[1] }}" class="img-fluid rounded">
                                            <p class="fw-bold loan-label">{{ $loan[1] }}</p>
                                            <span class="checkmark position-absolute top-0 end-0 bg-primary text-white  p-2 d-none" style="
                                                width: 18px;
                                                height: 18px;
                                                background-color: #3B68B2;
                                                border: 2px solid #3B68B2;
                                                border-radius: 3px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                color: white;
                                                font-size: 14px;
                                                font-weight: bold;
                                                margin-right: 10px;
                                                margin-top: 4px;
                                                ">✔</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                    @if ($currentStep == 2)
                        @if($loan_type == 'property')
                            <!-- Property Loan Fields -->
                            <div class="form-group p-4 border rounded property-type">
                                <label class="h5">Select Property Type</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="property_type" value="residential" id="residential">
                                        <label class="form-check-label" for="residential">Residential</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="property_type" value="commercial" id="commercial">
                                        <label class="form-check-label" for="commercial">Commercial</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Enter Property Address</label>
                                <input type="text" name="property_address" class="form-control" placeholder="Enter address" required>
                            </div>
                            {{-- <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Property Location</label>
                                <div id="map" style="height: 300px; width: 100%; border-radius: 8px; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);"></div>
                            </div> --}}

                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Specify Property Usage</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="property_usage" value="residence" id="residence">
                                        <label class="form-check-label" for="residence">Residence</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="property_usage" value="investment" id="investment">
                                        <label class="form-check-label" for="investment">Investment</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="property_usage" value="construct" id="construct">
                                        <label class="form-check-label" for="construct">Construct</label>
                                    </div>
                                </div>
                            </div>
                        @elseif($loan_type == 'car')
                            <!-- Car Loan Fields -->
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Enter Car Loan Address</label>
                                <input type="text" name="car_address" class="form-control" placeholder="Enter car address" required>
                            </div>
                        @elseif($loan_type == 'home')
                            <!-- Home Loan Fields -->
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Enter Home Loan Address</label>
                                <input type="text" name="home_address" class="form-control" placeholder="Enter home address" required>
                            </div>
                        @elseif($loan_type == 'personal')
                            <!-- Personal Loan Fields -->
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Enter Personal Loan Address</label>
                                <input type="text" name="personal_address" class="form-control" placeholder="Enter personal loan address" required>
                            </div>
                        @elseif($loan_type == 'refinance')
                            <!-- Refinance Loan Fields -->
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Enter Refinance Loan Address</label>
                                <input type="text" name="refinance_address" class="form-control" placeholder="Enter refinance loan address" required>
                            </div>
                        @endif
                    @endif

                    @if ($currentStep == 3)
                        <div class="form-group p-4 border rounded mt-4">
                            <label class="h5">How many people are going for the loan?</label>
                            <input type="number" name="number_of_people" class="form-control no-spinner" placeholder="Enter number of people" required min="1" id="numApplicants">
                        </div>
                    @endif

                    @if ($currentStep == 4)
                    <div id="applicantDetails">
                        <label class="h5">What is the name of the applicant(s)?</label>
                        <div id="applicantFields">
                            @for ($i = 1; $i <= $number_of_people; $i++)
                            <div class="applicant-set border p-3 mb-3" data-applicant="{{ $i }}">
                                <label>Applicant {{ $i }}</label>
                                <select name="title[]" class="form-control mb-2">
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Miss">Miss</option>
                                    <option value="Master">Master</option>
                                </select>
                                <input type="text" name="first_name[]" class="form-control mb-2" placeholder="First Name" required>
                                <input type="text" name="last_name[]" class="form-control mb-2" placeholder="Last Name" required>
                                <input type="text" name="other_names[]" class="form-control mb-2" placeholder="Other Names">

                                <!-- Employment Status Question for each applicant -->
                                <div class="form-group mt-3">
                                    <label class="h6">Are you employed or self-employed?</label>
                                    <select name="employment_status[{{ $i }}]" class="form-control employment-status" required data-applicant="{{ $i }}">
                                        <option value="">Select Option</option>
                                        <option value="employed">Employed</option>
                                        <option value="self-employed">Self-employed</option>
                                    </select>
                                </div>

                                <!-- Other Income Sources Question (shown when employed is selected) -->
                                <div class="form-group mt-3 other-income-question" style="display: none;" data-applicant="{{ $i }}">
                                    <label class="h6">Do you have other income sources? (e.g. Trust Distributions, Government income, Spousal Support, Gifts, Superannuation Income, Foreign Income)</label>
                                    <select name="other_income_sources[{{ $i }}]" class="form-control other-income-select" data-applicant="{{ $i }}">
                                        <option value="">Select Option</option>
                                        <option value="no">No</option>
                                        <option value="yes">Yes</option>
                                    </select>
                                </div>

                                <!-- Individual Income Source Dropdowns (shown when other income is yes) -->
                                <div class="form-group mt-3 income-sources-details" style="display: none;" data-applicant="{{ $i }}">
                                    <label class="h6">Please specify which income sources apply:</label>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Trust Distributions</label>
                                                <select name="income_sources[{{ $i }}][trust_distributions]" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Government Income</label>
                                                <select name="income_sources[{{ $i }}][government_income]" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Spousal Support</label>
                                                <select name="income_sources[{{ $i }}][spousal_support]" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gifts</label>
                                                <select name="income_sources[{{ $i }}][gifts]" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Superannuation Income</label>
                                                <select name="income_sources[{{ $i }}][superannuation_income]" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Foreign Income</label>
                                                <select name="income_sources[{{ $i }}][foreign_income]" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                    @endif

                    @if ($currentStep == 5)
                        <div id="applicantDetails">
                            <label class="h5">Select all Assets and Liabilities you currently own/have for review</label>
                            <div class="row">
                                <!-- Column 1: Assets -->
                                <div class="col-12 col-lg-6">
                                    <h5>Assets</h5>
                                    @for ($i = 1; $i <= $number_of_people; $i++)
                                        <div class="applicant-set border p-3 mb-3">
                                            <label>Applicant {{ $i }}</label>

                                            <!-- Checkboxes for each asset -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="assets[{{ $i }}][personal_home]" id="personal_home_{{ $i }}">
                                                <label class="form-check-label" for="personal_home_{{ $i }}">
                                                    Personal Home
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="assets[{{ $i }}][investment_properties]" id="investment_properties_{{ $i }}">
                                                <label class="form-check-label" for="investment_properties_{{ $i }}">
                                                    Investment Properties
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="assets[{{ $i }}][shares]" id="shares_{{ $i }}">
                                                <label class="form-check-label" for="shares_{{ $i }}">
                                                    Shares
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="assets[{{ $i }}][bank_accounts]" id="bank_accounts_{{ $i }}">
                                                <label class="form-check-label" for="bank_accounts_{{ $i }}">
                                                    Bank Accounts
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="assets[{{ $i }}][cryptocurrency]" id="cryptocurrency_{{ $i }}">
                                                <label class="form-check-label" for="cryptocurrency_{{ $i }}">
                                                    Cryptocurrency
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input other-assets-checkbox" type="checkbox" name="assets[{{ $i }}][other_assets]" id="other_assets_{{ $i }}" data-applicant="{{ $i }}">
                                                <label class="form-check-label" for="other_assets_{{ $i }}">
                                                    Other Assets e.g. Cars, Boats, Jewellery, Art
                                                </label>
                                            </div>

                                            <!-- Conditional dropdown for specific other assets -->
                                            <div class="form-group mt-2 other-assets-details" style="display: none;" data-applicant="{{ $i }}">
                                                <label class="h6">Please specify which other assets apply:</label>

                                                <div class="row">
                                                    <div class="col-6 col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="other_assets_details[{{ $i }}][cars]" id="cars_{{ $i }}">
                                                            <label class="form-check-label" for="cars_{{ $i }}">
                                                                Cars
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="other_assets_details[{{ $i }}][boats]" id="boats_{{ $i }}">
                                                            <label class="form-check-label" for="boats_{{ $i }}">
                                                                Boats
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6 col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="other_assets_details[{{ $i }}][jewellery]" id="jewellery_{{ $i }}">
                                                            <label class="form-check-label" for="jewellery_{{ $i }}">
                                                                Jewellery
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="other_assets_details[{{ $i }}][art]" id="art_{{ $i }}">
                                                            <label class="form-check-label" for="art_{{ $i }}">
                                                                Art
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endfor
                                </div>

                                <!-- Column 2: Liabilities -->
                                <div class="col-12 col-lg-6">
                                    <h5>Liabilities</h5>
                                    @for ($i = 1; $i <= $number_of_people; $i++)
                                        <div class="applicant-set border p-3 mb-3">
                                            <label>Applicant {{ $i }}</label>

                                            <!-- Checkboxes for each liability -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][home_loan]" id="home_loan_{{ $i }}">
                                                <label class="form-check-label" for="home_loan_{{ $i }}">
                                                    Home Loan
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][investment_loans]" id="investment_loans_{{ $i }}">
                                                <label class="form-check-label" for="investment_loans_{{ $i }}">
                                                    Investment Loans
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][personal_loans]" id="personal_loans_{{ $i }}">
                                                <label class="form-check-label" for="personal_loans_{{ $i }}">
                                                    Personal Loans
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][car_loans]" id="car_loans_{{ $i }}">
                                                <label class="form-check-label" for="car_loans_{{ $i }}">
                                                    Car Loans
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][private_loans]" id="private_loans_{{ $i }}">
                                                <label class="form-check-label" for="private_loans_{{ $i }}">
                                                    Private Loans
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][credit_cards]" id="credit_cards_{{ $i }}">
                                                <label class="form-check-label" for="credit_cards_{{ $i }}">
                                                    Credit Cards
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][others]" id="others_{{ $i }}">
                                                <label class="form-check-label" for="others_{{ $i }}">
                                                    Others
                                                </label>
                                            </div>

                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endif




                    @if ($currentStep == 6)
                    @for ($i = 0; $i < $number_of_people; $i++)
                        <div class="form-group p-4 border rounded mt-4">
                            <label class="h5">Applicant {{ $i + 1 }} - Do you own or part own any of the following?</label>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="business_ownership[{{ $i }}][sole_trader]" id="sole_trader_{{ $i }}">
                                <label class="form-check-label" for="sole_trader_{{ $i }}">
                                    Sole Trader Business
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="business_ownership[{{ $i }}][partnerships]" id="partnerships_{{ $i }}">
                                <label class="form-check-label" for="partnerships_{{ $i }}">
                                    Partnerships
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="business_ownership[{{ $i }}][pty_ltd]" id="pty_ltd_{{ $i }}">
                                <label class="form-check-label" for="pty_ltd_{{ $i }}">
                                    Pty Ltd Company
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="business_ownership[{{ $i }}][trusts]" id="trusts_{{ $i }}">
                                <label class="form-check-label" for="trusts_{{ $i }}">
                                    Trusts
                                </label>
                            </div>
                        </div>
                    @endfor
                @endif



                    <div class="d-flex justify-content-between">
                        @if($currentStep > 1)
                            <a href="{{ route('admin.loan.previousStep') }}" class="btn" style="background-color: #ed1d7e; color: white;"  onclick="storeStep({{ $currentStep - 1 }})">Previous</a>
                        @endif
                        @if($currentStep == 6)
                            <button type="submit" class="btn" style="background-color: #3b68b2; color: white;"  onclick="storeStep({{ $currentStep + 1 }})" >Get Started</button>
                        @else
                            <button type="submit" class="btn" style="background-color: #3b68b2; color: white;" onclick="storeStep({{ $currentStep + 1 }})">Next</button> <!-- Next Button -->
                        @endif
                    </div>

                </form>
            </div>
    </div>
</div>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        let storedLoanType = localStorage.getItem("loan_type");
        if (storedLoanType) {
            let selectedInput = document.querySelector(`input[name='loan_type'][value='${storedLoanType}']`);
            if (selectedInput) {
                selectedInput.checked = true;
            }
        }

        document.getElementById("numApplicants").addEventListener("change", function() {
            let numApplicants = parseInt(this.value);
            let container = document.getElementById("applicantFields");
            container.innerHTML = "";

            for (let i = 1; i <= numApplicants; i++) {
                let applicantHtml = `<div class="applicant-set border p-3 mb-3" data-applicant="${i}">
                                        <label>Applicant ${i}</label>
                                        <select name="title[]" class="form-control mb-2">
                                            <option value="Mr">Mr</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Miss">Miss</option>
                                            <option value="Master">Master</option>
                                        </select>
                                        <input type="text" name="first_name[]" class="form-control mb-2" placeholder="First Name" required>
                                        <input type="text" name="last_name[]" class="form-control mb-2" placeholder="Last Name" required>
                                        <input type="text" name="other_names[]" class="form-control mb-2" placeholder="Other Names">

                                        <!-- Employment Status Question for each applicant -->
                                        <div class="form-group mt-3">
                                            <label class="h6">Are you employed or self-employed?</label>
                                            <select name="employment_status[${i}]" class="form-control employment-status" required data-applicant="${i}">
                                                <option value="">Select Option</option>
                                                <option value="employed">Employed</option>
                                                <option value="self-employed">Self-employed</option>
                                            </select>
                                        </div>

                                        <!-- Other Income Sources Question (shown when employed is selected) -->
                                        <div class="form-group mt-3 other-income-question" style="display: none;" data-applicant="${i}">
                                            <label class="h6">Do you have other income sources? (e.g. Trust Distributions, Government income, Spousal Support, Gifts, Superannuation Income, Foreign Income)</label>
                                            <select name="other_income_sources[${i}]" class="form-control other-income-select" data-applicant="${i}">
                                                <option value="">Select Option</option>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>

                                        <!-- Individual Income Source Dropdowns (shown when other income is yes) -->
                                        <div class="form-group mt-3 income-sources-details" style="display: none;" data-applicant="${i}">
                                            <label class="h6">Please specify which income sources apply:</label>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Trust Distributions</label>
                                                        <select name="income_sources[${i}][trust_distributions]" class="form-control">
                                                            <option value="">Select Option</option>
                                                            <option value="no">No</option>
                                                            <option value="yes">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Government Income</label>
                                                        <select name="income_sources[${i}][government_income]" class="form-control">
                                                            <option value="">Select Option</option>
                                                            <option value="no">No</option>
                                                            <option value="yes">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Spousal Support</label>
                                                        <select name="income_sources[${i}][spousal_support]" class="form-control">
                                                            <option value="">Select Option</option>
                                                            <option value="no">No</option>
                                                            <option value="yes">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Gifts</label>
                                                        <select name="income_sources[${i}][gifts]" class="form-control">
                                                            <option value="">Select Option</option>
                                                            <option value="no">No</option>
                                                            <option value="yes">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Superannuation Income</label>
                                                        <select name="income_sources[${i}][superannuation_income]" class="form-control">
                                                            <option value="">Select Option</option>
                                                            <option value="no">No</option>
                                                            <option value="yes">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Foreign Income</label>
                                                        <select name="income_sources[${i}][foreign_income]" class="form-control">
                                                            <option value="">Select Option</option>
                                                            <option value="no">No</option>
                                                            <option value="yes">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                container.insertAdjacentHTML("beforeend", applicantHtml);
            }

            // Re-attach event listeners for the new elements
            attachEmploymentEventListeners();
        });

    });

    // Function to attach employment event listeners
    function attachEmploymentEventListeners() {
        // Handle employment status change
        document.querySelectorAll('.employment-status').forEach(select => {
            select.addEventListener('change', function() {
                const applicantId = this.getAttribute('data-applicant');
                const otherIncomeQuestion = document.querySelector(`.other-income-question[data-applicant="${applicantId}"]`);
                const incomeSourcesDetails = document.querySelector(`.income-sources-details[data-applicant="${applicantId}"]`);

                if (this.value === 'employed') {
                    otherIncomeQuestion.style.display = 'block';
                } else {
                    otherIncomeQuestion.style.display = 'none';
                    incomeSourcesDetails.style.display = 'none';
                    // Reset the other income select to 'Select Option'
                    const otherIncomeSelect = document.querySelector(`.other-income-select[data-applicant="${applicantId}"]`);
                    if (otherIncomeSelect) {
                        otherIncomeSelect.value = '';
                    }
                }
            });
        });

        // Handle other income sources change
        document.querySelectorAll('.other-income-select').forEach(select => {
            select.addEventListener('change', function() {
                const applicantId = this.getAttribute('data-applicant');
                const incomeSourcesDetails = document.querySelector(`.income-sources-details[data-applicant="${applicantId}"]`);

                if (this.value === 'yes') {
                    incomeSourcesDetails.style.display = 'block';
                } else {
                    incomeSourcesDetails.style.display = 'none';
                }
            });
        });
    }

    // Function to attach other assets event listeners
    function attachOtherAssetsEventListeners() {
        console.log('Attaching other assets event listeners...');
        console.log('Found checkboxes:', document.querySelectorAll('.other-assets-checkbox').length);

        // Handle other assets checkbox change
        document.querySelectorAll('.other-assets-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const applicantId = this.getAttribute('data-applicant');
                const otherAssetsDetails = document.querySelector(`.other-assets-details[data-applicant="${applicantId}"]`);

                console.log('Other assets checkbox changed for applicant:', applicantId);
                console.log('Checkbox checked:', this.checked);
                console.log('Other assets details element:', otherAssetsDetails);

                if (this.checked) {
                    otherAssetsDetails.style.display = 'block';
                    console.log('Showing other assets details');
                } else {
                    otherAssetsDetails.style.display = 'none';
                    // Uncheck all specific other assets when main checkbox is unchecked
                    const specificCheckboxes = otherAssetsDetails.querySelectorAll('input[type="checkbox"]');
                    specificCheckboxes.forEach(cb => cb.checked = false);
                    console.log('Hiding other assets details and unchecking all');
                }
            });
        });
    }

    // Attach event listeners on page load
    document.addEventListener('DOMContentLoaded', function() {
        attachEmploymentEventListeners();
        attachOtherAssetsEventListeners();
    });

    document.querySelectorAll(".loan-type-card").forEach(card => {
        card.addEventListener("click", function () {
            document.querySelectorAll(".loan-type-card").forEach(c => c.classList.remove("active-loan"));
            this.classList.add("active-loan");
            this.querySelector(".loan-input").checked = true;
            document.querySelectorAll(".checkmark").forEach(mark => mark.classList.add("d-none"));
            this.querySelector(".checkmark").classList.remove("d-none");
        });
    });

    document.querySelectorAll("input[name='loan_type']").forEach(input => {
        input.addEventListener("change", function() {
            localStorage.setItem("loan_type", this.value);
        });
    });

    // function initMap() {
    //     var map = new google.maps.Map(document.getElementById('map'), {
    //         center: { lat: -33.8688, lng: 151.2093 },
    //         zoom: 12
    //     });

    //     var input = document.querySelector("input[name='property_address']");
    //     var autocomplete = new google.maps.places.Autocomplete(input);
    //     autocomplete.bindTo('bounds', map);

    //     var marker = new google.maps.Marker({
    //         map: map,
    //         anchorPoint: new google.maps.Point(0, -29)
    //     });

    //     autocomplete.addListener('place_changed', function() {
    //         marker.setVisible(false);
    //         var place = autocomplete.getPlace();
    //         if (!place.geometry) {
    //             return;
    //         }

    //         // Store selected place details in hidden fields
    //         document.getElementById('latitude').value = place.geometry.location.lat();
    //         document.getElementById('longitude').value = place.geometry.location.lng();

    //         if (place.geometry.viewport) {
    //             map.fitBounds(place.geometry.viewport);
    //         } else {
    //             map.setCenter(place.geometry.location);
    //             map.setZoom(17);
    //         }
    //         marker.setPosition(place.geometry.location);
    //         marker.setVisible(true);
    //     });
    // }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7LxaE0lURrjon7YFU3Crz4BQ4yabxF64&libraries=places&callback=initMap" async defer></script>
<style>
    /* Reset and Base Styles */
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
    }

    /* Number Input Spinners */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    /* Main Container */
    .table-container {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin: 20px auto;
        width: 95%;
        max-width: 1200px;
        display: flex;
        flex-direction: column;
    }

    /* Step Navigation */
    .step-nav {
        list-style: none;
        padding: 0;
        margin: 0 0 30px;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        max-width: 120px;
        min-height: 90px;
        padding: 15px 10px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .step-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }

    .step-item:hover::before {
        left: 100%;
    }

    .step-item.active {
        background: linear-gradient(135deg, #3b68b2 0%, #2c5282 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 104, 178, 0.3);
    }

    .step-number {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #dee2e6 0%, #adb5bd 100%);
        color: #495057;
        font-weight: bold;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .step-item.active .step-number {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        color: #3b68b2;
        transform: scale(1.1);
    }

    .step-title {
        font-size: 11px;
        font-weight: 600;
        color: inherit;
        word-break: break-word;
        text-align: center;
        line-height: 1.3;
    }

    /* Progress Bar */
    .progress {
        height: 8px;
        border-radius: 10px;
        background: linear-gradient(90deg, #e9ecef 0%, #f8f9fa 100%);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .progress-bar {
        background: linear-gradient(90deg, #3c2464 0%, #3b68b2 100%);
        border-radius: 10px;
        transition: width 0.6s ease;
        box-shadow: 0 2px 4px rgba(60, 36, 100, 0.3);
    }

    /* Loan Type Cards */
    .loan-type-card {
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        padding: 20px 15px;
        text-align: center;
        height: 100%;
        border-radius: 12px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .loan-type-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #3b68b2 0%, #2c5282 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
    }

    .loan-type-card:hover::before {
        opacity: 0.1;
    }

    .loan-type-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(59, 104, 178, 0.2);
        border-color: #3b68b2;
    }

    .loan-type-card img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .loan-type-card:hover img {
        transform: scale(1.1);
        filter: brightness(1.1);
    }

    .loan-label {
        font-size: 14px;
        font-weight: 600;
        margin-top: 8px;
        color: #333;
        position: relative;
        z-index: 2;
        transition: color 0.3s ease;
    }

    .loan-type-card.active-loan {
        border-color: #3b68b2 !important;
        background: linear-gradient(135deg, rgba(59, 104, 178, 0.1) 0%, rgba(44, 82, 130, 0.1) 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 104, 178, 0.3);
    }

    .loan-type-card.active-loan .loan-label {
        color: #3b68b2;
        font-weight: 700;
    }

    .checkmark {
        font-size: 12px;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .form-control:focus {
        border-color: #3b68b2;
        box-shadow: 0 0 0 3px rgba(59, 104, 178, 0.1);
        outline: none;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 0.25em;
    }

    .form-check-input:checked {
        background-color: #3b68b2;
        border-color: #3b68b2;
    }

    .form-check-label {
        font-weight: 500;
        color: #333;
        margin-left: 8px;
    }

    /* Applicant Sets */
    .applicant-set {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .applicant-set:hover {
        border-color: #3b68b2;
        box-shadow: 0 4px 16px rgba(59, 104, 178, 0.1);
    }

    /* Buttons */
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Navigation Buttons */
    .d-flex.justify-content-between {
        justify-content: space-between !important;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e9ecef;
    }

    /* Headings */
    .h5, .h6 {
        color: #333;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .h5 {
        font-size: 1.4rem;
        color: #2c5282;
    }

    .h6 {
        font-size: 1.1rem;
        color: #3b68b2;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .table-container {
            padding: 20px 15px;
            margin: 10px auto;
            width: 98%;
        }

        .step-nav {
            gap: 8px;
            margin-bottom: 20px;
        }

        .step-item {
            min-width: 80px;
            max-width: 90px;
            min-height: 70px;
            padding: 10px 8px;
        }

        .step-number {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .step-title {
            font-size: 10px;
        }

        .loan-type-card {
            padding: 15px 10px;
        }

        .loan-type-card img {
            width: 45px;
            height: 45px;
        }

        .loan-label {
            font-size: 12px;
        }

        .applicant-set {
            padding: 15px;
        }

        .form-control {
            padding: 10px 12px;
            font-size: 13px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 13px;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 15px;
        }

        .d-flex.justify-content-between .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .step-nav {
            flex-direction: column;
            align-items: center;
        }

        .step-item {
            width: 100%;
            max-width: 200px;
            flex-direction: row;
            text-align: left;
            min-height: 50px;
        }

        .step-number {
            margin-right: 15px;
            margin-bottom: 0;
        }

        .step-title {
            text-align: left;
            font-size: 14px;
        }

        .h5 {
            font-size: 1.2rem;
        }

        .h6 {
            font-size: 1rem;
        }
    }

    /* Animation for form elements */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .applicant-set {
        animation: fadeInUp 0.5s ease-out;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: #3b68b2;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #2c5282;
    }
</style>
@endsection

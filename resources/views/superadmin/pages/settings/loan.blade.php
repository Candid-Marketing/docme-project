@extends('superadmin.dashboard')

@section('content')
<div class="main">
    <div class="container">
        <!-- Step Wizard -->
        <div class="step-wizard">
           <ul class="step-nav d-flex justify-content-between flex-wrap gap-2 px-3">
                @php
                $steps = [
                    1 => 'Type Of Loan',
                    2 => 'Property Type',
                    3 => 'Total Applicants',
                    4 => 'Applicant Information',
                    5 => 'Employment and Trusts',
                    6 => 'Employment and Shares',
                    7 => 'Assets and Liabilities',
                    8 => 'Get Started'
                ];
                $currentStep = session('current_step', 1);
                @endphp

                @foreach ($steps as $step => $title)
                    <li class="step-item {{ $currentStep == $step ? 'active' : '' }}">
                        <div class="step-number">{{ $step }}</div>
                        <div class="step-title">{{ $title }}</div>
                    </li>
                @endforeach
            </ul>


            <div class="progress mb-4">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($currentStep / count($steps)) * 100 }}%;" aria-valuenow="{{ $currentStep }}" aria-valuemin="1" aria-valuemax="{{ count($steps) }}"></div>
            </div>

            <!-- Step Content -->
            <div class="step-content">
                <h3>{{ $steps[$currentStep] }}</h3>
                <form action="{{ route('superadmin.loan.nextStep') }}" method="POST" id="loanForm">
                    @csrf

                    @if ($currentStep == 1)
                    <div class="form-group p-4 border rounded bg-light">
                        <label class="h5">Select Loan Type</label>
                        <div class="row text-center">
                            @foreach ([
                                ['car', 'Car Loan', 'car.png'],
                                ['home', 'Home Loan', 'house.png'],
                                ['personal', 'Personal Loan', 'personal.png'],
                                ['property', 'Property Loan', 'property.png'],
                                ['refinance', 'Refinance', 'ref.png']
                            ] as $loan)
                                <div class="col-md-4 mb-3">
                                    <label class="loan-option card shadow-sm p-3 border-0 position-relative loan-type-card">
                                        <input type="radio" name="loan_type" value="{{ $loan[0] }}" class="d-none loan-input">
                                        <img src="{{ asset('imgs/'.$loan[2]) }}" alt="{{ $loan[1] }}" class="img-fluid rounded">
                                        <p class="fw-bold loan-label">{{ $loan[1] }}</p>
                                        <span class="checkmark position-absolute top-0 end-0 bg-primary text-white rounded-circle p-2 d-none">✔</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif


                    @if ($currentStep == 2 && session('loan_type') == 'property')
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
                    @endif

                    @if ($currentStep == 3)
                    <div class="form-group p-4 border rounded mt-4">
                        <label class="h5">How many people are going for the loan?</label>
                        <input type="number" name="number_of_people" class="form-control" placeholder="Enter number of people" required min="1" id="numApplicants">
                    </div>
                    @endif

                    @if ($currentStep == 4)
                    <div id="applicantDetails">
                        <label class="h5">What is the name of the applicant(s)?</label>
                        <div id="applicantFields">
                            @for ($i = 1; $i <= $number_of_people; $i++)
                            <div class="applicant-set border p-3 mb-3">
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
                            </div>
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Monthly Income</label>
                                <input type="number" name="income[]" class="form-control" placeholder="Enter the montly income" required min="1">
                            </div>

                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Superannuation</label>
                                <input type="number" name="super_an[]" class="form-control" placeholder="Enter the yearly superannuation" required min="1">
                            </div>

                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Other Assets</label>
                                <input type="text" name="other_assets[]" class="form-control" placeholder="Enter the other assets" required min="1" >
                            </div>

                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Are you employed or self-employed?</label>
                                <select name="employment[]" class="form-control" required id="employmentStatus">
                                    <option value="employed">Employed</option>
                                    <option value="self-employed" selected>Self-employed</option> <!-- Default selected option -->
                                </select>
                            </div>
                            @endfor
                        </div>
                    </div>
                    @endif

                    @if ($currentStep == 5)
                        @if($number_of_people == 1)
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Does the applicant have a trust? </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trust_account" value="yes" id="trust_yes" required>
                                    <label class="form-check-label" for="trust_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trust_account" value="no" id="trust_no">
                                    <label class="form-check-label" for="trust_no">No</label>
                                </div>
                            </div>
                            <div class="form-group p-4 border rounded mt-4" id="trust_name_section" style="display:none;">
                                <label class="h5">Trust Name</label>
                                <input type="text" name="trust_name" class="form-control" id="trust_name" placeholder="Enter the trust name">
                            </div>
                        @else
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5"> Does either applicant have a trust account? </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trust_account" value="yes" id="trust_yes" required>
                                    <label class="form-check-label" for="trust_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trust_account" value="no" id="trust_no">
                                    <label class="form-check-label" for="trust_no">No</label>
                                </div>
                            </div>
                            <div class="form-group p-4 border rounded mt-4" id="trust_name_section" style="display:none;">
                                <label class="h5">Trust Name</label>
                                <input type="text" name="trust_name" class="form-control" id="trust_name" placeholder="Enter the trust name">
                            </div>
                        @endif
                    @endif

                    @if ($currentStep == 6)
                        @if($number_of_people == 1)
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5">Do you have shares ? </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="share_account" value="yes" id="share_yes" required>
                                    <label class="form-check-label" for="trust_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="share_account" value="no" id="share_no">
                                    <label class="form-check-label" for="trust_no">No</label>
                                </div>
                            </div>
                        @else
                            <div class="form-group p-4 border rounded mt-4">
                                <label class="h5"> Do either applicants have shares ? </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="share_account" value="yes" id="share_yes" required>
                                    <label class="form-check-label" for="trust_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="share_account" value="no" id="share_no">
                                    <label class="form-check-label" for="trust_no">No</label>
                                </div>
                            </div>
                        @endif
                    @endif


                    @if ($currentStep == 7)
                        <div id="applicantDetails">
                            <label class="h5">Select all liabilities and loan types that apply</label>
                            <div class="row">
                                <!-- Column 1: Liabilities -->
                                <div class="col-md-6">
                                    <h5>Liabilities</h5>
                                    @for ($i = 1; $i <= $number_of_people; $i++)
                                        <div class="applicant-set border p-3 mb-3">
                                            <label>Applicant {{ $i }}</label>

                                            <!-- Checkboxes for each liability -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][motor_vehicles]" id="motor_vehicles_{{ $i }}">
                                                <label class="form-check-label" for="motor_vehicles_{{ $i }}">
                                                    Motor Vehicles
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][boats]" id="boats_{{ $i }}">
                                                <label class="form-check-label" for="boats_{{ $i }}">
                                                    Boats
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][jewellery]" id="jewellery_{{ $i }}">
                                                <label class="form-check-label" for="jewellery_{{ $i }}">
                                                    Jewellery
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="liabilities[{{ $i }}][art]" id="art_{{ $i }}">
                                                <label class="form-check-label" for="art_{{ $i }}">
                                                    Art
                                                </label>
                                            </div>

                                        </div>
                                    @endfor
                                </div>

                                <!-- Column 2: Loan Types -->
                                <div class="col-md-6">
                                    <h5>Loan Types</h5>
                                    @for ($i = 1; $i <= $number_of_people; $i++)
                                        <div class="applicant-set border p-3 mb-3">
                                            <label>Applicant {{ $i }}</label>

                                            <!-- Checkboxes for each loan type -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="loan_types[{{ $i }}][home_loan]" id="home_loan_{{ $i }}">
                                                <label class="form-check-label" for="home_loan_{{ $i }}">
                                                    Home Loan
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="loan_types[{{ $i }}][investment_loan]" id="investment_loan_{{ $i }}">
                                                <label class="form-check-label" for="investment_loan_{{ $i }}">
                                                    Investment Loan
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="loan_types[{{ $i }}][personal_loan]" id="personal_loan_{{ $i }}">
                                                <label class="form-check-label" for="personal_loan_{{ $i }}">
                                                    Personal Loan
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="loan_types[{{ $i }}][car_loan]" id="car_loan_{{ $i }}">
                                                <label class="form-check-label" for="car_loan_{{ $i }}">
                                                    Car Loan
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="loan_types[{{ $i }}][credit_card]" id="credit_card_{{ $i }}">
                                                <label class="form-check-label" for="credit_card_{{ $i }}">
                                                    Credit Card
                                                </label>
                                            </div>

                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endif



                    <div class="d-flex justify-content-between">
                        @if($currentStep > 1)
                            <a href="{{ route('superadmin.loan.previousStep') }}" class="btn btn-secondary" onclick="storeStep({{ $currentStep - 1 }})">Previous</a>
                        @endif
                        @if($currentStep == 8)
                            <button type="submit" class="btn btn-success" onclick="storeStep({{ $currentStep + 1 }})" >Get Started</button>
                        @else
                            <button type="submit" class="btn btn-primary" onclick="storeStep({{ $currentStep + 1 }})">Next</button> <!-- Next Button -->
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="trust_account"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const trustNameSection = document.getElementById('trust_name_section');

            // Show or hide the Trust Name input based on whether "Yes" is selected
            if (this.value === 'yes') {
                trustNameSection.style.display = 'block'; // Show the trust name input
            } else {
                trustNameSection.style.display = 'none'; // Hide the trust name input
            }
        });
    });

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
                let applicantHtml = `<div class="applicant-set border p-3 mb-3">
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
                                    </div>`;
                container.insertAdjacentHTML("beforeend", applicantHtml);
            }
        });

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
    .loan-type-card {
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease-in-out;
    }
    .loan-type-card.active-loan {
        border-color: #007bff !important;
        background-color: rgba(0, 123, 255, 0.1);
    }
    .checkmark {
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
    }

</style>
@endsection

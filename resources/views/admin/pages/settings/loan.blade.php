@extends('admin.dashboard')

@section('content')
<div class="main  ">
    <div class="container table-container ">
        <!-- Step Wizard -->
         <ul class="step-nav d-flex justify-content-center gap-3 px-3 flex-nowrap ">

                @php
                $steps = [
                    1 => 'Type Of Loan',
                    2 => 'Property Type',
                    3 => 'Total Applicants',
                    4 => 'Applicant Information',
                    5 => 'Employment and Trusts',
                    6 => 'Employment and Shares',
                    7 => 'Assets and Liabilities',

                ];
                $currentStep = session('current_step', 1);
                  $loan_type = session('loan_type');
                $number_of_people =session('number_of_people')
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
                            <input type="number" name="number_of_people" class="form-control no-spinner" placeholder="Enter number of people" required min="1" id="numApplicants">
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
                    @for ($i = 0; $i < $number_of_people; $i++)
                        <div class="form-group p-4 border rounded mt-4">
                            <label class="h5">Does Applicant {{ $i + 1 }} have a trust account?</label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="trust_account[{{ $i }}]" value="yes" id="trust_yes_applicant_{{ $i }}" required>
                                <label class="form-check-label" for="trust_yes_applicant_{{ $i }}">Yes</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="trust_account[{{ $i }}]" value="no" id="trust_no_applicant_{{ $i }}">
                                <label class="form-check-label" for="trust_no_applicant_{{ $i }}">No</label>
                            </div>
                        </div>
                    @endfor
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
                            <label class="h5">Select all liabilities, assets and loan types that apply</label>
                            <div class="row">
                                <!-- Column 1: Liabilities -->
                                <div class="col-md-6">
                                    <h5>Liabilities and assets</h5>
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
                            <a href="{{ route('admin.loan.previousStep') }}" class="btn" style="background-color: #ed1d7e; color: white;"  onclick="storeStep({{ $currentStep - 1 }})">Previous</a>
                        @endif
                        @if($currentStep == 7)
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
    /* For Chrome, Safari, Edge, and Opera */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* For Firefox */
input[type="number"] {
    -moz-appearance: textfield;
}


.loan-type-card {
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease-in-out;
    padding: 15px;
    text-align: center;
    height: 100%;
}

.loan-type-card img {
    width: 50px;
    height: 50px;
    object-fit: contain;
    margin-bottom: 10px;
}

.loan-label {
    font-size: 14px;
    font-weight: 600;
    margin-top: 8px;
}

.loan-type-card.active-loan {
    border-color: #3b68b2 !important;
    background-color: rgba(59, 104, 178, 0.1);
}

.checkmark {
    font-size: 12px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Move Next button to bottom right */
.d-flex.justify-content-between {
    justify-content: flex-end !important;
}

.step-nav {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 110px;
    min-height: 80px;
    padding: 10px;
    border-radius: 8px;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.step-item.active {
    background-color: #3b68b2;
    color: white;
}

.step-number {
    width: 32px;
    height: 32px;
    background-color: #dee2e6;
    color: #495057;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 6px;
}

.step-item.active .step-number {
    background-color: white;
    color: #3b68b2;
}

.step-title {
    font-size: 12px;
    font-weight: 500;
    color: inherit;
    word-break: break-word;
    text-align: center;
}

 .table-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 0 auto;
        width: 90%;
        display: flex;
        flex-direction: column;
    }


     .loan-option:hover {
        background-color: #3b68b2 !important;
        color: white;
    }

    .loan-option:hover .loan-label {
        color: white;
    }

    .loan-option:hover img {
        filter: brightness(0.9);
    }

</style>
@endsection

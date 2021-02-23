<style>
    .form-applynow h5 {
        padding: 5px 15px;
        background-color: #a5dae4;
    }
    .form-group > p { margin-bottom: 8px!important; }
    .quick-survey-source-item { margin-bottom: 10px; }
</style>
@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Applicant Registration') }}</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin-bottom: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('applicants.store') }}" method="POST" class="form-applynow container bg-white mx-auto">
                        @csrf
                        @method('POST')
                        <div>
                            <h5 class="mb-3 mt-3 d-block">
                                Log-in Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p class="m-0 text-muted">E-mail:<span style="color:red;">* (You must enter a VALID e-mail address as your username)</span></p>
                                        <input class="form-control" name="email" type="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <p class="m-0 text-muted">Alternative E-mail: </p>
                                        <p style="color:red;font-size:10px; line-height: 1.2">(Due to the security and communication related issues we do NOT recommend using YAHOO email address to create an account.)</p>
                                        <input class="form-control" name="alternate_email" type="email" value="{{ old('alternate_email') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p class="m-0 text-muted">Password: <span style="color:red;">* (6 characters or more)</span></p>
                                        <input class="form-control password" name="password" type="password" required>
                                    </div>
                                    <div class="form-group">
                                        <p class="m-0 text-muted">Re-type Password: <span style="color:red;">*</span></p>
                                        <input class="form-control password" name="confirm_password" type="password" required>
                                        <a href="javascript:void(0)" class="btn btn-info show-hide-password" style="margin-top: 5px;" title="Show Hide"><i class="fas fa-eye-slash"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-warning generate-password" style="color: #fff; margin-top: 5px;" title="Generate Password"><i class="fas fa-key"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <p class="mb-0 text-muted">Upload your Image here:</p>
                                    <div class="profile-image-upload"></div>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="form-control-file custom-file-input" id="profile-image" name="image" accept=".jpg, .gif, .png">
                                            <label class="custom-file-label" for="profile-image">Choose Files</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 form-group">
                                    <p class="mb-0 text-muted">First Name:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="first_name" type="text" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="col-md-2 form-group">
                                    <p class="mb-0 text-muted">Middle Name:</p>
                                    <input class="form-control" name="middle_name" type="text" value="{{ old('middle_name') }}"> 
                                </div>
                                <div class="col-md-5 form-group">
                                    <p class="mb-0 text-muted">Last Name:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="last_name" type="text" value="{{ old('last_name') }}" required> 
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-3 mt-5 d-block">
                                Personal Information
                            </h5>
                            <div class="row">
                               
                                <div class="col-md-4 form-group">
                                    <p class="mb-0 text-muted">Mother's Maiden Name:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="mother_name" type="text" value="{{ old('mother_name') }}"> 
                                </div>

                                <div class="col-md-4 form-group">
                                    <p class="mb-0 text-muted">Birth Date:<span style="color:red;">*</span></p>
                                    <input class="form-control date_picker_js" name="birth_date" type="text" value="{{ old('birth_date') }}" autocomplete="off">
                                </div>
                                <div class="col-md-4 form-group">
                                    <p class="mb-0 text-muted">Marital Status:</p>
                                    <select class="form-control" name="marital_status" id="marital_status">
                                        <option value="null" disabled="" selected="">Select a Status</option>
                                        <option value="Married">Married</option>
                                        <option value="Single">Single</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                                <div class="col-md-12 form-group">
                                    <p class="mb-0 text-muted">Address:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="home_address" type="text" value="{{ old('home_address') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <p class="mb-0 text-muted">City:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="city" type="text" value="{{ old('city') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <p class="mb-0 text-muted">Zip/Postal Code:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="postal_code" type="text" value="{{ old('postal_code') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <p class="mb-0 text-muted">State:<span>(U.S. Residents only)</span></p>
                                    <select name="state" class="form-control">
                                        <option value="Select a state" disabled="" selected="">Select a state</option>
                                        <option value="Alabama">Alabama</option>
                                        <option value="Alaska">Alaska</option>
                                        <option value="American Samoa">American Samoa</option>
                                        <option value="Arizona">Arizona</option>
                                        <option value="Arkansas">Arkansas</option>
                                        <option value="California">California</option>
                                        <option value="Colorado">Colorado</option>
                                        <option value="Connecticut">Connecticut</option>
                                        <option value="Delaware">Delaware</option>
                                        <option value="Dist. of Columbia">Dist. of Columbia</option>
                                        <option value="Florida">Florida</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Guam">Guam</option>
                                        <option value="Hawaii">Hawaii</option>
                                        <option value="Idaho">Idaho</option>
                                        <option value="Illinois">Illinois</option>
                                        <option value="Indiana">Indiana</option>
                                        <option value="Iowa">Iowa</option>
                                        <option value="Kansas">Kansas</option>
                                        <option value="Kentucky">Kentucky</option>
                                        <option value="Louisiana">Louisiana</option>
                                        <option value="Maine">Maine</option>
                                        <option value="Maryland">Maryland</option>
                                        <option value="Marshall Islands">Marshall Islands</option>
                                        <option value="Massachusetts">Massachusetts</option>
                                        <option value="Michigan">Michigan</option>
                                        <option value="Micronesia">Micronesia</option>
                                        <option value="Minnesota">Minnesota</option>
                                        <option value="Mississippi">Mississippi</option>
                                        <option value="Missouri">Missouri</option>
                                        <option value="Montana">Montana</option>
                                        <option value="Nebraska">Nebraska</option>
                                        <option value="Nevada">Nevada</option>
                                        <option value="New Hampshire">New Hampshire</option>
                                        <option value="New Jersey">New Jersey</option>
                                        <option value="New Mexico">New Mexico</option>
                                        <option value="New York">New York</option>
                                        <option value="North Carolina">North Carolina</option>
                                        <option value="North Dakota">North Dakota</option>
                                        <option value="Northern Marianas">Northern Marianas</option>
                                        <option value="Ohio">Ohio</option>
                                        <option value="Oklahoma">Oklahoma</option>
                                        <option value="Oregon">Oregon</option>
                                        <option value="Palau">Palau</option>
                                        <option value="Pennsylvania">Pennsylvania</option>
                                        <option value="Puerto Rico">Puerto Rico</option>
                                        <option value="Rhode Island">Rhode Island</option>
                                        <option value="South Carolina">South Carolina</option>
                                        <option value="South Dakota">South Dakota</option>
                                        <option value="Tennessee">Tennessee</option>
                                        <option value="Texas">Texas</option>
                                        <option value="Utah">Utah</option>
                                        <option value="Vermont">Vermont</option>
                                        <option value="Virginia">Virginia</option>
                                        <option value="Virgin Islands">Virgin Islands</option>
                                        <option value="Washington">Washington</option>
                                        <option value="West Virginia">West Virginia</option>
                                        <option value="Wisconsin">Wisconsin</option>
                                        <option value="Wyoming">Wyoming</option>
                                        <option value="BRITISH COLUMBIA">BRITISH COLUMBIA</option>
                                        <option value="Saskatchewan">Saskatchewan</option>
                                        <option value="Manitoba">Manitoba</option>
                                        <option value="Ontario">Ontario</option>
                                        <option value="Quebec">Quebec</option>
                                        <option value="New Brunkswick">New Brunkswick</option>
                                        <option value="Nova Scotia">Nova Scotia</option>
                                        <option value="Price Edward Island">Price Edward Island</option>
                                        <option value="Northwest Territories">Northwest Territories</option>
                                        <option value="Yukon">Yukon</option>
                                        <option value="Quebec">Quebec</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <p class="mb-0 text-muted">Country:<span style="color:red;">*</span></p>
                                    <select name="country" class="form-control" required>
                                        <option selected="" disabled="">Select a Country</option>                                
                                        @foreach($countries as $country)
                                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 row form-group">
                                    <div class="col-md-6">
                                        <p class="mb-0 text-muted">U.S Social Security No.:<span>(U.s. Citizen and Immigrant Only)</span></p>
                                        <input name="us_social_security_number" type="text" class="form-control" value="{{ old('US_social_security_number') }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-12 form-group">
                                    <p class="mb-0 text-muted">Bachelor Degree School Name:</p>
                                    <input class="form-control" name="school_name" type="text" value="{{ old('school_name') }}">
                                </div>
                                <div class="col-md-12 form-group">
                                    <p class="mb-0 text-muted">Bachelor Degree Graduation Date:</p>
                                    <input class="form-control" name="nursing_graduation_date" type="text" value="{{ old('nursing_graduation_date') }}">
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-3 mt-5 d-block">
                                Contact Numbers
                            </h5>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <p class="m-0 text-muted">Phone No.:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="telephone_number" type="text" value="{{ old('telephone_number') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <p class="m-0 text-muted">Mobile No.:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="mobile_number" type="text" value="{{ old('mobile_number') }}" required>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-3 mt-5 d-block">
                                Other information
                            </h5>
                            <div class="row">
                            <div class="col-md-6">
                                    <p class="m-0 text-muted">How did you hear about us?<span style="color:red;">*</span></p>
                                    <div class="quick-survey-source-item">
                                        <input type="radio" name="quick_survey_source" id="Facebook" value="Facebook">&nbsp;&nbsp;Facebook
                                    </div>
                                    <div class="quick-survey-source-item">
                                        <input type="radio" name="quick_survey_source" id="Flyers" value="Flyers">&nbsp;&nbsp;Flyers
                                    </div>
                                    <div class="quick-survey-source-item">
                                        <input type="radio" name="quick_survey_source" id="Internet_Google_Search" value="Internet Google Search">&nbsp;&nbsp;Internet Google Search
                                    </div>
                                    <div class="quick-survey-source-item">
                                        <input type="radio" name="quick_survey_source" id="Your_Friends_Relatives" value="Your Friends Relatives">&nbsp;&nbsp;Your Friends/Relatives
                                    </div>
                                    <div class="quick-survey-source-item">
                                        <input type="radio" name="quick_survey_source" id="Billboard_Across_PRC" value="Billboard Across PRC">&nbsp;&nbsp;Billboard Across PRC
                                    </div>
                                    <div class="quick-survey-source-item row">
                                        <div class="col-md-4">
                                            <input type="radio" name="quick_survey_source" id="Review_Center" value="Review Center">&nbsp;&nbsp;Review Center 
                                        </div>
                                        <div class="col-md-8">
                                            <select name="Review_Center_desc" id="Review_Center_desc" class="form-control">
                                                <option value=""></option>
                                                <option value="Carl Balita Review">Carl Balita Review</option>
                                                <option value="East West Review">East West Review</option>
                                                <option value="Gapuz Review">Gapuz Review</option>
                                                <option value="International Academy Manila Review">International Academy Manila Review</option>
                                                <option value="Kaplan Review">Kaplan Review</option>
                                                <option value="Merge Review">Merge Review</option>
                                                <option value="NEAC Applicant">NEAC Applicant</option>
                                                <option value="Niner Review">Niner Review</option>
                                                <option value="OTHER Review Center">OTHER Review Center</option>
                                                <option value="PRN Review">PRN Review</option>
                                                <option value="Rachell Allen Review">Rachell Allen Review</option>
                                                <option value="Saint Louis Review">Saint Louis Review</option>
                                                <option value="SRG Review">SRG Review</option>
                                                <option value="Top Rank Review">Top Rank Review</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="quick-survey-source-item row">
                                        <div class="col-md-4">
                                            <input type="radio" name="quick_survey_source" id="Recruitment_Agency" value="Recruitment Agency">&nbsp;&nbsp;Recruitment Agency
                                        </div>
                                        <div class="col-md-8">
                                            <select name="Recruitment_Agency_desc" id="Recruitment_Agency_desc" class="form-control">
                                                <option value=""></option>
                                                <option value="Access Global International Manpower Services">Access Global International Manpower Services</option>
                                                <option value="Grand Placement Agency">Grand Placement Agency</option>
                                                <option value="IKON Recruitment Agency">IKON Recruitment Agency</option>
                                                <option value="International Service Development ">International Service Development </option>
                                                <option value="LNS International Manpower Services Corp">LNS International Manpower Services Corp</option>
                                                <option value="Magsaysay Agency">Magsaysay Agency</option>
                                                <option value="Manpower Alliance Corp">Manpower Alliance Corp</option>
                                                <option value="OTHER Recruitment Agency">OTHER Recruitment Agency</option>
                                                <option value="RPR International Recruitment Agency">RPR International Recruitment Agency</option>
                                                <option value="Silver Skilled Recruitment Inc">Silver Skilled Recruitment Inc</option>
                                                <option value="Stronghold Manpower International Recruitment Agency Corporation">Stronghold Manpower International Recruitment Agency Corporation</option>
                                                <option value="Subnet Recruitment Agency">Subnet Recruitment Agency</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="quick-survey-source-item">
                                        <input type="radio" name="quick_survey_source" id="Walk_In_Applicant" value="Walk In Applicant">&nbsp;&nbsp;Walk In applicants                                        
                                    </div>
                                    <div class="quick-survey-source-item">
                                        <input type="radio" name="quick_survey_source" id="Hospital" value="Hospital">&nbsp;&nbsp;Hospital
                                    </div>
                                    <div class="quick-survey-source-item">
                                        <input type="radio" name="quick_survey_source" id="Expo_Seminar" value="Expo/Seminar">&nbsp;&nbsp;Expo/Seminar <br>
                                    </div>
                                    <div class="quick-survey-source-item row">
                                        <div class="col-md-4">
                                            <input type="radio" name="quick_survey_source" id="BPO_Call_Center" value="BPO/Call Center">&nbsp;&nbsp;BPO/Call Center
                                        </div>
                                        <div class="col-md-8">
                                            <select name="BPO_Call_Center_desc" id="BPO_Call_Center_desc" class="form-control">
                                                <option value=""></option>
                                                <option value="Accenture">Accenture</option>
                                                <option value="COGNIZANT">COGNIZANT</option>
                                                <option value="EXL">EXL</option>
                                                <option value="GENPACT">GENPACT</option>
                                                <option value="OTHER BPO">OTHER BPO</option>
                                                <option value="United Health Group (UHG)">United Health Group (UHG)</option>
                                                <option value="WIPRO">WIPRO</option>
                                            </select>
                                        </div>
                                    </div>
                            
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p class="m-0 text-muted">Payment Status</p>
                                        <select name="payment_status" class="form-control">
                                            <option>Select</option>
                                            <option value="0">Not Paid</option>
                                            <option value="1">Paid</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <p class="m-0 text-muted">NEAC services you are applying for:<span style="color:red;">*</span></p>
                                        <select name="applying_for" class="form-control" required>
                                            <option value="Select a Service" disabled="" selected="">Select a Service</option>
                                            <option value="NCLEX-RN(USA) Exam">NCLEX-RN(USA) Exam</option>
                                            <option value="NCLEX-PN(USA) Exam">NCLEX-PN(USA) Exam</option>
                                            <option value="US PT(USA) Exam">US PT(USA) Exam</option>
                                            <option value="HAAD(ABU DHABI) Exam">HAAD(ABU DHABI) Exam</option>
                                            <option value="DHA(DUBAI) Exam">DHA(DUBAI) Exam</option>
                                            <option value="SAUDI EXAM">SAUDI EXAM</option>
                                            <option value="QATAR EXAM">QATAR EXAM</option>
                                            <option value="OMAN EXAM">OMAN EXAM</option>
                                            <option value="IELTS EXAM">IELTS EXAM</option>
                                            <option value="TOEFL EXAM">TOEFL EXAM</option>
                                            <option value="PEARSON TEST OF ENGLISH EXAM">PEARSON TEST OF ENGLISH EXAM</option>
                                            <option value="PRC LICENSE RENEWAL">PRC LICENSE RENEWAL</option>
                                            <option value="USRN LICENSE RENEWAL">USRN LICENSE RENEWAL</option>
                                            <option value="USRN LICENSE BY ENDORSEMENT">USRN LICENSE BY ENDORSEMENT</option>
                                            <option value="VISA SCREEN APPLICATION">VISA SCREEN APPLICATION</option>
                                            <option value="NCLEX-RN (Canada) Exam">NCLEX-RN (Canada) Exam</option>
                                            <option value="NMC-UK Exam">NMC-UK Exam</option>
                                            <option value="MNBI Application">MNBI Application</option>
                                            <option value="U.S Medical Coder Exam">U.S Medical Coder Exam</option>
                                            <option value="SAUDI Dataflow">SAUDI Dataflow</option>
                                            <option value="QATAR Dataflow">QATAR Dataflow</option>
                                            <option value="OMAN Dataflow">OMAN Dataflow</option>
                                            <option value="Medical Coding Exam">Medical Coding Exam</option>
                                            <option value="MOH Exam">MOH Exam</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <p class="m-0 text-muted">State Applying to: </p>
                                        <select name="state_applied" class="form-control">
                                            <option value="Select a State" disabled="" selected="">Select a State</option>
                                            <option value="Not Applicable">Not Applicable</option>
                                            <option value="Alabama">Alabama</option>
                                            <option value="Alaska">Alaska</option>
                                            <option value="American Samoa">American Samoa</option>
                                            <option value="Arizona">Arizona</option>
                                            <option value="Arkansas">Arkansas</option>
                                            <option value="California">California</option>
                                            <option value="Colorado">Colorado</option>
                                            <option value="Connecticut">Connecticut</option>
                                            <option value="Delaware">Delaware</option>
                                            <option value="Dist. of Columbia">Dist. of Columbia</option>
                                            <option value="Florida">Florida</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Guam">Guam</option>
                                            <option value="Hawaii">Hawaii</option>
                                            <option value="Idaho">Idaho</option>
                                            <option value="Illinois">Illinois</option>
                                            <option value="Indiana">Indiana</option>
                                            <option value="Iowa">Iowa</option>
                                            <option value="Kansas">Kansas</option>
                                            <option value="Kentucky">Kentucky</option>
                                            <option value="Louisiana">Louisiana</option>
                                            <option value="Maine">Maine</option>
                                            <option value="Maryland">Maryland</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Massachusetts">Massachusetts</option>
                                            <option value="Michigan">Michigan</option>
                                            <option value="Micronesia">Micronesia</option>
                                            <option value="Minnesota">Minnesota</option>
                                            <option value="Mississippi">Mississippi</option>
                                            <option value="Missouri">Missouri</option>
                                            <option value="Montana">Montana</option>
                                            <option value="Nebraska">Nebraska</option>
                                            <option value="Nevada">Nevada</option>
                                            <option value="New Hampshire">New Hampshire</option>
                                            <option value="New Jersey">New Jersey</option>
                                            <option value="New Mexico">New Mexico</option>
                                            <option value="New York">New York</option>
                                            <option value="North Carolina">North Carolina</option>
                                            <option value="North Dakota">North Dakota</option>
                                            <option value="Northern Marianas">Northern Marianas</option>
                                            <option value="Ohio">Ohio</option>
                                            <option value="Oklahoma">Oklahoma</option>
                                            <option value="Oregon">Oregon</option>
                                            <option value="Palau">Palau</option>
                                            <option value="Pennsylvania">Pennsylvania</option>
                                            <option value="Puerto Rico">Puerto Rico</option>
                                            <option value="Rhode Island">Rhode Island</option>
                                            <option value="South Carolina">South Carolina</option>
                                            <option value="South Dakota">South Dakota</option>
                                            <option value="Tennessee">Tennessee</option>
                                            <option value="Texas">Texas</option>
                                            <option value="Utah">Utah</option>
                                            <option value="Vermont">Vermont</option>
                                            <option value="Virginia">Virginia</option>
                                            <option value="Virgin Islands">Virgin Islands</option>
                                            <option value="Washington">Washington</option>
                                            <option value="West Virginia">West Virginia</option>
                                            <option value="Wisconsin">Wisconsin</option>
                                            <option value="Wyoming">Wyoming</option>
                                            <option value="British Columbia">British Columbia</option>
                                            <option value="Alberta">Alberta</option>
                                            <option value="Saskatchewan">Saskatchewan</option>
                                            <option value="Manitoba">Manitoba</option>
                                            <option value="Ontario">Ontario</option>
                                            <option value="Quebec">Quebec</option>
                                            <option value="New Brunkswick">New Brunkswick</option>
                                            <option value="Nova Scotia">Nova Scotia</option>
                                            <option value="Price Edward Island">Price Edward Island</option>
                                            <option value="Northwest Territories">Northwest Territories</option>
                                            <option value="Yukon">Yukon</option>
                                            <option value="Quebec">Quebec</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div>
                            <h5 class="mb-3 mt-5 d-block">
                                REFERRED BY: If the applicant is referred by Client. ENDORSED BY: If we have referred the applicant to the client.
                            </h5>
                            <div class="row">
                                <div class="row col-md-12">
                                    <div class="col-md-6 form-group">
                                        <p class="mb-0 text-muted">Agency/BPO/Review Center Referred By:</p>
                                        <select name="referred_by" class="select2 form-control">
                                            <option disabled selected>Select</option>
                                            @foreach($resellers as $reseller)
                                                <option value="{{ $reseller->id }}">{{ $reseller->first_name . ' ' . $reseller->last_name }}</option>
                                            @endforeach 
                                        </select>
                                    </div>
                                </div>
                                <div class="row col-md-12">
                                    <div class="col-md-6 form-group">
                                        <p class="mb-0 text-muted">To avail a discount enter your promocode here:</p>
                                        <input class="form-control" name="reseller_code" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-primary font-sm" type="submit" name="submit">Register</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection
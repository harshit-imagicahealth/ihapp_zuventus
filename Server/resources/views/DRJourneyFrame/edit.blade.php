@extends('DRJourneyFrame.layouts.main')

@push('css')
@endpush

@section('main')
    <link rel="stylesheet" href="{{ asset('public/css/drstyle.css') }}">
    <div id="ipadImageContainer" class="vh-100 w-100 position-relative d-none overflow-hidden">

        <!-- Image 1 -->
        <div class="ipadSlide">
            <img class="ipadImage img-fluid vw-100 vh-100" src="{{ asset('public/images/ipad_page_1.png') }}"
                data-ipad-image="1">
        </div>

        <!-- Image 2 with Button -->
        <div class="ipadSlide d-none position-relative">
            <img class="ipadImage img-fluid vw-100 vh-100" src="{{ asset('public/images/ipad_page_2.png') }}"
                data-ipad-image="2">

            <!-- Custom Button on Image -->
            <button type="button" id="ipadRegisterBtn" class="custom-img-btn">
                <img src="{{ asset('public/images/ipad_register_btn.png') }}" alt="Action">
            </button>
        </div>

        <!-- Previous Button -->
        <button type="button" id="ipadPrevBtn" class="nav-btn d-none ipadPrevBtn" data-ipad-page="1">
            <img src="{{ asset('public/images/ipad_prev_btn.png') }}" alt="Prev">
        </button>

        <!-- Next Button -->
        <button type="button" id="ipadNextBtn" class="nav-btn ipadNextBtn" data-ipad-page="2">
            <img src="{{ asset('public/images/ipad_next_btn.png') }}" alt="Next">
        </button>

    </div>
    <div id="formContanier" class="container mt-5 pb-5">
        <!-- FIELDSET 1: Enter Details -->
        <fieldset class="active mb-5">
            <h3 class="fw-medium title_section mb-5 text-center">Enter Doctor Details</h3>

            <div class="mb-3">
                <label class="custom_label mb-3" for="name">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Name of Doctor <span class="text-danger">*</span>
                </label>
                <input type="text" id="name" name="name" placeholder="Enter  Doctor Name without Dr. Prefix"
                    class="form-control custom_field" maxlength="25" required
                    value="{{ isset($data) ? $data?->name : '' }}">
            </div>
            <div class="mb-3">
                <label class="custom_label mb-3" for="city">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    City Name <span class="text-danger">*</span>
                </label>
                <input type="text" id="city" name="city" placeholder="Enter City Name"
                    class="form-control custom_field" maxlength="25" value="{{ isset($data) ? $data?->city : '' }}">
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3" for="speciality">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Speciality <span class="text-danger">*</span>
                </label>
                <input type="text" id="speciality" name="speciality" placeholder="Enter Speciality"
                    class="form-control custom_field" maxlength="25" value="{{ isset($data) ? $data?->speciality : '' }}">
            </div>
            <div class="mb-3">
                <label class="custom_label mb-3" for="mi_oci_id">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    MI Unnati/OCE ID <span class="text-danger">*</span>
                </label>
                <input type="text" id="mi_oci_id" name="mi_oci_id" placeholder="Enter MI Unnati/OCE ID"
                    class="form-control custom_field" maxlength="25" required
                    value="{{ isset($data) ? $data?->mi_oci_id : '' }}">
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3" for="mobile">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Mobile Numberb <span class="text-danger">*</span>
                </label>
                <input type="number" id="mobile" name="mobile" placeholder="Enter Mobile No"
                    class="form-control custom_field" maxlength="10" oninput="this.value = this.value.slice(0, 10)"
                    value="{{ isset($data) ? $data?->mobile : '' }}">
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Gender <span class="text-danger">*</span>
                </label>
                <div class="radio-group square-radio">
                    <label class="square-radio-option">
                        <input type="radio" name="gender" value="male"
                            {{ isset($data) ? ($data?->gender == 'male' ? 'checked' : '') : '' }}>
                        <span class="square-box"><i class="fa-solid fa-check"></i></span>
                        <span class="square-label">Male</span>
                    </label>
                    <label class="square-radio-option">
                        <input type="radio" name="gender" value="female"
                            {{ isset($data) ? ($data?->gender == 'female' ? 'checked' : '') : '' }}>
                        <span class="square-box"><i class="fa-solid fa-check"></i></span>
                        <span class="square-label">Female</span>
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Select Language <span class="text-danger">*</span>
                </label>

                @php
                    $languages = [
                        'English',
                        'Hindi',
                        'Marathi',
                        'Gujarati',
                        'Kannada',
                        'Telugu',
                        'Tamil',
                        'Malayalam',
                        'Odiya',
                        'Bengali',
                        // 'Punjabi',
                        'Assamese',
                    ];
                @endphp

                <select name="language" class="form-control">
                    <option value="">-- Select Language --</option>
                    @foreach ($languages as $lang)
                        <option value="{{ $lang }}"
                            {{ isset($data) ? ($data?->language == $lang ? 'selected' : '') : '' }}>
                            {{ $lang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn previous prev" data-id="0">Previous</button>
                <button type="button" class="btn next" data-id="1">Next</button>
            </div>
        </fieldset>
        <!-- FIELDSET 2: Terms and Conditions -->
        <fieldset>
            <h3 class="fw-medium title_section mb-5 text-center">Consent Page</h3>
            <div id="consent_page" class="consent-box mx-3"></div> {{-- style="text-align:justify;" --}}

            <div class="radio-group square-radio mx-3 my-3">

                <label class="square-radio-option">
                    <input type="checkbox" id="agree-checkbox" name="agreeCheckBox" value="1">
                    <span class="square-box"><i class="fa-solid fa-check"></i></span>
                    <span class="square-label">I agree with this terms</span>
                </label>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn previous prev" data-id="1">Previous</button>
                <button type="button" class="btn next" data-id="2">Next</button>
            </div>
        </fieldset>

        <!-- FIELDSET 3: Upload Photograph -->
        <fieldset>
            <h3 class="fw-medium title_section mb-5 text-center">Upload Photograph</h3>

            <div class="mb-3">
                <label class="custom_label mb-2">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">Upload Photo
                </label>

                <input type="file" id="photo_1" class="form-control custom_field" accept="image/*">

                <div class="row justify-content-center align-items-start mt-3">

                    <!-- LEFT: Cropper -->
                    <div id="cropper_main" class="col-md-6 mx-auto">
                        <div id="cropperBox" class="mx-auto">
                            <img id="cropImage"
                                src="{{ isset($data) ? ($data?->photo ? $data->photo : asset('public/images/img_sales.png')) : '' }}"
                                alt="">
                        </div>
                    </div>

                    <!-- RIGHT: Preview -->
                    <div id="preview_main" class="col-md-6 mx-auto">
                        <h5 id="preview_text" class="text-center">Preview Image</h5>
                        <img id="preview_1" class="mx-auto"
                            src="{{ isset($data) ? ($data?->photo ? $data->photo : asset('public/images/img_sales.png')) : '' }}"
                            width="280" style="display:block;">
                    </div>

                </div>

                <div class="d-flex justify-content-center mt-3">
                    <button type="button" id="cropPhotoBtn" class="btn btn-success" style="display:none;">
                        <i class="fa fa-crop"></i> Crop Photo
                    </button>
                </div>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn previous prev" data-id="2">Previous</button>
                <button type="button" class="btn next" data-id="3">Next</button>
            </div>
        </fieldset>

        <!-- FIELDSET 4: verify dr details -->
        <fieldset>
            @php
                $isReadonly = true;
            @endphp

            <h3 class="fw-medium title_section mb-5 text-center">Verify Doctor Details</h3>

            <div class="mb-3">
                <label class="custom_label mb-3"> <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}"
                        width="21">Name Of Doctor</label>
                <input type="text" id="verify_name" name="verify_name" @class(['form-control custom_field', 'bg-light' => $isReadonly]) value=""
                    @readonly($isReadonly) @disabled($isReadonly)>
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3"> <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}"
                        width="21"> City Name</label>
                <input type="text" id="verify_city" name="verify_city" @class(['form-control custom_field', 'bg-light' => $isReadonly]) value=""
                    @readonly($isReadonly) @disabled($isReadonly)>
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Specialty</label>
                <input type="text" id="verify_speciality" name="verify_speciality" @class(['form-control custom_field', 'bg-light' => $isReadonly])
                    value="" @readonly($isReadonly) @disabled($isReadonly)>
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3">MI Unnati/OCE ID</label>
                <input type="text" id="verify_mi_oci_id" name="verify_mi_oci_id" @class(['form-control custom_field', 'bg-light' => $isReadonly])
                    value="" @readonly($isReadonly) @disabled($isReadonly)>
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3"> <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}"
                        width="21"> Mobile Number</label>
                <input type="text" id="verify_mobile" name="verify_mobile" @class(['form-control custom_field', 'bg-light' => $isReadonly])
                    value="" @readonly($isReadonly) @disabled($isReadonly)>
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Gender
                </label>
                <div class="radio-group square-radio varify-squre-radio">
                    <label class="square-radio-option">
                        <input type="radio" id="verify_gender_male" name="verify_gender" value="male"
                            @class(['form-control custom_field', 'bg-light' => $isReadonly])>
                        <span class="square-box"><i class="fa-solid fa-check"></i></span>
                        <span class="square-label">Male</span>
                    </label>
                    <label class="square-radio-option">
                        <input type="radio" id="verify_gender_female" name="verify_gender" value="female"
                            @class(['form-control custom_field', 'bg-light' => $isReadonly])>
                        <span class="square-box"><i class="fa-solid fa-check"></i></span>
                        <span class="square-label">Female</span>
                    </label>
                </div>
            </div>

            {{-- <div class="mb-3">
                <label class="custom_label mb-3">Language</label>
                <input type="text" id="verify_language" name="verify_language" @class(['form-control custom_field', 'bg-light' => $isReadonly])
                    value="" @readonly($isReadonly) @disabled($isReadonly)>
            </div> --}}

            <div class="radio-group square-radio mx-0 my-3">

                <label class="square-radio-option">
                    <input type="checkbox" id="varify-agree-checkbox" name="varifyAgreeCheckBox" value="1">
                    <span class="square-box"><i class="fa-solid fa-check"></i></span>
                    <span class="square-label varify_detail_checkbox"></span>
                </label>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn previous prev" data-id="3">Previous</button>
                <button type="button" class="btn next" data-id="4">Submit</button>
            </div>
        </fieldset>
    </div>
@endsection

@push('script')
    <script>
        window.consentPage =
            `To,
Titans
Dr Reddy’s Laboratories
289, Bellasis Rd, Opposite Sahil Hotel, Navjeevan Society, Dalal Estate, Mumbai Central,
Mumbai, Maharashtra 400008.
 
I <b>Dr. {name}</b>, resident of <b>{city}</b>, specialized as <b>{speciality}</b>, by filling-in and submitting this form, I confirm that I have shared my personal information on a voluntary basis and out of my self-interest to participate in the social media activity through Spotify / YouTube / Radio by Dr Reddy’s for the public to spread awareness. I understand that for the purpose of this aforesaid program, my personal data is submitted on a voluntary basis under this form. I agree and understand that my video / audio may be recorded  and I agree that I will be liable for any opinion shared by me in such videos / audios and I will take full responsibility for the same. If selected, I understand that I may be requested to share such personal information at my sole discretion with fellow-participants for the purposes as above. I note that submission of my personal details under this form and my audio / video shared by me under this program shall not guarantee my participation in the Program and that Dr Reddy’s shall have sole discretion in this regard. I understand that the work of creating the material, suitably editing it and finalization of the same has been entrusted to a third party agency who shall bear the responsibility of the final material. I understand that any personal data shared by me herein or during my participation at the Program shall be subject to the Data Privacy Notice available at https://www.drreddys.com/cms/cms/sites/default/files/static/data-privacy-notice.pdf and Dr Reddy’s shall have no liability or responsibility, monetary or otherwise, for or in relation to the collection, use or application of the data shared by me or any inferences or analysis constructed or derived therefrom.
I hereby agree that Dr. Reddy’s shall be free to disseminate final material on any forum including any social media platform. I understand that to the extent any post is made on social media, it may not be retrievable or deleted and that I will be fully responsible for all the consequences if the video is posted by me on any forum including the social media platform.`;


        $(document).ready(function() {

            // consent page vars
            const consentPage = window.consentPage;
            let finalConsentPage = '';

            let cropper = null;
            let croppedPhoto1File = null;
            let hasExistingPhoto = "{{ $data->photo ? 'true' : 'false' }}";
            let originalPhotoDataUrl = null;
            const encryptedId = "{{ encrypt($data->id) }}";


            // ============================================
            // IMAGE CROPPING LOGIC
            // ============================================

            function destroyCropper() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            }

            function initCropper() {
                destroyCropper();
                let image = document.getElementById("cropImage");
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    restore: false,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false
                });
            }

            $('#cropperBox').hide();
            $('#preview_main').hide();
            $("#cropPhotoBtn").hide();

            $("#photo_1").on("change", function(e) {
                let file = e.target.files[0];
                if (!file) return;

                let reader = new FileReader();
                reader.onload = function(ev) {
                    originalPhotoDataUrl = ev.target.result;

                    $("#preview_main").show();
                    $("#preview_1").hide();
                    $("#cropperBox").show();
                    $("#cropPhotoBtn").show();
                    $("#cropImage").attr("src", originalPhotoDataUrl);

                    setTimeout(() => {
                        initCropper();
                    }, 200);
                };
                reader.readAsDataURL(file);
            });

            // ============================================
            // CROP BUTTON CLICK
            // ============================================

            $("#prv_im_b").hide();
            $("#cropPhotoBtn").on("click", function() {

                if (!cropper) {
                    return Swal.fire("Error", "Cropper not ready", "error");
                }

                $('.main_loader').show();

                cropper.getCroppedCanvas({
                    width: 900,
                    height: 900,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                }).toBlob(function(blob) {

                    croppedPhoto1File = new File([blob], "photo_1.png", {
                        type: "image/png"
                    });

                    // SHOW CROPPED PREVIEW
                    $("#preview_1").attr("src", URL.createObjectURL(blob)).show();
                    $("#prv_im_b").show();



                    $('.main_loader').hide();



                }, "image/png");

            });

            // ============================================
            // FORM NAVIGATION
            // ============================================

            function goToStep(step) {
                $("fieldset").removeClass("active");
                $("fieldset").eq(step - 1).addClass("active");

                // if (step === 1) {
                //     $("fieldset").eq(0).find(".prev").hide();
                // } else {
                $("fieldset").eq(step - 1).find(".prev").show();
                // }
                //  FIX: Restore cropper image when returning to step 2
                if (step === 3 && originalPhotoDataUrl) {
                    $("#cropImage").attr("src", originalPhotoDataUrl);
                    $("#cropperBox").show();

                    setTimeout(() => {
                        initCropper();
                    }, 300);
                }
            }


            $(document).on("click", ".prev", function() {
                let step = $(this).data("id");
                if (step === 1) {
                    goToStep(1);
                } else if (step === 2) {
                    goToStep(2);
                } else if (step === 3) {
                    goToStep(3);
                }
            });

            $(".next").on("click", async function() {

                let step = $(this).data("id");

                // Step 1: Validate details
                if (step === 1) {
                    let name = $("#name").val().trim();
                    let city = $("#city").val().trim();
                    let speciality = $("#speciality").val().trim();
                    let mi_oci_id = $("#mi_oci_id").val().trim();
                    let mobile = $("#mobile").val().trim();
                    let gender = $("input[name='gender']:checked").val() || "";
                    let language = $("select[name='language']").val() || "";

                    if (!name) return Swal.fire("Required", "Please Enter Doctor Name", "warning");

                    if (!mi_oci_id) return Swal.fire("Required", "Please Enter MI Unnati/OCE ID",
                        "warning");

                    if (!city) return Swal.fire("Required", "Please Enter City Name", "warning");

                    if (!speciality) return Swal.fire("Required", "Please Enter speciality", "warning");

                    if (!mobile) return Swal.fire("Required", "Please Enter Mobile Number", "warning");

                    if (!/^[0-9]{10}$/.test(mobile)) {
                        return Swal.fire("Invalid", "Mobile Number Must Be Exactly 10 Digits",
                            "warning");
                    }

                    // if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    //     return Swal.fire("Invalid", "Please Enter A Valid Email ID", "warning");
                    // }

                    if (!gender) return Swal.fire("Required", "Please Select Gender", "warning");

                    if (!language) return Swal.fire("Required", "Please Select Language", "warning");

                    finalConsentPage = consentPage
                        .replace("{name}", $("#name").val().trim())
                        .replace("{city}", $("#city").val().trim())
                        .replace("{speciality}", $("#speciality").val().trim());

                    $("#consent_page").html(finalConsentPage.replace(/\n/g, "<br>"));

                    // Set values to verify fields for the step 4
                    $("#verify_name").val(name);
                    $("#verify_city").val(city);
                    $("#verify_speciality").val(speciality);
                    $("#verify_mi_oci_id").val(mi_oci_id);
                    $("#verify_mobile").val(mobile);
                    // Disable all options first
                    $("input[name='verify_gender']").prop("disabled", true);
                    // Then check the selected one
                    $("input[name='verify_gender'][value='" + gender + "']").prop("checked", true);

                    // $("#verify_language").val(language);

                    goToStep(2);
                }

                // Step 2: Validate photo
                if (step === 2) {
                    let agreeCheckBox = $("#agree-checkbox")[0];

                    if (!agreeCheckBox.checked) {
                        return Swal.fire("Required", "Please Agree to Terms and Conditions",
                            "warning");
                    }
                    setTimeout(() => {

                        let existingImage = $("#preview_1").attr("src");

                        if (existingImage) {
                            originalPhotoDataUrl = existingImage;
                            $("#cropperBox").show();

                            $("#cropImage").attr("src", existingImage);

                            initCropper();
                        }

                    }, 300);
                    goToStep(3);
                }

                // Step 3: Validate photo
                if (step === 3) {
                    if (!croppedPhoto1File && !hasExistingPhoto) {
                        return Swal.fire("Required", "Please crop photo first", "warning");
                    }

                    let varifyAgreeCheckBox = 'I Dr. ' + $("#name").val().trim() +
                        ', hereby confirm that all details are correct and I approved creating patient education AI video';
                    $('.varify_detail_checkbox').text(varifyAgreeCheckBox)

                    goToStep(4);
                }

                // Step 4: Validate and submit
                if (step === 4) {

                    let vAgreeCheckBox = $("#varify-agree-checkbox")[0];

                    if (!vAgreeCheckBox.checked) {
                        return Swal.fire("Required", "Please Agree to Terms and Conditions",
                            "warning");
                    }
                    $('.main_loader').show();

                    $(this).attr('disabled', true);

                    let fd = new FormData();
                    fd.append("_token", "{{ csrf_token() }}");
                    // Append form fields
                    fd.append("name", $("#name").val().trim());
                    fd.append("city", $("#city").val().trim());
                    fd.append("speciality", $("#speciality").val().trim());
                    fd.append("mi_oci_id", $("#mi_oci_id").val().trim());
                    fd.append("mobile", $("#mobile").val().trim());
                    fd.append("gender", $("input[name='gender']:checked").val());
                    fd.append("language", $("select[name='language']").val());

                    // Append photo
                    if (croppedPhoto1File) {
                        fd.append("photo_1", croppedPhoto1File);
                    }



                    $.ajax({
                        url: "{{ route('dr.journey.update', encrypt($data?->id)) }}",
                        type: "POST",
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            $('.main_loader').hide();
                            if (res.status) {
                                window.location.href = res.url
                            } else {
                                Swal.fire("Error", "Something went wrong please try again!",
                                    "error");
                            }
                        },
                        error: function(xhr) {
                            $('.main_loader').hide();

                            let msg = "Something Went Wrong";

                            if (xhr.responseJSON) {
                                if (xhr.responseJSON.errors) {
                                    msg = Object.values(xhr.responseJSON.errors)[0][0];
                                } else if (xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                            }

                            Swal.fire("Error", msg, "error");
                        }
                    });
                    $(this).attr('disabled', false);
                }

            });

        });
    </script>
@endpush

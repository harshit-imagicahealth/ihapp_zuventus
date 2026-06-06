@extends('DRJourneyFrame.layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('public/css/drstyle.css') }}">
@endpush

@section('main')
    <div id="formContanier" class="container mt-5 pb-5">
        <!-- FIELDSET 1: Enter Details -->
        <fieldset class="active mb-5">
            <h3 class="fw-medium title_section mb-3 text-center">Doctor Details</h3>

            <div class="mb-3">
                <label class="custom_label mb-3">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Select Specialty <span class="text-danger">*</span>
                </label>
                <div class="radio-group square-radio">
                    <label class="square-radio-option">
                        <input name="specialty" type="radio" value="physician"
                            {{ isset($data) ? ($data?->specialty == 'physician' ? 'checked' : '') : '' }}>
                        <span class="square-box"><i class="fa-solid fa-check"></i></span>
                        <span class="square-label">Physicians</span>
                    </label>
                    <label class="square-radio-option">
                        <input name="specialty" type="radio" value="gyn"
                            {{ isset($data) ? ($data?->specialty == 'gyn' ? 'checked' : '') : '' }}>
                        <span class="square-box"><i class="fa-solid fa-check"></i></span>
                        <span class="square-label">Gyn</span>
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3" for="name">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Doctor Name <span class="text-danger">*</span>
                </label>
                <input id="name" name="name" class="form-control custom_field" type="text"
                    value="{{ isset($data) ? $data?->name : '' }}" placeholder="Enter Doctor Name without Dr. Prefix"
                    maxlength="25" required>
            </div>

            <div class="mb-3">
                <label class="custom_label mb-3" for="dr_specialty">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Doctor Specialty <span class="text-danger">*</span>
                </label>
                <input id="dr_specialty" name="dr_specialty" class="form-control custom_field" type="text"
                    value="{{ isset($data) ? $data?->speciality : '' }}" placeholder="Enter Specialty" maxlength="30">
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="custom_label mb-3" for="collage_name">
                        <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                        Name of MBBS College<span class="text-danger">*</span>
                    </label>
                    <input id="collage_name" name="collage_name" class="form-control custom_field" type="text"
                        value="{{ isset($data) ? $data?->collage_name : '' }}"
                        placeholder="e.g.. K J Somaiya Medical College" maxlength="25">
                </div>

                <div class="mb-3 col-md-6">
                    <label class="custom_label mb-3" for="pg_name">
                        <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                        Name of PG Institute<span class="text-danger">*</span>
                    </label>
                    <input id="pg_name" name="pg_name" class="form-control custom_field" type="text"
                        value="{{ isset($data) ? $data?->pg_name : '' }}" placeholder="e.g.. K J Somaiya Medical College"
                        maxlength="25" required>
                </div>

            </div>

            {{-- year of practice --}}
            <div class="mb-3">
                <label class="custom_label mb-3" for="practice_years">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Years of Practice <span class="text-danger">*</span>
                </label>
                <input id="practice_years" name="practice_years" class="form-control custom_field" type="number"
                    value="{{ isset($data) ? $data?->practice_years : '' }}" placeholder="e.g.. 12 years">
            </div>

            {{-- Area of Expertise --}}
            @php
                $phy = [
                    'Hypertension',
                    'Diabetes',
                    'Critical Care',
                    'Respiratory Diseases',
                    'Emergency Medicine',
                    'Geriatric Medicine',
                    'Allergy',
                    'Asthma',
                    'Stroke',
                    'Migraine & Sleep Disorders',
                    'Vaccination',
                    'GI disorders',
                    'Infectious Disease',
                ];
                $gyn = [
                    'Antenatal care and Delivery',
                    'Painless delivery',
                    'Infertility treatment',
                    'Adolescent girl counselling',
                    'Pre-conception counseling',
                    'Treatment of gynae disorders',
                    'Menopause clinic',
                    'Sonography',
                    'Obesity management',
                    'Cosmetic gynecology',
                    'Urogynecology',
                ];
            @endphp
            <div class="mb-3">
                <label class="custom_label mb-3">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Area of Expertise <span class="text-danger">*</span>
                </label>

                <select id="expertise" name="expertise" class="form-control form-select custom_field" multiple
                    max="4">
                    <option value="">-- Select Expertise --</option>
                </select>
            </div>
            <div class="row">

                {{-- Date of Birth --}}
                <div class="mb-3 col-md-6">
                    <label class="custom_label mb-3" for="dob">
                        <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                        Date of Birth <span class="text-danger">*</span>
                    </label>
                    <input id="dob" name="dob" class="form-control custom_field" type="text"
                        value="{{ isset($data) ? $data?->dob : '' }}" placeholder="Select Date of Birth">
                </div>
                <div class="mb-3 col-md-6">
                    <label class="custom_label mb-3" for="mobile_number">
                        <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                        Mobile Number <span class="text-danger">*</span>
                    </label>
                    <input id="mobile_number" name="mobile_number" class="form-control custom_field" type="number"
                        value="{{ isset($data) ? $data?->mobile_number : '' }}" placeholder="Enter Doctor Mobile No"
                        maxlength="10" oninput="this.value = this.value.slice(0, 10)">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button class="btn previous prev d-none" type="button" data-id="0">Previous</button>
                <button class="btn next" type="button" data-id="1">Next</button>
            </div>
        </fieldset>
        <!-- FIELDSET 2: Terms and Conditions -->
        <fieldset>
            <h3 class="fw-medium title_section mb-5 text-center">Consent Page</h3>
            <div id="consent_page" class="consent-box mx-3"></div> {{-- style="text-align:justify;" --}}
            <div class="my-3 ms-3">
                <strong>Date: </strong> <span>{{ now()->format('d-m-Y') }}</span>
            </div>
            <div class="radio-group square-radio mx-3 my-3">
                <label class="square-radio-option">
                    <input id="agree-checkbox" name="agreeCheckBox" type="checkbox" value="1">
                    <span class="square-box"><i class="fa-solid fa-check"></i></span>
                    <span class="square-label">I agree with this terms</span>
                </label>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn previous prev" type="button" data-id="1">Previous</button>
                <button class="btn next" type="button" data-id="2">Next</button>
            </div>
        </fieldset>

        <!-- FIELDSET 3: Upload Photograph -->
        <fieldset>
            <h3 class="fw-medium title_section mb-5 text-center">Doctor Photograph</h3>

            <div class="mb-3">
                <label class="custom_label mb-2">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">Upload Photo
                </label>

                <input id="photo_1" class="form-control custom_field" type="file" accept="image/*">

                <div class="row justify-content-center align-items-start mt-3">

                    <!-- LEFT: Cropper -->
                    <div id="cropper_main" class="col-md-6 mx-auto">
                        <div id="cropperBox" class="mx-auto">
                            <img id="cropImage" alt="" src="">
                        </div>
                        <div class="d-flex justify-content-center mt-3 flex-wrap gap-2 cropper-btns">
                            <button id="rotateRight" class="btn btn-primary custom-gradient-btn custom-gradient-btn-sm"
                                type="button">
                                ⟳ Rotate
                            </button>
                            <button id="flipX" class="btn btn-primary custom-gradient-btn custom-gradient-btn-sm"
                                type="button">
                                ⇋ Flip
                            </button>
                        </div>
                    </div>

                    <!-- RIGHT: Preview -->
                    <div id="preview_main" class="col-md-6 mx-auto">
                        <h5 id="preview_text" class="text-center">Preview Image</h5>
                        <img id="preview_1" class="mx-auto" style="display:block;"
                            src="{{ asset('public/images/doctor_preview.png') }}" width="280">
                        <input id="existing_photo" name="existing_photo" type="hidden"
                            value="{{ isset($data) ? ($data?->photo ? $data->photo : asset('public/images/img_sales.png')) : '' }}">
                    </div>

                </div>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn previous prev" type="button" data-id="2">Previous</button>
                <button class="btn next" type="button" data-id="3">Next</button>
            </div>
        </fieldset>

        <!-- FIELDSET 4: Upload Doctor Visiting Card -->
        <fieldset>
            <h3 class="fw-medium title_section mb-5 text-center">Doctor Visiting Card</h3>

            <div class="mb-3">
                <label class="custom_label mb-2">
                    <img class="me-2" src="{{ asset('public/images/icon_logo.png') }}" width="21">
                    Please Upload Front & Back Image
                </label>

                <!-- File Input -->
                <input id="photoInput" class="form-control custom_field" type="file" accept="image/*" multiple>

                <!-- Hidden Inputs -->
                <input id="card_f_photo" name="card_f_photo" type="hidden">
                <input id="card_b_photo" name="card_b_photo" type="hidden">

                <!-- Preview Section -->
                <div id="cardPreviewSection" class="row justify-content-center mt-4 d-none">

                    <!-- Front Image -->
                    <div class="col-md-4 text-center">
                        <img id="preview_front" class="img-fluid preview-img" src="">
                        <h5 class="my-2">Visiting Card Front Image</h5>
                    </div>

                    <!-- Back Image -->
                    <div class="col-md-4 text-center">
                        <img id="preview_back" class="img-fluid preview-img" src="">
                        <h5 class="my-2">Visiting Card Back Image</h5>
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn previous prev" type="button" data-id="3">Previous</button>
                <button class="btn next" type="button" data-id="4">Next</button>
            </div>
        </fieldset>

        <!-- FIELDSET 5: verify dr details -->
        <fieldset class="form-fieldset mt-4" data-title="Poster Preview">
            <h3 class="fw-medium title_section mb-5 text-center">Poster Preview</h3>

            <div class="">

                <div class="hs_doctors field hs-form-field row mb-2">

                    <div class="col-md-10 mx-auto text-center">

                        <!-- Image Preview -->
                        <img id="posterImage" class="img-fluid d-none mb-3" src="" />

                    </div>

                </div>

                <div class="d-flex col-md-11 justify-content-between mx-auto mt-3">
                    <input id="finalCanvasPoster" type="hidden">
                </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <button class="btn previous prev custom-gradient-btn custom-gradient-btn-sm" type="button"
                    data-id="4">Previous</button>
                <button id="submit" class="btn btn-primary next custom-gradient-btn custom-gradient-btn-sm"
                    type="button" data-id="5">Submit</button>
            </div>
        </fieldset>
    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        window.consentPage =
            `Hi I am Dr. {name}. I have been contacted to participate in Doctor Journey activity. I am interested to participate in the Doctor journey activity. I understand that as a process of creating content for this activity my personal details will be collected. I hereby certify that all the information and personal details provided by me are true & accurate to the best of my knowledge. I understand that my personal details will be embodied in the materials electronically, which once finalised and dispatched would become irretrievable. I understand that the work of creating the material, suitably editing it and finalisation of the same has been entrusted to a third party agency who shall bear the responsibility of the final material. I have read and understood the data privacy terms & conditions. I hereby provide my consent for collection of my personal details and sharing the same with the third party agency`;


        $(document).ready(function() {
            // dob picker
            $dob = flatpickr("#dob", {
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                maxDate: "today",
                disableMobile: "true",
            });

            let dateOfBirth = @json($data->dob ?? '');
            if (dateOfBirth) {
                $dob.setDate(dateOfBirth);
            }

            //  Area of expertise dropdown logic
            $expertise = $('#expertise').select2({
                placeholder: "-- Select Expertise --",
                width: '100%',
                allowClear: true,
            });

            // When dropdown opens (focus)
            $expertise.on('select2:open', function() {
                let container = $(this).data('select2').$container;
                let searchInput = container.find('.select2-search__field').css('width', '100%').attr(
                    'placeholder', 'Type to search...');
            });

            $expertise.on('select2:close', function() {

                let values = $(this).val(); // get selected values
                let container = $(this).data('select2').$container;
                let searchInput = container.find('.select2-search__field');

                if (!values || values.length === 0) {
                    searchInput.css('width', '100%').attr('placeholder', '-- Select Expertise --');
                } else {
                    searchInput.css('width', '100%').attr('placeholder', '');
                }
            });

            let maxSelection = 4;

            $expertise.on('select2:select', function(e) {
                let selected = $(this).val();
                if (selected.length > maxSelection) {
                    selected.pop();
                    $(this).val(selected).trigger('change');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Limit Exceeded',
                        text: 'You Can Select Maximum 4 Expertise Only',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
            $("#expertise").prop("disabled", true);

            $("input[name='specialty']").on("change", function() {
                $("#expertise").prop("disabled", false);
            });

            const phyOptions = @json($phy);
            const gynOptions = @json($gyn);
            $("input[name='specialty']").on("change", function() {

                let selected = $(this).val();
                let options = [];

                if (selected === "physician") {
                    options = phyOptions;
                } else if (selected === "gyn") {
                    options = gynOptions;
                }

                let dropdown = $("#expertise");
                dropdown.empty();
                dropdown.append('<option value="">-- Select Expertise --</option>');

                options.forEach(function(item) {
                    dropdown.append(`<option value="${item}">${item}</option>`);
                });

            });
            let existingExpertise = @json($data->area_of_expertise ?? '');

            if (existingExpertise) {
                $("#expertise").val(existingExpertise);
            }

            // visiting card image upload js
            let cardFrontPng = '';
            let cardBackPng = '';
            $('#photoInput').on('change', function(e) {

                let files = e.target.files;


                // if (files.length < 2) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Warning',
                //         text: 'Please Select Both Front and Back Images of the Visiting Card'
                //     });

                //     $(this).val('');
                //     return;
                // }
                // if (files.length > 2) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Warning',
                //         text: 'Please Select Only 2 Images (Front and Back)'
                //     });

                //     $(this).val('');
                //     return;
                // }

                // Clear previous

                $('#cardPreviewSection').removeClass('d-none');
                if (files[0]) {
                    $('#card_f_photo').val('');
                    let reader1 = new FileReader();
                    reader1.onload = function(e) {
                        $('#preview_front').attr('src', e.target.result);
                        $('#card_f_photo').val(e.target.result); // png string in hidden input
                        cardFrontPng = e.target.result;
                    };
                    reader1.readAsDataURL(files[0]);
                }

                if (files[1]) {
                    $('#card_b_photo').val('');
                    let reader2 = new FileReader();
                    reader2.onload = function(e) {
                        $('#preview_back').attr('src', e.target.result);
                        $('#card_b_photo').val(e.target.result); // png string in hidden input
                        cardBackPng = e.target.result;
                    };
                    reader2.readAsDataURL(files[1]);
                }

            });

            // set default value for testing step 1
            // $('input[name="specialty"][value="physician"]').prop("checked", true).trigger("change");
            // $("#name").val("Test Doctor");
            // $("#dr_specialty").val("Hospice and Palliative Medicine Specialists");
            // $("#collage_name").val("American Career College Ontario");
            // $("#pg_name").val("American Institute Of Medical Technology");
            // $("#practice_years").val("10");
            // $("#mobile_number").val("9876543210");
            // $('#dob').val('2026-05-30').trigger('change'); // Set DOB to 30-05-2026
            // $('#expertise').val(['Diabetes', 'Hypertension']).trigger('change');
            // $('input[name="agree-checkbox"]').prop("checked", true);


            let cropper = null;
            let croppedPhoto1File = null;
            let originalPhotoDataUrl = null;
            const generatePosterUrl = @json(route('dr.journey.generate.poster'));
            let PosterImage = null;

            // consent page vars
            const consentPage = window.consentPage;
            let finalConsentPage = '';


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

            $('#cropper_main').hide();
            $('#preview_text').hide();
            $('.cropper-btns').hide();
            $("#photo_1").on("change", function(e) {
                let file = e.target.files[0];
                if (!file) return;

                let reader = new FileReader();
                reader.onload = function(ev) {
                    originalPhotoDataUrl = ev.target.result; //  STORE IMAGE
                    $('#cropper_main').show();
                    $('#preview_main').hide();
                    $('#preview_text').show();
                    $("#cropperBox").show();
                    $("#cropImage").attr("src", originalPhotoDataUrl);

                    setTimeout(() => {
                        initCropper();
                    }, 200);
                };
                reader.readAsDataURL(file);
            });



            let scaleX = 1;

            // Rotate Right (90°)
            $("#rotateRight").on("click", function() {
                if (cropper) {
                    cropper.rotate(90);
                }
            });

            // Flip Horizontal
            $("#flipX").on("click", function() {
                if (cropper) {
                    scaleX = scaleX * -1;
                    cropper.scaleX(scaleX);
                }
            });

            // ============================================
            // FORM NAVIGATION
            // ============================================

            function goToStep(step) {
                $("fieldset").removeClass("active");
                $("fieldset").eq(step - 1).addClass("active");

                if (step === 1) {
                    $("fieldset").eq(0).find(".prev").hide();
                } else {
                    $("fieldset").eq(step - 1).find(".prev").show();
                }

                //  FIX: Restore cropper image when returning to step 2
                if (step === 2 && originalPhotoDataUrl) {
                    $("#cropperBox").show();
                    $("#cropImage").attr("src", originalPhotoDataUrl);

                    setTimeout(() => {
                        initCropper();
                    }, 300);
                }
            }

            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth' // smooth scrolling
                });
            }

            $(document).on("click", ".prev", function() {
                let step = $(this).data("id");
                scrollToTop();
                if (step === 0) {
                    currentPage = 1;
                    nav.hide();
                    formContanier.hide();
                    imageContainer.show();
                    updateView();
                } else if (step === 1) {
                    goToStep(1);
                } else if (step === 2) {
                    goToStep(2);
                } else if (step === 3) {
                    goToStep(3);
                } else if (step === 4) {
                    goToStep(4);
                }
            });

            $(".next").on("click", async function() {

                let step = $(this).data("id");

                // Step 1: Validate details
                if (step === 1) {
                    let specialty = $("input[name='specialty']:checked").val();
                    let name = $("#name").val().trim();
                    let dr_specialty = $("#dr_specialty").val().trim();
                    let collage = $("#collage_name").val().trim();
                    let pg = $("#pg_name").val().trim();
                    let practice = $("#practice_years").val().trim();
                    let expertise = $("#expertise").val(); // array (multi-select)
                    let dob = $("#dob").val().trim();
                    let mobile = $("#mobile_number").val().trim();

                    if (!specialty) {
                        return Swal.fire("Required", "Please Select Specialty", "warning");
                    }

                    if (!name) {
                        $("#name").focus();
                        return Swal.fire("Required", "Please Enter Doctor Name", "warning");
                    }

                    if (!dr_specialty) {
                        $("#dr_specialty").focus();
                        return Swal.fire("Required", "Please Enter Doctor Specialty", "warning");
                    }

                    if (!collage) {
                        $("#collage_name").focus();
                        return Swal.fire("Required", "Please Enter MBBS College Name", "warning");
                    }

                    if (!pg) {
                        $("#pg_name").focus();
                        return Swal.fire("Required", "Please Enter PG Institute Name", "warning");
                    }

                    if (!practice) {
                        $("#practice_years").focus();
                        return Swal.fire("Required", "Please Enter Years of Practice", "warning");
                    }

                    if (parseInt(practice) < 0) {
                        $("#practice_years").focus();
                        return Swal.fire("Invalid", "Years must be greater than 0", "warning");
                    }

                    if (!expertise || expertise.length === 0) {
                        $("#expertise").focus();
                        return Swal.fire("Required", "Please Select At Least One Expertise", "warning");
                    }

                    // Max 4 validation (since you added max=4)
                    if (expertise.length > 4) {
                        return Swal.fire("Error", "You can select maximum 4 expertise", "warning");
                    }

                    if (!dob) {
                        $("#dob").focus();
                        return Swal.fire("Required", "Please Select Date of Birth", "warning");
                    }

                    if (!mobile) {
                        $("#mobile_number").focus();
                        return Swal.fire("Required", "Please Enter Mobile Number", "warning");
                    }

                    if (!/^[0-9]{10}$/.test(mobile)) {
                        $("#mobile_number").focus();
                        return Swal.fire("Invalid", "Mobile Number must be exactly 10 digits",
                            "warning");
                    }

                    finalConsentPage = consentPage
                        .replace("{name}", $("#name").val().trim());

                    $("#consent_page").html(finalConsentPage.replace(/\n/g, "<br>"));
                    $checkboxLabel = 'I Dr. ' + $("#name").val().trim() + ', here by give my consent';
                    $('.square-label').text($checkboxLabel);
                    goToStep(2);
                }

                // Step 2: Validate Tearms & Conditions
                if (step === 2) {
                    let agreeCheckBox = $("#agree-checkbox")[0];
                    if (!agreeCheckBox.checked) {
                        return Swal.fire("Required", "Please Agree To Consent", "warning");
                    }
                    goToStep(3);
                }

                // Step 3: Validate photo
                if (step === 3) {
                    autoCropImage();
                    // validate dr photo
                    let input = $('#photo_1')[0];
                    let files = input.files;

                    if (!files || files.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Required',
                            text: 'Please Upload Doctor Photo.'
                        });
                        return;
                    }
                    goToStep(4);
                }
                if (step === 4) {
                    let input = $('#photoInput')[0];
                    let files = input.files;

                    if (!cardBackPng || !cardFrontPng) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Required',
                            text: 'Please Upload Doctor Visiting Card Images.'
                        });
                        return;
                    }

                    // // No file selected
                    // if (!files || files.length === 0 || files.length < 2) {
                    //     Swal.fire({
                    //         icon: 'warning',
                    //         title: 'Required',
                    //         text: 'Please Upload Doctor Visiting Card Front & Back Image.'
                    //     });
                    //     return;
                    // }

                    // // Max 2 images
                    // if (files.length > 2) {
                    //     Swal.fire({
                    //         icon: 'warning',
                    //         title: 'Warning',
                    //         text: 'Please Upload Only 2 Images.'
                    //     });
                    //     return;
                    // }

                    // generate poster ajax call
                    $('.main_loader').show();
                    // poster preview formdata submit
                    let fd = new FormData();
                    fd.append("_token", "{{ csrf_token() }}");
                    fd.append("name", $("#name").val());
                    fd.append("specialty", $("input[name='specialty']:checked").val());
                    fd.append("dr_specialty", $("#dr_specialty").val());
                    fd.append("collage_name", $("#collage_name").val());
                    fd.append("pg_name", $("#pg_name").val());
                    fd.append("year_of_practice", $("#practice_years").val());
                    $("#expertise").val().forEach(val => {
                        fd.append("area_of_expertise[]", val);
                    });
                    fd.append("dob", $("#dob").val());
                    fd.append("mobile", $("#mobile_number").val());
                    // let file;
                    // if (!croppedPhoto1File) {
                    //     // Convert Base64 → File
                    //     let base64 = getCroppedImage();
                    //     file = base64ToFile(base64, "doctor.png");
                    // } else {
                    //     file = croppedPhoto1File;
                    // }
                    fd.append("croped_dr_image", croppedPhoto1File);

                    $.ajax({
                        url: generatePosterUrl,
                        type: "POST",
                        data: fd,
                        processData: false,
                        contentType: false,
                        xhrFields: {
                            responseType: 'blob' // 🔥 MUST for image response
                        },
                        success: function(res) {
                            scrollToTop();
                            $('.main_loader').hide();

                            // Create image URL from blob
                            const imageURL = URL.createObjectURL(res);
                            PosterImage = imageURL;
                            // OPTION 1: Show in <img> (EASIEST)
                            $('#posterImage').attr('src', imageURL).removeClass('d-none');
                            goToStep(5);
                        },
                        error: function(xhr) {
                            $('.main_loader').hide();

                            let msg = "Something Went Wrong";

                            try {
                                let reader = new FileReader();

                                reader.onload = function() {
                                    try {
                                        let res = JSON.parse(reader.result);
                                        msg = res.message || msg;
                                    } catch (e) {}
                                    Swal.fire("Error", msg, "error");
                                };

                                reader.readAsText(xhr.response);
                            } catch (e) {
                                Swal.fire("Error", msg, "error");
                            }
                        }
                    });


                    goToStep(5);
                }

                // Step 4: generated poster and submit data
                if (step === 5) {
                    if (!croppedPhoto1File) {
                        return Swal.fire("Required", "Please Upload Doctor Photo.",
                            "warning");
                    }

                    if (!cardFrontPng || !cardBackPng) {
                        return Swal.fire("Required", "Please Upload Doctor Visiting Card Images.",
                            "warning");
                    }

                    // form submit start
                    $('.main_loader').show();

                    $(this).attr('disabled', true);

                    let fd = new FormData();
                    fd.append("_token", "{{ csrf_token() }}");
                    // Append form fields
                    fd.append("name", $("#name").val());
                    fd.append("specialty", $("input[name='specialty']:checked").val());
                    fd.append("dr_specialty", $("#dr_specialty").val().trim());
                    fd.append("collage_name", $("#collage_name").val().trim());
                    fd.append("pg_name", $("#pg_name").val().trim());
                    fd.append("year_of_practice", $("#practice_years").val().trim());
                    $("#expertise").val().forEach(val => {
                        fd.append("area_of_expertise[]", val);
                    });
                    fd.append("dob", $("#dob").val().trim());
                    fd.append("mobile_number", $("#mobile_number").val().trim());

                    // Append photo
                    if (croppedPhoto1File) {
                        fd.append("photo_1", croppedPhoto1File);
                    } else {
                        return Swal.fire("Required", "Doctor Photo not found",
                            "warning");
                    }

                    if (cardFrontPng) {
                        let fileFront;
                        if (cardFrontPng) {
                            // Convert Base64 → File
                            let base64 = $("#card_f_photo").val();
                            fileFront = base64ToFile(base64, "doctor_visiting_card_front.png");
                        } else {
                            return Swal.fire("Required", "Visiting Card Front Image Not Found",
                                "warning");
                        }
                        fd.append("card_f_photo_1", fileFront);
                    }
                    if (cardBackPng) {
                        let fileBack;
                        if (cardBackPng) {
                            // Convert Base64 → File
                            let base64 = $("#card_b_photo").val();
                            fileBack = base64ToFile(base64, "doctor_visiting_card_back.png");
                        } else {
                            return Swal.fire("Required", "Visiting Card Back Image Not Found",
                                "warning");
                        }
                        fd.append("card_b_photo_1", fileBack);
                    }

                    $.ajax({
                        url: "{{ route('dr.journey.store') }}",
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

            function base64ToFile(base64, filename) {
                let arr = base64.split(',');
                let mime = arr[0].match(/:(.*?);/)[1];
                let bstr = atob(arr[1]);
                let n = bstr.length;
                let u8arr = new Uint8Array(n);

                while (n--) {
                    u8arr[n] = bstr.charCodeAt(n);
                }

                return new File([u8arr], filename, {
                    type: mime
                });
            }


            function getCroppedImage() {
                croppedCanvas = cropper.getCroppedCanvas({
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: "high",
                });
                return croppedCanvas.toDataURL("image/png");
            }

            function autoCropImage() {
                if (!cropper) return;

                cropper.getCroppedCanvas({
                    // width: 1000,
                    // height: 1000
                }).toBlob(function(blob) {

                    croppedPhoto1File = new File([blob], "photo_1.png", {
                        type: "image/png"
                    });

                    // Optional preview
                    $("#preview_1").attr("src", URL.createObjectURL(blob)).show();

                    console.log("Auto cropped and stored");
                }, "image/png");
            }


        });
    </script>
@endpush

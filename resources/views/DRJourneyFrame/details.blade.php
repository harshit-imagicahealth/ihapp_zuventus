@extends('DRJourneyFrame.layouts.main')
@section('main')
    <fieldset class="active m-5">
        <div class="row container mx-auto justify-content-center align-items-center">

            {{-- <div class="col-md-6 d-flex justify-content-center align-items-center py-5">
                <div class="card-body p-4 text-center">

                    <!-- PHOTO -->
                    <div class="mb-3">
                        <img class="img-fluid" src="{{ $data->photo }}" alt="Photo"
                            style="width:50%;height:50%;object-fit:cover;border-radius:16px;border:3px solid #f1f1f1;">
                    </div>

                    <!-- NAME -->
                    <h4 class="fw-bold mb-1">Dr. {{ $data->name }}</h4>
                    <p class="mb-1">
                        {{ ucfirst($data->city) }}
                    </p>

                    <!-- ACTION BUTTONS -->
                    <div class="d-flex justify-content-center gap-2">
                        <a class="btn btn-warning px-4" href="{{ route('dr.journey.edit', encrypt($data->id)) }}">
                            ✏ Edit
                        </a>
                    </div>

                </div>
            </div> --}}
            <div class="col-md-6 d-flex justify-content-center align-items-center py-5">
                <div>
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('public/images/thank.gif') }}" alt="" width="100">
                    </div>
                    <h3 class="mb-2 text-center">Thank You</h3>
                    <h3 class="text-center">Your details have been submitted successfully</h3>
                    <div class="d-flex justify-content-center mt-4">
                        <a class="btn btn_custom" href="{{ route('dr.journey.dashboard') }}">Dashboard</a>
                    </div>
                </div>
            </div>

        </div>
    </fieldset>
@endsection

@push('script')
    {{-- <script>
        const audioUrl = 'https://ihapp.blr1.cdn.digitaloceanspaces.com/{{ $data->audio }}';
        const audioPlayer = document.getElementById('audioPlayer');

        // Set the source directly instead of fetching as a blob
        audioPlayer.src = audioUrl;

        // Force the browser to load metadata immediately
        audioPlayer.addEventListener('loadedmetadata', () => {
            console.log("Audio duration is: " + audioPlayer.duration);
        });
    </script> --}}
@endpush

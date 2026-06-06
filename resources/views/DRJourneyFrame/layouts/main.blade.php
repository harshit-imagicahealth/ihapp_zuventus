@include('DRJourneyFrame.layouts.header')
@yield('main')
@include('DRJourneyFrame.layouts.footer')
<div id="rotate-screen" class="rotate-screen">
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div style="text-align: center;">
            <img src="https://live.imagicahealth.com/tuffies/images/rotate.gif" width="50%">
            <p
                style="font-size: 18px; color: #000; font-family: 'Rubik', sans-serif; text-transform: uppercase; text-align: center; font-weight: 600;">
                Please Rotate Your Device
            </p>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // oriantation check function
        function checkOrientation() {
            let isPortrait = window.innerHeight > window.innerWidth;

            if (isPortrait) {
                // 📱 portrait → SHOW
                document.getElementById('rotate-screen').style.display = 'block';
            } else {
                // 📱 landscape → HIDE
                document.getElementById('rotate-screen').style.display = 'none';
            }
        }
        // run on load
        checkOrientation();

        // run on rotate
        window.addEventListener('resize', checkOrientation);
        window.addEventListener('orientationchange', checkOrientation);

    });
</script>

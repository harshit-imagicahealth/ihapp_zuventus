<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .message-container {
            text-align: center;
            padding: 2rem;
            border: 2px solid #27337a;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            animation: blink-shadow 1.5s infinite;
        }

        @keyframes blink-shadow {
            0% {
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            }

            50% {
                box-shadow: 0 8px 20px rgb(41 52 120 / 75%);
            }

            100% {
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            }
        }

        .message-container h1 {
            font-size: 2rem;
            color: #333;
            margin-top: 1rem;
            /* font-family: "Sour Gummy", sans-serif; */
        }

        .message-container p {
            font-size: 1.2rem;
            color: #555;
        }

        .message-container a {
            margin-top: 1rem;
            text-decoration: none;
        }

        @media (orientation: portrait) {
            .message-container h1 {
                font-size: 1rem !important;
                color: #333;
                margin-top: 1rem;
            }

            .message-container p {
                font-size: 0.8rem !important;
                color: #555;
            }
        }

        .icon-checkmark circle {
            fill: #27337a;
            transform-origin: 50% 50%;
            transform: scale(0);
            transition: transform 800ms cubic-bezier(0.22, 0.96, 0.38, 0.98);
        }

        .icon-checkmark path {
            transition: stroke-dashoffset 350ms ease;
            transition-delay: 100ms;
        }

        .active .icon-checkmark circle {
            transform: scale(1);
        }

        .success-message__icon {
            max-width: 75px;
        }
    </style>
</head>

<body>
    <div class="message-container col-10 col-md-5 mx-auto">
        <!-- <div class="col-6 col-md-3 mx-auto">
            <img src='img/clock.svg' alt='Clock' width="100%">
        </div> -->
        <svg viewBox="0 0 76 76" class="success-message__icon icon-checkmark">
            <circle cx="38" cy="38" r="36" />
            <path fill="none" stroke="#FFFFFF" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M17.7,40.9l10.9,10.9l28.7-28.7" />
        </svg>
        <h1>{{$message}}</h1>
        <p>Thank You!</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script src="js/jquery-2.1.3.min.js"></script>
<script>
    function PathLoader(el) {
        this.el = el;
        this.strokeLength = el.getTotalLength();

        // set dash offset to 0
        this.el.style.strokeDasharray = this.el.style.strokeDashoffset = this.strokeLength;
    }

    PathLoader.prototype._draw = function(val) {
        this.el.style.strokeDashoffset = this.strokeLength * (1 - val);
    };

    PathLoader.prototype.setProgress = function(val, cb) {
        this._draw(val);
        if (cb && typeof cb === "function") cb();
    };

    PathLoader.prototype.setProgressFn = function(fn) {
        if (typeof fn === "function") fn(this);
    };

    var body = document.body,
        svg = document.querySelector("svg path");

    if (svg !== null) {
        svg = new PathLoader(svg);

        setTimeout(function() {
            document.body.classList.add("active");
            svg.setProgress(1);
        }, 200);
    }
</script>

</html>
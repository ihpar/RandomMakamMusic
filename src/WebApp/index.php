<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="music">
    <meta name="author" content="ismail hakkı parlak, cem kösemen">
    <link rel="icon" href="images/favicon.png">

    <title>Rastgele!</title>
    <link href="https://fonts.googleapis.com/css?family=Oswald:400,700" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <style type="text/css">
        html, body {
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        body {
            min-height: 100vh;
            font-family: 'Oswald', sans-serif;
            font-size: 25px;
            font-weight: normal;
            color: #EEC765;
            background-color: #26292C;
            transition: background-color 0.6s ease-in-out;
        }

        .container {
            padding: 16px;
            margin: 0 auto;
            max-width: 500px;
        }

        h3 {
            font-size: 1.5em;
            padding: 0;
            margin: 0;
            font-weight: normal;
        }

        .mo-btn {
            position: relative;
            padding: 0.3em 0 0.3em 1.8em;
            text-align: left;
        }

        .mo-btn label {
            cursor: pointer;
        }

        .mo-btn label:before, .mo-btn label:after {
            content: '';
            position: absolute;
            top: 50%;
            border-radius: 50%;
        }

        .mo-btn label:before {
            left: 0;
            width: 1.4em;
            height: 1.4em;
            margin: -0.7em 0 0;
            background: transparent;
            border: 2px solid #EEC765;
            box-sizing: border-box;
        }

        .mo-btn label:after {
            left: 0.2em;
            width: 1em;
            height: 1em;
            margin: -0.5em 0 0;
            opacity: 0;
            background: #839F9B;
            transform: translate3d(-60px, 0, 0) scale(0.1);
            transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
        }

        .mo-btn input[type="radio"],
        .mo-btn input[type="checkbox"] {
            position: absolute;
            top: 0;
            left: -9999px;
            visibility: hidden;
        }

        .mo-btn input[type="radio"]:checked + label:after,
        .mo-btn input[type="checkbox"]:checked + label:after {
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }

        .opts {
            margin-top: 0.2em;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
        }

        div.inline {
            flex-grow: 1;
            flex-shrink: 1;
            flex-basis: 5%;
        }

        .row {
            padding: 0 0 1em 0;
        }

        .btn {
            cursor: pointer;
            display: inline-block;
            outline: 0;
            border: none;
            background-color: #3f514d;
            color: #EEC765;
            padding: 0.4em;
            font-size: 38px;
            width: 100%;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            user-select: none;
            transition: all 0.3s ease;
            -webkit-tap-highlight-color: transparent;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
        }

        .btn:hover {
            background-color: #3a4d49;
            color: #f1ca66;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.25);
        }

        .slider {
            background: #EEC765;
            position: relative;
            display: block;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
        }

        .slider-fill {
            background: #96993d;
            position: absolute;
            display: block;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            top: 0;
            height: 100%;
        }

        .slider-horizontal {
            height: 10px;
            width: 100%;
        }

        .slider-handle {
            background: #839F9B;
            cursor: pointer;
            display: inline-block;
            width: 35px;
            height: 35px;
            position: absolute;
            background-size: 100%;
            background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, rgba(255, 255, 255, 0)), color-stop(100%, rgba(0, 0, 0, 0.1)));
            background-image: -moz-linear-gradient(rgba(255, 255, 255, 0), rgba(0, 0, 0, 0.1));
            background-image: -webkit-linear-gradient(rgba(255, 255, 255, 0), rgba(0, 0, 0, 0.1));
            background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(0, 0, 0, 0.1));
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            top: -12.5px;
            touch-action: pan-y;
        }

        .song-builder, .song-player {
            animation-duration: 0.3s;
        }

        @media only screen and (max-width: 510px) {
            body {
                font-size: 20px;
            }

            .btn {
                font-size: 30px;
            }

            .slider-handle {
                width: 30px;
                height: 30px;
                top: -10px;
            }
        }

        @media only screen and (max-width: 450px) {
            div.inline {
                flex-grow: 1;
                flex-shrink: 1;
                flex-basis: 30%;
            }
        }


    </style>
</head>

<body>

<!-- Page Content -->
<div class="container">
    <div class="song-builder">
        <div class="row">
            <h3>Makam</h3>
            <div class="opts">
                <div class="mo-btn inline">
                    <input type="radio" name="optionsM" id="btnHicaz" class="makam" data-value="Hicaz" checked>
                    <label for="btnHicaz">Hicaz</label>
                </div>
                <div class="mo-btn inline">
                    <input type="radio" name="optionsM" id="btnUssak" class="makam" data-value="Uşşak">
                    <label for="btnUssak">Uşşak</label>
                </div>
                <div class="mo-btn inline" style="height: 0;"></div>
                <div class="mo-btn inline" style="height: 0;"></div>
            </div>
        </div>

        <div class="row">
            <h3>Orkestra</h3>
            <div class="opts">
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnUd" class="instrument" data-value="Ud" checked>
                    <label for="btnUd">Ud</label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnKanun" class="instrument" data-value="Kanun" checked>
                    <label for="btnKanun">Kanun</label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnTanbur" class="instrument" data-value="Tanbur" checked>
                    <label for="btnTanbur">Tanbur</label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnNey" class="instrument" data-value="Ney" checked>
                    <label for="btnNey">Ney</label>
                </div>
            </div>
        </div>

        <div class="row">
            <h3>Tempo</h3>
            <div class="opts">
                <div class="mo-btn inline">
                    <input type="radio" name="optionsT" id="btn80" class="tempo" data-value="80" checked>
                    <label for="btn80">80</label>
                </div>
                <div class="mo-btn inline">
                    <input type="radio" name="optionsT" id="btn100" class="tempo" data-value="100">
                    <label for="btn100">100</label>
                </div>
                <div class="mo-btn inline">
                    <input type="radio" name="optionsT" id="btn120" class="tempo" data-value="120">
                    <label for="btn120">120</label>
                </div>
                <div class="mo-btn inline" style="height: 0;"></div>
            </div>
        </div>

        <div class="row">
            <h3>Perküsyon</h3>
            <div class="opts">
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnBendir" class="percussion" data-value="Bendir" checked>
                    <label for="btnBendir">Bendir</label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnErbane" class="percussion" data-value="Erbane" checked>
                    <label for="btnErbane">Erbane</label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnKudum" class="percussion" data-value="Kudüm" checked>
                    <label for="btnKudum">Kudüm</label>
                </div>
                <div class="mo-btn inline" style="height: 0;"></div>
            </div>
        </div>

        <div class="row">
            <h3>Müzikalite <<span id="spnMusicality">5</span>></h3>
            <div class="opts" style="margin: 20px 0;">
                <input type="range" min="1" max="10" value="5" id="musicality" style="position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0;">
            </div>
        </div>

        <div class="row" style="padding-bottom: 0;">
            <button type="button" id="btnGenerateMusic" class="btn">Bestele</button>
        </div>
    </div>
    <div class="song-player" style="display: none;">
        <div class="row">
            Hello
        </div>
        <div class="row">
            <button type="button" id="btnBackToComposing" class="btn">Geri</button>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/rangeslider.min.js"></script>

<script type="text/javascript">
    var pages = [];
    var bgColors = ["#26292C", "#EEC765"];

    function switchToPage(fromPage, toPage) {
        var exitAnimation = (fromPage < toPage) ? "fadeOutLeft" : "fadeOutRight";
        var enterAnimation = (fromPage < toPage) ? "fadeInRight" : "fadeInLeft";
        var oldExitAnimation = (fromPage > toPage) ? "fadeOutLeft" : "fadeOutRight";
        var oldEnterAnimation = (fromPage > toPage) ? "fadeInRight" : "fadeInLeft";
        $("body").css({backgroundColor: bgColors[toPage]});
        pages[fromPage].removeClass(oldEnterAnimation).animateCss(exitAnimation, function () {
            pages[fromPage].hide();
            pages[toPage].removeClass(oldExitAnimation).show().animateCss(enterAnimation);
        });
    }

    $.fn.extend({
        animateCss: function (animationName, callback) {
            var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            this.addClass('animated ' + animationName).one(animationEnd, function () {
                if (callback) callback();
            });
            return this;
        }
    });

    $(document).ready(function () {
        pages.push($(".song-builder"));
        pages.push($(".song-player"));

        $("#musicality").rangeslider({
            polyfill: false,

            rangeClass: "slider",
            horizontalClass: "slider-horizontal",
            fillClass: "slider-fill",
            handleClass: "slider-handle",

            // Callback function
            onSlide: function (position, value) {
                $("#spnMusicality").html(value);
            }
        });

        $("#btnBackToComposing").on("click", function () {
            switchToPage(1, 0);
        });

        $("#btnGenerateMusic").on("click", function () {
            // get selected makam
            var makam = $("input.makam:checked").attr("data-value");
            // get selected tempo
            var tempo = parseInt($("input.tempo:checked").attr("data-value"));
            // get selected instruments
            var instruments = [];
            $("input.instrument:checked").each(function () {
                instruments.push($(this).attr("data-value"));
            });

            if (instruments.length === 0) {
                alert("En az bir tane enstrüman seçiniz.");
                return false;
            }
            // get selected percussion
            var percussion = [];
            $("input.percussion:checked").each(function () {
                percussion.push($(this).attr("data-value"));
            });

            if (percussion.length === 0) {
                alert("En az bir tane perküsyon seçiniz.");
                return false;
            }
            // get selected musicality
            var musicality = parseInt($("#musicality").val());

            var postParams = {
                m: makam,
                t: tempo,
                i: instruments.join(","),
                p: percussion.join(","),
                x: musicality
            };

            console.log(postParams);
            switchToPage(0, 1);
        });

    });

</script>
</body>

</html>

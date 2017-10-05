<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="music">
    <meta name="author" content="ismail hakkı parlak, cem kösemen">
    <link rel="icon" href="images/favicon.png">

    <title>Turca</title>
    <link href="https://fonts.googleapis.com/css?family=Oswald:400,700" rel="stylesheet">

    <style type="text/css">
        html, body {
            padding: 0;
            margin: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Oswald', sans-serif;
            font-size: 24px;
            font-weight: normal;
            background: url('images/tile.jpg') repeat;
            color: #817855;
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

        .styled-input-single {
            position: relative;
            padding: 0.3em 0 0.3em 1.8em;
            text-align: left;
        }

        .styled-input-single label {
            cursor: pointer;
        }

        .styled-input-single label:before, .styled-input-single label:after {
            content: '';
            position: absolute;
            top: 50%;
            border-radius: 50%;
        }

        .styled-input-single label:before {
            left: 0;
            width: 1.4em;
            height: 1.4em;
            margin: -0.7em 0 0;
            background: #82929f;
        }

        .styled-input-single label:after {
            left: 0.2em;
            width: 1em;
            height: 1em;
            margin: -0.5em 0 0;
            opacity: 0;
            background: #05182E;
            transform: translate3d(-60px, 0, 0) scale(0.1);
            transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
        }

        .styled-input-single input[type="radio"],
        .styled-input-single input[type="checkbox"] {
            position: absolute;
            top: 0;
            left: -9999px;
            visibility: hidden;
        }

        .styled-input-single input[type="radio"]:checked + label:after,
        .styled-input-single input[type="checkbox"]:checked + label:after {
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }

        .opts {
            margin-top: 0.5em;
        }

        div.inline {
            display: inline-block;
            margin-right: 1em;
        }

        .row {
            margin-bottom: 1em;
        }
    </style>

</head>

<body>

<!-- Page Content -->
<div class="container">

    <div class="row">
        <h3>Makam</h3>
        <div class="opts">
            <div class="styled-input-single inline">
                <input type="radio" name="optionsM" id="btnHicaz" checked>
                <label for="btnHicaz">Hicaz</label>
            </div>
            <div class="styled-input-single inline">
                <input type="radio" name="optionsM" id="btnUssak">
                <label for="btnUssak">Uşşak</label>
            </div>
        </div>
    </div>

    <div class="row">
        <h3>Instruments</h3>
        <div class="opts">
            <div class="styled-input-single inline">
                <input type="checkbox" id="btnUd" checked>
                <label for="btnUd">Ud</label>
            </div>
            <div class="styled-input-single inline">
                <input type="checkbox" id="btnKanun" checked>
                <label for="btnKanun">Kanun</label>
            </div>
            <div class="styled-input-single inline">
                <input type="checkbox" id="btnTanbur" checked>
                <label for="btnTanbur">Tanbur</label>
            </div>
            <div class="styled-input-single inline">
                <input type="checkbox" id="btnNey" checked>
                <label for="btnNey">Ney</label>
            </div>
        </div>
    </div>

    <div class="row">
        <h3>Tempo</h3>
        <div class="opts">
            <div class="styled-input-single inline">
                <input type="radio" name="optionsT" id="btn80" checked>
                <label for="btn80">80</label>
            </div>
            <div class="styled-input-single inline">
                <input type="radio" name="optionsT" id="btn100">
                <label for="btn100">100</label>
            </div>
            <div class="styled-input-single inline">
                <input type="radio" name="optionsT" id="btn120">
                <label for="btn120">120</label>
            </div>
        </div>
    </div>

    <div>

        <h3>Percussion</h3>
        <div class="btn-group btn-group-justified" data-toggle="buttons">
            <label class="btn btn-info active">
                <input type="checkbox" id="btnBendir" autocomplete="off" checked> Bendir
            </label>
            <label class="btn btn-info">
                <input type="checkbox" id="btnErbane" autocomplete="off"> Erbane
            </label>
            <label class="btn btn-info">
                <input type="checkbox" id="btnKudum" autocomplete="off"> Kudüm
            </label>
        </div>

    </div>

    <div>

        <h3>Musicality <span id="spnMusicality">(5)</span></h3>
        <div class="btn-group btn-group-justified" data-toggle="buttons">
            <input id="musicality">
        </div>

    </div>

    <div>
        <div id="dvSongHolder" style="height: 5px;">
            <div id="dvLoader" style="display: none;">
                <div style="background-color:#24888c; color:#fff; font-size:24px; border-radius:6px; padding:0px 16px; line-height:46px;">
                    COMPOSING...
                </div>
            </div>

            <div id="dvGeneratedSong" style="display: none;">
                <h3 style="margin-top: 0px;">Generated Song</h3>
                <div id="audioWrap"></div>
            </div>
        </div>
    </div>

    <div>
        <button type="button" id="btnGenerateMusic" class="btn btn-success btn-lg btn-block">Generate Music</button>
    </div>

</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<script type="text/javascript">
    /*
    var gotAudio = false;
    var player = null;
    var canGenerateSong = true;

    $(document).ready(function () {

        player = $.AudioPlayer;
        player.init({
            container: "#audioWrap",
            source: "",
            imagePath: "images",
            debuggers: false,
            allowSeek: true
        });

        $("#musicality").slider();
        $("#musicality").on("change", function (e) {
            $("#spnMusicality").html("(" + e.value.newValue + ")");
        });

        $("#btnGenerateMusic").click(function () {

            canGenerateSong = true;

            // get selected makam
            var makam = $("#btnUssak").is(':checked') ? "Ussak" : "Hicaz";
            // get selected tempo
            var tempo = $("#btn80").is(':checked') ? 80 : ($("#btn100").is(':checked') ? 100 : 120);
            // get selected instruments
            var instruments = [];
            if ($("#btnUd").is(':checked')) {
                instruments.push("Ud");
            }
            if ($("#btnKanun").is(':checked')) {
                instruments.push("Kanun");
            }
            if ($("#btnTanbur").is(':checked')) {
                instruments.push("Tanbur");
            }
            if ($("#btnNey").is(':checked')) {
                instruments.push("Ney");
            }

            if (instruments.length == 0) {
                canGenerateSong = false;
                alert("Please select at least 1 instrument!");
                return false;
            }
            // get selected percussion
            var percussion = [];
            if ($("#btnBendir").is(':checked')) {
                percussion.push("Bendir");
            }
            if ($("#btnErbane").is(':checked')) {
                percussion.push("Erbane");
            }
            if ($("#btnKudum").is(':checked')) {
                percussion.push("Kudum");
            }

            if (percussion.length == 0) {
                canGenerateSong = false;
                alert("Please select at least 1 percussion!");
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

            // ajax call
            if (canGenerateSong) {

                $("#btnGenerateMusic").attr("disabled", true);

                if (!gotAudio) {
                    $("#dvSongHolder").animate({height: "85px"}, 1000, function () {
                        $("#dvLoader").fadeIn(2000);
                    });
                }
                else {
                    $("#dvGeneratedSong").fadeOut(500, function () {
                        $("#dvLoader").fadeIn(2000);
                    });
                }

                $.ajax({
                    method: "POST",
                    url: "song-generator.php",
                    data: postParams
                })
                    .done(function (m) {
                        m = m.trim();
                        console.log(m);
                        if (m.indexOf(".mp3") > 0) {
                            $("#dvLoader").fadeOut(400, function () {
                                $("#dvGeneratedSong").fadeIn(1000, function () {
                                    player.updateSource({
                                        source: m
                                    });

                                    $("#btnGenerateMusic").removeAttr("disabled");
                                });
                            });
                            gotAudio = true;
                        }
                        else {
                            alert("An unexpected error ocuured, sorry!");
                            $("#dvLoader").fadeOut(1000);
                            $("#btnGenerateMusic").removeAttr("disabled");
                        }

                    });
            }
        });

    });
*/
</script>
</body>

</html>

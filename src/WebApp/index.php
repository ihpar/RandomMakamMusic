<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="music">
    <meta name="author" content="ismail hakkı parlak, cem kösemen">
    <link rel="icon" href="images/favicon.png">

    <title>Turca | A true random music generator for Turkish Makams</title>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/jQuery.AudioPlayer.js"></script>

    <link href="css/jQuery.AudioPlayer.css" rel="stylesheet">

    <style type="text/css">
        body {
            background: url('images/tile.jpg') repeat;
            color: #fff;
        }

    </style>

</head>

<body>

<!-- Page Content -->
<div class="container">

    <!-- Projects Row -->
    <div class="row">
        <div class="col-md-12 portfolio-item">
            <form>
                <div class="form-group">

                    <h3>Makam</h3>
                    <div class="btn-group btn-group-justified" data-toggle="buttons">
                        <label class="btn btn-info active">
                            <input type="radio" name="optionsM" id="btnUssak" autocomplete="off" checked> Uşşak
                        </label>
                        <label class="btn btn-info ">
                            <input type="radio" name="optionsM" id="btnHicaz" autocomplete="off"> Hicaz
                        </label>
                    </div>

                </div>

                <div class="form-group">

                    <h3>Instruments</h3>
                    <div class="btn-group btn-group-justified" data-toggle="buttons">
                        <label class="btn btn-info active">
                            <input type="checkbox" id="btnUd" autocomplete="off" checked> Ud
                        </label>
                        <label class="btn btn-info">
                            <input type="checkbox" id="btnKanun" autocomplete="off"> Kanun
                        </label>
                        <label class="btn btn-info">
                            <input type="checkbox" id="btnTanbur" autocomplete="off"> Tanbur
                        </label>
                        <label class="btn btn-info">
                            <input type="checkbox" id="btnNey" autocomplete="off"> Ney
                        </label>
                    </div>

                </div>

                <div class="form-group">

                    <h3>Tempo</h3>
                    <div class="btn-group btn-group-justified" data-toggle="buttons">
                        <label class="btn btn-info active">
                            <input type="radio" name="optionsT" id="btn80" autocomplete="off" checked> 80
                        </label>
                        <label class="btn btn-info">
                            <input type="radio" name="optionsT" id="btn100" autocomplete="off"> 100
                        </label>
                        <label class="btn btn-info">
                            <input type="radio" name="optionsT" id="btn120" autocomplete="off"> 120
                        </label>
                    </div>

                </div>

                <div class="form-group">

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

                <div class="form-group">

                    <h3>Musicality <span id="spnMusicality">(5)</span></h3>
                    <div class="btn-group btn-group-justified" data-toggle="buttons">
                        <input id="musicality" data-slider-id="musicalitySlider" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"
                               data-slider-tooltip="hide"/>
                    </div>

                </div>

                <div class="form-group">
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

                <div class="form-group">
                    <button type="button" id="btnGenerateMusic" class="btn btn-success btn-lg btn-block">Generate Music</button>
                </div>

            </form>
        </div>

    </div>
    <!-- /.row -->

</div>
<!-- /.container -->

<script type="text/javascript">
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

</script>
</body>

</html>

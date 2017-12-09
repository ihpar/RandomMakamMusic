<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="music">
    <meta name="author" content="ismail hakkı parlak, cem kösemen">
    <link rel="icon" href="images/favicon.png">

    <title class="lang" data-key="title">Good Luck!</title>
    <link href="https://fonts.googleapis.com/css?family=Oswald:400,700" rel="stylesheet">

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
            -webkit-transition: background-color 0.4s ease-in-out;
            -o-transition: background-color 0.4s ease-in-out;
            transition: background-color 0.4s ease-in-out;
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
            text-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
            color: #BDB298;
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
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .mo-btn label:after {
            left: 0.2em;
            width: 1em;
            height: 1em;
            margin: -0.5em 0 0;
            opacity: 0;
            background: #57726e;
            -webkit-transform: translate3d(-60px, 0, 0) scale(0.1);
            transform: translate3d(-60px, 0, 0) scale(0.1);
            -webkit-transition: opacity 0.25s ease-in-out, -webkit-transform 0.25s ease-in-out;
            transition: opacity 0.25s ease-in-out, -webkit-transform 0.25s ease-in-out;
            -o-transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
            transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
            transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out, -webkit-transform 0.25s ease-in-out;
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
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }

        .opts {
            margin-top: 0.2em;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }

        div.inline {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            -ms-flex-preferred-size: 5%;
            flex-basis: 5%;
        }

        .row {
            padding: 0 0 1em 0;
        }

        .last.row {
            padding-bottom: 0;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: end;
            -ms-flex-align: end;
            align-items: flex-end;
        }

        .song-builder, .song-player {
            -webkit-animation-duration: 0.5s;
            animation-duration: 0.5s;
            width: 100%;
            position: relative;
            min-height: calc(100vh - 32px);
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .pp-button {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            width: 0;
            height: 144px;
            border-color: transparent transparent transparent #96993d;
            -webkit-transition: 200ms all ease;
            -o-transition: 200ms all ease;
            transition: 200ms all ease;
            cursor: pointer;
            border-style: solid;
            border-width: 72px 0 72px 120px;
            margin: 0 auto;
            opacity: 0;
        }

        .pp-button.paused {
            border-style: double;
            border-width: 0px 0 0px 120px;
            border-color: transparent transparent transparent #BDB298;
        }

        #dv-loader-wrapper {
            position: absolute;
            width: 100%;
            top: 50%;
            margin-top: -126px;
        }

        #status-msg {
            text-align: center;
            position: relative;
            width: 100%;
            top: 12px;
        }

        #dv-player-controls {
            position: absolute;
            width: 100%;
            top: 50%;
            margin-top: -126px;
        }

        .flags {
            height: 56px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            float: right;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .flag {
            display: inline-block;
            margin: 0 0 0 8px;
            cursor: pointer;
            opacity: 0.4;
            border-radius: 3px;
            -webkit-transition: opacity 0.5s ease;
            -o-transition: opacity 0.5s ease;
            transition: opacity 0.5s ease;
        }

        .flag.active, .flag:hover {
            opacity: 0.85;
        }

        .lang {
            display: inline-block;
        }

        .text-animating {
            -webkit-animation-name: change;
            animation-name: change;
            -webkit-animation-duration: 400ms;
            animation-duration: 400ms;
            -webkit-animation-fill-mode: forwards;
            animation-fill-mode: forwards;
        }

        @-webkit-keyframes change {
            0% {
                opacity: 1;
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
            50% {
                opacity: 0;
                -webkit-transform: translateY(-50%);
                transform: translateY(-50%);
            }
            100% {
                opacity: 1;
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
        }

        @keyframes change {
            0% {
                opacity: 1;
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
            50% {
                opacity: 0;
                -webkit-transform: translateY(-50%);
                transform: translateY(-50%);
            }
            100% {
                opacity: 1;
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
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
                -webkit-box-flex: 1;
                -ms-flex-positive: 1;
                flex-grow: 1;
                -ms-flex-negative: 1;
                flex-shrink: 1;
                -ms-flex-preferred-size: 30%;
                flex-basis: 30%;
            }
        }
    </style>

    <link href="css/button.css" rel="stylesheet">
    <link href="css/slider.css" rel="stylesheet">
    <link href="css/tape.css" rel="stylesheet">
</head>

<body>

<!-- Page Content -->
<div class="container">
    <div class="song-builder">
        <div class="row">
            <h3>
                <span class="lang" data-key="makam">Makam</span>
                <div class="flags">
                    <img class="flag" src="images/Turkey.png" data-lang="TR">
                    <img class="flag active" src="images/United-States-of-America.png" data-lang="EN">
                </div>
            </h3>
            <div class="opts">
                <div class="mo-btn inline">
                    <input type="radio" name="optionsM" id="btnHicaz" class="makam" data-value="Hicaz" checked>
                    <label for="btnHicaz"><span class="lang" data-key="hijaz">Hijaz</span></label>
                </div>
                <div class="mo-btn inline">
                    <input type="radio" name="optionsM" id="btnUssak" class="makam" data-value="Ussak">
                    <label for="btnUssak"><span class="lang" data-key="ussak">Ussak</span></label>
                </div>
                <div class="mo-btn inline" style="height: 0;"></div>
                <div class="mo-btn inline" style="height: 0;"></div>
            </div>
        </div>

        <div class="row">
            <h3 class="lang" data-key="orchestra">Orchestra</h3>
            <div class="opts">
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnUd" class="instrument" data-value="Ud" checked>
                    <label for="btnUd"><span class="lang" data-key="oud">Oud</span></label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnKanun" class="instrument" data-value="Kanun" checked>
                    <label for="btnKanun"><span class="lang" data-key="kanun">Kanun</span></label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnTanbur" class="instrument" data-value="Tanbur" checked>
                    <label for="btnTanbur"><span class="lang" data-key="tanbur">Tanbur</span></label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnNey" class="instrument" data-value="Ney" checked>
                    <label for="btnNey"><span class="lang" data-key="reed">Reed</span></label>
                </div>
            </div>
        </div>

        <div class="row">
            <h3 class="lang" data-key="tempo">Tempo</h3>
            <div class="opts">
                <div class="mo-btn inline">
                    <input type="radio" name="optionsT" id="btn80" class="tempo" data-value="80" checked>
                    <label for="btn80"><span class="lang" data-key="eighty">80</span></label>
                </div>
                <div class="mo-btn inline">
                    <input type="radio" name="optionsT" id="btn100" class="tempo" data-value="100">
                    <label for="btn100"><span class="lang" data-key="hundred">100</span></label>
                </div>
                <div class="mo-btn inline">
                    <input type="radio" name="optionsT" id="btn120" class="tempo" data-value="120">
                    <label for="btn120"><span class="lang" data-key="hundredTwenty">120</span></label>
                </div>
                <div class="mo-btn inline" style="height: 0;"></div>
            </div>
        </div>

        <div class="row">
            <h3 class="lang" data-key="percussion">Percussion</h3>
            <div class="opts">
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnBendir" class="percussion" data-value="Bendir" checked>
                    <label for="btnBendir"><span class="lang" data-key="bendir">Bendir</span></label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnErbane" class="percussion" data-value="Erbane" checked>
                    <label for="btnErbane"><span class="lang" data-key="erbane">Erbane</span></label>
                </div>
                <div class="mo-btn inline">
                    <input type="checkbox" id="btnKudum" class="percussion" data-value="Kudum" checked>
                    <label for="btnKudum"><span class="lang" data-key="kudum">Kudüm</span></label>
                </div>
                <div class="mo-btn inline" style="height: 0;"></div>
            </div>
        </div>

        <div class="row">
            <h3><span class="lang" data-key="musicality" style="width: 140px;">Musicality</span> <<span id="spnMusicality">3</span>></h3>
            <div class="opts" style="margin: 20px 0;">
                <input type="range" min="1" max="10" value="3" id="musicality" style="position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0;">
            </div>
        </div>

        <div class="last row">
            <button type="button" id="btnGenerateMusic" class="btn"><span class="lang" data-key="compose">Compose</span></button>
        </div>
    </div>
    <div class="song-player" style="display: none;">

        <div id="dv-lo-and-controls">
            <div id="dv-loader-wrapper">
                <div class="loader-wrapper">
                    <div class="loader">
                        <div class="roller"></div>
                        <div class="roller"></div>
                    </div>

                    <div id="loader2" class="loader">
                        <div class="roller"></div>
                        <div class="roller"></div>
                    </div>

                    <div id="loader3" class="loader">
                        <div class="roller"></div>
                        <div class="roller"></div>
                    </div>
                </div>

                <h3 id="status-msg">Composing...</h3>
            </div>
            <div id="dv-player-controls">
                <div class="pp-button paused"></div>
            </div>
        </div>

        <div class="last row">
            <button type="button" id="btnBackToComposing" class="btn lang" data-key="goBack">Back</button>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/rangeslider.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.0.5/howler.min.js"></script>

<script type="text/javascript">
    var pages = [];
    var bgColors = ["#26292C", "#443D3A"];
    var $ppBtn, $flags;
    var currentLang;
    var music = null;
    var musicPlaying = false;
    var tongue = {
        EN: {
            title: "Good Luck!",
            makam: "Makam",
            hijaz: "Hijaz",
            ussak: "Ussak",
            orchestra: "Orchestra",
            oud: "Oud",
            kanun: "Kanun",
            tanbur: "Tanbur",
            reed: "Reed",
            tempo: "Tempo",
            eighty: "80",
            hundred: "100",
            hundredTwenty: "120",
            percussion: "Percussion",
            bendir: "Bendir",
            erbane: "Erbane",
            kudum: "Kudum",
            musicality: "Musicality",
            compose: "Compose",
            goBack: "Back",
            songLoading: "Song loading...",
            mp3Error: "An error occurred while creating MP3 file.",
            mp3Exception: "An unexpected error occurred while creating MP3 file.",
            exPickInstrument: "Pick at least 1 instrument.",
            exPickPercussion: "Pick at least 1 percussion.",
            songComposing: "Composing...",
            mp3Creating: "Creating MP3 file...",
            canTakeTime: "Will take some time...",
            exSongComposing: "An error occurred while composing the song.",
            exSongException: "An unexpected error occurred while composing the song."
        },
        TR: {
            title: "Rastgele!",
            makam: "Makam",
            hijaz: "Hicaz",
            ussak: "Uşşak",
            orchestra: "Orkestra",
            oud: "Ud",
            kanun: "Kanun",
            tanbur: "Tanbur",
            reed: "Ney",
            tempo: "Tempo",
            eighty: "80",
            hundred: "100",
            hundredTwenty: "120",
            percussion: "Perküsyon",
            bendir: "Bendir",
            erbane: "Erbane",
            kudum: "Kudüm",
            musicality: "Müzikalite",
            compose: "Bestele",
            goBack: "Geri",
            songLoading: "Şarkı yükleniyor...",
            mp3Error: "MP3 oluşturulurken bir hata oluştu.",
            mp3Exception: "MP3 oluşturulurken beklenmedik bir hata oluştu.",
            exPickInstrument: "En az bir tane enstrüman seçiniz.",
            exPickPercussion: "En az bir tane perküsyon seçiniz.",
            songComposing: "Şarkı besteleniyor...",
            mp3Creating: "MP3 Oluşturuluyor...",
            canTakeTime: "Biraz zaman alacak...",
            exSongComposing: "Şarkı bestelenirken bir hata oluştu.",
            exSongException: "Şarkı bestelenirken beklenmedik bir hata oluştu."
        }
    };
    var checkTimeInterval;
    var start;

    function loadSong(url) {
        clearInterval(checkTimeInterval);

        music = new Howl({
            src: [url]
        });

        music.once("load", function () {
            $ppBtn.addClass("paused");
            togglePlayer(true);
            setTimeout(function () {
                music.volume(1);
                music.play();
                musicPlaying = true;
            }, 400);
        });
    }

    function setStatusMessage(msg) {
        var $stMsg = $("#status-msg");
        $stMsg.animate({opacity: 0}, 200, function () {
            $stMsg.html(msg);
            $stMsg.animate({opacity: 1}, 200);
        })
    }

    function togglePlayer(isVisible) {
        if (isVisible) {
            $("#dv-loader-wrapper").fadeOut(400);
            $ppBtn.css({opacity: 1});
        }
        else {
            $ppBtn.css({opacity: 0});
            $("#dv-loader-wrapper").fadeIn(400);
        }
    }

    function switchToPage(fromPage, toPage) {
        $("body").css({backgroundColor: bgColors[toPage]});
        var newLeft = (fromPage < toPage) ? -1000 : 1000;

        pages[fromPage].animate({
            left: newLeft,
            opacity: 0
        }, 200, function () {
            pages[fromPage].hide();
            pages[toPage].show().css({
                left: -1 * newLeft,
                opacity: 0
            }).animate({
                left: 0,
                opacity: 1
            }, 200);
        });
    }

    function buildSongFile(fileName) {
        $.ajax({
            method: "POST",
            url: "services/song-generator.php",
            dataType: "json",
            data: {
                fileName: fileName
            },
            success: function (msg) {
                if (typeof(msg) !== 'undefined' && msg && typeof(msg["res"]) !== 'undefined' && msg["res"] && msg["res"] === "OK") {
                    setStatusMessage(tongue[currentLang].songLoading);
                    setTimeout(function () {
                        loadSong(msg.src);
                    }, 500);
                }
                else {
                    alert(tongue[currentLang].mp3Error);
                }
            },
            error: function (msg) {
                console.log(msg);
                alert(tongue[currentLang].mp3Exception);
            }
        });
    }

    function setTimerMessage() {
        var now = new Date();
        var timeDiff = (now - start) / 1000;
        if(timeDiff > 4) {
            setStatusMessage(tongue[currentLang].canTakeTime);
        }
    }

    $(document).ready(function () {
        pages.push($(".song-builder"));
        pages.push($(".song-player"));

        currentLang = $(".flag.active").attr("data-lang");
        $flags = $(".flag");
        $flags.on("click", function () {
            var $this = $(this);
            var newLang = $this.attr("data-lang");
            if (newLang !== currentLang) {
                currentLang = newLang;
                $(".flag.active").removeClass("active");
                $this.addClass("active");

                $(".lang").each(function () {
                    var $that = $(this);
                    $that.addClass("text-animating");
                    setTimeout(function () {
                        $that.html(tongue[currentLang][$that.attr("data-key")]);
                    }, 200);

                    setTimeout(function () {
                        $that.removeClass("text-animating");
                    }, 500);

                });
            }
        });

        $ppBtn = $(".pp-button");
        $ppBtn.on("click", function () {
            var $this = $(this);
            if (music && $this.hasClass("paused")) {
                music.pause();
                musicPlaying = false;
            }
            else if (music) {
                if (!musicPlaying) {
                    music.play();
                }
                musicPlaying = true;
            }
            $this.toggleClass("paused");
        });

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
                alert(tongue[currentLang].exPickInstrument);
                return false;
            }
            // get selected percussion
            var percussion = [];
            $("input.percussion:checked").each(function () {
                percussion.push($(this).attr("data-value"));
            });

            if (percussion.length === 0) {
                alert(tongue[currentLang].exPickPercussion);
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

            $.ajax({
                method: "POST",
                url: "services/sheet-builder.php",
                dataType: "json",
                data: postParams,
                success: function (msg) {
                    if (typeof(msg) !== 'undefined' && msg && typeof(msg["res"]) !== 'undefined' && msg["res"] && msg["res"] === "OK") {
                        if (music && musicPlaying) {
                            music.pause();
                            music.off("load");
                            music = null;
                            musicPlaying = false;
                        }
                        setStatusMessage(tongue[currentLang].songComposing);
                        togglePlayer(false);
                        var data = msg["data"];
                        switchToPage(0, 1);
                        // TODO del timeout
                        setTimeout(function () {
                            buildSongFile(data.file);
                        }, 1000);

                        setTimeout(function () {
                            setStatusMessage(tongue[currentLang].mp3Creating);
                            start = new Date();
                            checkTimeInterval = setInterval(function () {
                                setTimerMessage();
                            }, 4000);
                        }, 1000);
                    }
                    else {
                        alert(tongue[currentLang].exSongComposing);
                    }
                },
                error: function (msg) {
                    console.log(msg);
                    alert(tongue[currentLang].exSongException);
                }
            });

        });

    });

</script>
</body>

</html>

<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
// sample call of this page
// turca/song-generator.php?m=Ussak&t=80&i=Ud,Ney,Tanbur,Kanun&p=Bendir,Erbane,Kudum&x=9
try {
    ini_set('max_execution_time', 300); //300 seconds = 5 minutes
    // get song parameters
    if (!isset($_POST["m"]) || !isset($_POST["t"]) || !isset($_POST["i"]) || !isset($_POST["p"]) || !isset($_POST["x"])) {
        echo "error-wrong-call";
        exit();
    }

    // TODO: del this
    $res = [
        "song" => [
            "n" => ["La1", "La1", "Sol1", "La1", "Si1", "Do1", "Re1", "Do1", "Si1", "Si1", "La1", "Sol1"],
            "d" => [4, 2, 2, 4, 2, 2, 4, 2, 2, 3, 1, 8]
        ],
        "src" => "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3"
    ];
    echo json_encode($res);
    exit();

    $makam = trim(htmlspecialchars($_POST["m"]));
    $tempo = trim(htmlspecialchars($_POST["t"]));
    $instruments = explode(",", trim(htmlspecialchars($_POST["i"])));
    $percussions = explode(",", trim(htmlspecialchars($_POST["p"])));
    $musicality = trim(htmlspecialchars($_POST["x"]));

    // validate input
    $availableMaqams = array("Ussak", "Hicaz");
    $availableTempos = array(80, 100, 120);
    $availableInstruments = array("Ney", "Tanbur", "Kanun", "Ud");
    $availablePercussions = array("Bendir", "Erbane", "Kudum");

    if (!in_array($makam, $availableMaqams)) {
        die("error-wrong-maqam-parameter");
    }

    if (!in_array($tempo, $availableTempos)) {
        die("error-wrong-tempo-parameter");
    }

    for ($i = 0; $i < count($instruments); $i++) {
        if (!in_array($instruments[$i], $availableInstruments)) {
            die("error-wrong-instrument-parameter");
        }
    }

    for ($i = 0; $i < count($percussions); $i++) {
        if (!in_array($percussions[$i], $availablePercussions)) {
            die("error-wrong-percussion-parameter");
        }
    }

    if ($musicality < 0 || $musicality > 10) {
        die("error-wrong-musicality-parameter");
    }
    // EOF validate input

    // get new random numbers
    include "random-getter.php";
    // include song creator
    include "engine.php";

    // generate song sheet with random data
    $songNotesAndDurations = generateSongNotesAndDurations($randomResult["notes"], $randomResult["durations"], $makam, $musicality);
    if (!isset($songNotesAndDurations)) {
        die("error-creating-song");
    }
    // EOF generate song sheet with random data

    // pick a unique file name
    while (true) {
        $uniStr = uniqid(rand(), true);
        if (strlen($uniStr) > 12) {
            $uniStr = substr($uniStr, 0, 12);
        }
        $fileName = $makam . "_" . $uniStr;
        if (!file_exists(sys_get_temp_dir() . $fileName)) break;
    }
    // EOF pick a unique file name

    /*
        Song file format:

        Ussak 80
        Bendir
        Kanun Ud Ney
        La0 La0 Sol0 La0 Si0 Do0 Re0 Do0 Si0 Si0 La0 Sol0
        4 2 2 4 2 2 4 2 2 3 1 24
    */

    // write song data to file
    $myfile = fopen($fileName, "w") or die("error-unable to open file!");

    // write makam and tempo
    $makamNTempo = $makam . " " . $tempo . PHP_EOL;
    fwrite($myfile, $makamNTempo);
    // write percussions
    $percussions = implode(" ", $percussions) . PHP_EOL;
    fwrite($myfile, $percussions);
    // write instruments
    $instruments = implode(" ", $instruments) . PHP_EOL;
    fwrite($myfile, $instruments);

    // write notes and durations
    $notes = array();
    $durations = array();
    // iterate on measures
    for ($i = 0; $i < count($songNotesAndDurations); $i++) {
        $currMeasure = $songNotesAndDurations[$i];
        $currMeasureNotes = $currMeasure["notes"];
        $currMeasureDurations = $currMeasure["durations"];

        for ($j = 0; $j < count($currMeasureNotes); $j++) {
            $useNatural = true;
            if ($musicality == 10 && $j < count($currMeasureNotes) - 1) {
                if (($currMeasureNotes[$j + 1]["index"] - $currMeasureNotes[$j]["index"]) > 0) {
                    $useNatural = false;
                }
            }
            array_push($notes, (($useNatural == true) ? $currMeasureNotes[$j]["natural"] : $currMeasureNotes[$j]["increasing"]));
            array_push($durations, $currMeasureDurations[$j]);
        }
    }

    fwrite($myfile, implode(" ", $notes) . PHP_EOL);

    // write durations
    fwrite($myfile, implode(" ", $durations) . PHP_EOL);

    // close file
    fclose($myfile);

    //echo "transcription-file-generated";
    //echo "<br>";

    // run matlab engine
    $cmd = str_replace("\\", "/", getcwd()) . "/Besteci.exe " . $fileName;
    shell_exec($cmd);

    //echo "wav-file-generated";
    //echo "<br>";

    // delete unnecessary files
    unlink($fileName);

    //echo "transcription-file-deleted";
    //echo "<br>";

    // master track
    $cmd = str_replace("\\", "/", getcwd()) . "/mrswatson --input " . $fileName . ".wav" .
        " --output " . "out_" . $fileName . ".wav" .
        " --plugin MarvelGEQ,1-Marvel.fxp;NightShine,2-Night.fxp;Maxwell_Smart,3-Maxwell.fxp";

    shell_exec($cmd);

    //echo "mastered-file-generated";
    //echo "<br>";

    // delete unnecessary filesize
    unlink($fileName . ".wav");

    //echo "old-wav-file-deleted";
    //echo "<br>";

    $cmd = str_replace("\\", "/", getcwd()) . "/ffmpeg -i " . "out_" . $fileName . ".wav" . " -vn -ar 44100 -ac 2 -b:a 320k -f mp3 " . $fileName . ".mp3";
    shell_exec($cmd);

    unlink("out_" . $fileName . ".wav");

    // delete old song if user repeats song generation
    if (isset($_SESSION["lastFileName"])) {
        if ($_SESSION["lastFileName"] != $fileName . ".mp3") {
            unlink($_SESSION["lastFileName"]);
        }
    }

    $_SESSION["lastFileName"] = $fileName . ".mp3";

    echo $fileName . ".mp3";
    //printMeasureByMeasure($songNotesAndDurations);
} catch (Exception $e) {
    echo "error-exception-" . $e->getMessage();
}
?>
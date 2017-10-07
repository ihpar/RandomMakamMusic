<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

try {
    ini_set('max_execution_time', 300); //300 seconds = 5 minutes
    // get song parameters
    if (!isset($_POST["fileName"])) {
        echo "error-wrong-call";
        exit();
    }
    $fileName = $_POST["fileName"];
    $res = [
        "res" => "OK",
        "src" => "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-7.mp3"
    ];
    echo json_encode($res);
    exit();
    //echo "transcription-file-generated";
    //echo "<br>";

    // run MATLAB engine
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

    // delete unnecessary file size
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

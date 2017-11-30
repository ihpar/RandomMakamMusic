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

    // run MATLAB engine
    $cmd = str_replace("\\", "/", getcwd()) . "/Besteci.exe " . $fileName;
    shell_exec($cmd);

    // delete unnecessary files
    unlink($fileName);

    // master track
    $cmd = str_replace("\\", "/", getcwd()) . "/mrswatson --input " . $fileName . ".wav" .
        " --output " . "out_" . $fileName . ".wav" .
        " --plugin MarvelGEQ,1-Marvel.fxp;NightShine,2-Night.fxp;Maxwell_Smart,3-Maxwell.fxp";

    shell_exec($cmd);

    // delete unnecessary file size
    unlink($fileName . ".wav");

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

    $res = [
        "res" => "OK",
        "src" => "services/" . $fileName . ".mp3"
    ];
    echo json_encode($res);
    //printMeasureByMeasure($songNotesAndDurations);
} catch (Exception $e) {
    echo "error-exception-" . $e->getMessage();
}

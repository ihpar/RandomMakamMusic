<?php
if (!isset($_POST["m"]) || !isset($_POST["t"]) || !isset($_POST["i"]) || !isset($_POST["p"]) || !isset($_POST["x"])) {
    echo "error-wrong-call";
    exit();
}

$makam = trim(htmlspecialchars($_POST["m"]));
$tempo = trim(htmlspecialchars($_POST["t"]));
$instruments = explode(",", trim(htmlspecialchars($_POST["i"])));
$percussion = explode(",", trim(htmlspecialchars($_POST["p"])));
$musicality = trim(htmlspecialchars($_POST["x"]));

// validate input
$availableMakamS = array("Ussak", "Hicaz");
$availableTempos = array(80, 100, 120);
$availableInstruments = array("Ney", "Tanbur", "Kanun", "Ud");
$availablePercussion = array("Bendir", "Erbane", "Kudum");

if (!in_array($makam, $availableMakamS)) {
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

for ($i = 0; $i < count($percussion); $i++) {
    if (!in_array($percussion[$i], $availablePercussion)) {
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
$fileName = "test";
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
$myFile = fopen($fileName, "w") or die("error-unable to open file!");

// write makam and tempo
$makamNTempo = $makam . " " . $tempo . PHP_EOL;
fwrite($myFile, $makamNTempo);
// write percussion
$percussion = implode(" ", $percussion) . PHP_EOL;
fwrite($myFile, $percussion);
// write instruments
$instruments = implode(" ", $instruments) . PHP_EOL;
fwrite($myFile, $instruments);

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

fwrite($myFile, implode(" ", $notes) . PHP_EOL);

// write durations
fwrite($myFile, implode(" ", $durations) . PHP_EOL);

// close file
fclose($myFile);

$res = [
    "res" => "OK",
    "data" => [
        "song" => [
            "n" => $notes,
            "d" => $durations
        ],
        "file" => $fileName
    ]
];
echo json_encode($res);

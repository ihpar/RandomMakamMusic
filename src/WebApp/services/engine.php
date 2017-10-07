<?php
	include "ussak.php";
	include "hicaz.php";
	
	function printMeasureByMeasure($notesAndDurations){
		for($j=0; $j<count($notesAndDurations); $j++) {
			$currMeasure = $notesAndDurations[$j];
			$currNotes = $currMeasure["notes"];
			$currDurations = $currMeasure["durations"];
			for ($i = 0; $i < count($currNotes); $i++) {
				echo $currNotes[$i]["index"] . " ";
			}
			echo "<br>";
			for ($i = 0; $i < count($currDurations); $i++) {
				echo $currDurations[$i] . " ";
			}
			echo "<br><br>";
		}
	}
	
	// generates measures wrt total measure duration
	function generateMeasures(&$notesAndDurations, $measureDuration, $maxMeasureCount) {
		$allNewMeasures = array();
		$measureCount = 0;
		for($j=0; $j<count($notesAndDurations); $j++) {
			$currMeasure = $notesAndDurations[$j];
			$currNotes = $currMeasure["notes"];
			$currDurations = $currMeasure["durations"];
			
			// divide to measures
			$newMeasure = array("notes" => array(), "durations" => array());
			$newMeasureNotes = array();
			$newMeasureDurations = array();
			
			$addsUpToThusFar = 0;
			$endReached = false;
			
			for ($i = 0; $i < count($currDurations); $i++) {
				$currDur = $currDurations[$i];
				$currNote = $currNotes[$i];
				
				if($addsUpToThusFar + $currDur >= $measureDuration) {
					$endReached = true;
					$diff = $addsUpToThusFar + $currDur - $measureDuration;
					$currDur = $currDur - $diff;
					$addsUpToThusFar = 0;
				}
				else {
					$addsUpToThusFar += $currDur;
					$endReached = false;
				}
				array_push($newMeasureDurations, $currDur);
				array_push($newMeasureNotes, $currNote);
				
				if($endReached) { // got a full measure
					$newMeasure = array("notes" => $newMeasureNotes, "durations" => $newMeasureDurations);
					array_push($allNewMeasures, $newMeasure); 
					$newMeasureNotes = array();
					$newMeasureDurations = array();
					$measureCount++;
					if($measureCount >= $maxMeasureCount) break;
				}
			}
			
		}
		$notesAndDurations = $allNewMeasures;
		// fix cases like 1,4 - 4,3 - 1,8 - 8,3 - 3,6
		fixInternalMeasureDurations($notesAndDurations, $measureDuration);
	}
	
	// fix cases like 1,4 - 4,3 - 1,8 - 8,3 - 3,6
	function fixInternalMeasureDurations(&$notesAndDurations, $measureDuration) {
		for($j=0; $j<count($notesAndDurations); $j++) {
			$currMeasure = $notesAndDurations[$j];
			$currDurations = $currMeasure["durations"]; // ex: 
			// [4, 8, 4, 2, 8, 3, 1, 2]
			// [4, 2, 4, 4, 8, 6, 2, 2]
			// [1, 4, 6, 2, 4, 3, 1, 8, 3]
			// [1, 1, 8, 3, 1, 3, 1, 3, 1, 1, 1, 6, 2]
			fixInternalMeasureDuration($currDurations);
			$currMeasure["durations"] = $currDurations;
			$notesAndDurations[$j] = $currMeasure;
		}
	}
	
	function fixInternalMeasureDuration(&$durations) {
		// case: odd number of durations and last duration is odd
		if(count($durations)%2 == 1 && $durations[ count($durations)-1 ]%2 == 1) {
			$lastDuration = $durations[ count($durations)-1 ];
			for($i=0; $i<count($durations)-1; $i=$i+2) {
				// check each pair to see if they add up to some even number
				$currDur = $durations[$i];
				$nextDur = $durations[$i+1];
				if(($currDur+$nextDur) % 2 == 1) {
					// swap durations
					if($currDur%2==0) {
						$durations[$i] = $lastDuration;
						$durations[ count($durations)-1 ] = $currDur;
					}
					else {
						$durations[$i+1] = $lastDuration;
						$durations[ count($durations)-1 ] = $nextDur;
					}
					break;
				}
			}	
		}		
		else {
			// now we are sure that last duration will not cause any problems
			$change = 0;
			for($i=0; $i<count($durations)-1; $i=$i+2) {
				
				// check each pair to see if they add up to some odd number
				$currDur = $durations[$i];
				$nextDur = $durations[$i+1];
				if(($currDur+$nextDur) % 2 == 1) { 
					// one of curr or next is odd and the other is even
					if($currDur%2 == 1) {
						if($currDur == 1) {
							$change = ($change == 0) ? 1 : $change;
						}
						if($currDur == 3) {
							$change = ($change == 0) ? -1 : $change;
						}
						$currDur = $currDur + $change;
					}
					
					if($nextDur%2 == 1) {
						if($nextDur == 1) {
							$change = ($change == 0) ? 1 : $change;
						}
						if($nextDur == 3) {
							$change = ($change == 0) ? -1 : $change;
						}
						$nextDur = $nextDur + $change;
					}
					$durations[$i] = $currDur;
					$durations[$i+1] = $nextDur;
					$change = -1*$change;
				}
			}
			// some durations might be set to 0, fix them
			for($i=0; $i<count($durations)-1; $i=$i+2) {
				$currDur = $durations[$i];
				$nextDur = $durations[$i+1];
				if($currDur == 0) {
					$currDur = $nextDur/2;
					$nextDur = $nextDur/2;
				}
				if($nextDur == 0) {
					$nextDur = $currDur/2;
					$currDur = $currDur/2;
				}
				
				$durations[$i] = $currDur;
				$durations[$i+1] = $nextDur;
			}
		}
	}
	
	// corrects odd durations like 3,3  
	function correctOddDurations(&$notesAndDurations) {
		for($j=0; $j<count($notesAndDurations); $j++) {
			$currMeasure = $notesAndDurations[$j];
			$currDurations = $currMeasure["durations"];
			// adjust note durations
			// if currDur == 1 or 3 then nextDur = 1
			$addsUpToThusFar = 0;
			
			// correct durations
			for ($i = 0; $i < count($currDurations) - 1; $i++) {
				$currDur = $currDurations[$i];
				$nextDur = $currDurations[$i+1];
				if($addsUpToThusFar % 2 == 0 && $currDur % 2 == 1) {
					$nextDur = 1;
					$currDurations[$i+1] = $nextDur;
				}
				$addsUpToThusFar += $currDur;
			}
			
			$currMeasure["durations"] = $currDurations;
			$notesAndDurations[$j] = $currMeasure;
		}
	}
	
	// squeezes notes to some given interval
	function squeezeNotesToMakamInterval(&$notesAndDurations, $lowerLimit, $higherLimit, $makamNotes) {
		for($i=0; $i<count($notesAndDurations); $i++) {
			$currMeasure = $notesAndDurations[$i];
			$currNotes = $currMeasure["notes"];
			
			$ratio = ($higherLimit - $lowerLimit) / (count($makamNotes) - 1);
			
			for ($j = 0; $j < count($currNotes); $j++) {
				$currNotes[$j] = $makamNotes[ floor($lowerLimit + $ratio*$currNotes[$j]["index"]) ];
			}
			
			$currMeasure["notes"] = $currNotes;
			$notesAndDurations[$i] = $currMeasure;
		}
	}
	
	// adds final note
	function addEndingNote(&$notesAndDurations, $currMakamObj) {
		$lastMeasure = $notesAndDurations[ count($notesAndDurations)-1 ];
		$lastNote = $lastMeasure["notes"][ count($lastMeasure["notes"])-1 ];
		
		$kararNotes = $currMakamObj->getKarar();
		$minDiff = count($currMakamObj->getNotes());
		$kararNoteIndex = 0;
		for($i=0; $i<count($kararNotes); $i++) {
			if(abs($kararNotes[$i] - $lastNote["index"]) < $minDiff) {
				$minDiff = abs($kararNotes[$i] - $lastNote["index"]);
				$kararNoteIndex = $i;
			}
		}
		
		$endingMeasureNotes = array($currMakamObj->getNotes()[ $kararNotes[$kararNoteIndex] ]);
		$endingMeasureDurations = array(8);
		$endingMeasure = array("notes" => $endingMeasureNotes, "durations" => $endingMeasureDurations);
		array_push($notesAndDurations, $endingMeasure); 
	}
	
	// some measures should start with certain notes
	function beautifyMeasureStartings(&$notesAndDurations, $currMakamObj, $eachMeasureCount) {
		$commonStarts = $currMakamObj->getCommonStarts();
		$makamNotes = $currMakamObj->getNotes();
		
		for($i=0; $i<count($notesAndDurations); $i++) {
			if(($i % $eachMeasureCount) != 0) {
				continue;
			}
			
			$currMeasure = $notesAndDurations[$i];
			$currNotes = $currMeasure["notes"];
			
			if(!in_array($currNotes[0]["index"], $commonStarts)) {
				// find closest common start
				$min = 100;
				$index = null;
				for($j=0; $j<count($commonStarts); $j++) {
					if(abs($commonStarts[$j] - $currNotes[0]["index"]) < $min) {
						$min = abs($commonStarts[$j] - $currNotes[0]["index"]);
						$index = $commonStarts[$j];
					}
				}
				if($index != null) {
					$currNotes[0] = $makamNotes[$index];
					$currMeasure["notes"] = $currNotes;
					$notesAndDurations[$i] = $currMeasure;
				}
			}
		}
	}
	
	// pick a chorus and play it after each nth measure
	function introduceChorus(&$notesAndDurations, $chorusLength, $verseCount) {
		$totalNumOfMeasures = count($notesAndDurations);
		$maxDistinctSections = floor($totalNumOfMeasures/$chorusLength);
		if($maxDistinctSections < 2) {
			throw new Exception("error-not-enough-numbers-for-verseCount:" . $verseCount. "-and-chorusLength:" . $chorusLength);
		}
		$chorus = array();
		$verses = array();
		
		// extract chorus
		for($i=0; $i<$chorusLength; $i++) {
			$currMeasure = $notesAndDurations[$i];
			array_push($chorus, $currMeasure); 
		}
		
		// extract verses
		for($i=$chorusLength; $i<$chorusLength + $chorusLength*$verseCount; $i++) {
			$idx = $i % $totalNumOfMeasures;
			if($idx < $chorusLength) {
				$idx += $chorusLength;
			}
			$currMeasure = $notesAndDurations[$idx];
			array_push($verses, $currMeasure); 
		}
		
		$notesAndDurations = array();
		// combine verses and chorus
		for($i=0; $i<$verseCount; $i++) {
			// push $i'th verse
			for($j=0; $j<$chorusLength; $j++) {
				array_push($notesAndDurations, $verses[ $j + $chorusLength*$i ]); 	
			}
			// push chorus
			for($j=0; $j<$chorusLength; $j++) {
				array_push($notesAndDurations, $chorus[$j]); 	
			}
		}
	}
	
	// fix abnormal jumps
	function fixJumps(&$notesAndDurations, $currMakamObj) {
		$commonJums = $currMakamObj->getCommonJumps();
		$makamNotes = $currMakamObj->getNotes();
		
		for($i=0; $i<count($notesAndDurations); $i++) {
			$currMeasure = $notesAndDurations[$i];
			$currNotes = $currMeasure["notes"];
			for($j=0; $j<count($currNotes) - 1; $j++) {
				$currNote = $currNotes[$j]["index"];
				$nextNote = $currNotes[$j+1]["index"];
				if(abs($currNote-$nextNote) > 2) {
					// notes are jumping
					$minDiff = count($makamNotes) * count($makamNotes);
					$commonJumpIndex = 0;
					for($k=0; $k<count($commonJums); $k++) {
						$diff = sqrt( pow(($commonJums[$k][0] - $currNote), 2) + pow(($commonJums[$k][1] - $nextNote), 2) );
						if($diff < $minDiff) {
							$minDiff = $diff;
							$commonJumpIndex = $k;
						}
					}
					$currNotes[$j] = $makamNotes[ $commonJums[$commonJumpIndex][0] ];
					$currNotes[$j+1] = $makamNotes[ $commonJums[$commonJumpIndex][1] ];
				}
			}
			$currMeasure["notes"] = $currNotes;
			$notesAndDurations[$i] = $currMeasure;
		}
	}
	
	// introduce some 4th combinations
	function insertFourthCombinations(&$notesAndDurations, $currMakamObj, $durationPatterns, $probability) {
		$fourthCombinations = $currMakamObj->getFourthCombinations();
		$makamNotes = $currMakamObj->getNotes();
		for($i=0; $i<count($notesAndDurations); $i++) {
			$currMeasure = $notesAndDurations[$i];
			$currNotes = $currMeasure["notes"];
			$currDurations = $currMeasure["durations"];
			if(count($currDurations) < 4) continue;
			
			for($j=0; ($j+4)<=count($currDurations); $j=$j+2) {
				$fPattern = array($currDurations[$j], $currDurations[$j+1], $currDurations[$j+2], $currDurations[$j+3]);
				if(in_array($fPattern, $durationPatterns)) {
					// will insert a new comb 
					$insertCondition = rand(0 , 100) <= $probability ? true : false;
					if($insertCondition) {
						// find closest 4th comb from makam 
						$newComb = getClosestComb( array($currNotes[$j]["index"], $currNotes[$j+1]["index"], $currNotes[$j+2]["index"], $currNotes[$j+3]["index"]), $fourthCombinations );
						// newComb'u eskisinin yerine monte et
						$currNotes[$j] = $makamNotes[ $newComb[0] ];
						$currNotes[$j+1] = $makamNotes[ $newComb[1] ];
						$currNotes[$j+2] = $makamNotes[ $newComb[2] ];
						$currNotes[$j+3] = $makamNotes[ $newComb[3] ];
					}
					$j += 2;
				}
			}
			$currMeasure["notes"] = $currNotes;
			$notesAndDurations[$i] = $currMeasure;
		}
	}
	
	function getClosestComb($actualComb, $possibleCombs) {
		$minDiff = 1000;
		$combIndex = 0;
		for($i=0; $i<count($possibleCombs); $i++) {
			$currPossComb = $possibleCombs[$i];
			$diff = sqrt( pow(($currPossComb[0] - $actualComb[0]), 2) + pow(($currPossComb[1] - $actualComb[1]), 2) +
			pow(($currPossComb[2] - $actualComb[2]), 2) + pow(($currPossComb[3] - $actualComb[3]), 2) );
			if($diff < $minDiff) {
				$minDiff = $diff;
				$combIndex = $i;
			}
		}
		
		return $possibleCombs[$combIndex];
	}
	
	// max jump per measure controlled
	function fixJumpsPerMeasure(&$notesAndDurations, $currMakamObj, $maxJumpsPerMeasure) {
		$makamNotes = $currMakamObj->getNotes();
		for($i=0; $i<count($notesAndDurations); $i++) {
			$currMeasure = $notesAndDurations[$i];
			$currNotes = $currMeasure["notes"];
			$jumpCount = 0;
			for($j=0; $j<count($currNotes)-1; $j++) {
				$currNote = $currNotes[$j];
				$nextNote = $currNotes[$j+1];
				if(abs($currNote["index"] - $nextNote["index"]) > 2) {
					$jumpCount++;
					$diff = rand(0 , 100) <= 50 ? 1 : 2;
					if($jumpCount > $maxJumpsPerMeasure) {
						// we had a jump already
						if($currNote["index"] > $nextNote["index"]) {
							$nextNote = $makamNotes[ $nextNote["index"] + $diff ];
						}
						else {
							$nextNote = $makamNotes[ $nextNote["index"] - $diff ];
						}
						$currNotes[$j+1] = $nextNote;
					}
				}
			}
			$currMeasure["notes"] = $currNotes;
			$notesAndDurations[$i] = $currMeasure;
		}
	}
	
	// put smooth transitions between measures
	function fixJumpsBetweenMeasures(&$notesAndDurations, $currMakamObj, $measureGroupCount) {
		$makamNotes = $currMakamObj->getNotes();
		for($i=0; $i<floor(count($notesAndDurations)/$measureGroupCount); $i++) {
			for($j=0; $j<$measureGroupCount-1; $j++) {
				$currMeasure = $notesAndDurations[$i*$measureGroupCount + $j];
				$currMeasureNotes = $currMeasure["notes"];
				$nextMeasure = $notesAndDurations[$i*$measureGroupCount + $j+1];
				$nextMeasureNotes = $nextMeasure["notes"];
				
				$currMeasureLastNote = $currMeasureNotes[ count($currMeasureNotes)-1 ];
				$nextMeasureFirstNote = $nextMeasureNotes[0];
				
				$diff = abs($currMeasureLastNote["index"]-$nextMeasureFirstNote["index"]);
				if($diff > 2) {
					// we have a jump between measures
					$newDiff = 1;
					if($diff > 4) $newDiff = 2;	
					if($diff > 6) $newDiff = 3;
					if($currMeasureLastNote["index"] > $nextMeasureFirstNote["index"]) {
						$currMeasureNotes[ count($currMeasureNotes)-1 ] = $makamNotes[ $currMeasureLastNote["index"] - $newDiff ];
						$nextMeasureNotes[0] = $makamNotes[ $nextMeasureFirstNote["index"] + $newDiff ];
					}
					else {
						$currMeasureNotes[ count($currMeasureNotes)-1 ] = $makamNotes[ $currMeasureLastNote["index"] + $newDiff ];
						$nextMeasureNotes[0] = $makamNotes[ $nextMeasureFirstNote["index"] - $newDiff ];
					}
				}
				// set new values
				$currMeasure["notes"] = $currMeasureNotes;
				$notesAndDurations[$i*$measureGroupCount + $j] = $currMeasure;
				$nextMeasure["notes"] = $nextMeasureNotes;
				$notesAndDurations[$i*$measureGroupCount + $j+1] = $nextMeasure;
			}
		}
	}
	
	// split 6 -> 4, 2
	function splitSixes(&$notesAndDurations, $currMakamObj) {
		$makamNotes = $currMakamObj->getNotes();
		for($i=0; $i<count($notesAndDurations); $i++) {
			$currMeasure = $notesAndDurations[$i];
			$currNotes = $currMeasure["notes"];
			$notesHolder = array();
			$currDurations = $currMeasure["durations"];
			$durationsHolder = array();
			for($j=0; $j<count($currDurations); $j++) {
				if($currDurations[$j] == 6) {
					array_push($notesHolder, $currNotes[$j]);
					array_push($durationsHolder, 4);
					
					if($j < count($currNotes)-1) {
						$nextNote = $currNotes[$j+1];
						$diff = floor( ($currNotes[$j]["index"]-$nextNote["index"]) / 2 );
						array_push($notesHolder, $makamNotes[ $currNotes[$j]["index"] - $diff ]);
					}
					else {
						array_push($notesHolder, $currNotes[$j]);
					}
					array_push($durationsHolder, 2);
				}
				else {
					array_push($notesHolder, $currNotes[$j]);
					array_push($durationsHolder, $currDurations[$j]);
				}
			}
			$currMeasure["notes"] = $notesHolder;
			$currMeasure["durations"] = $durationsHolder;
			$notesAndDurations[$i] = $currMeasure;
		}
	}
	
	function fixRepeatedNotes(&$notesAndDurations, $currMakamObj, $noteDuration) {
		$makamNotes = $currMakamObj->getNotes();
		for($i=0; $i<count($notesAndDurations); $i++) {
			$currMeasure = $notesAndDurations[$i];
			$currNotes = $currMeasure["notes"];
			$currDurations = $currMeasure["durations"];
			
			for($j=0; $j<count($currDurations)-1; $j++) {
				$currDuration = $currDurations[$j];
				$nextDuration = $currDurations[$j+1];
				if(($currDuration == $noteDuration) && ($nextDuration == $noteDuration)) {
					if($currNotes[$j]["index"] == $currNotes[$j+1]["index"]) {
						// notes are repeating, fix them
						if($j+2 < count($currDurations)) {
							// has a next note
							if($currNotes[$j+2]["index"] != $currNotes[$j+1]["index"]) {
								// move the note towards the next
								$diff = ceil(abs($currNotes[$j+2]["index"] - $currNotes[$j+1]["index"])/2);
								$diff = $currNotes[$j+2]["index"] > $currNotes[$j+1]["index"] ? $diff : -1*$diff; 
								$currNotes[$j+1] = $makamNotes[ $currNotes[$j+1]["index"] + $diff ];
							}
							else {
								// move the note randomly
								$diff = rand(0 , 100) <= 50 ? 1 : -1;
								$currNotes[$j+1] = $makamNotes[ $currNotes[$j+1]["index"] + $diff ];
							}
						}
						else {
							// last 2 notes in the measure
							$diff = rand(0 , 100) <= 50 ? 1 : -1;
							$currNotes[$j+1] = $makamNotes[ $currNotes[$j+1]["index"] + $diff ];
						}
					}
				}
			}
			
			$currMeasure["notes"] = $currNotes;
			$notesAndDurations[$i] = $currMeasure;
		}
	}
	
	function fixTripleSameNotes(&$notesAndDurations, $currMakamObj) {
		$makamNotes = $currMakamObj->getNotes();
		for($i=0; $i<count($notesAndDurations); $i++) {
			$currMeasure = $notesAndDurations[$i];
			$currNotes = $currMeasure["notes"];
			
			for($j=0; $j<count($currNotes)-2; $j++) {
				$currNote = $currNotes[$j];
				$nextNote = $currNotes[$j+1];
				$nextNextNote = $currNotes[$j+2];
				if( ($currNotes[$j]["index"] == $currNotes[$j+1]["index"]) && ($currNotes[$j+1]["index"] == $currNotes[$j+2]["index"]) ) {
					$diff = rand(0 , 100) <= 50 ? 1 : -1;
					// check bounds
					if( ($currNotes[$j+1]["index"] + $diff) > (count($makamNotes)-1) ) {
						$diff = -1;
					}
					if( ($currNotes[$j+1]["index"] + $diff) < 0 ) {
						$diff = 1;
					}
					$currNotes[$j+1] = $makamNotes[ $currNotes[$j+1]["index"] + $diff ];
				}
			}
			
			$currMeasure["notes"] = $currNotes;
			$notesAndDurations[$i] = $currMeasure;
		}
	}
	
	// make the endings similar to known songs
	function fixChorusEndings(&$notesAndDurations, $chorusMeasureCount, $verseCount, $currMakamObj) {
		$makamNotes = $currMakamObj->getNotes();
		$chorusEndings = $currMakamObj->getChorusEndings();
		for($i=0; $i<$verseCount*2; $i++) {
			if($i % 2 == 0) continue;
			$chorusStartIndex = $i*$chorusMeasureCount;
			$chorusLastMeasure = $notesAndDurations[$chorusStartIndex + $chorusMeasureCount - 1];
			$currNotes = $chorusLastMeasure["notes"];
			
			for($j=0; $j<count($chorusEndings); $j++) {
				$endingCandidate = $chorusEndings[$j];
				$minDiff = 20*20*20;
				$closestCandidateIndex = 0;
				
				$diff = 0;
				for($ic=count($endingCandidate)-1, $in=count($currNotes)-1; $ic>=0 && $in>=0; $ic--, $in--) {
					$diff += pow(($endingCandidate[$ic] - $currNotes[$in]["index"]), 2);
				}
				$diff = sqrt($diff);
				if($diff < $minDiff) {
					$closestCandidateIndex = $j;
					$minDiff = $diff;
				}
			}
			
			$endingCandidate = $chorusEndings[$closestCandidateIndex];
			for($ic=count($endingCandidate)-1, $in=count($currNotes)-1; $ic>=0 && $in>=0; $ic--, $in--) {
				$currNotes[$in] = $makamNotes[ $endingCandidate[$ic] ];
			}
			
			$chorusLastMeasure["notes"] = $currNotes;
			$notesAndDurations[$chorusStartIndex + $chorusMeasureCount - 1] = $chorusLastMeasure;
		}
	}
	
	// BACKBONE
	function generateSongNotesAndDurations($notes, $durations, $makam, $musicality) {
		$currMakamObj = null;
		// set active makam
		if($makam == "Ussak") {
			$currMakamObj = new Ussak();
		}
		if($makam == "Hicaz") {
			$currMakamObj = new Hicaz();
		}
		// if some invalid makam comes kill
		if(!isset($currMakamObj)) {
			return null;
		}
		
		$pNotes = array();
		$pDurations = array();
		
		// calculate notes
		$makamNotes = $currMakamObj->getNotes();
		
		for ($i = 0; $i < count($notes); $i++) {
			$currNote = $makamNotes[ $notes[$i] % count($makamNotes) ];
			array_push($pNotes, $currNote);
			if($musicality < 3 && $i>=63) break;
		}
		
		// calculate note durations
		$availableDurations = array(1, 2, 3, 4, 1, 2, 4, 6, 8, 2, 4);
		
		for ($i = 0; $i < count($durations); $i++) {
			$currDur = $availableDurations[ $durations[$i] % count($availableDurations) ];
			array_push($pDurations, $currDur);
			if($musicality < 3 && $i>=63) break;
		}
		
		$notesAndDurations = array(
		array("notes" => $pNotes, "durations" => $pDurations)
		);
		
		
		if($musicality == 2) {
			squeezeNotesToMakamInterval($notesAndDurations, $currMakamObj->getYeden(), count($makamNotes)-1, $makamNotes);
			correctOddDurations($notesAndDurations);
		}
		
		if($musicality == 3) {
			squeezeNotesToMakamInterval($notesAndDurations, $currMakamObj->getYeden(), count($makamNotes)-1, $makamNotes);
			correctOddDurations($notesAndDurations);
			generateMeasures($notesAndDurations, 32, 4);
			addEndingNote($notesAndDurations, $currMakamObj);
		}
		
		if($musicality == 4) {
			squeezeNotesToMakamInterval($notesAndDurations, $currMakamObj->getYeden(), count($makamNotes)-1, $makamNotes);
			correctOddDurations($notesAndDurations);
			generateMeasures($notesAndDurations, 16, 8);
			beautifyMeasureStartings($notesAndDurations, $currMakamObj, 4);
			introduceChorus($notesAndDurations, 4, 2);
			fixJumps($notesAndDurations, $currMakamObj);
			addEndingNote($notesAndDurations, $currMakamObj);
		}
		
		if($musicality == 5) {
			squeezeNotesToMakamInterval($notesAndDurations, $currMakamObj->getYeden(), count($makamNotes)-1, $makamNotes);
			correctOddDurations($notesAndDurations);
			generateMeasures($notesAndDurations, 16, 8);
			beautifyMeasureStartings($notesAndDurations, $currMakamObj, 4);
			
			fixJumps($notesAndDurations, $currMakamObj);
			
			$durationPatterns = array(
			array(1, 1, 1, 1),
			array(2, 2, 2, 2),
			array(3, 1, 2, 2),
			array(3, 1, 3, 1)
			);
			// 60% probaility of introducing 4th combinations
			insertFourthCombinations($notesAndDurations, $currMakamObj, $durationPatterns, 50);
			
			introduceChorus($notesAndDurations, 4, 2);
			
			addEndingNote($notesAndDurations, $currMakamObj);
		}
		
		if($musicality == 6) {
			squeezeNotesToMakamInterval($notesAndDurations, $currMakamObj->getYeden(), count($makamNotes)-1, $makamNotes);
			correctOddDurations($notesAndDurations);
			generateMeasures($notesAndDurations, 16, 8);
			beautifyMeasureStartings($notesAndDurations, $currMakamObj, 4);
			
			fixJumps($notesAndDurations, $currMakamObj);
			
			$durationPatterns = array(
			array(1, 1, 1, 1),
			array(2, 2, 2, 2),
			array(3, 1, 2, 2),
			array(6, 2, 4, 4)
			);
			
			insertFourthCombinations($notesAndDurations, $currMakamObj, $durationPatterns, 75);
			
			fixJumpsBetweenMeasures($notesAndDurations, $currMakamObj, 4);
			
			fixJumpsPerMeasure($notesAndDurations, $currMakamObj, 1);
			
			introduceChorus($notesAndDurations, 4, 2);
			
			addEndingNote($notesAndDurations, $currMakamObj);
		}
		
		if($musicality == 7) {
			squeezeNotesToMakamInterval($notesAndDurations, $currMakamObj->getYeden(), count($makamNotes)-1, $makamNotes);
			correctOddDurations($notesAndDurations);
			generateMeasures($notesAndDurations, 16, 8);
			beautifyMeasureStartings($notesAndDurations, $currMakamObj, 4);
			
			fixJumps($notesAndDurations, $currMakamObj);
			
			$durationPatterns = array(
			array(1, 1, 1, 1),
			array(2, 2, 2, 2),
			array(3, 1, 2, 2),
			array(2, 2, 1, 1)
			);
			
			splitSixes($notesAndDurations, $currMakamObj);
			
			insertFourthCombinations($notesAndDurations, $currMakamObj, $durationPatterns, 75);
			
			fixJumpsBetweenMeasures($notesAndDurations, $currMakamObj, 4);
			
			fixJumpsPerMeasure($notesAndDurations, $currMakamObj, 1);
			
			fixRepeatedNotes($notesAndDurations, $currMakamObj, 1);
			
			introduceChorus($notesAndDurations, 4, 2);
			
			addEndingNote($notesAndDurations, $currMakamObj);
		}
		
		if($musicality == 8) {
			squeezeNotesToMakamInterval($notesAndDurations, $currMakamObj->getYeden(), count($makamNotes)-1, $makamNotes);
			correctOddDurations($notesAndDurations);
			generateMeasures($notesAndDurations, 16, 8);
			beautifyMeasureStartings($notesAndDurations, $currMakamObj, 4);
			
			fixJumps($notesAndDurations, $currMakamObj);
			
			$durationPatterns = array(
			array(1, 1, 1, 1),
			array(2, 2, 2, 2),
			array(3, 1, 2, 2),
			array(2, 2, 1, 1),
			array(6, 2, 4, 4)
			);
			
			insertFourthCombinations($notesAndDurations, $currMakamObj, $durationPatterns, 80);
			
			splitSixes($notesAndDurations, $currMakamObj);
			
			fixJumpsBetweenMeasures($notesAndDurations, $currMakamObj, 4);
			
			fixJumpsPerMeasure($notesAndDurations, $currMakamObj, 1);
			
			fixTripleSameNotes($notesAndDurations, $currMakamObj);
			
			fixRepeatedNotes($notesAndDurations, $currMakamObj, 1);
			
			introduceChorus($notesAndDurations, 4, 2);
			
			addEndingNote($notesAndDurations, $currMakamObj);
		}
		
		if($musicality == 9) {
			squeezeNotesToMakamInterval($notesAndDurations, $currMakamObj->getYeden(), count($makamNotes)-1, $makamNotes);
			correctOddDurations($notesAndDurations);
			generateMeasures($notesAndDurations, 16, 8);
			beautifyMeasureStartings($notesAndDurations, $currMakamObj, 4);
			
			fixJumps($notesAndDurations, $currMakamObj);
			
			$durationPatterns = array(
			array(1, 1, 1, 1),
			array(2, 2, 2, 2),
			array(3, 1, 2, 2),
			array(2, 2, 1, 1),
			array(6, 2, 4, 4)
			);
			
			insertFourthCombinations($notesAndDurations, $currMakamObj, $durationPatterns, 80);
			
			splitSixes($notesAndDurations, $currMakamObj);
			
			fixJumpsBetweenMeasures($notesAndDurations, $currMakamObj, 4);
			
			fixJumpsPerMeasure($notesAndDurations, $currMakamObj, 1);
			
			fixTripleSameNotes($notesAndDurations, $currMakamObj);
			
			fixRepeatedNotes($notesAndDurations, $currMakamObj, 1);
			
			introduceChorus($notesAndDurations, 4, 2);
			
			fixChorusEndings($notesAndDurations, 4, 2, $currMakamObj);
			
			addEndingNote($notesAndDurations, $currMakamObj);
		}
		
		if($musicality == 10) {
			squeezeNotesToMakamInterval($notesAndDurations, $currMakamObj->getYeden(), count($makamNotes)-1, $makamNotes);
			correctOddDurations($notesAndDurations);
			generateMeasures($notesAndDurations, 16, 8);
			beautifyMeasureStartings($notesAndDurations, $currMakamObj, 4);
			
			fixJumps($notesAndDurations, $currMakamObj);
			
			$durationPatterns = array(
			array(1, 1, 1, 1),
			array(2, 2, 2, 2),
			array(3, 1, 2, 2),
			array(2, 2, 1, 1),
			array(6, 2, 4, 4)
			);
			
			insertFourthCombinations($notesAndDurations, $currMakamObj, $durationPatterns, 80);
			
			splitSixes($notesAndDurations, $currMakamObj);
			
			fixJumpsBetweenMeasures($notesAndDurations, $currMakamObj, 4);
			
			fixJumpsPerMeasure($notesAndDurations, $currMakamObj, 1);
			
			fixTripleSameNotes($notesAndDurations, $currMakamObj);
			
			fixRepeatedNotes($notesAndDurations, $currMakamObj, 1);
			
			introduceChorus($notesAndDurations, 4, 2);
			
			fixChorusEndings($notesAndDurations, 4, 2, $currMakamObj);
			
			addEndingNote($notesAndDurations, $currMakamObj);
		}
		
		return $notesAndDurations;
	}
?>			
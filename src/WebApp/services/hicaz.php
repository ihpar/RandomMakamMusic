<?php
	class Hicaz { 
		// members
		private $notes; 
		private $karar;
		private $guclu;
		private $yeden;
		private $commonStarts;
		private $commonJumps;
		private $fourthCombinations;
		private $chorusEndings;
		
		// c'tor
		public function __construct() {
			$this->notes = array(
			0  => array("natural" => "Sol0", "increasing" => "Sol0", "decreasing" => "Sol0", "index" => 0),
			1  => array("natural" => "La0",  "increasing" => "La0",  "decreasing" => "La0",  "index" => 1),
			2  => array("natural" => "Si0",  "increasing" => "Si0",  "decreasing" => "Si0",  "index" => 2),
			3  => array("natural" => "Do0",  "increasing" => "Do0",  "decreasing" => "Do0",  "index" => 3),
			4  => array("natural" => "Re0",  "increasing" => "Re0",  "decreasing" => "Re0",  "index" => 4),
			5  => array("natural" => "Mi0",  "increasing" => "Mi0",  "decreasing" => "Mi0",  "index" => 5),
			6  => array("natural" => "Fa0",  "increasing" => "Fa#0", "decreasing" => "Fa0",  "index" => 6),
			7  => array("natural" => "Sol1", "increasing" => "Sol1", "decreasing" => "Sol1", "index" => 7),
			8  => array("natural" => "La1",  "increasing" => "La1",  "decreasing" => "La1",  "index" => 8),
			9  => array("natural" => "Si1",  "increasing" => "Si1",  "decreasing" => "Si1",  "index" => 9),
			10 => array("natural" => "Do1",  "increasing" => "Do1",  "decreasing" => "Do1",  "index" => 10),
			11 => array("natural" => "Re1",  "increasing" => "Re1",  "decreasing" => "Re1",  "index" => 11),
			12 => array("natural" => "Mi1",  "increasing" => "Mi1",  "decreasing" => "Mi1",  "index" => 12),
			13 => array("natural" => "Fa1",  "increasing" => "Fa#1", "decreasing" => "Fa1",  "index" => 13),
			14 => array("natural" => "Sol2", "increasing" => "Sol2", "decreasing" => "Sol2", "index" => 14),
			15 => array("natural" => "La2",  "increasing" => "La2",  "decreasing" => "La2",  "index" => 15),
			16 => array("natural" => "Si2",  "increasing" => "Si2",  "decreasing" => "Si2",  "index" => 16),
			17 => array("natural" => "Do2",  "increasing" => "Do2",  "decreasing" => "Do2",  "index" => 17),
			18 => array("natural" => "Re2",  "increasing" => "Re2",  "decreasing" => "Re2",  "index" => 18)
			); 
			
			$this->karar = array(1, 8, 15);
			
			$this->guclu = 11;
			
			$this->yeden = 7;
			
			$this->commonStarts = array(7, 8, 11, 13, 14, 15, 17);
			
			$this->commonJumps = array(
			array(7, 11),
			array(7, 13),
			array(8, 10),
			array(8, 11),
			array(8, 12),
			array(8, 14),
			array(11, 15),
			array(12, 15),
			array(12, 17),
			array(15, 18),
			array(11, 8),
			array(12, 8),
			array(13, 7),
			array(15, 11),
			array(15, 12),
			array(18, 14),
			array(18, 15)
			);
			
			$this->fourthCombinations = array(
			array(11, 10, 9, 10),
			array(12, 13, 14, 12),
			array(12, 13, 12, 11),
			array(15, 14, 13, 12),
			array(14, 12, 13, 11),
			array(14, 15, 14, 13),
			array(8, 9, 8, 7),
			array(15, 16, 15, 14),
			array(10, 11, 10, 9),
			array(17, 18, 17, 16),
			array(9, 10, 9, 8),
			array(16, 17, 16, 15),
			array(13, 12, 13, 15),
			array(13, 12, 12, 15),
			array(16, 15, 16, 17),
			array(16, 14, 15, 13),
			array(13, 12, 13, 14),
			array(8, 11, 10, 9),
			array(8, 7, 8, 9),
			array(14, 13, 12, 11),
			array(8, 9, 10, 11),
			array(15, 16, 17, 18)
			);
			
			$this->chorusEndings = array(
			array(9, 8, 8),
			array(16, 15, 15),
			array(8, 8, 8),
			array(15, 15, 15),
			array(11, 10, 8),
			array(18, 17, 15),
			array(10, 9, 8),
			array(17, 16, 15),
			array(11, 9, 8),
			array(18, 16, 15),
			array(8, 7, 8),
			array(15, 14, 15)
			);
		}
		
		// getters
		
		// get notes
		public function getNotes() {
			return $this->notes;
		}
		// get karar
		public function getKarar() {
			return $this->karar;
		}
		// get guclu
		public function getGuclu() {
			return $this->guclu;
		}
		// get yeden
		public function getYeden() {
			return $this->yeden;
		}
		// get ölçü başlangıç ihtimalleri
		public function getCommonStarts() {
			return $this->commonStarts;
		}
		// get common jumps
		public function getCommonJumps() {
			return $this->commonJumps;
		}
		// get four combs
		public function getFourthCombinations() {
			return $this->fourthCombinations;
		}
		// get chorus endings
		public function getChorusEndings() {
			return $this->chorusEndings;
		}
	}

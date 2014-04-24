<?php

// Strongtube server-side video conversion
// 2013, Sean Collins

// This module converts all files in a static directory to various 
// HTML5-supported video codecs and puts them in their appropriate server
// directories for public access. In addition, it also generates the HTML5
// code necessary to link to the converted videos on Strongtube.

class StrongtubeConverter {
	
	private $videoName = "";
	private $fileName = "";
	private $CONVERSION_DIRECTORY;
	
	function __construct($fileName, $conversionDirectory) {
		$this->fileName = $fileName;
		# Server-contextual bath below, not URI (e.g. /var/www/...)
		$this->CONVERSION_DIRECTORY = $conversionDirectory;
		$this->videoName = pathinfo(self::CONVERSION_DIRECTORY . $fileName, PATHINFO_FILENAME);
	}
	
	public function convert() {
		# Bash script which converts into three formats via avconv serverside
		# HTML5-compatible MP4, OGV, and WEBM for multi-browser support
		$result = shell_exec("export FILENAME=" . $this->videoName . "&& /bin/sh /var/www/scripts/strongtube_convert_all_codec.sh");
		
		return $this->uploadSuccessful($result);
	}
	
	public function generateHTML() {
		# Probably should change this so not specific to my implementation :\
		$html = "
			<video width=640 height=480 controls>
				<source src=" . $this->videoName . " type=video/webm>
				<source src=" . $this->videoName . " type=video/mp4>
				<source src=" . $this->videoName . " type=video/ogg>
				<i>Browser does not support codec!</i>
			";
		
		return $html;
	}
	
	public function uploadSuccessful($result) {
		$outcome = TRUE;
		
		if (strpos($result, "overwriting") !== FALSE) {
			$outcome = FALSE;
		}
		
		return $outcome;
	}
	
	public function getFileName() {
		return $this->fileName;
	}
	
	public function getVideoName() {
		return $this->videoName;
	}
}
?>

<?php

// Strongtube video finder
// 2013, Sean Collins

// This module find a video on Strongtube when passed a GET string,
// and generates the appropriate HTML5 <video> code.

class StrongtubeVideoFinder {

	private $videoName = "";
	
	private $STRONGTUBE_DIR;
	private $STRONGTUBE_WEBM_DIR;
	private $STRONGTUBE_MP4_DIR;
	private $STRONGTUBE_OGV_DIR;
	private $PUBLIC_WEBM;
	private $PUBLIC_MP4;
	private $PUBLIC_OGV;
	
	function __construct($videoName, $strongtubeLocationBundle) {
		$this->videoName = $videoName;
		
		# The $strongtubeLocationBundle is a collection of server and absolute paths
		$this->STRONGTUBE_DIR = $strongtubeLocationBundle->STRONGTUBE_DIR;
		$this->STRONGTUBE_WEBM_DIR = $strongtubeLocationBundle->STRONGTUBE_WEBM_DIR;
		$this->STRONGTUBE_MP4_DIR = $strongtubeLocationBundle->STRONGTUBE_MP4_DIR;
		$this->STRONGTUBE_OGV_DIR = $strongtubeLocationBundle->STRONGTUBE_OGV_DIR;
		$this->PUBLIC_WEBM = $strongtubeLocationBundle->PUBLIC_WEBM;
		$this->PUBLIC_MP4 = $strongtubeLocationBundle->PUBLIC_MP4;
		$this->PUBLIC_OGV = $strongtubeLocationBundle->PUBLIC_OGV;
		
	}
	
	public function generateHTML() {
		if (!($this->webmExists() && $this->mp4Exists() && $this->ogvExists())) {
			return "This video ID (" . $this->videoName . ") does not exist.";
		}
		
		else {
      $html = '<video id="strongtube-video" style="width:100%;" oncanplay="$(this)[0].play()" controls autoplay>';
		}
		
		if ($this->mp4Exists()) {
			$html = $html . "\n" . "<source src=" . self::PUBLIC_MP4 . 
			$this->videoName . ".mp4 type=video/mp4>";
		}
		
		else {
			$html = $html . "\n" . 
			"<!-- WARNING: MP4 version doesn't exist in dir! -->";
		}
		
				if ($this->webmExists()) {
			$html = $html . "\n" . "<source src=" . self::PUBLIC_WEBM . 
			$this->videoName . ".webm type=video/webm>";
		}
		
		else {
			$html = $html . "\n" . 
			"<!-- WARNING: WEBM version doesn't exist in dir! -->";
		}
		
		if ($this->ogvExists()) {
			$html = $html . "\n" . "<source src=" . self::PUBLIC_OGV .  
			$this->videoName . ".ogv type=video/ogg>";
		}
		
		else {
			$html = $html . "\n" . 
			"<!-- WARNING: OGV version doesn't exist in dir! -->";
		}
		
		$html = $html . "\n" . "</video>";
		return $html;
	}
	
	public function getVideoName() {
		return $this->videoName;
	}
	
	private function webmExists() {
		return file_exists(self::STRONGTUBE_WEBM_DIR . $this->videoName . ".webm");
	}
	
	private function mp4Exists() {
		return file_exists(self::STRONGTUBE_MP4_DIR . $this->videoName . ".mp4");
	}
	
	private function ogvExists() {
		return file_exists(self::STRONGTUBE_OGV_DIR . $this->videoName . ".ogv");
	}
}

?>

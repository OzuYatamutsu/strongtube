<?php

// Class-based file upload in PHP
// 2013, Sean Collins

// This module allows a SteakscorpFileUploader class to be created.
// A SteakscorpFileUploader object allows uploading of files to a 
// given directory with given restrictions on filetype and size.

// $_FILES[$id]["name", "type", "size", "tmp_name", "error" (returns code as int)]

class SteakscorpFileUploader {

	public $uploadDir = "";
	public $allowedTypes = array();
	public $maxFileSize = 0; // <-- In B
	
	// Assigned when upload is called
	// getFilename(), getSize() and getType() return results for last file uploaded
	private $filename = "";
	private $size = 0;
	private $type = "";

	function __construct($uploadDir, $allowedTypes, $maxFileSize) {
		$this->uploadDir = $uploadDir;
		$this->allowedTypes = $allowedTypes;
		// When constructing, a value of "ALL" accepts all filetypes
		$this->maxFileSize = $maxFileSize;
		// When constructing, a value of "0" allows for unlimited size
	}
	
	public function upload($fileArray) {
		$result = "";
		$valid = $this->validateUpload($fileArray["error"], 
			$fileArray["type"], 
			$fileArray["size"],
			$fileArray["name"]);	
		
		if (!$valid[0]) {
			$result = $valid[1];
		}
		
		else {
			// Filenames escape spaces and quotes to underscores!
			$this->filename = str_replace(array(" ", "'", '"', "$", "(", ")", "{", "}", "?", "#"), "_", $fileArray["name"]);
			$this->size = $fileArray["size"];
			$this->type = $fileArray["type"];
			
			move_uploaded_file($fileArray["tmp_name"], $this->uploadDir . 
			$this->filename) or $result = "Error: Error while moving file. 
			Maybe directory permissions problem?";
			
			$result = "File successfully uploaded.";
		}
		
		return $result;
	}
	
	public function validateUpload($error, $fileType, $size, $filename) {
		$valid = TRUE;
		$result = "";
		
		if ($error > 0) {
			$result = "Error: " . $error . " File upload error?!";
			if ($error == 4) {
				$result = $result . " (Did you forget to select a file?)";
			}
			$valid = FALSE;
		}
		
		else if ($this->allowedTypes[0] != "ALL" && !$this->isValidFileType($fileType)) {
			$result = "Error: Invalid file type.";
			$valid = FALSE;
		}
		
		else if ($this->maxFileSize != 0 && !$this->isValidSize($size)) {
			$result = "Error: Invalid size (usually too big). Maximum file size 
				allowed: " . $this->maxFileSize;
			$valid = FALSE;
		}
		
		else if (file_exists($this->uploadDir . $filename)) {
			$result = "Error: File exists on server. Recheck if you've uploaded the 
				file already, or rename your file and try again.";
			$valid = FALSE;
		}
		
		return array($valid, $result);
	}
	
	public function getFileName() {
		return $this->filename;
	}
	
	public function getFileSize() {
		return $this->size;
	}
	
	public function getFileType() {
		return $this->type;
	}
	
	private function isValidFileType($fileType) {
		$result = FALSE;
		foreach ($this->allowedTypes as $item) {
			if ($fileType == $item) {
				$result = TRUE;
			}
		}
			
		return $result;
	}
	
	private function isValidSize($size) {
		return $size <= $this->maxFileSize;
	}
}
?>

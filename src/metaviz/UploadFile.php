<?php 

namespace metaviz;

/**
 * 
 */
 class UploadFile
 {
 	
 	// destination folder
 	protected $destination;
 	// Array of messages
 	protected $messages = [];

 	// Builder function
 	public function __construct($uploadFolder)
 	{
 		if(!is_dir($uploadFolder) || !is_writable($uploadFolder)){
 			throw new \Exception("$uploadFolder must be a valid, writable folder.");
 		}
 		if($uploadFolder[strlen($uploadFolder)-1] != '/'){
 			$uploadFolder .= '/';
 		}
 		$this->destination = $uploadFolder;
 	}

 	// Checks file,
 	public function upload(){
 		$uploaded = current($_FILES);
 		if ($this->checkFile($uploaded)){
 			$this->moveFile($uploaded);
 		}
 	}


 	// Checks for any error messages
 	protected function checkFile($file){
 		if ($file['error'] != 0) {
 			$this->getErrorMessage($file);
 			return false;
 		}
 		return true;
 	}

 	// Checks for errors numbers, creates messages
 	protected function getErrorMessage($file){
 		switch ($file['error']) {
 			case 1:
 			case 2:
 				$this->messages[] = $file['name'] . ' is too big.';
 				break
 			case 3:
 				$this->messages[] = $file['name'] . ' was only partially uploaded.';
 				break;
 			case 4:
 				$this->messages[] = 'No file submitted.';
 				break;
 			default:
 				$this->messages[] = 'Sorry, there was a problem uploading ' . $file['name'];
 				break;
 		}
 	}

 	// Move the file from tmp
 	protected function moveFile($file){

 		echo $file['name'] . ' was uploaded succesfully.';
 	}
 } ?>
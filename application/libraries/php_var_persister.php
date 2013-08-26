<?php

/**
 * php_var_persister is used to serialize associative array data to a PHP file in the form of PHP variables.
 * This is useful for data structures to be easily stored in latency sensitive environments without the need for explicit caching.   
 **/ 

class php_var_persister {
	public $fileName;  //Name of file to where the serialized data will be stored
	public $varName; //Name of variable to store data in
	private $data = array();
	function __construct($configarray){
		if($configarray['fileName'] === '' || $configarray['varName'] === ''){
			return FALSE;
			//throw new Exception('fileName & varName paremeters required in data_to_php_vars_serializer constructor!');
		}
		$this->fileName = $configarray['fileName'];
		$this->varName = $configarray['varName'];
	}
	//function __destruct(){
	//	//$this->writeData();
	//}
	public function read(){
	
		if( !file_exists($this->fileName) ){
			return FALSE;
		}
		include($this->fileName);
		
		if( !isset(${$this->varName}) ){
			return FALSE;
		}
		return ${$this->varName};
	}
	public function pushData($data){
		foreach( $data as $key=>$value){
			$this->data[$key] = $value;
		}
	}
	private function getStringValue($value){
		switch(gettype($value)){
			case 'integer':
			case 'boolean':
			case 'double':
				return "{$value}";
				break;
			default:
				return '"'.addcslashes($value, '"').'"';
				break;
		}
	}
	private function is_assoc($a){
		// http://stackoverflow.com/questions/173400/php-arrays-a-good-way-to-check-if-an-array-is-associative-or-sequential
		
		//$a = array_keys($a);
		//return ($a != array_keys($a));
		return array_keys($a) !== range(0, count($a) - 1);
	}
	private function serialize_array($array){
		$firstItemDone = false;
		$dataString = "Array(\r\n";
				
		if( $this->is_assoc($array) ){
			foreach( $array as $name=>$value){
			
				if($firstItemDone ){ // add comma to separate array indexes
					$dataString .= "\r\n,";
				}else{
					$firstItemDone = true;
				}
				
				$dataString .= $this->getStringValue($name)." => ";
				
				if(is_array($value)){
					$dataString .= $this->serialize_array($value);
				}else{
				
					$dataString .= $this->getStringValue($value);
					/*
					if(is_numeric($name)){
						$dataString .= $value;
					}else{
						$dataString .= '"'.addcslashes($value, '"').'"';
					}
					*/
					//$dataString .= '"'.addcslashes($value, '"').'"';
				}
			}
		}else{
			$len = count($array);
			$it = 0;
			while($it < $len){
				//DEBUG //echo "{$it} < {$len}<BR>";
				if($firstItemDone ){
					$dataString .= ',';
				}else{
					$firstItemDone = true;
				}
				if(is_array($array[$it])){
					$dataString .= $this->serialize_array($array[$it]);
				}else{
					$dataString .= $this->getStringValue($array[$it]);
					//$dataString .= '"'.addcslashes($array[$it], '"').'"';
				}
				$it++;
			}
		}
		$dataString .= "\r\n)";
		return $dataString;
	}
	public function writeData(){
		//$this->data = $data;
		if( file_exists($this->fileName) ){
			if( !is_writable($this->fileName) ){
				return FALSE;
				//throw new Exception('fileName passed to data_to_php_vars_serializer is not writeable!');
			}
		}
		
		$dataString = "<?php \r\n\r\n\${$this->varName} = "; //start of output string
		
		$firstItemDone = false;
		if( is_array($this->data) ) {
		
			$dataString .= $this->serialize_array($this->data);
			
		}elseif( trim($this->data) !== '') {
		
   			$dataString .= '"'.addcslashes($this->data, '"').'"';
			
		}else{
			return false;
		}
		
		$dataString .= ";\r\n\r\n?>"; //end of output string, close PHP tag
		
		if(FALSE === file_put_contents($this->fileName, $dataString) ){
			return FALSE;
			//throw new Exception('Error writing to serializer output file!');
		}
	}
}

?>
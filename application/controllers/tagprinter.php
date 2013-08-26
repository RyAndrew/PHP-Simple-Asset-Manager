<?php

/**
 * This is the tagprinter class. It contains all the functions that allow for asset tag printing.
 */
class tagprinter extends CI_Controller {

    /**
     * Loads the libraries and helpers used for this class.
     * If the user is not logged in, then they are pushed to the login page.
     */
    function __construct(){
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('login', 'refresh');
			die();
		}
	}

    /**
     * Echos "Hi, this is the asset tag printer!".
     */
    function index(){
		echo "Hi, this is the asset tag printer!";
	}

    /**
     * Prints an asset tag using a given asset id from POST.
     *
     * @param $assetId The asset to print
     */
    function printAssetTag($assetId){

		require_once($_SERVER['DOCUMENT_ROOT'] .'/router/controllers/inventoryprintlabels.php');

		$printer = new inventoryprintlabels();

		$printer = "Zebra Asset Tags TLP2824 2x1 .5";
		$printJobName = "Asset Tag";

		$labelData = array(
			'barcode' => '090' . $assetId
			,'assetId' => $assetId
		);

		$assetTagTemplate = '{
			"items": [
			{
				"type": "text",
				"text": "Asset",
				"size": "18",
				"font": "Lucida Console",
				"bold": "true",
				"italic": "false",
				"xpos": 0,
				"ypos": 0
			},{
				"type": "barcode",
				"text": "0000",
				"dataBinding": "barcode",
				"size": "16",
				"xpos": 15,
				"ypos": 15
			},{
				"type": "text",
				"text": "0000",
				"dataBinding": "assetId",
				"barcodeHeight": "1",
				"barcodeWidth": "1.5",
				"size": "28",
				"font": "Arial",
				"bold": "true",
				"italic": "false",
				"xpos": 15,
				"ypos": 40
			}
			]
		}';
		
    	//json_decode returns NULL when the json string is invalid
		if( NULL === $Template = json_decode($assetTagTemplate ,true) ){
			die('{success:false,printed:false,reason:"Invalid JSON Data for label template!"}');
		}

		if(TRUE !== $this->printSinglePageDocument($printer, $printJobName, $Template, $labelData )){
			die('{success:true,printed:false}');
		}else{
			die('{success:true,printed:true}');
		}
	}
	
}
?>
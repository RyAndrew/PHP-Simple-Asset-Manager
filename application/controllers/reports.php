<?php
/**
 * The reports class. Contains the functions that generate various reports.
 */
class reports extends CI_Controller {

    /**
     * Loads up various models and libraries that the class uses.
     * Redirects to the login page if the current user is not logged in.
     */
    function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('asset_model');
		$this->load->model('attribute_model');
		$this->load->model('type_model');
		if(!$this->session->userdata('logged_in')){
			redirect('login', 'refresh');
			die();
		}
	}

    /**
     * Equivalent to reportList();
     */
    function index(){
		$this->reportList();
	}

    /**
     * Displays the list of reports.
     */
    function reportList()
	{
		$this->load->view('templates/header');
		$this->load->view('reports/reportList');
		$this->load->view('templates/footer');
	}

    /**
     * Generates an excel spreadsheet using a JSON array of assets passed via POST and sends it to the browser to download.
     * It does not store anything on the server.
     */
    function assetListExcel(){
		$postValidation = array(
			array(
				'field'=>'excelDataJSON'
				,'label'=>'Excel Data'
				,'rules'=>'trim|required|xss_clean|prep_for_form'
			)
		);
		$this->form_validation->set_rules($postValidation);
		$jsonDecodeAssets = json_decode($this->input->post('excelDataJSON'),true);
		if ($this->form_validation->run() == FALSE || $jsonDecodeAssets === NULL){
			die('You really shouldn\'t be seeing this - '.validation_errors());
		}else{
			array_unshift($jsonDecodeAssets,array('Asset ID','Type','Name','Location','Status'));
			$this->load->model('asset_model');
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			//$this->excel->getActiveSheet()->setTitle('Assets');
			$this->excel->getActiveSheet()->fromArray($jsonDecodeAssets, null, 'A1'); 
			$this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$filename='Asset_Export_'.date('m-d-y_h-i-s').'.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
		}
	}
    /**
     * Generates an excel spreadsheet using a JSON array of printers passed via POST and sends it to the browser to download.
     * It does not store anything on the server.
     */
	function printerPageCountExcel(){
	
		$postValidation = array(
			array(
				'field'=>'excelDataJSON'
				,'label'=>'Excel Data'
				,'rules'=>'trim|required|xss_clean|prep_for_form'
			)
		);
		$this->form_validation->set_rules($postValidation);
		$jsonDecodeAssets = json_decode($this->input->post('excelDataJSON'),true);
		
		//var_dump($jsonDecodeAssets);
		//die();
		if ($this->form_validation->run() == FALSE || $jsonDecodeAssets === NULL){
			die('You really shouldn\'t be seeing this - '.validation_errors());
		}else{
			//array_unshift($jsonDecodeAssets,array('Asset ID','Type','Name','Location','Status'));
			$this->load->model('asset_model');
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			//$this->excel->getActiveSheet()->setTitle('Assets');
			$this->excel->getActiveSheet()->fromArray($jsonDecodeAssets, null, 'A1'); 
			
			for ($col = 'A'; $col != 'J'; $col++) {
				$this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
			
			$filename='Printer_Page_Counts'.date('m-d-y_h-i-s').'.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
		}
	}

    /**
     * Shows the printer page count report. It pulls it's info from the 'Page Count' attribute from any asset in the 'Printer' type.
     */
    function printerPageCount(){
		//Redo this to be one query, no reason to do multiple queries! JOIN!!!
		$data['error']=array();
		$this->load->model('asset_model');
		$allAssets = $this->asset_model->getAssets();
		$tableArray = array();
		foreach($allAssets as $asset){
			$type_id = $this->asset_model->getType($asset['asset_id']);
			$type_name = $this->type_model->getName($type_id);
			if(FALSE !== strpos($type_name, 'Printer')){ //We have the printer type
				$attributes = $this->asset_model->getCurrentAttributes($asset['asset_id']);
				$pageCounts = array();
				foreach($attributes as $attribute){
					$attribute_name = $this->attribute_model->getName($attribute['attribute_id']);
					if(FALSE !== strpos($attribute_name, 'Page Count')){ //have the page count attribute
						$explodedAttributeValue = explode("=",$attribute['attribute_value']);
						if(isset($explodedAttributeValue[1])){
							$pageCounts[trim($explodedAttributeValue[0])]=trim($explodedAttributeValue[1]);
						}else{
							$data['error'][$asset['asset_id']]="Page Count isn't formatted correctly.";
							//var_dump($tableArray[$asset['asset_id']]);
							//die('WTF ERROR');
						}
					}
				}
				arsort($pageCounts);
				$tableArray[$asset['asset_id']] = array(
					'asset_name' => $asset['asset_name']
					,'asset_location' => $asset['asset_location']
					,'page_counts' => array()
				);
				foreach($pageCounts as $date => $count){
					$tableArray[$asset['asset_id']]['page_counts'][$date]= array('date'=>$date,'count'=>$count);
				}
				$usage="";
				$avgPagePerDay="";
				if(count($pageCounts)>=2){
					$array_values=array_values($tableArray[$asset['asset_id']]['page_counts']);
					$tableArray[$asset['asset_id']]['lastPageCount'] = $array_values[count($array_values)-1]['count'];
					$tableArray[$asset['asset_id']]['firstPageCount'] = $array_values[0]['count'];
					$tableArray[$asset['asset_id']]['lastPageCountDate'] = $array_values[count($array_values)-1]['date'];
					$tableArray[$asset['asset_id']]['firstPageCountDate'] = $array_values[0]['date'];
					$usage =  $tableArray[$asset['asset_id']]['firstPageCount'] - $tableArray[$asset['asset_id']]['lastPageCount'];
					$startDate = $tableArray[$asset['asset_id']]['lastPageCountDate'];
					$endDate = $tableArray[$asset['asset_id']]['firstPageCountDate'];
					$avgPagePerDay = floor((strtotime($endDate)-strtotime($startDate))/86400);	
					
					if($avgPagePerDay != 0){
						$avgPagePerMonth = number_format( ($usage / $avgPagePerDay) * 30 );
					}else{
						$avgPagePerMonth ="N/A";
					}
				}else{
					$usage="N/A";
					$avgPagePerDay ="N/A";
					$avgPagePerMonth ="N/A";
				}
				$tableArray[$asset['asset_id']]['usage']=$usage;
				$tableArray[$asset['asset_id']]['pg_per_day']=$avgPagePerDay;
				$tableArray[$asset['asset_id']]['pg_per_month']=$avgPagePerMonth;
			}
		}
		$data['tables'] = $tableArray;
		$this->load->view('templates/header');
		$this->load->view('reports/printer', $data);
		$this->load->view('templates/footer');
	}
}
?>
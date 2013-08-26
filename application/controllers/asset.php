<?php
/**
 * The asset class. Contains functions that allow one to create, modify and delete assets.
 */
class asset extends CI_Controller {

    /**
     * Loads up all the standard models used for this class. Kicks the user back to login if not logged in.
     */
    function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('table');
		$this->load->model('asset_model');
		$this->load->model('attribute_model');
		$this->load->model('type_model');
		$this->load->model('note_model');
		$this->load->model('link_model');
		$this->load->helper('simple_error_messages');
		
		if(!$this->session->userdata('logged_in')){
			redirect('login', 'refresh');
			die();
		}
	}

    /**
     * Equivalent to showHomePage()
     */
    function index(){
		$this->showHomePage();
	}

    /**
     * Returns true if the function was called via ajax.
     *
     * @return bool Returns true if the function was called via ajax.
     */
    //function usingAjax(){
	//	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	//}

    /**
     * Loads the standard header, assets, footer view. It's used often in this class.
     *
     * @param array $data data passed to the view.
     * @return void
     */
    function loadStandardAssetView($data=array()){
		$this->load->view('templates/header',$data);
		$this->load->view('asset/assets', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Shows the "Dashboard" with various views loaded
     * (Header, Search By Id, Create, Recent Creations, Recent Changes, Footer)
     *
     * @param array $messages This is passed to the header view. if ['error_message'] or ['happy_message'] is set, an error message will appear.
     */
    function showHomePage($messages=array()){
		$data['all_types']= $this->type_model->getTypes();
		$this->load->view('templates/header',$messages);
		$this->load->view('asset/searchById');
		$data['recentassets'] = $this->log_model->getLastCreated();
		$data['recentedits'] = $this->log_model->getLastChanged();
		$this->load->view('asset/create', $data);
		$this->load->view('asset/recentAssets',$data);
		$this->load->view('asset/recentChanged',$data);
		$this->load->view('templates/footer');
	}

    /**
     * @param bool $passThroughPostData
     * @return bool
     */
    /*
    function confirmStatusChange($passThroughPostData=false){
        $postValidation = array(
            array(
			'field'  => 'statusChangeReason'
			,'label' => 'Status Change Reason'
			,'rules' =>	'trim|required|xss_clean|prep_for_form|max_length[30]'
		));
		if('yes' !== $this->input->post('reasonEntered')){
			$this->load->view('asset/confirmStatusChange', $passThroughPostData);
			return false;
		}
		$this->form_validation->set_rules($postValidation);
		if($this->form_validation->run() == FALSE){
			$this->load->view('asset/confirmStatusChange', array(
				'asset_id'=>$this->input->post('asset_id')
				,'attributeData'=>array()
				,'assetData'=>array()
				,'attributesDeleted'=>array()
			));
			return false;
		}
		$this->load->edit($this->input->post('asset_id'));
	} */

	function apiConfirmAssetStatusChange(){
		$postValidation = array(
			array(
				'field' =>'statusReasonField'
				,'label'=>'Type'
				,'rules'=>'trim|required|xss_clean|prep_for_form|min_length[15]'
			)
		);

		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);

		if($this->form_validation->run() == FALSE){
			$messages['failed_fields'] = $this->form_validation->error_array();
			$messages['error_message']=validation_errors();
			$this->load->view('utility/notifier',$messages);
			return false;
		}
		$messages['happy_message']="Status Confirmed";
		$messages['data']=$this->input->post('statusReasonField');
		$this->load->view('utility/notifier',$messages);
	}

    /**
     * This shows all the assets in the system. It loads the standard asset view and passes all the assets to it.
     */
    function allAssets(){
		$data['assets']=$this->asset_model->getAssets();
		//$data['everything']=$this->asset_model->getEverything();
		if($data['assets']==-1){
			$data['error_message'] = "There are no assets that match your search parameters";
		}
		$this->loadStandardAssetView($data);
	}

	function apiGetAssetAttributes(){
		$postValidation = array(
			array(
				'field' =>'asset_id'
				,'label'=>'Type'
				,'rules'=>'trim|required|xss_clean|prep_for_form|is_natural'
			)
		);
		$this->form_validation->set_rules($postValidation);
 
		if($this->form_validation->run() == FALSE){
			$messages['error_message']=validation_errors();
			$this->load->view('utility/notifier',$messages);
			return false;
		}

		$messages['data']['attributes'] = $this->asset_model->getCurrentAttributes($this->input->post('asset_id'));

		$messages['happy_message']="Attributes Retrieved";
		$this->load->view('utility/notifier',$messages);
		//$this->load->view('utility/json',array('data' => $assets));
	}

    /**
     * Creates a new asset using given POST data. It also logs the creation of the asset. Also echos back asset id via JSON.
     *
     * @return bool Returns false if POST validation fails.
     */
    function apiCreateAsset(){
		$postValidation = array(
			array(
				'field' =>'new_asset_type'
				,'label'=>'Type'
				,'rules'=>'trim|required|xss_clean|prep_for_form|is_natural'
			)
			,array(
				'field' =>'new_asset_name'
				,'label'=>'Name'
				,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
			,array(
				'field' =>'new_asset_location'
				,'label'=>'Location'
				,'rules'=>'trim|xss_clean|prep_for_form|max_length[30]'
			)
			,array(
				'field' =>'new_asset_status'
				,'label'=>'Status'
				,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
		);

		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
 
		if($this->form_validation->run() == FALSE){
			$messages['failed_fields'] = $this->form_validation->error_array();
			$messages['error_message']=validation_errors();
			$this->load->view('utility/notifier',$messages);
			return false;
		}
		
		$data = array(
			'asset_name'         => $this->input->post('new_asset_name')
			,'type_id'           => $this->input->post('new_asset_type')
			,'asset_location'    => $this->input->post('new_asset_location')
			,'asset_status'      => $this->input->post('new_asset_status')
		);

		$asset_id = $this->asset_model->create($data);

		$this->log_model->log(array(
			'class'          => 'asset'
			,'method'        => 'apiCreateAsset'
			,'asset_id'      => $asset_id
			,'asset_name'	 => $data['asset_name']
			,'type_id'		 => $data['type_id']
			,'description'   => json_encode($data)
		));

		$messages['happy_message']="Asset Created - Redirecting...";
		$messages['data']['asset_id']=$asset_id;
		$this->load->view('utility/notifier',$messages);
	}

    function apiDeleteAsset(){
        $postValidation = array(
			array(
				'field'=> 'asset_id'
				,'label'=> 'Asset Id'
				,'rules'=> 'trim|required|is_natural|xss_clean|prep_for_form'
			)
			,array(
				'field'=> 'deleteReasonField'
				,'label'=> 'Asset Delete Note'
				,'rules'=> 'trim|required|xss_clean|prep_for_form|min_length[15]'
			)
		);

		loadSimpleErrorMessages($this);
		$assetDeleteNote = $this->input->post('deleteReasonField');
		$asset_id = $this->input->post('asset_id');
		$this->form_validation->set_rules($postValidation);

		if($this->form_validation->run() === FALSE){
			$messages['failed_fields'] = $this->form_validation->error_array();
			$messages['error_message'] = validation_errors();
			$this->load->view('utility/notifier',$messages);
			return false;
		}


		$this->note_model->addNote(array(
			'note_type' => 1
			,'asset_id' => $asset_id
			,'note' 	=> "Asset Deleted. ".$assetDeleteNote
		));
		$notes = $this->note_model->getNotesOfAsset($asset_id);
		$this->log_model->log(array(
			'class'         => 'asset'
			,'method'       => 'apiDeleteAsset-addNote'
            ,'asset_id'     => $asset_id
			,'asset_name'   => $this->asset_model->getName($asset_id)
			,'description'  => json_encode(array('note_type' => $notes[0]['note_type'], 'note' => $notes[0]['note']))
		));

		$notes = $this->note_model->getNotesOfAsset($asset_id);

		$assetInfo = $this->asset_model->getAsset($asset_id);
		$attributes = $this->asset_model->getCurrentAttributes($asset_id);
		$links=$this->link_model->getLinksOfAsset($asset_id);
		$notes = $this->note_model->getNotesOfAsset($asset_id);

		$deleteDesc=array();
		
		$deleteDesc['asset_id']= $assetInfo->asset_id;
		$deleteDesc['type']= $this->type_model->getName($assetInfo->type_id);
		$deleteDesc['asset_name']= $assetInfo->asset_name;
		$deleteDesc['asset_location']= $assetInfo->asset_location;
		$deleteDesc['asset_status']= $assetInfo->asset_status;
		
		foreach($attributes as $attribute){
			$deleteDesc['Attribute: '.$attribute['attribute_name']]=$attribute['attribute_value'];
		}

		foreach($links as $link){
			$deleteDesc['Link'] = $link['asset_id'].'('.$link['asset_name'].') - Link Note: '.$link['link_note'];
		}

		foreach($notes as $note){
			$deleteDesc['Note: '.$note['note_type']] = $note['note'];
		}
	

		$this->log_model->log(array(
			'class'         => 'asset'
			,'method'       => 'apiDeleteAsset'
            ,'asset_id'     => $asset_id
			,'asset_name'   => $this->asset_model->getName($asset_id)
			,'description'  => json_encode($deleteDesc)
		));

		$this->asset_model->delete($asset_id);

		$messages['happy_message']="Asset Deleted";
		$this->load->view('utility/notifier',$messages);

	}

    /**
     * Adds an attribute using given POST data. Logs and refreshes the page once added.
     *
     * @return bool Returns false if POST validation fails.
     */
    function apiAddAttribute(){
		$postValidation = array(
			array(
				'field'	=>'asset_id'
				,'label'=>'Type'
				,'rules'=>'trim|required|xss_clean|prep_for_form|numeric'
			)
			,array(
				'field'	=>'new_attribute_id'
				,'label'=>'Attribute Type'
				,'rules'=>'trim|required|xss_clean|prep_for_form|numeric'
			)
			,array(
				'field'	=>'new_attribute_value'
				,'label'=>'New Attribute Value'
				,'rules'=>'trim|xss_clean|prep_for_form|max_length[30]'
			)
		);
		
		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
 
		if($this->form_validation->run() == FALSE){
			$data['failed_fields'] = $this->form_validation->error_array();
			$data['error_message']=validation_errors();
			$this->load->view('utility/notifier',$data);
			return false;
		}

		/*
		$data['asset_id']=$this->input->post('asset_id');
		$data['asset_name']=$this->asset_model->getName($this->input->post('asset_id'));
		$data['asset_location']=$this->asset_model->getLocation($this->input->post('asset_id'));
		$data['all_types']=$this->type_model->getTypes();
		$data['asset_type']=$this->asset_model->getType($this->input->post('asset_id'));
		$data['current_attributes']=$this->asset_model->getCurrentAttributes($this->input->post('asset_id'));
		$data['all_attributes']=$this->attribute_model->getAttributes();
		$data['attribute_value'] = ""; //$this->input->post('new_attribute_value');
		$data['attribute_id'] = $this->input->post('new_attribute_id');
		*/

		$newAttribute=array(
			'asset_id'			=>  $this->input->post('asset_id')
			,'attribute_id'		=>	$this->input->post('new_attribute_id')
			,'attribute_value'	=>	$this->input->post('new_attribute_value')
		);
		$this->asset_model->addAttribute($newAttribute);
		
		$this->log_model->log(array(
			'class' 			=> 'asset'
			,'method' 			=> 'apiAddAttribute'
			,'asset_id' 		=> $newAttribute['asset_id']
			,'attribute_name' 	=> $this->attribute_model->getName($newAttribute['attribute_id'])
			,'asset_name' 		=> $this->asset_model->getName($newAttribute['asset_id'])
			,'data_to' 			=> $newAttribute['attribute_value']
			,'description' 		=> json_encode($newAttribute)
		));

		$data['data']['attributeTable'] = $this->generateAttributeTable($this->asset_model->getCurrentAttributes($newAttribute['asset_id']));
		$data['happy_message'] = "Attribute Added";
		$this->load->view('utility/notifier',$data);
		return false;
	}

    /**
     * Increments the print count on a given asset.
     *
     * @param int $asset_id the asset to increment.
     */
    function incrementTagsPrintedCount($asset_id = ""){
		$this->asset_model->incrementTagsPrintedCount($asset_id);
		$messages['happy_message']='Asset Tag Printed!';
		$this->edit($asset_id);
	}

    /**
     * Prints an asset tag for a given asset and increments the asset's "tags printed" counter.
     *
     * @param int $asset_id the asset to print a tag for.
     * @return bool Returns false if not a valid asset id.
     */
    function printAssetTag($asset_id = ""){
		
		if(!is_numeric($asset_id) || !$this->asset_model->checkAssetExist($asset_id)){
			$messages['error_message']='There is no asset with the id: '.$asset_id;
			$messages['data']=$this->input->post();
			$this->load->view('utility/notifier',$messages);
			return false;
		}
		
		require_once($_SERVER['DOCUMENT_ROOT'] .'/router/controllers/inventoryprintlabels.php');

		$printerLabels = new inventoryprintlabels();

		$printer = "Zebra Asset Tags TLP2824 2x1 .5";
		$printJobName = "Asset Tag";
		
		//$asset_id = 22222;
		
		$labelData = array(
			'barcode' => '090' . $asset_id
			,'assetId' => $asset_id
		);

		$labelWidth = 2 * 203;
		$barcodeCharacterOverhead = 2;
		$averageBarcodeCharacterWidthInDPI = 28;
		$averageFontArialCharacterWidthInDPI = 16;
		$dpiPerFontSizeScalingFactor = .888888;
		
		$Template = array(
			"items"=> array(
				array(
					"type"         => "text"
					,"text"        => "Asset"
					,"size"        => "28"
					//,"hcenter"   => true
					,"font"        => "Arial"
					,"bold"        => "true"
					,"italic"      => "false"
					,"xpos"        => 60
					,"ypos"        => 25
				)
				,array(
					"type"         => "barcode"
					,"text"        => "0000"
					,"dataBinding" => "barcode"
					,"barcodeHeight"=> "1.5"
					,"barcodeWidth"=> "1"
					,"size"        => "16"
					,"xpos"        => (($labelWidth - ((strlen($labelData['barcode']) + $barcodeCharacterOverhead) * $averageBarcodeCharacterWidthInDPI)) / 2)
					,"ypos"        => 80
				)
				,array(
					"type"         => "text"
					,"text"        => "0000"
					,"dataBinding" => "assetId"
					,"size"        => "18"
					,"font"        => "Arial"
					,"bold"        => "true"
					,"italic"      => "false"
					,"xpos"        => (($labelWidth - (strlen($asset_id) * ($dpiPerFontSizeScalingFactor * 18))) / 2)
					,"ypos"        => 160
				)
			)
		);		

		if(TRUE !== $labelPrintResult = $printerLabels->printSinglePageDocument($printer, $printJobName, $Template, $labelData )){
			$messages['error_message']='Error printing Asset Tag! Error: '.$labelPrintResult;
			$messages['data']=$this->input->post();
			$this->load->view('utility/notifier',$messages);
			return false;
		}else{
			$this->asset_model->incrementTagsPrintedCount($asset_id);			
			$messages['happy_message']='Asset Tag Printed!';
			$messages['data']=$this->input->post();
			$this->load->view('utility/notifier',$messages);
		}
		//$this->edit($asset_id);
	}

    /**
     * Loads up all the views for the asset editor page of a given asset.
     *
     * @param int $asset_id The asset to edit
     * @return bool Returns false if not a valid asset id
     */
    function edit($asset_id = ""){
		if(!is_numeric($asset_id) || !$this->asset_model->checkAssetExist($asset_id)){
			$messages['error_message']='There is no asset with the id: '.$asset_id;
			$this->load->view('templates/header', $messages);
			$this->load->view('asset/searchById');
			$this->load->view('templates/footer');
			return false;
		}else{

			//CLEAN UP INTO 1-2 QUERIES! (called getAsset or something...)
			$data['asset_id']=$asset_id;
			$data['asset_name']=$this->asset_model->getName($asset_id);
			$data['asset_location']=$this->asset_model->getLocation($asset_id);
			$data['asset_status']=$this->asset_model->getStatus($asset_id);
			$data['all_types']=$this->type_model->getTypes();
			$data['asset_type']=$this->asset_model->getType($asset_id);
			$data['tags_printed']=$this->asset_model->getTagsPrinted($asset_id);
			
			$data['current_attributes']=$this->asset_model->getCurrentAttributes($asset_id);
			$data['all_attributes']=$this->attribute_model->getAttributes(true);

			$data['attributeTable'] = $this->generateAttributeTable($data['current_attributes']);

			$links=$this->link_model->getLinksOfAsset($asset_id);
			$data['linksTable']=$this->generateLinkTable($links);

			$notes = $this->note_model->getNotesOfAsset($asset_id);
			$data['notesTable'] = $this->generateNoteTable($notes);
			$data['noteTypes'] = $this->note_model->getNoteTypes();

			$this->load->view('templates/header');
			$this->load->view('asset/asset_editor', $data);
			$this->load->view('templates/footer');
		}

	}

    /**
     * Updates an asset using given POST data.
     * This also will format the MAC address properly (as well as check for duplicates in POST validation).
     * Finally it deletes any attributes that are marked for deletion and logs any changes made to asset or attribute.
     *
     * @return bool Returns false if POST validation fails
     */
    function apiUpdateAsset(){
		$postValidation = array(
			array(
				'field' => 'asset_id'
				,'label'=> 'Asset ID'
				,'rules'=> 'trim|required|xss_clean|prep_for_form|is_natural'
			)
			,array(
				'field' => 'asset_name'
				,'label'=> 'Asset Name'
				,'rules'=> 'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
			,array(
				'field' => 'asset_type'
				,'label'=> 'Asset Type'
				,'rules'=> 'trim|required|xss_clean|prep_for_form|is_natural'
			)
			,array(
				'field' => 'asset_location'
				,'label'=> 'Asset Location'
				,'rules'=> 'trim|xss_clean|prep_for_form|max_length[30]'
			)
			,array(
				'field' => 'asset_status'
				,'label'=> 'Asset Status'
				,'rules'=> 'trim|xss_clean|prep_for_form|max_length[30]'
			)
			,array(
				'field' => 'asset_status_note'
				,'label'=> 'Asset Status Change Note'
				,'rules'=> 'trim|xss_clean|prep_for_form'
			)
		);

		if($this->input->post('attribute_value')!=""){
			$lineNum=0;
			foreach($this->input->post('attribute_value') as $id => $value){
				$lineNum++;
				$temp  = array(
					'field'    =>  'attribute_value['.$id.']'
					,'label'   =>  'attribute in field #'.$lineNum.' has a problem. This'
				);
				$attributeName = $this->attribute_model->getNameFromAssetAttributeId($id);
				if(strstr(strtolower($attributeName),'mac addr') !== false){ 
					$temp['rules'] = 'trim|xss_clean|prep_for_form|max_length[30]|callback_check_duplicates_mac';
				}else if(strstr(strtolower($attributeName),'serial') !== false){
					$temp['rules'] = 'trim|xss_clean|prep_for_form|max_length[30]|callback_check_duplicates_serial';
				}else{
					$temp['rules'] = 'trim|xss_clean|prep_for_form|max_length[30]';
				}
				$postValidation[] = $temp;
			}
		}

		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
		$assetStatusNote = $this->input->post('asset_status_note');

		if($this->form_validation->run() == FALSE){
			$data['failed_fields'] = $this->form_validation->error_array();
			$data['error_message']=validation_errors();
			if(isset($assetStatusNote) && $assetStatusNote!=""){
				$data['data']['hadStatusMessage'] = true;
			}
			$this->load->view('utility/notifier',$data);
			return false;
		}
		
		$asset_id=$this->input->post('asset_id');

		$assetData=array(
			'asset_name' 		=> $this->input->post('asset_name')
			,'type_id' 			=> $this->input->post('asset_type')
			,'asset_location'	=> $this->input->post('asset_location')
			,'asset_status' 	=> $this->input->post('asset_status')
		);

		$updateAsset = false;
		
		$logData = array();
		$assetOldData=$this->asset_model->getAsset($asset_id);
		foreach($this->asset_model->assetSetFields as $attributeName){
			if($assetData[$attributeName] != $assetOldData->$attributeName){ //making sure they aren't the same
				$updateAsset = true;
				$logData[$attributeName . ' from'] = $assetOldData->$attributeName;
				$logData[$attributeName . ' to'] = $assetData[$attributeName];
				
				if($attributeName=="type_id"){
					$logData[$attributeName . ' from'] .= ' ('.$this->type_model->getName($assetOldData->$attributeName).')';
					$logData[$attributeName . ' to'] .= ' ('.$this->type_model->getName($assetData[$attributeName]).')';	
				}
			}
		}
		
		$assetAllAttributes = $this->asset_model->getCurrentAttributes($asset_id);
		
		if(FALSE !== $this->input->post('attribute_value')){	
			foreach($this->input->post('attribute_value') as $asset_attribute_id => $value){
				if($assetAllAttributes[$asset_attribute_id]['attribute_value'] != $value){ //There is no reason to log or update, unless there is a change...
					$this->asset_model->editAssetAttribute($asset_attribute_id, $value);
					$this->log_model->log(array(
						'class' => 'asset',
						'method' => 'edit attribute value', 
						'asset_id' => $asset_id,
						'asset_name' => $assetOldData->asset_name,
						'attribute_name' => $assetAllAttributes[$asset_attribute_id]['attribute_name'],
						'data_from' => $assetAllAttributes[$asset_attribute_id]['attribute_value'],
						'data_to' => $value
					));
				}
			}
		}
		if(FALSE !== $this->input->post('attribute_delete')){
			foreach($this->input->post('attribute_delete') as $asset_attribute_id => $value){
				$logDesc=array(
					'asset_id'	=>  $asset_id,
					'asset_attribute_id'	=>	$asset_attribute_id,
				);
				$this->log_model->log(array(
						'class' => 'asset',
						'method' => 'delete attribute', 
						'asset_id' => $asset_id,
						'asset_name' => $assetData['asset_name'],
						'attribute_name' => $assetAllAttributes[$asset_attribute_id]['attribute_name'],
						'data_from' => $assetAllAttributes[$asset_attribute_id]['attribute_value']			
				));
				
				$this->asset_model->deleteAssetAttribute($asset_attribute_id);
				$data['data']['attributesToDelete'][]=$asset_attribute_id;
			}
		}
		
		if($updateAsset){
			$this->asset_model->edit($asset_id, $assetData);
			$this->log_model->log(array(
				'class' => 'asset',
				'method' => 'edit asset', 
				'asset_id' => $asset_id,
				'asset_name' => $assetOldData->asset_name,
				'description' => json_encode($logData)
			));
		}

		if(isset($assetStatusNote) && $assetStatusNote!=""){
			$this->note_model->addNote(array(
				'note_type' => 4
				,'asset_id' => $asset_id
				,'note' 	=> $this->input->post('asset_status_note')
			));
			$notes = $this->note_model->getNotesOfAsset($asset_id);
			$this->log_model->log(array(
				'class'         => 'asset'
				,'method'       => 'apiUpdateAsset-addNote'
                ,'asset_id'     => $asset_id
				,'asset_name'   => $this->asset_model->getName($asset_id)
				,'description'  => json_encode(array('note_type' => $notes[0]['note_type'],'note' => $notes[0]['note']))
			));

			$data['data']['notesTable'] = $this->generateNoteTable($this->note_model->getNotesOfAsset($asset_id));
		}

		$data['data']['attributeTable'] = $this->generateAttributeTable($this->asset_model->getCurrentAttributes($asset_id));
		$data['happy_message']='Asset Updated!';
		$this->load->view('utility/notifier',$data);
	}

    /**
     * Checks a given mac address for duplicates in the database. Also formats the mac address properly for the database.
     *
     * @param string $str Mac address
     * @return bool|string Returns false if the mac address is not formatted correctly or another mac address exists in the database. Returns a properly formatted mac address otherwise.
     */
    function check_duplicates_mac($str){

		$str=strtoupper(preg_replace("/[^a-zA-Z0-9]/","",$str));
		if(strlen($str)!=12 && strlen($str)!=0){
			$this->form_validation->set_message('check_duplicates_mac', 'Not a proper MAC address');
			return false;
		}
		$str=strtoupper(preg_replace("/[^a-fA-F0-9]/","",$str));
		if(strlen($str)!=12 && strlen($str)!=0){
			$this->form_validation->set_message('check_duplicates_mac', 'Not a proper MAC address');
			return false;
		}
		$macs = $this->attribute_model->countMACs($str,$this->input->post('asset_id'));
		if(!($str=="" || count($macs)<1)){
			$links="";
			foreach($macs as $mac){
				$links.=anchor('asset/edit/'.$mac['asset_id'],$mac['asset_name']);
			}
			$this->form_validation->set_message('check_duplicates_mac', 'This MAC is already in the system'.$links);
			return false;
		}
		return $str;
	}

    /**
     * Checks a given serial number for duplicates in the database.
     *
     * @param string $str Serial number
     * @return bool|string Returns false if the serial already exists in the database. Returns the serial back otherwise.
     */
    function check_duplicates_serial($str){
		
		$str=strtoupper(preg_replace("/[^a-zA-Z0-9]/","",$str));
		$serials = $this->attribute_model->countSerials($str,$this->input->post('asset_id'));
		if(!($str=="" || count($serials)<1)){
			$links="";
			foreach($serials as $serial){
				$links.=anchor('asset/edit/'.$serial['asset_id'],$serial['asset_name']);
				$this->form_validation->set_message('check_duplicates_serial', 'This serial is already in the system');
			}
			return false;
		}
		return $str;
	}

    /**
     * Loads the views for the "advanced search" page.
     */
    function search(){
		$data['all_attributes']=$this->attribute_model->getAttributes(true);	
		$data['all_types']= $this->type_model->getTypes();	
		$this->load->view('templates/header');
		$this->load->view('asset/searchById');
		
		$this->load->view('asset/searchByNameTypeAttribute',$data);
		
		//OLD VIEWS - DO NOT USE - THEY ARE NOT BOOTSTRAPPED. (they outta be scrapped, really)
		//$this->load->view('asset/searchByName');
		//$this->load->view('asset/searchByType',$data);
		//$this->load->view('asset/searchByAttribute',$data);
		
		$this->load->view('templates/footer');
	}

    /**
     * Loads the standard view with a list of assets using the search parameters from POST.
     */
    function searchByNameTypeAttribute(){
		$data['assets']=$this->asset_model->getAssetsByNameTypeAttribute($this->input->post('searchParams'));
		if($data['assets']==-1){
			$data['error_message'] = "There are no assets that match your search parameters";
		}
		$this->loadStandardAssetView($data);
	}

    /*
	function searchByAttribute(){
		$data['assets']=$this->asset_model->getAssetsByAttribute($this->input->post('searchParams'));
		if($data['assets']==-1){
			$data['error_message'] = "There are no assets that match your search parameters";
		}
		$this->loadStandardAssetView($data);
	}
	
	function searchByType(){
		//$this->allAssets($this->input->post('searchParams'));
		
		$data['assets']=$this->asset_model->getAssetsByType($this->input->post('searchParams'));
		if($data['assets']==-1){
			$data['error_message'] = "There are no assets that match your search parameters";
		}
		$this->loadStandardAssetView($data);
	}
	*/
	
	/*
		Returns a list of assets using a search string via POST. Search string can be partial, or wildcarded.
		- Assumes Post Data named 'asset_name' to exist
		- If requested with Ajax - returns array of data(results) for the javascript to use
		-- Otherwise it loads up the asset page with a list of assets it retrieved.
	*/
    /*
	function searchByName(){
		$postValidation = array(
			array(
				'field'=> 'asset_name',
				'label'=> 'Asset Name',
				'rules'=> 'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
		);
		$this->form_validation->set_rules($postValidation);
	
		if($this->form_validation->run() == FALSE){
			if($this->usingAjax()){
				$data['error_message'] = validation_errors();
				$this->load->view('utility/notifier',$data);
			}
			else{
				$data['error_message'] = validation_errors();
				$this->load->view('templates/header',$data);
				$this->load->view('asset/searchByName', $data);
				$this->load->view('templates/footer');
			}
			return false;
		}
		
		$data['assets']=$this->asset_model->getAssetsByName($this->input->post('asset_name'));
		
		
		if(count($data['assets'])==1&&isset($data['assets'][0]['asset_id'])){
			if($this->usingAjax()){
				$data['happy_message'] = "1 Asset Found!";
				$data['data']=$data['assets'];
				$this->load->view('utility/notifier',$data);
			}
			else{
				redirect('asset/edit/'.$data['assets'][0]['asset_id']);
			}
			return false;
		}
		if($data['assets']==-1){
			$data['error_message'] = "No assets match your search parameters.";
			if($this->usingAjax()){
				$this->load->view('utility/notifier',$data);
			}else{
				$this->load->view('templates/header',$data);
				$this->load->view('asset/searchByName', $data);
				$this->load->view('templates/footer');
			}
			return false;
		}
		
		if($this->usingAjax()){
			$data['happy_message'] = count($data['assets'])." Assets Found!";
			$data['data']=$data['assets'];
			$this->load->view('utility/notifier',$data);
		}else{
			//$data['everything']=$this->asset_model->getEverything();
			$this->loadStandardAssetView($data);
		}
	}
	*/

    /**
     * Searches for an asset using it's id passed via POST.
     * It will parse out the asset sticker prefix "090".
     * It also echos its results back via JSON array.
     *
     * @return bool Returns false if POST validation fails or asset doesn't exist.
     */
    function apiSearchById(){
		$postValidation = array(
			array(
				'field'=> 'asset_id',
				'label'=> 'Asset ID',
				'rules'=> 'trim|required|is_natural|xss_clean|prep_for_form'
			)
		);
		
		$this->form_validation->set_rules($postValidation);
		loadSimpleErrorMessages($this);
		$messages['data']=$this->input->post();
		if($this->form_validation->run() == FALSE){
			$messages['failed_fields'] = $this->form_validation->error_array();
			$messages['error_message']=validation_errors();
			$this->load->view('utility/notifier',$messages);
			return false;
		}
		$assetId = $this->input->post('asset_id');
		if(substr($assetId,0,3) === '090'){
			$assetId = substr($assetId,3);
		}
		
		if(!$this->asset_model->checkAssetExist($assetId)){
			$messages['error_message']='That asset doesn\'t exist.';
			$messages['failed_fields'] = array('asset_id' => $messages['error_message']);
			$this->load->view('utility/notifier',$messages);
			return false;
		}

		$messages['data']['asset_id']=$assetId;
		$messages['happy_message']='Asset Found! - Redirecting...';
		$this->load->view('utility/notifier',$messages);
	
	}

    /**
     * Loads up the history view of a given asset.
     *
     * @param int $asset_id The asset.
     */
    function history($asset_id){
		
		$this->load->view('templates/header',array('fluid' 	=>	true));
		$this->load->view('asset/history',array(
			'logData' => $this->log_model->getLogForAsset($asset_id),
			'asset_id' => $asset_id
		));
		$this->load->view('templates/footer');
	}

    /**
     * Adds a note on a given asset using info passed via POST.
     * It also echos back the current notes in table form via JSON (after the new note is added).
     * The new note is also recorded in the log.
     */
    function apiAddNote(){
		$postValidation = array(
			array(
				'field'	=> 'asset_id'
				,'label'=> 'Asset ID'
				,'rules'=> 'trim|required|is_natural|xss_clean|prep_for_form'
			)
			,array(
				'field'	=> 'note_type'
				,'label'=> 'Note Type'
				,'rules'=> 'trim|required|is_natural|xss_clean|prep_for_form'
			)
			,array(
				'field'	=> 'note'
				,'label'=> 'Asset Note'
				,'rules'=> 'trim|required|xss_clean|prep_for_form'
			)
		);

		$asset_id = $this->input->post('asset_id');

		$this->form_validation->set_rules($postValidation);
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$this->load->view('utility/notifier',$data);
		}else{
			$this->note_model->addNote($this->input->post());

			$notes = $this->note_model->getNotesOfAsset($asset_id);
			$data['data']['notesTable'] = $this->generateNoteTable($notes);

			$this->log_model->log(array(
				'class'         => 'asset'
				,'method'       => 'apiAddNote'
                ,'asset_id'     => $asset_id
				,'asset_name'   => $this->asset_model->getName($asset_id)
				,'description'  => json_encode(array('note_type' => $notes[0]['note_type'],'note' => $notes[0]['note']))
			));

			$data['happy_message']='Note Added';
			$this->load->view('utility/notifier',$data);
		}
	}

    /**
     * Adds a new asset link using the given POST data.
     * It also logs the creation of the link.
     * This function will also echo back the current links for the current asset in table form via JSON (after the new link is added).
     *
     * @return bool Returns false if POST validation fails.
     */
    function apiAddLink(){
		$postValidation = array(
			array(
				'field'	=> 'asset_id'
				,'label'=> 'Asset Id'
				,'rules'=> 'trim|required|is_natural|xss_clean|prep_for_form'
			)
			,array(
				'field'	=> 'new_linked_asset'
				,'label'=> 'Asset Id'
				,'rules'=> 'trim|required|is_natural|xss_clean|prep_for_form'
			)
			,array(
				'field'	=> 'link_note'
				,'label'=> 'Asset Id'
				,'rules'=> 'trim|xss_clean|prep_for_form|max_length[30]'
			)
		);
		loadSimpleErrorMessages($this);

		$this->form_validation->set_rules($postValidation);
		$data['data'] = $this->input->post();
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
            return false;
		}

        $newLink = array(
            'asset_id' => $this->input->post('asset_id')
            ,'asset_linked_to' => $this->input->post('new_linked_asset')
            ,'link_note' => $this->input->post('link_note')
        );


        $this->link_model->addLink($newLink);

        $links = $this->link_model->getLinksOfAsset($this->input->post('asset_id'));
        $data['data']['linksTable'] = $this->generateLinkTable($links);

        $this->log_model->log(array(
            'class'         =>	'asset'
            ,'method'       =>	'apiAddLink'
            ,'asset_id'		=>	$newLink['asset_id']
            ,'asset_name'	=>	$this->asset_model->getName($newLink['asset_id'])
            ,'description'  =>	json_encode(array('asset_linked_to' => $newLink['asset_linked_to'],'asset_linked_to_name' => $this->asset_model->getName($newLink['asset_linked_to']), 'link_note' => $newLink['link_note']))
        ));

        $data['happy_message'] = "Added Link";
        $this->load->view('utility/notifier',$data);

	}

    /**\
     * Deletes an asset link using the given POST data. It also logs link deletion.
     * Finally, it echos out an array of the deleted links in JSON form.
     *
     * @return bool Returns false if POST validation fails.
     */
    function apiDeleteLink(){
		$postValidation = array(
			array(
				'field'	=> 'asset_id'
				,'label'=> 'Asset Id'
				,'rules'=> 'trim|required|is_natural|xss_clean|prep_for_form'
			)
		);
		if(count($this->input->post('link_delete'))==0){
			$data['error_message'] = "You didn't check any links to delete!";
			$this->load->view('utility/notifier',$data);
			return;
		}
		foreach($this->input->post('link_delete') as $asset_link_id => $value){
			$postValidation[] = array(
				'field'  =>  'attribute_value['.$asset_link_id.']'
				,'label' =>  'Link Delete Checkbox #'.$asset_link_id
				,'rules' => 'trim|is_natural|xss_clean|prep_for_form'
			);
		}
		$this->form_validation->set_rules($postValidation);
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$this->load->view('utility/notifier',$data);
            return false;
		}else{
			$links=array();
			foreach($this->input->post('link_delete') as $asset_link_id => $value){
				$link=$this->link_model->getLink($asset_link_id);
				$links[]=$asset_link_id;
				
				$this->log_model->log(array(
					'class'         =>	'asset'
					,'method'       =>	'apiRemoveLink'
					,'asset_id'		=>	$link['asset_id']	
					,'asset_name'	=>	$this->asset_model->getName($link['asset_id'])
					,'description'  =>	json_encode(array('asset_linked_to' => $link['asset_linked_to'],'asset_linked_to_name' => $this->asset_model->getName($link['asset_linked_to']), 'link_note' => $link['link_note']))
				));
				$this->link_model->deleteLink($asset_link_id);
			}
			$data['data']['linksToRemove'] = $links;
			$data['happy_message'] = "Removed Link(s)";
			$this->load->view('utility/notifier',$data);
		}
	}

    /**
     * Searches the database for assets using given POST parameters. Echos out an array of assets via JSON.
     */
    function apiGetAssetsUsingSearchTerm(){
		$assets = $this->asset_model->getAssetsUsingSearchTerm($this->input->get('term'));
		for($i=0;$i<count($assets);$i++){
			$assets[$i]['cleanLabel'] = $assets[$i]['asset_name'];
			$assets[$i]['label'] = "{$assets[$i]['asset_name']}<span style='opacity:0.4; filter:alpha(opacity=40);'> / {$assets[$i]['asset_location']} / {$assets[$i]['type_name']} /</span> ID:{$assets[$i]['asset_id']}";
			$assets[$i]['value'] = $assets[$i]['asset_id'];

			unset($assets[$i]['asset_id']);
			unset($assets[$i]['asset_name']);
			unset($assets[$i]['asset_location']);
			unset($assets[$i]['type_name']);
		}
		$this->load->view('utility/json',array('data' => $assets));
	}

    /**
     * Generated a html table using the given array of note data.
     *
     * @param array $notes the raw note data in array form.
     * @return string A html formatted note table
     */
    function generateNoteTable($notes){
		$table = "";
	 	foreach($notes as $note){
	 		$table .= '<table class="table table-condensed table-striped table-bordered">';
	 		$table .= '<tr><td style="width:200px;">'.date('m/d/y H:i',strtotime($note['note_date'])).'</td><td>'.$note['note_type'].'</td></tr>';
			$table .= '<tr><td colspan="2">'.wordwrap($note['note'],35," ",true).'</td></tr>';
	 		$table .= '</table>';
		}
		return $table;
	}
    /**
     * Generated a html table using the given array of asset link data.
     *
     * @param array $links the raw asset link data in array form.
     * @return string A html formatted asset link table
     */
	function generateLinkTable($links){
		$table = "";
		if(count($links)==0){
			return;
		}
		$table .= '<table class="table table-condensed table-striped table-bordered">';
		$table .= '<tr><th>Asset</th><th>Note</th><th class="text-align-center" style="width:100px;">Remove Link</th></tr>';
	 	foreach($links as $link){
	 		$table .= '<tr linkId="'.$link['asset_link_id'].'"><td>'.anchor('asset/edit/'.$link['asset_id'],$link['asset_name']).'</td><td>'.$link['link_note'].'</td><td class="text-align-center">'.form_checkbox('link_delete['.$link['asset_link_id'].']','',false,'class="linkDelete"').'</td></tr>';
		}
		$table .= '</table>';
		$table .= form_button(array('id' => 'deleteLinkSubmit', 'name' => 'Delete Selected', 'value' => 'Delete Selected', 'content' => 'Delete Selected' , 'class' => 'btn btn-large input-block-level'));
		return $table;
	}
    /**
     * Generated a html table using the given array of attribute data. Also formats any attribute whose name contains "mac addr" as a mac address.
     *
     * @param array $current_attributes the raw attribute data in array form.
     * @return string A html formatted attribute table
     */
	function generateAttributeTable($current_attributes){

		//format the mac addresses properly.
		foreach($current_attributes as $id => $curAttribute){
			if(strstr(strtolower($curAttribute['attribute_name']),'mac addr') !== false){
				$current_attributes[$id]['attribute_value'] = strtoupper(wordwrap(preg_replace("/[^a-fA-F0-9]/","",$curAttribute['attribute_value']),2,":",true));
			}
		}

		$theTable = "";
		$theTable .='<table class="table table-condensed" style="margin-bottom:0px;"><tr><th style="padding-left:0px;">'.heading('Custom Attributes', 4).'</th><th class="text-align-right">'.heading('Delete', 4).'</th></tr></table>';
		foreach($current_attributes as $attribute){
			$inputAttributes=array(
				'name' 			=> 'attribute_value['.$attribute['asset_attribute_id'].']'
				,'value'		=> $attribute['attribute_value']
				,'originalValue'=>$attribute['attribute_value']
				,'maxlength' 	=> 30
				,'class' 		=> 'input-block-level'
				,'style'		=> 'margin-bottom:0px;'
			);

			$theTable .=
				'<div wrapping="'.$inputAttributes['name'].'" class="control-group">'
					.form_label($attribute['attribute_name'].' <span errorMessageFor="'.$inputAttributes['name'].'"></span>','',array('class' => 'control-label'))
					.'<div class="controls">'
						.'<table class="table table-condensed table-striped table-bordered" style="margin-bottom:10px"><tr><td>'
							.form_input($inputAttributes)
							.'</td><td class="text-align-center">'
			 				.form_checkbox('attribute_delete['.$attribute['asset_attribute_id'].']','',false,'class="assetAttribute-delete"')
			 				.'</td></tr></table>'
			 		.'</div>'
			 	.'</div>';
		}
		return $theTable;
	}
}
?>
<?php

/**
 * This is the type class. It contains all the functions to add, edit, delete asset types.
 */
class type extends CI_Controller {

    /**
     * Loads up all the external libraries used for this class.
     * Will also redirect to login if the user isn't logged in.
     * It will also redirect to a unauthorized error
     * if the current user is not an admin.
     */
	function __construct(){
		// Call the Model constructor
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('type_model');
		$this->load->model('attribute_model');
		$this->load->model('asset_model');
		$this->load->model('module_model');
		$this->load->helper('simple_error_messages');
		
		if(!$this->session->userdata('logged_in')){
			redirect('login', 'refresh');
			die();
		}

		if(!$this->user_model->curIsAdmin()){
			redirect('error/notAuthorized', 'refresh');
			die();
		}
	}

    function index(){
		$data['all_types']=$this->type_model->getTypes(false);
		$data['all_attributes']=$this->attribute_model->getAttributes(true);
		$data['all_modules']=$this->module_model->getModules(true);
		
		$data['typeTable'] = $this->generateTypeTable($data['all_types']);

		$this->load->view('templates/header');
		$this->load->view('type/types',$data);
		$this->load->view('templates/footer');
	}

    /**
     * Creates a new type using given POST data.
     * Also logs the creation of the type.
     * Finally echos out a fresh 
     *
     * @return bool Returns false on POST validation error.
     */
    function apiTypeCreate(){
		$postValidation = array(
			array(
    		'field'	 => 'new_type_name'
	        ,'label' => 'Type Name'
	        ,'rules' => 'trim|required|xss_clean|prep_for_form|max_length[30]'
	      )
	    );

		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
		
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}

		$newType['type_name'] = $this->input->post('new_type_name');
		$newType['type_id'] = $this->type_model->create($newType);

		$this->log_model->log(array(
			'class' 		=> 'type'
			,'method' 		=> 'apiCreateType'
			,'type_id' 		=> $newType['type_id']
			,'description'	=> json_encode($newType)
		));



		$data['data']=$newType;
		$data['data']['type_table']=$this->generateTypeTable($this->type_model->getTypes(false));
		$data['happy_message']='New type created!';
		$this->load->view('utility/notifier',$data);
	}

    /**
     * Generates a type table using the given array of types.
     *
     * @param array $types The array of types.
     * @return string The type table.
     */
    function generateTypeTable($types){
        $form_attributes = array(
			'id' 		=> 'editTypeForm'
			,'onsubmit' => 'asmDeleteTypes(); return false;'
		);

        $typeTable = '';
        $typeTable.= form_open('',$form_attributes);
		$typeTable.= '<table class="table table-striped table-condensed table-bordered table-hover">';
		$typeTable.= '<thead><tr><th>ID</th><th>Type Name</th><th class="text-align-center"><i class="icon-ok"></i></th></tr></thead><tbody>';
		

		foreach($types as $type_id => $type_name){
			$typeTable.= '<tr class="typeRow" typeid="'.$type_id.'"><td class="span1">'.$type_id.'</td><td typeNameFor="'.$type_id.'">'.$type_name.'</td><td class="span1 text-align-center">'.form_checkbox('type_delete['.$type_id.']','',false,"class='type-delete' style='margin:0px;' originalValue=''").'</td></tr>';
		}
		 
		$typeTable.= '</tbody></table>';
		$typeTable.= form_button(array('type' => 'submit','id' => 'deleteSelectedTypesSubmit', 'name' => 'Delete Selected', 'value' => 'Delete Selected', 'content' => 'Delete Selected' , 'class' => 'btn disabled btn-large btn-block'));
		$typeTable.= form_close();

		return $typeTable;
	}


	function generateTypeAttributeTable($typeAttributes){
		$form_attributes = array(
			'id' 		=> 'deleteTypeAttributeForm'
			,'onsubmit' => 'asmDeleteTypeAttributes(); return false;'
		);

		$typeAttributeTable = '';
		$typeAttributeTable.= form_open('',$form_attributes);
		$typeAttributeTable.= form_hidden(array('edit_type_id' => ''));
		$typeAttributeTable.= '<table class="table table-striped table-condensed table-bordered">';
		$typeAttributeTable.= '<thead><tr><th>Attribute Name</th><th class="text-align-center"><i class="icon-ok"></i></th></tr></thead><tbody>';
		
		foreach($typeAttributes as $attribute_id => $attribute_name){
			$typeAttributeTable.= '<tr typeattributeid="'.$attribute_id.'"><td>'.$attribute_name.'</td><td class="span1 text-align-center">'.form_checkbox('type_attribute_delete['.$attribute_id.']','',false,"class='type-attribute-delete' style='margin:0px;' originalValue=''").'</td></tr>';
		}
		 
		$typeAttributeTable.= '</tbody></table>';
		$typeAttributeTable.= form_button(array('id' => 'deleteTypeAttributeSubmit','type' => 'submit', 'name' => 'Remove Selected', 'value' => 'Remove Selected', 'content' => 'Remove Selected' , 'class' => 'btn disabled btn-block'));
		$typeAttributeTable.= form_close();

		return $typeAttributeTable;
	}

	function generateTypeModuleTable($typeModules){
		$form_attributes = array(
			'id' 		=> 'deleteTypeModuleForm'
			,'onsubmit' => 'asmDeleteTypeModules(); return false;'
		);

		$typeModuleTable = '';
		$typeModuleTable.= form_open('',$form_attributes);
		$typeModuleTable.= form_hidden(array('edit_type_id' => ''));
		$typeModuleTable.= '<table class="table table-striped table-condensed table-bordered">';
		$typeModuleTable.= '<thead><tr><th>Module Name</th><th class="text-align-center"><i class="icon-ok"></i></th></tr></thead><tbody>';

		foreach($typeModules as $module){
			$typeModuleTable.= '<tr typemodelid="'.$module['asset_type_module_id'].'"><td>'.$module['asset_module_name'].'</td><td class="span1 text-align-center">'.form_checkbox('type_module_delete['.$module['asset_type_module_id'].']','',false,"class='type-module-delete' style='margin:0px;' originalValue=''").'</td></tr>';
		}
		 
		$typeModuleTable.= '</tbody></table>';
		$typeModuleTable.= form_button(array('id' => 'deleteTypeModuleSubmit', 'type' => 'submit', 'name' => 'Remove Selected', 'value' => 'Remove Selected', 'content' => 'Remove Selected' , 'class' => 'btn disabled btn-block'));
		$typeModuleTable.= form_close();

		return $typeModuleTable;
	}

	function apiLoadTypeInfo(){
		$postValidation = array(
			array(
				'field'	 => 'type_id'
				,'label' => 'Type ID'
				,'rules' => 'trim|required|xss_clean|prep_for_form|is_natural'
			)
		);

		$this->form_validation->set_rules($postValidation);
		
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$this->load->view('utility/notifier',$data);
			return false;
		}

		$data['data']['type_info']['type_id'] = $this->input->post('type_id');
		$data['data']['type_info']['type_name'] =  $this->type_model->getName($data['data']['type_info']['type_id']);

		$data['data']['attributesTable'] = $this->generateTypeAttributeTable($this->type_model->getAttributes($data['data']['type_info']['type_id']));
		$data['data']['modulesTable'] = $this->generateTypeModuleTable($this->type_model->getModulesOfType($data['data']['type_info']['type_id']));

		$data['happy_message'] = "Loaded type info";+
		$this->load->view('utility/notifier',$data);
	}

	function apiUpdateName(){
		$postValidation = array(
			array(
				'field'	 => 'edit_type_id'
				,'label' => 'Type ID'
				,'rules' => 'trim|required|xss_clean|prep_for_form|is_natural'
			)
			,array(
				'field'	 => 'edit_type_name'
				,'label' => 'Type Name'
				,'rules' => 'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
		);

		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
		
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}

		$data['data']['type_id'] = $this->input->post('edit_type_id');
		$data['data']['type_name'] = $this->input->post('edit_type_name');

		$oldname = $this->type_model->getName($data['data']['type_id']);

		$this->type_model->updateType($data['data']['type_id'],$data['data']['type_name']);


		$this->log_model->log(array(
			'class' 		=> 'type'
			,'method' 		=> 'apiUpdateName'
			,'type_id' 		=> $data['data']['type_id']
			,'data_from'	=> $oldname
			,'data_to'		=> $data['data']['type_name']
		));

		$data['happy_message'] = "Name Updated!";
		$this->load->view('utility/notifier',$data);
	}


    /**
     * Adds an attribute to a given type from the info passed from POST.
     * Then, automatically adds the attribute to any asset that is of the same type.
     * This also logs all changes made to any asset as well as the addition of the attribute-type association.
     * Finally it echos out a fresh type attribute table in JSON format.
     *
     * @return bool Returns false on POST validation error.
     */
    function apiAddTypeAttribute(){
		$postValidation = array(
			array(
				'field'	 	=> 'edit_type_id'
				,'label' 	=> 'Type ID'
				,'rules' 	=> 'trim|required|xss_clean|prep_for_form|is_natural'
			)
			,array(
    			'field'		=> 'new_type_attribute'
	        	,'label'	=> 'Attribute id'
	        	,'rules'	=> 'trim|required|xss_clean|prep_for_form|is_natural_no_zero'
	    	)
	    );

	    loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);

		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}

		$data=array(
			'type_id' 		=> $this->input->post('edit_type_id')
			,'attribute_id' => $this->input->post('new_type_attribute')
		);

		$this->type_model->addAttribute($data);
	
		$data['attribute_name'] = $this->attribute_model->getName($data['attribute_id']);
		$data['type_name']=$this->type_model->getName($data['type_id']);

		$this->type_model->addAttributeToAllAssetsWithType($data['attribute_id'],$data['type_id']);

		$assetsToAddAttributeArray=$this->asset_model->getAssetsByType(array('type_id' => array($data['type_id'])));

		$attrubutesAdded=array();
		if($assetsToAddAttributeArray !== NULL && $assetsToAddAttributeArray !==-1){
			foreach($assetsToAddAttributeArray as $theAsset){	
				$attrubutesAdded[]= $theAsset['asset_id'].' - '.$theAsset['asset_name'];
			}
		}

		$this->log_model->log(array(
			'class' 			=> 'type'
			,'method' 			=> 'addAttribute'
			,'type_id'			=> $data['type_id']
			,'type_name' 		=> $data['type_name']
			,'attribute_id' 	=> $data['attribute_id']
			,'attribute_name' 	=> $data['attribute_name']
			,'description' 		=> json_encode(array(
				'type_name' 	=> $data['type_name']
				,'attribute_name Added' => $data['attribute_name']
				,'attribute_id Added' 	=> $data['attribute_id']
				,'Assets I added the attribute to:' => implode(',<br />',$attrubutesAdded)
			))
		));

		$data['data']['attributesTable'] = $this->generateTypeAttributeTable($this->type_model->getAttributes($data['type_id']));
		$data['data']['type_id'] = $data['type_id'];
		$data['happy_message'] = 'Added Attribute Association';
		$this->load->view('utility/notifier',$data);
	}

    /**
     * If any attributes are marked for un-association in POST, this function will remove the attribute-type association.
     * This will also remove the attribute from any asset of the given type if (and only if) the attribute is blank on the asset.
     * Then it logs any changes made to any asset and logs the un-association. Finally it echos out a fresh type attribute table in JSON format.
     *
     * @return bool Returns false on POST validation error.
     */
    function apiDeleteTypeAttribute(){

    	$postValidation = array(
			array(
				'field'	 	=> 'edit_type_id'
				,'label' 	=> 'Type ID'
				,'rules' 	=> 'trim|required|xss_clean|prep_for_form|is_natural'
			)
	    );

	    loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);

		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}
		
		$type_id = $this->input->post('edit_type_id');
		$typeName = $this->type_model->getName($type_id);
		
		if(FALSE !== $this->input->post('type_attribute_delete')){
			$allTypeAttributes = $this->type_model->getTypeAttributeData($type_id);
			
			//$types = $this->type_model->getTypes();
			//$data['types']=$types;
			$typeAttributesToDelete = array();
			//var_dump($this->input->post('type_attribute_delete'));
			//var_dump($allTypeAttributes);
			foreach($this->input->post('type_attribute_delete') as $type_attribute_id => $dummyVar){
				//echo 'allTypeAttributes: '. $allTypeAttributes[$type_attribute_id] ."\r\n";

				if(isset($typeAttributesToDelete[$allTypeAttributes[$type_attribute_id]['attribute_id']])){
					$typeAttributesToDelete[$allTypeAttributes[$type_attribute_id]['attribute_id']]++;
				}else{
					$typeAttributesToDelete[$allTypeAttributes[$type_attribute_id]['attribute_id']] = 1;
				}
			}
			//exit;

			//REMOVING 1 ==BLANK== ATTRIBUTE FROM EVERY ASSET IN A TYPE WHEN THE ATTRIBUTE IS UN ASSOC-ED FROM THAT TYPE.
			$assetAttributesDeleted=array();
			$assetsToRemoveAttributeArray=$this->asset_model->getAssetsByType(array('type_id' => array($type_id)));
			if($assetsToRemoveAttributeArray !== NULL && $assetsToRemoveAttributeArray !==-1){
				foreach($assetsToRemoveAttributeArray as $theAsset){
					$attributesRemoved = array();
					
					$assetAttributesArray = $this->asset_model->getCurrentAttributes($theAsset['asset_id']);
					foreach($assetAttributesArray as $asset_attribute_id => $attribute){
						if($attribute['attribute_value'] == '' && isset($typeAttributesToDelete[$attribute['attribute_id']]) ){// && //!$removedAttribute){

							if(!isset($attributesRemoved[$attribute['attribute_id']])){
								$attributesRemoved[$attribute['attribute_id']] = 1;
								$assetAttributesDeleted[] = $theAsset['asset_id']." - Asset: '".$theAsset['asset_name']."' - Attr: '".$attribute['attribute_name']."'";
								$this->asset_model->deleteAssetAttribute($asset_attribute_id);
							}elseif($attributesRemoved[$attribute['attribute_id']] < $typeAttributesToDelete[$attribute['attribute_id']]){
								$attributesRemoved[$attribute['attribute_id']]++;
								$assetAttributesDeleted[] = $theAsset['asset_id']." - Asset: '".$theAsset['asset_name']."' - Attr: '".$attribute['attribute_name']."'";
								$this->asset_model->deleteAssetAttribute($asset_attribute_id);
							}
							
						}
					}	
				}
			}

			/*
			foreach($this->input->post('type_attribute_delete') as $type_attribute_id => $dummyVar){
				if(isset($typeAttributesToDelete[$type_attribute_id])){
					$typeAttributesToDelete[$type_attribute_id]++;
				}else{
					$typeAttributesToDelete[$type_attribute_id] = 1;
				}

			}
			
			*/
			
			$this->type_model->deleteAttributes($this->input->post('type_attribute_delete'));	

			$this->log_model->log(array(
				'class' 			=> 'type'
				,'method' 			=> 'apiDeleteTypeAttribute'
				,'type_id' 			=> $type_id
				,'type_name' 		=> $typeName
				//,'attribute_name' 	=> $allTypeAttributes[$type_attribute_id]
				,'description' 		=> json_encode(array(
					//'type_name' 				=> $typeName
					//,'attribute_name removed' 	=> $data['attribute_name']
					//,'attribute_id' 				=> $this->type_model->getAttributeID($type_attribute_id)
					'Asset Attributes Removed'	=> implode(', <br />',$assetAttributesDeleted)
				))
			));

		}
		
		$data['data']['attributesTable'] = $this->generateTypeAttributeTable($this->type_model->getAttributes($type_id));
		$data['data']['type_id'] = $type_id;
		$data['happy_message'] = 'Attribute(s) Un-Associated';
		$this->load->view('utility/notifier',$data);
	}


	function apiAddTypeModule(){
		$postValidation = array(
			array(
				'field'	 	=> 'edit_type_id'
				,'label' 	=> 'Type ID'
				,'rules' 	=> 'trim|required|xss_clean|prep_for_form|is_natural'
			)
			,array(
    			'field'		=> 'new_type_module'
	        	,'label'	=> 'Attribute id'
	        	,'rules'	=> 'trim|required|xss_clean|prep_for_form|is_natural_no_zero'
	    	)
	    );

	    loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);

		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}

		$data=array(
			'asset_type_id'		=> $this->input->post('edit_type_id')
			,'asset_module_id' 	=> $this->input->post('new_type_module')
		);

		$currentModules = $this->type_model->getModulesOfType($data['asset_type_id']);

		foreach($currentModules as $module){
			if($module['asset_module_id']==$data['asset_module_id']){
				$data['error_message'] = "You cannot add two of the same modules to a type!";
				$data['failed_fields'] = array('new_type_module' => 'Already Associated');
				$this->load->view('utility/notifier',$data);
				return false;
			}
		}


		$this->type_model->addModule($data);
		$data['type_name'] = $this->type_model->getName($data['asset_type_id']);
		$this->log_model->log(array(
			'class' 			=> 'type'
			,'method' 			=> 'apiAddTypeModule'
			,'type_id'			=> $data['asset_type_id']
			,'type_name' 		=> $data['type_name']
			,'description' 		=> json_encode(array(
				'type_name' 	=> $data['type_name']
				,'module name Added' => $this->module_model->getModuleName($data['asset_module_id'])
				,'module id Added' 	=> $data['asset_module_id']
			))
		));
		
		$data['data']['modulesTable'] = $this->generateTypeModuleTable($this->type_model->getModulesOfType($data['asset_type_id']));
		$data['data']['type_id'] = $data['asset_type_id'];
		$data['happy_message'] = 'Added Module Association';
		$this->load->view('utility/notifier',$data);
	}

	function apiDeleteTypeModule(){
		$postValidation = array(
			array(
				'field'	 	=> 'edit_type_id'
				,'label' 	=> 'Type ID'
				,'rules' 	=> 'trim|required|xss_clean|prep_for_form|is_natural'
			)
		);
		
		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);

		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}

		$moduleNames=array();
		$modulesToRemove=array();
		foreach ($this->input->post('type_module_delete') as $asset_type_module_id => $dummyVar) {
			$modulesToRemove[]=$asset_type_module_id;
			$moduleNames[]=$this->module_model->getModuleNameUsingUniqueID($asset_type_module_id);
		}

		$this->type_model->removeModules($modulesToRemove);

		$data['type_name'] = $this->type_model->getName($this->input->post('edit_type_id'));
		$this->log_model->log(array(
			'class' 			=> 'type'
			,'method' 			=> 'apiDeleteTypeModule'
			,'type_id'			=> $this->input->post('edit_type_id')
			,'type_name' 		=> $data['type_name']
			,'description' 		=> json_encode(array(
				'type_name' 	=> $data['type_name']
				,'modules removed' 	=>  implode (", ", $moduleNames)
			))
		));

		$data['data']['modulesTable'] = $this->generateTypeModuleTable($this->type_model->getModulesOfType($this->input->post('edit_type_id')));
		$data['data']['type_id'] = $this->input->post('edit_type_id');
		$data['happy_message'] = 'Removed Module Association(s)';
		$this->load->view('utility/notifier',$data);
	}

	function apiDeleteTypes(){
		
	

		$typesToDelete=array();
		$descriptionArray=array();
		foreach ($this->input->post('type_delete') as $type_id => $dummyVar){
			if(!is_numeric($type_id)){
				$data['error_message'] = "The type id's to delete must be numbers.";
				$this->load->view('utility/notifier',$data);
				return false;
			}
			$type_name=$this->type_model->getName($type_id);
			$typesToDelete[]=$type_id;
			$descriptionArray['Type - '.$type_name.' (ID: '.$type_id.') Attributes'] =var_export($this->type_model->getAttributes($type_id),true);
			$descriptionArray['Type - '.$type_name.' (ID: '.$type_id.') Modules'] = var_export($this->type_model->getModulesOfType($type_id),true);
		}
		

		$this->log_model->log(array(
			'class' 			=> 'type'
			,'method' 			=> 'apiDeleteTypes'
			,'description' 		=> json_encode($descriptionArray)
		));

		$this->type_model->deleteTypes($typesToDelete);

		$data['data']['type_table']=$this->generateTypeTable($this->type_model->getTypes(false));
		$data['happy_message']='Type(s) Deleted';
		$this->load->view('utility/notifier',$data);
		return;
	}
}
?>
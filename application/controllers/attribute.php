<?php
/**
 * The attribute class. Contains all the functions regarding creating new types of attributes, editing attributes and deleting attributes. This is an admin only class.
 */
class attribute extends CI_Controller {

    /**
     * Loads up all the standard models used for this class. Kicks the user back to login if not logged in.
     */
    function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('attribute_model');
		$this->load->model('type_model');
		$this->load->model('asset_model');
		if(!$this->session->userdata('logged_in')){
			redirect('login', 'refresh');
			die();
		}
		if(!$this->user_model->curIsAdmin()){
			redirect('error/notAuthorized', 'refresh');
			die();
		}
	}

    /**
     * Equivalent to edit().
     */
    function index(){
		$this->edit();
	}

    /**
     * Creates a new attribute using given POST data. It also logs the creation of the attribute.
     *
     * @return bool Returns false on POST validation error.
     */
    function create(){
		$postValidation = array(
			array(
				'field' =>'new_attribute_name'
				,'label'=>'Attribute Name'
				,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
		);
		$this->form_validation->set_rules($postValidation);
		if($this->form_validation->run() == FALSE){
			$data = array(
				'all_attributes'=>$this->attribute_model->getAttributes()
			);
			$messages['error_message']=validation_errors();
			$this->load->view('templates/header', $messages);
			$this->load->view('attribute/attributes',$data);
			$this->load->view('templates/footer');
			return false;
		}
		$data=array(
			'attribute_name' => $this->input->post('new_attribute_name')
		);
		$attribute_id=$this->attribute_model->create($data);
		
		$this->log_model->log(array(
			'class'             => 'attribute'
			,'method'           => 'create'
			,'attribute_id'     => $attribute_id
			,'attribute_name'   => $data['attribute_name']
		));
		redirect('attribute/edit');
	}

    /**
     * Renames attributes using given POST data. It also deletes any attributes marked for deletion. It also logs all changes made to attributes
     *
     * @return bool Returns false on POST validation error.
     */
    function edit(){
		$postValidation=array();

		if($this->input->post('attribute_name')!=""){
			$lineNum=0;
			foreach($this->input->post('attribute_name') as $id => $value){
				$lineNum++;
				$postValidation[]=array(
					'field'	=> 	'attribute_name['.$id.']',
					'label'	=> 	'attribute in field #'.$lineNum.' has a problem. This',
					'rules'	=>	'trim|required|xss_clean|prep_for_form|max_length[30]'
				);
			}
		}

		$this->form_validation->set_rules($postValidation);

		$data = array(
			'all_attributes'=>$this->attribute_model->getAttributes()
		);
				
		if($this->form_validation->run() == FALSE){
			$messages['error_message']=validation_errors();
			$this->load->view('templates/header', $messages);
			$this->load->view('attribute/attributes',$data);
			$this->load->view('templates/footer');
			return false;
		}

		if(FALSE !== $this->input->post('attribute_name')){
			foreach($this->input->post('attribute_name') as $AttributeId => $AttributeNewValue){
				if($data['all_attributes'][$AttributeId] != $AttributeNewValue){ //There is no reason to log or update, unless there is a change... 
					//$attributes_to_update[$id]['name']=$value;
					$this->attribute_model->editAttribute($AttributeId,$AttributeNewValue);
					
					$this->log_model->log(array(
						'class' => 'attribute',
						'method' => 'update', 
						'attribute_id' => $AttributeId,
						'attribute_name' => $data['all_attributes'][$AttributeId],
						'data_from' => $data['all_attributes'][$AttributeId],
						'data_to' => $AttributeNewValue
					));
				}
			}
		}

		if(FALSE !== $this->input->post('attribute_delete')){
			foreach($this->input->post('attribute_delete') as $AttributeId => $dummyVar){
				
				$this->log_model->log(array(
					'class' => 'attribute',
					'method' => 'delete', 
					'attribute_id' => $AttributeId,
					'attribute_name' => $data['all_attributes'][$AttributeId]
				));
				
				$assetsWithDeletedAttributeArray = $this->asset_model->getAssetsByAttribute(array('attribute_id' => array($AttributeId)));
				foreach($assetsWithDeletedAttributeArray as $asset){
					$assetAttributes=$this->asset_model->getCurrentAttributes($asset['asset_id']);
					forEach($assetAttributes as $attribute){
						if($attribute['attribute_id']==$AttributeId){
							$this->log_model->log(array(
								'class' => 'attribute',
								'method' => 'deleteAttribute',
								'asset_id' => $asset['asset_id'],
								'asset_name' => $this->asset_model->getName($asset['asset_id']),
								'attribute_id' => $AttributeId,
								'attribute_name' => $data['all_attributes'][$AttributeId],
								'data_from' => $attribute['attribute_value']
							));
						}
					}
				}
				$this->attribute_model->deleteAttribute($AttributeId);
			}
		}
		redirect('attribute/edit');
	}
}
?>
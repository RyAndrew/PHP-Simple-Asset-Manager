<?php

		echo form_open('asset/confirmStatusChange');
		echo "<H2>Asset Status Change</H2>";
		echo "please enter a reason for changing the status of this asset<BR><BR>";
		echo form_input('statusChangeReason',set_value('statusChangeReason')) , "<BR>";
		/*
			'asset_id' => $asset_id
			,'assetData' => $assetData
			,'attributeData' => array()
			,'attributesDeleted' => array()
		*/
		echo form_hidden('asset_id',set_value('asset_id', $asset_id));
		foreach($assetData as $assetDataName => $assetDataValue){
			echo form_hidden($assetDataName,set_value($assetDataName, $assetDataValue));
		}
		foreach($attributeData as $attributeChangeValueId => $attributeDataValue){
			echo form_hidden('attribute_value[' . $attributeChangeValueId . ']',set_value('attribute_value[' . $attributeChangeValueId . ']', $attributeDataValue)) ;
		}
		foreach($attributesDeleted as $attributesDeleted => $attributesDeletedId){
			echo form_hidden('attribute_delete[' . $attributesDeletedId . ']','') ;
		}
		echo form_hidden('reasonEntered','yes') ;
		echo form_submit('Save Changes','Save Changes');
		echo form_close();
		
?>
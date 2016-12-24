<?php

class Vt_ProductsConfig_Model_System_Config_Source_DescriptionSrc
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'description',	'label'=>Mage::helper('productsconfig')->__('Description')),
        	array('value'=>'short_description',	'label'=>Mage::helper('productsconfig')->__('Short Description'))
		);
	}
}

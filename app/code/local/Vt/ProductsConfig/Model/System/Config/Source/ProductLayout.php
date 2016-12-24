<?php

class Vt_ProductsConfig_Model_System_Config_Source_ProductLayout
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'layout01',	'label'=>Mage::helper('productsconfig')->__('layout01')),
        	array('value'=>'layout02',	'label'=>Mage::helper('productsconfig')->__('layout02'))
		);
	}
}

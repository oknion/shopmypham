<?php

class Vt_Supershop_Model_System_Config_Source_ListColunmListing
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'2',	'label'=>Mage::helper('productsconfig')->__('2')),
        	array('value'=>'3',	'label'=>Mage::helper('productsconfig')->__('3')),
        	array('value'=>'4',	'label'=>Mage::helper('productsconfig')->__('4')),
			array('value'=>'6',	'label'=>Mage::helper('productsconfig')->__('6'))
		);
	}
}

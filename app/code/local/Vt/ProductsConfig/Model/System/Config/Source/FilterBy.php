<?php

class Vt_ProductsConfig_Model_System_Config_Source_FilterBy
{
	public function toOptionArray()
	{
		return array(
			array('value' => 'all',	'label' => Mage::helper('productsconfig')->__('All product')),
			array('value' => 'sale', 	'label' => Mage::helper('productsconfig')->__('Only Sale product')),
			array('value' => 'deals', 	'label' => Mage::helper('productsconfig')->__('Hot Deals'))
		);
	}
}

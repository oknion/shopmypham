<?php


class Vt_ProductsConfig_Model_System_Config_Source_OrderDirection
{
	public function toOptionArray()
	{
		return array(
			array('value' => 'asc',			'label' => Mage::helper('productsconfig')->__('Asc')),
			array('value' => 'desc', 		'label' => Mage::helper('productsconfig')->__('Desc'))
		);
	}
}

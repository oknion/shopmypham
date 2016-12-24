<?php

class Vt_ProductsConfig_Model_System_Config_Source_ProductSources
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'catalog',	'label'=>Mage::helper('productsconfig')->__('Catalog')),
        	array('value'=>'product',	'label'=>Mage::helper('productsconfig')->__('Product'))
		);
	}
}

<?php

class Vt_ProductsConfig_Model_System_Config_Source_OrderBy
{
	public function toOptionArray()
	{
		return array(
			array('value' => 'position',	'label' => Mage::helper('productsconfig')->__('Position')),
			array('value' => 'created_at', 	'label' => Mage::helper('productsconfig')->__('Date Created')),
			array('value' => 'name', 		'label' => Mage::helper('productsconfig')->__('Name')),
			array('value' => 'price', 		'label' => Mage::helper('productsconfig')->__('Price')),
			array('value' => 'random', 		'label' => Mage::helper('productsconfig')->__('Random')),
			array('value' => 'top_rating', 	'label' => Mage::helper('productsconfig')->__('Top Rating')),
			array('value' => 'most_reviewed',	'label' => Mage::helper('productsconfig')->__('Most Reviews')),
			array('value' => 'most_viewed',	'label' => Mage::helper('productsconfig')->__('Most Viewed')),
			array('value' => 'best_sales',	'label' => Mage::helper('productsconfig')->__('Most Selling')),
		);
	}
}

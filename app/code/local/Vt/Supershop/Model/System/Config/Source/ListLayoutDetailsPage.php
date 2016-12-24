<?php

class Vt_Supershop_Model_System_Config_Source_ListLayoutDetailsPage
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'one_column',	'label'=>Mage::helper('supershop')->__('1 Colunn')),
        	array('value'=>'two_columns_left',	'label'=>Mage::helper('supershop')->__('2 Colunms Left')),
        	array('value'=>'two_columns_right',	'label'=>Mage::helper('supershop')->__('2 Colunms Right')),
		);
	}
}

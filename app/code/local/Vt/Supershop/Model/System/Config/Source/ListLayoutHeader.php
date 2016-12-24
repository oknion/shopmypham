<?php
/*------------------------------------------------------------------------
 # VT Megashop - Version 1.0
 # Copyright (c) 2014 The VnThemePro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: VnThemePro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/

class Vt_Supershop_Model_System_Config_Source_ListLayoutHeader
{
	public function toOptionArray()
	{	
		return array(
			array('value'=>'1', 'label'=>Mage::helper('supershop')->__('Header default')),			
			array('value'=>'2', 'label'=>Mage::helper('supershop')->__('Header 02')),			
		);
	}
}

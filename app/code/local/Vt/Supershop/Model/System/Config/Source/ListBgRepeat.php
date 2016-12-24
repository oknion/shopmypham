<?php
/*------------------------------------------------------------------------
 # VT Supershop - Version 1.0
 # Copyright (c) 2014 The VnThemePro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: VnThemePro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/

class Vt_Supershop_Model_System_Config_Source_ListBgRepeat
{
	public function toOptionArray()
	{	
		return array(
			array('value'=>'repeat', 'label'=>Mage::helper('supershop')->__('repeat')),
			array('value'=>'repeat-x', 'label'=>Mage::helper('supershop')->__('repeat-x')),
			array('value'=>'repeat-y', 'label'=>Mage::helper('supershop')->__('repeat-y')),
			array('value'=>'no-repeat', 'label'=>Mage::helper('supershop')->__('no-repeat'))
		);
	}
}

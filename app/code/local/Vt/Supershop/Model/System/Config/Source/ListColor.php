<?php
/*------------------------------------------------------------------------
 # VT Supershop - Version 1.0
 # Copyright (c) 2014 The VnThemePro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: VnThemePro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/

class Vt_Supershop_Model_System_Config_Source_ListColor
{
	public function toOptionArray()
	{	
		return array(
		array('value'=>'default', 'label'=>Mage::helper('supershop')->__('Default')),
		array('value'=>'blue', 'label'=>Mage::helper('supershop')->__('Blue')),
		array('value'=>'yellow', 'label'=>Mage::helper('supershop')->__('Yellow-Dark')),
		array('value'=>'yellow2', 'label'=>Mage::helper('supershop')->__('Yellow2')),
		array('value'=>'green', 'label'=>Mage::helper('supershop')->__('Green-Dark')),
		array('value'=>'orange', 'label'=>Mage::helper('supershop')->__('Orange-Dark')),
		array('value'=>'cyan', 'label'=>Mage::helper('supershop')->__('Cyan-Dark')),
		array('value'=>'burgundy', 'label'=>Mage::helper('supershop')->__('Burgundy')),
		array('value'=>'red-orange', 'label'=>Mage::helper('supershop')->__('Red-orange')),
		);
	}
}

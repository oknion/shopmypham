<?php
/*------------------------------------------------------------------------
 # VT Supershop - Version 1.0
 # Copyright (c) 2014 The VnThemePro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: VnThemePro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/

class Vt_Supershop_Model_System_Config_Source_ListBgPosition
{
	public function toOptionArray()
	{	
		return array(
			array('value'=>'left top', 'label'=>Mage::helper('supershop')->__('left top')),
			array('value'=>'left top', 'label'=>Mage::helper('supershop')->__('left top')),
			array('value'=>'left bottom', 'label'=>Mage::helper('supershop')->__('left bottom')),
			array('value'=>'right top', 'label'=>Mage::helper('supershop')->__('right top')),
			array('value'=>'right center', 'label'=>Mage::helper('supershop')->__('right center')),
			array('value'=>'right bottom', 'label'=>Mage::helper('supershop')->__('right bottom')),
			array('value'=>'center top', 'label'=>Mage::helper('supershop')->__('center top')),
			array('value'=>'center center', 'label'=>Mage::helper('supershop')->__('center center')),
			array('value'=>'center bottom', 'label'=>Mage::helper('supershop')->__('center bottom'))
		);
	}
}

<?php
/*------------------------------------------------------------------------
 # VT Supershop - Version 1.0
 # Copyright (c) 2014 The VnThemePro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: VnThemePro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/

class Vt_Supershop_Model_System_Config_Source_ListGoogleFont
{
	public function toOptionArray()
	{	
		return array(
			array('value'=>'', 'label'=>Mage::helper('supershop')->__('No select')),
			array('value'=>'Roboto', 'label'=>Mage::helper('supershop')->__('Roboto')),
			array('value'=>'Anton', 'label'=>Mage::helper('supershop')->__('Anton')),
			array('value'=>'Questrial', 'label'=>Mage::helper('supershop')->__('Questrial')),
			array('value'=>'Kameron', 'label'=>Mage::helper('supershop')->__('Kameron')),
			array('value'=>'Oswald', 'label'=>Mage::helper('supershop')->__('Oswald')),
			array('value'=>'Open Sans', 'label'=>Mage::helper('supershop')->__('Open Sans')),
			array('value'=>'BenchNine', 'label'=>Mage::helper('supershop')->__('BenchNine')),
			array('value'=>'Droid Sans', 'label'=>Mage::helper('supershop')->__('Droid Sans')),
			array('value'=>'Droid Serif', 'label'=>Mage::helper('supershop')->__('Droid Serif')),
			array('value'=>'PT Sans', 'label'=>Mage::helper('supershop')->__('PT Sans')),
			array('value'=>'Vollkorn', 'label'=>Mage::helper('supershop')->__('Vollkorn')),
			array('value'=>'Ubuntu', 'label'=>Mage::helper('supershop')->__('Ubuntu')),
			array('value'=>'Neucha', 'label'=>Mage::helper('supershop')->__('Neucha')),
			array('value'=>'Cuprum', 'label'=>Mage::helper('supershop')->__('Cuprum'))	
		);
	}
}

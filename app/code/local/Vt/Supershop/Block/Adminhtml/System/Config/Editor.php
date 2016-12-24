<?php
/*------------------------------------------------------------------------
 # VT Supershop - Version 1.0
 # Copyright (c) 2014 The VnThemePro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: VnThemePro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/

class Vt_Supershop_Block_Adminhtml_System_Config_Editor 
	extends Mage_Adminhtml_Block_System_Config_Form_Field 
    implements Varien_Data_Form_Element_Renderer_Interface {
	
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $element->setWysiwyg(true);
        $element->setConfig(Mage::getSingleton('cms/wysiwyg_config')->getConfig());
        return parent::_getElementHtml($element);
    }	
}

<?php

class Vt_SlideShow_Model_System_Config_Source_NavigationArrows{
	public function toOptionArray(){
		return array(
		array('value'=>'solo', 'label'=>Mage::helper('slideshow')->__('solo')),
		array('value'=>'nextto', 'label'=>Mage::helper('slideshow')->__('nextto')),
		array('value'=>'none', 'label'=>Mage::helper('slideshow')->__('none')),
		);
	}
}

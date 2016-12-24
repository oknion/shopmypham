<?php

class Vt_SlideShow_Model_System_Config_Source_NavigationType{
	public function toOptionArray(){
		return array(
		array('value'=>'bullet', 'label'=>Mage::helper('slideshow')->__('Bullet')),
		array('value'=>'thumb', 'label'=>Mage::helper('slideshow')->__('Thumb')),
		array('value'=>'none', 'label'=>Mage::helper('slideshow')->__('None')),
		);
	}
}

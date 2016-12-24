<?php

class Vt_SlideShow_Model_System_Config_Source_NavigationStyle{
	public function toOptionArray(){
		return array(
		array('value'=>'round', 'label'=>Mage::helper('slideshow')->__('round')),
		array('value'=>'square', 'label'=>Mage::helper('slideshow')->__('square')),
		array('value'=>'navbar', 'label'=>Mage::helper('slideshow')->__('navbar')),
		array('value'=>'round-old', 'label'=>Mage::helper('slideshow')->__('round-old')),
		array('value'=>'square-old', 'label'=>Mage::helper('slideshow')->__('square-old')),
		array('value'=>'navbar-old', 'label'=>Mage::helper('slideshow')->__('navbar-old')),
		);
	}
}

<?php

class Vt_SlideShow_Model_System_Config_Source_TimerBar{
	public function toOptionArray(){
		return array(
		array('value'=>'top', 'label'=>Mage::helper('slideshow')->__('Top')),
		array('value'=>'bottom', 'label'=>Mage::helper('slideshow')->__('Bottom'))
		);
	}
}

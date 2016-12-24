<?php

class Vt_SlideShow_Helper_Data extends Mage_Core_Helper_Abstract {
	public function __construct(){
		$this->defaults = array(
			/* General options */
			'title' 		=> 'vt Revolution Slider',
			'autoplay'		=> '1',
			'html_slides'	=>'',
			'include_jquery'	=> '1',
			'pretext'			=> '',
			'posttext'			=> ''

			/**config_fields**/
		);
	}

	function get($attributes=array())
	{
		$data = $this->defaults;
		$general 					= Mage::getStoreConfig("slideshow_cfg/general");
		$slideshow_effect				= Mage::getStoreConfig("slideshow_cfg/slideshow_effect");
		$advanced 					= Mage::getStoreConfig("slideshow_cfg/advanced");
		if (!is_array($attributes)) {
			$attributes = array($attributes);
		}
		if (is_array($general))					$data = array_merge($data, $general);
		if (is_array($slideshow_effect)) 		$data = array_merge($data, $slideshow_effect);
		if (is_array($advanced)) 				$data = array_merge($data, $advanced);

		return array_merge($data, $attributes);;
	}
	public function getJQquery(){
		if (Mage::getStoreConfigFlag('slideshow_cfg/advanced/include_jquery')){
			if (null == Mage::registry('vt.jquery')){
				Mage::register('vt.jquery', 1);
				return 'vt/slideshow/js/jquery.min.js';
			}
		}
		return;
	}
	public function getJQqueryNoconflict(){
		if (Mage::getStoreConfigFlag('slideshow_cfg/advanced/include_jquery')){
			if (null == Mage::registry('vt.jquerynoconflict')){
				Mage::register('vt.jquerynoconflict', 1);
				return 'vt/slideshow/js/vt.noconflict.js';
			}
		}
		return;
	}
	public function getJSSlideShow(){
		if (null == Mage::registry('vt.slideshow')){
			Mage::register('vt.slideshow', 1);
			return 'vt/slideshow/js/jquery.themepunch.revolution.min.js';
		}
		return;
	}
	public function getJSThemepunchPlugins(){
		if (null == Mage::registry('vt.themepunchplugins')){
			Mage::register('vt.themepunchplugins', 1);
			return 'vt/slideshow/js/jquery.themepunch.plugins.min.js';
		}
		return;
	}
}
?>
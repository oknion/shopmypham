<?php
class Vt_Quickview_Helper_Data extends Mage_Core_Helper_Abstract {
	public function getJQquery(){
		if (null == Mage::registry('vt.jquery')){
			Mage::register('vt.jquery', 1);
			return 'vt/quickview/js/jquery-1.7.2.min.js';
		}
		return;
	}
	public function getJQqueryNoconflict(){
		if (null == Mage::registry('vt.jquerynoconflict')){
			Mage::register('vt.jquerynoconflict', 1);
			return 'vt/quickview/js/vt.noconflict.js';
		}
		return;
	}
	public function getJSQuickview(){
		if (Mage::getStoreConfigFlag('quickview/general/enable')){
			if (null == Mage::registry('vt.quickview')){
				Mage::register('vt.quickview', 1);
				return 'vt/quickview/js/quickview.js';
			}
		}
		return;
	}
	public function getJSFancybox(){
		if (null == Mage::registry('vt.fancybox')){
			Mage::register('vt.fancybox', 1);
			return 'vt/quickview/js/jquery.fancybox-1.3.4.pack.js';
		}
		return;
	}
}
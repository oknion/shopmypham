<?php
class Vt_Ajaxfilter_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getJQquery(){
		if (Mage::getStoreConfigFlag('ajaxfilter_cfg/advanced/include_jquery') && Mage::getStoreConfigFlag('ajaxfilter_cfg/general/isenabled')){
			if (null == Mage::registry('vt.jquery')){
				Mage::register('vt.jquery', 1);
				return 'vt/ajaxfilter/js/jquery-1.7.2.min.js';
			}
		}
		return;
	}
	public function getJQqueryUI(){
		if (Mage::getStoreConfigFlag('ajaxfilter_cfg/advanced/include_jqueryui') && Mage::getStoreConfigFlag('ajaxfilter_cfg/general/isenabled')){
			if (null == Mage::registry('vt.jqueryui')){
				Mage::register('vt.jqueryui', 1);
				return 'vt/ajaxfilter/js/jquery-ui.min.js';
			}
		}
		return;
	}
	public function getJQqueryNoconflict(){
		if (Mage::getStoreConfigFlag('ajaxfilter_cfg/advanced/include_jquery')){
			if (null == Mage::registry('vt.jquerynoconflict')){
				Mage::register('vt.jquerynoconflict', 1);
				return 'vt/ajaxfilter/js/vt.noconflict.js';
			}
		}
		return;
	}

}

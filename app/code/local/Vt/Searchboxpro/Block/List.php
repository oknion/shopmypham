<?php
/*------------------------------------------------------------------------
 # VT Search Box Pro - Version 1.0
 # Copyright (c) 2013 vnthemepro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: vnthemepro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/

class Vt_Searchboxpro_Block_List extends Mage_Catalog_Block_Product_Abstract
{

	protected $_config = null;

	public function __construct($attributes = array()){
		parent::__construct();
		$this->_config = Mage::helper('searchboxpro/data')->get($attributes);
	}

	public function getConfig($name=null, $value=null){
		if (is_null($this->_config)){
			$this->_config = Mage::helper('searchboxpro/data')->get(null);
		}
		if (!is_null($name) && !empty($name)){
			$valueRet = isset($this->_config[$name]) ? $this->_config[$name] : $value;
			return $valueRet;
		}
		return $this->_config;
	}

	public function setConfig($name, $value=null){
		if (is_null($this->_config)) $this->getConfig();
		if (is_array($name)){
			Mage::log($name);
			$this->_config = array_merge($this->_config, $name);
			return;
		}
		if (!empty($name)){
			$this->_config[$name] = $value;
		}
		return true;
	}

	public function getConfigObject(){
        return (object)$this->getConfig();
	}

	protected function _toHtml(){
		if(!$this->getConfig('isenabled')) return;
		$template_file = 'vt/searchboxpro/default.phtml';
		$this->setTemplate($template_file);
		return parent::_toHtml();
	}

	
	
	public function getCategories(){
        $category = Mage::getModel('searchboxpro/system_config_source_listCategory');
		$cat_list = $category->toOptionArray(true);
        return $cat_list;	
	}

}




<?php
/*------------------------------------------------------------------------
# SM Lovefashion - Version 1.0.0
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\Lovefashion\Helper;
use Magento\Store\Model\StoreManagerInterface;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

	public function __construct(
		StoreManagerInterface $storeManagerInterface,
        \Magento\Framework\App\Helper\Context $context
    ) {
		$this->_storeManager = $storeManagerInterface;
        parent::__construct($context);
    }
	
	public function getStoreId(){
		return $this->_storeManager->getStore()->getId();
	}
	
	public function getUrl(){
		return $this->_storeManager->getStore()->getBaseUrl();
	}
	
	
	public function getGeneral($name)
	{
        return $this->scopeConfig->getValue(
            'lovefashion/general/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}
	
	public function getThemeLayout($name)
	{
        return $this->scopeConfig->getValue(
            'lovefashion/theme_layout/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}
	
	public function getProductListing($name)
	{
        return $this->scopeConfig->getValue(
            'lovefashion/product_listing/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}	
	
	public function getProductDetail($name)
	{
        return $this->scopeConfig->getValue(
            'lovefashion/product_detail/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}

	public function getSocial($name)
	{
        return $this->scopeConfig->getValue(
            'lovefashion/socials/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}
	
	public function getAdvanced($name)
	{
        return $this->scopeConfig->getValue(
            'lovefashion/advanced/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}
	
}
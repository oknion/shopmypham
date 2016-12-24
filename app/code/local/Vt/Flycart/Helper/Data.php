<?php
/**
 *Flycart Extension
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.store.vt.com/license.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to admin@vt.com so we can mail you a copy immediately.
 *
 * @category   Magento Extensions
 * @package    Vt_Flycart
 * @author     Vt <sales@vt.com>
 * @copyright  2007-2011 Vt
 * @license    http://www.store.vt.com/license.txt
 * @version    1.0.1
 * @link       http://www.store.vt.com
 */

class Vt_Flycart_Helper_Data extends Mage_Core_Helper_Abstract{
    
    public function getConfigData($node){
		return Mage::getStoreConfig('flycart/'.$node);
	}
	
	public function getCustomCartBlock($cartTotal, $qty)
	{
		$html = $this->getConfigData('custom_cart/html');
		$html = str_ireplace("{cart_total}", Mage::helper('core')->formatPrice($cartTotal, false), $html);
		$html = str_ireplace("{cart_qty}", $qty, $html);
		return $html;
	}
	
	public function isCartPage(){
	    return ((Mage::app()->getFrontController()->getRequest()->getRequestedRouteName() == 'checkout') &&
	           (Mage::app()->getFrontController()->getRequest()->getRequestedControllerName() == 'cart') &&
	           (Mage::app()->getFrontController()->getRequest()->getRequestedActionName() == 'index'));
                   
	}
	
    public function isChangedAttributeCart(){	        
	    return ((Mage::app()->getFrontController()->getRequest()->getRequestedRouteName() == 'flycart') &&
	           (Mage::app()->getFrontController()->getRequest()->getRequestedControllerName() == 'index') &&
	           (Mage::app()->getFrontController()->getRequest()->getRequestedActionName() == 'updateattqty'));
                   
	}
	
    public function isChangedQtyCart(){	        
	    return  ((Mage::app()->getFrontController()->getRequest()->getRequestedRouteName() == 'flycart') &&
	            (Mage::app()->getFrontController()->getRequest()->getRequestedControllerName() == 'index') &&
	            (Mage::app()->getFrontController()->getRequest()->getRequestedActionName() == 'updatecartqty'));
                   
	}
	
	public function isActivated(){
	    return Mage::getStoreConfig('flycart/general/enabled');
	}

    public function isCrosssellAdd(){
	    return ((Mage::app()->getFrontController()->getRequest()->getParam('flycart_crosssell') == 1) ||
	            (Mage::app()->getFrontController()->getRequest()->getParam('flycart_add') == 1));
	}
	
    public function getFlycartProductData($product){
        
         if ($product->getStockItem()->getManageStock() && 
             !$product->getStockItem()->getBackorders()){
             $min_qty = $product->getStockItem()->getMinSaleQty();
             if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE){                 
                 $max_qty = min(array($product->getStockItem()->getMaxSaleQty(), $product->getStockItem()->getQty()));
             }else{
                 $max_qty = $product->getStockItem()->getMaxSaleQty();
             }    
    
             $quote = Mage::getSingleton('checkout/session')->getQuote();
             $item = $quote->getItemByProduct($product);
             if ($item && $qty = $item->getQty()){
                 $max_qty = $max_qty - $qty;  
                 if ($min_qty > $max_qty) $min_qty = $max_qty;
             }
         }else{
             $min_qty = $product->getStockItem()->getMinSaleQty();
             $max_qty = $product->getStockItem()->getMaxSaleQty();
         }
         
         return array('min_qty' => intval($min_qty),
                      'max_qty' => intval($max_qty),
                      'name' => $product->getName(),
                      'is_simple' => ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE ? 1 : 0),
                      'is_grouped' => ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED ? 1 : 0),
         			  'product_url' => $product->getProductUrl()    
                 );
    }
    
    public function isOtherVersion($major, $minor, $revision = 0)
    {
        $version_info = Mage::getVersion();
        $version_info = explode('.', $version_info);
        
        if ($version_info[0] > $major) {
           return true;
        } elseif ($version_info[0] == $major) {
            if ($version_info[1] > $minor) {
                return true; 
            } elseif ($version_info[1] == $minor) {
                if ($version_info[2] >= $revision) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }        
        } else { 
           return false;
        }                                
    }
    
    public function formatColor($value){
	    if ($value = preg_replace('/[^a-zA-Z0-9\s]/', '', $value)){
	       $value = '#' . $value; 	        
	    }
	    return $value;
	}
	
}

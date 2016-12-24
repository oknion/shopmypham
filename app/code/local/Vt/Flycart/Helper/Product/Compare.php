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

class Vt_Flycart_Helper_Product_Compare extends Mage_Catalog_Helper_Product_Compare
{
    public function getRemoveUrl($item)
    {
        $is_compare_page = ((Mage::app()->getFrontController()->getRequest()->getRequestedRouteName() == 'catalog') &&
	             (Mage::app()->getFrontController()->getRequest()->getRequestedControllerName() == 'product_compare') &&
	             (Mage::app()->getFrontController()->getRequest()->getRequestedActionName() == 'index'));
        if (Mage::helper('flycart')->isActivated() && !$is_compare_page){
             $params = array(
                'product'=> $item->getId(),
                'isAjax' => 1,
                'flycart_remove_compare' => 1 
            ); 
            return 'javascript:ajaxcartConfig.deleteCompareItem(\'' . 
                $this->_getUrl('catalog/product_compare/remove', $params) . '\')';
        }else{ 
            return parent::getRemoveUrl($item);
        }
    }
    
	public function getClearListUrl()
    {
    	if (Mage::helper('flycart')->isActivated() && 
    	    Mage::app()->getFrontController()->getRequest()->getParam('flycart_compare_add') == 1){
        	$params = array();
        	return $this->_getUrl('catalog/product_compare/clear', $params);
    	}else{
    		return parent::getClearListUrl();
    	}	
    }
    
}

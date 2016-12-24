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
	
class Vt_Flycart_Block_Initial extends Mage_Core_Block_Template
{

    protected $_config = null; 
    protected $_qty_template = null;
    protected $_qty_cart_template = null;
    protected $_qty_product_template = null;
        
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('vt/flycart/initial.phtml');                               
    }
    
    public function _prepareLayout()
    {
        $helper = Mage::helper('flycart');
        $root = $this->getLayout()->getBlock('root');
        if ($helper->isActivated() && $root){
            if ($helper->getConfigData('qtyupdate/product_page'))
                $root->addBodyClass('flycart-product');
            if ($helper->getConfigData('qtyupdate/cart_block'))        
                $root->addBodyClass('flycart-block-cart');
            if ($helper->getConfigData('qtyupdate/cart_page'))        
                $root->addBodyClass('flycart-my-cart');
            if ($helper->getConfigData('qtyupdate/crosssell'))        
                $root->addBodyClass('flycart-crosssell');
            $window_popup_styles = $this->getLayout()->createBlock('core/template', 'window_popup_styles')->setTemplate('vt/flycart/header/styles.phtml');
	        $this->getLayout()->getBlock('head')->setChild('window_popup_styles', $window_popup_styles);     
        }    
        parent::_prepareLayout();
    }
    
    public function getConfig()
    {
         if (!$this->_config)
         {             
             $this->_config = array();
             $helper = Mage::helper('flycart');
             $this->_config['enable'] = ($helper->isActivated() ? 1 : 0);
             
             if($loadingImage = $helper->getConfigData('ajaxloader/loadingimage')) 
	            $this->_config['loadingImage'] = Mage::getBaseUrl('media') . 'flycart/config/' . $loadingImage;	
	         else
	            $this->_config['loadingImage'] = $this->getSkinUrl('vt/flycart/images/loader.gif');

	         $this->_config['loadingAlign'] = $helper->getConfigData('ajaxloader/loadingimagealign');
			 $this->_config['custom_cart'] = $helper->getConfigData('custom_cart/dom') ? $helper->getConfigData('custom_cart/dom'):false;
			 $this->_config['visible_top_cart'] = $helper->getConfigData('general/visible_top_cart');
	         $text = trim($helper->getConfigData('ajaxloader/text')) ? trim($helper->getConfigData('ajaxloader/text')) : $this->__('Processing, please wait...');
		     $text = addslashes(str_replace("\n", "<br/>", str_replace("\r", '', $text)));		     
		     $this->_config['loadingText'] = $text; 
		     $this->_config['show_confirmation'] = $helper->getConfigData('general/show_confirmation');
		     $this->_config['updateqty'] = $this->getUrl('flycart/index/updateQty');		     
		     $this->_config['updatecartqty'] = $this->getUrl('flycart/index/updateCartQty');
		     $this->_config['updateattqty'] = $this->getUrl('flycart/index/updateAttQty');
		     $this->_config['updateproductqty'] = $this->getUrl('flycart/index/updateProductQty');
		     $this->_config['qty_update_cart_page'] = $helper->getConfigData('qtyupdate/cart_page');
			 $this->_config['qty_update_category_page'] = $helper->getConfigData('qtyupdate/category_page');
		     $this->_config['qty_update_product_page'] = (Mage::registry('current_product') && $helper->getConfigData('qtyupdate/product_page') ? 1 : 0);
		     $this->_config['qty_update_crosssell'] = $helper->getConfigData('qtyupdate/crosssell');
		     $this->_config['show_window'] = $helper->getConfigData('confirm_window/show_window');
		     $this->_config['auto_hide_window'] = $helper->getConfigData('confirm_window/auto_hide_window');
		     $this->_config['redirect_to'] = $helper->getConfigData('confirm_window/redirect_to');
		     $this->_config['effect'] = $helper->getConfigData('general/effect');
		     $this->_config['cart_button_color'] = $helper->getConfigData('confirm_window/cart_button_color');
			 $this->_config['window_width'] = $helper->getConfigData('confirm_window/width');
		     $this->_config['related_product_url'] = $this->getUrl('flycart/index/relatedProduct');
		     $this->_config['name_url_encoded'] = Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED;
         }
         return Mage::helper('core')->jsonEncode($this->_config);          
    }
    
    public function getQtyTemplate()
    {
        if (!$this->_qty_template)
        {
            $template = $this->getLayout()->createBlock('core/template', 'flycart.qty.template');
			$template->setTemplate('vt/flycart/qty.phtml');
            $this->_qty_template = $template->toHtml();
        }
        return Mage::helper('core')->jsonEncode($this->_qty_template);                  
    }
	
    public function getQtyProductTemplate()
    {
		$helper = Mage::helper('flycart');
        if (!$this->_qty_product_template)
        {
            $template = $this->getLayout()->createBlock('core/template', 'flycart.qty.product.template'); 
            $template->setTemplate('vt/flycart/product/qty.phtml');           
            $this->_qty_product_template = $template->toHtml();
         }
         return Mage::helper('core')->jsonEncode($this->_qty_product_template);                  
    }
	
    public function getQtyCartTemplate()
    {
		$helper = Mage::helper('flycart');
        if (!$this->_qty_cart_template)
        {
            $template = $this->getLayout()->createBlock('core/template', 'flycart.qty.cart.template');
            if ($helper->getConfigData('qtyupdate/cart_page') || $helper->getConfigData('qtyupdate/cart_block'))
            { 
                $template->setTemplate('vt/flycart/cart/qty.phtml');         
            } 
            $this->_qty_cart_template = $template->toHtml();
        }
        return Mage::helper('core')->jsonEncode($this->_qty_cart_template);                  
    }
    
}
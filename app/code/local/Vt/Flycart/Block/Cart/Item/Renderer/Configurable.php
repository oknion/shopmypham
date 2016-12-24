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

class Vt_Flycart_Block_Cart_Item_Renderer_Configurable extends Mage_Checkout_Block_Cart_Item_Renderer_Configurable
{
    
    public function getQty()
    {                       
        $rendered = $this->getRenderedBlock();
        $helper = Mage::helper('flycart');
        if ($rendered && ($rendered->getNameInLayout() == 'cart_sidebar' || $rendered->getName() == 'cart_sidebar') && $helper->isActivated() && Mage::getStoreConfig('flycart/qtyupdate/cart_block'))
        {                    
            $template = $this->getLayout()->createBlock('core/template', 'flycart.sidebar.qty.template');
			$template->setTemplate('vt/flycart/sidebar/qty.phtml');
			$template->setItem($this->getItem());                        
            return $template->toHtml();
        }
         else 
		{
            return parent::getQty(); 
		}
    }
    
    public function getDeleteUrl()
    {
        $helper = Mage::helper('flycart');
        $rendered = $this->getRenderedBlock();
        $is_cart = ($helper->isCartPage() || $helper->isChangedAttributeCart() || 
                    $helper->isChangedQtyCart() || $helper->isCrosssellAdd());
        if ($helper->isActivated() &&
            (((Mage::getStoreConfig('flycart/qtyupdate/cart_block') || Mage::getStoreConfig('flycart/general/visible_top_cart')) && $rendered) ||
             (Mage::getStoreConfig('flycart/qtyupdate/cart_page') && $is_cart) )){    
            return 'javascript:ajaxcartConfig.deleteItem(\'' . $this->getUrl(
                'checkout/cart/delete',
                array(
                    'id'=>$this->getItem()->getId()
                )
            ) . '\')';
        }    
        else{
            return parent::getDeleteUrl();
        }    
    }
    
    public function getFormatedOptionValue($optionValue)
    {
        $helper = Mage::helper('flycart');
        
        if ($helper->isActivated()){
            if (!$helper->isOtherVersion(1, 5) &&
                 Mage::getStoreConfig('flycart/qtyupdate/cart_page') &&
                 (Mage::helper('flycart')->isCartPage() || 
                  Mage::helper('flycart')->isChangedAttributeCart() ||
                  Mage::helper('flycart')->isChangedQtyCart())){
                $optionInfo = array();
				
                if (is_array($optionValue)) {
                    if (isset($optionValue['option_id'])) {
                        $optionInfo = $optionValue;
                        if (isset($optionInfo['value'])) {
                            $optionValue = $optionInfo['value'];
                        }
                    } elseif (isset($optionValue['value'])) {
                        $optionValue = $optionValue['value'];
                    }
                }
        
                if (isset($optionInfo['custom_view']) && $optionInfo['custom_view']) {
                    $_default = array('value' => $optionValue);
                    if (isset($optionInfo['option_type'])) {
                        try {
                            $group = Mage::getModel('catalog/product_option')->groupFactory($optionInfo['option_type']);
                            return array('value' => $group->getCustomizedView($optionInfo));
                        } catch (Exception $e) {
                            return $_default;
                        }
                    }
                    return $_default;
                }
        
                $result = array();
                if (is_array($optionValue)) {
                    $_truncatedValue = implode("\n", $optionValue);
                    $_truncatedValue = nl2br($_truncatedValue);
                    return array('value' => $_truncatedValue);
                } else {
                    $_truncatedValue = nl2br($optionValue);
                }
        
                $result = array('value' => $_truncatedValue);

                return $result;
                                
            }
            else {
                return parent::getFormatedOptionValue($optionValue);
            }             
        }
        else {
            return parent::getFormatedOptionValue($optionValue);
        }
        
    }
    
}

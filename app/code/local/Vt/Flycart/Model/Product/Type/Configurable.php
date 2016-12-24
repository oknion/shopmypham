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

class Vt_Flycart_Model_Product_Type_Configurable extends Mage_Catalog_Model_Product_Type_Configurable
{
    public function getAttributeActiveOptions($product, $attribute){
        $result = array();
        $allProducts = $this->getUsedProducts(null, $product);
        foreach ($allProducts as $_product){
            if ($_product->isSaleable()) {
                 $result[] = $_product->getData($attribute->getProductAttribute()->getAttributeCode());
            }     
        }
        return $result;
    } 
        
    public function getSelectedAttributesInfo($product = null)
    {        
        $attributes = array();
        Varien_Profiler::start('CONFIGURABLE:'.__METHOD__);
        if ($attributesOption = $this->getProduct($product)->getCustomOption('attributes')) {
            $data = unserialize($attributesOption->getValue());
            $this->getUsedProductAttributeIds($product);

            $usedAttributes = $this->getProduct($product)->getData($this->_usedAttributes);

            foreach ($data as $attributeId => $attributeValue) {
                if (isset($usedAttributes[$attributeId])) {
                    $attribute = $usedAttributes[$attributeId];
                    $label = $attribute->getLabel();
                    $value = $attribute->getProductAttribute();
                    if ($value->getSourceModel()) {
                        if (Mage::helper('flycart')->isActivated() &&
                            (Mage::getStoreConfig('flycart/qtyupdate/cart_page') ||
                             Mage::getStoreConfig('flycart/qtyupdate/cart_block')) )
                        {                        
                            $attribute_values = $attribute->getPrices() ? $attribute->getPrices() : array();                        
                            foreach ($attribute_values as $_k => $_v){
                                if (in_array($_v['value_index'], $this->getAttributeActiveOptions($product, $attribute))){
                                    $attribute_values[$_k]['value'] = $_v['value_index'];
                                }else{
                                    unset($attribute_values[$_k]);
                                }
                            } 
                            $select = Mage::getSingleton('core/layout')->createBlock('core/html_select')
                                        ->setClass('cart_attribute_' . $attributeId)
                                        ->setId('cart_attribute_' . $product->getId() .'_'. $attributeId .'_'. $attributeValue)
                                        ->setName('cart_attribute_' . $product->getId() .'_'. $attributeId .'_'. $attributeValue)
                                        ->setTitle($label)
                                        ->setExtraParams('onchange="ajaxcartConfig.attributeCartUpdate(this,'.$product->getId().')"')
                                        ->setValue($attributeValue)
                                        ->setOptions($attribute_values);
                            $value = $select->getHtml();
                        }else 
                            $value = $value->getSource()->getOptionText($attributeValue);                                
                    }
                    else {
                        $value = '';
                    }

                    $attributes[] = array('label'=>$label, 'value'=>$value);
                }
            }
        }
        Varien_Profiler::stop('CONFIGURABLE:'.__METHOD__);
        return $attributes;
    }

}

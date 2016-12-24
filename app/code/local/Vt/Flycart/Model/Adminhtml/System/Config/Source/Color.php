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
	
class Vt_Flycart_Model_Adminhtml_System_Config_Source_Color
{
  
    public function toOptionArray()
    {
        $helper = Mage::helper('flycart');
        
        return array(            
            array('value' => 'black', 'label'=>$helper->__('Black')),
            array('value' => 'blue', 'label'=>$helper->__('Blue')),
            array('value' => 'brown', 'label'=>$helper->__('Brown')),
            array('value' => 'gray', 'label'=>$helper->__('Gray')),
            array('value' => 'green', 'label'=>$helper->__('Green')),
            array('value' => 'light-blue', 'label'=>$helper->__('Light-Blue')),
            array('value' => 'light-green', 'label'=>$helper->__('Light-Green')),
            array('value' => 'orange', 'label'=>$helper->__('Orange')),
            array('value' => 'red', 'label'=>$helper->__('Red')),
            array('value' => 'pink', 'label'=>$helper->__('Pink')),
            array('value' => 'violet', 'label'=>$helper->__('Violet')),
            array('value' => 'yellow', 'label'=>$helper->__('Yellow')),
        );
    }

}
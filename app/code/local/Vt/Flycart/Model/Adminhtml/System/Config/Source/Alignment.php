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
	
class Vt_Flycart_Model_Adminhtml_System_Config_Source_Alignment
{

    public function toOptionArray(){
    	
    	$helper = Mage::helper('flycart');
    	
        return array(
            array('value'=>'left', 'label' => $helper->__('Left')),
        	array('value'=>'right', 'label' => $helper->__('Right')),
        	array('value'=>'top', 'label' => $helper->__('Top')),
        	array('value'=>'bottom', 'label' => $helper->__('Bottom')),
        );
    	
    }
    
    public function toOptionHash(){
    	
    	$helper = Mage::helper('flycart');
    	
        return array(
            'left' => $helper->__('Left'),
            'right' => $helper->__('Right'),
            'top' => $helper->__('Top'),
        	'bottom' => $helper->__('Bottom'),
        );
    }

}
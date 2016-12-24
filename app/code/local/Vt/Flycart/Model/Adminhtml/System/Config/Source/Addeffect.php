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
	
class Vt_Flycart_Model_Adminhtml_System_Config_Source_Addeffect
{

    const NO = 0;
    const AJAX_LOADING = 1;
    const SLIDE = 2;
    
    public function toOptionArray(){
    	
    	$helper = Mage::helper('flycart');
    	
        return array(
            array('value'=>self::NO, 'label' => $helper->__('No')),
        	array('value'=>self::AJAX_LOADING, 'label' => $helper->__('Ajax Loading')),
        	array('value'=>self::SLIDE, 'label' => $helper->__('Slide')),        	        	
        );
    	
    }
        
    public function toOptionHash(){
    	
    	$helper = Mage::helper('flycart');
    	
        return array(
            self::NO => $helper->__('No'),
            self::AJAX_WINDOW => $helper->__('Ajax Window'),
            self::SLIDE => $helper->__('Slide'),        	
        );
    }

}
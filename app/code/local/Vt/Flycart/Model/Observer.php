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
	
class Vt_Flycart_Model_Observer{
	
	public function noCookies($event){
	    $helper = Mage::helper('flycart');	    
	    if ($helper->isActivated() && $event->getEvent()->getAction()->getRequest()->getParam('flycart_add')){	        	        
	        $result = array();
	        $result['success'] = false;
	        $result['error'] = true;
	        $result['message'] = $helper->__('Please enable cookies in your web browser to continue.');            
	        $result['redirect'] = Mage::getUrl('core/index/noCookies');
	        echo Mage::helper('core')->jsonEncode($result); exit(); 
	    }
	}
	
    public function addToCart($event)
    {        
        $request = $event->getRequest();
        if ($request->getParam('flycart_add') == 1)
		{
		    $result = array();
	        $result['success'] = true;
	        
	        $result['cart'] = $this->getCartSidebar();                                                                                    
            $result['top_links'] = $this->getTopLinks();
            $result['top_cart'] = $this->getCartTopbar();
			$result['custom_cart'] = $this->getCustomCart();
            $result['prod_name'] = $event->getProduct()->getName();                        	        
            $result['qty'] = $event->getRequest()->getParam('qty');
            $result['product_id'] = $event->getProduct()->getId();
            
            $result['base_cart'] = $this->getBaseCartItems();
                        
            $layout = Mage::getSingleton('core/layout');            
            $result['total'] = $layout->createBlock('checkout/cart_totals', 'checkout.cart.totals')
                                     ->setTemplate('checkout/cart/totals.phtml')  
                                     ->renderView();
                                     
            $result['shipping'] = $layout->createBlock('checkout/cart_shipping', 'checkout.cart.shipping')
                                     ->setTemplate('checkout/cart/shipping.phtml')  
                                     ->renderView();
                                     
            if (($request->getParam('flycart_crosssell') == 1) && Mage::getStoreConfig('flycart/qtyupdate/crosssell')){
            	$result['crosssell'] = $layout->createBlock('checkout/cart_crosssell', 'checkout.cart.crosssell')
	                                     ->setTemplate('checkout/cart/crosssell.phtml')  
	                                     ->renderView();            	
            }                         
                        
            Mage::getSingleton('checkout/session')->setNoCartRedirect(true);  
            Mage::app()->getFrontController()->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));           
		}
	}
	
    public function getBaseCartItems()
	{
	    $item_html = '';
	    $layout = Mage::getSingleton('core/layout');
        $cart = $layout->createBlock('checkout/cart', 'checkout.cart')    	                                    	                                
                                ->addItemRender('simple', 'checkout/cart_item_renderer', 'checkout/cart/item/default.phtml')
                                ->addItemRender('configurable', 'checkout/cart_item_renderer_configurable', 'checkout/cart/item/default.phtml')
                                ->addItemRender('grouped', 'checkout/cart_item_renderer_grouped', 'checkout/cart/item/default.phtml')
                                ->addItemRender('bundle', 'bundle/checkout_cart_item_renderer', 'checkout/cart/item/default.phtml');                                        
        foreach ($cart->getItems() as $_item)
        {                                
            $item_html .= $cart->getItemHtml($_item);                        
        }   
        
        return $item_html;
	}
	
    public function getWishlistSidebar()
	{	    
	    $layout = Mage::getSingleton('core/layout');
        return  $layout->createBlock('wishlist/customer_sidebar', 'wishlist_sidebar')    	                                    	                                
                                ->setTemplate('wishlist/sidebar.phtml')
                                ->renderView();                                        
	}
	
    public function updateItemOptions($event)
    {        
        $request = $event->getRequest();
        if ($request->getParam('flycart_add') == 1)
		{			
		    $result = array();
		    
		    $id = $request->getParam('id');		    		    
		    if ($id != $event->getItem()->getId()){
		    	$result['success'] = false;
		    	$result['redirect'] = Mage::getUrl('checkout/cart/configure', array('id' => $event->getItem()->getId())); 
		    }else{		    
		        $result['success'] = true;	        	        
		        $result['cart'] = $this->getCartSidebar();                                                                                     
	            $result['top_links'] = $this->getTopLinks();            
	            $result['top_cart'] = $this->getCartTopbar();   
				$result['custom_cart'] = $this->getCustomCart();				
	            $result['prod_name'] = $event->getItem()->getProduct()->getName();                        	        
	            $result['qty'] = $event->getRequest()->getParam('qty');
	            $result['product_id'] = $event->getItem()->getProduct()->getId();
		    }
            
            Mage::getSingleton('checkout/session')->setNoCartRedirect(true);  
            Mage::app()->getFrontController()->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            Mage::getSingleton('checkout/session')->setData('flycart_result', Mage::helper('core')->jsonEncode($result));
            Mage::getSingleton('checkout/session')->setData('flycart_updateitem', true);
		}
	}
	
	public function getCustomCart()
	{
		$qty 		= Mage::getSingleton('checkout/cart')->getItemsQty();
		$custom_cart = Mage::helper('flycart')->getCustomCartBlock(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal(), $qty);
		return $custom_cart;
	}
	
	public function getCartSidebar()
	{
	    $layout = Mage::getSingleton('core/layout');
	    
	    return $layout->createBlock('checkout/cart_sidebar', 'cart_sidebar')
	                                ->setTemplate('checkout/cart/sidebar.phtml')
                                    ->addItemRender('simple', 'checkout/cart_item_renderer', 'checkout/cart/sidebar/default.phtml')
                                    ->addItemRender('configurable', 'checkout/cart_item_renderer_configurable', 'checkout/cart/sidebar/default.phtml')
                                    ->addItemRender('grouped', 'checkout/cart_item_renderer_grouped', 'checkout/cart/sidebar/default.phtml')
                                    ->addItemRender('bundle', 'bundle/checkout_cart_item_renderer', 'checkout/cart/sidebar/default.phtml')                                    
                                    ->renderView();
	}
	
	public function getCartTopbar(){
		
	    $layout = Mage::getSingleton('core/layout');
		

		if(version_compare(Mage::getVersion(), '1.8.1') > 0) {
			$html = $layout->createBlock('checkout/cart_minicart', 'cart_top')->setTemplate('checkout/cart/minicart.phtml');
			$child = $layout->createBlock('checkout/cart_sidebar', 'cart_top_item')
											->setTemplate('checkout/cart/minicart/items.phtml')
											->addItemRender('simple', 'checkout/cart_item_renderer', 'checkout/cart/minicart/default.phtml')
											->addItemRender('configurable', 'checkout/cart_item_renderer_configurable', 'checkout/cart/minicart/default.phtml')
											->addItemRender('grouped', 'checkout/cart_item_renderer_grouped', 'checkout/cart/minicart/default.phtml')
											->addItemRender('bundle', 'bundle/checkout_cart_item_renderer', 'checkout/cart/minicart/default.phtml')                                    
											;
			return $html->setChild('minicart_content', $child)->renderView();
		} else {
			if(Mage::getStoreConfig('flycart/general/visible_top_cart')) {
				return $layout->createBlock('checkout/cart_sidebar', 'cart_top')
											->setTemplate('vt/flycart/cart/link.phtml')
											->addItemRender('simple', 'checkout/cart_item_renderer', 'checkout/cart/sidebar/default.phtml')
											->addItemRender('configurable', 'checkout/cart_item_renderer_configurable', 'checkout/cart/sidebar/default.phtml')
											->addItemRender('grouped', 'checkout/cart_item_renderer_grouped', 'checkout/cart/sidebar/default.phtml')
											->addItemRender('bundle', 'bundle/checkout_cart_item_renderer', 'checkout/cart/sidebar/default.phtml')                                    
											->renderView();
			} else {
				$qty 		= Mage::getSingleton('checkout/cart')->getItemsQty();
				$cart_link = Mage::helper('flycart')->__('My Cart');
				if($qty == 1){$cart_link = Mage::helper('flycart')->__('My Cart (%s item)',$qty);}
				else if($qty > 1) {$cart_link = Mage::helper('flycart')->__('My Cart (%s items)',$qty);}
				return $cart_link;
			}
			
		}
	}
	
	public function getTopLinks()
	{
	    $layout = Mage::getSingleton('core/layout');
	    
	    $top_links = $layout->createBlock('page/template_links', 'gcp.top.links');
        
	    $checkout_cart_link = $layout->createBlock('checkout/links', 'checkout_cart_link');
        $wishlist_link = $layout->createBlock('wishlist/links', 'wishlist_link');
                            
        $top_links->setChild('checkout_cart_link', $checkout_cart_link);
        $top_links->setChild('wishlist_link', $wishlist_link);        
        
        if (method_exists($top_links, 'addLinkBlock')){
            $top_links->addLinkBlock('checkout_cart_link');
            $top_links->addLinkBlock('wishlist_link');
        }
        
        $checkout_cart_link->addCartLink();
                     
        return $top_links->renderView();
	}
	
	public function showConfigurableParams($event)
	{
	    $request = $event->getControllerAction()->getRequest();
	    if ($request->getParam('flycart_show_configurable') == 1)
	    {
	        $form = Mage::getBlockSingleton('flycart/product_configurable_form');
	        $product = Mage::registry('current_product');
	        $form->setProduct($product);
	         
	        $layout = Mage::getSingleton('core/layout');	        
	        $product_options = $layout->getBlock('product.info.options.wrapper');
	        $product_options_bottom = $layout->getBlock('product.info.options.wrapper.bottom');
	        	        
	        $form->setChild('flycart_configurable_options', $product_options);
	        $form->setChild('flycart_configurable_options_bottom', $product_options_bottom);
	        
	        if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE){
	            $product_info_bundle = $layout->getBlock('product.info.bundle');
	            $form->setChild('flycart_product_info_bundle', $product_info_bundle);
	        }
	        
	        $result = array();
	        $result['success'] = true;
	        $result['form'] = $form->renderView();
	        $result['qty'] = $request->getParam('qty');
	        
	        $event->getControllerAction()->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	    }	    
	}
	
	public function addProductWithError($event){
	    
	    $request = $event->getControllerAction()->getRequest();
	    $product = Mage::getModel('catalog/product')->load($request->getParam('id'));

	    if ($product && in_array($product->getTypeId(), array(Mage_Catalog_Model_Product_Type::TYPE_GROUPED, Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE))){
	    
    	    if(Mage::helper('flycart')->isActivated() && Mage::getStoreConfig('flycart/qtyupdate/product_page')){	        	                 
    	                 
        	    $messages = Mage::getSingleton('checkout/session')->getMessages(false)->getItems(Mage_Core_Model_Message::ERROR);
        	    
        	    $message_text = '';
        	    
        	    foreach ( $messages as $message) {
        	        $message_text .= str_replace('""', '"'.$product->getName().'"', $message->getText());
        	    }
      	    
        	    if ($message_text){
        	        Mage::getSingleton('checkout/session')->getMessages(true);
        	        $result = array();
        	        $result['success'] = false;
        	        $result['message'] = $message_text;
        	        $event->getControllerAction()->getResponse()
        	                ->setBody(Mage::helper('core')->jsonEncode($result));
        	        $event->getControllerAction()->setFlag('', 'no-renderLayout', true);                	        
        	    }        	    	    
    	    }
	    }
	}
	
	
	public function showGroupedParams($event){
	    $request = $event->getEvent()->getControllerAction()->getRequest();
	    if (($request->getParam('flycart_add') == 1) && ($flycart_item = $request->getParam('flycart_item'))){
	        $product = Mage::getModel('catalog/product')->load($flycart_item);
	        if ($product->isGrouped()){
	            $result = array();
	            $result['success'] = true;
	            $result['is_grouped'] = true;
	            $result['deals_id'] = $request->getParam('deals_id');
	            
	            $form = Mage::getBlockSingleton('flycart/product_grouped_form');
	            $form->setProduct($product);
	            $form->setProductId($product->getId());
	            
	            $layout = Mage::getSingleton('core/layout');
	            
	            $product_info_grouped = $layout->createBlock('catalog/product_view_type_grouped', 'product.info.grouped')
	                                           ->setTemplate('catalog/product/view/type/grouped.phtml');
	            $product_info_grouped_extra = $layout->createBlock('core/text_list', 'product.info.grouped.extra');
	            $product_info_grouped->setChild('product_type_data_extra', $product_info_grouped_extra);                               	            
	            $form->setChild('product_type_data', $product_info_grouped);
	            
    	        $product_options_bottom = $layout->createBlock('catalog/product_view', 'product.info.options.wrapper.bottom')
	                                             ->setTemplate('catalog/product/view/options/wrapper/bottom.phtml');

	            $product_tierprice = $layout->createBlock('catalog/product_view', 'product.tierprices')
	                                        ->setTemplate('catalog/product/view/tierprices.phtml');                                 
	            $product_options_bottom->insert('product.tierprices');
	            $clone_prices = $layout->createBlock('catalog/product_view', 'product.clone_prices')
	                                   ->setTemplate('catalog/product/view/price_clone.phtml');
	            $product_options_bottom->append('prices', $clone_prices);

	            $info_addto = $layout->createBlock('catalog/product_view', 'product.info.addto')
	                                 ->setTemplate('catalog/product/view/addto.phtml');
	            $product_options_bottom->append('product.info.addto');
	            $info_addtocart = $layout->createBlock('catalog/product_view', 'product.info.addtocart')
	                                     ->setTemplate('catalog/product/view/addtocart.phtml');
	            $product_options_bottom->append('product.info.addtocart');                     

    	        $form->setChild('product_options_wrapper_bottom', $product_options_bottom);
	            
	            $result['form'] = $form->renderView();
	            $result['qty'] = $request->getParam('qty');
	            
	            $event->getEvent()->getControllerAction()->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
	            $event->getEvent()->getControllerAction()->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	        }
	        elseif ($product->isConfigurable()){
	            $result = array();
	            $result['success'] = true;
	            $result['is_configurable'] = true;
	            $result['deals_id'] = $request->getParam('deals_id');
	            
	            $additional = array();
	            $additional['_query']['options'] = 'cart';
	            $additional['_query']['flycart_item'] = $product->getId();	            
	             
	            $result['url'] = $product->getUrlModel()->getUrl($product, $additional);
	            $result['product_id'] = $product->getId();
	            $result['qty'] = $request->getParam('qty');
	            
	            $event->getEvent()->getControllerAction()->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
	            $event->getEvent()->getControllerAction()->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	        }
	    }
	}
	
	public function deleteCartItem($event){
	    $request = $event->getEvent()->getControllerAction()->getRequest();
	    if (($request->getParam('flycart_sidebar_delete') == 1 || $request->getParam('flycart_cart_delete') == 1) && ($id = $request->getParam('id'))){
	        
	        $helper = Mage::helper('flycart');
	        $result = array();
	        $result['error'] = false;
	        
	        $cart = Mage::getSingleton('checkout/cart');
	        $item = $cart->getQuote()->getItemById($id);
			if($item) {
				$product = Mage::getModel('catalog/product')->load($item->getProductId());
				
				try {
					Mage::getSingleton('checkout/cart')->removeItem($id)->save();
				} catch (Exception $e) {                
					$result['error'] = true;
					$result['message'] = $helper->__('Cannot remove the item.');
				}
				
				$layout = Mage::getSingleton('core/layout');
				
				if (!$result['error'] && $request->getParam('flycart_sidebar_delete') == 1){                                	        	        
					$result['cart'] = $layout->createBlock('checkout/cart_sidebar', 'cart_sidebar')
											->setTemplate('checkout/cart/sidebar.phtml')
											->addItemRender('simple', 'checkout/cart_item_renderer', 'checkout/cart/sidebar/default.phtml')
											->addItemRender('configurable', 'checkout/cart_item_renderer_configurable', 'checkout/cart/sidebar/default.phtml')
											->addItemRender('grouped', 'checkout/cart_item_renderer_grouped', 'checkout/cart/sidebar/default.phtml')
											->addItemRender('bundle', 'bundle/checkout_cart_item_renderer', 'checkout/cart/sidebar/default.phtml')                                    
											->renderView();
				}
				
				if (!$result['error'] && $request->getParam('flycart_cart_delete') == 1){
					if (!Mage::helper('checkout/cart')->getCart()->getItemsCount()){
						$result['redirect'] = Mage::getUrl('checkout/cart');
					}
					$result['item_id'] = $id;                	            
					$result['total'] = $layout->createBlock('checkout/cart_totals', 'checkout.cart.totals')
											 ->setTemplate('checkout/cart/totals.phtml')  
											 ->renderView();
											 
					$result['shipping'] = $layout->createBlock('checkout/cart_shipping', 'checkout.cart.shipping')
											 ->setTemplate('checkout/cart/shipping.phtml')  
											 ->renderView();                         
											 
					if (Mage::getStoreConfig('flycart/qtyupdate/crosssell')){
						$result['crosssell'] = $layout->createBlock('checkout/cart_crosssell', 'checkout.cart.crosssell')
												 ->setTemplate('checkout/cart/crosssell.phtml')  
												 ->renderView();            	
					}                         
				}
				
				if (!$result['error']){
					$result['top_links'] = $this->getTopLinks();
					$result['top_cart'] = $this->getCartTopbar();
					$result['custom_cart'] = $this->getCustomCart();
					if ($product->getStockItem()->getManageStock()){
						$result['product_id'] = $product->getId();
						$result['max_qty'] = intval($product->getStockItem()->getQty());
					}	
				}
            }
            $event->getEvent()->getControllerAction()->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
	        $event->getEvent()->getControllerAction()->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            
	    }
	}
	
	public function redirectToWishlist($event){
	    $request = $event->getEvent()->getControllerAction()->getRequest();
	    if ($request->getParam('flycart_wishlist_add') == 1){
    	    if(!Mage::getSingleton('customer/session')->isLoggedIn()){
    	        $result = array();    	        
    	        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_current'=>true)));
    	        $result['redirect'] = Mage::helper('customer')->getLoginUrl();    	        
	            echo Mage::helper('core')->jsonEncode($result); exit();
    	    }    	    
	    } 
	}
	
	public function addProductWishlist($event){	    
	    $request = Mage::app()->getFrontController()->getRequest();
	    if ($request->getParam('flycart_wishlist_add') == 1){
	        Mage::helper('wishlist')->calculate();
	        Mage::unregister('wishlist');
	        Mage::unregister('shared_wishlist');
	        Mage::unregister('_helper/wishlist');	        	        
    	    $result = array();    	        
        	$result['prod_name'] = $event->getProduct()->getName(); 
        	$result['top_links'] = $this->getTopLinks();  
        	$result['wishlist'] = $this->getWishlistSidebar();     	
            echo Mage::helper('core')->jsonEncode($result); exit();
	    }                
	}
	
    public function addProductCompare($event){	    
	    $request = Mage::app()->getFrontController()->getRequest();
	    if ($request->getParam('flycart_compare_add') == 1){
    	    $result = array();    	        
        	$result['prod_name'] = $event->getProduct()->getName();
			Mage::getSingleton('catalog/session')->getMessages(true);
        	Mage::helper('catalog/product_compare')->calculate();
        	$layout = Mage::getSingleton('core/layout');
	    
    	    $result['compare_products'] = $layout->createBlock('catalog/product_compare_sidebar', 'catalog.compare.sidebar')
	                                ->setTemplate('catalog/product/compare/sidebar.phtml')                                                                        
                                    ->renderView();
        	
            echo Mage::helper('core')->jsonEncode($result); exit();
	    }                
	}
	
	public function removeProductCompare($event){
	    $request = Mage::app()->getFrontController()->getRequest();
	    if (($request->getParam('flycart_remove_compare') == 1) &&
	        ($request->getParam('isAjax') == 1)){
    	    $result = array();
    	    
    	    Mage::helper('catalog/product_compare')->calculate();
        	$layout = Mage::getSingleton('core/layout');
	    
    	    $result['compare_products'] = $layout->createBlock('catalog/product_compare_sidebar', 'catalog.compare.sidebar')
	                                ->setTemplate('catalog/product/compare/sidebar.phtml')                                                                        
                                    ->renderView();
        	
            echo Mage::helper('core')->jsonEncode($result); exit();
	    }        	        
	}
	
	public function addToCartFromWishlist($event){
		$request = Mage::app()->getFrontController()->getRequest();
		if ($request->getParam('flycart_add') == 1){
			$itemId = $request->getParam('item');
			if ($itemId){
				 $item = Mage::getModel('wishlist/item')->load($itemId);			     		
				 if (!$item->getId()){
				 	$result = array();
				 	$result['success'] = false;
				 	$result['redirect'] = Mage::helper('checkout/cart')->getCartUrl();				 	
		            echo Mage::helper('core')->jsonEncode($result); exit();
		            
		         }else{
			        $messages = Mage::getSingleton('catalog/session')->getMessages(false)->getItems(Mage_Core_Model_Message::NOTICE);	        	    
	        	    $message_text = '';	        	    
	        	    foreach ( $messages as $message) {
	        	        $message_text .= str_replace('""', '"'.$item->getProduct()->getName().'"', $message->getText());
	        	    }
	        	    
	        	    $messages = Mage::getSingleton('wishlist/session')->getMessages(false)->getItems(Mage_Core_Model_Message::ERROR);
		         	foreach ( $messages as $message) {
	        	        $message_text .= str_replace('""', '"'.$item->getProduct()->getName().'"', $message->getText());
	        	    }
	        	    
	        	    if ($message_text){
	        	    	Mage::getSingleton('catalog/session')->getMessages(true);
	        	    	Mage::getSingleton('wishlist/session')->getMessages(true);
	        	    	$result['success'] = false;
					 	$result['message'] = $message_text;				 	
			            echo Mage::helper('core')->jsonEncode($result); exit();
	        	    }	        	    
		         }
			}
		} 		
	}
	
	public function updateItemOptionsWithError($event){
		$request = Mage::app()->getFrontController()->getRequest();
		if ($request->getParam('flycart_add') == 1){
			
				$product = Mage::getModel('catalog/product')->load($request->getParam('product', 0));
				$product_name = ($product->getId() ? $product->getName() : '');
				
				
				$messages = array_merge(Mage::getSingleton('checkout/session')->getMessages(false)->getItems(Mage_Core_Model_Message::ERROR), Mage::getSingleton('checkout/session')->getMessages(false)->getItems(Mage_Core_Model_Message::NOTICE));	        	    
        	    $message_text = '';	        	    
        	    foreach ( $messages as $message) {
        	        $message_text .= str_replace('""', '"'.$product_name.'"', $message->getText());
        	    }
        	    if ($message_text){
        	    	Mage::getSingleton('checkout/session')->getMessages(true);        	    	
        	    	$result['success'] = false;        	    	
				 	$result['message'] = $message_text;				 	
		            echo Mage::helper('core')->jsonEncode($result); exit();
        	    }	        	   
		}			
	}
			
}
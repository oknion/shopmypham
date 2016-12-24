<?php

class Vt_ProductsConfig_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {    			
	  $this->loadLayout();
      $this->renderLayout();
    }
    public function testAction(){
    	$myblock = $this->getLayout()->createBlock('productsconfig/list')->testAjax();
    	echo $myblock;
    }
    public function ajaxAction() {
		$block   = $this->getLayout()->createBlock('productsconfig/list');                                    
        $listProducts = json_encode($block->toHtml());
        //$categoryName = json_encode($block->getCategoryName());
        //$categoryImage = json_encode($block->getCategoryImage());
        //echo '{"listProducts":'.$listProducts.',"categoryName":'.$categoryName.',"categoryImage":'.$categoryImage.'}';		
        echo '{"listProducts":'.$listProducts.'}';
    }
}

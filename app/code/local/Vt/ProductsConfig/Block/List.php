<?php

class Vt_ProductsConfig_Block_List extends Mage_Catalog_Block_Product_List
{
	protected $defaultTemplate = 'vt/productsconfig/default.phtml';
	protected $filter = null;
	protected $_productlist = null; 

	public function __construct($attributes = array()){
		parent::__construct($attributes);
		
		$selfData = $this->getData();
		
		// handler configuration for module config
		$configuration = $this->_getConfiguration();
		if ( count($configuration) ){
			foreach ($configuration as $field => $value) {
				//if (!array_key_exists($field, $selfData)){
				$selfData[$field] = $value;
				//}
			}
		}
		
		// handler attributes for {{block ...}} in cms page
		if ( count($attributes) ){
			foreach ($attributes as $field => $value) {
				//if (!array_key_exists($field, $selfData)){
				$selfData[$field] = $value;
				//}
			}
		}
		
		// re-save data
		$this->setData($selfData);
	}
	public function getAddToCartUrl($product, $additional = array()){
    
		if (!isset($additional['_query'])) {
			 $additional['_query'] = array();
		}
		$additional['_query']['flycart_item'] = $product->getId();        
    
        return parent::getAddToCartUrl($product, $additional);
        
    } 
    public function getFlycartAssociatedProduct(){
        
        if (!$this->_productlist){       
        	  
             $this->_productlist = array();
             $helper = Mage::helper('flycart');
             

            $collection = Mage::getSingleton('catalog/product')->getCollection();
			$collection->addAttributeToSelect('*');
			$collection->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);

			if($this->filter){
				if($this->filter == 'deals' || $this->filter == 'sale'){
					$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
					$tomorrow = mktime(0, 0, 0, date('m'), date('d')+1, date('y'));
					$dateTomorrow = date('m/d/y', $tomorrow);
					$collection->addAttributeToFilter('special_price', array('neq' => ""));
					if($filter == 'deals') {
						$collection->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
							->addAttributeToFilter('special_to_date', array('or'=> array(0 => array('date' => true, 'from' => $dateTomorrow), 1 => array('isNot' => new Zend_Db_Expr('null')))), 'left');
					}
				}
			}

			$visibility = array(
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
			);
			$collection->addAttributeToFilter('visibility', $visibility);

             foreach ($collection as $_product){                 
                 $product = Mage::getModel('catalog/product')->load($_product->getId());  

                 $this->_productlist[$product->getId()] = $helper->getFlycartProductData($product);
                
             }
              
        }
       
        return Mage::helper('core')->jsonEncode($this->_productlist);          
    }    
   
	public function setConfig($key = null, $value = null){
		if ( is_array($key) ){
			foreach ($key as $k => $v){
				$this->setData($k, $v);
			}
		} else if ( !is_null($key) ) {
			$this->setData($key, $value);
		}
	}
	
	protected function _getConfiguration($path = 'vt_productsconfig_cfg'){
		$configuration = Mage::getStoreConfig($path);
		$conf_merged = array();
		foreach( $configuration as $group ){
			foreach($group as $field => $value){
				if (array_key_exists($field, $conf_merged)){
					// no override
				} else {
					$conf_merged[$field] = $value;
				}
			}
		}
		return $conf_merged;
	}

	public function getListProductAjax(){
		$collection = $this->_getProductCollection()->load();		
		$this->setTemplate('vt/productsconfig/item.phtml');	
		return parent::_toHtml();
	}
	/*public function getCategoryName(){		
		$this->setTemplate('vt/productsconfig/categoryName.phtml');	
		return parent::_toHtml();
	}
	public function getCategoryImage(){		
		$this->setTemplate('vt/productsconfig/categoryImage.phtml');	
		return parent::_toHtml();
	}
	protected function _toHtml(){
		$catid    = Mage::app()->getRequest()->getParam('catid');
		if ($catid != '') {
			$this->setTemplate('vt/productsconfig/item.phtml');
		}	
		return parent::_toHtml();
	}*/
	protected function _beforeToHtml(){
		
		$catid    = Mage::app()->getRequest()->getParam('catid');
		if ($catid != '') {
			$this->setTemplate('vt/productsconfig/item.phtml');
		}else if ( !($template = $this->getTemplate()) ){
				$this->setTemplate($this->defaultTemplate);
		}		
	
		/*$toolbar = $this->getToolbarBlock();
        $collection = $this->_getProductCollection();
          // use sortable parameters
        if ($orders = $this->getAvailableOrders()) {
            $toolbar->setAvailableOrders($orders);
        }
        if ($sort = $this->getSortBy()) {
            $toolbar->setDefaultOrder($sort);
        }
        if ($dir = $this->getDefaultDirection()) {
            $toolbar->setDefaultDirection($dir);
        }
        if ($modes = $this->getModes()) {
            $toolbar->setModes($modes);
        }
        $toolbar->setCollection($collection);
 
        $this->setChild('toolbar', $toolbar);
        Mage::dispatchEvent('catalog_block_product_list_collection', array(
            'collection' => $this->_getProductCollection()
        ));*/
 
        $this->_getProductCollection()->load();
 
        return parent::_beforeToHtml();
	}
	
	protected function _getProductCollection()
	{
		if (is_null($this->_productCollection)) {
			$collection = Mage::getSingleton('catalog/product')->getCollection();
			$collection->addAttributeToSelect('*');
			$collection->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);	
			if($this->filter){
				if($this->filter == 'deals' || $this->filter == 'sale'){
					$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
					$tomorrow = mktime(0, 0, 0, date('m'), date('d')+1, date('y'));
					$dateTomorrow = date('m/d/y', $tomorrow);
					$collection->addAttributeToFilter('special_price', array('neq' => ""));
					if($filter == 'deals') {
						$collection->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
							->addAttributeToFilter('special_to_date', array('or'=> array(0 => array('date' => true, 'from' => $dateTomorrow), 1 => array('isNot' => new Zend_Db_Expr('null')))), 'left');
					}
				}
			}		
			$visibility = array(
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
			);
			$collection->addAttributeToFilter('visibility', $visibility);
			
			
			$collection->addPriceData();
			/*$catid    = Mage::app()->getRequest()->getParam('catid');
			if ($catid != '') {
				$this->_addCategoryFilter($collection, $catid);
			}*/
			if($this->getLayoutProduct() =='layout01'){
				if ( $this->getProductSource() == 'product'){					
					if ( $ids = $this->getProductIds() ){
						$ids = preg_split('#[\s|,]+#', $ids, -1, PREG_SPLIT_NO_EMPTY);
						$ids = array_map('intval', $ids);
						$ids = array_unique($ids);
						$collection->addIdFilter($ids);
					}
				} else {					
					$category_ids = $this->getProductCategory() ? $this->getProductCategory() : '';				
					$category_ids = preg_split('#[\s|,]+#', $category_ids, -1, PREG_SPLIT_NO_EMPTY);
					$category_ids = array_map('intval', $category_ids);
					$category_ids = array_unique($category_ids);
					$this->_addCategoryFilter($collection, $category_ids);	
					//print_r($category_ids);		
				}
			}else{
				if($this->getRequest()->getParam('catid')){
					$category_ids = $this->getRequest()->getParam('catid') ? $this->getRequest()->getParam('catid') : '';				
					$category_ids = preg_split('#[\s|,]+#', $category_ids, -1, PREG_SPLIT_NO_EMPTY);
					$category_ids = array_map('intval', $category_ids);
					$category_ids = array_unique($category_ids);				
					$this->_addCategoryFilter($collection, $category_ids);
				}else{
					$category_ids = $this->getProductCategoryLayout02() ? $this->getProductCategoryLayout02() : '';				
					$category_ids = preg_split('#[\s|,]+#', $category_ids, -1, PREG_SPLIT_NO_EMPTY);
					$category_ids = array_map('intval', $category_ids);
					$category_ids = array_unique($category_ids);
					$this->_addCategoryFilter($collection, $category_ids);
				}
			}
		
			if ($this->getRequest()->getParam('orderby')) {
				$product_sort_by = $this->getRequest()->getParam('orderby');
			}else{
				$product_sort_by = $this->getProductOrderBy() ? trim($this->product_order_by) : 'rand()';
			}	
			
			if ( $this->getProductRatingSummary() || $product_sort_by=='top_rating'
					|| $this->getProductReviewCount() || $product_sort_by=='most_reviewed'){
				$this->_addReviewSummary($collection);
			}
			if ( $this->getProductViewedCount() || $product_sort_by=='most_viewed' ){
				$this->_addViewedCount($collection);
			}
			if ( $this->getProductOrderedCount() || $product_sort_by=='best_sales' ){
				$this->_addOrderedCount($collection);
			}
			
			switch ($product_sort_by){
				case 'name':
					$collection->addAttributeToSort('name', 'asc');
					break;
				case 'created_at':
					$collection->getSelect()->order("e.entity_id desc");
					break;
				case 'price':
					$product_sort_dir = $this->getProductOrderDir() ? $this->product_order_dir : 'ASC';
					if ( !in_array( strtoupper($product_sort_dir), array('ASC', 'DESC') ) ){
						$product_sort_dir = 'ASC';
					}
					$collection->addAttributeToSort($product_sort_by, $product_sort_dir);
					break;
				case 'position':
					break;
				case 'random':
					$collection->getSelect()->order('rand()');
					break;
			}
			
			$collection->addStoreFilter();
			$numProductsConfig = $this->getProductLimitation()>0 ? intval($this->product_limitation) : 0;
			$collection->setPage(1, $numProductsConfig); 
			$this->_productCollection = $collection;
		}
		return $this->_productCollection;
	}
	private function _addCategoryFilter(& $collection, $category_ids){
		if ( empty($category_ids) ){
			return ;
		}
		$category_collection = Mage::getModel('catalog/category')->getCollection();
		$category_collection->addAttributeToSelect('*');
		$category_collection->addIsActiveFilter();
		if (count($category_ids)>0){
			$category_collection->addIdFilter($category_ids);
		}
		if (!Mage::helper('catalog/category_flat')->isEnabled()) {
		    $category_collection->groupByAttribute('entity_id');
		}
		$category_productsconfig = array();
		foreach ($category_collection as $category){
			$cid = $category->getId();
			if ( !array_key_exists( $cid, $category_productsconfig) ){
				$category_productsconfig[$cid] = $category->getProductCollection()->getAllIds();
			}
		}
		$product_ids = array();
		if (count($category_productsconfig)){
			foreach ($category_productsconfig as $cp) {
				$product_ids = array_merge($product_ids, $cp);
			}
		}
		$collection->addIdFilter($product_ids);
	}
	public function getListCategoriesFilter(){
		$categoryId = $this->getProductCategoryLayout02() ? $this->getProductCategoryLayout02() : '';	
		$catid    = Mage::app()->getRequest()->getParam('catid');
		if ($catid != '') {
			$categoryId = $catid;
		}
		$cat_info = Mage::getModel('catalog/category')->load($categoryId);			
		$list = array();	
		$list[0] = $cat_info;
		$cat_child = $cat_info->getChildren();
		if( $cat_child != null ){
			$cat_child = explode(',',$cat_child);			
			$i = 1;		
			foreach( $cat_child as $subCatid){ 
				$_sub_cat = Mage::getModel('catalog/category')->load($subCatid);
				if( $_sub_cat->getIsActive() ) {					
					$list[$i++] = $_sub_cat;
				}				
			}
		}			
		return $list;		
	}
	private function _addViewedCount(& $collection, $viewed_count_alias='vt_viewed_count'){
		// add viewed_count
		$reports_event_table		= Mage::getSingleton('core/resource')->getTableName('reports/event');
		$reports_event_types_table 	= Mage::getSingleton('core/resource')->getTableName('reports/event_type');
		$collection->getSelect()
		->joinLeft(
				array('re_table' => $reports_event_table),
				'e.entity_id = re_table.object_id',
				array(
						$viewed_count_alias => 'COUNT(re_table.event_id)'
				)
		)->joinLeft(
				array('ret_table' => $reports_event_types_table),
				"re_table.event_type_id = ret_table.event_type_id AND ret_table.event_name = 'catalog_product_view'",
				array()
		)->group('e.entity_id');
	}
	
	private function _addReviewSummary(& $collection, $review_count_alias='vt_review_count', $rating_summary_alias='vt_rating_summary' ){
		// add review_count and rating_summary
		$review_summary_table = Mage::getSingleton('core/resource')->getTableName('review/review_aggregate');
		$collection->getSelect()->joinLeft(
				array('rs_table' => $review_summary_table),
				'e.entity_id = rs_table.entity_pk_value AND rs_table.store_id='.Mage::app()->getStore()->getId(),
				array(
						$review_count_alias  => 'rs_table.reviews_count',
						$rating_summary_alias => 'rs_table.rating_summary'
				)
		);
	}

	private function _addOrderedCount(& $collection, $ordered_qty_alias='vt_ordered_count'){
		$order_table = Mage::getSingleton('core/resource')->getTableName('sales/order');
		$read = Mage::getSingleton('core/resource')->getConnection ('core_read');
		$orders_active_query = $read->select()->from(array('o_table'=>$order_table), 'o_table.entity_id')->where("o_table.state<>'" . Mage_Sales_Model_Order::STATE_CANCELED . "'");
		$res = $orders_active_query->query()->fetchAll();
		$order_ids = array();
		if ( count($res) ){
			foreach($res as $row){
				array_key_exists('entity_id', $row) && array_push($order_ids, $row['entity_id']);
			}
		}
		$order_item_table = Mage::getSingleton('core/resource')->getTableName('sales/order_item');
		$collection->getSelect()->join(
				array('oi_table' => $order_item_table),
				'e.entity_id=oi_table.product_id'.(count($order_ids) ? ' AND oi_table.order_id IN('.implode(',', $order_ids).')' : ''),
				array(
						$ordered_qty_alias => 'SUM(oi_table.qty_ordered)'
				)
		);
		$collection->getSelect()->group('e.entity_id');
		return $this;
	}
}

<?php
/*------------------------------------------------------------------------
 # SM Listing Tabs - Version 2.2.1
 # Copyright (c) 2014 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\ListingTabs\Block;

class ListingTabs extends \Magento\Catalog\Block\Product\AbstractProduct
{
	protected $_config = null;

	/**
	 * Currently selected store ID if applicable
	 *
	 * @var int
	 */
	protected $_storeId;

	/**
	 * Resource
	 *
	 * @var \Magento\Framework\App\ResourceConnection
	 */
	protected $_resource;

	/**
	 * @var \Magento\Eav\Model\Config
	 */
	protected $_eavConfig;

	/**
	 * @var \Magento\Framework\Filesystem
	 */
	protected $_directory;

	/**
	 * Object manager
	 *
	 * @var \Magento\Framework\ObjectManagerInterface
	 */
	private $_objectManager;

	/**
	 * @var \Magento\Framework\App\Config\ScopeConfigInterface
	 */
	protected $_scopeConfigInterface;

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;

	/**
	 * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
	 */
	protected $localeDate;

	/**
	 * Class constructor
	 *
	 * @param \Magento\Framework\App\ResourceConnection $resourceConnection
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 * @param \Magento\Catalog\Block\Product\Context $context
	 * @param \Magento\Eav\Model\Config $eavConfig
	 * @param array $data
	 * @param string|null $scope
	 */
	public function __construct(
		\Magento\Framework\App\ResourceConnection $resourceConnection,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Catalog\Block\Product\Context $context,
		\Magento\Eav\Model\Config $eavConfig,
		array $data = [],
		$attr = null
	)
	{
		$this->_eavConfig = $eavConfig;
		$this->_resource = $resourceConnection;
		$this->_objectManager = $objectManager;
		$this->_storeManager = $this->_objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		$this->_scopeConfigInterface = $this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
		$this->localeDate = $this->_objectManager->get('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
		$this->_storeId = (int)$this->_storeManager->getStore()->getId();
		$this->_directory = $this->_objectManager->get('\Magento\Framework\Filesystem');
		if ($context->getRequest() && $context->getRequest()->isAjax()) {
			$_cfg =  $context->getRequest()->getParam('config');
			$this->_config = (array)json_decode(base64_decode(strtr($_cfg, '-_', '+/')));
		} else {
			$this->_config = $this->_getCfg($attr, $data);
		}
		parent::__construct($context, $data);
	}

	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}

	public function _helper()
	{
		return $this->_objectManager->get('\Sm\ListingTabs\Helper\Data');
	}

	public function _getCfg($attr = null , $data = null)
	{
		// get default config.xml
		$defaults = [];
		$collection = $this->_scopeConfigInterface->getValue('listingtabs',\Magento\Store\Model\ScopeInterface::SCOPE_STORES,$this->_storeId);
		if (empty($collection)) return;
		$groups = [];
		foreach ($collection as $def_key => $def_cfg) {
			$groups[] = $def_key;
			foreach ($def_cfg as $_def_key => $cfg) {
				$defaults[$_def_key] = $cfg;
			}
		}

		// get configs after change
		$_configs = $this->_scopeConfigInterface->getValue('listingtabs',\Magento\Store\Model\ScopeInterface::SCOPE_STORES,$this->_storeId);
		if (empty($_configs)) return;
		$cfgs = [];

		foreach ($groups as $group) {
			$_cfgs = $this->_scopeConfigInterface->getValue('listingtabs/'.$group.'',\Magento\Store\Model\ScopeInterface::SCOPE_STORES,$this->_storeId);
			foreach ($_cfgs as $_key => $_cfg) {
				$cfgs[$_key] = $_cfg;
			}
		}

		// get output config
		$configs = [];
		foreach ($defaults as $key => $def) {
			if (isset($defaults[$key])) {
				$configs[$key] = $cfgs[$key];
			} else {
				unset($cfgs[$key]);
			}
		}
		$cf = ($attr != null) ? array_merge($configs, $attr) : $configs;
		$this->_config = ($data != null) ? array_merge($cf, $data) : $cf;
		return $this->_config;
	}

	public function _getConfig($name = null, $value_def = null)
	{
		if (is_null($this->_config)) $this->_getCfg();
		if (!is_null($name)) {
			$value_def = isset($this->_config[$name]) ? $this->_config[$name] : $value_def;
			return $value_def;
		}
		return $this->_config;
	}

	public function _setConfig($name, $value = null)
	{

		if (is_null($this->_config)) $this->_getCfg();
		if (is_array($name)) {
			$this->_config = array_merge($this->_config, $name);

			return;
		}
		if (!empty($name) && isset($this->_config[$name])) {
			$this->_config[$name] = $value;
		}
		return true;
	}

	protected function _toHtml()
	{
		if (!$this->_getConfig('isactive', 1)) return;

		$use_cache = (int)$this->_getConfig('use_cache');
		$cache_time = (int)$this->_getConfig('cache_time');
		$folder_cache = $this->_directory->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::CACHE)->getAbsolutePath();
		$folder_cache = $folder_cache.'Sm/ListingTabs/';
		if(!file_exists($folder_cache))
			mkdir ($folder_cache, 0777, true);

		$options = array(
			'cacheDir' => $folder_cache,
			'lifeTime' => $cache_time
		);
		$Cache_Lite = new \Sm\ListingTabs\Block\Cache\Lite($options);
		if ($this->_isAjax()) {
			$ajax_listingtabs_start = $this->getRequest()->getPost('ajax_listingtabs_start', 0);
			$catid = $this->getRequest()->getPost('categoryid');
			if ($use_cache){
				$cacheid_items = md5(serialize([$this->_getConfig(), $this->_storeId ,$this->_storeManager->getStore()->getCurrentCurrencyCode(), $ajax_listingtabs_start, $catid]));
				if ( $dataitems = $Cache_Lite->get($cacheid_items)) {
					return  $dataitems;
				} else {
					$template_file = "default_items.phtml";
					$this->setTemplate($template_file);
					$dataitems = parent::_toHtml();
					$Cache_Lite->save($dataitems);
				}
			}else{
				if(file_exists($folder_cache))
					$Cache_Lite->_cleanDir($folder_cache);
					$template_file = "default_items.phtml";
					$this->setTemplate($template_file);
			}

		}else{
			if ($use_cache){
				$hash = md5( serialize([$this->_getConfig(), $this->_storeId ,$this->_storeManager->getStore()->getCurrentCurrencyCode()]) );
				if ($data = $Cache_Lite->get($hash)) {
					return  $data;
				} else {
					$template_file = $this->getTemplate();
					$template_file = (!empty($template_file)) ? $template_file : "Sm_ListingTabs::default.phtml";
					$this->setTemplate($template_file);
					$data = parent::_toHtml();
					$Cache_Lite->save($data);
				}
			}else{
				if(file_exists($folder_cache))
					$Cache_Lite->_cleanDir($folder_cache);
				$template_file = $this->getTemplate();
				$template_file = (!empty($template_file)) ? $template_file : "Sm_ListingTabs::default.phtml";
				$this->setTemplate($template_file);
			}
		}

        return parent::_toHtml();
	}

	public function _isAjax()
	{
		$isAjax = $this->getRequest()->isAjax();
		$is_ajax_listing_tabs = $this->getRequest()->getPost('is_ajax_listing_tabs');
		if ($isAjax && $is_ajax_listing_tabs == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function _getSelectSource()
	{
		$type_filter = $this->_getConfig('product_source');
		$list = [];
		switch ($type_filter) {
			default:
			case 'categories':
				$catids = $this->_getCatIds();

				!is_array($catids) && settype($catids, 'array');
				if (empty($catids)) return;

				$cats = $this->_getCatinfor($catids);

				if (empty($cats)) return;
				if ($this->_getConfig('tab_all_display', 1)) {
					$all = [];
					$all['entity_id'] = '*';
					$all['count'] = $this->_countProducts($catids);
					$all['title'] = 'ALL';
					array_unshift($cats, $all);
				}

				$catidpreload = $this->_getConfig('category_preload');
				$selected = false;
				foreach ($cats as $cat) {
					if (isset($cat['count']) && $cat['count']) {
						if ($cat['entity_id']== $catidpreload) {
							$cat['sel'] = 'sel';
							$cat['child'] = $this->_getProductInfor($catidpreload);
							$selected = true;
						}
						$list[$cat['entity_id']] = $cat;
					}
				}

				if (!$selected) {
					foreach ($cats as $cat) {
						if ($cat['count'] > 0) {
							$cat['sel'] = 'sel';
							$cat['child'] = $this->_getProductInfor($cat['entity_id']);
							$list[$cat['entity_id']] = $cat;
							break;
						}
					}
				}
				break;

			case 'fieldproducts':
				$catids = $this->_getCatIds();
				$filters = explode(',', $this->_getConfig('filter_order_by'));
				$filter_preload = $this->_getConfig('field_preload');
				if (empty($filters)) return;
				if (!in_array($filter_preload, $filters)) {
					$filter_preload = $filters[0];
				}

				foreach ($filters as $filter) {
					$product = [];
					$product['count'] = $this->_countProducts($catids, $filter);
					$product['entity_id'] = $filter;
					$product['title'] = $filter;
					if ($product['count'] > 0) {
						if ($product['entity_id'] == $filter_preload) {
							$product['sel'] = 'sel';
							$product['child'] = $this->_getProductInfor($catids, $filter_preload);
						}
						$list[$product['entity_id']] = $product;
					}
				}
				break;
		}
		if (empty($list)) return;
		return $list;
	}

	public function _getCatIds()
	{
		$catids = $this->_getConfig('product_category');
		if ($catids == null) return;
		$_catids = $this->_getCatActive($catids);
		if (empty($_catids)) return;

		return $_catids;
	}

	public function _getCatinfor($catids, $orderby = null)
	{
		$helper = $this->_helper();
		$category_image_config = [
			'width' => (int)$this->_getConfig('imgcat_width', 50),
			'height' => (int)$this->_getConfig('imgcat_height', 50),
			'background' => (string)$this->_getConfig('imgcat_background'),
			'function' => (int)$this->_getConfig('imgcat_function', 0)
		];
		$list = [];

		if (!empty($catids)) {
			foreach ($catids as $catid) {
				$cat = [];
				$category_model = $this->_objectManager->create('Magento\Catalog\Model\Category');
				$category = $category_model->load((int)$catid);
				$cat['title'] = $category->getName();
				$cat['count'] = $this->_countProducts($catid);
				$cat['entity_id'] = $catid;

				if($this->_getConfig('icon_display', 1))
				{
					$_image = $helper->getCatImage($category, $this->_getConfig());
					$cat['_image'] = $helper->_resizeImage($_image, $category_image_config,"category");
				} else {
					$cat['_image'] = '';
				}

				$list[$catid] = $cat;
			}
		}
		return $list;
	}

	public function _moduleID()
	{
		return md5(serialize(['sm_listingtabs', $this->_config]));
	}

	public function _getProductInfor($_catids, $field_order = null)
	{
		$small_image_config = [
			'width' => (int)$this->_getConfig('img_width', 200),
			'height' => $this->_getConfig('img_height', null),
			'background' => (string)$this->_getConfig('img_background'),
			'function' => (int)$this->_getConfig('img_function')
		];

		if ($_catids == '*') {
			$_catids = $this->_getCatIds();
		}
		!is_array($_catids) && settype($_catids, 'array');
		if (!empty($_catids)) {
			$products = $this->_getProductsBasic($_catids, $field_order);
			if ($products != null) {
				$_products = $products->getItems();
				if (!empty($_products)) {
					$helper = $this->_helper();
					foreach ($_products as $_product) {
						$_product->setStoreId($this->_storeId);
						$_product->title = $_product->getName();
						if($this->_getConfig('product_image_display', 1))
						{
							$image = $helper->getProductImage($_product, $this->_getConfig());
							$_image = $helper->_resizeImage($image, $small_image_config,"product");
							$_product->_image = $_image;
						} else {
							$_product->_image = '';
						}
						if ((int)$this->_getConfig('product_description_display', 1)) {
							$_product->_description = $helper->_cleanText($_product->getDescription());
							$_product->_description = $helper->_trimEncode($_product->_description != '') ? $_product->_description : $helper->_cleanText($_product->getShortDescription());
							$_product->_description = $helper->_trimEncode($_product->_description != '') ? $helper->truncate($_product->_description, $this->_getConfig('product_description_maxlength')) : '';
						}
						$_product->link = $_product->getProductUrl();
						if (($this->_getConfig('show_theme') == 'deals') && ($this->_getConfig('product_countdown_display') == 1)) {
							$_product->special_to_date = date_format(date_create($_product->getSpecialToDate()), 'Y/m/d 23:59:59');
						}
					}

					return $_products;
				}
			}
		}
		return null;
	}

	/*
	* Check Categories is Active ?
	*/
	private function _getCatActive($catids = null, $orderby = true)
	{
		if (is_null($catids)) {
			$catids = $this->_getConfig('product_category');
		}
		!is_array($catids) && $catids = preg_split('/[\s|,|;]/', $catids, -1, PREG_SPLIT_NO_EMPTY);
		if (empty($catids)) return;
		$categoryIds = ['in' => $catids];
		$categories = $this->_objectManager->get('Magento\Catalog\Model\Category')
			->getCollection()
			->addAttributeToSelect('*')
			->setStoreId($this->_storeId)->addAttributeToFilter('entity_id', $categoryIds)
			->addIsActiveFilter();
		if ($orderby) {
			$attribute = $this->_getConfig('category_order_by', 'name'); // name | position | entry_id | random
			$dir = $this->_getConfig('category_order_dir', 'ASC');
			switch ($attribute) {
				case 'name':
				case 'position':
				case 'entry_id':
					$categories->addAttributeToSort($attribute, $dir);
					break;

				case 'random':
					$categories->getSelect()->order(new Zend_Db_Expr('RAND()'));
					break;

				default:
			}
		}
		$_catids = [];
		if (empty($categories)) return;
		foreach ($categories as $category) {
			$_catids[] = $category->getId();
		}
		return $_catids;
	}

	/*
	* array $catids
	* bool $allcat = true return with parentid else return only childId
	* int $limitCat = 0 return unlimit else return limit
	* int $levels =  1
	* return $catids
	*/
	private function _childCategory($catids, $allcat = true, $limitCat = 0, $levels = 0)
	{
		!is_array($catids) && settype($catids, 'array');
		$additional_catids = [];
		if (!empty($catids)) {
			foreach ($catids as $catid) {
				$_category = $this->_objectManager->get('Magento\Catalog\Model\Category')->load($catid);
				$levelCat = $_category->getLevel();
				if ($_category->hasChildren()){
					$catid_childs = $_category->getAllChildren(true);
					foreach ($catid_childs as $cat_child) {
						$_cat_child = $this->_objectManager->get('Magento\Catalog\Model\Category')->load($cat_child);
						$cat_child_level = $_cat_child->getLevel();
						$condition = ($cat_child_level - $levelCat <= $levels);
						if ($condition) {
							$additional_catids[] = $_cat_child->getId();
						}
					}
				}
			}
			$catids = $allcat ? array_unique(array_merge($catids, $additional_catids)) : array_unique($additional_catids);
		}
		return $catids;
	}

	/*
	* return countProduct;
	*/
	protected function _countProducts($catids, $field_order = null)
	{
		!is_array($catids) && settype($catids, 'array');
		$countProduct = $this->_getCountProductsBasic($catids, $field_order, true);
		return $countProduct;
	}

	public function _getCountProductsBasic($catids,$field_order = null, $countProduct = false)
	{
		$collection = [];
		$inlucde = (int)$this->_getConfig('child_category_products', 1);
		$level = (int)$this->_getConfig('max_depth', 1);
		$catids = ($inlucde && $level > 0) ? $this->_childCategory($catids, true, 0, (int)$level) : $catids;
		if (!empty($catids)) {
			$attributes = ['name','price','special_price','special_from_date','special_to_date','msrp','price_view','special_to_date', 'description', 'short_description', 'image', 'thumbnail'];
			if($this->_getConfig('show_theme') == 'deals')
			{
				$todayStartOfDayDate = $this->localeDate->date()->setTime(0, 0)
					->format('Y-m-d H:i:s');

				$todayEndOfDayDate = $this->localeDate->date()
					->setTime(23, 59, 59)
					->format('Y-m-d H:i:s');
				$collection = $this->_objectManager->get('Magento\Catalog\Model\Product')
					->getCollection()
					->addAttributeToSelect($attributes)
					->addAttributeToSelect('featured')
					->setStoreId($this->_storeId)
					->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id = entity_id', null, 'left')
					->addAttributeToFilter([['attribute' => 'category_id', 'in' => [$catids]]])
					->addAttributeToFilter([
						['attribute' => 'special_from_date', 'is' => new \Zend_Db_Expr('not null')],
						['attribute' => 'special_to_date', 'is' => new \Zend_Db_Expr('not null')],
					])
					->addAttributeToFilter('special_from_date',
						['and' => [
							0 => ['date' => true, 'to' => $todayEndOfDayDate]
						]], 'left')
					->addAttributeToFilter('special_to_date',
						['and' => [
							0 => ['date' => true, 'from' => $todayStartOfDayDate]
						]], 'left')
					->addFieldToFilter('special_price', ['neq' => '']);
			} else {
				$collection = $this->_objectManager->get('Magento\Catalog\Model\Product')
					->getCollection()
					->addAttributeToSelect($attributes)
					->addAttributeToSelect('featured')
					->setStoreId($this->_storeId)
					->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id = entity_id', null, 'left')
					->addAttributeToFilter([['attribute' => 'category_id', 'in' => [$catids]]]);
			}

			if ($this->_getFeaturedProduct($collection) == false) return null;
			$this->_getFeaturedProduct($collection);
			$collection->getSelect()->group('entity_id')->distinct(true);
			$this->_getOrder($collection, $field_order);

			$collection->clear();
			if ($countProduct) return  count($collection->getAllIds());
			$start = (int)$this->getRequest()->getPost('ajax_listingtabs_start');
			if (!$start) $start = 0;
			$_limit = (int)$this->_getConfig('product_limitation', 5);
			$_limit = $_limit <= 0 ? 0 : $_limit;
			if ($_limit >= 0) {
				$collection->getSelect()->limit($_limit, $start);
			}
		}
		return $collection;
	}

	public function _getProductsBasic($catids,$field_order = null, $countProduct = false)
	{
		$collection = [];
		!is_array($catids) && settype($catids, 'array');
		$inlucde = (int)$this->_getConfig('child_category_products', 1);
		$level = (int)$this->_getConfig('max_depth', 1);
		$catids = ($inlucde && $level > 0) ? $this->_childCategory($catids, true, 0, (int)$level) : $catids;
		if (!empty($catids)) {
			$attributes = ['name','price','special_price','special_from_date','special_to_date','msrp','price_view','special_to_date', 'description', 'short_description', 'image', 'thumbnail'];

			if($this->_getConfig('show_theme') == 'deals')
			{
				$todayStartOfDayDate = $this->localeDate->date()->setTime(0, 0)
					->format('Y-m-d H:i:s');

				$todayEndOfDayDate = $this->localeDate->date()
					->setTime(23, 59, 59)
					->format('Y-m-d H:i:s');
				$collection = $this->_objectManager->get('Magento\Catalog\Model\Product')
					->getCollection()
					->addAttributeToSelect($attributes)
					->addAttributeToSelect('featured')
					->addMinimalPrice()
					->addFinalPrice()
					->addUrlRewrite()
					->setStoreId($this->_storeId)
					->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id = entity_id', null, 'left')
					->addAttributeToFilter([['attribute' => 'category_id', 'in' => [$catids]]])
					->addAttributeToFilter([
						['attribute' => 'special_from_date', 'is' => new \Zend_Db_Expr('not null')],
						['attribute' => 'special_to_date', 'is' => new \Zend_Db_Expr('not null')],
					])
					->addAttributeToFilter('special_from_date',
						['and' => [
							0 => ['date' => true, 'to' => $todayEndOfDayDate]
						]], 'left')
					->addAttributeToFilter('special_to_date',
						['and' => [
							0 => ['date' => true, 'from' => $todayStartOfDayDate]
						]], 'left')
					->addFieldToFilter('special_price', ['neq' => '']);
			} else {
				$collection = $this->_objectManager->get('Magento\Catalog\Model\Product')
					->getCollection()
					->addAttributeToSelect($attributes)
					->addAttributeToSelect('featured')
					->addMinimalPrice()
					->addFinalPrice()
					->addUrlRewrite()
					->setStoreId($this->_storeId)
					->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id = entity_id', null, 'left')
					->addAttributeToFilter([['attribute' => 'category_id', 'in' => [$catids]]]);
			}

			if ($this->_getFeaturedProduct($collection) == false) return null;
			$this->_getFeaturedProduct($collection);
			$collection->setVisibility($this->_objectManager->get('\Magento\Catalog\Model\Product\Visibility')->getVisibleInCatalogIds());
			$this->_addViewsCount($collection); // For Most Viewed
			$this->_addReviewsCount($collection); // For Most Reviews and Top Ratting
			$collection->getSelect()->group('entity_id')->distinct(true);
			$this->_getOrder($collection, $field_order);
			$collection->clear();
			if ($countProduct) return  count($collection->getAllIds());
			$start = (int)$this->getRequest()->getPost('ajax_listingtabs_start');
			if (!$start) $start = 0;
			$_limit = (int)$this->_getConfig('product_limitation', 5);
			$_limit = $_limit <= 0 ? 0 : $_limit;
			if ($_limit >= 0) {
				$collection->getSelect()->limit($_limit, $start);
			}
			$this->_objectManager->get('Magento\Review\Model\Review')->appendSummary($collection);
		}

		return $collection;
	}

	/*
	 *	Get Featured Product
	 */
	private function _getFeaturedProduct($collection)
	{
		$filter = (int)$this->_getConfig('product_featured', 0);
		$attributeModel = $this->_eavConfig->getAttribute('catalog_product', 'featured');
		switch ($filter) {
			// Show All
			case 0:
				break;
			// None Featured
			case 1:
				if ($attributeModel->usesSource()) {
					$collection->addAttributeToFilter([['attribute' => 'featured', 'eq' => 0]], null, 'left');
				}
				break;
			// Only Featured
			case 2:
				if ($attributeModel->usesSource()) {
					$collection->addAttributeToFilter([['attribute' => 'featured', 'eq' => 1]]);
				} else {
					return;
				}
				break;
		}
		return $collection;
	}

	/*
	 *	Get Lastest Product
	 */
	private function _getLastestProduct(& $collection)
	{
		$todayStartOfDayDate = $this->localeDate->date()
			->setTime(0, 0)
			->format('Y-m-d H:i:s');

		$todayEndOfDayDate = $this->localeDate->date()
			->setTime(23, 59, 59)
			->format('Y-m-d H:i:s');
		if($this->_getConfig('show_theme') == 'deals')
		{
			$collection = $this->_addProductAttributesAndPrices($collection)
				->addStoreFilter()
				->addAttributeToFilter('news_from_date',
					['or' => [
						0 => ['date' => true, 'to' => $todayEndOfDayDate],
						1 => ['is' => new \Zend_Db_Expr('null')]
					]], 'left')
				->addAttributeToFilter('news_to_date',
					['or' => [
						0 => ['date' => true, 'from' => $todayStartOfDayDate],
						1 => ['is' => new \Zend_Db_Expr('null')]
					]], 'left')
				->addAttributeToFilter([
					['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
					['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
				])
				->addAttributeToSort('news_from_date', 'DESC');
		} else {
			$collection = $this->_addProductAttributesAndPrices($collection)
				->addStoreFilter()
				->addAttributeToFilter('news_from_date',
					['or' => [
						0 => ['date' => true, 'to' => $todayEndOfDayDate],
						1 => ['is' => new \Zend_Db_Expr('null')]
					]], 'left')
				->addAttributeToFilter('news_to_date',
					['or' => [
						0 => ['date' => true, 'from' => $todayStartOfDayDate],
						1 => ['is' => new \Zend_Db_Expr('null')]
					]], 'left')
				->addAttributeToSort('news_from_date', 'DESC');
		}
		return $collection;
	}

	/*
	 * For Most Viewed
	 * add views_count
	 * */
	private function _addViewsCount(& $collection)
	{
		$reports_event_table = $this->_resource->getTableName('report_event');
		$select = $this->_resource->getConnection('core_read')
			->select()
			->from($reports_event_table, ['*', 'num_view_counts' => 'COUNT(`event_id`)'])
			->where('event_type_id = 1')
			->group('object_id');
		$collection->getSelect()
			->joinLeft(['mv' => $select],
				'mv.object_id = e.entity_id');
		return $collection;
	}

	/*
	 * For Most Reviews and Top Ratting
	 * add reviews_count and rating_summary
	 * */
	private function _addReviewsCount(& $collection)
	{
		$review_summary_table = $this->_resource->getTableName('review_entity_summary');
		$collection->getSelect()
			->joinLeft(
				["ra" => $review_summary_table],
				"e.entity_id = ra.entity_pk_value AND ra.store_id=" . $this->_storeId,
				[
					'num_reviews_count' => "ra.reviews_count",
					'num_rating_summary' => "ra.rating_summary"
				]
			);
		return $collection;
	}

	/*
	 *	Get Order
	 */
	private function _getOrder($collection, $fileld_order = null)
	{
		$attribute = ($fileld_order == null) ? (string)$this->_getConfig('product_order_by', 'name') : $fileld_order;
		$dir = (string)$this->_getConfig('product_order_dir', 'ASC');
		switch ($attribute) {
			case 'entity_id':
			case 'name':
			case 'created_at':
				$collection->setOrder($attribute, $dir);
				break;
			case 'price':
				if($this->_getConfig('show_theme') == 'deals')
				{
					$collection->getSelect()->order('special_price ' . $dir . '');
				} else {
					$collection->getSelect()->order('final_price ' . $dir . '');
				}
				break;
			case 'random':
				$collection->getSelect()->order(new \Zend_Db_Expr('RAND()'));
				break;
			case 'lastest_product':
				$this->_getLastestProduct($collection);
				break;
			case 'top_rating':
				$collection->getSelect()->order('num_rating_summary ' . $dir . '');
				break;
			case 'most_reviewed':
				$collection->getSelect()->order('num_reviews_count ' . $dir . '');
				break;
			case 'most_viewed':
				$collection->getSelect()->order('num_view_counts ' . $dir . '');
				break;
			case 'best_sellers':
				$collection->getSelect()->order('ordered_qty ' . $dir . '');
				break;
			default:
		}
		return $collection;
	}

	public function getLabel($filter)
	{
		switch ($filter) {
			case 'name':
				return __('Name');
			case 'entity_id':
				return __('Id');
			case 'created_at':
				return __('Date Created');
			case 'price':
				return __('Price');
			case 'lastest_product':
				return __('Lastest Product');
			case 'top_rating':
				return __('Top Rating');
			case 'most_reviewed':
				return __('Most Reviews');
			case 'most_viewed':
				return __('Most Viewed');
			case 'best_sales':
				return __('Most Selling');
			case 'random':
				return __('Random');
		}
	}

	public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->_objectManager->get('\Magento\Framework\Url\Helper\Data')->getEncodedUrl($url),
            ]
        ];
    }

}
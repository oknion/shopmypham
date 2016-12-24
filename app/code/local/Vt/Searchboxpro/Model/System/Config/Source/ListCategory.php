<?php
/*------------------------------------------------------------------------
 # VT Search Box Pro - Version 1.0
 # Copyright (c) 2013 vnthemepro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: vnthemepro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/
/*class Vt_Searchboxpro_Model_System_Config_Source_ListCategory
{
    public function toOptionArray($addEmpty = true)
    {
        $options = array();
               
        $store = Mage::app()->getStore()->getId();
        $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();

        $rootpath = Mage::getModel('catalog/category')
                            ->setStoreId($store)
                            ->load($rootCategoryId)
                            ->getPath();

        $collection = Mage::getModel('catalog/category')->setStoreId($store)
                            ->getCollection()
                            ->addAttributeToSelect('*')
                            ->addAttributeToFilter('path', array("like"=>$rootpath."/"."%"));

        //$collection = Mage::getResourceModel('catalog/category_collection');
        $collection->addAttributeToSelect('name')->addPathFilter('^1/[0-9/]+')->addAttributeToSelect('is_active')->load();
        $cats = array();
        
        foreach ($collection as $category) {
            $c = new stdClass();
            $c->label = $category->getName();
            $c->value = $category->getId();
            $c->level = $category->getLevel();
            $c->parentid = $category->getParentId();
            $c->active = $category->getIsActive();
            if( $c->active == 1 ){
                $cats[$c->value] = $c;
            }
        }

        foreach($cats as $id => $c){
            if (isset($cats[$c->parentid])){
                if (!isset($cats[$c->parentid]->child)){
                    $cats[$c->parentid]->child = array();
                }
                $cats[$c->parentid]->child[] =& $cats[$id];
            }
        }
        foreach($cats as $id => $c){
            if (!isset($cats[$c->parentid])){
                $stack = array($cats[$id]);
                while( count($stack)>0 ){
                    $opt = array_pop($stack);
                    $option = array(
                        'label' => ($opt->level>1 ? str_repeat('- - ', $opt->level-1) : '') . $opt->label,
                        'value' => $opt->value
                    );
                    array_push($options, $option);
                    if (isset($opt->child) && count($opt->child)){
                        foreach(array_reverse($opt->child) as $child){
                            array_push($stack, $child);
                        }
                    }
                }
            }
        }
        unset($cats);
        return $options;
        
    }
}*/
class Vt_Searchboxpro_Model_System_Config_Source_ListCategory
{
	public function toOptionArray($addEmpty = true)
    {
        $options = array();
               
        $store = Mage::app()->getStore()->getId();
        $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();

        $rootpath = Mage::getModel('catalog/category')
                            ->setStoreId($store)
                            ->load($rootCategoryId)
                            ->getPath();

        $collection = Mage::getModel('catalog/category')->setStoreId($store)
                            ->getCollection()
                            ->addAttributeToSelect('*')
                            ->addAttributeToFilter('path', array("like"=>$rootpath."/"."%"));

        $collection = Mage::getResourceModel('catalog/category_collection');
        $collection->addAttributeToSelect('name')->addPathFilter('^1/[0-9/]+')->addAttributeToSelect('is_active')->load();
              
        $cats = array();
        
        foreach ($collection as $category) {
            if($category->getIsActive()){
            	$c = new stdClass();
            	$c->label = $category->getName();
            	$c->value = $category->getId();
            	$c->level = $category->getLevel();
            	$c->parentid = $category->getParentId();
    			$c->active = $category->getIsActive();
    			if( $c->active == 1 ){
    				$cats[$c->value] = $c;
    			}
            }
        }

        foreach($cats as $id => $c){
        	if (isset($cats[$c->parentid])){
        		if (!isset($cats[$c->parentid]->child)){
        			$cats[$c->parentid]->child = array();
        		}
        		$cats[$c->parentid]->child[] =& $cats[$id];
        	}
        }
        foreach($cats as $id => $c){
        	if (!isset($cats[$c->parentid])){
        		$stack = array($cats[$id]);
        		while( count($stack)>0 ){
        			$opt = array_pop($stack);
        			$option = array(
		    			'label' => $opt->label,
		    			'value' => $opt->value,
                        'level' => $opt->level,
		    		);
        			array_push($options, $option);
        			if (isset($opt->child) && count($opt->child)){
        				foreach(array_reverse($opt->child) as $child){
        					array_push($stack, $child);
        				}
        			}
        		}
        	}
        }
        unset($cats);
        return $options;
		
    }
}
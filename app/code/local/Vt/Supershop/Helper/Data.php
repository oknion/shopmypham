<?php
/*------------------------------------------------------------------------
 # VT Supershop - Version 1.0
 # Copyright (c) 2014 The VnThemePro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: VnThemePro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/

class Vt_Supershop_Helper_Data extends Mage_Core_Helper_Abstract{

	public function __construct(){
		$this->defaults = array(
			/* general options */
			'layout_styles'				 => '1',
			'color'						 => 'default',
			'body_font_family'			 => 'Arial',
			'body_font_size'			 => '14px',
			'google_font'				 => 'Open Sans',
			'google_font_targets'		 => '',
			'direction'                  => '1',
			'body_link_color'			 => '#666',
			'body_link_hover_color'		 => '#666',
			'body_text_color'			 => '#666',
			'body_background_color'		 => '#ffffff',			
			'body_background_image'		 => '',
			'use_customize_image'		 => '',
			'background_customize_image' => '',
			'background_repeat'		     => '',			
			'background_position'		 => '',
			'menu_styles'                => '1',
			'menu_ontop'		         => '1',			
			'responsive_menu'		     => '3',			
			/* detail supershop */
			'show_imagezoom'		     => '',
			'zoom_mode'		 			 => '',
			'show_related' 				 => '',
			'related_number'		     => '',			
			'show_upsell'		 		 => '',
			'upsell_number'              => '',
			'show_customtab'		     => '',			
			'customtab_name'		     => '',
			'customtab_content'		     => '',			
			/* advanced */
			'show_cpanel'		     	 => '1',
			'use_ajaxcart'		 		 => '1',
			'show_addtocart' 			 => '1',
			'show_wishlist'		     	 => '1',			
			'show_compare'		 		 => '1',
			'show_quickview'             => '1',
			'custom_copyright'		     => '',			
			'copyright'		     		 => '',
			'custom_css'		     	 => '',	
			'custom_js'		     		 => '',			
		);
	}

	function get($attributes=array()){
		$data           = $this->defaults;
		$general        = Mage::getStoreConfig("supershop_cfg/general");
		$detail_supershop = Mage::getStoreConfig("supershop_cfg/detail_supershop");
		$social_supershop = Mage::getStoreConfig("supershop_cfg/social_supershop");
		$advanced 	    = Mage::getStoreConfig("supershop_cfg/advanced");
		if (!is_array($attributes)) {
			$attributes = array($attributes);
		}
		if (is_array($general))	
		$data = array_merge($data, $general);
		if (is_array($detail_supershop)) 				
		$data = array_merge($data, $detail_supershop);
		if (is_array($social_supershop)) 				
		$data = array_merge($data, $social_supershop);
		if (is_array($advanced)) 				
		$data = array_merge($data, $advanced);
		
		return array_merge($data, $attributes);
	}
	
}
	 
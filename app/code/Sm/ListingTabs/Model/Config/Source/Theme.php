<?php
/*------------------------------------------------------------------------
 # SM Listing Tabs - Version 2.2.1
 # Copyright (c) 2014 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\ListingTabs\Model\Config\Source;

class Theme implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => 'tabs', 'label' => __('Tabs')],
			['value' => 'deals', 'label' => __('Deals')]
		];
	}
}
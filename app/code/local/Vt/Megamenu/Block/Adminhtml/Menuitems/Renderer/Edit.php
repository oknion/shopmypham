<?php

class Vt_Megamenu_Block_Adminhtml_Menuitems_Renderer_Edit extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
	public function render(Varien_Object $row){
		return "<a style='text-decoration: none;' href='".$this->getUrl('*/*/edit', array('id' => $row->getId()))."' >".$row->getData('name')."</a>";
	}

}
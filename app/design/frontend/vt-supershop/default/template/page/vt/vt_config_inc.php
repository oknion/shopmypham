<?php
/*------------------------------------------------------------------------
 # Copyright (c) 2014 VnThemePro Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: VnThemePro Company
 # Websites: http://www.vnthemepro.com
-------------------------------------------------------------------------*/

/*--- BEGIN: Theme param config ---*/
$_params = new ThemeParameter();

$_config = array(	
	'layout_styles'					=>'1',
	'layout_home'					=>'1',
	'color'							=>'default',
	'body_font_family'				=>'arial',
	'body_font_size'				=>'14px',
	'google_font'	            	=>'Oswald',
	'google_font_targets'			=>'',
	'direction'                 	=>'1',
	'device_responsive'             =>'1',
	'body_link_color'				=>'#666',
	'body_link_hover_color'			=>'#FFC000',
	'body_text_color'				=>'#666',
	'body_background_color'			=>'#ffffff',
	'menu_styles'					=>'1',	
	'menu_ontop'					=>'1',	
	'show_cpanel'					=>'1',
	'responsive_menu'				=>'3',
);
$attributes = array();
if( Mage::getConfig()->getNode('modules/Vt_Supershop') ){
	$_config = Mage::helper('supershop/data')->get($attributes);
}
// Layout
$_params->set('layoutfb',$_config['layout_styles']);
$_params->set('menu_ontop',$_config['menu_ontop']);
// Theme color
$_params->set('theme_color',$_config['color']);
$_params->set('responsivemenu',$_config['responsive_menu']);
// font family
$_params->set('font_name',$_config['body_font_family']);
// Fontsize
$_params->set('fontsize',$_config['body_font_size']);
// Google web font
$_params->set('googleWebFont',$_config['google_font']);
// Google WebFont Targets
$_params->set('googleWebFontTargets',$_config['google_font_targets']);

// Body link color
$_params->set('linkcolor', $_config['body_link_color']);
// Body link color hover
$_params->set('linkcolorhover', $_config['body_link_hover_color']);
// Body text color
$_params->set('textcolor', $_config['body_text_color']);
// Body background-color
$_params->set('bgcolor', $_config['body_background_color']);
// Theme menu
if($_config['menu_styles'] ==1) {	$menu_style='mega';}	
if($_config['menu_styles'] ==2) {	$menu_style='css';}	
$_params->set('menutype',$menu_style);
// layout header
$_params->set('layout_home',$_config['layout_home']);
// Show cpanel
$_params->set('showCpanel',$_config['show_cpanel']);
// Array param for cookie
$paramscookie = array();
global $var_vttheme;
$var_vttheme = new VtTheme('vt_supershop', $_params, $paramscookie);

<?php
/**
 * @version     $Id: jiextensionmanager.php 013 2014-12-19 10:22:00Z Anton Wintergerst $
 * @package     JiExtensionManager for Joomla 1.7+
 * @copyright   Copyright (C) 2014 Jinfinity. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website     www.jinfinity.com
 * @email       support@jinfinity.com
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Load Stylesheets
if(version_compare(JVERSION, '3.0', 'ge')) {
    JHTML::stylesheet('media/jiframework/css/admin.css');
    JHTML::stylesheet('media/jiextensionmanager/css/admin.css');
} else {
    JHTML::_('stylesheet', 'admin.css', 'media/jiframework/css/');
    JHTML::_('stylesheet', 'admin.css', 'media/jiextensionmanager/css/');
}

// Load 1.7+ SubMenu Helpers
if(version_compare(JVERSION, '1.6.0', 'ge')) {
    JLoader::register('JiExtensionManagerHelper', dirname(__FILE__).'/helpers/jiextensionmanager.php');
}

// Build Controller
$controller = JControllerLegacy::getInstance('JiExtensionManager');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
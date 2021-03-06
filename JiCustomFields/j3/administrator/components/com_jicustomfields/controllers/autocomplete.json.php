<?php
/**
 * @version     $Id: autocomplete.json.php 051 2014-03-29 11:39:00Z Anton Wintergerst $
 * @package     JiCustomFields 2.1 Framework for Joomla
 * @copyright   Copyright (C) 2014 Jinfinity. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website     www.jinfinity.com
 * @email       support@jinfinity.com
 */

// No direct access 
defined( '_JEXEC' ) or die( 'Restricted access' );

if(!class_exists('JControllerLegacy')){
    class JControllerLegacy extends JView {
    }
}
class JiCustomFieldsControllerAutoComplete extends JControllerLegacy
{
    function __construct()
    {
        parent::__construct();
    }
    function field()
    {
        // Get the Application Object.
        $app = JFactory::getApplication();      
        // Set Page Header
        header('Content-Type: application/json;charset=UTF-8');     
        // Get Data
        $model = $this->getModel('autocomplete');
        $response = $model->fields();
        // Echo Data
        echo json_encode($response);
        // Close the Application.
        $app->close(); 
    }
}
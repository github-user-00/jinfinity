<?php
/**
 * @version     $Id: extensions.php 055 2014-12-30 12:48:00Z Anton Wintergerst $
 * @package     JiExtensionServer for Joomla 2.5-3.0
 * @copyright   Copyright (C) 2014 Jinfinity. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website     www.jinfinity.com
 * @email       support@jinfinity.com
 */
 
// No direct access 
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.modellist');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class JiExtensionServerModelExtensions extends JModelList
{
    /**
     * Constructor.
     *
     * @param	array	An optional associative array of configuration settings.
     * @see		JController
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'id',
                'title', 'title',
                'alias', 'alias',
                'publisher', 'publisher',
                'jversion', 'jversion',
                'subversion', 'subversion',
                'downloadhits', 'downloadhits',
                'updatehits', 'updatehits',
                'state', 'state',
                'publish_up', 'publish_up',
                'publish_down', 'publish_down',
                'ordering', 'ordering'
            );
        }

        parent::__construct($config);
    }
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return	void
     */
    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();

        // Adjust the context to support modal layouts.
        if ($layout = $app->input->get('layout'))
        {
            $this->context .= '.'.$layout;
        }

        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $context = $this->getUserStateFromRequest($this->context.'.filter.context', 'filter_context', '');
        $this->setState('filter.context', $context);

        // List state information.
        parent::populateState('title', 'asc');
    }
    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db		= $this->getDbo();
        $query	= $db->getQuery(true);
        $user	= JFactory::getUser();
        $app	= JFactory::getApplication();

        // Select the required fields from the table.
        $query->select('e.*');
        $query->from('#__jiextensions AS e');

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('e.state = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(e.state = 0 OR e.state = 1)');
        }
        // Filter by publisher
        if($context = $this->getState('filter.publisher')) {
            $query->where('e.publisher = ' .$db->quote($context));
        }
        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'e.id:') === 0) {
                $query->where('e.id = '.(int) substr($search, 3));
            } else {
                $search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('(`e.title` LIKE '.$search.')');
            }
        }
        // Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering', 'e.title');
        $orderDirn	= $this->state->get('list.direction', 'asc');

        $query->order($db->escape($orderCol.' '.$orderDirn));

        $query->select('(SELECT SUM(s.`downloadhits`) FROM #__jiextensions_subversions AS s WHERE s.eid=e.id) AS downloadhits');

        return $query;
    }
    /**
     * Method to get a list of jiextensions.
     *
     * @return	mixed	An array of data items on success, false on failure.
     */
    public function getItems()
    {
        $items	= parent::getItems();
        return $items;
    }
}
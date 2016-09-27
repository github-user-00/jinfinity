<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;
if(version_compare(JVERSION, '3.0.0', 'l')) {
/**
 * HTML utility class for building a dropdown menu
 *
 * @package     Joomla.Libraries
 * @subpackage  HTML
 * @since       3.0
 */
abstract class JHtmlDropdown
{
	/**
	 * @var    array  Array containing information for loaded files
	 * @since  3.0
	 */
	protected static $loaded = array();

	/**
	 * @var    string  HTML markup for the dropdown list
	 * @since  3.0
	 */
	protected static $dropDownList = null;

	/**
	 * Method to inject needed script
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function init()
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}

		// Depends on Bootstrap
		JHtml::_('bootstrap.framework');

		JFactory::getDocument()->addScriptDeclaration("
			(function($){
				$(document).ready(function (){
					$('.has-context')
					.mouseenter(function (){
						$('.btn-group',$(this)).show();
					})
					.mouseleave(function (){
						$('.btn-group',$(this)).hide();
						$('.btn-group',$(this)).removeClass('open');
					});

					contextAction =function (cbId, task) {
						$('input[name=\"cid[]\"]').removeAttr('checked');
						$('#' + cbId).attr('checked','checked');
						Joomla.submitbutton(task);
					}
				});
			})(jQuery);
			"
		);

		// Set static array
		self::$loaded[__METHOD__] = true;
		return;
	}

	/**
	 * Method to start a new dropdown menu
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function start()
	{
		// Only start once
		if (isset(self::$loaded[__METHOD__]) && self::$loaded[__METHOD__] == true)
		{
			return;
		}

		$dropDownList = '<div class="btn-group" style="margin-left:6px;display:none">
							<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-mini">
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">';
		self::$dropDownList = $dropDownList;
		self::$loaded[__METHOD__] = true;
		return;
	}

	/**
	 * Method to render current dropdown menu
	 *
	 * @return  string  HTML markup for the dropdown list
	 *
	 * @since   3.0
	 */
	public static function render()
	{
		$dropDownList = self::$dropDownList;
		$dropDownList .= '</ul></div>';
		self::$dropDownList	= null;
		self::$loaded['JHtmlDropdown::start'] = false;

		return $dropDownList;

	}

	/**
	 * Append an edit item to the current dropdown menu
	 *
	 * @param   integer  $id          Record ID
	 * @param   string   $prefix      Task prefix
	 * @param   string   $customLink  The custom link if dont use default Joomla action format
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function edit($id, $prefix = '', $customLink = '')
	{
		self::start();

		if (!$customLink)
		{
			$option = JFactory::getApplication()->input->getCmd('option');
			$link = 'index.php?option=' . $option;
		}
		else
		{
			$link = $customLink;
		}

		$link .= '&task=' . $prefix . 'edit&id=' . $id;
		$link = JRoute::_($link);

		self::addCustomItem(JText::_('JACTION_EDIT'), $link);
		return;
	}

	/**
	 * Append a publish item to the current dropdown menu
	 *
	 * @param   string  $checkboxId  ID of corresponding checkbox of the record
	 * @param   string  $prefix      The task prefix
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function publish($checkboxId, $prefix = '')
	{
		$task = $prefix . 'publish';
		self::addCustomItem(JText::_('JTOOLBAR_PUBLISH'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"');
		return;
	}

	/**
	 * Append an unpublish item to the current dropdown menu
	 *
	 * @param   string  $checkboxId  ID of corresponding checkbox of the record
	 * @param   string  $prefix      The task prefix
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function unpublish($checkboxId, $prefix = '')
	{
		$task = $prefix . 'unpublish';
		self::addCustomItem(JText::_('JTOOLBAR_UNPUBLISH'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"');
		return;
	}

	/**
	 * Append a featured item to the current dropdown menu
	 *
	 * @param   string  $checkboxId  ID of corresponding checkbox of the record
	 * @param   string  $prefix      The task prefix
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function featured($checkboxId, $prefix = '')
	{
		$task = $prefix . 'featured';
		self::addCustomItem(JText::_('JFEATURED'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"');
		return;
	}

	/**
	 * Append an unfeatured item to the current dropdown menu
	 *
	 * @param   string  $checkboxId  ID of corresponding checkbox of the record
	 * @param   string  $prefix      The task prefix
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function unfeatured($checkboxId, $prefix = '')
	{
		$task = $prefix . 'unfeatured';
		self::addCustomItem(JText::_('JUNFEATURED'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"');
		return;
	}

	/**
	 * Append an archive item to the current dropdown menu
	 *
	 * @param   string  $checkboxId  ID of corresponding checkbox of the record
	 * @param   string  $prefix      The task prefix
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function archive($checkboxId, $prefix = '')
	{
		$task = $prefix . 'archive';
		self::addCustomItem(JText::_('JTOOLBAR_ARCHIVE'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"');
		return;
	}

	/**
	 * Append an unarchive item to the current dropdown menu
	 *
	 * @param   string  $checkboxId  ID of corresponding checkbox of the record
	 * @param   string  $prefix      The task prefix
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function unarchive($checkboxId, $prefix = '')
	{
		$task = $prefix . 'unpublish';
		self::addCustomItem(JText::_('JTOOLBAR_UNARCHIVE'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"');
		return;
	}

	/**
	 * Append a trash item to the current dropdown menu
	 *
	 * @param   string  $checkboxId  ID of corresponding checkbox of the record
	 * @param   string  $prefix      The task prefix
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function trash($checkboxId, $prefix = '')
	{
		$task = $prefix . 'trash';
		self::addCustomItem(JText::_('JTOOLBAR_TRASH'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"');
		return;
	}

	/**
	 * Append an untrash item to the current dropdown menu
	 *
	 * @param   string  $checkboxId  ID of corresponding checkbox of the record
	 * @param   string  $prefix      The task prefix
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function untrash($checkboxId, $prefix = '')
	{
		$task = $prefix . 'publish';
		self::addCustomItem(JText::_('JTOOLBAR_UNTRASH'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"');
		return;
	}

	/**
	 * Append a checkin item to the current dropdown menu
	 *
	 * @param   string  $checkboxId  ID of corresponding checkbox of the record
	 * @param   string  $prefix      The task prefix
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function checkin($checkboxId, $prefix = '')
	{
		$task = $prefix . 'checkin';
		self::addCustomItem(JText::_('JTOOLBAR_CHECKIN'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"');
		return;
	}

	/**
	 * Writes a divider between dropdown items
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function divider()
	{
		self::$dropDownList .= '<li class="divider"></li>';
		return;
	}

	/**
	 * Append a custom item to current dropdown menu
	 *
	 * @param   string   $label           The label of item
	 * @param   string   $link            The link of item
	 * @param   string   $linkAttributes  Custom link attributes
	 * @param   string   $className       Class name of item
	 * @param   boolean  $ajaxLoad        True if using ajax load when item clicked
	 * @param   string   $jsCallBackFunc  Javascript function name, called when ajax load successfully
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function addCustomItem($label, $link = 'javascript:void(0)', $linkAttributes = '', $className = '', $ajaxLoad = false,
		$jsCallBackFunc = null)
	{
		self::start();

		if ($ajaxLoad)
		{
			$href = ' href = "javascript:void(0)" onclick="loadAjax(\'' . $link . '\', \'' . $jsCallBackFunc . '\')"';
		}
		else
		{
			$href = ' href = "' . $link . '" ';
		}

		$dropDownList = self::$dropDownList;
		$dropDownList .= '<li class="' . $className . '"><a ' . $linkAttributes . $href . ' >';
		$dropDownList .= $label;
		$dropDownList .= '</a></li>';
		self::$dropDownList = $dropDownList;
		return;
	}
}
}
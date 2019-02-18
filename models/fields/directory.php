<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Supports a one line text field.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since       11.1
 */
class JFormFieldDirectory extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'Directory';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Get the label text button from the XML element, defaulting to the element name.
		$button = $this->element['button'] ? (string) $this->element['button'] : (string) $this->element['name'];
		$button = $this->translateLabel ? JText::_($button) : $button;

		return '<input type="text" name="' . $this->name . '" id="directory"' . ' value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"/><a href="#" id="add_playlist" onclick="Joomla.submitbutton(\'module.apply\')">' . $button . '</a>';
	}
}

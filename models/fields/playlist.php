<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

require_once(JPATH_ROOT.'/modules/mod_jlplayer2/helper.php');

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Platform.
 * Displays options as a list of check boxes.
 * Multiselect may be forced to be true.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @see         JFormFieldCheckbox
 * @since       11.1
 */
class JFormFieldPlayList extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'PlayList';

	/**
	 * Flag to tell the field to always be in multiple values mode.
	 *
	 * @var    boolean
	 * @since  11.1
	 */
	protected $forceMultiple = true;	
	
	protected function getInput()
	{
		$name			= $this->element['directory'];
		$directory 		= $this->form->getFieldset();
		$music_dir 		= $this->hasValue($directory, $name);
        $music_dir = (!empty($music_dir)) ? $music_dir : 'music';
		$dir = JPATH_ROOT ."/" . $music_dir;

        if(!JFolder::exists($dir))
        {
            JFolder::create($dir);
        }

        if(JFolder::exists($dir))
        {
            $sounds = modJlplayer2Helper::playListgGen($music_dir, JURI::base(), 1, null, 0);
        }

        $class = '';
		// Initialize variables.
		$html = array();

		$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

		// Get the field options.
		if(isset($sounds)) 
		{
			$options = array();

			foreach ($sounds as $sound)
			{
				// Create a new option object based on the <option /> element.
				$tmp = JHtml::_('select.option', (string) $sound['name'], trim((string) $sound['name']), 'value', 'text');

				// Add the option object to the result set.
				$options[] = $tmp;
			}

			// Build the checkbox field output.
			$html[] = '<ul>';
			foreach ($options as $i => $option)
			{
				$checked = (in_array((string) $option->value, (array) $this->value)) ? ' checked="checked"' : '';
				$html[] = '<li><label><input type="checkbox" name="' . $this->name . '"' . ' value="' . $option->value . '"' . $checked . '/>' . JText::_($option->text) . '</label></li>';
			}
			$html[] = '</ul>';
			$html[] = '<div class="sellinks"><a class="select_check" href="javascript:void();" onclick="javascript:checkAll(\'adminForm\', true);">' . JText::_('MOD_JLP_BTN_SELECT') . '</a> / <a href="javascript:void();" class="select_check" onclick="javascript:checkAll(\'adminForm\', false);">' . JText::_('MOD_JLP_BTN_UNSELECT') . '</a></div>';
		} else {
			$html[] = '<div class="error">is not a directory or empty!</div>';
		}

		// End the checkbox field output.
		$html[] = '</fieldset>';

		return implode($html);
	}
	
	// get directory value
	public function hasValue($fildset = array(), $name) 
	{
        $directory = '';
		foreach($fildset as $field) 
		{
		   if ( $field->name == $this->form->getFormControl() . '[params][' . $name . ']' )
           {
               $directory = $field->value;
               break;
           }
		}
		return $directory;
	}
}

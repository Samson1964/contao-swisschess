<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package   swisschess
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */

/**
 * Paletten
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['swisschess'] = '{type_legend},type,headline;{swtsource_legend},swt_file;{protected_legend:hide},protected;{expert_legend:hide},guest,cssID,space;{invisible_legend:hide},invisible,start,stop';

/**
 * Felder
 */
 
/**
 * Auswahl der SwissChess-Turnierdatei. Der alte SQL-Typ mit varchar(255) funktioniert nicht mehr:
 * Die ausgewählte Datei wird dann einfach beim Speichern rausgeworfen.
 * http://www.contao.glen-langer.de/das-datenbank-gestuetzte-filesystem.html
 * https://github.com/contao/core/issues/6459
 * https://github.com/contao/core/issues/6729
 */
 
$GLOBALS['TL_DCA']['tl_content']['fields']['swt_file'] = array (
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['swt_file'],
	'exclude'                 => true,
	'inputType'               => 'fileTree',
	'eval'                    => array (
		'filesOnly'  => true, 
		'files'      => true,
		'fieldType'  => 'radio', 
		'mandatory'  => true, 
		'extensions' => 'swt',
		'tl_class'   => 'clr'
	),
	'sql'                     => "binary(16) NULL"
);

?>

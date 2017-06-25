<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Swisschess
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'SwissChess' => 'system/modules/swisschess/classes/SwissChess.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_swisschess' => 'system/modules/swisschess/templates',
));

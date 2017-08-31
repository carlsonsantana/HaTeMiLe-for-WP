<?php
/*
  Plugin Name: HaTeMiLe for WP
  Description: HaTeMiLe for WP improve accessibility web pages, converting pages with HTML code into pages with a more accessible code.
  Version: 1.0
  Author: Carlson Santana Cruz
  License: Apache License, Version 2.0
  License URI: http://www.apache.org/licenses/LICENSE-2.0
*/

/**
 * Execute the HaTeMiLe by buffer of PHP.
 */
function executeHatemileByBuffer() {
	ob_start('executeHatemile');
}

/**
 * Execute the HaTeMiLe.
 * @param string $html The HTML code of page.
 * @return string The new HTML code of page.
 */
function executeHatemile($html) {
	if (!is_admin()) {
		try {
			$hatemilePath = plugin_dir_path(__FILE__) . 'hatemile_for_php/hatemile/';
			
			echo plugin_dir_path(__FILE__) . 'hatemile-configure.xml';

			require_once plugin_dir_path(__FILE__) . 'phpQuery/phpQuery/phpQuery.php';
			require_once $hatemilePath . 'util/Configure.php';
			require_once $hatemilePath . 'util/phpquery/phpQueryHTMLDOMParser.php';
			require_once $hatemilePath . 'implementation/AccessibleEventImplementation.php';
			require_once $hatemilePath . 'implementation/AccessibleFormImplementation.php';
			require_once $hatemilePath . 'implementation/AccessibleImageImplementation.php';
			require_once $hatemilePath . 'implementation/AccessibleSelectorImplementation.php';
			require_once $hatemilePath . 'implementation/AccessibleNavigationImplementation.php';
			require_once $hatemilePath . 'implementation/AccessibleTableImplementation.php';

			$parser = new hatemile\util\phpquery\phpQueryHTMLDOMParser($html);
			$configure = new hatemile\util\Configure(plugin_dir_path(__FILE__) . 'hatemile-configure.xml');
			
			$commonElements = $parser->createElement('link');
			$commonElements->setAttribute('rel', 'stylesheet');
			$commonElements->setAttribute('type', 'text/css');
			$commonElements->setAttribute('href', get_site_url() . '/wp-content/plugins/hatemile_for_wp/hatemile_for_php/css/common_elements.css');
			$parser->find('head')->firstResult()->appendElement($commonElements);
			
			$accessibleEvent = new hatemile\implementation\AccessibleEventImplementation($parser, $configure);
			$accessibleEvent->fixDragsandDrops();
			$accessibleEvent->fixActives();
			$accessibleEvent->fixHovers();

			$accessibleForm = new hatemile\implementation\AccessibleFormImplementation($parser, $configure);
			$accessibleForm->fixRequiredFields();
			$accessibleForm->fixRangeFields();
			$accessibleForm->fixLabels();
			$accessibleForm->fixAutoCompleteFields();

			$accessibleImage = new hatemile\implementation\AccessibleImageImplementation($parser, $configure);
			$accessibleImage->fixLongDescriptions();

			$accessibleSelector = new hatemile\implementation\AccessibleSelectorImplementation($parser, $configure);
			$accessibleSelector->fixSelectors();

			$accessibleShortcut = new hatemile\implementation\AccessibleNavigationImplementation($parser, $configure, $_SERVER['HTTP_USER_AGENT']);
			$accessibleShortcut->fixShortcuts();
			$accessibleShortcut->fixHeadings();
			$accessibleShortcut->fixSkippers();

			$accessibleTable = new hatemile\implementation\AccessibleTableImplementation($parser, $configure);
			$accessibleTable->fixAssociationCellsTables();

			return $parser->getHTML();
		} catch (Exception $e) {
			return $html;
		}
	}
	return $html;
}

add_action('init', 'executeHatemileByBuffer');
add_filter('shutdown', 'ob_end_flush');
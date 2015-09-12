<?php
/*
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
 */

namespace hatemile;

require_once dirname(__FILE__) . '/util/HTMLDOMElement.php';

use \hatemile\util\HTMLDOMElement;

/**
 * The AccessibleForm interface fixes accessibility problems associated
 * with forms.
 */
interface AccessibleForm {
	
	/**
	 * Display that the field is required.
	 * @param \hatemile\util\HTMLDOMElement $requiredField The required field.
	 */
	public function fixRequiredField(HTMLDOMElement $requiredField);
	
	/**
	 * Display that the fields is required.
	 */
	public function fixRequiredFields();
	
	/**
	 * Display that the field have range.
	 * @param \hatemile\util\HTMLDOMElement $rangeField The range field.
	 */
	public function fixRangeField(HTMLDOMElement $rangeField);
	
	/**
	 * Display that the fields have range.
	 */
	public function fixRangeFields();
	
	/**
	 * Display that the field have autocomplete.
	 * @param \hatemile\util\HTMLDOMElement $autoCompleteField The field with
	 * autocomplete.
	 */
	public function fixAutoCompleteField(HTMLDOMElement $autoCompleteField);
	
	/**
	 * Display that the fields have autocomplete.
	 */
	public function fixAutoCompleteFields();
	
	/**
	 * Associate label with field.
	 * @param \hatemile\util\HTMLDOMElement $label The label.
	 */
	public function fixLabel(HTMLDOMElement $label);
	
	/**
	 * Associate labels with fields.
	 */
	public function fixLabels();
}
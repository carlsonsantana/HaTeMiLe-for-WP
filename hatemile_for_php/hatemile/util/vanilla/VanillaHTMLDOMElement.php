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

namespace hatemile\util\vanilla;

require_once dirname(__FILE__) . '/../HTMLDOMElement.php';

use \hatemile\util\HTMLDOMElement;

/**
 * The VanillaHTMLDOMElement class is official implementation of HTMLDOMElement
 * interface for the DOMElement.
 */
class VanillaHTMLDOMElement implements HTMLDOMElement {
	
	/**
	 * The DOMElement native element encapsulated.
	 * @var \DOMElement
	 */
	protected $element;

	/**
	 * Initializes a new object that encapsulate the DOMElement.
	 * @param \DOMElement $element The DOMElement.
	 */
	public function __construct(\DOMElement $element) {
		$this->element = $element;
	}
	
	public function getTagName() {
		return strtoupper($this->element->tagName);
	}
	
	public function getAttribute($name) {
		return $this->element->getAttribute($name);
	}
	
	public function setAttribute($name, $value) {
		$this->element->setAttribute($name, $value);
	}
	
	public function removeAttribute($name) {
		if ($this->hasAttribute($name)) {
			$this->element->removeAttribute($name);
		}
	}
	
	public function hasAttribute($name) {
		return $this->element->hasAttribute($name);
	}
	
	public function hasAttributes() {
		return $this->element->hasAttributes();
	}
	
	public function getTextContent() {
		return $this->element->textContent;
	}
	
	public function insertBefore(HTMLDOMElement $newElement) {
		$this->getParentElement()->getData()->insertBefore($newElement->getData(), $this->element);
		return $newElement;
	}
	
	public function insertAfter(HTMLDOMElement $newElement) {
		$children = $this->getParentElement()->getData()->childNodes;
		$found = false;
		$added = false;
		for ($i = 0, $length = sizeof($children); $i < $length; $i++) {
			$child = new VanillaHTMLDOMElement($children[$i]);
			if ($found) {
				$child->getParentElement()->getData()->insertBefore($newElement->getData(), $child);
				$added = true;
				break;
			} else if ($child->getData() === $this->element) {
				$found = true;
			}
		}
		if (!$added) {
			$this->getParentElement()->appendElement($newElement);
		}
		return $newElement;
	}
	
	public function removeElement() {
		$this->getParentElement()->getData()->removeChild($this->element);
		return $this;
	}
	
	public function replaceElement(HTMLDOMElement $newElement) {
		$this->getParentElement()->getData()->replaceChild($newElement->getData(), $this->element);
		return $newElement;
	}
	
	public function appendElement(HTMLDOMElement $element) {
		$this->element->appendChild($element->getData());
		return $element;
	}
	
	public function getChildren() {
		$children = $this->element->childNodes;
		$elements = array();
		foreach ($children as $child) {
			if ($child instanceof \DOMElement) {
				array_push($elements, new VanillaHTMLDOMElement($child));
			}
		}
		return $elements;
	}
	
	public function appendText($text) {
		$this->element->appendChild(new \DOMText($text));
	}
	
	public function hasChildren() {
		$children = $this->element->childNodes;
		foreach ($children as $child) {
			if ($child instanceof \DOMElement) {
				return true;
			}
		}
		return false;
	}
	
	public function getParentElement() {
		if (empty($this->element->parentNode)) {
			return null;
		}
		return new VanillaHTMLDOMElement($this->element->parentNode);
	}
	
	public function getInnerHTML() {
		$innerHTML = '';
		$children = $this->element->childNodes;
		foreach ($children as $child) {
			$innerHTML .= $child->ownerDocument->saveXML($child);
		}
		return $innerHTML;
	}
	
	public function setInnerHTML($html) {
		$originalChildren = $this->element->childNodes;
		foreach ($originalChildren as $child) {
			$this->element->removeChild($child);
		}
		
		$DOMInnerHTML = new \DOMDocument();
		$DOMInnerHTML->loadHTML('<!DOCTYPE html><html>' . $html . '</html>');
		$children = $DOMInnerHTML->getElementsByTagName('html')->item(0)->childNodes;
		
		foreach ($children as $child) {
			$this->element->ownerDocument->importNode($child, true);
			$this->element->appendChild($child);
		}
	}
	
	public function getOuterHTML() {
		return $this->element->ownerDocument->saveXML($this->element);
	}
	
	public function getData() {
		return $this->element;
	}
	
	public function setData($data) {
		$this->element = $data;
	}
	
	public function cloneElement() {
		return new VanillaHTMLDOMElement($this->element->cloneNode(true));
	}
	
	public function getFirstElementChild() {
		$children = $this->element->childNodes;
		foreach ($children as $child) {
			if ($child instanceof \DOMElement) {
				return new VanillaHTMLDOMElement($child);
			}
		}
		return null;
	}
	
	public function getLastElementChild() {
		$children = $this->element->childNodes;
		foreach ($children as $child) {
			if ($child instanceof \DOMElement) {
				$result = $this->element;
			}
		}
		if ($result != null) {
			return new VanillaHTMLDOMElement($result);
		}
		return null;
	}
}
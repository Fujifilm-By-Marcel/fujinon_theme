'use strict';

/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function () {
	var siteNavigation = document.getElementById('site-navigation');

	// Return early if the navigation don't exist.
	if (!siteNavigation) {
		return;
	}

	var button = siteNavigation.getElementsByTagName('button')[0];

	// Return early if the button don't exist.
	if ('undefined' === typeof button) {
		return;
	}

	var menu = siteNavigation.getElementsByTagName('ul')[0];

	// Hide menu toggle button if menu is empty and return early.
	if ('undefined' === typeof menu) {
		button.style.display = 'none';
		return;
	}

	if (!menu.classList.contains('nav-menu')) {
		menu.classList.add('nav-menu');
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener('click', function () {
		siteNavigation.classList.toggle('toggled');

		if (button.getAttribute('aria-expanded') === 'true') {
			button.setAttribute('aria-expanded', 'false');
		} else {
			button.setAttribute('aria-expanded', 'true');
		}
	});

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener('click', function (event) {
		var isClickInside = siteNavigation.contains(event.target);

		if (!isClickInside) {
			siteNavigation.classList.remove('toggled');
			button.setAttribute('aria-expanded', 'false');
		}
	});

	// Get all the link elements within the menu.
	var links = menu.getElementsByTagName('a');

	// Get all the link elements with children within the menu.
	var linksWithChildren = menu.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');

	// Toggle focus each time a menu link is focused or blurred.
	var _iteratorNormalCompletion = true;
	var _didIteratorError = false;
	var _iteratorError = undefined;

	try {
		for (var _iterator = links[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
			var link = _step.value;

			link.addEventListener('focus', toggleFocus, true);
			link.addEventListener('blur', toggleFocus, true);
		}

		// Toggle focus each time a menu link with children receive a touch event.
	} catch (err) {
		_didIteratorError = true;
		_iteratorError = err;
	} finally {
		try {
			if (!_iteratorNormalCompletion && _iterator.return) {
				_iterator.return();
			}
		} finally {
			if (_didIteratorError) {
				throw _iteratorError;
			}
		}
	}

	var _iteratorNormalCompletion2 = true;
	var _didIteratorError2 = false;
	var _iteratorError2 = undefined;

	try {
		for (var _iterator2 = linksWithChildren[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
			var _link = _step2.value;

			_link.addEventListener('touchstart', toggleFocus, false);
		}

		/**
   * Sets or removes .focus class on an element.
   */
	} catch (err) {
		_didIteratorError2 = true;
		_iteratorError2 = err;
	} finally {
		try {
			if (!_iteratorNormalCompletion2 && _iterator2.return) {
				_iterator2.return();
			}
		} finally {
			if (_didIteratorError2) {
				throw _iteratorError2;
			}
		}
	}

	function toggleFocus() {
		if (event.type === 'focus' || event.type === 'blur') {
			var self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while (!self.classList.contains('nav-menu')) {
				// On li elements toggle the class .focus.
				if ('li' === self.tagName.toLowerCase()) {
					self.classList.toggle('focus');
				}
				self = self.parentNode;
			}
		}

		if (event.type === 'touchstart') {
			var menuItem = this.parentNode;
			event.preventDefault();
			var _iteratorNormalCompletion3 = true;
			var _didIteratorError3 = false;
			var _iteratorError3 = undefined;

			try {
				for (var _iterator3 = menuItem.parentNode.children[Symbol.iterator](), _step3; !(_iteratorNormalCompletion3 = (_step3 = _iterator3.next()).done); _iteratorNormalCompletion3 = true) {
					var _link2 = _step3.value;

					if (menuItem !== _link2) {
						_link2.classList.remove('focus');
					}
				}
			} catch (err) {
				_didIteratorError3 = true;
				_iteratorError3 = err;
			} finally {
				try {
					if (!_iteratorNormalCompletion3 && _iterator3.return) {
						_iterator3.return();
					}
				} finally {
					if (_didIteratorError3) {
						throw _iteratorError3;
					}
				}
			}

			menuItem.classList.toggle('focus');
		}
	}

	// When the user scrolls the page, execute myFunction
	window.onscroll = function () {
		stickyHeader();
	};

	// Get the header
	var header = document.getElementById("masthead");
	var product_nav = document.getElementsByClassName("product-nav")[0];

	// Get the offset position of the navbar
	var sticky = header.offsetTop;

	// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
	function stickyHeader() {
		if (window.pageYOffset > sticky) {
			header.classList.add("sticky");
			product_nav.classList.add("sticky");
		} else {
			header.classList.remove("sticky");
			product_nav.classList.remove("sticky");
		}
	}

	stickyHeader();
})();
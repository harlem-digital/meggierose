/*
Theme Name: Memorabella
Author: Wyatt Meade
Author URI: http://wyattmeade.com
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

var menuBtn = document.getElementsByClassName('menu-button')[0];
var header = document.getElementById('header');

var menuSearchBtn = document.getElementsByClassName('menu-search')[0];
menuSearchBtn = menuSearchBtn.getElementsByTagName('a')[0];

var menuCancelBtn = document.getElementsByClassName('form-cancel-icon')[0];
var form = document.getElementById('form');

var toggleSearch = function() {
	formClasses = form.getAttribute('class');

	if(formClasses) {
		form.removeAttribute('class');
	} else {
		form.setAttribute('class', 'open');
	}

	return false;
}

var toggleMenu = function() {

	headerOpen = header.getAttribute('data-menu');

	if(headerOpen) {
		header.removeAttribute('data-menu');
	} else {
		header.setAttribute('data-menu', 'open');
	}

	return false;
}

menuBtn.addEventListener('click', toggleMenu);
menuSearchBtn.addEventListener('click', toggleSearch);
menuCancelBtn.addEventListener('click', toggleSearch);
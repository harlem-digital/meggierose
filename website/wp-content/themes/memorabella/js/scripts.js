/*
Theme Name: Memorabella
Author: Wyatt Meade
Author URI: http://wyattmeade.com
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

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

menuSearchBtn.addEventListener('click', toggleSearch);
menuCancelBtn.addEventListener('click', toggleSearch);
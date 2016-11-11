webpackJsonp([2],[
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(jQuery) {'use strict';
	
	var _breakpoints = __webpack_require__(3);
	
	var _breakpoints2 = _interopRequireDefault(_breakpoints);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	__webpack_require__(4);
	window.$ = jQuery;
	window.jQuery = jQuery;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(1)))

/***/ },
/* 1 */,
/* 2 */,
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function($) {'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	var breakpoints = {
	  current_breakpoint: function current_breakpoint() {
	    var breakpoint = window.getComputedStyle($('#breakpoint-detector'), ':before').getPropertyValue('content');
	    return breakpoint.replace(/'/g, "").replace(/"/g, ""); // Remove quotes returned by breakpoint query
	  }
	};
	
	exports.default = breakpoints;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(1)))

/***/ },
/* 4 */
/***/ function(module, exports) {

	// removed by extract-text-webpack-plugin

/***/ }
]);
//# sourceMappingURL=main.js.map
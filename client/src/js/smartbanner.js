/*!
 * smartbanner.js v1.10.0 <https://github.com/ain/smartbanner.js>
 * Copyright © 2018 Ain Tohvri, contributors. Licensed under GPL-3.0.
 */
/* eslint-disable */

(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
    'use strict';
    
    Object.defineProperty(exports, "__esModule", {
      value: true
    });
    
    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
    
    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
    
    var Bakery = function () {
      function Bakery() {
        _classCallCheck(this, Bakery);
      }
    
      _createClass(Bakery, null, [{
        key: 'getCookieExpiresString',
        value: function getCookieExpiresString(hideTtl) {
          var now = new Date();
          var expireTime = new Date(now.getTime() + hideTtl);
          return 'expires=' + expireTime.toGMTString() + ';';
        }
      }, {
        key: 'getPathString',
        value: function getPathString(path) {
          return 'path=' + path + ';';
        }
      }, {
        key: 'bake',
        value: function bake(hideTtl) {
          var hidePath = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    
          document.cookie = 'smartbanner_exited=1; ' + (hideTtl ? Bakery.getCookieExpiresString(hideTtl) : '') + ' ' + (hidePath ? Bakery.getPathString(hidePath) : '');
        }
      }, {
        key: 'unbake',
        value: function unbake() {
          document.cookie = 'smartbanner_exited=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }
      }, {
        key: 'baked',
        get: function get() {
          var value = document.cookie.replace(/(?:(?:^|.*;\s*)smartbanner_exited\s*\=\s*([^;]*).*$)|^.*$/, '$1');
          return value === '1';
        }
      }]);
    
      return Bakery;
    }();
    
    exports.default = Bakery;
    
    },{}],2:[function(require,module,exports){
    (function (global){
    'use strict';
    
    Object.defineProperty(exports, "__esModule", {
      value: true
    });
    
    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
    
    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
    
    var Detector = function () {
      function Detector() {
        _classCallCheck(this, Detector);
      }
    
      _createClass(Detector, null, [{
        key: 'platform',
        value: function platform() {
          if (/iPhone|iPad|iPod/i.test(window.navigator.userAgent)) {
            return 'ios';
          } else if (/Android/i.test(window.navigator.userAgent)) {
            return 'android';
          }
        }
      }, {
        key: 'userAgentMatchesRegex',
        value: function userAgentMatchesRegex(regexString) {
          return new RegExp(regexString).test(window.navigator.userAgent);
        }
      }, {
        key: 'jQueryMobilePage',
        value: function jQueryMobilePage() {
          return typeof global.$ !== 'undefined' && global.$.mobile !== 'undefined' && document.querySelector('.ui-page') !== null;
        }
      }, {
        key: 'wrapperElement',
        value: function wrapperElement() {
          var selector = Detector.jQueryMobilePage() ? '.ui-page' : 'html';
          return document.querySelectorAll(selector);
        }
      }]);
    
      return Detector;
    }();
    
    exports.default = Detector;
    
    }).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
    },{}],3:[function(require,module,exports){
    'use strict';
    
    var _smartbanner = require('./smartbanner.js');
    
    var _smartbanner2 = _interopRequireDefault(_smartbanner);
    
    function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
    
    var smartbanner = void 0;
    
    window.addEventListener('load', function () {
      smartbanner = new _smartbanner2.default();
      smartbanner.publish();
    });
    
    },{"./smartbanner.js":5}],4:[function(require,module,exports){
    'use strict';
    
    Object.defineProperty(exports, "__esModule", {
      value: true
    });
    
    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
    
    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
    
    function valid(name) {
      // TODO: validate against options dictionary
      return name.indexOf('smartbanner:') !== -1 && name.split(':')[1].length > 0;
    }
    
    function convertToCamelCase(name) {
      var parts = name.split('-');
      parts.map(function (part, index) {
        if (index > 0) {
          parts[index] = part.charAt(0).toUpperCase() + part.substring(1);
        }
      });
      return parts.join('');
    }
    
    var OptionParser = function () {
      function OptionParser() {
        _classCallCheck(this, OptionParser);
      }
    
      _createClass(OptionParser, [{
        key: 'parse',
        value: function parse() {
          var metas = document.getElementsByTagName('meta');
          var options = {};
          var optionName = null;
          Array.from(metas).forEach(function (meta) {
            var name = meta.getAttribute('name');
            var content = meta.getAttribute('content');
            if (name && content && valid(name) && content.length > 0) {
              optionName = name.split(':')[1];
              if (Array.from(optionName).includes('-')) {
                optionName = convertToCamelCase(optionName);
              }
              options[optionName] = content;
            }
          });
          return options;
        }
      }]);
    
      return OptionParser;
    }();
    
    exports.default = OptionParser;
    
    },{}],5:[function(require,module,exports){
    'use strict';
    
    Object.defineProperty(exports, "__esModule", {
      value: true
    });
    
    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
    
    var _optionparser = require('./optionparser.js');
    
    var _optionparser2 = _interopRequireDefault(_optionparser);
    
    var _detector = require('./detector.js');
    
    var _detector2 = _interopRequireDefault(_detector);
    
    var _bakery = require('./bakery.js');
    
    var _bakery2 = _interopRequireDefault(_bakery);
    
    function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
    
    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
    
    var DEFAULT_PLATFORMS = 'android,ios';
    
    var datas = {
      originalTop: 'data-smartbanner-original-top',
      originalMarginTop: 'data-smartbanner-original-margin-top'
    };
    
    function handleExitClick(event, self) {
      self.exit();
      event.preventDefault();
    }
    
    function handleJQueryMobilePageLoad(event) {
      if (!this.positioningDisabled) {
        setContentPosition(event.data.height);
      }
    }
    
    function addEventListeners(self) {
      var closeIcon = document.querySelector('.js_smartbanner__exit');
      closeIcon.addEventListener('click', function (event) {
        return handleExitClick(event, self);
      });
      if (_detector2.default.jQueryMobilePage()) {
        $(document).on('pagebeforeshow', self, handleJQueryMobilePageLoad);
      }
    }
    
    function removeEventListeners() {
      if (_detector2.default.jQueryMobilePage()) {
        $(document).off('pagebeforeshow', handleJQueryMobilePageLoad);
      }
    }
    
    function setContentPosition(value) {
      var wrappers = _detector2.default.wrapperElement();
      for (var i = 0, l = wrappers.length, wrapper; i < l; i++) {
        wrapper = wrappers[i];
        if (_detector2.default.jQueryMobilePage()) {
          if (wrapper.getAttribute(datas.originalTop)) {
            continue;
          }
          var top = parseFloat(getComputedStyle(wrapper).top);
          wrapper.setAttribute(datas.originalTop, isNaN(top) ? 0 : top);
          wrapper.style.top = value + 'px';
        } else {
          if (wrapper.getAttribute(datas.originalMarginTop)) {
            continue;
          }
          var margin = parseFloat(getComputedStyle(wrapper).marginTop);
          wrapper.setAttribute(datas.originalMarginTop, isNaN(margin) ? 0 : margin);
          wrapper.style.marginTop = value + 'px';
        }
      }
    }
    
    function restoreContentPosition() {
      var wrappers = _detector2.default.wrapperElement();
      for (var i = 0, l = wrappers.length, wrapper; i < l; i++) {
        wrapper = wrappers[i];
        if (_detector2.default.jQueryMobilePage() && wrapper.getAttribute(datas.originalTop)) {
          wrapper.style.top = wrapper.getAttribute(datas.originalTop) + 'px';
        } else if (wrapper.getAttribute(datas.originalMarginTop)) {
          wrapper.style.marginTop = wrapper.getAttribute(datas.originalMarginTop) + 'px';
        }
      }
    }
    
    var SmartBanner = function () {
      function SmartBanner() {
        _classCallCheck(this, SmartBanner);
    
        var parser = new _optionparser2.default();
        this.options = parser.parse();
        this.platform = _detector2.default.platform();
      }
    
      // DEPRECATED. Will be removed.
    
    
      _createClass(SmartBanner, [{
        key: 'publish',
        value: function publish() {
          if (Object.keys(this.options).length === 0) {
            throw new Error('No options detected. Please consult documentation.');
          }
    
          if (_bakery2.default.baked) {
            return false;
          }
    
          // User Agent was explicetely excluded by defined excludeUserAgentRegex
          if (this.userAgentExcluded) {
            return false;
          }
    
          // User agent was neither included by platformEnabled,
          // nor by defined includeUserAgentRegex
          if (!(this.platformEnabled || this.userAgentIncluded)) {
            return false;
          }
    
          var bannerDiv = document.createElement('div');
          document.querySelector('body').appendChild(bannerDiv);
          bannerDiv.outerHTML = this.html;
          if (!this.positioningDisabled) {
            setContentPosition(this.height);
          }
          addEventListeners(this);
        }
      }, {
        key: 'exit',
        value: function exit() {
          removeEventListeners();
          if (!this.positioningDisabled) {
            restoreContentPosition();
          }
          var banner = document.querySelector('.js_smartbanner');
          document.querySelector('body').removeChild(banner);
          _bakery2.default.bake(this.hideTtl, this.hidePath);
        }
      }, {
        key: 'originalTop',
        get: function get() {
          var wrapper = _detector2.default.wrapperElement()[0];
          return parseFloat(wrapper.getAttribute(datas.originalTop));
        }
    
        // DEPRECATED. Will be removed.
    
      }, {
        key: 'originalTopMargin',
        get: function get() {
          var wrapper = _detector2.default.wrapperElement()[0];
          return parseFloat(wrapper.getAttribute(datas.originalMarginTop));
        }
      }, {
        key: 'priceSuffix',
        get: function get() {
          if (this.platform === 'ios') {
            return this.options.priceSuffixApple;
          } else if (this.platform === 'android') {
            return this.options.priceSuffixGoogle;
          }
          return '';
        }
      }, {
        key: 'icon',
        get: function get() {
          if (this.platform === 'android') {
            return this.options.iconGoogle;
          } else {
            return this.options.iconApple;
          }
        }
      }, {
        key: 'buttonUrl',
        get: function get() {
          if (this.platform === 'android') {
            return this.options.buttonUrlGoogle;
          } else if (this.platform === 'ios') {
            return this.options.buttonUrlApple;
          }
          return '#';
        }
      }, {
        key: 'html',
        get: function get() {
          var modifier = !this.options.customDesignModifier ? this.platform : this.options.customDesignModifier;
          return '<div class="smartbanner smartbanner--' + modifier + ' js_smartbanner">\n      <a href="javascript:void();" class="smartbanner__exit js_smartbanner__exit"></a>\n      <div class="smartbanner__icon" style="background-image: url(' + this.icon + ');"></div>\n      <div class="smartbanner__info">\n        <div>\n          <div class="smartbanner__info__title">' + this.options.title + '</div>\n          <div class="smartbanner__info__author">' + this.options.author + '</div>\n          <div class="smartbanner__info__price"><span class="smartbanner__info__price__price">' + this.options.price + '</span><span class="smartbanner__info__price__suffix">' + this.priceSuffix + '</span></div>\n        </div>\n      </div>\n      <a href="' + this.buttonUrl + '" target="_blank" class="smartbanner__button"><span class="smartbanner__button__label">' + this.options.button + '</span></a>\n    </div>';
        }
      }, {
        key: 'height',
        get: function get() {
          var height = document.querySelector('.js_smartbanner').offsetHeight;
          return height !== undefined ? height : 0;
        }
      }, {
        key: 'platformEnabled',
        get: function get() {
          var enabledPlatforms = this.options.enabledPlatforms || DEFAULT_PLATFORMS;
          return enabledPlatforms && enabledPlatforms.replace(/\s+/g, '').split(',').indexOf(this.platform) !== -1;
        }
      }, {
        key: 'positioningDisabled',
        get: function get() {
          return this.options.disablePositioning === 'true';
        }
      }, {
        key: 'userAgentExcluded',
        get: function get() {
          if (!this.options.excludeUserAgentRegex) {
            return false;
          }
          return _detector2.default.userAgentMatchesRegex(this.options.excludeUserAgentRegex);
        }
      }, {
        key: 'userAgentIncluded',
        get: function get() {
          if (!this.options.includeUserAgentRegex) {
            return false;
          }
          return _detector2.default.userAgentMatchesRegex(this.options.includeUserAgentRegex);
        }
      }, {
        key: 'hideTtl',
        get: function get() {
          return this.options.hideTtl ? parseInt(this.options.hideTtl) : false;
        }
      }]);
    
      return SmartBanner;
    }();
    
    exports.default = SmartBanner;
    
    },{"./bakery.js":1,"./detector.js":2,"./optionparser.js":4}]},{},[3]);
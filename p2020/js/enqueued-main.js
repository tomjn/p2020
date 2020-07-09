"use strict";

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

!function (e, t) {
  "object" == (typeof exports === "undefined" ? "undefined" : _typeof(exports)) && "undefined" != typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define(t) : (e = e || self).MicroModal = t();
}(void 0, function () {
  "use strict";

  function e(e, t) {
    for (var o = 0; o < t.length; o++) {
      var n = t[o];
      n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(e, n.key, n);
    }
  }

  function t(e) {
    return function (e) {
      if (Array.isArray(e)) return o(e);
    }(e) || function (e) {
      if ("undefined" != typeof Symbol && Symbol.iterator in Object(e)) return Array.from(e);
    }(e) || function (e, t) {
      if (!e) return;
      if ("string" == typeof e) return o(e, t);
      var n = Object.prototype.toString.call(e).slice(8, -1);
      "Object" === n && e.constructor && (n = e.constructor.name);
      if ("Map" === n || "Set" === n) return Array.from(n);
      if ("Arguments" === n || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return o(e, t);
    }(e) || function () {
      throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }();
  }

  function o(e, t) {
    (null == t || t > e.length) && (t = e.length);

    for (var o = 0, n = new Array(t); o < t; o++) {
      n[o] = e[o];
    }

    return n;
  }

  var n,
      i,
      a,
      r,
      s,
      l = (n = ["a[href]", "area[href]", 'input:not([disabled]):not([type="hidden"]):not([aria-hidden])', "select:not([disabled]):not([aria-hidden])", "textarea:not([disabled]):not([aria-hidden])", "button:not([disabled]):not([aria-hidden])", "iframe", "object", "embed", "[contenteditable]", '[tabindex]:not([tabindex^="-"])'], i = function () {
    function o(e) {
      var n = e.targetModal,
          i = e.triggers,
          a = void 0 === i ? [] : i,
          r = e.onShow,
          s = void 0 === r ? function () {} : r,
          l = e.onClose,
          c = void 0 === l ? function () {} : l,
          d = e.openTrigger,
          u = void 0 === d ? "data-micromodal-trigger" : d,
          f = e.closeTrigger,
          h = void 0 === f ? "data-micromodal-close" : f,
          v = e.openClass,
          m = void 0 === v ? "is-open" : v,
          g = e.disableScroll,
          b = void 0 !== g && g,
          y = e.disableFocus,
          p = void 0 !== y && y,
          w = e.awaitCloseAnimation,
          E = void 0 !== w && w,
          k = e.awaitOpenAnimation,
          M = void 0 !== k && k,
          C = e.debugMode,
          A = void 0 !== C && C;
      !function (e, t) {
        if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
      }(this, o), this.modal = document.getElementById(n), this.config = {
        debugMode: A,
        disableScroll: b,
        openTrigger: u,
        closeTrigger: h,
        openClass: m,
        onShow: s,
        onClose: c,
        awaitCloseAnimation: E,
        awaitOpenAnimation: M,
        disableFocus: p
      }, a.length > 0 && this.registerTriggers.apply(this, t(a)), this.onClick = this.onClick.bind(this), this.onKeydown = this.onKeydown.bind(this);
    }

    var i, a, r;
    return i = o, (a = [{
      key: "registerTriggers",
      value: function value() {
        for (var e = this, t = arguments.length, o = new Array(t), n = 0; n < t; n++) {
          o[n] = arguments[n];
        }

        o.filter(Boolean).forEach(function (t) {
          t.addEventListener("click", function (t) {
            return e.showModal(t);
          });
        });
      }
    }, {
      key: "showModal",
      value: function value() {
        var e = this,
            t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null;

        if (this.activeElement = document.activeElement, this.modal.setAttribute("aria-hidden", "false"), this.modal.classList.add(this.config.openClass), this.scrollBehaviour("disable"), this.addEventListeners(), this.config.awaitOpenAnimation) {
          var o = function t() {
            e.modal.removeEventListener("animationend", t, !1), e.setFocusToFirstNode();
          };

          this.modal.addEventListener("animationend", o, !1);
        } else this.setFocusToFirstNode();

        this.config.onShow(this.modal, this.activeElement, t);
      }
    }, {
      key: "closeModal",
      value: function value() {
        var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null,
            t = this.modal;

        if (this.modal.setAttribute("aria-hidden", "true"), this.removeEventListeners(), this.scrollBehaviour("enable"), this.activeElement && this.activeElement.focus && this.activeElement.focus(), this.config.onClose(this.modal, this.activeElement, e), this.config.awaitCloseAnimation) {
          var o = this.config.openClass;
          this.modal.addEventListener("animationend", function e() {
            t.classList.remove(o), t.removeEventListener("animationend", e, !1);
          }, !1);
        } else t.classList.remove(this.config.openClass);
      }
    }, {
      key: "closeModalById",
      value: function value(e) {
        this.modal = document.getElementById(e), this.modal && this.closeModal();
      }
    }, {
      key: "scrollBehaviour",
      value: function value(e) {
        if (this.config.disableScroll) {
          var t = document.querySelector("body");

          switch (e) {
            case "enable":
              Object.assign(t.style, {
                overflow: ""
              });
              break;

            case "disable":
              Object.assign(t.style, {
                overflow: "hidden"
              });
          }
        }
      }
    }, {
      key: "addEventListeners",
      value: function value() {
        this.modal.addEventListener("touchstart", this.onClick), this.modal.addEventListener("click", this.onClick), document.addEventListener("keydown", this.onKeydown);
      }
    }, {
      key: "removeEventListeners",
      value: function value() {
        this.modal.removeEventListener("touchstart", this.onClick), this.modal.removeEventListener("click", this.onClick), document.removeEventListener("keydown", this.onKeydown);
      }
    }, {
      key: "onClick",
      value: function value(e) {
        e.target.hasAttribute(this.config.closeTrigger) && this.closeModal(e);
      }
    }, {
      key: "onKeydown",
      value: function value(e) {
        27 === e.keyCode && this.closeModal(e), 9 === e.keyCode && this.retainFocus(e);
      }
    }, {
      key: "getFocusableNodes",
      value: function value() {
        var e = this.modal.querySelectorAll(n);
        return Array.apply(void 0, t(e));
      }
    }, {
      key: "setFocusToFirstNode",
      value: function value() {
        var e = this;

        if (!this.config.disableFocus) {
          var t = this.getFocusableNodes();

          if (0 !== t.length) {
            var o = t.filter(function (t) {
              return !t.hasAttribute(e.config.closeTrigger);
            });
            o.length > 0 && o[0].focus(), 0 === o.length && t[0].focus();
          }
        }
      }
    }, {
      key: "retainFocus",
      value: function value(e) {
        var t = this.getFocusableNodes();
        if (0 !== t.length) if (t = t.filter(function (e) {
          return null !== e.offsetParent;
        }), this.modal.contains(document.activeElement)) {
          var o = t.indexOf(document.activeElement);
          e.shiftKey && 0 === o && (t[t.length - 1].focus(), e.preventDefault()), !e.shiftKey && t.length > 0 && o === t.length - 1 && (t[0].focus(), e.preventDefault());
        } else t[0].focus();
      }
    }]) && e(i.prototype, a), r && e(i, r), o;
  }(), a = null, r = function r(e) {
    if (!document.getElementById(e)) return console.warn("MicroModal: ❗Seems like you have missed %c'".concat(e, "'"), "background-color: #f8f9fa;color: #50596c;font-weight: bold;", "ID somewhere in your code. Refer example below to resolve it."), console.warn("%cExample:", "background-color: #f8f9fa;color: #50596c;font-weight: bold;", '<div class="modal" id="'.concat(e, '"></div>')), !1;
  }, s = function s(e, t) {
    if (function (e) {
      e.length <= 0 && (console.warn("MicroModal: ❗Please specify at least one %c'micromodal-trigger'", "background-color: #f8f9fa;color: #50596c;font-weight: bold;", "data attribute."), console.warn("%cExample:", "background-color: #f8f9fa;color: #50596c;font-weight: bold;", '<a href="#" data-micromodal-trigger="my-modal"></a>'));
    }(e), !t) return !0;

    for (var o in t) {
      r(o);
    }

    return !0;
  }, {
    init: function init(e) {
      var o = Object.assign({}, {
        openTrigger: "data-micromodal-trigger"
      }, e),
          n = t(document.querySelectorAll("[".concat(o.openTrigger, "]"))),
          r = function (e, t) {
        var o = [];
        return e.forEach(function (e) {
          var n = e.attributes[t].value;
          void 0 === o[n] && (o[n] = []), o[n].push(e);
        }), o;
      }(n, o.openTrigger);

      if (!0 !== o.debugMode || !1 !== s(n, r)) for (var l in r) {
        var c = r[l];
        o.targetModal = l, o.triggers = t(c), a = new i(o);
      }
    },
    show: function show(e, t) {
      var o = t || {};
      o.targetModal = e, !0 === o.debugMode && !1 === r(e) || (a && a.removeEventListeners(), (a = new i(o)).showModal());
    },
    close: function close(e) {
      e ? a.closeModalById(e) : a.closeModal();
    }
  });
  return window.MicroModal = l, l;
});
/**
 * MBP - Mobile boilerplate helper functions
 */

(function (document) {
  window.MBP = window.MBP || {};
  /**
   * Fix for iPhone viewport scale bug
   * http://www.blog.highub.com/mobile-2/a-fix-for-iphone-viewport-scale-bug/
   */

  MBP.viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]');
  MBP.ua = navigator.userAgent;

  MBP.scaleFix = function () {
    if (MBP.viewportmeta && /iPhone|iPad|iPod/.test(MBP.ua) && !/Opera Mini/.test(MBP.ua)) {
      MBP.viewportmeta.content = 'width=device-width, minimum-scale=1.0, maximum-scale=1.0';
      document.addEventListener('gesturestart', MBP.gestureStart, false);
    }
  };

  MBP.gestureStart = function () {
    MBP.viewportmeta.content = 'width=device-width, minimum-scale=0.25, maximum-scale=1.6';
  };
  /**
   * Normalized hide address bar for iOS & Android
   * (c) Scott Jehl, scottjehl.com
   * MIT License
   */
  // If we split this up into two functions we can reuse
  // this function if we aren't doing full page reloads.
  // If we cache this we don't need to re-calibrate everytime we call
  // the hide url bar


  MBP.BODY_SCROLL_TOP = false; // So we don't redefine this function everytime we
  // we call hideUrlBar

  MBP.getScrollTop = function () {
    var win = window;
    var doc = document;
    return win.pageYOffset || doc.compatMode === 'CSS1Compat' && doc.documentElement.scrollTop || doc.body.scrollTop || 0;
  }; // It should be up to the mobile


  MBP.hideUrlBar = function () {
    var win = window; // if there is a hash, or MBP.BODY_SCROLL_TOP hasn't been set yet, wait till that happens

    if (!location.hash && MBP.BODY_SCROLL_TOP !== false) {
      win.scrollTo(0, MBP.BODY_SCROLL_TOP === 1 ? 0 : 1);
    }
  };

  MBP.hideUrlBarOnLoad = function () {
    var win = window;
    var doc = win.document;
    var bodycheck; // If there's a hash, or addEventListener is undefined, stop here

    if (!location.hash && win.addEventListener) {
      // scroll to 1
      window.scrollTo(0, 1);
      MBP.BODY_SCROLL_TOP = 1; // reset to 0 on bodyready, if needed

      bodycheck = setInterval(function () {
        if (doc.body) {
          clearInterval(bodycheck);
          MBP.BODY_SCROLL_TOP = MBP.getScrollTop();
          MBP.hideUrlBar();
        }
      }, 15);
      win.addEventListener('load', function () {
        setTimeout(function () {
          // at load, if user hasn't scrolled more than 20 or so...
          if (MBP.getScrollTop() < 20) {
            // reset to hide addr bar at onload
            MBP.hideUrlBar();
          }
        }, 0);
      });
    }
  };
  /**
   * Prevent iOS from zooming onfocus
   * https://github.com/h5bp/mobile-boilerplate/pull/108
   * Adapted from original jQuery code here: http://nerd.vasilis.nl/prevent-ios-from-zooming-onfocus/
   */


  MBP.preventZoom = function () {
    var formFields = document.querySelectorAll('input, select, textarea');
    var contentString = 'width=device-width,initial-scale=1,maximum-scale=';
    var i = 0;
    var fieldLength = formFields.length;

    var setViewportOnFocus = function setViewportOnFocus() {
      MBP.viewportmeta.content = contentString + '1';
    };

    var setViewportOnBlur = function setViewportOnBlur() {
      MBP.viewportmeta.content = contentString + '10';
    };

    for (; i < fieldLength; i++) {
      formFields[i].onfocus = setViewportOnFocus;
      formFields[i].onblur = setViewportOnBlur;
    }
  };
})(document);
/**
 * @popperjs/core v2.3.3 - MIT License
 */


"use strict";

!function (e, t) {
  "object" == (typeof exports === "undefined" ? "undefined" : _typeof(exports)) && "undefined" != typeof module ? t(exports) : "function" == typeof define && define.amd ? define(["exports"], t) : t((e = e || self).Popper = {});
}(void 0, function (e) {
  function t(e) {
    return {
      width: (e = e.getBoundingClientRect()).width,
      height: e.height,
      top: e.top,
      right: e.right,
      bottom: e.bottom,
      left: e.left,
      x: e.left,
      y: e.top
    };
  }

  function r(e) {
    return "[object Window]" !== e.toString() ? (e = e.ownerDocument) ? e.defaultView : window : e;
  }

  function n(e) {
    return {
      scrollLeft: (e = r(e)).pageXOffset,
      scrollTop: e.pageYOffset
    };
  }

  function o(e) {
    return e instanceof r(e).Element || e instanceof Element;
  }

  function i(e) {
    return e instanceof r(e).HTMLElement || e instanceof HTMLElement;
  }

  function a(e) {
    return e ? (e.nodeName || "").toLowerCase() : null;
  }

  function s(e) {
    return (o(e) ? e.ownerDocument : e.document).documentElement;
  }

  function f(e) {
    return t(s(e)).left + n(e).scrollLeft;
  }

  function p(e, o, p) {
    void 0 === p && (p = !1), e = t(e);
    var c = {
      scrollLeft: 0,
      scrollTop: 0
    },
        u = {
      x: 0,
      y: 0
    };
    return p || ("body" !== a(o) && (c = o !== r(o) && i(o) ? {
      scrollLeft: o.scrollLeft,
      scrollTop: o.scrollTop
    } : n(o)), i(o) ? ((u = t(o)).x += o.clientLeft, u.y += o.clientTop) : (o = s(o)) && (u.x = f(o))), {
      x: e.left + c.scrollLeft - u.x,
      y: e.top + c.scrollTop - u.y,
      width: e.width,
      height: e.height
    };
  }

  function c(e) {
    return {
      x: e.offsetLeft,
      y: e.offsetTop,
      width: e.offsetWidth,
      height: e.offsetHeight
    };
  }

  function u(e) {
    return "html" === a(e) ? e : e.assignedSlot || e.parentNode || e.host || s(e);
  }

  function l(e) {
    return r(e).getComputedStyle(e);
  }

  function d(e, t) {
    void 0 === t && (t = []);

    var n = function e(t) {
      if (0 <= ["html", "body", "#document"].indexOf(a(t))) return t.ownerDocument.body;

      if (i(t)) {
        var r = l(t);
        if (/auto|scroll|overlay|hidden/.test(r.overflow + r.overflowY + r.overflowX)) return t;
      }

      return e(u(t));
    }(e);

    e = "body" === a(n);
    var o = r(n);
    return n = e ? [o].concat(o.visualViewport || []) : n, t = t.concat(n), e ? t : t.concat(d(u(n)));
  }

  function m(e) {
    return i(e) && "fixed" !== l(e).position ? e.offsetParent : null;
  }

  function h(e) {
    var t = r(e);

    for (e = m(e); e && 0 <= ["table", "td", "th"].indexOf(a(e));) {
      e = m(e);
    }

    return e && "body" === a(e) && "static" === l(e).position ? t : e || t;
  }

  function v(e) {
    var t = new Map(),
        r = new Set(),
        n = [];
    return e.forEach(function (e) {
      t.set(e.name, e);
    }), e.forEach(function (e) {
      r.has(e.name) || function e(o) {
        r.add(o.name), [].concat(o.requires || [], o.requiresIfExists || []).forEach(function (n) {
          r.has(n) || (n = t.get(n)) && e(n);
        }), n.push(o);
      }(e);
    }), n;
  }

  function g(e) {
    var t;
    return function () {
      return t || (t = new Promise(function (r) {
        Promise.resolve().then(function () {
          t = void 0, r(e());
        });
      })), t;
    };
  }

  function b(e) {
    return e.split("-")[0];
  }

  function y() {
    for (var e = arguments.length, t = Array(e), r = 0; r < e; r++) {
      t[r] = arguments[r];
    }

    return !t.some(function (e) {
      return !(e && "function" == typeof e.getBoundingClientRect);
    });
  }

  function w(e) {
    void 0 === e && (e = {});
    var t = e.defaultModifiers,
        r = void 0 === t ? [] : t,
        n = void 0 === (e = e.defaultOptions) ? F : e;
    return function (e, t, i) {
      function a() {
        f.forEach(function (e) {
          return e();
        }), f = [];
      }

      void 0 === i && (i = n);
      var s = {
        placement: "bottom",
        orderedModifiers: [],
        options: Object.assign({}, F, {}, n),
        modifiersData: {},
        elements: {
          reference: e,
          popper: t
        },
        attributes: {},
        styles: {}
      },
          f = [],
          u = !1,
          l = {
        state: s,
        setOptions: function setOptions(i) {
          return a(), s.options = Object.assign({}, n, {}, s.options, {}, i), s.scrollParents = {
            reference: o(e) ? d(e) : e.contextElement ? d(e.contextElement) : [],
            popper: d(t)
          }, i = function (e) {
            var t = v(e);
            return C.reduce(function (e, r) {
              return e.concat(t.filter(function (e) {
                return e.phase === r;
              }));
            }, []);
          }(function (e) {
            var t = e.reduce(function (e, t) {
              var r = e[t.name];
              return e[t.name] = r ? Object.assign({}, r, {}, t, {
                options: Object.assign({}, r.options, {}, t.options),
                data: Object.assign({}, r.data, {}, t.data)
              }) : t, e;
            }, {});
            return Object.keys(t).map(function (e) {
              return t[e];
            });
          }([].concat(r, s.options.modifiers))), s.orderedModifiers = i.filter(function (e) {
            return e.enabled;
          }), s.orderedModifiers.forEach(function (e) {
            var t = e.name,
                r = e.options;
            r = void 0 === r ? {} : r, "function" == typeof (e = e.effect) && (t = e({
              state: s,
              name: t,
              instance: l,
              options: r
            }), f.push(t || function () {}));
          }), l.update();
        },
        forceUpdate: function forceUpdate() {
          if (!u) {
            var e = s.elements,
                t = e.reference;
            if (y(t, e = e.popper)) for (s.rects = {
              reference: p(t, h(e), "fixed" === s.options.strategy),
              popper: c(e)
            }, s.reset = !1, s.placement = s.options.placement, s.orderedModifiers.forEach(function (e) {
              return s.modifiersData[e.name] = Object.assign({}, e.data);
            }), t = 0; t < s.orderedModifiers.length; t++) {
              if (!0 === s.reset) s.reset = !1, t = -1;else {
                var r = s.orderedModifiers[t];
                e = r.fn;
                var n = r.options;
                n = void 0 === n ? {} : n, r = r.name, "function" == typeof e && (s = e({
                  state: s,
                  options: n,
                  name: r,
                  instance: l
                }) || s);
              }
            }
          }
        },
        update: g(function () {
          return new Promise(function (e) {
            l.forceUpdate(), e(s);
          });
        }),
        destroy: function destroy() {
          a(), u = !0;
        }
      };
      return y(e, t) ? (l.setOptions(i).then(function (e) {
        !u && i.onFirstUpdate && i.onFirstUpdate(e);
      }), l) : l;
    };
  }

  function x(e) {
    return 0 <= ["top", "bottom"].indexOf(e) ? "x" : "y";
  }

  function O(e) {
    var t = e.reference,
        r = e.element,
        n = (e = e.placement) ? b(e) : null;
    e = e ? e.split("-")[1] : null;
    var o = t.x + t.width / 2 - r.width / 2,
        i = t.y + t.height / 2 - r.height / 2;

    switch (n) {
      case "top":
        o = {
          x: o,
          y: t.y - r.height
        };
        break;

      case "bottom":
        o = {
          x: o,
          y: t.y + t.height
        };
        break;

      case "right":
        o = {
          x: t.x + t.width,
          y: i
        };
        break;

      case "left":
        o = {
          x: t.x - r.width,
          y: i
        };
        break;

      default:
        o = {
          x: t.x,
          y: t.y
        };
    }

    if (null != (n = n ? x(n) : null)) switch (i = "y" === n ? "height" : "width", e) {
      case "start":
        o[n] = Math.floor(o[n]) - Math.floor(t[i] / 2 - r[i] / 2);
        break;

      case "end":
        o[n] = Math.floor(o[n]) + Math.ceil(t[i] / 2 - r[i] / 2);
    }
    return o;
  }

  function M(e) {
    var t,
        n = e.popper,
        o = e.popperRect,
        i = e.placement,
        a = e.offsets,
        f = e.position,
        p = e.gpuAcceleration,
        c = e.adaptive,
        u = window.devicePixelRatio || 1;
    e = Math.round(a.x * u) / u || 0, u = Math.round(a.y * u) / u || 0;
    var l = a.hasOwnProperty("x");
    a = a.hasOwnProperty("y");
    var d,
        m = "left",
        v = "top",
        g = window;

    if (c) {
      var b = h(n);
      b === r(n) && (b = s(n)), "top" === i && (v = "bottom", u -= b.clientHeight - o.height, u *= p ? 1 : -1), "left" === i && (m = "right", e -= b.clientWidth - o.width, e *= p ? 1 : -1);
    }

    return n = Object.assign({
      position: f
    }, c && V), p ? Object.assign({}, n, ((d = {})[v] = a ? "0" : "", d[m] = l ? "0" : "", d.transform = 2 > (g.devicePixelRatio || 1) ? "translate(" + e + "px, " + u + "px)" : "translate3d(" + e + "px, " + u + "px, 0)", d)) : Object.assign({}, n, ((t = {})[v] = a ? u + "px" : "", t[m] = l ? e + "px" : "", t.transform = "", t));
  }

  function j(e) {
    return e.replace(/left|right|bottom|top/g, function (e) {
      return I[e];
    });
  }

  function E(e) {
    return e.replace(/start|end/g, function (e) {
      return _[e];
    });
  }

  function D(e, t) {
    var r = !(!t.getRootNode || !t.getRootNode().host);
    if (e.contains(t)) return !0;
    if (r) do {
      if (t && e.isSameNode(t)) return !0;
      t = t.parentNode || t.host;
    } while (t);
    return !1;
  }

  function P(e) {
    return Object.assign({}, e, {
      left: e.x,
      top: e.y,
      right: e.x + e.width,
      bottom: e.y + e.height
    });
  }

  function L(e, o) {
    if ("viewport" === o) {
      var a = r(e);
      e = a.visualViewport, o = a.innerWidth, a = a.innerHeight, e && /iPhone|iPod|iPad/.test(navigator.platform) && (o = e.width, a = e.height), e = P({
        width: o,
        height: a,
        x: 0,
        y: 0
      });
    } else i(o) ? e = t(o) : (e = r(a = s(e)), o = n(a), (a = p(s(a), e)).height = Math.max(a.height, e.innerHeight), a.width = Math.max(a.width, e.innerWidth), a.x = -o.scrollLeft, a.y = -o.scrollTop, e = P(a));

    return e;
  }

  function k(e, t, n) {
    return t = "clippingParents" === t ? function (e) {
      var t = d(e),
          r = 0 <= ["absolute", "fixed"].indexOf(l(e).position) && i(e) ? h(e) : e;
      return o(r) ? t.filter(function (e) {
        return o(e) && D(e, r);
      }) : [];
    }(e) : [].concat(t), (n = (n = [].concat(t, [n])).reduce(function (t, n) {
      var o = L(e, n),
          p = r(n = i(n) ? n : s(e)),
          c = i(n) ? l(n) : {};
      parseFloat(c.borderTopWidth);
      var u = parseFloat(c.borderRightWidth) || 0,
          d = parseFloat(c.borderBottomWidth) || 0,
          m = parseFloat(c.borderLeftWidth) || 0;
      c = "html" === a(n);
      var h = f(n),
          v = n.clientWidth + u,
          g = n.clientHeight + d;
      return c && 50 < p.innerHeight - n.clientHeight && (g = p.innerHeight - d), d = c ? 0 : n.clientTop, u = n.clientLeft > m ? u : c ? p.innerWidth - v - h : n.offsetWidth - v, p = c ? p.innerHeight - g : n.offsetHeight - g, n = c ? h : n.clientLeft, t.top = Math.max(o.top + d, t.top), t.right = Math.min(o.right - u, t.right), t.bottom = Math.min(o.bottom - p, t.bottom), t.left = Math.max(o.left + n, t.left), t;
    }, L(e, n[0]))).width = n.right - n.left, n.height = n.bottom - n.top, n.x = n.left, n.y = n.top, n;
  }

  function B(e) {
    return Object.assign({}, {
      top: 0,
      right: 0,
      bottom: 0,
      left: 0
    }, {}, e);
  }

  function W(e, t) {
    return t.reduce(function (t, r) {
      return t[r] = e, t;
    }, {});
  }

  function H(e, r) {
    void 0 === r && (r = {});
    var n = r;
    r = void 0 === (r = n.placement) ? e.placement : r;
    var i = n.boundary,
        a = void 0 === i ? "clippingParents" : i,
        f = void 0 === (i = n.rootBoundary) ? "viewport" : i;
    i = void 0 === (i = n.elementContext) ? "popper" : i;
    var p = n.altBoundary,
        c = void 0 !== p && p;
    n = B("number" != typeof (n = void 0 === (n = n.padding) ? 0 : n) ? n : W(n, R));
    var u = e.elements.reference;
    p = e.rects.popper, a = k(o(c = e.elements[c ? "popper" === i ? "reference" : "popper" : i]) ? c : c.contextElement || s(e.elements.popper), a, f), c = O({
      reference: f = t(u),
      element: p,
      strategy: "absolute",
      placement: r
    }), p = P(Object.assign({}, p, {}, c)), f = "popper" === i ? p : f;
    var l = {
      top: a.top - f.top + n.top,
      bottom: f.bottom - a.bottom + n.bottom,
      left: a.left - f.left + n.left,
      right: f.right - a.right + n.right
    };

    if (e = e.modifiersData.offset, "popper" === i && e) {
      var d = e[r];
      Object.keys(l).forEach(function (e) {
        var t = 0 <= ["right", "bottom"].indexOf(e) ? 1 : -1,
            r = 0 <= ["top", "bottom"].indexOf(e) ? "y" : "x";
        l[e] += d[r] * t;
      });
    }

    return l;
  }

  function T(e, t, r) {
    return void 0 === r && (r = {
      x: 0,
      y: 0
    }), {
      top: e.top - t.height - r.y,
      right: e.right - t.width + r.x,
      bottom: e.bottom - t.height + r.y,
      left: e.left - t.width - r.x
    };
  }

  function A(e) {
    return ["top", "right", "bottom", "left"].some(function (t) {
      return 0 <= e[t];
    });
  }

  var R = ["top", "bottom", "right", "left"],
      q = R.reduce(function (e, t) {
    return e.concat([t + "-start", t + "-end"]);
  }, []),
      S = [].concat(R, ["auto"]).reduce(function (e, t) {
    return e.concat([t, t + "-start", t + "-end"]);
  }, []),
      C = "beforeRead read afterRead beforeMain main afterMain beforeWrite write afterWrite".split(" "),
      F = {
    placement: "bottom",
    modifiers: [],
    strategy: "absolute"
  },
      N = {
    passive: !0
  },
      V = {
    top: "auto",
    right: "auto",
    bottom: "auto",
    left: "auto"
  },
      I = {
    left: "right",
    right: "left",
    bottom: "top",
    top: "bottom"
  },
      _ = {
    start: "end",
    end: "start"
  },
      U = [{
    name: "eventListeners",
    enabled: !0,
    phase: "write",
    fn: function fn() {},
    effect: function effect(e) {
      var t = e.state,
          n = e.instance,
          o = (e = e.options).scroll,
          i = void 0 === o || o,
          a = void 0 === (e = e.resize) || e,
          s = r(t.elements.popper),
          f = [].concat(t.scrollParents.reference, t.scrollParents.popper);
      return i && f.forEach(function (e) {
        e.addEventListener("scroll", n.update, N);
      }), a && s.addEventListener("resize", n.update, N), function () {
        i && f.forEach(function (e) {
          e.removeEventListener("scroll", n.update, N);
        }), a && s.removeEventListener("resize", n.update, N);
      };
    },
    data: {}
  }, {
    name: "popperOffsets",
    enabled: !0,
    phase: "read",
    fn: function fn(e) {
      var t = e.state;
      t.modifiersData[e.name] = O({
        reference: t.rects.reference,
        element: t.rects.popper,
        strategy: "absolute",
        placement: t.placement
      });
    },
    data: {}
  }, {
    name: "computeStyles",
    enabled: !0,
    phase: "beforeWrite",
    fn: function fn(e) {
      var t = e.state,
          r = e.options;
      e = void 0 === (e = r.gpuAcceleration) || e, r = void 0 === (r = r.adaptive) || r, e = {
        placement: b(t.placement),
        popper: t.elements.popper,
        popperRect: t.rects.popper,
        gpuAcceleration: e
      }, null != t.modifiersData.popperOffsets && (t.styles.popper = Object.assign({}, t.styles.popper, {}, M(Object.assign({}, e, {
        offsets: t.modifiersData.popperOffsets,
        position: t.options.strategy,
        adaptive: r
      })))), null != t.modifiersData.arrow && (t.styles.arrow = Object.assign({}, t.styles.arrow, {}, M(Object.assign({}, e, {
        offsets: t.modifiersData.arrow,
        position: "absolute",
        adaptive: !1
      })))), t.attributes.popper = Object.assign({}, t.attributes.popper, {
        "data-popper-placement": t.placement
      });
    },
    data: {}
  }, {
    name: "applyStyles",
    enabled: !0,
    phase: "write",
    fn: function fn(e) {
      var t = e.state;
      Object.keys(t.elements).forEach(function (e) {
        var r = t.styles[e] || {},
            n = t.attributes[e] || {},
            o = t.elements[e];
        i(o) && a(o) && (Object.assign(o.style, r), Object.keys(n).forEach(function (e) {
          var t = n[e];
          !1 === t ? o.removeAttribute(e) : o.setAttribute(e, !0 === t ? "" : t);
        }));
      });
    },
    effect: function effect(e) {
      var t = e.state,
          r = {
        popper: {
          position: t.options.strategy,
          left: "0",
          top: "0",
          margin: "0"
        },
        arrow: {
          position: "absolute"
        },
        reference: {}
      };
      return Object.assign(t.elements.popper.style, r.popper), t.elements.arrow && Object.assign(t.elements.arrow.style, r.arrow), function () {
        Object.keys(t.elements).forEach(function (e) {
          var n = t.elements[e],
              o = t.attributes[e] || {};
          e = Object.keys(t.styles.hasOwnProperty(e) ? t.styles[e] : r[e]).reduce(function (e, t) {
            return e[t] = "", e;
          }, {}), i(n) && a(n) && (Object.assign(n.style, e), Object.keys(o).forEach(function (e) {
            n.removeAttribute(e);
          }));
        });
      };
    },
    requires: ["computeStyles"]
  }, {
    name: "offset",
    enabled: !0,
    phase: "main",
    requires: ["popperOffsets"],
    fn: function fn(e) {
      var t = e.state,
          r = e.name,
          n = void 0 === (e = e.options.offset) ? [0, 0] : e,
          o = (e = S.reduce(function (e, r) {
        var o = t.rects,
            i = b(r),
            a = 0 <= ["left", "top"].indexOf(i) ? -1 : 1,
            s = "function" == typeof n ? n(Object.assign({}, o, {
          placement: r
        })) : n;
        return o = (o = s[0]) || 0, s = ((s = s[1]) || 0) * a, i = 0 <= ["left", "right"].indexOf(i) ? {
          x: s,
          y: o
        } : {
          x: o,
          y: s
        }, e[r] = i, e;
      }, {}))[t.placement],
          i = o.x;
      o = o.y, null != t.modifiersData.popperOffsets && (t.modifiersData.popperOffsets.x += i, t.modifiersData.popperOffsets.y += o), t.modifiersData[r] = e;
    }
  }, {
    name: "flip",
    enabled: !0,
    phase: "main",
    fn: function fn(e) {
      var t = e.state,
          r = e.options;

      if (e = e.name, !t.modifiersData[e]._skip) {
        var n = r.fallbackPlacements,
            o = r.padding,
            i = r.boundary,
            a = r.rootBoundary,
            s = r.altBoundary,
            f = r.flipVariations,
            p = void 0 === f || f,
            c = r.allowedAutoPlacements;
        f = b(r = t.options.placement), n = n || (f !== r && p ? function (e) {
          if ("auto" === b(e)) return [];
          var t = j(e);
          return [E(e), t, E(t)];
        }(r) : [j(r)]);
        var u = [r].concat(n).reduce(function (e, r) {
          return e.concat("auto" === b(r) ? function (e, t) {
            void 0 === t && (t = {});
            var r = t.boundary,
                n = t.rootBoundary,
                o = t.padding,
                i = t.flipVariations,
                a = t.allowedAutoPlacements,
                s = void 0 === a ? S : a,
                f = t.placement.split("-")[1],
                p = (f ? i ? q : q.filter(function (e) {
              return e.split("-")[1] === f;
            }) : R).filter(function (e) {
              return 0 <= s.indexOf(e);
            }).reduce(function (t, i) {
              return t[i] = H(e, {
                placement: i,
                boundary: r,
                rootBoundary: n,
                padding: o
              })[b(i)], t;
            }, {});
            return Object.keys(p).sort(function (e, t) {
              return p[e] - p[t];
            });
          }(t, {
            placement: r,
            boundary: i,
            rootBoundary: a,
            padding: o,
            flipVariations: p,
            allowedAutoPlacements: c
          }) : r);
        }, []);
        n = t.rects.reference, r = t.rects.popper;
        var l = new Map();
        f = !0;

        for (var d = u[0], m = 0; m < u.length; m++) {
          var h = u[m],
              v = b(h),
              g = "start" === h.split("-")[1],
              y = 0 <= ["top", "bottom"].indexOf(v),
              w = y ? "width" : "height",
              x = H(t, {
            placement: h,
            boundary: i,
            rootBoundary: a,
            altBoundary: s,
            padding: o
          });

          if (g = y ? g ? "right" : "left" : g ? "bottom" : "top", n[w] > r[w] && (g = j(g)), w = j(g), (v = [0 >= x[v], 0 >= x[g], 0 >= x[w]]).every(function (e) {
            return e;
          })) {
            d = h, f = !1;
            break;
          }

          l.set(h, v);
        }

        if (f) for (s = function s(e) {
          var t = u.find(function (t) {
            if (t = l.get(t)) return t.slice(0, e).every(function (e) {
              return e;
            });
          });
          if (t) return d = t, "break";
        }, n = p ? 3 : 1; 0 < n && "break" !== s(n); n--) {
          ;
        }
        t.placement !== d && (t.modifiersData[e]._skip = !0, t.placement = d, t.reset = !0);
      }
    },
    requiresIfExists: ["offset"],
    data: {
      _skip: !1
    }
  }, {
    name: "preventOverflow",
    enabled: !0,
    phase: "main",
    fn: function fn(e) {
      var t = e.state,
          r = e.options;
      e = e.name;
      var n = r.mainAxis,
          o = void 0 === n || n;
      n = void 0 !== (n = r.altAxis) && n;
      var i = r.tether;
      i = void 0 === i || i;
      var a = r.tetherOffset,
          s = void 0 === a ? 0 : a;
      r = H(t, {
        boundary: r.boundary,
        rootBoundary: r.rootBoundary,
        padding: r.padding,
        altBoundary: r.altBoundary
      }), a = b(t.placement);
      var f = t.placement.split("-")[1],
          p = !f,
          u = x(a);
      a = "x" === u ? "y" : "x";
      var l = t.modifiersData.popperOffsets,
          d = t.rects.reference,
          m = t.rects.popper,
          v = "function" == typeof s ? s(Object.assign({}, t.rects, {
        placement: t.placement
      })) : s;

      if (s = {
        x: 0,
        y: 0
      }, l) {
        if (o) {
          var g = "y" === u ? "top" : "left",
              y = "y" === u ? "bottom" : "right",
              w = "y" === u ? "height" : "width";
          o = l[u];
          var O = l[u] + r[g],
              M = l[u] - r[y],
              j = i ? -m[w] / 2 : 0,
              E = "start" === f ? d[w] : m[w];
          f = "start" === f ? -m[w] : -d[w], m = t.elements.arrow, m = i && m ? c(m) : {
            width: 0,
            height: 0
          };
          var D = t.modifiersData["arrow#persistent"] ? t.modifiersData["arrow#persistent"].padding : {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
          };
          g = D[g], y = D[y], m = Math.max(0, Math.min(d[w], m[w])), E = p ? d[w] / 2 - j - m - g - v : E - m - g - v, p = p ? -d[w] / 2 + j + m + y + v : f + m + y + v, v = t.elements.arrow && h(t.elements.arrow), d = t.modifiersData.offset ? t.modifiersData.offset[t.placement][u] : 0, v = l[u] + E - d - (v ? "y" === u ? v.clientTop || 0 : v.clientLeft || 0 : 0), p = l[u] + p - d, i = Math.max(i ? Math.min(O, v) : O, Math.min(o, i ? Math.max(M, p) : M)), l[u] = i, s[u] = i - o;
        }

        n && (n = l[a], i = Math.max(n + r["x" === u ? "top" : "left"], Math.min(n, n - r["x" === u ? "bottom" : "right"])), l[a] = i, s[a] = i - n), t.modifiersData[e] = s;
      }
    },
    requiresIfExists: ["offset"]
  }, {
    name: "arrow",
    enabled: !0,
    phase: "main",
    fn: function fn(e) {
      var t,
          r = e.state;
      e = e.name;
      var n = r.elements.arrow,
          o = r.modifiersData.popperOffsets,
          i = b(r.placement),
          a = x(i);

      if (i = 0 <= ["left", "right"].indexOf(i) ? "height" : "width", n && o) {
        var s = r.modifiersData[e + "#persistent"].padding;
        n = c(n);
        var f = "y" === a ? "top" : "left",
            p = "y" === a ? "bottom" : "right",
            u = r.rects.reference[i] + r.rects.reference[a] - o[a] - r.rects.popper[i];
        o = o[a] - r.rects.reference[a];
        var l = r.elements.arrow && h(r.elements.arrow);
        u = (l = l ? "y" === a ? l.clientHeight || 0 : l.clientWidth || 0 : 0) / 2 - n[i] / 2 + (u / 2 - o / 2), i = Math.max(s[f], Math.min(u, l - n[i] - s[p])), r.modifiersData[e] = ((t = {})[a] = i, t.centerOffset = i - u, t);
      }
    },
    effect: function effect(e) {
      var t = e.state,
          r = e.options;
      e = e.name;
      var n = r.element;

      if (n = void 0 === n ? "[data-popper-arrow]" : n, r = void 0 === (r = r.padding) ? 0 : r, null != n) {
        if ("string" == typeof n && !(n = t.elements.popper.querySelector(n))) return;
        D(t.elements.popper, n) && (t.elements.arrow = n, t.modifiersData[e + "#persistent"] = {
          padding: B("number" != typeof r ? r : W(r, R))
        });
      }
    },
    requires: ["popperOffsets"],
    requiresIfExists: ["preventOverflow"]
  }, {
    name: "hide",
    enabled: !0,
    phase: "main",
    requiresIfExists: ["preventOverflow"],
    fn: function fn(e) {
      var t = e.state;
      e = e.name;
      var r = t.rects.reference,
          n = t.rects.popper,
          o = t.modifiersData.preventOverflow,
          i = H(t, {
        elementContext: "reference"
      }),
          a = H(t, {
        altBoundary: !0
      });
      r = T(i, r), n = T(a, n, o), o = A(r), a = A(n), t.modifiersData[e] = {
        referenceClippingOffsets: r,
        popperEscapeOffsets: n,
        isReferenceHidden: o,
        hasPopperEscaped: a
      }, t.attributes.popper = Object.assign({}, t.attributes.popper, {
        "data-popper-reference-hidden": o,
        "data-popper-escaped": a
      });
    }
  }],
      z = w({
    defaultModifiers: U
  });
  e.createPopper = z, e.defaultModifiers = U, e.detectOverflow = H, e.popperGenerator = w, Object.defineProperty(e, "__esModule", {
    value: !0
  });
});

(function () {
  var is_webkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1,
      is_opera = navigator.userAgent.toLowerCase().indexOf('opera') > -1,
      is_ie = navigator.userAgent.toLowerCase().indexOf('msie') > -1;

  if ((is_webkit || is_opera || is_ie) && 'undefined' !== typeof document.getElementById) {
    var eventMethod = window.addEventListener ? 'addEventListener' : 'attachEvent';
    window[eventMethod]('hashchange', function () {
      var element = document.getElementById(location.hash.substring(1));

      if (element) {
        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) element.tabIndex = -1;
        element.focus();
      }
    }, false);
  }
})();

!function (t, e) {
  "object" == (typeof exports === "undefined" ? "undefined" : _typeof(exports)) && "undefined" != typeof module ? module.exports = e(require("@popperjs/core")) : "function" == typeof define && define.amd ? define(["@popperjs/core"], e) : (t = t || self).tippy = e(t.Popper);
}(void 0, function (t) {
  "use strict";

  var e = "undefined" != typeof window && "undefined" != typeof document,
      n = e ? navigator.userAgent : "",
      i = /MSIE |Trident\//.test(n),
      r = {
    passive: !0,
    capture: !0
  };

  function o(t, e, n) {
    if (Array.isArray(t)) {
      var i = t[e];
      return null == i ? Array.isArray(n) ? n[e] : n : i;
    }

    return t;
  }

  function a(t, e) {
    var n = {}.toString.call(t);
    return 0 === n.indexOf("[object") && n.indexOf(e + "]") > -1;
  }

  function s(t, e) {
    return "function" == typeof t ? t.apply(void 0, e) : t;
  }

  function p(t, e) {
    return 0 === e ? t : function (i) {
      clearTimeout(n), n = setTimeout(function () {
        t(i);
      }, e);
    };
    var n;
  }

  function u(t, e) {
    var n = Object.assign({}, t);
    return e.forEach(function (t) {
      delete n[t];
    }), n;
  }

  function c(t) {
    return [].concat(t);
  }

  function f(t, e) {
    -1 === t.indexOf(e) && t.push(e);
  }

  function l(t) {
    return t.split("-")[0];
  }

  function d(t) {
    return [].slice.call(t);
  }

  function v() {
    return document.createElement("div");
  }

  function m(t) {
    return ["Element", "Fragment"].some(function (e) {
      return a(t, e);
    });
  }

  function g(t) {
    return a(t, "MouseEvent");
  }

  function h(t) {
    return !(!t || !t._tippy || t._tippy.reference !== t);
  }

  function b(t) {
    return m(t) ? [t] : function (t) {
      return a(t, "NodeList");
    }(t) ? d(t) : Array.isArray(t) ? t : d(document.querySelectorAll(t));
  }

  function y(t, e) {
    t.forEach(function (t) {
      t && (t.style.transitionDuration = e + "ms");
    });
  }

  function x(t, e) {
    t.forEach(function (t) {
      t && t.setAttribute("data-state", e);
    });
  }

  function w(t) {
    var e = c(t)[0];
    return e && e.ownerDocument || document;
  }

  function E(t, e, n) {
    var i = e + "EventListener";
    ["transitionend", "webkitTransitionEnd"].forEach(function (e) {
      t[i](e, n);
    });
  }

  var T = {
    isTouch: !1
  },
      A = 0;

  function C() {
    T.isTouch || (T.isTouch = !0, window.performance && document.addEventListener("mousemove", O));
  }

  function O() {
    var t = performance.now();
    t - A < 20 && (T.isTouch = !1, document.removeEventListener("mousemove", O)), A = t;
  }

  function L() {
    var t = document.activeElement;

    if (h(t)) {
      var e = t._tippy;
      t.blur && !e.state.isVisible && t.blur();
    }
  }

  var D = Object.assign({
    appendTo: function appendTo() {
      return document.body;
    },
    aria: {
      content: "auto",
      expanded: "auto"
    },
    delay: 0,
    duration: [300, 250],
    getReferenceClientRect: null,
    hideOnClick: !0,
    ignoreAttributes: !1,
    interactive: !1,
    interactiveBorder: 2,
    interactiveDebounce: 0,
    moveTransition: "",
    offset: [0, 10],
    onAfterUpdate: function onAfterUpdate() {},
    onBeforeUpdate: function onBeforeUpdate() {},
    onCreate: function onCreate() {},
    onDestroy: function onDestroy() {},
    onHidden: function onHidden() {},
    onHide: function onHide() {},
    onMount: function onMount() {},
    onShow: function onShow() {},
    onShown: function onShown() {},
    onTrigger: function onTrigger() {},
    onUntrigger: function onUntrigger() {},
    onClickOutside: function onClickOutside() {},
    placement: "top",
    plugins: [],
    popperOptions: {},
    render: null,
    showOnCreate: !1,
    touch: !0,
    trigger: "mouseenter focus",
    triggerTarget: null
  }, {
    animateFill: !1,
    followCursor: !1,
    inlinePositioning: !1,
    sticky: !1
  }, {}, {
    allowHTML: !1,
    animation: "fade",
    arrow: !0,
    content: "",
    inertia: !1,
    maxWidth: 350,
    role: "tooltip",
    theme: "",
    zIndex: 9999
  }),
      k = Object.keys(D);

  function V(t) {
    var e = (t.plugins || []).reduce(function (e, n) {
      var i = n.name,
          r = n.defaultValue;
      return i && (e[i] = void 0 !== t[i] ? t[i] : r), e;
    }, {});
    return Object.assign({}, t, {}, e);
  }

  function M(t, e) {
    var n = Object.assign({}, e, {
      content: s(e.content, [t])
    }, e.ignoreAttributes ? {} : function (t, e) {
      return (e ? Object.keys(V(Object.assign({}, D, {
        plugins: e
      }))) : k).reduce(function (e, n) {
        var i = (t.getAttribute("data-tippy-" + n) || "").trim();
        if (!i) return e;
        if ("content" === n) e[n] = i;else try {
          e[n] = JSON.parse(i);
        } catch (t) {
          e[n] = i;
        }
        return e;
      }, {});
    }(t, e.plugins));
    return n.aria = Object.assign({}, D.aria, {}, n.aria), n.aria = {
      expanded: "auto" === n.aria.expanded ? e.interactive : n.aria.expanded,
      content: "auto" === n.aria.content ? e.interactive ? null : "describedby" : n.aria.content
    }, n;
  }

  function R(t, e) {
    t.innerHTML = e;
  }

  function j(t) {
    var e = v();
    return !0 === t ? e.className = "tippy-arrow" : (e.className = "tippy-svg-arrow", m(t) ? e.appendChild(t) : R(e, t)), e;
  }

  function P(t, e) {
    m(e.content) ? (R(t, ""), t.appendChild(e.content)) : "function" != typeof e.content && (e.allowHTML ? R(t, e.content) : t.textContent = e.content);
  }

  function I(t) {
    var e = t.firstElementChild,
        n = d(e.children);
    return {
      box: e,
      content: n.find(function (t) {
        return t.classList.contains("tippy-content");
      }),
      arrow: n.find(function (t) {
        return t.classList.contains("tippy-arrow") || t.classList.contains("tippy-svg-arrow");
      }),
      backdrop: n.find(function (t) {
        return t.classList.contains("tippy-backdrop");
      })
    };
  }

  function S(t) {
    var e = v(),
        n = v();
    n.className = "tippy-box", n.setAttribute("data-state", "hidden"), n.setAttribute("tabindex", "-1");
    var i = v();

    function r(n, i) {
      var r = I(e),
          o = r.box,
          a = r.content,
          s = r.arrow;
      i.theme ? o.setAttribute("data-theme", i.theme) : o.removeAttribute("data-theme"), "string" == typeof i.animation ? o.setAttribute("data-animation", i.animation) : o.removeAttribute("data-animation"), i.inertia ? o.setAttribute("data-inertia", "") : o.removeAttribute("data-inertia"), o.style.maxWidth = "number" == typeof i.maxWidth ? i.maxWidth + "px" : i.maxWidth, i.role ? o.setAttribute("role", i.role) : o.removeAttribute("role"), n.content === i.content && n.allowHTML === i.allowHTML || P(a, t.props), i.arrow ? s ? n.arrow !== i.arrow && (o.removeChild(s), o.appendChild(j(i.arrow))) : o.appendChild(j(i.arrow)) : s && o.removeChild(s);
    }

    return i.className = "tippy-content", i.setAttribute("data-state", "hidden"), P(i, t.props), e.appendChild(n), n.appendChild(i), r(t.props, t.props), {
      popper: e,
      onUpdate: r
    };
  }

  S.$$tippy = !0;
  var B = 1,
      H = [],
      U = [];

  function N(e, n) {
    var a,
        u,
        m,
        h,
        b,
        A,
        C,
        O,
        L = M(e, Object.assign({}, D, {}, V(n))),
        k = !1,
        R = !1,
        j = !1,
        P = !1,
        S = [],
        N = p(ht, L.interactiveDebounce),
        X = w(L.triggerTarget || e),
        Y = B++,
        _ = (O = L.plugins).filter(function (t, e) {
      return O.indexOf(t) === e;
    }),
        z = {
      id: Y,
      reference: e,
      popper: v(),
      popperInstance: null,
      props: L,
      state: {
        isEnabled: !0,
        isVisible: !1,
        isDestroyed: !1,
        isMounted: !1,
        isShown: !1
      },
      plugins: _,
      clearDelayTimeouts: function clearDelayTimeouts() {
        clearTimeout(a), clearTimeout(u), cancelAnimationFrame(m);
      },
      setProps: function setProps(t) {
        if (z.state.isDestroyed) return;
        it("onBeforeUpdate", [z, t]), mt();
        var n = z.props,
            i = M(e, Object.assign({}, z.props, {}, t, {
          ignoreAttributes: !0
        }));
        z.props = i, vt(), n.interactiveDebounce !== i.interactiveDebounce && (at(), N = p(ht, i.interactiveDebounce));
        n.triggerTarget && !i.triggerTarget ? c(n.triggerTarget).forEach(function (t) {
          t.removeAttribute("aria-expanded");
        }) : i.triggerTarget && e.removeAttribute("aria-expanded");
        ot(), nt(), q && q(n, i);
        z.popperInstance && (wt(), Tt().forEach(function (t) {
          requestAnimationFrame(t._tippy.popperInstance.forceUpdate);
        }));
        it("onAfterUpdate", [z, t]);
      },
      setContent: function setContent(t) {
        z.setProps({
          content: t
        });
      },
      show: function show() {
        var t = z.state.isVisible,
            e = z.state.isDestroyed,
            n = !z.state.isEnabled,
            i = T.isTouch && !z.props.touch,
            r = o(z.props.duration, 0, D.duration);
        if (t || e || n || i) return;
        if (Z().hasAttribute("disabled")) return;
        if (it("onShow", [z], !1), !1 === z.props.onShow(z)) return;
        z.state.isVisible = !0, Q() && (W.style.visibility = "visible");
        nt(), ct(), z.state.isMounted || (W.style.transition = "none");

        if (Q()) {
          var a = tt(),
              p = a.box,
              u = a.content;
          y([p, u], 0);
        }

        A = function A() {
          if (z.state.isVisible && !P) {
            if (P = !0, W.offsetHeight, W.style.transition = z.props.moveTransition, Q() && z.props.animation) {
              var t = tt(),
                  e = t.box,
                  n = t.content;
              y([e, n], r), x([e, n], "visible");
            }

            rt(), ot(), f(U, z), z.state.isMounted = !0, it("onMount", [z]), z.props.animation && Q() && function (t, e) {
              lt(t, e);
            }(r, function () {
              z.state.isShown = !0, it("onShown", [z]);
            });
          }
        }, function () {
          var t,
              e = z.props.appendTo,
              n = Z();
          t = z.props.interactive && e === D.appendTo || "parent" === e ? n.parentNode : s(e, [n]);
          t.contains(W) || t.appendChild(W);
          wt();
        }();
      },
      hide: function hide() {
        var t = !z.state.isVisible,
            e = z.state.isDestroyed,
            n = !z.state.isEnabled,
            i = o(z.props.duration, 1, D.duration);
        if (t || e || n) return;
        if (it("onHide", [z], !1), !1 === z.props.onHide(z)) return;
        z.state.isVisible = !1, z.state.isShown = !1, P = !1, Q() && (W.style.visibility = "hidden");

        if (at(), ft(), nt(), Q()) {
          var r = tt(),
              a = r.box,
              s = r.content;
          z.props.animation && (y([a, s], i), x([a, s], "hidden"));
        }

        rt(), ot(), z.props.animation ? Q() && function (t, e) {
          lt(t, function () {
            !z.state.isVisible && W.parentNode && W.parentNode.contains(W) && e();
          });
        }(i, z.unmount) : z.unmount();
      },
      hideWithInteractivity: function hideWithInteractivity(t) {
        z.state.isVisible && (X.body.addEventListener("mouseleave", Ct), X.addEventListener("mousemove", N), f(H, N), N(t));
      },
      enable: function enable() {
        z.state.isEnabled = !0;
      },
      disable: function disable() {
        z.hide(), z.state.isEnabled = !1;
      },
      unmount: function unmount() {
        z.state.isVisible && z.hide();
        if (!z.state.isMounted) return;
        Et(), Tt().forEach(function (t) {
          t._tippy.unmount();
        }), W.parentNode && W.parentNode.removeChild(W);
        U = U.filter(function (t) {
          return t !== z;
        }), z.state.isMounted = !1, it("onHidden", [z]);
      },
      destroy: function destroy() {
        if (z.state.isDestroyed) return;
        z.clearDelayTimeouts(), z.unmount(), mt(), delete e._tippy, z.state.isDestroyed = !0, it("onDestroy", [z]);
      }
    };

    if (!L.render) return z;
    var F = L.render(z),
        W = F.popper,
        q = F.onUpdate;
    W.setAttribute("data-tippy-root", ""), W.id = "tippy-" + z.id, z.popper = W, e._tippy = z, W._tippy = z;

    var $ = _.map(function (t) {
      return t.fn(z);
    }),
        J = e.hasAttribute("aria-expanded");

    return vt(), ot(), nt(), it("onCreate", [z]), L.showOnCreate && At(), W.addEventListener("mouseenter", function () {
      z.props.interactive && z.state.isVisible && z.clearDelayTimeouts();
    }), W.addEventListener("mouseleave", function (t) {
      z.props.interactive && z.props.trigger.indexOf("mouseenter") >= 0 && (X.addEventListener("mousemove", N), N(t));
    }), z;

    function G() {
      var t = z.props.touch;
      return Array.isArray(t) ? t : [t, 0];
    }

    function K() {
      return "hold" === G()[0];
    }

    function Q() {
      var t;
      return !!(null == (t = z.props.render) ? void 0 : t.$$tippy);
    }

    function Z() {
      return C || e;
    }

    function tt() {
      return I(W);
    }

    function et(t) {
      return z.state.isMounted && !z.state.isVisible || T.isTouch || h && "focus" === h.type ? 0 : o(z.props.delay, t ? 0 : 1, D.delay);
    }

    function nt() {
      W.style.pointerEvents = z.props.interactive && z.state.isVisible ? "" : "none", W.style.zIndex = "" + z.props.zIndex;
    }

    function it(t, e, n) {
      var i;
      (void 0 === n && (n = !0), $.forEach(function (n) {
        n[t] && n[t].apply(void 0, e);
      }), n) && (i = z.props)[t].apply(i, e);
    }

    function rt() {
      var t = z.props.aria;

      if (t.content) {
        var n = "aria-" + t.content,
            i = W.id;
        c(z.props.triggerTarget || e).forEach(function (t) {
          var e = t.getAttribute(n);
          if (z.state.isVisible) t.setAttribute(n, e ? e + " " + i : i);else {
            var r = e && e.replace(i, "").trim();
            r ? t.setAttribute(n, r) : t.removeAttribute(n);
          }
        });
      }
    }

    function ot() {
      !J && z.props.aria.expanded && c(z.props.triggerTarget || e).forEach(function (t) {
        z.props.interactive ? t.setAttribute("aria-expanded", z.state.isVisible && t === Z() ? "true" : "false") : t.removeAttribute("aria-expanded");
      });
    }

    function at() {
      X.body.removeEventListener("mouseleave", Ct), X.removeEventListener("mousemove", N), H = H.filter(function (t) {
        return t !== N;
      });
    }

    function st(t) {
      if (!(T.isTouch && (j || "mousedown" === t.type) || z.props.interactive && W.contains(t.target))) {
        if (Z().contains(t.target)) {
          if (T.isTouch) return;
          if (z.state.isVisible && z.props.trigger.indexOf("click") >= 0) return;
        } else it("onClickOutside", [z, t]);

        !0 === z.props.hideOnClick && (k = !1, z.clearDelayTimeouts(), z.hide(), R = !0, setTimeout(function () {
          R = !1;
        }), z.state.isMounted || ft());
      }
    }

    function pt() {
      j = !0;
    }

    function ut() {
      j = !1;
    }

    function ct() {
      X.addEventListener("mousedown", st, !0), X.addEventListener("touchend", st, r), X.addEventListener("touchstart", ut, r), X.addEventListener("touchmove", pt, r);
    }

    function ft() {
      X.removeEventListener("mousedown", st, !0), X.removeEventListener("touchend", st, r), X.removeEventListener("touchstart", ut, r), X.removeEventListener("touchmove", pt, r);
    }

    function lt(t, e) {
      var n = tt().box;

      function i(t) {
        t.target === n && (E(n, "remove", i), e());
      }

      if (0 === t) return e();
      E(n, "remove", b), E(n, "add", i), b = i;
    }

    function dt(t, n, i) {
      void 0 === i && (i = !1), c(z.props.triggerTarget || e).forEach(function (e) {
        e.addEventListener(t, n, i), S.push({
          node: e,
          eventType: t,
          handler: n,
          options: i
        });
      });
    }

    function vt() {
      var t;
      K() && (dt("touchstart", gt, {
        passive: !0
      }), dt("touchend", bt, {
        passive: !0
      })), (t = z.props.trigger, t.split(/\s+/).filter(Boolean)).forEach(function (t) {
        if ("manual" !== t) switch (dt(t, gt), t) {
          case "mouseenter":
            dt("mouseleave", bt);
            break;

          case "focus":
            dt(i ? "focusout" : "blur", yt);
            break;

          case "focusin":
            dt("focusout", yt);
        }
      });
    }

    function mt() {
      S.forEach(function (t) {
        var e = t.node,
            n = t.eventType,
            i = t.handler,
            r = t.options;
        e.removeEventListener(n, i, r);
      }), S = [];
    }

    function gt(t) {
      var e,
          n = !1;

      if (z.state.isEnabled && !xt(t) && !R) {
        var i = "focus" === (null == (e = h) ? void 0 : e.type);
        h = t, C = t.currentTarget, ot(), !z.state.isVisible && g(t) && H.forEach(function (e) {
          return e(t);
        }), "click" === t.type && (z.props.trigger.indexOf("mouseenter") < 0 || k) && !1 !== z.props.hideOnClick && z.state.isVisible ? n = !0 : At(t), "click" === t.type && (k = !n), n && !i && Ct(t);
      }
    }

    function ht(t) {
      var n = t.target,
          i = e.contains(n) || W.contains(n);
      "mousemove" === t.type && i || function (t, e) {
        var n = e.clientX,
            i = e.clientY;
        return t.every(function (t) {
          var e = t.popperRect,
              r = t.popperState,
              o = t.props.interactiveBorder,
              a = l(r.placement),
              s = r.modifiersData.offset;
          if (!s) return !0;
          var p = "bottom" === a ? s.top.y : 0,
              u = "top" === a ? s.bottom.y : 0,
              c = "right" === a ? s.left.x : 0,
              f = "left" === a ? s.right.x : 0,
              d = e.top - i + p > o,
              v = i - e.bottom - u > o,
              m = e.left - n + c > o,
              g = n - e.right - f > o;
          return d || v || m || g;
        });
      }(Tt().concat(W).map(function (t) {
        var e,
            n = null == (e = t._tippy.popperInstance) ? void 0 : e.state;
        return n ? {
          popperRect: t.getBoundingClientRect(),
          popperState: n,
          props: L
        } : null;
      }).filter(Boolean), t) && (at(), Ct(t));
    }

    function bt(t) {
      xt(t) || z.props.trigger.indexOf("click") >= 0 && k || (z.props.interactive ? z.hideWithInteractivity(t) : Ct(t));
    }

    function yt(t) {
      z.props.trigger.indexOf("focusin") < 0 && t.target !== Z() || z.props.interactive && t.relatedTarget && W.contains(t.relatedTarget) || Ct(t);
    }

    function xt(t) {
      return !!T.isTouch && K() !== t.type.indexOf("touch") >= 0;
    }

    function wt() {
      Et();
      var n = z.props,
          i = n.popperOptions,
          r = n.placement,
          o = n.offset,
          a = n.getReferenceClientRect,
          s = n.moveTransition,
          p = Q() ? I(W).arrow : null,
          u = a ? {
        getBoundingClientRect: a,
        contextElement: a.contextElement || Z()
      } : e,
          c = [{
        name: "offset",
        options: {
          offset: o
        }
      }, {
        name: "preventOverflow",
        options: {
          padding: {
            top: 2,
            bottom: 2,
            left: 5,
            right: 5
          }
        }
      }, {
        name: "flip",
        options: {
          padding: 5
        }
      }, {
        name: "computeStyles",
        options: {
          adaptive: !s
        }
      }, {
        name: "$$tippy",
        enabled: !0,
        phase: "beforeWrite",
        requires: ["computeStyles"],
        fn: function fn(t) {
          var e = t.state;

          if (Q()) {
            var n = tt().box;
            ["placement", "reference-hidden", "escaped"].forEach(function (t) {
              "placement" === t ? n.setAttribute("data-placement", e.placement) : e.attributes.popper["data-popper-" + t] ? n.setAttribute("data-" + t, "") : n.removeAttribute("data-" + t);
            }), e.attributes.popper = {};
          }
        }
      }];
      Q() && p && c.push({
        name: "arrow",
        options: {
          element: p,
          padding: 3
        }
      }), c.push.apply(c, (null == i ? void 0 : i.modifiers) || []), z.popperInstance = t.createPopper(u, W, Object.assign({}, i, {
        placement: r,
        onFirstUpdate: A,
        modifiers: c
      }));
    }

    function Et() {
      z.popperInstance && (z.popperInstance.destroy(), z.popperInstance = null);
    }

    function Tt() {
      return d(W.querySelectorAll("[data-tippy-root]"));
    }

    function At(t) {
      z.clearDelayTimeouts(), t && it("onTrigger", [z, t]), ct();
      var e = et(!0),
          n = G(),
          i = n[0],
          r = n[1];
      T.isTouch && "hold" === i && r && (e = r), e ? a = setTimeout(function () {
        z.show();
      }, e) : z.show();
    }

    function Ct(t) {
      if (z.clearDelayTimeouts(), it("onUntrigger", [z, t]), z.state.isVisible) {
        if (!(z.props.trigger.indexOf("mouseenter") >= 0 && z.props.trigger.indexOf("click") >= 0 && ["mouseleave", "mousemove"].indexOf(t.type) >= 0 && k)) {
          var e = et(!1);
          e ? u = setTimeout(function () {
            z.state.isVisible && z.hide();
          }, e) : m = requestAnimationFrame(function () {
            z.hide();
          });
        }
      } else ft();
    }
  }

  function X(t, e) {
    void 0 === e && (e = {});
    var n = D.plugins.concat(e.plugins || []);
    document.addEventListener("touchstart", C, r), window.addEventListener("blur", L);
    var i = Object.assign({}, e, {
      plugins: n
    }),
        o = b(t).reduce(function (t, e) {
      var n = e && N(e, i);
      return n && t.push(n), t;
    }, []);
    return m(t) ? o[0] : o;
  }

  X.defaultProps = D, X.setDefaultProps = function (t) {
    Object.keys(t).forEach(function (e) {
      D[e] = t[e];
    });
  }, X.currentInput = T;
  var Y = {
    mouseover: "mouseenter",
    focusin: "focus",
    click: "click"
  };
  var _ = {
    name: "animateFill",
    defaultValue: !1,
    fn: function fn(t) {
      var e;
      if (!(null == (e = t.props.render) ? void 0 : e.$$tippy)) return {};
      var n = I(t.popper),
          i = n.box,
          r = n.content,
          o = t.props.animateFill ? function () {
        var t = v();
        return t.className = "tippy-backdrop", x([t], "hidden"), t;
      }() : null;
      return {
        onCreate: function onCreate() {
          o && (i.insertBefore(o, i.firstElementChild), i.setAttribute("data-animatefill", ""), i.style.overflow = "hidden", t.setProps({
            arrow: !1,
            animation: "shift-away"
          }));
        },
        onMount: function onMount() {
          if (o) {
            var t = i.style.transitionDuration,
                e = Number(t.replace("ms", ""));
            r.style.transitionDelay = Math.round(e / 10) + "ms", o.style.transitionDuration = t, x([o], "visible");
          }
        },
        onShow: function onShow() {
          o && (o.style.transitionDuration = "0ms");
        },
        onHide: function onHide() {
          o && x([o], "hidden");
        }
      };
    }
  };
  var z = {
    name: "followCursor",
    defaultValue: !1,
    fn: function fn(t) {
      var e = t.reference,
          n = w(t.props.triggerTarget || e),
          i = null;

      function r() {
        return "manual" === t.props.trigger.trim();
      }

      function o() {
        var e = !!r() || null !== i && !(0 === i.clientX && 0 === i.clientY);
        return t.props.followCursor && e;
      }

      function a(e) {
        e && t.setProps({
          getReferenceClientRect: null
        });
      }

      function s() {
        o() ? n.addEventListener("mousemove", u) : a(t.props.followCursor);
      }

      function p() {
        n.removeEventListener("mousemove", u);
      }

      function u(n) {
        i = {
          clientX: n.clientX,
          clientY: n.clientY
        };
        var r = !n.target || e.contains(n.target),
            o = t.props.followCursor,
            a = n.clientX,
            s = n.clientY,
            u = e.getBoundingClientRect(),
            c = a - u.left,
            f = s - u.top;
        !r && t.props.interactive || t.setProps({
          getReferenceClientRect: function getReferenceClientRect() {
            var t = e.getBoundingClientRect(),
                n = a,
                i = s;
            "initial" === o && (n = t.left + c, i = t.top + f);
            var r = "horizontal" === o ? t.top : i,
                p = "vertical" === o ? t.right : n,
                u = "horizontal" === o ? t.bottom : i,
                l = "vertical" === o ? t.left : n;
            return {
              width: p - l,
              height: u - r,
              top: r,
              right: p,
              bottom: u,
              left: l
            };
          }
        }), (T.isTouch || "initial" === t.props.followCursor && t.state.isVisible) && p();
      }

      return {
        onAfterUpdate: function onAfterUpdate(t, e) {
          var n = e.followCursor;
          void 0 === n || n || a(!0);
        },
        onMount: function onMount() {
          o() && u(i);
        },
        onShow: function onShow() {
          r() && (i = {
            clientX: 0,
            clientY: 0
          }, s());
        },
        onTrigger: function onTrigger(t, e) {
          i || (g(e) && (i = {
            clientX: e.clientX,
            clientY: e.clientY
          }), s());
        },
        onUntrigger: function onUntrigger() {
          t.state.isVisible || (p(), i = null);
        },
        onHidden: function onHidden() {
          p(), i = null;
        }
      };
    }
  };
  var F = {
    name: "inlinePositioning",
    defaultValue: !1,
    fn: function fn(t) {
      var e,
          n = t.reference;
      var i = -1,
          r = !1,
          o = {
        name: "tippyInlinePositioning",
        enabled: !0,
        phase: "afterWrite",
        fn: function fn(r) {
          var o = r.state;
          t.props.inlinePositioning && (e !== o.placement && t.setProps({
            getReferenceClientRect: function getReferenceClientRect() {
              return function (t) {
                return function (t, e, n, i) {
                  if (n.length < 2 || null === t) return e;
                  if (2 === n.length && i >= 0 && n[0].left > n[1].right) return n[i] || e;

                  switch (t) {
                    case "top":
                    case "bottom":
                      var r = n[0],
                          o = n[n.length - 1],
                          a = "top" === t,
                          s = r.top,
                          p = o.bottom,
                          u = a ? r.left : o.left,
                          c = a ? r.right : o.right;
                      return {
                        top: s,
                        bottom: p,
                        left: u,
                        right: c,
                        width: c - u,
                        height: p - s
                      };

                    case "left":
                    case "right":
                      var f = Math.min.apply(Math, n.map(function (t) {
                        return t.left;
                      })),
                          l = Math.max.apply(Math, n.map(function (t) {
                        return t.right;
                      })),
                          d = n.filter(function (e) {
                        return "left" === t ? e.left === f : e.right === l;
                      }),
                          v = d[0].top,
                          m = d[d.length - 1].bottom;
                      return {
                        top: v,
                        bottom: m,
                        left: f,
                        right: l,
                        width: l - f,
                        height: m - v
                      };

                    default:
                      return e;
                  }
                }(l(t), n.getBoundingClientRect(), d(n.getClientRects()), i);
              }(o.placement);
            }
          }), e = o.placement);
        }
      };

      function a() {
        var e;
        r || (e = function (t, e) {
          var n;
          return {
            popperOptions: Object.assign({}, t.popperOptions, {
              modifiers: [].concat(((null == (n = t.popperOptions) ? void 0 : n.modifiers) || []).filter(function (t) {
                return t.name !== e.name;
              }), [e])
            })
          };
        }(t.props, o), r = !0, t.setProps(e), r = !1);
      }

      return {
        onCreate: a,
        onAfterUpdate: a,
        onTrigger: function onTrigger(e, n) {
          if (g(n)) {
            var r = d(t.reference.getClientRects()),
                o = r.find(function (t) {
              return t.left - 2 <= n.clientX && t.right + 2 >= n.clientX && t.top - 2 <= n.clientY && t.bottom + 2 >= n.clientY;
            });
            i = r.indexOf(o);
          }
        },
        onUntrigger: function onUntrigger() {
          i = -1;
        }
      };
    }
  };
  var W = {
    name: "sticky",
    defaultValue: !1,
    fn: function fn(t) {
      var e = t.reference,
          n = t.popper;

      function i(e) {
        return !0 === t.props.sticky || t.props.sticky === e;
      }

      var r = null,
          o = null;

      function a() {
        var s = i("reference") ? (t.popperInstance ? t.popperInstance.state.elements.reference : e).getBoundingClientRect() : null,
            p = i("popper") ? n.getBoundingClientRect() : null;
        (s && q(r, s) || p && q(o, p)) && t.popperInstance && t.popperInstance.update(), r = s, o = p, t.state.isMounted && requestAnimationFrame(a);
      }

      return {
        onMount: function onMount() {
          t.props.sticky && a();
        }
      };
    }
  };

  function q(t, e) {
    return !t || !e || t.top !== e.top || t.right !== e.right || t.bottom !== e.bottom || t.left !== e.left;
  }

  return e && function (t) {
    var e = document.createElement("style");
    e.textContent = t, e.setAttribute("data-tippy-stylesheet", "");
    var n = document.head,
        i = document.querySelector("head>style,head>link");
    i ? n.insertBefore(e, i) : n.appendChild(e);
  }('.tippy-box[data-animation=fade][data-state=hidden]{opacity:0}[data-tippy-root]{max-width:calc(100vw - 10px)}.tippy-box{position:relative;background-color:#333;color:#fff;border-radius:4px;font-size:14px;line-height:1.4;outline:0;transition-property:transform,visibility,opacity}.tippy-box[data-placement^=top]>.tippy-arrow{bottom:0}.tippy-box[data-placement^=top]>.tippy-arrow:before{bottom:-7px;left:0;border-width:8px 8px 0;border-top-color:initial;transform-origin:center top}.tippy-box[data-placement^=bottom]>.tippy-arrow{top:0}.tippy-box[data-placement^=bottom]>.tippy-arrow:before{top:-7px;left:0;border-width:0 8px 8px;border-bottom-color:initial;transform-origin:center bottom}.tippy-box[data-placement^=left]>.tippy-arrow{right:0}.tippy-box[data-placement^=left]>.tippy-arrow:before{border-width:8px 0 8px 8px;border-left-color:initial;right:-7px;transform-origin:center left}.tippy-box[data-placement^=right]>.tippy-arrow{left:0}.tippy-box[data-placement^=right]>.tippy-arrow:before{left:-7px;border-width:8px 8px 8px 0;border-right-color:initial;transform-origin:center right}.tippy-box[data-inertia][data-state=visible]{transition-timing-function:cubic-bezier(.54,1.5,.38,1.11)}.tippy-arrow{width:16px;height:16px;color:#333}.tippy-arrow:before{content:"";position:absolute;border-color:transparent;border-style:solid}.tippy-content{position:relative;padding:5px 9px;z-index:1}'), X.setDefaultProps({
    plugins: [_, z, F, W],
    render: S
  }), X.createSingleton = function (t, e) {
    void 0 === e && (e = {});
    var n,
        i = t,
        r = [],
        o = e.overrides;

    function a() {
      r = i.map(function (t) {
        return t.reference;
      });
    }

    function s(t) {
      i.forEach(function (e) {
        t ? e.enable() : e.disable();
      });
    }

    s(!1), a();
    var p = {
      fn: function fn() {
        return {
          onDestroy: function onDestroy() {
            s(!0);
          },
          onTrigger: function onTrigger(t, e) {
            var a = e.currentTarget,
                s = r.indexOf(a);

            if (a !== n) {
              n = a;
              var p = (o || []).concat("content").reduce(function (t, e) {
                return t[e] = i[s].props[e], t;
              }, {});
              t.setProps(Object.assign({}, p, {
                getReferenceClientRect: function getReferenceClientRect() {
                  return a.getBoundingClientRect();
                }
              }));
            }
          }
        };
      }
    },
        c = X(v(), Object.assign({}, u(e, ["overrides"]), {
      plugins: [p].concat(e.plugins || []),
      triggerTarget: r
    })),
        f = c.setProps;
    return c.setProps = function (t) {
      o = t.overrides || o, f(t);
    }, c.setInstances = function (t) {
      s(!0), i = t, s(!1), a(), c.setProps({
        triggerTarget: r
      });
    }, c;
  }, X.delegate = function (t, e) {
    var n = [],
        i = [],
        r = e.target,
        o = u(e, ["target"]),
        a = Object.assign({}, o, {
      trigger: "manual",
      touch: !1
    }),
        s = Object.assign({}, o, {
      showOnCreate: !0
    }),
        p = X(t, a);

    function f(t) {
      if (t.target) {
        var n = t.target.closest(r);

        if (n) {
          var o = n.getAttribute("data-tippy-trigger") || e.trigger || D.trigger;

          if (!n._tippy && !("touchstart" === t.type && "boolean" == typeof s.touch || "touchstart" !== t.type && o.indexOf(Y[t.type]))) {
            var a = X(n, s);
            a && (i = i.concat(a));
          }
        }
      }
    }

    function l(t, e, i, r) {
      void 0 === r && (r = !1), t.addEventListener(e, i, r), n.push({
        node: t,
        eventType: e,
        handler: i,
        options: r
      });
    }

    return c(p).forEach(function (t) {
      var e = t.destroy;
      t.destroy = function (t) {
        void 0 === t && (t = !0), t && i.forEach(function (t) {
          t.destroy();
        }), i = [], n.forEach(function (t) {
          var e = t.node,
              n = t.eventType,
              i = t.handler,
              r = t.options;
          e.removeEventListener(n, i, r);
        }), n = [], e();
      }, function (t) {
        var e = t.reference;
        l(e, "touchstart", f), l(e, "mouseover", f), l(e, "focusin", f), l(e, "click", f);
      }(t);
    }), p;
  }, X.hideAll = function (t) {
    var e = void 0 === t ? {} : t,
        n = e.exclude,
        i = e.duration;
    U.forEach(function (t) {
      var e = !1;

      if (n && (e = h(n) ? t.reference === n : t.popper === n.popper), !e) {
        var r = t.props.duration;
        t.setProps({
          duration: i
        }), t.hide(), t.state.isDestroyed || t.setProps({
          duration: r
        });
      }
    });
  }, X.roundArrow = '<svg width="16" height="6" xmlns="http://www.w3.org/2000/svg"><path d="M0 6s1.796-.013 4.67-3.615C5.851.9 6.93.006 8 0c1.07-.006 2.148.887 3.343 2.385C14.233 6.005 16 6 16 6H0z"></svg>', X;
});

(function ($) {
  // Used for fallback styling when JS is disabled or slow to load
  $('body').removeClass('no-js');
})(jQuery);

(function ($) {
  /**
   * Tippy.js Plugins
   * https://atomiks.github.io/tippyjs/v6/plugins/
   */
  var hideOnPopperBlur = {
    name: 'hideOnPopperBlur',
    defaultValue: true,
    fn: function fn(instance) {
      return {
        onCreate: function onCreate() {
          instance.popper.addEventListener('focusout', function (event) {
            if (instance.props.hideOnPopperBlur && event.relatedTarget && !instance.popper.contains(event.relatedTarget)) {
              // If the focusout was caused by a click on the menu trigger button,
              // simply hiding does not work in Chrome because the click triggers
              // an instant re-show. Adding a slight delay here ensures that the
              // focusout hide takes precedence over the click show.
              window.setTimeout(instance.hide, 200);
            }
          });
        }
      };
    }
  };
  var hideOnEsc = {
    name: 'hideOnEsc',
    defaultValue: true,
    fn: function fn(_ref) {
      var hide = _ref.hide;

      function onKeyDown(event) {
        if (event.keyCode === 27) {
          hide();
        }
      }

      return {
        onShow: function onShow() {
          return document.addEventListener('keydown', onKeyDown);
        },
        onHide: function onHide() {
          return document.removeEventListener('keydown', onKeyDown);
        }
      };
    }
  };

  function initTooltips() {
    window.tippy('[data-tippy-content]', {
      theme: 'p2-tooltip',
      duration: 100
      /* duration for transition animation */

    });
  }

  function initEllipsisMenus() {
    var triggerSelector = '[data-tippy-menu-trigger]';
    window.tippy(triggerSelector, {
      allowHTML: true,
      arrow: false,
      content: function content(reference) {
        return $(reference).siblings('[data-tippy-menu-content]').html();
      },
      duration: 50
      /* duration for transition animation */
      ,
      hideOnEsc: true,
      hideOnPopperBlur: true,
      interactive: true,
      // offset: [skidding, distance] (See https://popper.js.org/docs/v2/modifiers/offset/)
      offset: [0, 6],
      onHide: function onHide(_ref2) {
        var reference = _ref2.reference;
        return reference.setAttribute('aria-expanded', false);
      },
      onMount: function onMount(_ref3) {
        var popper = _ref3.popper,
            reference = _ref3.reference;
        // Focus the first menu item
        popper.querySelector('a, button').focus();
        reference.setAttribute('aria-expanded', true);
      },
      placement: 'bottom-start',
      plugins: [hideOnEsc, hideOnPopperBlur],
      theme: 'p2-menu',
      trigger: 'click'
    });
  }

  $(document).ready(function () {
    initTooltips();
    initEllipsisMenus();
  });
})(jQuery);

(function ($) {
  // Also defined in css/src/global/_variables.scss
  var breakpoint = '876px';
  var $button = $('[data-sidebar-mobile-toggle]');
  var $sidebar = $('#sidebar');
  var $mainContent = $('#content');
  var classModifierExpanded = 'is-mobile-expanded';
  var classModifierStartingExpand = 'has-started-expanding';
  var mainContentFadeDuration = 300;

  function expandMenu() {
    $button.attr('aria-expanded', true);
    $mainContent.fadeOut(mainContentFadeDuration, function () {
      $sidebar.addClass(classModifierStartingExpand);
      window.setTimeout(function () {
        $sidebar.addClass(classModifierExpanded);
      }, 100);
    });
  }

  function collapseMenu() {
    $button.attr('aria-expanded', false);
    $sidebar.removeClass(classModifierExpanded);
    window.setTimeout(function () {
      $sidebar.removeClass(classModifierStartingExpand);
      $mainContent.fadeIn(mainContentFadeDuration);
    }, 400);
  }

  function toggleMenu() {
    if ($button.attr('aria-expanded') === 'true') {
      collapseMenu();
    } else {
      expandMenu();
    }
  }

  $button.click(toggleMenu);
  var mql = window.matchMedia("( max-width: ".concat(breakpoint, " )"));
  mql.addListener(function (event) {
    if (!event.matches) {
      collapseMenu();
    }
  });
})(jQuery);
/**
 * Only show the editor footer when the editor is active or has content.
 */


(function ($) {
  $(document).ready(function () {
    /**
     * Selectors
     */
    var editor = document.querySelector('.o2-app-new-post');

    if (editor === null) {
      return;
    }

    var $editorFooter = $('.o2-app-new-post .o2-editor-footer');
    var blockListLayoutSelector = '.block-editor-block-list__layout';
    var editorExpandedModifier = 'is-expanded';
    /**
     * Local Storage Keys
     */

    var newPostContentKey = "".concat(window._currentSiteId, "-new");
    /**
     * Functions
     */

    var isEditorEmpty = function isEditorEmpty() {
      var editorContent = window.localStorage.getItem(newPostContentKey);
      return editorContent === '';
    };

    var shouldExpandEditor = function shouldExpandEditor() {
      return editor.contains(document.activeElement) || !isEditorEmpty();
    };

    var handleFocusChange = function handleFocusChange() {
      if (shouldExpandEditor()) {
        $editorFooter.show();
        $(blockListLayoutSelector).addClass(editorExpandedModifier);
      } else {
        $editorFooter.hide();
        $(blockListLayoutSelector).removeClass(editorExpandedModifier);
      }
    };
    /**
     * Main
     */


    if (isEditorEmpty()) {
      $editorFooter.hide();
    }

    if (editor) {
      editor.addEventListener('focus', handleFocusChange, true);
      editor.addEventListener('blur', handleFocusChange, true);
    }
  });
})(jQuery);

(function () {
  // Enable the fixed toolbar feature if not explicitly disabled
  function enableFixedToolbarByDefault() {
    var rawSettings = window.localStorage.getItem('p2tenberg_features');
    var settings = rawSettings ? JSON.parse(rawSettings) : {};

    if (settings.fixedToolbar === undefined) {
      window.localStorage.setItem('p2tenberg_features', JSON.stringify(_objectSpread({}, settings, {
        fixedToolbar: true
      })));
    }
  } // No need to wait for document ready


  enableFixedToolbarByDefault();
})();

(function ($) {
  $(document).ready(function () {
    var $editor = $('.o2-app-new-post');
    var $controls = $('[data-p2020-mobile-new-post-controls]');
    var btnNew = $controls.find('button')[0];
    var btnCancel = $controls.find('button')[1]; // When user is not logged in

    if ($editor.length === 0) {
      $controls.hide();
      return;
    }

    $editor.before($controls);
    $controls.css('visibility', 'visible'); // prevents FOUC before the DOM manipulation

    btnNew.addEventListener('click', function () {
      $editor.slideDown('fast');
      $(btnNew).hide();
      $(btnCancel).show();
    });
    btnCancel.addEventListener('click', function () {
      $editor.slideUp('fast');
      $(btnCancel).hide();
      $(btnNew).show();
    });
  });
})(jQuery);

(function ($) {
  // Set the total height of the sticky header bars in a CSS variable
  // so the P2tenberg sticky header can be offset to the proper Y position.
  function setTopOffset() {
    var adminbarHeight = $('#wpadminbar').height();
    var headerHeight = $('#sidebar').height();
    var totalHeight = adminbarHeight + headerHeight;
    document.documentElement.style.setProperty('--editor-header-offset', "".concat(totalHeight, "px"));
  }

  $(document).ready(function () {
    setTopOffset();
    $(window).on('resize', $.debounce(200, setTopOffset));
  });
})(jQuery); // Polyfills for things that don't need to block the rest of the JS


(function ($) {
  $(document).ready(function () {
    // CSS :focus-visible
    $.getScript('https://unpkg.com/focus-visible'); // CSS :focus-within

    if (!Modernizr.focuswithin) {
      $.getScript('https://unpkg.com/focus-within-polyfill');
    }
  });
})(jQuery);
/**
 * For handling Remembered Posts widget (a8c-only).
 *
 */


(function ($) {
  $('#remembered-posts').insertBefore('#sidebar .widget:first-of-type').wrapInner('<div class="p2020-sidebar-padded-container"></div>');
})(jQuery);

(function ($) {
  var selectorSeachform = '[data-searchform]';
  var $searchform = $(selectorSeachform);
  var $input = $searchform.find('input[type="search"]');
  var classActive = 'is-active';
  var $cancelButton = $('[data-searchform-cancel]');

  function activate() {
    $(this).closest(selectorSeachform).addClass(classActive);
  }

  function deactivate() {
    var $form = $(this).is(selectorSeachform) ? $(this) : $(this).closest(selectorSeachform);
    $form.find('input[type="search"]').attr('value', '');
    $form.removeClass(classActive);
  }

  function isActive() {
    return $(this).val() !== '' || this === document.activeElement;
  } // If search field is not empty on load, activate


  $input.filter(isActive).each(activate); // When search icon is clicked, activate and focus the search field

  $searchform.children('form').on('click', function () {
    activate();
    $searchform.find('input[type="search"]').focus();
  }); // Cancel on Esc key

  $searchform.on('keydown', function (event) {
    if (event.which === 27
    /* Esc */
    ) {
        deactivate.bind(this)();
        $(this).find('input').blur();
      }
  });
  $input.on('focus', activate);
  $input.on('blur', function () {
    $(this).not(isActive).each(deactivate);
  });
  $cancelButton.on('click', deactivate);
})(jQuery);

(function ($) {
  var $sidebar = $('#sidebar');
  var $button = $('[data-sidebar-hamburger]');
  var sidebarPrimary = document.querySelector('[data-sidebar-primary]');
  var sidebarSecondary = document.querySelector('[data-sidebar-secondary]');
  var classModifierDark = 'is-dark';

  function toggleBooleanAttr($element, attr) {
    var newValue = $element.attr(attr) === 'false';
    $element.attr(attr, newValue);
  }

  function toggleMenu() {
    $sidebar.toggleClass(classModifierDark);
    toggleBooleanAttr($button, 'aria-expanded');
    sidebarPrimary.toggleAttribute('hidden');
    sidebarSecondary.toggleAttribute('hidden');
  }

  $button.on('click', toggleMenu);
})(jQuery);

(function ($) {
  function isSinglePage() {
    return $('body').hasClass('page');
  }

  function isEditorVisible() {
    return $('.o2-editor').length !== 0;
  }

  function insertTextAsEntryTitle(text) {
    if ($('h1.entry-title').length) {
      return;
      /* Don't do anything if entry-title already exists */
    }

    $('.entry-header .entry-meta').prepend($('<h1 />', {
      class: 'entry-title',
      text: text
    }));
  }

  function reinsertTitleAfterEditingPage(sourceNode) {
    // Watch for the editor getting dismissed after a Cancel or Save
    var observer = new MutationObserver(function () {
      if (!isEditorVisible()) {
        // Prevents a FOUC after clicking the Save button
        $('.entry-content h1:first-child').hide();
        insertTextAsEntryTitle(sourceNode.textContent);
      }
    });
    observer.observe(sourceNode, {
      childList: true
    });
  }
  /**
   * Main
   */


  $(document).ready(function () {
    var sourceNode = document.querySelector('.o2-app-page-title');

    if (isSinglePage()) {
      insertTextAsEntryTitle(sourceNode.textContent);
      reinsertTitleAfterEditingPage(sourceNode);
    }
  });
})(jQuery);

(function ($) {
  function isInCustomizer() {
    return !!wp.customize;
  }

  $(document).ready(function () {
    // Is home page and not displaying O2 filtered content
    if (isInCustomizer() || window.location.pathname === '/' && window.location.search === '') {
      $('.o2-app-page-title').addClass('is-unfiltered-home');
    }
  });
})(jQuery);
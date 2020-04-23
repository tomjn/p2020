"use strict";

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

(function ($) {
  // Only do this once
  if ($('body').hasClass('p2020-js-loaded')) {
    return;
  }

  $('body').addClass('p2020-js-loaded');
})(jQuery); // TODO: Test and clean up for P2020


(function ($) {
  function moveNavIntoSidebar() {
    var $menu = $('nav#site-navigation');

    if ($menu.length && $menu.parents('header#masthead').length) {
      $menu.prependTo('#sidebar');
      $menu.wrap('<aside class="widget" id="o2-responsive-nav"></aside>');
    }
  }

  function moveNavOutOfSidebar() {
    var $menu = $('nav#site-navigation');

    if ($menu.length && 0 === $menu.parents('header#masthead').length) {
      $('nav#site-navigation').appendTo('header#masthead');
      $('#o2-responsive-nav').remove();
    }
  }

  $(document).ready(function () {
    $('[data-mobile-menu-toggle]').click(function () {
      $('body').toggleClass('mobile-menu-is-visible');
    });

    if ('undefined' !== typeof enquire) {
      // "Tablet" max-width also defined in css/src/global/_variables.scss
      enquire.register('screen and (max-width:876px)', {
        match: function match() {
          moveNavIntoSidebar();
        },
        unmatch: function unmatch() {
          moveNavOutOfSidebar();
        }
      });
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

    var shouldShowEditorFooter = function shouldShowEditorFooter() {
      return editor.contains(document.activeElement) || !isEditorEmpty();
    };

    var handleFocusChange = function handleFocusChange() {
      if (shouldShowEditorFooter()) {
        $editorFooter.show();
      } else {
        $editorFooter.hide();
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
  // Enable "Notify me of new comments via email" on new posts by default and hide the form
  function subscribeToCommentsOnNewPostsByDefault() {
    $('input[type="checkbox"]#post_subscribe').prop('checked', true);
    $('.o2-post-form-options').hide();
  }

  $(document).ready(function () {
    subscribeToCommentsOnNewPostsByDefault();
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

(function ($) {
  $(document).ready(function () {
    // Is home page and not displaying O2 filtered content
    if (window.location.pathname === '/' && window.location.search === '') {
      $('.o2-app-page-title').addClass('is-unfiltered-home');
    }
  });
})(jQuery);
//# sourceMappingURL=enqueued-main.js.map

"use strict";

wp.domReady(function () {
  var blocksBlacklist = ['jetpack/calendly', 'jetpack/opentable', 'jetpack/recurring-payments', 'jetpack/simple-payments', 'premium-content/container', 'premium-content/logged-out-view', 'premium-content/subscriber-view'];
  blocksBlacklist.forEach(wp.blocks.unregisterBlockType);
});
//# sourceMappingURL=enqueued-editor-main.js.map

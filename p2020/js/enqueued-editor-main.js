"use strict";

wp.domReady(function () {
  window.p2020Editor.blocksBlacklist.forEach(wp.blocks.unregisterBlockType);
});
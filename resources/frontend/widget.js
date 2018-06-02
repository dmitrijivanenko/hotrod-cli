define([
  "jquery",
  "jquery/ui"
], function ($) {
  'use strict';

  $.widget('mage.{{widget-name}}', {
    options: {
      text: "hello world"
    },

    _create: function() {
      console.log(this.options.text);
    }

  });
});
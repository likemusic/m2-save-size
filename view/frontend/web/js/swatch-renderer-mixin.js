define(
    ['jquery', 'Likemusic_SaveSize/js/saved-size-attribute'],
    function ($, savedSizeAttribute) {
        'use strict';

        var swatchRendererMixin = {
            _RenderControls: function () {
                this._super();

                if (!savedSizeAttribute) {
                    return;
                }

                this._EmulateSelected(savedSizeAttribute);
            }
        };

        return function (targetWidget) {
            $.widget('mage.SwatchRenderer', targetWidget, swatchRendererMixin);

            return $.mage.SwatchRenderer;
        };
    });

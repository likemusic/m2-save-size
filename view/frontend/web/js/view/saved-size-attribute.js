define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($, customerData) {
    'use strict';

    function onSectionLoaded(sections) {
        var section = sections['saved-size-attribute'];

        var attributeCode = section['attribute_code'];
        var valueId = section['value_id'];

        var selectedAttributes = {};
        selectedAttributes[attributeCode] = valueId;

        setSwatchersAttributes(selectedAttributes);
    }

    function setSwatchersAttributes(selectedAttributes) {
        var renderers = $(':mage-SwatchRenderer');
        renderers.each(function (index, renderer) {
            var a = 1;
            // $(renderer).SwatchRenderer('_EmulateSelected', selectedAttributes);
            var control = $(renderer).data('mage-SwatchRenderer');
            control._EmulateSelected(selectedAttributes);
        })
    }

    return function() {
        customerData.reload(['saved-size-attribute']).done(onSectionLoaded);
    };

    // customerData.reload(['saved-size-attribute']).done(onSectionLoaded);

/*
    return Component.extend({
        initialize: function () {
            this._super();
            customerData.reload(['saved-size-attribute']).done(onSectionLoaded);
            // customerData.reload(['saved-size-attribute']);
            this['savedSizeAttribute'] = customerData.get('saved-size-attribute');
        }
    });
*/
});

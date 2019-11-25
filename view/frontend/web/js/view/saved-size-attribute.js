define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($, customerData) {
    'use strict';

    var widgetName = 'mage-SwatchRenderer';
    var sectionName = 'saved-size-attribute';

    function onSectionLoaded(sections) {
        var section = sections[sectionName];

        var valueId = section['value_id'];

        if (!valueId) {
            return;
        }

        var attributeCode = section['attribute_code'];

        var selectedAttributes = {};
        selectedAttributes[attributeCode] = valueId;

        setSwatchesAttributes(selectedAttributes);
    }

    function setSwatchesAttributes(selectedAttributes) {
        var renderers = $(':' + widgetNames);

        renderers.each(function (index, renderer) {
            var control = $(renderer).data(widgetName);
            control._EmulateSelected(selectedAttributes);
        })
    }

    return function () {
        customerData.reload([sectionName]).done(onSectionLoaded);
    };
});

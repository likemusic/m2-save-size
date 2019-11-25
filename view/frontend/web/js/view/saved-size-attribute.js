define([
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (Component, customerData) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            customerData.reload(['saved-size-attribute']);
            this['savedSizeAttribute'] = customerData.get('saved-size-attribute');
        }
    });
});

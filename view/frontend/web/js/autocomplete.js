/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_SearchAutocomplete
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

define([
    'jquery',
    'uiComponent',
    'ko'
], function ($, Component, ko) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'M2Commerce_SearchAutocomplete/autocomplete',
            showPopup: ko.observable(false),
            result: {
                product: {
                    data: ko.observableArray([]),
                    size: ko.observable(0),
                    url: ko.observable('')
                }
            },
            anyResultCount: false
        },


        initialize: function () {
            var self = this;
            this._super();

            this.anyResultCount = ko.computed(function () {
                var sum = self.result.product.data().length;

                return sum > 0;
            }, this);
        },

    });
});

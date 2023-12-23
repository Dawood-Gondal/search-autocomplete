/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_SearchAutocomplete
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

define([
    'jquery',
    'uiComponent',
    'uiRegistry',
    'underscore',
    'jquery/jquery-storageapi'
], function ($, Component, registry, _) {
    'use strict';

    $.Product = function (data) {
        this.name = data.name;
        this.sku = data.sku;
        this.image = data.image;
        this.description = data.description;
        this.url = data.url;
    };

    return Component.extend({
        defaults: {
            localStorage: $.initNamespaceStorage('searchsuiteautocomplete').localStorage,
            searchText: ''
        },

        load: function () {
            var self = this;

            if (this.xhr) {
                this.xhr.abort();
            }

            this.xhr = $.ajax({
                method: "get",
                dataType: "json",
                url: this.url,
                data: {q: this.searchText},
                beforeSend: function () {
                    self.spinnerShow();
                    if (self.loadFromLocalSorage(self.searchText)) {
                        self.showPopup();
                    }
                },
                success: $.proxy(function (response) {
                    self.parseData(response);
                    self.saveToLocalSorage(response, self.searchText);
                    self.spinnerHide();
                    self.showPopup();
                })
            });
        },

        showPopup: function () {
            registry.get('searchsuiteautocompleteBindEvents', function (binder) {
                binder.showPopup();
            });
        },

        spinnerShow: function () {
            registry.get('searchsuiteautocompleteBindEvents', function (binder) {
                binder.spinnerShow();
            });
        },

        spinnerHide: function () {
            registry.get('searchsuiteautocompleteBindEvents', function (binder) {
                binder.spinnerHide();
            });
        },

        parseData: function (response) {
            this.setProducts(this.getResponseData(response, 'product'));
        },

        getResponseData: function (response, code) {
            var data = []

            if (_.isUndefined(response.result)) {
                return data;
            }

            $.each(response.result, function (index, obj) {
                if (obj.code == code) {
                    data = obj;
                }
            });

            return data;
        },

        setProducts: function (productsData) {
            var products = [];

            if (!_.isUndefined(productsData.data)) {
                products = $.map(productsData.data, function (product) {
                    return new $.Product(product)
                });
            }

            registry.get('searchsuiteautocomplete_form', function (autocomplete) {
                autocomplete.result.product.data(products);
                autocomplete.result.product.size(productsData.size);
                autocomplete.result.product.url(productsData.url);
            });
        },

        loadFromLocalSorage: function (queryText) {
            if (!this.localStorage) {
                return;
            }

            var hash = this._hash(queryText);
            var data = this.localStorage.get(hash);

            if (!data) {
                return false;
            }

            this.parseData(data);

            return true;
        },

        saveToLocalSorage: function (data, queryText) {
            if (!this.localStorage) {
                return;
            }

            var hash = this._hash(queryText);

            this.localStorage.remove(hash);
            this.localStorage.set(hash, data);
        },

        _hash: function (object) {
            var string = JSON.stringify(object) + "";

            var hash = 0, i, chr, len;
            if (string.length == 0) {
                return hash;
            }
            for (i = 0, len = string.length; i < len; i++) {
                chr = string.charCodeAt(i);
                hash = ((hash << 5) - hash) + chr;
                hash |= 0;
            }
            return 'h' + hash;
        }

    });
});

var notie = require('./notie.min.js');
var $ = require('jquery');

App = {
    ajax_url: '',

    init: function()
    {
        if ($('#search-video-form').length > 0) {
            this.searchEvent();
        }

        if ($('tr#item').length > 0) {
            this.itemListEvents();
        }
    },

    searchEvent: function()
    {
        var __input = $('#search-input'),
            __button = $('#search-button');

        __button.on('click', function () {
            if (__input.val().length > 0) {
                $.ajax({
                    url: App.ajax_url + 'search/' + __input.val(),
                    type: 'GET',
                    success: function(data) {
                        if (data.success) {
                            $("#search-result").html(data.content);
                            App.addVideoEvent();
                        }
                    }
                });
            }
        })
    },

    addVideoEvent: function()
    {
        var __searchItem = $('tr.search-item');

        __searchItem.find('a.mdi-action-add').on('click', function (event) {
            event.preventDefault();

            var __code = $(this).closest("[data-code]").data("code"),
                __thumbnail = $(this).closest("[data-thumbnail]").data("thumbnail"),
                __title = $(this).closest("[data-title]").data("title");

            notie.confirm('Add this item?', 'Yes', 'Cancel', function() {
                $.ajax({
                    url: App.ajax_url + 'video/add',
                    type: 'POST',
                    dataType: 'json',
                    data: JSON.stringify({
                        'thumbnail': __thumbnail,
                        'title': __title,
                        'code': __code
                    }),
                    success: function(data) {
                        if (data.success) {
                            $('#search-item-' + __code).remove();
                        }
                    }
                });
            });
        });
    },

    itemListEvents: function()
    {
        var item = $('tr#item');

        item.find('a.mdi-action-enable, a.mdi-action-disable').on('click', function (event) {

            event.preventDefault();

            var id = $(this).closest("[data-item-id]").data("itemId"),
                __makeEnable = $(this).hasClass('mdi-action-enable');

            notie.confirm((__makeEnable ? 'Enable' : 'Disable') + ' this item?', 'Yes', 'Cancel', function() {
                $.ajax({
                    url: App.ajax_url + 'video/enable/' + id,
                    type: 'PUT',
                    dataType: 'json',
                    data: JSON.stringify({'enable': __makeEnable ? 1 : 0}),
                    success: function(data) {
                        if (data.success) {
                            $('.item-' + id + ' a.mdi-action-enable').prop('hidden', __makeEnable);
                            $('.item-' + id + ' a.mdi-action-disable').prop('hidden', !__makeEnable);
                            $('.item-' + id).toggleClass('table-danger', !__makeEnable);
                        }
                    }
                });
            });
        });
    }
};
$(function() {
    App.init();
});
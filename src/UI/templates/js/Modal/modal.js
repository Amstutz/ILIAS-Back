var il = il || {};
il.UI = il.UI || {};

(function($, UI) {

    UI.modal = (function ($) {

        var defaultShowOptions = {
            backdrop: true,
            keyboard: true,
            modalId: '',
            ajaxRenderUrl: '',
            ajaxRenderUrlParamsCode: function(){
                return "";
            }
        };

        var showModal = function (id, options) {
            options = $.extend(defaultShowOptions, options);
            if (options.ajaxRenderUrl) {
                var $container = $('#' + id);
                var func = new Function('modalId',options.ajaxRenderUrlParamsCode);
                options.ajaxRenderUrl += func(id);
                $container.load(options.ajaxRenderUrl, function() {
                    var $modal = $(this).find('.modal');
                    if ($modal.length) {
                        $modal.modal(options);
                    }
                });
            } else {
                var $modal = $('#' + id);
                $modal.modal(options);
            }
        };

        var closeModal = function(id) {
            $('#' + id).modal('hide');
        };

        var replaceModal = function(id, replacementSignal){
            $('#' + id).trigger(replacementSignal);
        };

        var showAsReplacement = function(id){
            renderLoadingOverlayOverPrevious
            renderSelfInSpanOfPrevious
            hidePrevious
        };

        return {
            showModal: showModal,
            closeModal: closeModal,
            replaceModal: replaceModal
        };

    })($);

})($, il.UI);
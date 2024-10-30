/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global coresocial_sharing_data*/

;(function($, window, document, undefined) {
    'use strict';

    window.wp = window.wp || {};
    window.wp.coresocial = window.wp.coresocial || {};

    window.wp.coresocial.core = {
        init: function() {
            $(document).on("click", "#coresocial-overlay-wrapper", function() {
                $(this).remove();
            });

            $(document).on("click", ".coresocial_social_network a", function(e) {
                var a = $(this).data("action"), n = $(this).data("network");

                if (a !== 'link') {
                    e.preventDefault();
                }

                if (a === 'share' || a === 'like' || a === 'show') {
                    wp.coresocial.core.call($(this));
                }

                if (a === 'share') {
                    var w = $(this).data("popup-width"),
                        h = $(this).data("popup-height"),
                        url = $(this).attr("href"),
                        left = ($(window).width() - w) / 2,
                        top = ($(window).height() - h) / 2,
                        data = {
                            action: a,
                            network: n,
                            item: $(this).data("item"),
                            item_id: $(this).data("item_id"),
                            url: $(this).data("url"),
                            title: $(this).data("title")
                        };

                    wp.hooks.doAction("coresocial-sharing-popup-ready", data, this);

                    var popup = window.open(url, "", "width=" + w + ", height=" + h + ", scrollbars=1, left=" + left + ", top=" + top);

                    wp.hooks.doAction("coresocial-sharing-popup-open", data, this, popup);

                    popup.focus();
                } else if (a === 'show') {
                    if (n === "qrcode") {
                        wp.coresocial.core.overlay();

                        const code = kjua({
                            render: 'svg',
                            text: $(this).data("url"),
                            ecLevel: 'Q',
                            quiet: 1,
                            size: 800,
                            fill: '#000',
                            back: '#FFF'
                        });

                        document.getElementById("coresocial-overlay-element").appendChild(code);

                        wp.hooks.doAction("coresocial-sharing-qrcode", this);
                    } else if (n === "printer") {
                        window.print();

                        wp.hooks.doAction("coresocial-sharing-print", this);
                    }
                }

                return a === 'link';
            });
        },
        url: function() {
            return coresocial_sharing_data.url + "?action=" + coresocial_sharing_data.handler;
        },
        call: function(el) {
            var args = {
                item: el.data("item"),
                id: el.data("id"),
                uid: el.parent().attr("id"),
                network: el.data("network"),
                action: el.data("action"),
                module: el.data("module"),
                url: el.data("url"),
                check: el.data("check")
            };

            $.ajax({
                url: this.url(),
                type: "post",
                dataType: "json",
                data: {
                    req: JSON.stringify(args),
                    nonce: coresocial_sharing_data.nonce
                },
                success: wp.coresocial.core.result,
                error: wp.coresocial.core.error
            });
        },
        result: function(json) {
            if (json.status === "ok") {
                var el = $("#" + json.uid), counts = json.count,
                    span = $("a > span", el);

                span.removeClass("__empty");

                if (span.find(".__count").length === 1) {
                    span.find(".__count").html(counts);
                } else {
                    span.append("<span class='__count'>" + counts + "</span>");
                }
            }
        },
        overlay: function() {
            $("body").append('<div id="coresocial-overlay-wrapper"><div class="__close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="#FFF" d="M13.7 34.3c-3.1-3.1-8.2-3.1-11.3 0s-3.1 8.2 0 11.3L212.7 256 2.3 466.3c-3.1 3.1-3.1 8.2 0 11.3s8.2 3.1 11.3 0L224 267.3 434.3 477.7c3.1 3.1 8.2 3.1 11.3 0s3.1-8.2 0-11.3L235.3 256 445.7 45.7c3.1-3.1 3.1-8.2 0-11.3s-8.2-3.1-11.3 0L224 244.7 13.7 34.3z"/></svg></div><div class="__inside"><div id="coresocial-overlay-element"></div></div></div>');
        },
        error: function(jqXhr, textStatus, errorThrown) {
            var json = {
                message: ""
            }, m = '', s = jqXhr.status;

            if (typeof jqXhr.responseJSON === "object") {
                if (jqXhr.responseJSON.hasOwnProperty("message")) {
                    json.message = jqXhr.responseJSON.message;
                }
            }

            if (json.message === "") {
                if (s === 0) {
                    m = "No internet connection.";
                } else if (s === 404) {
                    m = "Requested page not found.";
                } else if (s === 500) {
                    m = "Internal Server Error.";
                } else if (textStatus === "timeout") {
                    m = "Request timed out.";
                } else if (textStatus === "abort") {
                    m = "Request aborted.";
                } else {
                    m = "Uncaught Error: " + errorThrown;
                }
            } else {
                m = json.message;
            }

            var message = 'AJAX ERROR: CoreSocial Pro - (' + s + ') ' + m;

            if (window.console) {
                console.log(message);
            }
        }
    };

    window.wp.coresocial.core.init();
})(jQuery, window, document);

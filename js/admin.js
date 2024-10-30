/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */

;(function ($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.coresocial = window.wp.coresocial || {};

    window.wp.coresocial.admin = {
        init: function () {
            $(document).on("click", ".d4p-setting-social_profiles a.button-primary", function (e) {
                e.preventDefault();

                var list = $(this).closest(".d4p-setting-social_profiles"),
                    next = $(".d4p-next-id", list),
                    next_id = next.val(),
                    el = $(".profile-element-0", list).clone();

                $("input, select", el).each(function () {
                    var id = $(this).attr("id").replace("_0_", "_" + next_id + "_"),
                        name = $(this).attr("name").replace("[0]", "[" + next_id + "]");

                    $(this).attr("id", id).attr("name", name);

                    if ($(this).attr("type") === "hidden") {
                        $(this).val(next_id);
                    }
                });

                el.attr("class", "d4p-profile-element-wrapper profile-element-" + next_id).fadeIn();
                $(this).before(el);

                next_id++;
                next.val(next_id);
            });

            $(document).on("click", ".d4p-setting-social_profiles a.button-secondary", function (e) {
                e.preventDefault();

                $(this).closest(".d4p-profile-element-wrapper").remove();
            });
        }
    };

    window.wp.coresocial.admin.init();
})(jQuery, window, document);

var application = window.application || {

        init: function () {
            var functions = window.functions || [];

            for (var i = 0; i < functions.length; i++) {
                if (typeof functions[i] === 'function') {
                    functions[i](this);
                }
            }
        },

        config: {
            datetimeFormat: 'd.m.Y. H:i',
            locale: 'en'
        },

        initDatatables: function () {
            var self = this;
            //Datatables
            $('table.dataTable').dataTable({
                "pageLength": 30,
                "stateSave": true,
                "initComplete": function (settings, json) {
                    $(this).addClass('initialized');
                }
            });
        },

        initSelect2: function () {
            // Select2
            $('select').not('.noSelect2').not('.dataTables_wrapper select').select2();
        },

        initTooltips: function () {
            // Bootstrap tooltips
            $('.hasTooltip').tooltip();
        },

        initDatetimePicker: function () {
            var self = this;
            // Datetime picker
            $('.datetimepicker').datetimepicker({
                lang: self.config.locale,
                timepicker: true,
                format: self.config.datetimeFormat
            });
        },

        initSubmitLinksPlugin: function () {
            var self = this;

            $('a[data-submit-url]').on("click", function (e) {
                e.preventDefault();
                e.stopPropagation();

                var submitUrl = $(this).attr('data-submit-url');
                var method = $(this).attr('data-method');
                var callback = $(this).attr('data-callback');

                $.ajax({
                    method: method,
                    url: submitUrl,
                    dataType: "JSON"
                }).done(function (msg) {
                    if (msg.status == 1) {
                        switch (callback) {
                            case 'reload':
                                window.location.reload();
                                break;
                        }
                    }
                });

            })
        },

        confirmOnClick: function () {
            $('[data-confirm]').on("click", function (e) {
                if (!confirm($(this).attr('data-confirm'))) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });

        },

        initAdditionalBootstrap: function () {
            // Tab change on hover
            $('.nav-tabs > li.hoverActiveable > a').hover(function () {
                $(this).click();
            });

            $('.nav-tabs > li.dragActiveable > a').on('dragenter', function () {
                $(this).click();
            });
        },


        initMassAction: function(){
            $('.activateSelectedBtn').on("click", function () {
                doMass('activate');
            });

            $('.deactivateSelectedBtn').on("click", function () {
                doMass('deactivate');
            });

            $('.selectVisible').on("click", function () {
                $('tr td:first-child input:checkbox').click();
            });

            $('.deselectSelection').on("click", function () {
                $('tr td:first-child input:checkbox:checked').click();
            });

            $('.deleteSelectedBtn').on("click", function () {
                doMass('delete');
            });

        }
    };

/**
 * Reset and submit current form - used for clearing filters
 * @param elem
 */
function resetFormAndSubmit(elem) {
    var form = $(elem).closest('form');
    form.find('input,select').each(function () {
        $(this).val('');
    });
    form.submit();
}

/**
 * @param filename
 * @param filetype
 */
function loadjscssfile(filename, filetype) {
    if (filetype == "js") { //if filename is a external JavaScript file
        var fileref = document.createElement('script');
        fileref.setAttribute("type", "text/javascript");
        fileref.setAttribute("src", filename);
    }
    else if (filetype == "css") { //if filename is an external CSS file
        var fileref = document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("type", "text/css");
        fileref.setAttribute("href", filename)
    }
    if (typeof fileref != "undefined") {
        document.getElementsByTagName("head")[0].appendChild(fileref);
    }
}

$(function () {
    application.init();
});

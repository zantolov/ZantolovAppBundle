var application = window.application || {

        init: function () {
            var self = this;
            this.initAlertUnsaved();
            var functions = window.functions || [];

            for (var i = 0; i < functions.length; i++) {
                if (typeof functions[i] === 'function') {
                    functions[i](self);
                }
            }
        },

        config: {
            datetimeFormat: 'd.m.Y. H:i',
            dateFormat: 'd.m.Y.',
            locale: 'en',
            datatables: {
                language: {}
            }
        },

        initDatatables: function () {
            var self = this;
            var options = {
                "pageLength": 30,
                "stateSave": true,
                "initComplete": function (settings, json) {
                    $(this).addClass('initialized');
                }
            };

            if (self.config.datatables.language.url != undefined) {
                options.language = self.config.datatables.language;
            }
            console.log(options);
            //Datatables
            $('table.dataTable').dataTable(options);
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

            $('.datepicker').datetimepicker({
                lang: self.config.locale,
                timepicker: false,
                format: self.config.dateFormat
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


        initMassAction: function () {
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

        },

        initOnChangeSubmit: function () {
            $('.onChangeSubmit').on('change', function () {
                var form = $(this).closest('form');
                form.submit();
            });
        },

        initAlertUnsaved: function () {
            $elem = $('.unsavedAlertForm form');
            $elem.on('submit', function(){
                $(window).unbind('beforeunload.zantolov');
            });

            var formData = $elem.serialize();
            $(window).bind('beforeunload.zantolov', function (e) {
                if ($elem.serialize() != formData) {
                    return true;
                }
                else {
                    e = null; // i.e; if form state change show warning box, else don't show it.
                }
            });
        }
    };

window.application = application;

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

$(function () {
    application.init();
});



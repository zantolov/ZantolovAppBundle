var application = {

    init: function () {
        var functions = window.functions || [];

        for (var i = 0; i < functions.length; i++) {
            if (typeof functions[i] === 'function') {
                functions[i]();
            }
        }
    }
};

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

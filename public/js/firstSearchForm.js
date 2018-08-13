$('#form').addClass('form-inline');
$('button').css('marginLeft', '10px');

var idAttr = $('form').attr('id');
var idSplit = idAttr.split("_");
var idForm = idSplit[idSplit.length - 1];

$(document).ready(function() {
    $('.first_button_' + idForm).click(function () {
        $('#first_price_' + idForm).empty();

        $.ajax({
            url: /first-price/ + idForm,
            type: 'POST',
            dataType: 'html',
            data: $('#first_form_' + idForm).serialize(),
            success: function(response) {
                var result = $.parseJSON(response);

                if (result.currentPrice && !result.errorMessage) {
                    $('#first_price_' + idForm).append('<span class="font-weight-bold">Результат:</span> ' + result.currentPrice + ' грн&ensp;<i class="fas fa-check text-success"></i></p>');
                } else if (!result.currentPrice && result.errorMessage) {
                    $('#first_price_' + idForm).append('<span class="text-danger">' + result.errorMessage + '</p>');
                }
            },
            error: function(response) {
                $('#first_price_' + idForm).append('<span class="text-danger">Ошибка. Данные не отправлены</span>');
            }
        });

        return false;
    });
});
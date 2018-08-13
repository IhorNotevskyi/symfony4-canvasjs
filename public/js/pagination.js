$('.pagination li:first-child a').html('&laquo;&nbsp;Предыдущая');
$('.pagination li:last-child a').html('Следующая&nbsp;&raquo;');

if ($('.pagination li:first-child').hasClass('disabled')) {
    $('.pagination li:first-child span').html('&laquo;&nbsp;Предыдущая');
}
if ($('.pagination li:last-child').hasClass('disabled')) {
    $('.pagination li:last-child span').html('Следующая&nbsp;&raquo;');
}
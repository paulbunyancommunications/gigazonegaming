/**
 * Created by nelson_castillo on 11/8/16.
 */

$('table').on('click','.fa-eye',function() {
    $(this).removeClass("fa-eye").addClass('fa-eye-slash');
    var id = $(this).attr('id');
    $("." + id ).removeClass("printable").addClass("black");
});
$('table').on('click','.fa-eye-slash',function() {
    $(this).removeClass("fa-eye-slash").addClass('fa-eye');
    var id = $(this).attr('id');
    $("." + id).addClass("printable").removeClass("black");
});
$("#print_table").tablesorter();
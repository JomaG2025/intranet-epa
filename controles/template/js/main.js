$(function(){

//=============
//! Dropdowns
//=============

$('.dropdown').on('show.bs.dropdown', function(e){
$(this).find('.dropdown-menu').first().stop(true, true).slideDown();
});
  
$('.dropdown').on('hide.bs.dropdown', function(e){
$(this).find('.dropdown-menu').first().stop(true, true).slideUp();
});

//=====================
//! Listeners botones
//=====================

$('body').on('click', '.btn-eliminar', function(){
if(!confirm('Desea eliminar este elemento?')){
return false;
}
});

$('body').on('click', '.btn-cancelar', function(){
if(!confirm('Desea cancelar la operación actual?')){
return false;
}
});

//==============================
//! Override Bootstrap tooltip
//==============================

$('body').tooltip({
selector: '[data-toggle=tooltip]',
container: 'body'
});

$('form').submit(function(e){
$('[type=submit]', this).prop('disabled', true);
return true;
});
});

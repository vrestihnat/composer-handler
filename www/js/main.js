$(function () {
  $.nette.init();
  
//ksicht
  var actionClean = function () {
    $('#package_name_id').hide();
//    $('#package_name_id').find('input').val('');
    $('#expert_command_id').hide();
//    $('#expert_command_id').find('input').val('');
  }
  actionClean();
  $('#action_list_id').find('input').on('click', function (ev) {
    var val = $(this).val();
    actionClean();
    if (val * 1 === 2) {
      $('#package_name_id').show();
    } else if (val * 1 === 3) {
      $('#expert_command_id').show();
    }
  });


////ajax  
//  $('#frm-composerForm').on('submit', function (e) {
//    var isvalidate = Nette.validateForm(this);
//    if(isvalidate){
////      $(this).
//    }
//    return false;
//  });


$.nette.ext('snippets').after(function ($el) {
  alert(123);
});

});

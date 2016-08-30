require(['init'], function() {
  $(function() {
    if ($('.doForm').length) {
      return require(['form'], function(form) {
        return form.init();
      });
    }
  });
});

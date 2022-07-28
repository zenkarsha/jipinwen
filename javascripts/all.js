(function() {
  var countdown, createImage, currentTime;

  countdown = Date.now();

  currentTime = Date.now();

  createImage = function() {
    $('#loading').show();
    return $.ajax({
      url: 'generate',
      dataType: 'html',
      type: 'POST',
      data: {
        author: $('#author').val(),
        title: $('#title').val(),
        text: $('#text').val(),
        type: 1
      },
      success: function(result) {
        console.log(result);
        // $('#coverprint').html(result);
        // return $('#loading').hide();
      }
    });
  };

  $(function() {
    createImage();
    $('#default-submit').click(function() {
      $('#type').val('3');
      return $('#appform').attr('target', '_self').submit();
    });
    $('#showroom-submit').click(function() {
      $('#type').val('2');
      return $('#appform').attr('target', '_self').submit();
    });
    $('#example').on('change blur', function() {
      console.log($(this).val());
      if ($(this).val() !== '') {
        $('#text').val($(this).val());
        return createImage();
      }
    });
    $('body').delegate('#author,#title,#text', 'blur', function() {
      return createImage();
    });
    $('body').delegate('#author,#title,#text', 'keydown', function(e) {
      e.stopPropagation();
      return countdown = Date.now();
    });
    $('body').delegate('#author,#title,#text', 'keyup', function() {
      return setTimeout((function() {
        currentTime = Date.now();
        if ((currentTime - countdown) >= 240) {
          $('#loading').show();
          return createImage();
        }
      }), 250);
    });
    return $(document).keydown(function(e) {
      if (e.which === 68) {
        return $('#default-submit').trigger('click');
      } else if (e.which === 75) {
        return $('#showroom-submit').trigger('click');
      } else if (e.which === 80) {
        return $('#navbar-post-link span').trigger('click');
      } else if (e.which === 90) {
        return $('#navbar-showroom-link span').trigger('click');
      } else if (e.which === 77) {
        return $('#navbar-more-link span').trigger('click');
      }
    });
  });

}).call(this);

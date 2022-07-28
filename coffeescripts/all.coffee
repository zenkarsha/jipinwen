#################################
# Settings
#################################
countdown = Date.now()
currentTime = Date.now()


#################################
# Function
#################################
createImage = ->
  $('#loading').show()
  $.ajax
    url: 'generate'
    dataType: 'html'
    type: 'POST'
    data:
      author: $('#author').val()
      title: $('#title').val()
      text: $('#text').val()
      type: 1
    success: (result) ->
      $('#coverprint').html result
      $('#loading').hide()

#################################
# Document events
#################################
$ ->
  createImage();
  $('#default-submit').click ->
    $('#type').val '3'
    $('#appform').attr('target', '_self').submit()

  $('#showroom-submit').click ->
    $('#type').val '2'
    $('#appform').attr('target', '_self').submit()

  $('#example').on 'change blur', ->
    console.log($(this).val())
    if($(this).val() isnt '')
      $('#text').val($(this).val())
      createImage()

  $('body').delegate '#author,#title,#text', 'blur', ->
    createImage()

  $('body').delegate '#author,#title,#text', 'keydown', (e)->
    e.stopPropagation()
    countdown = Date.now()

  $('body').delegate '#author,#title,#text', 'keyup', ->
    setTimeout (->
      currentTime = Date.now()
      if (currentTime - countdown) >= 240
        $('#loading').show()
        createImage()
    ), 250

  $(document).keydown (e) ->
    if(e.which is 68)
      $('#default-submit').trigger('click')
    else if(e.which is 75)
      $('#showroom-submit').trigger('click')
    else if(e.which is 80)
      $('#navbar-post-link span').trigger('click')
    else if(e.which is 90)
      $('#navbar-showroom-link span').trigger('click')
    else if(e.which is 77)
      $('#navbar-more-link span').trigger('click')
    # else if(e.which is 89)
    #   $('#comment-submit').trigger('click')

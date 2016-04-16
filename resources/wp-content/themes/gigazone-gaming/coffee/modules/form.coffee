define ['jquery', 'Utility'], ($, Utility) ->
  form = {}
  
  form.init = ->
    return
    
  # if form.doGeoLocate is found get the location and create form inputs
  $ ->
    if "geolocation" of navigator
      glForm = $('form.doGeoLocate')
      if glForm.length
        navigator.geolocation.getCurrentPosition (position)->
          glForm.prepend('<input type="hidden" name="geo_lat" value="' + position.coords.latitude + '" />');
          glForm.prepend('<input type="hidden" name="geo_long" value="' + position.coords.longitude + '" />');
          return


  # on submit of an ajax form
  $('.doAjaxForm').on('submit', (e)->
    e.preventDefault()
    thisForm = $(this)
    fields = thisForm.serializeArray()
    message = thisForm.find('.message-container')
    url = thisForm.attr('action')
    method = thisForm.attr('method').toUpperCase()
    progress = thisForm.find('.progress-container')

    # initial state of form notification
    progress.show()
    message.html('').hide()

    $.ajax({
      data: fields
      url: url
      method: method
      dataType: "JSON"
      success: (data)->
        progress.hide()
        if data.hasOwnProperty('success')
          message.html('<div class="alert alert-success"><p>' + data.success.join() + '</p></div>').show()
          thisForm[0].reset()
        else if data.hasOwnProperty('error')
          message.html('<div class="alert alert-warning"><p>' + data.error.join() + '</p></div>').show()
      error:  (jqXHR, textStatus)->
        progress.hide()
        message.html('<div class="alert alert-danger"><p>Request failed: ' + textStatus + '</p></div>').show()
    })
    return true
  )
    
  return form
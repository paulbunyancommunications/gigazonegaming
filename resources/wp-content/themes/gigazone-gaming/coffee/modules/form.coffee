define ['jquery', 'Utility'], ($, Utility) ->
  form = {}
  
  form.init = ->

  # on submit of an ajax form
  $('.doAjaxForm').on('submit', (e)->
    e.preventDefault()
    thisForm = $(this)
    progress = thisForm.find('.progress-container')
    progress.show()
    message = thisForm.find('.message-container')
    message.html('').hide()
    $.ajax({
      data: thisForm.serialize()
      url: thisForm.attr('action')
      method: thisForm.attr('method').toUpperCase()
      dataType: "JSON"
    })
    .done (data)->
      progress.hide()
      if data.hasOwnProperty('success')
        message.html('<div class="alert alert-success"><p>' + data.success + '</p></div>').show()
        thisForm[0].reset()
      else if data.hasOwnProperty('error')
        message.html('<div class="alert alert-warning"><p>' + data.error.join() + '</p></div>').show()
    .fail (jqXHR, textStatus)->
      progress.hide()
      message.html('<div class="alert alert-danger"><p>Request failed: ' + textStatus + '</p></div>').show()
    return
  )
    
  return form
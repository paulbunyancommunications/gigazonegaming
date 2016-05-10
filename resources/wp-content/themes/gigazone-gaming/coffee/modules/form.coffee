define ['jquery', 'Utility'], ($, Utility) ->
  form = {}

  form.booleans = $('.boolean-group')
  form.ranges = $('.range-group')
  
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
          message.html('<div class="alert alert-success"><p>' + data.success.join(' ') + '</p></div>').show()
          thisForm[0].reset()
        else if data.hasOwnProperty('error')
          message.html('<div class="alert alert-warning"><p>' + data.error.join(' ') + '</p></div>').show()
      error:  (jqXHR, textStatus)->
        progress.hide()
        message.html('<div class="alert alert-danger"><p>Request failed: ' + textStatus + '</p></div>').show()
    })
    return true
  )

  if form.ranges.length
    require ['jquery','Slider'], ($, Slider) ->
      form.ranges.each ->
        thisSliderInput = $(this).find('input[type="text"]');
        thisSliderMin = $(this).find('.min')
        thisSliderMax = $(this).find('.max')
        thisSliderDefault = $(this).find('.default')
        thisSliderLabel = $(this).find('label')
        thisSliderInput.slider(
          {
            tooltip: 'show'
            tooltip_position: 'bottom'
            min : parseInt(thisSliderMin.text(), 10)
            max : parseInt(thisSliderMax.text(), 10)
            value : parseInt(thisSliderDefault.text(), 10)
            labelledby : thisSliderLabel.attr('id')
          }
        );

  if form.booleans.length
    require ['jquery','Switch'], ($, Switch) ->
      form.booleans.each ->
        thisSwitch = $(this).find('input.boolean');
        if thisSwitch.length > 0
          thisSwitch.bootstrapSwitch({
            state: false
            onText: "Yes"
            offText: "No"
            wrapperClass: "yesNoInput"
          });
          thisSwitch.on('switchChange.bootstrapSwitch', (event, state)->
            $(this).attr('checked', state);
            if state
              $(this).val('Yes')
            else
              $(this).val('')
    
          )
  return form
define ['jquery', 'underscore', 'Utility'], ($, _, Utility) ->
  form = {}

  form.booleans = $('.boolean-group')
  form.ranges = $('.range-group')
  form.metaRequestToken = $('meta[name="request_token"]')
  form.messageKeys = ['success','warning','error','info']
  
  form.init = ->
    form.getCsrfToken()
    $('#doFormSubmit').removeClass('disabled')
    $('#doFormSubmit').removeAttr('disabled')
    return

  # get the csrf token add add it to the jquery ajax setup
  form.getCsrfToken = ->
    $.get('/app/frontend/session/csrf', (csrf) ->
      #$('body').append('<div style="color: #000000; background: #ffffff; z-index: 99999; position: absolute; top: 0; left: 0;">' + csrf + '</div>')
      if(form.metaRequestToken.length)
        form.metaRequestToken.attr('content', csrf)
      else
        $('head').append('<meta content="' + csrf + '" name="request_token">')
        form.metaRequestToken = $('meta[name="request_token"]')
      return
    )

  if($('meta[name=env]').attr("content") == "local")
    $('#hidden')
      .css({
          'display': 'block',
          'color':'#ff0000',
          'font-size':'20px',
          'width':'500px',
          'height':'40px'
          })
      .prop(
          'type', 'text'
          )
  form.csrfToken = ->
    return form.metaRequestToken.attr('content');

  # if form.doGeoLocate is found get the location and create form inputs
  $ ->
    if "geolocation" of navigator
      glForm = $('form.doGeoLocate')
      if glForm.length
        navigator.geolocation.getCurrentPosition (position)->
          glForm.prepend('<input type="hidden" name="geo_lat" value="' + position.coords.latitude + '" />');
          glForm.prepend('<input type="hidden" name="geo_long" value="' + position.coords.longitude + '" />');
          return

  form.getRequestToken = ->

  # on submit of an ajax form
  $('.doAjaxForm').on('submit', (e)->
    e.preventDefault()
    thisForm = $(this)
    fields = thisForm.serializeArray()
    message = thisForm.find('.message-container')
    url = thisForm.attr('action')
    method = thisForm.attr('method').toUpperCase()
    progress = thisForm.find('.progress-container')

    # make sure the form has an id
    if !thisForm.attr('id')
      thisForm.attr('id', Utility.makeid)

    formId = thisForm.attr('id')

    # check and see if there's already a container to hold the raw response
    if $('#' + formId + '-response-container').length
      $('#' + formId + '-response-container').html('')
    else
      $('body').append('<div id="' + formId + '-response-container" style="display: none"></div>')

    formIdResponseContainer = $('#' + formId + '-response-container')

    # initial state of form notification
    progress.show()
    message.html('').hide()
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': form.csrfToken()
      }
    })

    $.ajax({
      data: fields
      url: url
      method: method
      dataType: "JSON"
      success: (data)->
        formIdResponseContainer.text(window.JSON.stringify(data))
        progress.hide()
        for messageKey in form.messageKeys
          if data.hasOwnProperty(messageKey)
            switch messageKey
              when "success"
                message.html('<div class="alert alert-success"><p>' + data.success.join('<br>') + '</p></div>').show()
                thisForm[0].reset()
              when "warning", "error", "info"
                message.html('<div class="alert alert-' + messageKey + '"><p>' + data[messageKey].join('<br>') + '</p></div>').show()
      error:  (jqXHR, textStatus)->
        progress.hide()
        try
          jqXHRResponseText=JSON.parse(jqXHR.responseText);
          mId = Utility.makeid();
          message.html('<div class="alert alert-danger"><p id="' + mId + '"></p></div>')
          #get the inputs and check if there's a key on the response array with it
          thisForm.find('input, textarea').each ->
            if jqXHRResponseText.hasOwnProperty($(this).attr('name'))
              $('#' + mId).append(jqXHRResponseText[$(this).attr('name')].join('<br>') + '<br>')
          message.show()
        catch e
          message.html('<div class="alert alert-danger"><p>Request failed: ' + textStatus + '</p></div>').show()
        if($('meta[name=env]').attr("content") == "local")
          message.html('<div id="errorChecker" class="alert alert-danger"><p>Request failed: ' + textStatus + '</p><p>' + jqXHR.responseText + '</p></div>').show()
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
              $(this).val('yes')
            else
              $(this).val('')
          )
  return form
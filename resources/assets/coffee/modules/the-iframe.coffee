define ['jquery'], ($) ->
  theIframe = {}

  theIframe.init = ->
    $('#theIframe', window.parent.document)
      .height(Math.floor($('#page-content').height() * 1.0375))
      .css({
        'min-height': Math.floor($('#page-content').height() * 1.0375) + 'px'
        'overflow' : 'hidden'
      })
      .attr('scrolling', 'no')

    # @todo What does this line below do?
    toAdd = '</div><div id="pageLinker2" class="btn-group btn-group-justified" role="group" aria-label="Justified button group">';

  theIframe
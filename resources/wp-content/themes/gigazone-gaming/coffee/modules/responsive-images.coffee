define ['jquery', 'imager', 'variables'], ($, Imager, variables) ->
  responsiveImages = {}

  responsiveImages.int = ->
    new Imager('.responsive-image', {
      availableWidths: variables.boostrapBreakPoints,
      onResize: true
    });

  return responsiveImages
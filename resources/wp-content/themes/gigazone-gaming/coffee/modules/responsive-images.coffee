define ['jquery', 'imager', 'variables', 'functions'], ($, Imager, variables, Functions) ->
  responsiveImages = {}

  responsiveImages.int = ->
    new Imager('.responsive-image', {
      availableWidths: variables.boostrapBreakPoints,
      onResize: true
    }).ready(->
      # give the rotator images and ID
      $(".image-replace").each(->
        thisImageReplace = $(this)
        id = "responsive-image-" + Functions.makeid()
        thisImageReplace.attr 'id', id
      )
    );

  return responsiveImages
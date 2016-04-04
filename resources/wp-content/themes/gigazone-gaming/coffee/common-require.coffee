themePath = "/wp-content/themes/gigazone-gaming"
require =
  baseUrl: themePath+'/js'
  paths:
    #third party libraries
    jquery: [ "../../../../bower_components/jquery/dist/jquery.min" ]
    bootstrap: [ "../../../../bower_components/bootstrap/dist/js/bootstrap.min" ]
    underscore: [ "../../../../bower_components/underscore/underscore-min" ]
    handlebars: [ "../../../../bower_components/handlebars/handlebars.min" ]
    imager: [ "../../../../bower_components/imager.js/dist/Imager.min" ]
    responsiveSlides: [ "../../../../bower_components/ResponsiveSlides/responsiveslides.min" ]
    iFrameResize: [ "../../../../bower_components/iframe-resizer/js/iframeResizer.min" ]
    # modules
    variables: ["variables"]
    functions: ["functions"]
    mainNavigation: ["modules/main-navigation"]
    responsiveImages: ["modules/responsive-images"]
    photoRotator: ["modules/photo-rotator"]
    links: ["modules/links"]
    stickyFooter: ["modules/sticky-footer"]
    searchResults: ["modules/search-results"]
  shim:
    "bootstrap": [ "jquery" ]
    "responsiveSlides": [ "jquery" ]
    "iFrameResize": [ "jquery" ]
    "underscore": exports: "_"
    "handlebars": exports: "Handlebars"
    "imager": exports: "Imager"
  priority: [ "jquery" ]
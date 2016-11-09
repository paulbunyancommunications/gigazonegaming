themePath = "/app/content"
require =
  baseUrl: themePath+'/js'
  paths:
    #third party libraries
    jquery: [ "../../../bower_components/jquery/dist/jquery.min" ]
    bootstrap: [ "../../../bower_components/bootstrap/dist/js/bootstrap.min" ]
    underscore: [ "../../../bower_components/underscore/underscore-min" ]
    handlebars: [ "../../../bower_components/handlebars/handlebars.min" ]
    select2: [ "../../../bower_components/select2/dist/js/select2.full.min" ]
    # modules
    init: ["init"]
    Utility: ["../../../wp-content/themes/gigazone-gaming/js/modules/Utility"]
    form: ["../../../wp-content/themes/gigazone-gaming/js/modules/form"]
    searchBar: ["modules/search-bar"]
    theIframe: ["modules/the-iframe"]
  waitSeconds: 0
  shim:
    "bootstrap": [ "jquery" ]
    "underscore": exports: "_"
    "handlebars": exports: "Handlebars"
  priority: [ "jquery" ]
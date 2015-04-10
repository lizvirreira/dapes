$(function() {
    var widgetNames = ["slider"];
    var loadedWidgets = {};
    var coreWidgets = ["slider"];

    // Allow tab to be selected through deep linking
    var selectedTabId = 0;
    var query = document.location.search;
    var fragmentId = document.location.hash;
    if (query) {
        var match = query.match(/(\\?|&)tabId=([^&]+)(\\&|$)/i);
        if (match && match[2]) {
            if (!isNaN(match[2]))                      
                selectedTabId = match[2];
            else
                selectedTabId = jQuery.inArray(match[2], widgetNames);
            if (selectedTabId == -1)
                selectedTabId = 0;
        }
    }
    else if (fragmentId) {
        var match = fragmentId.match(/^#goto_(.+)$/i);
        if (match && match[1]) {
            if (!isNaN(match[1]))
                selectedTabId = match[1];
            else
                selectedTabId = jQuery.inArray(match[1], widgetNames);
            if (selectedTabId == -1)
                selectedTabId = 0;
        }

    }

    //experimental: GLobal focusin handler that assigns focus classnames

    $(document).focusin(function(event){
    	$(event.target).addClass("ui-global-focus");
    })
    .focusout(function(event){
    	$(event.target).removeClass("ui-global-focus");
    });

    //Load main tabs
    $("#demoTabs").tabs({labelledBy: "tabsLbl", selected : selectedTabId});

    //show event doesn't fire for selected tab on creation, so manually trigger the handler
    createTabPanelContents($("#demoTabs > .ui-tabs-panel").eq(selectedTabId));

    setTimeout(function() {
        createSliders($("#slider"));
    }, 500);


    $('#demoTabs').bind('tabsactivate', function(event, ui) {
        createTabPanelContents($(ui.newPanel));
        if ($(ui.oldPanel).attr("id") == "tooltip")
            $(".toggleTooltips :ui-tooltip").tooltip("close");
    });

    function createTabPanelContents(panel, skipLoadedCheck) {
        if (!panel || panel.length == 0)
            return;
        var widgetId = $(panel).attr("id");
        document.location.hash = "#goto_" + widgetId;
        if (!widgetId || (loadedWidgets[widgetId] & !skipLoadedCheck) || $.inArray(widgetId, widgetNames) == -1)
            return;

        switch(widgetId) {
        	
        	case "slider":
                createSliders(panel);
                break;

            default: //No special logic required, simply call component's method on demo objects in tab poanel
                autoCreateInPanel(panel);
        }
        loadedWidgets[widgetId] = true;
    }

    function autoCreateInPanel(panel) {
        var widgetId = panel.attr("id");
        var elements = panel.find(".demoWidget");
        if (typeof elements[widgetId] == "function")
            elements[widgetId]();
    }

    function destroyInPanel(panel) {
        if (!panel || panel.length === 0)
            return;
        var widgetId = panel.attr("id");
        if (!widgetId || $.inArray(widgetId, coreWidgets) == -1)
            return;

        switch (widgetId) {
        	
            case "slider":
                autoDestroyInPanel(panel);
                destroySliders(panel);
                break;
            default:
                autoDestroyInPanel(panel);
                break;
        }
    }

    function autoDestroyInPanel(panel) {
        var widgetId = panel.attr("id");
        var elements = panel.find(".demoWidget");
        if (typeof elements[widgetId] == "function")
            elements[widgetId]("destroy");
    }

    function toggleEnabledInPanel(panel, enable) {
        var widgetId = panel.attr("id");

        switch (widgetId) {
            case "button":
                autoToggleEnabled(panel, enable);
                $("#repeat :radio").button(enable ? "enable" : "disable");
                break;
            default:
                autoToggleEnabled(panel, enable);
                break;
        }
    }

    function autoToggleEnabled(panel, enable) {
        var elements = panel.find(".demoWidget");
        var widgetId = panel.attr("id");
        if (typeof elements[widgetId] == "function")
            elements[widgetId](enable ? "enable" : "disable");
    }

    //SLIDER

    function createSliders(panel)  {
        //single slider
          $(panel).find(".fallback").hide();

          $("#singleSlider1").slider({unittext : "MB",
              label : "Altitud",
              unittext: "",
              slide: function(event, ui) {
              updateSliderLabels(ui, ["#slider1Val"]);
                  },
              change : function(event, ui) {
                      updateSliderLabels(ui, ["#slider1Val"]);
                  }
          });

          setTimeout( function() {
              $(panel).find(".sliderValue").show();
              updateSliderLabels({value : $("#singleSlider1").slider("value"), handle : $("#singleSlider1").find(".ui-slider-handle").eq(0)}, ["#slider1Val"]);
              updateSliderLabels({value : $("#singleSlider1").slider("value"), handle : $("#singleSlider1").find(".ui-slider-handle").eq(0)}, ["#slider1Val"]);
          }, 100);

          // range slider
          var rangeSlider = $("#rangeSlider1")
          .slider({
              range: true,
              min: 0,
              max: 4500,
              values: [200, 3300],
              label: "Altitud",
              slide: function(event, ui) {
              updateSliderLabels(ui, ["#slider2ValMin", "#slider2ValMax"]);
              },
              change : function(event, ui) {
                  updateSliderLabels(ui, ["#slider2ValMin", "#slider2ValMax"]);
              }
          });
          setTimeout(function() {
              var sliderValues = rangeSlider.slider("values");
              updateSliderLabels({value : sliderValues[0], values : sliderValues, handle : rangeSlider.find(".ui-slider-handle").eq(0)}, ["#slider2ValMin", "#slider2ValMax"]);
              updateSliderLabels({value : sliderValues[1], values : sliderValues, handle : rangeSlider.find(".ui-slider-handle").eq(1)}, ["#slider2ValMax", "#slider2ValMax"]);
              // need to do this twice for some reason, va;ue is not properly positioned otherwise
              updateSliderLabels({value : sliderValues[0], values : sliderValues, handle : rangeSlider.find(".ui-slider-handle").eq(0)}, ["#slider2ValMin", "#slider2ValMax"]);
              updateSliderLabels({value : sliderValues[1], values : sliderValues, handle : rangeSlider.find(".ui-slider-handle").eq(1)}, ["#slider2ValMin", "#slider2ValMax"]);
          }, 100);
          
          
          var rangeSlider2 = $("#rangeSlider2")
          .slider({
              range: true,
              min: 300,
              max: 8000,
              values: [1300, 2500],
              unittext: "",
              label: "Precipitacion",
              slide: function(event, ui) {
              updateSliderLabels(ui, ["#slider2ValMin2", "#slider2ValMax2"]);
              },
              change : function(event, ui) {
                  updateSliderLabels(ui, ["#slider2ValMin2", "#slider2ValMax2"]);
              }
          });
          setTimeout(function() {
              var sliderValues = rangeSlider2.slider("values");
              updateSliderLabels({value : sliderValues[0], values : sliderValues, handle : rangeSlider2.find(".ui-slider-handle").eq(0)}, ["#slider2ValMin2", "#slider2ValMax2"]);
              updateSliderLabels({value : sliderValues[1], values : sliderValues, handle : rangeSlider2.find(".ui-slider-handle").eq(1)}, ["#slider2ValMin2", "#slider2ValMax2"]);
              // need to do this twice for some reason, va;ue is not properly positioned otherwise
              updateSliderLabels({value : sliderValues[0], values : sliderValues, handle : rangeSlider2.find(".ui-slider-handle").eq(0)}, ["#slider2ValMin2", "#slider2ValMax2"]);
              updateSliderLabels({value : sliderValues[1], values : sliderValues, handle : rangeSlider2.find(".ui-slider-handle").eq(1)}, ["#slider2ValMin2", "#slider2ValMax2"]);
          }, 100);
          
    }

    function updateSliderLabels(ui, valueLabels) {
        if (!ui.values)
            ui.values = [ui.value];
        // need to be able to determine which of the handles actually changes
        var index = $.inArray(ui.value, ui.values);
        var myAlign = index == 0 ? "right" : "left";
        var atAlign = index == 0 ? "left" : "right";
            $(valueLabels[index])
                .position({
                    my: myAlign + " bottom",
                    at : atAlign + " top",
                    of: ui.handle
                    })
                .text(ui.value);
            return;
   }

    function destroySliders(panel)  {
        panel.find(".fallback").show();
        panel.find("demoWidget").slider("destroy");
        panel.find(".sliderValue").hide();
    }


});
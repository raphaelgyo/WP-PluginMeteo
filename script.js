var widgetchoice = value.widgetchoice;
var city = value.idcity;
var key = value.key;

window.myWidgetParam ? window.myWidgetParam : (window.myWidgetParam = []);
window.myWidgetParam.push({
    id: widgetchoice,
    cityid: city,
    appid: key,
    // units: 'metric',
    containerid: 'openweathermap-widget',
    // containerid: 'openweathermap-widget-', + widgetchoice,
});
(function () {
    var script = document.createElement('script');
    script.async = true;
    script.charset = 'utf-8';
    script.src =
        '//openweathermap.org/themes/openweathermap/assets/vendor/owm/js/weather-widget-generator.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(script, s);
})();

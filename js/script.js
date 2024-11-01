(function($) {
var autoplay = $('#autoplay').text();
var stop = $('#stop').text();
function ws_fade(c,a,b){var e=jQuery;var d=e("ul",b);var f={position:"absolute",left:0,top:0,width:"100%",height:"100%"};this.go=function(g,h){var i=e(a.get(g)).clone().css(f).hide().appendTo(b);if(!c.noCross){var j=e(a.get(h)).clone().css(f).appendTo(b);d.hide();j.fadeOut(c.duration,function(){j.remove()})}i.fadeIn(c.duration,function(){d.css({left:-g+"00%"}).show();i.remove()});return g}};
wowReInitor(jQuery("#spyro-container1"),{effect:"fade",prev:"",next:"",duration:10*100,delay:30*100,autoPlay:autoplay,stopOnHover:stop,loop:false,bullets:true,caption:true,captionEffect:"move",controls:true,logo:"engine1/loading.gif",images:0});
})(jQuery);
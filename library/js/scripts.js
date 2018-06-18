/*
 * Bones Scripts File
 * Author: Eddie Machado
 *
 * This file should contain any js scripts you want to add to the site.
 * Instead of calling it in the header or throwing it inside wp_head()
 * this file will be called automatically in the footer so as not to
 * slow the page load.
 *
 * There are a lot of example functions and tools in here. If you don't
 * need any of it, just remove it. They are meant to be helpers and are
 * not required. It's your world baby, you can do whatever you want.
*/


/*
 * Get Viewport Dimensions
 * returns object with viewport dimensions to match css in width and height properties
 * ( source: http://andylangton.co.uk/blog/development/get-viewport-size-width-and-height-javascript )
*/
function updateViewportDimensions() {
	var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
	return { width:x,height:y };
}
// setting the viewport width
var viewport = updateViewportDimensions();


/*
 * Throttle Resize-triggered Events
 * Wrap your actions in this function to throttle the frequency of firing them off, for better performance, esp. on mobile.
 * ( source: http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed )
*/
var waitForFinalEvent = (function () {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) { uniqueId = "Don't call this twice without a uniqueId"; }
		if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();

// how long to wait before deciding the resize has stopped, in ms. Around 50-100 should work ok.
var timeToWaitForLast = 100;


/*
 * Here's an example so you can see how we're using the above function
 *
 * This is commented out so it won't work, but you can copy it and
 * remove the comments.
 *
 *
 *
 * If we want to only do it on a certain page, we can setup checks so we do it
 * as efficient as possible.
 *
 * if( typeof is_home === "undefined" ) var is_home = $('body').hasClass('home');
 *
 * This once checks to see if you're on the home page based on the body class
 * We can then use that check to perform actions on the home page only
 *
 * When the window is resized, we perform this function
 * $(window).resize(function () {
 *
 *    // if we're on the home page, we wait the set amount (in function above) then fire the function
 *    if( is_home ) { waitForFinalEvent( function() {
 *
 *	// update the viewport, in case the window size has changed
 *	viewport = updateViewportDimensions();
 *
 *      // if we're above or equal to 768 fire this off
 *      if( viewport.width >= 768 ) {
 *        console.log('On home page and window sized to 768 width or more.');
 *      } else {
 *        // otherwise, let's do this instead
 *        console.log('Not on home page, or window sized to less than 768.');
 *      }
 *
 *    }, timeToWaitForLast, "your-function-identifier-string"); }
 * });
 *
 * Pretty cool huh? You can create functions like this to conditionally load
 * content and other stuff dependent on the viewport.
 * Remember that mobile devices and javascript aren't the best of friends.
 * Keep it light and always make sure the larger viewports are doing the heavy lifting.
 *
*/

/*
 * We're going to swap out the gravatars.
 * In the functions.php file, you can see we're not loading the gravatar
 * images on mobile to save bandwidth. Once we hit an acceptable viewport
 * then we can swap out those images since they are located in a data attribute.
*/
function loadGravatars() {
  // set the viewport using the function above
  viewport = updateViewportDimensions();
  // if the viewport is tablet or larger, we load in the gravatars
  if (viewport.width >= 768) {
  jQuery('.comment img[data-gravatar]').each(function(){
    jQuery(this).attr('src',jQuery(this).attr('data-gravatar'));
  });
	}
} // end function


/*
 * Put all your regular jQuery in here.
*/
jQuery(document).ready(function($) {

  /*
   * Let's fire off the gravatar function
   * You can remove this if you don't need it
  */
  loadGravatars();


}); /* end of as page load scripts */

// make global players object
var players = {}
document.addEventListener("DOMContentLoaded", function(event) { 
  if (document.getElementsByClassName('slide_gallery').length > 0){
    jQuery('.slide_gallery').slick({
      dots: false,
      infinite: true,
      arrows: true,
      prevArrow: document.getElementsByClassName('prev')[0],
      nextArrow: document.getElementsByClassName('next')[0],
      speed: 250,
      fade: true,
      cssEase: 'linear'
    });

    // if there is a youtube video
    if (document.getElementsByClassName('youtube').length > 0) {
      
      loadVideos();

      // add listeners to the arrows
      document.getElementsByClassName('prev')[0].addEventListener('click', function(e){
        var next_slide = document.getElementsByClassName('slick-current')[0].nextSibling;
        if (next_slide.children[0].classList.contains('youtube')) {
          var player = players[next_slide.children[0].dataset.videoid];
          player.pauseVideo();
        }
      });
      document.getElementsByClassName('next')[0].addEventListener('click', function(e){
        var prev_slide = document.getElementsByClassName('slick-current')[0].previousSibling;
        if (prev_slide.children[0].classList.contains('youtube')) {
          var player = players[prev_slide.children[0].dataset.videoid];
          player.pauseVideo();
        }
      });
      
    }    
  }
});



function loadVideos(){
  // ensure api is finished loading
  if (YT.loaded !== 1) {
    setTimeout(loadVideos, 50);
    return;
  }
  // set up youtube api for each player
  var videos = document.getElementsByClassName('youtube');
 
  for (var i = 0; i < videos.length; i++) {
    // if the video id is set
    
    var player = new YT.Player(videos[i], {
      videoId: videos[i].id,
      width: '100%',
      height: '500px'
    });
    players[videos[i].id] = player;
    
  }
}

function change_gallery_target( change_by ){
  var this_gallery = this;
  var current_index = this_gallery.dataset.imageTarget * 1;
  var index = current_index + change_by;
  var image_lis = this_gallery.children[1];
  if( index < 0 || index >= image_lis.children.length )
    return false;
  for( var i = 0; i < image_lis.children.length; i++ ){
    image_lis.children[i].classList.remove('current');
  }
  image_lis.children[index].classList.add('current');
  this_gallery.dataset.imageTarget = index;
}

/*
this.parentElement.children[1].style.left += this.parentElement.children[1].clientWidth;
*/
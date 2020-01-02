/*
 * Purpose: Provides 'LinkedIn-like endorsements' functionality for use on user profiles and handles ajax for profile edits
 *
 * License: GPL v2.0
 * Full license here: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Author: Bryden Arndt
 * email: bryden@arndt.ca
 */

/*
 * Purpose: initialize the script
 */

/*** CANVAS ***/

var mul_table = [
        512,512,456,512,328,456,335,512,405,328,271,456,388,335,292,512,
        454,405,364,328,298,271,496,456,420,388,360,335,312,292,273,512,
        482,454,428,405,383,364,345,328,312,298,284,271,259,496,475,456,
        437,420,404,388,374,360,347,335,323,312,302,292,282,273,265,512,
        497,482,468,454,441,428,417,405,394,383,373,364,354,345,337,328,
        320,312,305,298,291,284,278,271,265,259,507,496,485,475,465,456,
        446,437,428,420,412,404,396,388,381,374,367,360,354,347,341,335,
        329,323,318,312,307,302,297,292,287,282,278,273,269,265,261,512,
        505,497,489,482,475,468,461,454,447,441,435,428,422,417,411,405,
        399,394,389,383,378,373,368,364,359,354,350,345,341,337,332,328,
        324,320,316,312,309,305,301,298,294,291,287,284,281,278,274,271,
        268,265,262,259,257,507,501,496,491,485,480,475,470,465,460,456,
        451,446,442,437,433,428,424,420,416,412,408,404,400,396,392,388,
        385,381,377,374,370,367,363,360,357,354,350,347,344,341,338,335,
        332,329,326,323,320,318,315,312,310,307,304,302,299,297,294,292,
        289,287,285,282,280,278,275,273,271,269,267,265,263,261,259];
        
   
var shg_table = [
	     9, 11, 12, 13, 13, 14, 14, 15, 15, 15, 15, 16, 16, 16, 16, 17, 
		17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 18, 18, 18, 18, 18, 19, 
		19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 20, 20, 20,
		20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 21,
		21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21,
		21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22, 
		22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22,
		22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 23, 
		23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23,
		23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23,
		23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 
		23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 
		24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24,
		24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24,
		24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24,
		24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24 ];

function stackBlurCanvasRGBA( canvas, top_x, top_y, width, height, radius )
{
	if ( isNaN(radius) || radius < 1 ) return;
	radius |= 0;
	
	var context = canvas.getContext("2d");
	var imageData;
	
	try {
	  try {
		imageData = context.getImageData( top_x, top_y, width, height );
	  } catch(e) {
	  
		// NOTE: this part is supposedly only needed if you want to work with local files
		// so it might be okay to remove the whole try/catch block and just use
		// imageData = context.getImageData( top_x, top_y, width, height );
		try {
			netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead");
			imageData = context.getImageData( top_x, top_y, width, height );
		} catch(e) {
			alert("Cannot access local image");
			throw new Error("unable to access local image data: " + e);
			return;
		}
	  }
	} catch(e) {
	  alert("Cannot access image");
	  throw new Error("unable to access image data: " + e);
	}
			
	var pixels = imageData.data;
			
	var x, y, i, p, yp, yi, yw, r_sum, g_sum, b_sum, a_sum, 
	r_out_sum, g_out_sum, b_out_sum, a_out_sum,
	r_in_sum, g_in_sum, b_in_sum, a_in_sum, 
	pr, pg, pb, pa, rbs;
			
	var div = radius + radius + 1;
	var w4 = width << 2;
	var widthMinus1  = width - 1;
	var heightMinus1 = height - 1;
	var radiusPlus1  = radius + 1;
	var sumFactor = radiusPlus1 * ( radiusPlus1 + 1 ) / 2;
	
	var stackStart = new BlurStack();
	var stack = stackStart;
	for ( i = 1; i < div; i++ )
	{
		stack = stack.next = new BlurStack();
		if ( i == radiusPlus1 ) var stackEnd = stack;
	}
	stack.next = stackStart;
	var stackIn = null;
	var stackOut = null;
	
	yw = yi = 0;
	
	var mul_sum = mul_table[radius];
	var shg_sum = shg_table[radius];
	
	for ( y = 0; y < height; y++ )
	{
		r_in_sum = g_in_sum = b_in_sum = a_in_sum = r_sum = g_sum = b_sum = a_sum = 0;
		
		r_out_sum = radiusPlus1 * ( pr = pixels[yi] );
		g_out_sum = radiusPlus1 * ( pg = pixels[yi+1] );
		b_out_sum = radiusPlus1 * ( pb = pixels[yi+2] );
		a_out_sum = radiusPlus1 * ( pa = pixels[yi+3] );
		
		r_sum += sumFactor * pr;
		g_sum += sumFactor * pg;
		b_sum += sumFactor * pb;
		a_sum += sumFactor * pa;
		
		stack = stackStart;
		
		for( i = 0; i < radiusPlus1; i++ )
		{
			stack.r = pr;
			stack.g = pg;
			stack.b = pb;
			stack.a = pa;
			stack = stack.next;
		}
		
		for( i = 1; i < radiusPlus1; i++ )
		{
			p = yi + (( widthMinus1 < i ? widthMinus1 : i ) << 2 );
			r_sum += ( stack.r = ( pr = pixels[p])) * ( rbs = radiusPlus1 - i );
			g_sum += ( stack.g = ( pg = pixels[p+1])) * rbs;
			b_sum += ( stack.b = ( pb = pixels[p+2])) * rbs;
			a_sum += ( stack.a = ( pa = pixels[p+3])) * rbs;
			
			r_in_sum += pr;
			g_in_sum += pg;
			b_in_sum += pb;
			a_in_sum += pa;
			
			stack = stack.next;
		}
		
		
		stackIn = stackStart;
		stackOut = stackEnd;
		for ( x = 0; x < width; x++ )
		{
			pixels[yi+3] = pa = (a_sum * mul_sum) >> shg_sum;
			if ( pa != 0 )
			{
				pa = 255 / pa;
				pixels[yi]   = ((r_sum * mul_sum) >> shg_sum) * pa;
				pixels[yi+1] = ((g_sum * mul_sum) >> shg_sum) * pa;
				pixels[yi+2] = ((b_sum * mul_sum) >> shg_sum) * pa;
			} else {
				pixels[yi] = pixels[yi+1] = pixels[yi+2] = 0;
			}
			
			r_sum -= r_out_sum;
			g_sum -= g_out_sum;
			b_sum -= b_out_sum;
			a_sum -= a_out_sum;
			
			r_out_sum -= stackIn.r;
			g_out_sum -= stackIn.g;
			b_out_sum -= stackIn.b;
			a_out_sum -= stackIn.a;
			
			p =  ( yw + ( ( p = x + radius + 1 ) < widthMinus1 ? p : widthMinus1 ) ) << 2;
			
			r_in_sum += ( stackIn.r = pixels[p]);
			g_in_sum += ( stackIn.g = pixels[p+1]);
			b_in_sum += ( stackIn.b = pixels[p+2]);
			a_in_sum += ( stackIn.a = pixels[p+3]);
			
			r_sum += r_in_sum;
			g_sum += g_in_sum;
			b_sum += b_in_sum;
			a_sum += a_in_sum;
			
			stackIn = stackIn.next;
			
			r_out_sum += ( pr = stackOut.r );
			g_out_sum += ( pg = stackOut.g );
			b_out_sum += ( pb = stackOut.b );
			a_out_sum += ( pa = stackOut.a );
			
			r_in_sum -= pr;
			g_in_sum -= pg;
			b_in_sum -= pb;
			a_in_sum -= pa;
			
			stackOut = stackOut.next;

			yi += 4;
		}
		yw += width;
	}

	
	for ( x = 0; x < width; x++ )
	{
		g_in_sum = b_in_sum = a_in_sum = r_in_sum = g_sum = b_sum = a_sum = r_sum = 0;
		
		yi = x << 2;
		r_out_sum = radiusPlus1 * ( pr = pixels[yi]);
		g_out_sum = radiusPlus1 * ( pg = pixels[yi+1]);
		b_out_sum = radiusPlus1 * ( pb = pixels[yi+2]);
		a_out_sum = radiusPlus1 * ( pa = pixels[yi+3]);
		
		r_sum += sumFactor * pr;
		g_sum += sumFactor * pg;
		b_sum += sumFactor * pb;
		a_sum += sumFactor * pa;
		
		stack = stackStart;
		
		for( i = 0; i < radiusPlus1; i++ )
		{
			stack.r = pr;
			stack.g = pg;
			stack.b = pb;
			stack.a = pa;
			stack = stack.next;
		}
		
		yp = width;
		
		for( i = 1; i <= radius; i++ )
		{
			yi = ( yp + x ) << 2;
			
			r_sum += ( stack.r = ( pr = pixels[yi])) * ( rbs = radiusPlus1 - i );
			g_sum += ( stack.g = ( pg = pixels[yi+1])) * rbs;
			b_sum += ( stack.b = ( pb = pixels[yi+2])) * rbs;
			a_sum += ( stack.a = ( pa = pixels[yi+3])) * rbs;
		   
			r_in_sum += pr;
			g_in_sum += pg;
			b_in_sum += pb;
			a_in_sum += pa;
			
			stack = stack.next;
		
			if( i < heightMinus1 )
			{
				yp += width;
			}
		}
		
		yi = x;
		stackIn = stackStart;
		stackOut = stackEnd;
		for ( y = 0; y < height; y++ )
		{
			p = yi << 2;
			pixels[p+3] = pa = (a_sum * mul_sum) >> shg_sum;
			if ( pa > 0 )
			{
				pa = 255 / pa;
				pixels[p]   = ((r_sum * mul_sum) >> shg_sum ) * pa;
				pixels[p+1] = ((g_sum * mul_sum) >> shg_sum ) * pa;
				pixels[p+2] = ((b_sum * mul_sum) >> shg_sum ) * pa;
			} else {
				pixels[p] = pixels[p+1] = pixels[p+2] = 0;
			}
			
			r_sum -= r_out_sum;
			g_sum -= g_out_sum;
			b_sum -= b_out_sum;
			a_sum -= a_out_sum;
		   
			r_out_sum -= stackIn.r;
			g_out_sum -= stackIn.g;
			b_out_sum -= stackIn.b;
			a_out_sum -= stackIn.a;
			
			p = ( x + (( ( p = y + radiusPlus1) < heightMinus1 ? p : heightMinus1 ) * width )) << 2;
			
			r_sum += ( r_in_sum += ( stackIn.r = pixels[p]));
			g_sum += ( g_in_sum += ( stackIn.g = pixels[p+1]));
			b_sum += ( b_in_sum += ( stackIn.b = pixels[p+2]));
			a_sum += ( a_in_sum += ( stackIn.a = pixels[p+3]));
		   
			stackIn = stackIn.next;
			
			r_out_sum += ( pr = stackOut.r );
			g_out_sum += ( pg = stackOut.g );
			b_out_sum += ( pb = stackOut.b );
			a_out_sum += ( pa = stackOut.a );
			
			r_in_sum -= pr;
			g_in_sum -= pg;
			b_in_sum -= pb;
			a_in_sum -= pa;
			
			stackOut = stackOut.next;
			
			yi += width;
		}
	}
	
	context.putImageData( imageData, top_x, top_y );
	
}

function BlurStack()
{
	this.r = 0;
	this.g = 0;
	this.b = 0;
	this.a = 0;
	this.next = null;
}


/*** END CANVAS ***/

function initFancyProfileBox() {

    var select = function(e, user) {
        $('#manager-id').val(user.guid);
    };
/*
    var filter = function(suggestions) {
        return $.grep(suggestions, function(suggestion) {
            return $.inArray(suggestion.guid, $colleagueSelected) === -1; // if suggestion.guid == $colleagueSelected
        });
    };
*/
    var manager = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: elgg.get_site_url() + "userfind?query=%QUERY",
            filter: function (response) {
                // Map the remote source JSON array to a JavaScript object array
                return $.map(response, function (user) {
                    return {
                        value: user.value,
                        guid: user.guid,
                        pic: user.pic,
                        avatar: user.avatar,
                    };
                });
            }
        }
    });

    // initialize bloodhound engine for colleague auto-suggest
    manager.initialize();

    $('#manager').typeahead(null, {
        name: 'manager',
        displayKey: function(user) {
            return user.value;
        },
        limit: Infinity,
        source: manager.ttAdapter(),
        /*
        source: function(query, cb) {
            manager.get(query, function(suggestions) {
                cb(filter(suggestions));
            });
        },
        */
        templates: {
            suggestion: function (user) {
                return '<div class="tt-suggest-avatar">' + user.pic + '</div><div class="tt-suggest-username">' + user.value + '</div><br>';
            }
        }
    }).bind('typeahead:selected', select);

    //$userSuggest.on('typeahead:selected', addColleague);
    //$userSuggest.on('typeahead:autocompleted', addColleague);

    //$userFind.push(userSearchField);
}

$(function() {
  
    var showmore = elgg.data.pessek_profile.showmore;
    var showless = elgg.data.pessek_profile.showless;
    
    $('.education_readmore').moreLines({
        linecount: 20,
        baseclass: 'b-description',
        basejsclass: 'js-description',
        classspecific: '_readmore',    
        buttontxtmore: showmore,               
        buttontxtless: showless,
        animationspeed: 200 
    });
    
    $('.skills_readmore').moreLines({
        linecount: 15,
        baseclass: 'b-description',
        basejsclass: 'js-description',
        classspecific: '_readmore',    
        buttontxtmore: showmore,               
        buttontxtless: showless,
        animationspeed: 200 
    });
    
    $('.mooc_readmore').moreLines({
        linecount: 14,
        baseclass: 'b-description',
        basejsclass: 'js-description',
        classspecific: '_readmore',    
        buttontxtmore: showmore,               
        buttontxtless: showless,
        animationspeed: 200 
    });
    
    $('.cerfifiction_readmore').moreLines({
        linecount: 14,
        baseclass: 'b-description',
        basejsclass: 'js-description',
        classspecific: '_readmore',    
        buttontxtmore: showmore,               
        buttontxtless: showless,
        animationspeed: 200 
    });
    
    $('button[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $('.experiences_readmore').moreLines({
            linecount: 20,
            baseclass: 'b-description',
            basejsclass: 'js-description',
            classspecific: '_readmore',    
            buttontxtmore: showmore,               
            buttontxtless: showless,
            animationspeed: 200 
        });
        
        $('.volunteers_readmore').moreLines({
            linecount: 20,
            baseclass: 'b-description',
            basejsclass: 'js-description',
            classspecific: '_readmore',    
            buttontxtmore: showmore,               
            buttontxtless: showless,
            animationspeed: 200 
        });
        
        $('.internships_readmore').moreLines({
            linecount: 20,
            baseclass: 'b-description',
            basejsclass: 'js-description',
            classspecific: '_readmore',    
            buttontxtmore: showmore,               
            buttontxtless: showless,
            animationspeed: 200 
        });
        
        $('.projects_readmore').moreLines({
            linecount: 30,
            baseclass: 'b-description',
            basejsclass: 'js-description',
            classspecific: '_readmore',    
            buttontxtmore: showmore,               
            buttontxtless: showless,
            animationspeed: 200 
        });
        
        $('.publications_readmore').moreLines({
            linecount: 20,
            baseclass: 'b-description',
            basejsclass: 'js-description',
            classspecific: '_readmore',    
            buttontxtmore: showmore,               
            buttontxtless: showless,
            animationspeed: 200 
        });
        
        $('.portfolio_readmore').moreLines({
            linecount: 30,
            baseclass: 'b-description',
            basejsclass: 'js-description',
            classspecific: '_readmore',    
            buttontxtmore: showmore,               
            buttontxtless: showless,
            animationspeed: 200 
        });
    
        $('.language_readmore').moreLines({
            linecount: 15,
            baseclass: 'b-description',
            basejsclass: 'js-description',
            classspecific: '_readmore',    
            buttontxtmore: showmore,               
            buttontxtless: showless,
            animationspeed: 200 
        });  
        
    });
    
});

$(document).ready(function() {
    
/*** CANVAS ***/

    /* Tab bootstrap */

    $(".btn-pref .btn").click(function () {
        $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
        $(this).removeClass("btn-default").addClass("btn-primary");   
    });

    /* End Tab bootstrap */

  var BLUR_RADIUS = 40;
  var sourceImages = [];

  $('.src-image').each(function(){
    sourceImages.push($(this).attr('src'));
  });

  $('.avatar img').each(function(index){
    $(this).attr('src', sourceImages[index] );
  });

  var drawBlur = function(canvas, image) {
    var w = canvas.width;
    var h = canvas.height;
    var canvasContext = canvas.getContext('2d');
    canvasContext.drawImage(image, 0, 0, w, h);
    stackBlurCanvasRGBA(canvas, 0, 0, w, h, BLUR_RADIUS);
  }; 
    
  
  $('.card canvas').each(function(index){
    var canvas = $(this)[0];
    
    var image = new Image();
    image.src = sourceImages[index];
    
    image.onload = function() {
      drawBlur(canvas, image);
    }
  });
  
/*** END CANVAS *****/

    // bootstrap tabs.js functionality..
    $('#myTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // show "edit profile picture" overlay on hover
    $('.avatar-profile-edit').hover(
        function() {
            $('.avatar-hover-edit').stop(true,true);
            $('.avatar-hover-edit').fadeIn('slow');
        },
        function() {
            $('.avatar-hover-edit').stop(true,true);
            $('.avatar-hover-edit').fadeOut('slow');
        }
    );


    // initialize edit/save/cancel buttons and hide some of the toggle elements
    $('.save-control').hide();
    $('.cancel-control').hide();

    $('.edit-control').hover(function() {
            $(this).addClass('edit-hover');
        },
        function(){
            $(this).removeClass('edit-hover');
        });

    $('.cancel-control').hover(function() {
            $(this).addClass('cancel-hover');
        },
        function(){
            $(this).removeClass('cancel-hover');
        });

    $('.save-control').hover(function() {
            $(this).addClass('save-hover');
        },
        function(){
            $(this).removeClass('save-hover');
        });

    //link the edit/save/cancel buttons with the appropriate functions on click..
    $('.edit-about-me').on("click", {section: "about-me"}, editProfile);
    $('.save-about-me').on("click", {section: "about-me"}, saveProfile);
    $('.cancel-about-me').on("click", {section: "about-me"}, cancelChanges);

    $('.edit-education').on("click", {section: "education"}, editProfile);
    $('.save-education').on("click", {section: "education"}, saveProfile);

    
/*
define(function(require) {
    
    var elgg = require("elgg");
    var $ = require("jquery"); //alert('hello');
   
   //$(document).on('click', '.save-education', function(e) {
      //alert('hello');
   //});
    $(document).on('click', '.save-education', {section: "education"}, saveProfile);
   
  // $('.save-education').on("click", {section: "education"}, saveProfile);

});*/
    $('.cancel-education').on("click", {section: "education"}, cancelChanges);
    
    $('.edit-certification').on("click", {section: "certification"}, editProfile);
    $('.save-certification').on("click", {section: "certification"}, saveProfile);
    $('.cancel-certification').on("click", {section: "certification"}, cancelChanges);

    $('.edit-work-experience').on("click", {section: "work-experience"}, editProfile);
    $('.save-work-experience').on("click", {section: "work-experience"}, saveProfile);
    $('.cancel-work-experience').on("click", {section: "work-experience"}, cancelChanges);

    $('.edit-skills').on("click", {section: "skills"}, editProfile);
    $('.save-skills').on("click", {section: "skills"}, saveProfile);
    $('.cancel-skills').on("click", {section: "skills"}, cancelChanges);

    $('.edit-languages').on("click", {section: "languages"}, editProfile);
    $('.save-languages').on("click", {section: "languages"}, saveProfile);
    $('.cancel-languages').on("click", {section: "languages"}, cancelChanges);

    $('.edit-portfolio').on("click", {section: "portfolio"}, editProfile);
    $('.save-portfolio').on("click", {section: "portfolio"}, saveProfile);
    $('.cancel-portfolio').on("click", {section: "portfolio"}, cancelChanges);

    $('.gcconnex-education-add-another').on("click", {section: "education"}, addMore);
    
    $('.gcconnex-certification-add-another').on("click", {section: "certification"}, addMore);
    
    $(document).click(function(event) {
        if(!$(event.target).closest('.gcconnex-endorsements-input-wrapper').length) {
            /*if($('.gcconnex-endorsements-input-skill').is(":visible")) {
                $('.gcconnex-endorsements-input-skill').hide();
                $('.gcconnex-endorsements-add-skill').fadeIn('slowly');
            }*/
        }
        
    });
    
    $(document).on('mouseover', '.gcconnex-avatar-in-list', function() {
        $(this).find('.remove-colleague-from-list').toggle();
    });
    $(document).on('mouseout', '.gcconnex-avatar-in-list', function() {
        $(this).find('.remove-colleague-from-list').toggle();
    });
    
    $(document).on('mouseover', '.gcconnex-avatar-in-list', function() {
        $(this).find('.remove-coauthor-from-list').toggle();
    });
    $(document).on('mouseout', '.gcconnex-avatar-in-list', function() {
        $(this).find('.remove-coauthor-from-list').toggle();
    });

    var newSkill = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        //prefetch: '../data/films/post_1960.json',
        //remote: '../data/films/queries/%QUERY.json'
        remote: {
            url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/autoskill.php?query=%QUERY',
        }
    });

    newSkill.initialize();

    $('.gcconnex-certification-authority1').typeahead(null, {
        name: 'newSkill',
        displayKey: 'value',
        limit: 10,
        source: newSkill.ttAdapter()
    });

});

function LoadCountryOfOrigin(){
    
    $('.basic-form').removeAttr("onclick");
    
    var language_code = elgg.get_language(); //language_code = 'fr';
    
    if(language_code=="fr"){
    
            var newSkill = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.common_name_fr);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/countries.json',
                    filter: function (response) {
                        //console.log("data", data.response.songs)
                        return $.map(response, function (data) {
                            return {
                                common_name: data.name.common,
                                official_name: data.name.official,
                                common_name_fr: data.translations.fra.common,
                                official_name_fr: data.translations.fra.official,
                                flag_name: data.cca2
                            // artistName: song.artist_name
                            };
                        });
                    }
                    
                }
            });
            
            newSkill.clearPrefetchCache();
            newSkill.initialize();

            $('.gcconnex-country-origin').typeahead(null, {
                name: 'newSkill',
                //displayKey: 'value',
                displayKey: 'common_name_fr', //str.toLowerCase(); 
                limit: Infinity,
                source: newSkill.ttAdapter(),
                templates: {
                    
                        suggestion: function (data) {
                            
                            var contry_flag_name = data.flag_name;
                            contry_flag_name = contry_flag_name.toLowerCase(); 
                            var countryimg = elgg.get_site_url() + "mod/pessek_profile/img/flags/" + contry_flag_name + ".png";
                                
                            return '<div><img src="'+ countryimg +'" width="30px" height="20px" alt=""> ' + data.common_name_fr + ' (' + data.official_name_fr + ')</div>';
                            
                        }
                    }
            });
            
            $('.gcconnex-country-residence').typeahead(null, {
                name: 'newSkill',
                //displayKey: 'value',
                displayKey: 'common_name_fr', //str.toLowerCase(); 
                limit: Infinity,
                source: newSkill.ttAdapter(),
                templates: {
                    
                        suggestion: function (data) {
                            
                            var contry_flag_name = data.flag_name;
                            contry_flag_name = contry_flag_name.toLowerCase(); 
                            var countryimg = elgg.get_site_url() + "mod/pessek_profile/img/flags/" + contry_flag_name + ".png";
                                
                            return '<div><img src="'+ countryimg +'" width="30px" height="20px" alt=""> ' + data.common_name_fr + ' (' + data.official_name_fr + ')</div>';
                            
                        }
                    }
            });
    
    } else if(language_code=="en"){
    //////////////////////////////////////////////////
       
            var newSkill = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.common_name);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/countries.json',
                    filter: function (response) {
                        //console.log("data", data.response.songs)
                        return $.map(response, function (data) {
                            return {
                                common_name: data.name.common,
                                official_name: data.name.official,
                                common_name_fr: data.translations.fra.common,
                                official_name_fr: data.translations.fra.official,
                                flag_name: data.cca2
                            // artistName: song.artist_name
                            };
                        });
                    }
                    
                }
            });
            
            newSkill.clearPrefetchCache();
            newSkill.initialize();

            $('.gcconnex-country-origin').typeahead(null, {
                name: 'newSkill',
                //displayKey: 'value',
                displayKey: 'common_name', //str.toLowerCase(); 
                limit: Infinity,
                source: newSkill.ttAdapter(),
                templates: {
                    
                        suggestion: function (data) {
                            
                            var contry_flag_name = data.flag_name;
                            contry_flag_name = contry_flag_name.toLowerCase(); 
                            var countryimg = elgg.get_site_url() + "mod/pessek_profile/img/flags/" + contry_flag_name + ".png";
                                
                            return '<div><img src="'+ countryimg +'" width="30px" height="20px" alt=""> ' + data.common_name + ' (' + data.official_name + ')</div>';
                            
                        }
                    }
            });
            
            $('.gcconnex-country-residence').typeahead(null, {
                name: 'newSkill',
                //displayKey: 'value',
                displayKey: 'common_name', //str.toLowerCase(); 
                limit: Infinity,
                source: newSkill.ttAdapter(),
                templates: {
                    
                        suggestion: function (data) {
                            
                            var contry_flag_name = data.flag_name;
                            contry_flag_name = contry_flag_name.toLowerCase(); 
                            var countryimg = elgg.get_site_url() + "mod/pessek_profile/img/flags/" + contry_flag_name + ".png";
                                
                            return '<div><img src="'+ countryimg +'" width="30px" height="20px" alt=""> ' + data.common_name + ' (' + data.official_name + ')</div>';
                            
                        }
                    }
            });
    
    }
    
    $('.gcconnex-country-origin').on('typeahead:selected', CountryOriginSubmit);
    $('.gcconnex-country-origin').on('typeahead:autocompleted', CountryOriginSubmit);
    
    $('.gcconnex-country-residence').on('typeahead:selected', CountryResidenceSubmit);
    $('.gcconnex-country-residence').on('typeahead:autocompleted', CountryResidenceSubmit);
    
    
}

function LoadCountryLocation(){
    
    $('.experience-form').removeAttr("onclick");
    
    var language_code = elgg.get_language(); //language_code = 'fr';
    
    if(language_code=="fr"){
    
            var newSkill = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.common_name_fr);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/countries.json',
                    filter: function (response) {
                        //console.log("data", data.response.songs)
                        return $.map(response, function (data) {
                            return {
                                common_name: data.name.common,
                                official_name: data.name.official,
                                common_name_fr: data.translations.fra.common,
                                official_name_fr: data.translations.fra.official,
                                flag_name: data.cca2
                            // artistName: song.artist_name
                            };
                        });
                    }
                    
                }
            });
            
            newSkill.clearPrefetchCache();
            newSkill.initialize();

            $('.gcconnex-experience-country').typeahead(null, {
                name: 'newSkill',
                //displayKey: 'value',
                displayKey: 'common_name_fr', //str.toLowerCase(); 
                limit: Infinity,
                source: newSkill.ttAdapter(),
                templates: {
                    
                        suggestion: function (data) {
                            
                            var contry_flag_name = data.flag_name;
                            contry_flag_name = contry_flag_name.toLowerCase(); 
                            var countryimg = elgg.get_site_url() + "mod/pessek_profile/img/flags/" + contry_flag_name + ".png";
                                
                            return '<div><img src="'+ countryimg +'" width="30px" height="20px" alt=""> ' + data.common_name_fr + ' (' + data.official_name_fr + ')</div>';
                            
                        }
                    }
            });
    
    } else if(language_code=="en"){
    //////////////////////////////////////////////////
       
            var newSkill = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.common_name);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/countries.json',
                    filter: function (response) {
                        //console.log("data", data.response.songs)
                        return $.map(response, function (data) {
                            return {
                                common_name: data.name.common,
                                official_name: data.name.official,
                                common_name_fr: data.translations.fra.common,
                                official_name_fr: data.translations.fra.official,
                                flag_name: data.cca2
                            // artistName: song.artist_name
                            };
                        });
                    }
                    
                }
            });
            
            newSkill.clearPrefetchCache();
            newSkill.initialize();

            $('.gcconnex-experience-country').typeahead(null, {
                name: 'newSkill',
                //displayKey: 'value',
                displayKey: 'common_name', //str.toLowerCase(); 
                limit: Infinity,
                source: newSkill.ttAdapter(),
                templates: {
                    
                        suggestion: function (data) {
                            
                            var contry_flag_name = data.flag_name;
                            contry_flag_name = contry_flag_name.toLowerCase(); 
                            var countryimg = elgg.get_site_url() + "mod/pessek_profile/img/flags/" + contry_flag_name + ".png";
                                
                            return '<div><img src="'+ countryimg +'" width="30px" height="20px" alt=""> ' + data.common_name + ' (' + data.official_name + ')</div>';
                            
                        }
                    }
            });
    
    }
    
    $('.gcconnex-experience-country').on('typeahead:selected', CountrySubmit);
    $('.gcconnex-experience-country').on('typeahead:autocompleted', CountrySubmit);
    
    
}

function LoadLanguages(){
    
    $('.languages-form').removeAttr("onclick");
    
    var language_code = elgg.get_language(); //language_code = 'fr';
    
    if(language_code=="fr"){
        
            var Languages = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.language_fr);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/language-codes-full.json',
                    filter: function (response) {
                        //console.log("data", data.response.songs)
                        return $.map(response, function (data) {
                            return {
                                language_en: data.English,
                                language_fr: data.French
                            };
                        });
                    }
                    
                }
            });
            
            Languages.clearPrefetchCache();
            Languages.initialize();
        
            $('.gcconnex-languages-langs').typeahead(null, {
            name: 'Languages',
            //displayKey: 'value',
            displayKey: 'language_fr', //str.toLowerCase(); 
            limit: Infinity,
            source: Languages.ttAdapter(),
            templates: {
                
                    suggestion: function (data) {
                        
                    return '<div>' + data.language_fr + '</div>';
                        
                    }
                }
            });
        
    } else if(language_code=="en"){
        
            var Languages = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.language_en);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/language-codes-full.json',
                    filter: function (response) {
                        //console.log("data", "data.response.songs");
                        return $.map(response, function (data) {
                            return {
                                language_en: data.English,
                                language_fr: data.French
                            };
                        });
                    }
                    
                }
            });
            
            Languages.clearPrefetchCache();
            Languages.initialize();
                        
            $('.gcconnex-languages-langs').typeahead(null, {
            name: 'Languages',
            //displayKey: 'value',
            displayKey: 'language_en', //str.toLowerCase(); 
            limit: Infinity,
            source: Languages.ttAdapter(),
            templates: {
                
                    suggestion: function (data) {
                        
                    return '<div>' + data.language_en + '</div>';
                        
                    }
                }
            });
                        
    }
    
    $('.gcconnex-languages-langs').focus();
    
}


function LoadSkills(){
    
    $('.skills-form').removeAttr("onclick");
    
    var language_code = elgg.get_language(); //language_code = 'fr';
    
    if(language_code=="fr"){
        
            var skills = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.skills_fr);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/skills.json',
                    filter: function (response) {
                        //console.log("data", data.response.songs)
                        return $.map(response, function (data) {
                            return {
                                skills_en: data.name.en,
                                skills_fr: data.name.fr,
                                skills_id: data.name.id
                            };
                        });
                    }
                    
                }
            });
            
            skills.clearPrefetchCache();
            skills.initialize();
                        
            $('.gcconnex-skills-skill').typeahead(null, {
            name: 'skills',
            displayKey: 'skills_fr', 
            limit: Infinity,
            source: skills.ttAdapter(),
            templates: {
                
                    suggestion: function (data) {
                        
                    return '<div>' + data.skills_fr + '</div>';
                        
                    }
                }
            });
            
    } else if(language_code=="en"){
      
            var skills = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.skills_en);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/skills.json',
                    filter: function (response) {
                        //console.log("data", data.response.songs)
                        return $.map(response, function (data) {
                            return {
                                skills_en: data.name.en,
                                skills_fr: data.name.fr,
                                skills_id: data.name.id
                            };
                        });
                    }
                    
                }
            });
            
            skills.clearPrefetchCache();
            skills.initialize();
                        
            $('.gcconnex-skills-skill').typeahead(null, {
            name: 'skills',
            displayKey: 'skills_en', 
            limit: Infinity,
            source: skills.ttAdapter(),
            templates: {
                
                    suggestion: function (data) {
                        
                    return '<div>' + data.skills_en + '</div>';
                        
                    }
                }
            });
    
    }
            
    $('.gcconnex-skills-skill').on('typeahead:selected', SkillsSubmit);
    $('.gcconnex-skills-skill').on('typeahead:autocompleted', SkillsSubmit);
    
    $('.gcconnex-skills-skill').focus();
    
}


function UserContributors(section){
            var manager = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: elgg.get_site_url() + "userfind?query=%QUERY",
                filter: function (response) { 
                    // Map the remote source JSON array to a JavaScript object array
                    return $.map(response, function (user) {
                        return {
                                value: user.value,
                                guid: user.guid,
                                pic: user.pic,
                                avatar: user.avatar,
                            };
                        });
                    }
                }
            });
            
            $('.gcconnex-publication-input-wrapper').hide();
            $('.contibutor_lib').hide();
            
            var co_author_placeholder = elgg.echo("gcconnex_profile:projects:co:contributors:placeholder", null, null);
            var co_author_placeholder_help = elgg.echo("gcconnex_profile:projects:co:contributors:placeholder:help", null, null);
            var contributor_lib = elgg.echo("gcconnex_profile:projects:contributor", null, null);
            
            $('.colleagues-list').append('<span class="contibutor_lib" ><strong>'+ contributor_lib + '</strong></span>');
            
            $('.pessek-publication-co-author').append('<div class="gcconnex-publication-input-wrapper">' +
            '<input type="text" placeholder="' + co_author_placeholder + '" class="form-control gcconnex-endorsements-input-coauthor coauthorfind"/>' +
            '<p><h6><span class="text-danger">' + co_author_placeholder_help +'</span></h6></p>' +
            '</div>');
	   /*
	   $('.pessek-publication-co-author').append('<div class="gcconnex-publication-input-wrapper">' +
            '<input type="text" placeholder="' + co_author_placeholder + '" class="form-control gcconnex-endorsements-input-coauthor coauthorfind" onkeyup="checkForEnter(event)"/>' +
            '<p><h6><span class="text-danger">' + co_author_placeholder_help +'</span></h6></p>' +
            '</div>');*/
            
	    manager.initialize();
            
            $('.gcconnex-endorsements-input-coauthor').typeahead(null, {
            name: 'manager',
            displayKey: function(user) {
                return user.value; 
            },
            limit: Infinity,
            source: manager.ttAdapter(),
            
            templates: {
                suggestion: function (user) {
                    return '<div class="tt-suggest-avatar">' + user.pic + '</div><div class="tt-suggest-username">' + user.value + '</div><br>';
                }
            }
        });
        
        $('.gcconnex-endorsements-input-coauthor').on('typeahead:selected', CoauthorSubmit);
        $('.gcconnex-endorsements-input-coauthor').on('typeahead:autocompleted', CoauthorSubmit);
        
        $('.gcconnex-endorsements-input-coauthor').focus();
}


function UserFriend(section){
            
            var manager = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: elgg.get_site_url() + "userfind?query=%QUERY",
                filter: function (response) {
                    // Map the remote source JSON array to a JavaScript object array
                    return $.map(response, function (user) {
                        return {
                                value: user.value,
                                guid: user.guid,
                                pic: user.pic,
                                avatar: user.avatar,
                            };
                        });
                    }
                }
            });
            
            $('.gcconnex-publication-input-wrapper').hide();
            $('.coauthor_lib').hide();
            
            var christineFix = elgg.echo("gcconnex_profile:gc_skill:add", null, null);
            var co_author_placeholder = elgg.echo("gcconnex_profile:publications:co:author:placeholder", null, null);
            var co_author_placeholder_help = elgg.echo("gcconnex_profile:publications:co:author:placeholder:help", null, null);
            
            var coauthor_lib = elgg.echo("gcconnex_profile:publications:co:author", null, null);
            
            $('.colleagues-list').append('<span class="coauthor_lib" ><strong>'+ coauthor_lib + '</strong></span>');
            
            $('.pessek-publication-co-author').append('<div class="gcconnex-publication-input-wrapper">' +
            '<input type="text" placeholder="' + co_author_placeholder + '" class="form-control gcconnex-endorsements-input-coauthor coauthorfind" onkeyup="checkForEnter(event)"/>' +
            '<p><h6><span class="text-danger">' + co_author_placeholder_help +'</span></h6></p>' +
            '</div>')
            
            manager.initialize();
            
            $('.gcconnex-endorsements-input-coauthor').typeahead(null, {
            name: 'manager',
            displayKey: function(user) {
                return user.value;
            },
            limit: Infinity,
            source: manager.ttAdapter(),
            
            templates: {
                suggestion: function (user) {
                    return '<div class="tt-suggest-avatar">' + user.pic + '</div><div class="tt-suggest-username">' + user.value + '</div><br>';
                }
            }
        });
        
        $('.gcconnex-endorsements-input-coauthor').on('typeahead:selected', CoauthorSubmit);
        $('.gcconnex-endorsements-input-coauthor').on('typeahead:autocompleted', CoauthorSubmit);
        
        $('.gcconnex-endorsements-input-coauthor').focus();
        
}

function entryDeletion(guidp, guide, deletionmessage, section, userName, yes, cancel){
    
            $.confirm({
                icon: 'fa fa-question',
                theme: 'modern',
                closeIcon: true,
                animation: 'rotateXR',
                type: 'red',
                content: deletionmessage,
                title: userName,
                buttons: {
                        okay: {
                        text: yes,
                        //btnClass: 'btn-blue',
                        action: function () {
                                deleteProfileEntry(guidp, guide, section);
                            }
                        },
                        cancel: {
                        text: cancel,
                        //btnClass: 'btn-blue',
                        action: function () {
                        // do nothing
                            }
                        }
                }
            });
            
}
function confirmLinkedin(guidp, guide, deletionmessage, section, userName, yes, cancel, confirm_message){
    
            $.confirm({
                icon: 'fa fa-question',
                theme: 'modern',
                closeIcon: true,
                animation: 'rotateXR',
                type: 'blue',
                content: deletionmessage,
                title: userName,
                buttons: {
                        okay: {
                        text: yes,
                        //btnClass: 'btn-blue',
                        action: function () {
                                deleteProfileEntry(guidp, guide, section, confirm_message);
                            }
                        },
                        cancel: {
                        text: cancel,
                        //btnClass: 'btn-blue',
                        action: function () {
                        // do nothing
                            }
                        }
                }
            });
            
}
function deleteProfileEntry(guidp, guide, section, confirm_message ='NULL'){
    
    switch (section) {
            case 'portfolio':
                    elgg.action('pessek_profile/portfolio/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/portfolio'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-portfolio-display').remove();
                                $('.gcconnex-portfolio').append('<div class="gcconnex-profile-portfolio-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'education':
                    elgg.action('action/pessek_profile/education/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/education'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-education-display').remove();
                                $('.gcconnex-education').append('<div class="gcconnex-profile-education-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'certification':
                    elgg.action('action/pessek_profile/certification/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/certification'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-certification-display').remove();
                                $('.gcconnex-certification').append('<div class="gcconnex-profile-certification-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'mooc':
                    elgg.action('action/pessek_profile/mooc/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/mooc'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-mooc-display').remove();
                                $('.gcconnex-mooc').append('<div class="gcconnex-profile-mooc-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'publications':
                    elgg.action('action/pessek_profile/publications/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/publications'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-publications-display').remove();
                                $('.gcconnex-publications').append('<div class="gcconnex-profile-publications-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'projects':
                    elgg.action('action/pessek_profile/projects/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/projects'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-projects-display').remove();
                                $('.gcconnex-projects').append('<div class="gcconnex-profile-projects-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'internships':
                    elgg.action('action/pessek_profile/internships/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/internships'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-internships-display').remove();
                                $('.gcconnex-internships').append('<div class="gcconnex-profile-internships-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'volunteers':
                    elgg.action('action/pessek_profile/volunteers/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/volunteers'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-volunteers-display').remove();
                                $('.gcconnex-volunteers').append('<div class="gcconnex-profile-volunteers-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'experiences':
                    elgg.action('action/pessek_profile/experiences/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/experiences'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-experiences-display').remove();
                                $('.gcconnex-experiences').append('<div class="gcconnex-profile-experiences-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'languages':
                    elgg.action('action/pessek_profile/languages/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/languages'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-languagess-display').remove();
                                $('.gcconnex-languagess').append('<div class="gcconnex-profile-languagess-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'skillssentry':
                    elgg.action('action/pessek_profile/skillssentry/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/skills'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-skillss-display').remove();
                                $('.gcconnex-skillss').append('<div class="gcconnex-profile-skillss-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'description':
                    elgg.action('action/pessek_profile/description/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/about-me'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                },
                                function (data) {
                                            // Output portfolio here
                                $('.gcconnex-profile-description-display').remove();
                                $('.gcconnex-description').append('<div class="gcconnex-profile-description-display">' + data + '</div>');
                                });
                        },
                    });
            break;
            case 'linkedin': //guidp, guide
               //$(location).attr('href',elgg.get_site_url() + 'linkedin_by_pessek?guid=' + guidp +'&confirm_message='+confirm_message); 
               $(location).attr('href',elgg.get_site_url() + guidp); 
                //alert(guidp);alert(guide);
            break;
            case 'experiences0':

                
    }
}

function Delete_Skills_Entry(guidp, guide, section, identifier){
    
    switch (section) {
            case 'skillssentry':
                    
                    deleteEntry(identifier);
                    
                    elgg.action('action/pessek_profile/skillssentry/delete', {
                        data : {
                            guidp: guidp,
                            guide: guide,
                        },
                        success: function(json) {
                                $.get(elgg.normalize_url('ajax/view/pessek_profile/skills'),{
                                    //guid: elgg.get_logged_in_user_guid() 
                                    guid: elgg.get_page_owner_guid()
                                });
                        },
                    });
                    
            break;
            case 'experiences0':

                
    }
}


function testtest(event) {//alert('12345');
    
    $('.gcconnex-certification-authority1').each(function() {
                        pessekhermand(this);
                    });
}

/*
 * Purpose: To handle all click events on "edit" controls for the gcconnex profile.
 *
 * Porpoise: Porpoises are small cetaceans of the family Phocoenidae; they are related to whales and dolphins.
 *   They are distinct from dolphins, although the word "porpoise" has been used to refer to any small dolphin,
 *   especially by sailors and fishermen. This paragraph has nothing to do with this function.
 */
function pessekhermand(target){
    
    var newSkill = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        //prefetch: '../data/films/post_1960.json',
        //remote: '../data/films/queries/%QUERY.json'
        remote: {
            url: elgg.get_site_url() + 'mod/b_extended_profile/actions/b_extended_profile/autoskill.php?query=%QUERY',
        }
    });

    newSkill.initialize();

    $('.gcconnex-certification-authority1').typeahead(null, {
        name: 'newSkill',
        displayKey: 'value',
        limit: 10,
        source: newSkill.ttAdapter()
    });
    
}
function editProfile(event) {

    var $section = event.data.section; // which edit button is the user clicking on?

    // toggle the edit, save, cancel buttons
    
    $('.edit-' + $section).hide();
    $('.cancel-' + $section).show();

    switch ($section) {
        case 'about-me':
            // Edit the About Me blurb
            $.get(elgg.normalize_url('ajax/view/pessek_profile/edit_about-me'),
                {
                    guid: elgg.get_logged_in_user_guid()
                },
                function(data) {
                    $('.gcconnex-about-me').append('<div class="gcconnex-about-me-edit-wrapper">' + data + '</div>');
                    $('.save-' + $section).show();//$('.save-' + $section).show();
                });
            $('.gcconnex-profile-about-me-display').hide();
            break;
        case 'education':
            // Edit the edumacation
            $.get(elgg.normalize_url('ajax/view/pessek_profile/edit_education'),
                {
                    guid: elgg.get_logged_in_user_guid()
                },
                function(data) {
                    // Output in a DIV with id=somewhere
                    $('.gcconnex-education').append('<div class="gcconnex-education-edit-wrapper">' + data + '</div>');
                    $('.save-' + $section).show();
                    $('.userfindPessek').each(function() {
                        pessekhermand(this);
                    });
                });
            $('.gcconnex-profile-education-display').hide();
            break;
        case 'certification':
            // Edit certification
            //alert("salo");
            
            $.get(elgg.normalize_url('ajax/view/pessek_profile/edit_certification'),
                {
                    guid: elgg.get_logged_in_user_guid()
                },
                function(data) {
                    // Output in a DIV with id=somewhere
                    $('.gcconnex-certification').append('<div class="gcconnex-certification-edit-wrapper">' + data + '</div>');
                    $('.save-' + $section).show();
                });
            $('.gcconnex-profile-certification-display').hide();
            break;
        case 'work-experience':
            // Edit the experience for this user
            $.get(elgg.normalize_url('ajax/view/b_extended_profile/edit_work-experience'),
                {
                    guid: elgg.get_logged_in_user_guid()
                },
                function(data) {
                    // Output in a DIV with id=somewhere
                    $('.gcconnex-work-experience').append('<div class="gcconnex-work-experience-edit-wrapper">' + data + '</div>');
                    //elgg.security.refreshToken();

                    $userFind = [];
                    $colleagueSelected = [];

                    $('.userfind').each(function() {
                        user_search_init(this);
                    });
                    $('.gcconnex-profile-work-experience-display').hide();
                    $('.save-' + $section).show();
                });
            break;

        case 'skills':
            // inject the html to add ability to add skills
            var newSkill = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                //prefetch: '../data/films/post_1960.json',
                //remote: '../data/films/queries/%QUERY.json'
                remote: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/autoskill.php?query=%QUERY',
                }
            });

            var christineFix = elgg.echo("gcconnex_profile:gc_skill:add", null, null);
            $('.gcconnex-skills').append('<div class="gcconnex-endorsements-input-wrapper">' +
            '<input type="text" class="gcconnex-endorsements-input-skill" onkeyup="checkForEnter(event)"/>' +
            '<span class="gcconnex-endorsements-add-skill">' + christineFix + '</span>' +
            '</div>');
            
            newSkill.initialize();

            $('.gcconnex-endorsements-input-skill').typeahead(null, {
                name: 'newSkill',
                displayKey: 'value',
                limit: 10,
                source: newSkill.ttAdapter()
            });
/*
            var newSkill = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                //prefetch: '../data/films/post_1960.json',
                //remote: '../data/films/queries/%QUERY.json'
                remote: {
                    url: elgg.get_site_url() + 'mod/pessek_profile/actions/pessek_profile/autoskill.php?query=%QUERY',
                }
            });

            newSkill.initialize();

            $('.gcconnex-endorsements-input-skill').typeahead(null, {
                name: 'newSkill',
                displayKey: 'value',
                limit: 10,
                source: newSkill.ttAdapter()
            });
*/

            $('.gcconnex-endorsements-input-skill').on('typeahead:selected', skillSubmit);
            $('.gcconnex-endorsements-input-skill').on('typeahead:autocompleted', skillSubmit);

            // hide the skill entry text box which is only to be shown when toggled by the link
            //$('.gcconnex-endorsements-input-skill').hide();

            // the profile owner would like to type in a new skill
            $('.gcconnex-endorsements-add-skill').on("click", function () {
                $('.gcconnex-endorsements-input-skill').fadeIn('slowly').focus() ;
                $('.gcconnex-endorsements-add-skill').hide();
            });

            // create a "delete this skill" link for each skill
            $('.gcconnex-endorsements-skill').each(function(){
                $(this).after('<img class="delete-skill-img" src="' + elgg.get_site_url() + 'mod/b_extended_profile/img/delete.png"><span class="delete-skill" onclick="deleteEntry(this)" data-type="skill">' + elgg.echo("gcconnex_profile:gc_skill:delete", null, null) + '</span>'); //goes in here i think..
            });
            $('.save-' + $section).show();

            //$('.delete-skill').show();

            break;
        case 'languages':
            // Edit the languages for this user

            $.get(elgg.normalize_url('ajax/view/b_extended_profile/edit_languages'),
                {
                    guid: elgg.get_logged_in_user_guid()
                },
                function(data) {
                    // Output in a DIV with id=somewhere
                    $('.gcconnex-languages').append('<div class="gcconnex-languages-edit-wrapper">' + data + '</div>');
                    $('.gcconnex-profile-languages-display').hide();
                    $('.save-' + $section).show();
                });
            break;
        case 'portfolio':
            $.get(elgg.normalize_url('ajax/view/b_extended_profile/edit_portfolio'),
                {
                    guid: elgg.get_logged_in_user_guid()
                },
            function(data) {
                // Output the 'edit portfolio' page somewhere
                $('.gcconnex-portfolio').append('<div class="gcconnex-portfolio-edit-wrapper">' + data + '</div>');
                $('.gcconnex-profile-portfolio-display').hide();
                $('.save-' + $section).show();
            });
        default:
            break;
    }
}

function user_search_init(target) {

    var tid = $(target).data("tid");
    tidName = tid;
    $userSuggest = $('.' + tid);
    $colleagueSelected[tid] = [];

    $(target).closest('.gcconnex-work-experience-entry').find('.gcconnex-avatar-in-list').each(function() {
        $colleagueSelected[tid].push($(this).data('guid'));
    });

    var select = function(e, user, dataset) {
        $colleagueSelected[dataset].push(user.guid);
        $("#selected").text(JSON.stringify($colleagueSelected[dataset]));
        $("input.typeahead").typeahead("val", "");
    };
    //$colleagueSelected[tid] = [];
    //$colleagueSelected[tid].push(selected);

    var filter = function(suggestions, tidName) {
        return $.grep(suggestions, function(suggestion, tid) {
            return $.inArray(suggestion.guid, $colleagueSelected[suggestion.tid]) === -1;
        });
    };

    var userName = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: elgg.get_site_url() + "userfind?query=%QUERY",
            filter: function (response) {
                // Map the remote source JSON array to a JavaScript object array
                return $.map(response, function (user) {
                    return {
                        value: user.value,
                        guid: user.guid,
                        pic: user.pic,
                        avatar: user.avatar,
                        tid: tid
                    };
                });
            }
        }
    });

    // initialize bloodhound engine for colleague auto-suggest
    userName.initialize();

    var userSearchField = $userSuggest.typeahead(null, {
        name: tid,
        displayKey: function(user) {
            return user.value;
        },
        limit: 0,
        //source: userName.ttAdapter(),
        source: function(query, cb) {
            userName.get(query, function(suggestions) {
                cb(filter(suggestions, tidName));
            });
        },
        templates: {
            suggestion: function (user) {
                return '<div class="tt-suggest-avatar">' + user.pic + '</div><div class="tt-suggest-username">' + user.value + '</div><br>';
            }
        }
    }).bind('typeahead:selected', select);

    $userSuggest.on('typeahead:selected', addColleague);
    $userSuggest.on('typeahead:autocompleted', addColleague);

    $userFind.push(userSearchField);
}

/*
 * Purpose: Save any changes made to the profile
 */
function saveProfile(event) {

    var $section = event.data.section;

    // toggle the edit, save, cancel buttons
    $('.edit-' + $section).show();
    $('.save-' + $section).hide();
    $('.cancel-' + $section).hide();

    switch ($section) {
        case "about-me":
            //var $about_me = tinyMCE.activeEditor.getContent();
            var $about_me = document.getElementById("aboutme").value; //alert($about_me);
            //var $about_me = CKEDITOR.instances.editor1.getData(); 
            // save the information the user just edited
            elgg.action('pessek_profile/edit_profile', {
                data: {
                    guid: elgg.get_logged_in_user_guid(),
                    section: 'about-me',
                    description: $about_me
                },
                success: function() {            // fetch and display the information we just saved
                    $.get(elgg.normalize_url('ajax/view/pessek_profile/about-me'),
                        {
                            guid: elgg.get_logged_in_user_guid()
                        },
                        function(data) {
                            // Output in a DIV with id=somewhere
                            $('.gcconnex-profile-about-me-display').remove();
                            $('.gcconnex-about-me').append('<div class="gcconnex-profile-about-me-display">' + data + '</div>');
                        });
                }
            });

            $('.gcconnex-about-me-edit-wrapper').remove();

            break;

        case "education":

            //var $school = $('.gcconnex-education-school').val();
            
            var $education_guid = [];
            var $delete_guid = [];

            $('.gcconnex-education-entry').each(function() {
                if ( $(this).is(":hidden") ) {
                    if ($(this).data('guid') != "new") {
                        $delete_guid.push($(this).data('guid'));
                    }
                }
                else {
                    $education_guid.push($(this).data('guid'));
                }
            });

            var $school = [];
            $('.gcconnex-education-school').not(":hidden").each(function() {
                $school.push($(this).val());
            });
            
            var $diploma = [];
            $('.gcconnex-education-diploma').not(":hidden").each(function() {
                $diploma.push($(this).val());
            });
            
            var $resultobtain = [];
            $('.gcconnex-education-resultobtain').not(":hidden").each(function() {
                $resultobtain.push($(this).val());
            });
            
            var $educationurl = [];
            $('.gcconnex-education-educationurl').not(":hidden").each(function() {
                $educationurl.push($(this).val());
            });
            
            var $activity = [];
            $('.gcconnex-education-activity').not(":hidden").each(function() {
                $activity.push($(this).val());
            });
            
            var $trainingd = [];
            $('.gcconnex-education-trainingd').not(":hidden").each(function() {
                $trainingd.push($(this).val());
            });

            var $startdate = [];
            $('.gcconnex-education-startdate').not(":hidden").each(function() {
                $startdate.push($(this).val());
            });

            var $startyear = [];
            $('.gcconnex-education-start-year').not(":hidden").each(function() {
                $startyear.push($(this).val());
            });

            var $enddate = [];
            $('.gcconnex-education-enddate').not(":hidden").each(function() {
                $enddate.push($(this).val());
            });

            var $endyear = [];
            $('.gcconnex-education-end-year').not(":hidden").each(function() {
                $endyear.push($(this).val());
            });

            var $ongoing = [];
            $('.gcconnex-education-ongoing').not(":hidden").each(function() {
                $ongoing.push($(this).prop('checked'));
            });

            /*
            var $program = [];
            $('.gcconnex-education-program').not(":hidden").each(function() {
                $program.push($(this).val());
            });
            */

            var $degree = [];
            $('.gcconnex-education-degree').not(":hidden").each(function() {
                $degree.push($(this).val());
            });

            var $field = [];
            $('.gcconnex-education-field').not(":hidden").each(function() {
                $field.push($(this).val());
            });
            var $access = $('.gcconnex-education-access').val();

            // save the information the user just edited
            elgg.action('pessek_profile/edit_profile', {
                data: {
                    guid: elgg.get_logged_in_user_guid(),
                    delete: $delete_guid,
                    eguid: $education_guid,
                    section: 'education',
                    school: $school,
                    diploma : $diploma,
                    resultobtain : $resultobtain,
                    educationurl : $educationurl,
                    activity : $activity,
                    trainingd : $trainingd,
                    startdate: $startdate,
                    startyear: $startyear,
                    enddate: $enddate,
                    endyear: $endyear,
                    ongoing: $ongoing,
                    //program: $program,
                    degree: $degree,
                    field: $field,
                    access: $access
                },
                success: function() {            // fetch and display the information we just saved
                    $.get(elgg.normalize_url('ajax/view/pessek_profile/education'),
                        {
                            guid: elgg.get_logged_in_user_guid()
                        },
                        function(data) {
                            // Output in a DIV with id=somewhere
                            $('.gcconnex-education-display').remove();
                            $('.gcconnex-education').append('<div class="gcconnex-education-display">' + data + '</div>');
                        });
                }
                });
            $('.gcconnex-education-edit-wrapper').remove();

            break;
        
        case "certification":

            //var $school = $('.gcconnex-education-school').val();
            
            var $certification_guid = [];
            var $delete_guid = [];

            $('.gcconnex-certification-entry').each(function() {
                if ( $(this).is(":hidden") ) {
                    if ($(this).data('guid') != "new") {
                        $delete_guid.push($(this).data('guid'));
                    }
                }
                else {
                    $certification_guid.push($(this).data('guid'));
                }
            });

            var $certification = [];
            $('.gcconnex-certification-name').not(":hidden").each(function() {
                $certification.push($(this).val());
            });
            
            var $authority = [];
            $('.gcconnex-certification-authority').not(":hidden").each(function() {
                $authority.push($(this).val());
            });
            
            var $licence = [];
            $('.gcconnex-certification-licence').not(":hidden").each(function() {
                $licence.push($(this).val());
            });
            
            var $certurl = [];
            $('.gcconnex-certification-certurl').not(":hidden").each(function() {
                $certurl.push($(this).val());
            });
            
            var $activity = [];
            $('.gcconnex-education-activity').not(":hidden").each(function() {
                $activity.push($(this).val());
            });
            
            var $trainingd = [];
            $('.gcconnex-education-trainingd').not(":hidden").each(function() {
                $trainingd.push($(this).val());
            });

            var $startdate = [];
            $('.gcconnex-education-startdate').not(":hidden").each(function() {
                $startdate.push($(this).val());
            });

            var $startyear = [];
            $('.gcconnex-education-start-year').not(":hidden").each(function() {
                $startyear.push($(this).val());
            });

            var $enddate = [];
            $('.gcconnex-education-enddate').not(":hidden").each(function() {
                $enddate.push($(this).val());
            });

            var $endyear = [];
            $('.gcconnex-education-end-year').not(":hidden").each(function() {
                $endyear.push($(this).val());
            });

            var $ongoing = [];
            $('.gcconnex-education-ongoing').not(":hidden").each(function() {
                $ongoing.push($(this).prop('checked'));
            });

            /*
            var $program = [];
            $('.gcconnex-education-program').not(":hidden").each(function() {
                $program.push($(this).val());
            });
            */

            var $degree = [];
            $('.gcconnex-education-degree').not(":hidden").each(function() {
                $degree.push($(this).val());
            });

            var $field = [];
            $('.gcconnex-education-field').not(":hidden").each(function() {
                $field.push($(this).val());
            });
            var $access = $('.gcconnex-education-access').val();

            // save the information the user just edited
            elgg.action('pessek_profile/edit_profile', {
                data: {
                    guid: elgg.get_logged_in_user_guid(),
                    delete: $delete_guid,
                    eguid: $certification_guid,
                    section: 'certification',
                    school: $school,
                    diploma : $diploma,
                    resultobtain : $resultobtain,
                    educationurl : $educationurl,
                    activity : $activity,
                    trainingd : $trainingd,
                    startdate: $startdate,
                    startyear: $startyear,
                    enddate: $enddate,
                    endyear: $endyear,
                    ongoing: $ongoing,
                    //program: $program,
                    degree: $degree,
                    field: $field,
                    access: $access
                },
                success: function() {            // fetch and display the information we just saved
                    $.get(elgg.normalize_url('ajax/view/pessek_profile/education'),
                        {
                            guid: elgg.get_logged_in_user_guid()
                        },
                        function(data) {
                            // Output in a DIV with id=somewhere
                            $('.gcconnex-education-display').remove();
                            $('.gcconnex-education').append('<div class="gcconnex-education-display">' + data + '</div>');
                        });
                }
                });
            $('.gcconnex-certification-edit-wrapper').remove();

            break;
            
        case "work-experience":

            var work_experience = {};
            var experience = [];

            work_experience.edit = experience;
            work_experience.delete = [];

            $('.gcconnex-work-experience-entry').each(function() {
                if ( $(this).is(":hidden") ) {
                    //if ($(this).data('guid') != "new") {
                        work_experience.delete.push($(this).data('guid'));
                        //$delete_guid.push($(this).data('guid'));
                   // }
                }
                else {
                    experience = {
                        'eguid': $(this).data('guid'),
                        'organization': $(this).find('.gcconnex-work-experience-organization').val(),
                        'title': $(this).find('.gcconnex-work-experience-title').val(),
                        'startdate': $(this).find('.gcconnex-work-experience-startdate').val(),
                        'startyear': $(this).find('.gcconnex-work-experience-start-year').val(),
                        'enddate': $(this).find('.gcconnex-work-experience-enddate').val(),
                        'endyear': $(this).find('.gcconnex-work-experience-end-year').val(),
                        'ongoing': $(this).find('.gcconnex-work-experience-ongoing').prop('checked'),
                        'responsibilities': $(this).find('.gcconnex-work-experience-responsibilities').val()
                    };
                    experience.colleagues = [];
                    $(this).find('.gcconnex-avatar-in-list').each(function() {
                        if ($(this).is(':visible')) {
                            experience.colleagues.push($(this).data('guid'));
                        }
                    });
                    work_experience.edit.push(experience);
                }
            });

            // save the information the user just edited
            elgg.action('b_extended_profile/edit_profile', {
                data: {
                    guid: elgg.get_logged_in_user_guid(),
                    work: work_experience,
                    section: 'work-experience'
                },
                success: function() {
                    $.get(elgg.normalize_url('ajax/view/b_extended_profile/work-experience'),
                        {
                            guid: elgg.get_logged_in_user_guid()
                        },
                        function(data) {
                            // Output in a DIV with id=somewhere
                            $('.gcconnex-profile-work-experience-display').remove();
                            $('.gcconnex-work-experience').append('<div class="gcconnex-profile-work-experience-display"><div class="gcconnex-work-experience-display">' + data + '</div></div>');
                        });
                }
            });
            $('.gcconnex-work-experience-edit-wrapper').remove();

            // fetch and display the information we just saved

            //$('.gcconnex-profile-work-experience-display').hide();
            break;

        case "skills":

            if ( $('.gcconnex-skill-entry:visible').length >= 15 ) {
                alert('Too many!');
                // toggle the edit, save, cancel buttons
                $('.edit-' + $section).hide();
                $('.save-' + $section).show();
                $('.cancel-' + $section).show();
            }
            else {
                var $skills_added = [];
                var $delete_guid = [];

                if ($('.gcconnex-endorsements-input-skill').is(":visible")) {
                    skillSubmit();
                }

                $('.gcconnex-skill-entry').each(function () {
                    if ($(this).is(":hidden")) {
                        $delete_guid.push($(this).data('guid'));
                    }
                    if ($(this).hasClass("temporarily-added")) {
                        $skills_added.push($(this).data('skill'));
                    }
                });

                // save the information the user just edited

                elgg.action('b_extended_profile/edit_profile', {
                    guid: elgg.get_logged_in_user_guid(),
                    section: 'skills',
                    skillsadded: $skills_added,
                    skillsremoved: $delete_guid
                });

                $('.delete-skill-img').remove();
                $('.delete-skill').remove();
                $('.gcconnex-endorsements-input-wrapper').remove();
                $('.gcconnex-skill-entry').removeClass('temporarily-added');
            }
            break;

        case 'languages':

            var english = [];
            var french = [];

            $official_langs = $('.gcconnex-profile-language-official-languages');

            english = {
                'writtencomp': $official_langs.find('.gcconnex-languages-english-writtencomp').val(),
                'writtenexp': $official_langs.find('.gcconnex-languages-english-writtenexp').val(),
                'oral': $official_langs.find('.gcconnex-languages-english-oral').val(),
                'expiry': $official_langs.find('#english_expiry').val()
            };

            french = {
                'writtencomp': $official_langs.find('.gcconnex-languages-french-writtencomp').val(),
                'writtenexp': $official_langs.find('.gcconnex-languages-french-writtenexp').val(),
                'oral': $official_langs.find('.gcconnex-languages-french-oral').val(),
                'expiry': $official_langs.find('#french_expiry').val()
            };

            // save the information the user just edited
            elgg.action('b_extended_profile/edit_profile', {
                data: {
                    guid: elgg.get_logged_in_user_guid(),
                    section: 'languages',
                    english: english,
                    french: french
                },
                success: function() {
                    $.get(elgg.normalize_url('ajax/view/b_extended_profile/languages'),
                        {
                            guid: elgg.get_logged_in_user_guid()
                        },
                        function(data) {
                            // Output in a DIV with id=somewhere
                            $('.gcconnex-profile-languages-display').remove();
                            $('.gcconnex-languages').append('<div class="gcconnex-profile-languages-display">' + data + '</div>');
                        });
                }
            });
            $('.gcconnex-languages-edit-wrapper').remove();
            break;
        case 'portfolio':
            // Save the portfolio
            var portfolio = {};
            var entry = [];

            portfolio.edit = entry;
            portfolio.delete = [];

            $('.gcconnex-portfolio-entry').each(function() {
                if ( $(this).is(":hidden") ) {
                    portfolio.delete.push($(this).data('guid'));
                }
                else {
                    entry = {
                        'eguid': $(this).data('guid'),
                        'title': $(this).find('.gcconnex-portfolio-title').val(),
                        'link': $(this).find('.gcconnex-portfolio-link').val(),
                        'pubdate': $(this).find('#pubdate').val(),
                        'datestamped': $(this).find('.gcconnex-portfolio-datestamped').val(),
                        'description': $(this).find('.gcconnex-portfolio-description').val(),
                    };
                    portfolio.edit.push(entry);
                }
            });

            elgg.action('b_extended_profile/edit_profile', {
                data: {
                    guid: elgg.get_logged_in_user_guid(),
                    section: 'portfolio',
                    portfolio: portfolio
                },
                success: function() {
                    $.get(elgg.normalize_url('ajax/view/b_extended_profile/portfolio'),
                        {
                            guid: elgg.get_logged_in_user_guid()
                        },
                        function (data) {
                            // Output portfolio here
                            $('.gcconnex-profile-portfolio-display').remove();
                            $('.gcconnex-portfolio').append('<div class="gcconnex-profile-portfolio-display">' + data + '</div>');
                        });
                }
            });
            $('.gcconnex-portfolio-edit-wrapper').remove();
            break;
        default:
            break;
    }
}

/*
 * Purpose: Handle click event on the cancel button for all profile changes
 */
function cancelChanges(event) {

    var $section = event.data.section;

    $('.edit-' + $section).show();
    $('.save-' + $section).hide();
    $('.cancel-' + $section).hide();

    switch ($section) {
        case "about-me":
            // show the about me
            $('.gcconnex-about-me-edit-wrapper').remove();
            $('.gcconnex-profile-about-me-display').show();
            break;
        case "education":
            $('.gcconnex-education-edit-wrapper').remove();
            $('.gcconnex-profile-education-display').show();
            break;
        case "certification":
            $('.gcconnex-certification-edit-wrapper').remove();
            $('.gcconnex-profile-certification-display').show();
            break;
        case "work-experience":
            $('.gcconnex-work-experience-edit-wrapper').remove();
            $('.gcconnex-profile-work-experience-display').show();
            break;
        case "skills":
            $('.gcconnex-endorsements-input-wrapper').remove();

            $('.delete-skill').remove();
            $('.delete-skill-img').remove();
            $('.gcconnex-skills-skill-wrapper').removeClass('endorsements-markedForDelete');

            $('.gcconnex-skills-skill-wrapper').show();
            $('.temporarily-added').remove();
            break;
        case 'languages':
            $('.gcconnex-languages-edit-wrapper').remove();
            $('.gcconnex-profile-languages-display').show();
            break;
        case 'portfolio':
            $('.gcconnex-portfolio-edit-wrapper').remove();
            $('.gcconnex-profile-portfolio-display').show();
            break;
        default:
            break;
    }
}

/*
 * Purpose: Listen for the enter key in the "add new skill" text box
 */
function checkForEnter(event) {
    if (event.keyCode == 13) { // 13 = 'Enter' key

        // The new skill being added, as entered by user
        //var newSkill = $('.gcconnex-endorsements-input-skill').val().trim();
        var newSkill = $('.gcconnex-endorsements-input-skill').typeahead('val');
        // @todo: do data validation to ensure css class-friendly naming (ie: no symbols)
        // @todo: add a max length to newSkill
        addNewSkill(newSkill);
    }
}

/*
 * Purpose: Only allow numbers to be entered for the year inputs
 */
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

/*
 * Purpose: disable the end date inputs when a user selects "I'm currently still working here"
 */

function toggleEndDate(section, evt) {

    $(evt).closest('.gcconnex-' + section + '-entry').find('.gcconnex-' + section + '-end-year').attr('disabled', evt.checked);
    $(evt).closest('.gcconnex-' + section + '-entry').find('.gcconnex-' + section + '-enddate').attr('disabled', evt.checked);
    
    if(section == "education"){
        
        if(evt.checked){
            $('.gcconnex-education-end-year').prop('disabled', true);
        }else{
            $('.gcconnex-education-end-year').prop('disabled', false);
        }
        
    }
    
    if(section == "certification"){
        
        if(evt.checked){
            $('.gcconnex-certification-endmonth').prop('disabled', true);
            $('.gcconnex-certification-endyear').prop('disabled', true);
        }else{
            $('.gcconnex-certification-endmonth').prop('disabled', false);
            $('.gcconnex-certification-endyear').prop('disabled', false);
        }
        
    }
    
    if(section == "experience"){
        
        if(evt.checked){
            $('.gcconnex-experience-endmonth').prop('disabled', true);
            $('.gcconnex-experience-endyear').prop('disabled', true);
        }else{
            $('.gcconnex-experience-endmonth').prop('disabled', false);
            $('.gcconnex-experience-endyear').prop('disabled', false);
        }
        
    }
    
    if(section == "projects"){
        
        if(evt.checked){
            $('.gcconnex-projects-endmonth').prop('disabled', true);
            $('.gcconnex-projects-endyear').prop('disabled', true);
        }else{
            $('.gcconnex-projects-endmonth').prop('disabled', false);
            $('.gcconnex-projects-endyear').prop('disabled', false);
        }
        
    }
    
    if(section == "portfolio"){
        
        if(evt.checked){
            $('.gcconnex-portfolio-startday').prop('disabled', true);
            $('.gcconnex-portfolio-startmonth').prop('disabled', true);
            $('.gcconnex-portfolio-startyear').prop('disabled', true);
        }else{
            $('.gcconnex-portfolio-startday').prop('disabled', false);
            $('.gcconnex-portfolio-startmonth').prop('disabled', false);
            $('.gcconnex-portfolio-startyear').prop('disabled', false);
        }
        
    }
    
    if(section == "mooc"){
        
        if(evt.checked){
            $('.gcconnex-mooc-endmonth').prop('disabled', true);
            $('.gcconnex-mooc-endyear').prop('disabled', true);
        }else{
            $('.gcconnex-mooc-endmonth').prop('disabled', false);
            $('.gcconnex-mooc-endyear').prop('disabled', false);
        }
        
    }
    
    
    //alert("pessek");
    /*
    $(evt).closest('.gcconnex-work-experience-entry').find('.gcconnex-work-experience-end-year').attr('disabled', function(index, attr) {
        return attr == 'disabled' ? null : 'disabled';
    });

    $(evt).closest('.gcconnex-work-experience-entry').find('.gcconnex-work-experience-enddate').attr('disabled', function(index, attr) {
        return attr == 'disabled' ? null : 'disabled';
    });
    */
    /*
    $('.gcconnex-' + section + '-enddate-' + guid).attr('disabled', function(index, attr) {
        return attr == 'disabled' ? null : 'disabled';
    });

    $('.gcconnex-' + section + '-end-year-' + guid).attr('disabled', function(index, attr) {
        return attr == 'disabled' ? null : 'disabled';
    });
    */
}

/*
 * Purpose: add colleague to work-experience entry
 */
function addColleague(obj, datum, name) {
    //var colleague = datum.avatar;

    if ($(this).closest('.gcconnex-work-experience-entry').find("[data-guid=" + datum.guid + "]") && $(this).closest('.gcconnex-work-experience-entry').find("[data-guid=" + datum.guid + "]").is(":hidden")) {
        $(this).closest('.gcconnex-work-experience-entry').find("[data-guid=" + datum.guid + "]").show();
    }
    else {
        $(this).closest('.gcconnex-work-experience-entry').find('.list-avatars').append(
            '<div class="gcconnex-avatar-in-list temporarily-added" data-guid="' + datum.guid + '" onclick="removeColleague(this)">' +
            '<div class="remove-colleague-from-list">X</div>' + datum.avatar + '</div>'
        );
    }
    $('.userfind').typeahead('val', '');        // clear the typeahead box
    // remove colleague from suggestible usernames list
}

function addColleaguePessek(obj, datum, name) {
    //var colleague = datum.avatar;

    if ($(this).closest('.gcconnex-publications-entry').find("[data-guid=" + datum.guid + "]") && $(this).closest('.gcconnex-publications-entry').find("[data-guid=" + datum.guid + "]").is(":hidden")) {
        $(this).closest('.gcconnex-publications-entry').find("[data-guid=" + datum.guid + "]").show();
    }
    else {
        $(this).closest('.gcconnex-publications-entry').find('.list-avatars').append(
            '<div class="gcconnex-avatar-in-list temporarily-added" data-guid="' + datum.guid + '" onclick="removeColleague(this)">' +
            '<div class="remove-colleague-from-list">X</div>' + datum.avatar + '</div>'
        );
    }
    $('.gcconnex-endorsements-input-skil').typeahead('val', '');        // clear the typeahead box 
    // remove colleague from suggestible usernames list
    //alert('123456');
}

/*
 * Purpose: When user clicks on the "X" to remove a user from the list of colleagues, animate the removal
 */
function removeColleague(identifier) {
    $(identifier).fadeOut('slow', function() {
        if ($(identifier).hasClass('temporarily-added')) {
            $(identifier).remove();
            tid = $('.gcconnex-work-experience-colleagues').data("tid");
            guid = $(identifier).data('guid');
            $colleagueSelected[tid].splice($.inArray(guid, $colleagueSelected[tid]), 1);
        }
        else {
            $(identifier).hide();
            tid = $('.gcconnex-work-experience-colleagues').data("tid");
            guid = $(identifier).data('guid');
            $colleagueSelected[tid].splice($.inArray(guid, $colleagueSelected[tid]), 1);
        }
    });
    //add colleague back to suggestible usernames list
}


/*
 * Purpose: to trigger the submission of a co-author that was selected or auto-completed from the typeahead suggestion list
 */

function removeCoauthor(identifier) {
    $(identifier).fadeOut('slow', function() {
        if ($(identifier).hasClass('temporarily-added')) {
            $(identifier).remove();
            tid = $('.gcconnex-endorsements-input-coauthor').data("tid");
            guid = $(identifier).data('guid');
        }
        else {
            $(identifier).hide();
            tid = $('.gcconnex-endorsements-input-coauthor').data("tid");
            guid = $(identifier).data('guid');
        }
    });
}

function CoauthorSubmit(obj, datum, name) {
    //var colleague = datum.avatar;
    if ($(this).closest('.gcconnex-publications-entry').find("[data-guid=" + datum.guid + "]") && $(this).closest('.gcconnex-publications-entry').find("[data-guid=" + datum.guid + "]").is(":hidden")) {
        $(this).closest('.gcconnex-publications-entry').find("[data-guid=" + datum.guid + "]").show();
    }
    else {
       if(!$("[data-guid-userfind=" + datum.guid + "]").length){
            $(this).closest('.gcconnex-publications-entry').find('.list-avatars').append(
                '<div class="gcconnex-avatar-in-list temporarily-added" data-guid="' + datum.guid + '" data-guid-userfind="' + datum.guid + '" onclick="removeCoauthor(this)">' +
                '<div class="remove-coauthor-from-list">X</div>' + datum.avatar + '<input type="hidden" value="' + datum.guid + '" name="user@' + datum.guid + '" id="user@' + datum.guid + '" class="user@' + datum.guid + '"></div>'
            );
        }
    }
    
    $('.coauthorfind').typeahead('val', '');        // clear the typeahead box
    
    // remove colleague from suggestible usernames list
    //alert(datum.guid);
    //alert(datum.avatar);
}

function SkillsSubmit(obj, datum, name) {
    
    var language_code = elgg.get_language(); //language_code = 'fr';
    
    var skills_val_cat = '0@' + datum.skills_id + '@' + datum.skills_en + '' ;
    
    var skills_lib = datum.skills_en ;
    
    if(language_code=="fr"){
        
        skills_val_cat = '0@' + datum.skills_id + '@' + datum.skills_fr + '' ;
        skills_lib = datum.skills_fr ;
                        
    }  
   
    if(!$("[data-guid-skillfind=" + datum.skills_id + "]").length){
        
        $(this).closest('.gcconnex-skills-entry').find('.list-skills').append(
            '<div class="gcconnex-skill-entry temporarily-added" data-skill="' + skills_lib + '" data-guid="' + datum.skills_id + '" data-guid-skillfind="' + datum.skills_id + '">' +
            '<span title="Number of endorsements" class="gcconnex-endorsements-count" data-skill="' + skills_lib + '">&nbsp;</span>' +
            '<span data-skill="' + skills_lib + '" class="gcconnex-endorsements-skill">' + skills_lib + '</span>' +
            '<span class="delete-skill-pessek btn btn-danger btn-xs glyphicon glyphicon-trash" data-type="skill" onclick="deleteEntry(this)"></span>' +
            '<input type="hidden" value="' + skills_val_cat + '" name="user@' + datum.skills_id + '" id="user@' + datum.skills_id + '" class="user@' + datum.skills_id + '"></div>'
        );
        
    }
   
    $('.gcconnex-skills-skill').typeahead('val', ''); 

}

function CountrySubmit(obj, datum, name) {
    //var colleague = datum.avatar;

   $('.gcconnex-country-in-list').remove();
    
    var language_code = elgg.get_language();
    
    var contry_flag_name = datum.flag_name;
    
    contry_flag_name = contry_flag_name.toLowerCase(); 
                    
    var contryConcat = contry_flag_name + '@' + datum.common_name ;
    
    if(language_code=="fr"){
        
        var contryConcat = contry_flag_name + '@' + datum.common_name_fr ;
                        
    } 

    //alert(contryConcat);

    $(this).closest('.gcconnex-experience-entry').find('.list-country').append(
        '<div class="gcconnex-country-in-list temporarily-added"><input type="hidden" value="' + contryConcat + '" name="thecountry" id="thecountry" class="thecountry"></div>'
    );
    
}

function CountryOriginSubmit(obj, datum, name) {
    //var colleague = datum.avatar;

   $('.gcconnex-country-in-list-origin').remove();
    
    var language_code = elgg.get_language();
    
    var contry_flag_name = datum.flag_name;
    
    contry_flag_name = contry_flag_name.toLowerCase(); 
                    
    var contryConcat = contry_flag_name + '@' + datum.common_name ;
    
    if(language_code=="fr"){
        
        var contryConcat = contry_flag_name + '@' + datum.common_name_fr ;
                        
    } 

    //alert(contryConcat);

    $(this).closest('.gcconnex-portfolio-entry').find('.list-country-origin').append(
        '<div class="gcconnex-country-in-list-origin temporarily-added"><input type="hidden" value="' + contryConcat + '" name="thecountryoforigin" id="thecountryoforigin" class="thecountryoforigin"></div>'
    );
    
}

function CountryResidenceSubmit(obj, datum, name) {
    //var colleague = datum.avatar;

   $('.gcconnex-country-in-list-residence').remove();
    
    var language_code = elgg.get_language();
    
    var contry_flag_name = datum.flag_name;
    
    contry_flag_name = contry_flag_name.toLowerCase(); 
                    
    var contryConcat = contry_flag_name + '@' + datum.common_name ;
    
    if(language_code=="fr"){
        
        var contryConcat = contry_flag_name + '@' + datum.common_name_fr ;
                        
    } 

    //alert(contryConcat);

    $(this).closest('.gcconnex-portfolio-entry').find('.list-country-residence').append(
        '<div class="gcconnex-country-in-list-residence temporarily-added"><input type="hidden" value="' + contryConcat + '" name="thecountryofresidence" id="thecountryofresidence" class="thecountryofresidence"></div>'
    );
    
}

/*
 * Purpose: to trigger the submission of a skill that was selected or auto-completed from the typeahead suggestion list
 */
function skillSubmit() {
    var myVal = $('.gcconnex-endorsements-input-skill').typeahead('val');
    addNewSkill(myVal);
}

/*
 * Purpose: append a new skill to the bottom of the list
 */
function addNewSkill(newSkill) {

    //var newSkillDashed = newSkill.replace(/\s+/g, '-').toLowerCase(); // replace spaces with '-' for css classes

    newSkill = escapeHtml(newSkill);
    // @todo: cap the list of skills at ~8-10 in order not to have "too many" on each profile
    // inject HTML for newly added skill
    $('.gcconnex-skills-skills-list-wrapper').append('<div class="gcconnex-skill-entry temporarily-added" data-skill="' + newSkill + '">' +
    '<span title="Number of endorsements" class="gcconnex-endorsements-count" data-skill="' + newSkill + '">0</span>' +
    '<span data-skill="' + newSkill + '" class="gcconnex-endorsements-skill">' + newSkill + '</span>' +
    '<img class="delete-skill-img" src="' + elgg.get_site_url() + 'mod/b_extended_profile/img/delete.png">' +
    '<span class="delete-skill" data-type="skill" onclick="deleteEntry(this)">' + elgg.echo("gcconnex_profile:gc_skill:delete", null, null) + '</span></div>');

    $('.gcconnex-endorsements-input-skill').val('');                                 // clear the text box
    $('.gcconnex-endorsements-input-skill').typeahead('val', '');                                           // clear the typeahead box
    $('.gcconnex-endorsements-input-skill').hide();                                  // hide the text box
    $('.gcconnex-endorsements-add-skill').show();                                    // show the 'add a new skill' link
    $('.add-endorsements-' + newSkill).on('click', addEndorsement);            // bind the addEndoresement function to the '+'
    $('.retract-endorsements-' + newSkill).on('click', retractEndorsement);    // bind the retractEndorsement function to the '-'
    $('.delete-' + newSkill).on('click', deleteSkill);                        // bind the deleteSkill function to the 'Delete this skill' link
}

/*
 * Purpose: Increase the endorsement count by one, for a specific skill for a specific user
 */
function addEndorsement(identifier) {
    // A user is endorsing a skill! Do some things about it..
    var skill_guid = $(identifier).data('guid');

    elgg.action('b_extended_profile/add_endorsement', {
        guid: elgg.get_logged_in_user_guid(),
        skill: skill_guid
    });


    var targetSkill = $(identifier).data('skill');
    var targetSkillDashed = targetSkill.replace(/\s+/g, '-').toLowerCase(); // replace spaces with '-' for css classes


    var endorse_count = $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsements-count').text();
    endorse_count++;
    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsements-count').text(endorse_count);

    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').append('<span class="gcconnex-endorsement-retract elgg-button btn" onclick="retractEndorsement(this)" data-guid="' + skill_guid + '" data-skill="' + targetSkill + '">Retract Endorsement</span>')
    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsement-add').remove();
    //$('.add-endorsement-' + targetSkillDashed).remove();
}

function Add_Endorsement(identifier) {
    
    var disLike_Image = elgg.get_site_url() + "mod/pessek_profile/img/dislike.png";
    
    var skill_guid = $(identifier).data('guid');
    
    var targetSkill = $(identifier).data('skill');
    var targetSkillDashed = targetSkill.replace(/\s+/g, '-').toLowerCase(); // replace spaces with '-' for css classes
    
    var endorse_count = $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsements-count').text();
    endorse_count++;
    
    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsements-count').text(endorse_count);
    
    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').append('<span class="gcconnex-endorsement-retract elgg-button btn" onclick="Retract_Endorsement(this)" data-guid="' + skill_guid + '" data-skill="' + targetSkill + '"> </span>');
    /* $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').append('<span class="gcconnex-endorsement-retract elgg-button btn" onclick="Retract_Endorsement(this)" data-guid="' + skill_guid + '" data-skill="' + targetSkill + '"> <img src="' + disLike_Image + '" title="' + elgg.echo('gcconnex_profile:skills:retractendorsement') + '" style="float: left"> </span>');*/
    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsement-add').remove();
    
    $('.ajax_auf_loading').show();
    
    elgg.action('action/pessek_profile/endorsements/add', {
        data : {
            guid: elgg.get_logged_in_user_guid(),
            skill: skill_guid
        },
        success: function(json) {
                            
                $.get(elgg.normalize_url('ajax/view/pessek_profile/skills'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-skillss-display').remove();
                    $('.gcconnex-skillss').append('<div class="gcconnex-profile-skillss-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
}

/*
 * Purpose: Retract a previous endorsement for a specific skill for a specific user
 */
function Retract_Endorsement(identifier) {
    
    var Like_Image = elgg.get_site_url() + "mod/pessek_profile/img/like.png";
    
    var skill_guid = $(identifier).data('guid');

    var targetSkill = $(identifier).data('skill');
    var targetSkillDashed = targetSkill.replace(/\s+/g, '-').toLowerCase(); // replace spaces with '-' for css classes


    var endorse_count = $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsements-count').text();
    endorse_count--;
    
    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsements-count').text(endorse_count);

    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').append('<span class="gcconnex-endorsement-add elgg-button btn" onclick="Add_Endorsement(this)" data-guid="' + skill_guid + '" data-skill="' + targetSkill + '"></span>');
    
    /*$('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').append('<span class="gcconnex-endorsement-add elgg-button btn" onclick="Add_Endorsement(this)" data-guid="' + skill_guid + '" data-skill="' + targetSkill + '"><img src="' + Like_Image + '" title="' + elgg.echo('gcconnex_profile:skills:endorse') + '" style="float: left"></span>');*/

    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsement-retract').remove();
    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-skill-endorsements').remove();
    
    $('.ajax_auf_loading').show();
    
    elgg.action('pessek_profile/endorsements/delete', {
        data: {
            guid: elgg.get_logged_in_user_guid(),
            skill: skill_guid
        },
        success: function(json) {
                            
                $.get(elgg.normalize_url('ajax/view/pessek_profile/skills'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-skillss-display').remove();
                    $('.gcconnex-skillss').append('<div class="gcconnex-profile-skillss-display">' + data + '</div>');
                });
                
                 $('.ajax_auf_loading').hide();
        },
    });
    
    //event.preventDefault();
    
    
}

function retractEndorsement(identifier) {
    // A user is retracting their endorsement for a skill! Do stuff about it..
    var skill_guid = $(identifier).data('guid');

    elgg.action('b_extended_profile/retract_endorsement', {
        guid: elgg.get_logged_in_user_guid(),
        skill: skill_guid
    });


    var targetSkill = $(identifier).data('skill');
    var targetSkillDashed = targetSkill.replace(/\s+/g, '-').toLowerCase(); // replace spaces with '-' for css classes


    var endorse_count = $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsements-count').text();
    endorse_count--;
    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsements-count').text(endorse_count);

    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').append('<span class="gcconnex-endorsement-add elgg-button btn" onclick="addEndorsement(this)" data-guid="' + skill_guid + '" data-skill="' + targetSkill + '">Endorse</span>')

    //$(identifier).after('<span class="gcconnex-endorsement-add add-endorsement-' + targetSkillDashed + '" onclick="addEndorsement(this)" data-guid="' + skill_guid + '" data-skill="' + targetSkill + '">+</span>');
    $('.gcconnex-skill-entry[data-guid="' + skill_guid + '"]').find('.gcconnex-endorsement-retract').remove();
}

/*
 * Purpose: Delete a skill from the list of endorsements
 */
function deleteSkill() {
    // We don't _actually_ delete anything yet, since the user still has the ability to click 'Cancel' and bring the skill back,
    // instead, we just hide the skill until the user clicks on 'Save'. See the 'saveChanges' function for
    // the actual code where skills are permanently deleted.
    $(this).parent('.gcconnex-endorsements-skill-wrapper').addClass('endorsements-markedForDelete').hide();
}

/*
 * Purpose: add more inputs for the input type
 */
function addMore(identifier) {
    another = $(identifier).data('type');
    $.when( $.get(elgg.normalize_url('ajax/view/input/' + another), '',
        function(data) {
            $('.gcconnex-' + another + '-all').append(data);
        })).done(function() {
        if (another == "work-experience") {
            $temp = $('.gcconnex-work-experience-entry.new').find('.userfind');
            $temp.each(function() {
                user_search_init(this);
            });
        }
    });
}

/*
 * Purpose: Delete an entry based on the value of the data-type attribute in the delete link
 */
function deleteEntry(identifier) {
    // get the entry-type name
    var entryType = $(identifier).data('type');

    if ($(identifier).closest('.gcconnex-' + entryType + '-entry').hasClass('temporarily-added')) {
        $(identifier).closest('.gcconnex-' + entryType + '-entry').remove(); 
    }
    else {
        // mark the entry for deletion and hide it from view
        $(identifier).closest('.gcconnex-' + entryType + '-entry').hide();
    }
}

/*
 * Purpose: Remove the message box that informs users they need to re-enter their skills into the new system
 */
function removeOldSkills() {
    elgg.action('b_extended_profile/edit_profile', {
        data: {
            guid: elgg.get_logged_in_user_guid(),
            section: 'old-skills'
        },
        success: function() {
            $('.gcconnex-old-skills').remove();
        }
    });

}

var entityMap = {
    "<": "<",
    ">": ">",
    '"': '&quot;',
    "'": '\'',
    "/": '\/'
};

function escapeHtml(string) {
    return String(string).replace(/[<>"'\/]/g, function (s) {
        return entityMap[s];
    });
}

/*
$('.custom-form').submit(function() {

    var Ajax = require('elgg/Ajax');
    var ajax = new Ajax();

	ajax.action('pessek_profile/portfolio/add', {
		data: {
			arg1: 1,
			arg2: 2
		},
	}).done(function (output, statusText, jqXHR) {
	    if (jqXHR.AjaxData.status == -1) {
	        return;
	    }
		alert('output.sum');
		alert('output.product');
	});
});
*/
$(document).on('submit', '.custom-form', function(event) {
    
    var form = $(this);
    var data = form.serialize();
    
    $('.ajax_auf_loading').show();
    
    elgg.action('pessek_profile/portfolio/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/portfolio'),{
                    //guid: elgg.get_logged_in_user_guid()
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                            // Output portfolio here
                    $('.gcconnex-profile-portfolio-display').remove();
                    $('.gcconnex-portfolio').append('<div class="gcconnex-profile-portfolio-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
    event.preventDefault();
    
});

$(document).on('submit', '.education-form', function(event) {
    
    var form = $(this);
    var data = form.serialize();
    
    $('.ajax_auf_loading').show();
   
    elgg.action('pessek_profile/education/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/education'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-education-display').remove();
                    $('.gcconnex-education').append('<div class="gcconnex-profile-education-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
    event.preventDefault();
    
});

$(document).on('submit', '.certification-form', function(event) {
    
    var form = $(this);
    var data = form.serialize();
    
    $('.ajax_auf_loading').show();
   
    elgg.action('pessek_profile/certification/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/certification'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-certification-display').remove();
                    $('.gcconnex-certification').append('<div class="gcconnex-profile-certification-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
    event.preventDefault();
    
});

$(document).on('submit', '.mooc-form', function(event) {
    
    var form = $(this);
    var data = form.serialize();
    
    $('.ajax_auf_loading').show();
   
    elgg.action('pessek_profile/mooc/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/mooc'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-mooc-display').remove();
                    $('.gcconnex-mooc').append('<div class="gcconnex-profile-mooc-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
    event.preventDefault();
    
});

$(document).on('submit', '.publications-form', function(event) {
            
    var HidCoauthor = '';
    var HidCoauthorVal = '';
    var sep = ',';
    var i = 0;
    $.each($('input'),function(i,val){
               
                if($(this).attr("type")=="hidden"){
                
                     var HidFiledName = $(this).attr('name');
                     var HidFiledNameVal = $(this).val();
                     
                     var res = HidFiledName.split("@");
                    
                    if(res[1] !== undefined){
                            //alert(res[1]);
                        if(i == 0){
                            HidCoauthor  = HidFiledName;
                            HidCoauthorVal  = HidFiledNameVal;
                        }else{
                            HidCoauthor = HidFiledName + sep + HidCoauthor;
                            HidCoauthorVal = HidFiledNameVal + sep + HidCoauthorVal;
                        }
                        
                         i++;
                    }
                    
                }
    });
 
    var form = $(this);
    //var data = form.serialize();
    var data =   $(this).serialize() + '&HidCoauthor=' + HidCoauthor + '&HidCoauthorVal=' + HidCoauthorVal; 
    
    //alert(HidCoauthor);
    
    $('.ajax_auf_loading').show();
    
    elgg.action('pessek_profile/publications/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/publications'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-publications-display').remove();
                    $('.gcconnex-publications').append('<div class="gcconnex-profile-publications-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
    event.preventDefault();
    
});

$(document).on('submit', '.projects-form', function(event) {
            
    var HidCoauthor = '';
    var HidCoauthorVal = '';
    var sep = ',';
    var i = 0;
    $.each($('input'),function(i,val){
               
                if($(this).attr("type")=="hidden"){
                
                     var HidFiledName = $(this).attr('name');
                     var HidFiledNameVal = $(this).val();
                     
                     var res = HidFiledName.split("@");
                    
                    if(res[1] !== undefined){
                            //alert(res[1]);
                        if(i == 0){
                            HidCoauthor  = HidFiledName;
                            HidCoauthorVal  = HidFiledNameVal;
                        }else{
                            HidCoauthor = HidFiledName + sep + HidCoauthor;
                            HidCoauthorVal = HidFiledNameVal + sep + HidCoauthorVal;
                        }
                        
                         i++;
                    }
                    
                }
    });
 
    var form = $(this);
    //var data = form.serialize();
    var data =   $(this).serialize() + '&HidCoauthor=' + HidCoauthor + '&HidCoauthorVal=' + HidCoauthorVal; 
    
    //alert(HidCoauthor);
    
    $('.ajax_auf_loading').show();
   
    elgg.action('pessek_profile/projects/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/projects'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-projects-display').remove();
                    $('.gcconnex-projects').append('<div class="gcconnex-profile-projects-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
    event.preventDefault();
    
});

$(document).on('submit', '.experience-form', function(event) {
    
    var form = $(this);
    var data = form.serialize();
    
    $('.ajax_auf_loading').show();
    
    elgg.action('pessek_profile/experience/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/experiences'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-experiences-display').remove();
                    $('.gcconnex-experiences').append('<div class="gcconnex-profile-experiences-display">' + data + '</div>');
                });
                $.get(elgg.normalize_url('ajax/view/pessek_profile/volunteers'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-volunteers-display').remove();
                    $('.gcconnex-volunteers').append('<div class="gcconnex-profile-volunteers-display">' + data + '</div>');
                });
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/internships'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-internships-display').remove();
                    $('.gcconnex-internships').append('<div class="gcconnex-profile-internships-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
    event.preventDefault();
    
});


$(document).on('submit', '.languages-form', function(event) {
    
    var form = $(this);
    var data = form.serialize();
    
    $('.ajax_auf_loading').show();
    
    elgg.action('pessek_profile/languages/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/languages'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-languagess-display').remove();
                    $('.gcconnex-languagess').append('<div class="gcconnex-profile-languagess-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
    event.preventDefault();
    
});


$(document).on('submit', '.skills-form', function(event) {
            
    var HidSkills = '';
    var HidSkillsVal = '';
    var sep = ',';
    var i = 0;
    $.each($('input'),function(i,val){
               
                if($(this).attr("type")=="hidden"){
                
                     var HidFiledName = $(this).attr('name');
                     var HidFiledNameVal = $(this).val();
                     
                     var res = HidFiledName.split("@");
                    
                    if(res[1] !== undefined){
                            //alert(res[1]);
                        if(i == 0){
                            HidSkills  = HidFiledName;
                            HidSkillsVal  = HidFiledNameVal;
                        }else{
                            HidSkills = HidFiledName + sep + HidSkills;
                            HidSkillsVal = HidFiledNameVal + sep + HidSkillsVal;
                        }
                        
                         i++;
                    }
                    
                }
    });
    
    //alert(HidSkillsVal);
    
    var form = $(this);

    var data =   $(this).serialize() + '&HidSkills=' + HidSkills + '&HidSkillsVal=' + HidSkillsVal; 
    
    $('.ajax_auf_loading').show();
    
    elgg.action('pessek_profile/skills/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/skills'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-skillss-display').remove();
                    $('.gcconnex-skillss').append('<div class="gcconnex-profile-skillss-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
                $('.skills_readmore').moreLines({
                    linecount: 14,
                    baseclass: 'b-description',
                    basejsclass: 'js-description',
                    classspecific: '_readmore',    
                    buttontxtmore: showmore,               
                    buttontxtless: showless,
                    animationspeed: 200 
                });
        },
    });
    
    event.preventDefault();
    
});

$(document).on('submit', '.basic-form', function(event) {
   
   var un = '0';
   var annee = '1919';
   
   var startday_anniv = document.getElementById("startday_anniv").value;
   var startmonth_anniv = document.getElementById("startmonth_anniv").value;
   var startyear_anniv = document.getElementById("startyear_anniv").value;
   
   var startday_wedding = document.getElementById("startday_wedding").value;
   var startmonth_wedding = document.getElementById("startmonth_wedding").value;
   var startyear_wedding = document.getElementById("startyear_wedding").value;
   
   var timestamp_anniv = humanToTime_pessek(startday_anniv, startmonth_anniv, startyear_anniv);
   var timestamp_wedding = humanToTime_pessek(startday_wedding, startmonth_wedding, startyear_wedding);
   
   if(startday_anniv.localeCompare(un) == 0 || startmonth_anniv.localeCompare(un) == 0){timestamp_anniv = 0;}
   if(startday_wedding.localeCompare(un) == 0 || startmonth_wedding.localeCompare(un) == 0){timestamp_wedding = 0;}
   
   var form = $(this);
   var data = form.serialize();
  
   $('.ajax_auf_loading').show();
   
    elgg.action('pessek_profile/basic/add', {
        data: data + "&timestamp_anniv="+timestamp_anniv+"&timestamp_wedding="+timestamp_wedding,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/profile/details'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-languagess-display').remove();
                    $('.gcconnex-languagess').append('<div class="gcconnex-profile-languagess-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();location.reload();
        },
    });
    
    event.preventDefault();
  
});

$(document).on('submit', '.description-form', function(event) {
    
    var form = $(this);
    var data = form.serialize();
    
    $('.ajax_auf_loading').show();
    
    elgg.action('pessek_profile/description/add', {
        data: data,
        beforeSend: function () {
           form.find('[type="submit"]').addClass('elgg-state-disabled');
        },
        success: function(json) {
            
                form.find('[type="submit"]').removeClass('elgg-state-disabled');
                $.colorbox.close();
                
                $.get(elgg.normalize_url('ajax/view/pessek_profile/about-me'),{
                    guid: elgg.get_page_owner_guid()
                },
                function (data) {
                    $('.gcconnex-profile-description-display').remove();
                    $('.gcconnex-description').append('<div class="gcconnex-profile-description-display">' + data + '</div>');
                });
                
                $('.ajax_auf_loading').hide();
        },
    });
    
    event.preventDefault();
    
});

function stripLeadingZeroes_pessek(input) {
		if((input.length > 1) && (input.substr(0, 1) == "0")) {
			return input.substr(1);
		} else {
			return input;
		}
}

function humanToTime_pessek(Day, Month, Year) {

		var humDate = new Date(Date.UTC(Year,
			(stripLeadingZeroes_pessek(Month) - 1),
			stripLeadingZeroes_pessek(Day),
			stripLeadingZeroes_pessek(0),
			stripLeadingZeroes_pessek(0),
			stripLeadingZeroes_pessek(0)));

		return (humDate.getTime() / 1000.0);
}



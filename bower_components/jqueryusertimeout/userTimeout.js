define(function(require) {
    
    var elgg = require("elgg");
    
    $(document).userTimeout({

    // ULR to redirect to, to log user out
    
    logouturl: elgg.get_site_url() + 'action/logout?__elgg_ts='+elgg.security.token.__elgg_ts+'&__elgg_token='+elgg.security.token.__elgg_token,              

    // URL Referer - false, auto or a passed URL     
    referer: false,            

    // Name of the passed referal in the URL
    refererName: 'refer',        

    // Toggle for notification of session ending
    notify: true,                      

    // Toggle for enabling the countdown timer
    timer: true,             

    // 10 Minutes in Milliseconds, then notification of logout // 1,800,000 --> 30 Minutes
    session: 900000,                   

    // 5 Minutes in Milliseconds, then logout
    force: 120000,       

    // Model Dialog selector (auto, bootstrap, jqueryui)              
    ui: 'auto',                        

    // Shows alerts
    debug: false,            

    // <a href="https://www.jqueryscript.net/tags.php?/Modal/">Modal</a> Title
    modalTitle: elgg.echo('pessek_profile:in:modalTitle'),     

    // Modal Body
    modalBody: elgg.echo('pessek_profile:in:modalBody'),

    // Modal log off button text
    modalLogOffBtn: elgg.echo('pessek_profile:in:modalTitle'),  

    // Modal stay logged in button text        
    modalStayLoggedBtn: elgg.echo('pessek_profile:in:modalStayLoggedBtn') 
        
    });
});
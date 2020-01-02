/*$(window).on('load', function () {
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
    alert('123456789');
});*/
$(function() {
    $(window).on("load", function() {
        alert("this will not be triggered");
    });
});
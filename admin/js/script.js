jQuery(document).ready(function($) {
    // Tab navigation
    $('.nav-tab-wrapper a').on('click', function(e) {
        e.preventDefault();
        var tab = $(this).attr('href');
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.tab-content').hide();
        $(tab).show();
    });

    // Show log details in modal
    $('.view-details').on('click', function() {
        var response = $(this).data('response');
        $('#log-details-content').text(JSON.stringify(response, null, 2));
        $('#log-details-modal').show();
    });

    // Close modal
    $('.close-modal').on('click', function() {
        $('#log-details-modal').hide();
    });

    // Close modal on click outside
    $(document).on('click', function(e) {
        if ($(e.target).is('#log-details-modal')) {
            $('#log-details-modal').hide();
        }
    });
});
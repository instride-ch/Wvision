export default function PageLoader() {

    $(window).on('load', function() {
        $('.page-loader').fadeOut('slow', function() {
            $(this).remove();
        });
    });

}
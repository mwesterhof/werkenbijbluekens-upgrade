$(window).scroll(() => {
    //Distance from top fo document to top of footer
    topOfFooter = $('footer').position().top;
    // Distance user has scrolled from top + windows inner height
    scrollDistanceFromTopOfDoc = $(document).scrollTop() + window.innerHeight;
    // Difference between the two.
    scrollDistanceFromTopOfFooter = scrollDistanceFromTopOfDoc - topOfFooter;
    // If user has scrolled further than footer,
    if (scrollDistanceFromTopOfDoc > topOfFooter) {
        // add margin-bottom so button stays above footer.
        $('.floating-buttons').css('margin-bottom',  0 + scrollDistanceFromTopOfFooter);
    } else  {
        // remove margin-bottom so button goes back to the bottom of the page
        $('.floating-buttons').css('margin-bottom', 0);
    }
});
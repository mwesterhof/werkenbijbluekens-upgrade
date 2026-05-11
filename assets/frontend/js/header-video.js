var iframe = document.getElementById('video');

if (iframe != null) {
// $f == Froogaloop
    var player = $f(iframe);

// bind events
    var playButton = document.getElementById("play-button");
    var videoTitle = document.getElementById("video-title");

    playButton.addEventListener("click", function () {
        player.api("play");
        $(this).hide();
        $(videoTitle).hide();
    });
}
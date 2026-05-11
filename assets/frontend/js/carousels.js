import Glide, { Breakpoints, Images, Swipe, Controls } from '@glidejs/glide/dist/glide.modular.esm';

// carousel crashes the Sulu preview :-/
function notInIframe () {
    try {
        return window.self == window.top;
    } catch (e) {
        return true;
    }
}

$(function () {

    if ( $('.inline_stories_glide .item').length && notInIframe()  ) {
        var inline_stories_glide = new Glide('.inline_stories_glide',{
            type: 'carousel',
            perView: 1,
            animationDuration: 600
        }).mount({Breakpoints, Images, Swipe, Controls});

        inline_stories_glide.on('move', function () {
            // Logic fired after mounting
            $('.inline_stories_glide').find('.current_slide').html(inline_stories_glide.index+1);
          })
    }

    if ( $('.functiongroup_glide .item').length && notInIframe()  ) {
        var functiongroup_glide = new Glide('.functiongroup_glide',{
            type: 'carousel',
            perView: 1,
            animationDuration: 600,
            peek: {
                before: 0,
                after: 400
            },
            breakpoints: {
                769: {
                    perView: 1 ,
                    peek: {
                        before: 0,
                        after: 60
                    },
                },
                992: {
                    perView: 1 ,
                    peek: {
                        before: 0,
                        after: 100
                    },
                },
                1200: {
                    perView: 1 ,
                    peek: {
                        before: 0,
                        after: 120
                    },
                },
                1400: {
                    perView: 1 ,
                    peek: {
                        before: 0,
                        after: 160
                    },
                },
                1700: {
                    perView: 1 ,
                    peek: {
                        before: 0,
                        after: 200
                    },
                },
            }
        }).mount({Breakpoints, Images, Swipe, Controls});

        functiongroup_glide.on('move', function () {
            // Logic fired after mounting
            $('.functiongroup_glide').find('.current_slide').html(functiongroup_glide.index+1);
          })
    }
    
    
});
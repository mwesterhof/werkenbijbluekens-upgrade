require('mmenu-js/dist/mmenu.js');
require('mmenu-js/dist/mmenu.polyfills.js');
require('mmenu-js/dist/mmenu.css');

document.addEventListener(
    "DOMContentLoaded", () => {
        new Mmenu( "#navbarNavMain", {
            extensions: [
                "pagedim-black",
                "position-front"
             ],
            navbars     : [{
                height  : 3,
                content : [
                    '<a href="' + window.location.origin + '" class="d-block text-left pt-2 px-2"><img src="/build/frontend/images/logo-werkenbij-bluekens-wit.svg" alt="Werken bij Bluekens" class="img-fluid"></a>'
                ]
            }, true],
            wrappers: ["bootstrap"]
            // options
        }, {
            // configuration
            offCanvas: {
                page: {
                    selector: "#page"
                }
            }
        });
    }
);

$(function () {

    $('.mm-wrapper__blocker a').addClass('close');
});

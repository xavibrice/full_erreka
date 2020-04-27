/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import 'bootstrap/dist/js/bootstrap.min';
import 'admin-lte/dist/js/adminlte.min';
import Sortable from 'sortablejs';

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
//import $ from 'jquery';
const $ = require('jquery');

$(function () {
    let el = document.getElementById('photos');

    if (el !== null ) {
        let sortable = new Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: () => {
                let url = $('#photos').attr('data-url');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: JSON.stringify(sortable.toArray())
                })
            }
        });
    }
});

// $(document).on('click', '[data-toggle="lightbox"]', function(event) {
//     event.preventDefault();
//     $(this).ekkoLightbox();
// });

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

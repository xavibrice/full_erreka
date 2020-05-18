/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import 'bootstrap/dist/js/bootstrap.min';
import 'admin-lte/dist/js/adminlte.min';
import Sortable from 'sortablejs';
import moment from "moment";
import 'eonasdan-bootstrap-datetimepicker-bootstrap4beta';

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
//import $ from 'jquery';
const $ = require('jquery');
const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

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

$("#property_zone").change(function() {
    const data = {
        zone_id: $(this).val()
    };

    $.ajax({
        type: 'post',
        url: Routing.generate('street_by_zone'),
        data: data,
        success: function(data) {
            const $street_selector = $('#property_street');
            $street_selector.html('<option>Selecciona calle</option>');
            for (let i = 0, total = data.length; i < total; i++) {
                $street_selector.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
        }
    });
});
// $(document).on('click', '[data-toggle="lightbox"]', function(event) {
//     event.preventDefault();
//     $(this).ekkoLightbox();
// });

$.extend(true, $.fn.datetimepicker.defaults, {
    icons: {
        time: 'far fa-clock',
        date: 'far fa-calendar',
        up: 'fas fa-arrow-up',
        down: 'fas fa-arrow-down',
        previous: 'fas fa-chevron-left',
        next: 'fas fa-chevron-right',
        today: 'fas fa-calendar-check',
        clear: 'far fa-trash-alt',
        close: 'far fa-times-circle'
    }
});

$(document).ready(function () {
    $('.js-datepicker').datetimepicker({
        format: moment().format('L'),
        locale: 'es',
    });

    $(".js-datepicker-empty").datetimepicker({
        format: moment().format('L'),
        locale: 'es',
    });
});
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

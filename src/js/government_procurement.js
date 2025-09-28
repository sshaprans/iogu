import './main';

$(document).ready(function() {

    var _zk_uaprom = _zk_uaprom || [];
    _zk_uaprom.push( ['APP_ID', 'e2465797-3574-4696-bc67-fa6d90acaec5' ],['CONTAINER_ID', 'prom_state_purchase' ],['LOCALE', 'uk' ],['ENTITYTYPE', 0 ],['SRN', ['25835792']] );

    (function(d, tag, id) {
        if (d.getElementById(id)) return;
        var pjs = d.createElement(tag);
        pjs.id = id;
        pjs.type = 'text/javascript';
        pjs.src = 'https://zk-cabinet.s3.zakupivli.pro/js/build/zakupki_widget_init_v2/zakupki_widget_init_v2_wp.fd745.js';

        pjs.async = true;
        var sc = d.getElementsByTagName(tag)[0];
        sc.parentNode.insertBefore(pjs, sc);
    })(document, 'script', 'zakupki_uaprom_id');

});


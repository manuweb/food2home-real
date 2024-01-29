<?php
/*
 *
 * Archivo: enviaemailcss.php
 *
 * Version: 1.0.0
 * Fecha  : 26/11/2022
 * 
 * CSS para email
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
$cssmail=''.
'<style>
    body {margin:0;padding:10px}

    h2 {font-size:36px;margin:0 0 10px 0}
    p {margin:0 0 10px 0}

    table.width200,table.rwd_auto {border:1px solid #ccc;width:100%;margin:0 0 50px 0}
        .width200 th,.rwd_auto th {background:#FF5F00;color:#fff;padding:5px;}
        .width200 td,.rwd_auto td {border-bottom:1px solid #ccc;padding:5px;}
        .width200 tr:last-child td, .rwd_auto tr:last-child td{border:0}

    .rwd {width:100%;overflow:auto;}
        .rwd table.rwd_auto {width:auto;min-width:100%}
            .rwd_auto th,.rwd_auto td {white-space: nowrap;}

    @media only screen and (max-width: 760px), (min-width: 768px) and (max-width: 1024px)  
    {

        table.width200, .width200 thead, .width200 tbody, .width200 th, .width200 td, .width200 tr { display: block; }

        .width200 thead tr { position: absolute;top: -9999px;left: -9999px; }

        .width200 tr { border: 1px solid #ccc; }

        .width200 td { border: none;border-bottom: 1px solid #ccc; position: relative;padding-left: 50%;text-align:left }

        .width200 td:before {  position: absolute; top: 6px; left: 6px; width: 45%; padding-right: 10px; white-space: nowrap;}


        .descarto {display:none;}
        .fontsize {font-size:10px}
    }

    /* Smartphones (portrait and landscape) ----------- */
    @media only screen and (min-width : 320px) and (max-width : 480px) 
    {
        body { width: 320px; }
        .descarto {display:none;}
    }

    /* iPads (portrait and landscape) ----------- */
    @media only screen and (min-width: 768px) and (max-width: 1024px) 
    {
        body { width: 495px; }
        .descarto {display:none;}
        .fontsize {font-size:10px}
    }

</style>';

?>

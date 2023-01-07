td p{margin: 0;font-size: 7pt;padding-left: 5px;padding-top:1px}
td{padding: 5px;}
td b{font-weight: bold;}
hr {display: block;margin-top: 20px;margin-bottom: 20px;margin-left: auto;margin-right: auto;border-style: dashed;border-width: 1px;height: 0;border-bottom: dashed #0a0a0a .1em;}

@media print {
* {background:transparent !important;color:#000 !important;text-shadow:none !important;filter:none !important;-ms-filter:none !important;}

body {
    margin:0;padding:0;
    overflow-y:hidden;
    color: #000;
}
@page {margin: 1cm;}
}
img.logo-correio{max-width: 80px;vertical-align: middle;float: left;}
.titulo-correio{font-weight: bold;display: inline-block;vertical-align: middle;margin:0}
#tableServico tr td{padding: 0;}

.verticalTableHeader {text-align:center;white-space:nowrap;transform: rotate(90deg);border-style: dotted; width:0.4cm;padding: 0;}
.verticalTableHeader p {margin:0;display:inline-block;width: 0.5cm;font-size: 10px;}
.verticalTableHeader p:before{content:'';width:0;padding-top:110%;/* takes width as reference, + 10% for faking some extra padding */display:inline-block;vertical-align:middle;}
p.assinatura{font-size:5pt;text-transform: uppercase;}
.vazioTableHeader {white-space:nowrap; width:0.1cm;padding:0;}

span.box-numero{border: 1px solid black;padding: 0 1px;}
#imgQRCode{float: right;position: relative;top: -2px; width:115px; margin-right: 2px;}

.table-ar, .td-ar {
    border: 1px solid black;
    border-collapse: collapse;
    padding: unset;
}

.texto-alinhado-acima {
    text-align: left;
    border: 1px solid black;
    padding: 0px;height: 20px;
    vertical-align: top;
}

.qr-code {
    margin-top: 1px;
}

.label-campo{
    style="text-align: left;
    border: 1px solid black;
    padding: 0px;
    height: 20px;
    vertical-align: top;"
}

.mg-tp-7{
    margin-top: 7px;
}

.pd-0{
    padding: 0px;
}

td{
    pading:unset !important;
}
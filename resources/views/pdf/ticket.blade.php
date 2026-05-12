<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    font-size: 9px;
    color: #000;
    background: #fff;
    width: 100%;
}

/* ── Página ticketera 80mm ancho ── */
@page {
    size: 80mm 200mm;
    margin: 4mm 5mm;
}

.page { width: 100%; background: #fff; }

/* ── Bordes ── */
.borde-top    { border-top: 3px solid #000; margin-bottom: 5px; }
.borde-medium { border-top: 1.5px solid #000; margin: 4px 0; }
.borde-dash   { border-top: 1px dashed #000; margin: 4px 0; }
.borde-bottom { border-top: 3px double #000; margin-top: 5px; }

/* ── Encabezado ── */
.header { text-align: center; padding: 3px 0; }
.logo-img {
    width: 48px;
    height: 48px;
    object-fit: contain;
    display: block;
    margin: 0 auto 3px;
}
.logo-fallback {
    width: 48px; height: 48px;
    border: 2px solid #000;
    border-radius: 4px;
    display: block;
    line-height: 44px;
    font-weight: 900;
    font-size: 22px;
    text-align: center;
    margin: 0 auto 3px;
}
.inst-nombre {
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.inst-sub {
    font-size: 7px;
    color: #333;
    margin-top: 1px;
}

/* ── Título del comprobante ── */
.comp-titulo {
    text-align: center;
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    padding: 4px 0 2px;
}
.comp-num {
    text-align: center;
    font-size: 7.5px;
    color: #333;
    margin-bottom: 2px;
}

/* ── Datos en dos columnas ── */
.fila {
    display: table;
    width: 100%;
    padding: 2px 0;
}
.fila-lbl {
    display: table-cell;
    color: #444;
    font-size: 8px;
    width: 45%;
    vertical-align: top;
}
.fila-val {
    display: table-cell;
    font-weight: bold;
    font-size: 8px;
    vertical-align: top;
    text-align: right;
}

/* ── Sección de título ── */
.sec-titulo {
    font-size: 7.5px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 2px 0 1px;
    color: #000;
}

/* ── Alumno destacado ── */
.alumno-box {
    border: 1px solid #000;
    padding: 4px 6px;
    margin: 3px 0;
}
.alumno-nombre {
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
}
.alumno-sub {
    font-size: 7.5px;
    color: #333;
    margin-top: 1px;
}

/* ── Detalle de pagos ── */
.detalle-header {
    display: table;
    width: 100%;
    border-bottom: 1px solid #000;
    padding-bottom: 2px;
    margin-bottom: 2px;
}
.dh-desc  { display: table-cell; font-size: 7px; font-weight: 900; text-transform: uppercase; width: 62%; }
.dh-monto { display: table-cell; font-size: 7px; font-weight: 900; text-transform: uppercase; text-align: right; }

.detalle-fila {
    display: table;
    width: 100%;
    padding: 2px 0;
    border-bottom: 1px dotted #bbb;
}
.df-desc  { display: table-cell; font-size: 8px; width: 62%; vertical-align: middle; }
.df-monto { display: table-cell; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle; }

.total-box {
    display: table;
    width: 100%;
    border-top: 2px solid #000;
    padding-top: 3px;
    margin-top: 2px;
}
.total-lbl { display: table-cell; font-size: 9px; font-weight: 900; text-transform: uppercase; }
.total-val { display: table-cell; font-size: 11px; font-weight: 900; text-align: right; }

/* ── Estado de pago ── */
.estado-box {
    text-align: center;
    border: 2px solid #000;
    padding: 3px 6px;
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 4px 0;
    display: block;
    width: 100%;
}
.estado-pagado   { border-style: double; }
.estado-pendiente{ border-style: dashed; }

/* ── QR ── */
.qr-box { text-align: center; margin: 5px 0; }
.qr-box img { width: 55px; height: 55px; }
.qr-label { font-size: 6.5px; color: #555; margin-top: 2px; }

/* ── Firma ── */
.firma-box { margin-top: 8px; text-align: center; }
.firma-linea { border-top: 1px solid #000; width: 100px; margin: 0 auto 3px; }
.firma-txt { font-size: 7.5px; }

/* ── Footer ── */
.footer { margin-top: 5px; text-align: center; font-size: 6.5px; color: #555; }
</style>
</head>
<body>
<div class="page">

@php
    $mat        = $alumno->matriculas->sortByDesc('periodo')->first();
    $pago       = $mat?->pagoMatricula;
    $nroTicket  = 'TKT-' . date('Y') . '-' . str_pad($alumno->id, 5, '0', STR_PAD_LEFT);
    $totalAnual = $pago ? ($pago->pension_mensual * ($pago->numero_pensiones ?? 10)) : 0;
    $descuento  = ($alumno->tipo_descuento !== 'ninguno') ? $alumno->monto_descuento : 0;
    $totalPagar = $pago ? ($pago->monto_matricula + $totalAnual - $descuento) : 0;
    $estadoPago = strtolower($pago?->estado_pago ?? 'pendiente');

    // Logo — dompdf no ejecuta JS, verificamos la existencia con PHP
    $logoPath = public_path('images/logo.png');
    $logoSrc  = file_exists($logoPath)
        ? 'file:///' . str_replace('\\', '/', $logoPath)
        : null;
@endphp

<div class="borde-top"></div>

{{-- ENCABEZADO CON LOGO --}}
<div class="header">
    @if($logoSrc)
        <img src="{{ $logoSrc }}" class="logo-img" alt="Logo">
    @else
        <div class="logo-fallback">J</div>
    @endif
    <div class="inst-nombre">Colegio Pre JEDSON</div>
    <div class="inst-sub">Institución Educativa Privada — Arequipa, Perú</div>
    <div class="inst-sub">{{ $alumno->sede?->nombre ?? 'Sede Principal' }}</div>
</div>

<div class="borde-medium"></div>

{{-- TÍTULO COMPROBANTE --}}
<div class="comp-titulo">Comprobante de Matrícula</div>
<div class="comp-num">
    N° {{ $nroTicket }} &nbsp;|&nbsp; Período: {{ $mat?->periodo ?? date('Y') }}
</div>
<div class="comp-num">Emitido: {{ $fechaEmision }}</div>

<div class="borde-dash"></div>

{{-- DATOS DEL ALUMNO --}}
<div class="alumno-box">
    <div class="alumno-nombre">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</div>
    <div class="alumno-sub">
        DNI: <strong>{{ $alumno->dni }}</strong>
        &nbsp;&nbsp;{{ $alumno->nivel_academico ?? '' }}
        @if($alumno->grado_seccion) — {{ $alumno->grado_seccion }} @endif
    </div>
    @if($alumno->apoderado)
    <div class="alumno-sub" style="margin-top:2px;">
        Apoderado: <strong>{{ $alumno->apoderado->nombre_completo }}</strong>
        @if($alumno->apoderado->telefono)
            &nbsp;| Tel: {{ $alumno->apoderado->telefono }}
        @endif
    </div>
    @endif
</div>

{{-- DATOS DE MATRÍCULA --}}
@if($mat)
<div class="sec-titulo">Datos de Matrícula</div>
<div class="fila">
    <span class="fila-lbl">Código:</span>
    <span class="fila-val">{{ $mat->codigo_matricula }}</span>
</div>
<div class="fila">
    <span class="fila-lbl">Nivel / Grado:</span>
    <span class="fila-val">{{ $mat->nivel_academico }} — {{ $mat->grado_seccion }}</span>
</div>
<div class="fila">
    <span class="fila-lbl">Situación:</span>
    <span class="fila-val">{{ $mat->situacion }}</span>
</div>
<div class="fila">
    <span class="fila-lbl">Modalidad pago:</span>
    <span class="fila-val">{{ ucfirst($mat->modalidad_pago) }}</span>
</div>
@endif

{{-- DETALLE DE PAGO --}}
@if($pago)
<div class="borde-dash"></div>
<div class="sec-titulo">Detalle del Pago</div>

<div class="detalle-header">
    <span class="dh-desc">Concepto</span>
    <span class="dh-monto">Monto</span>
</div>

<div class="detalle-fila">
    <span class="df-desc">Matrícula {{ $mat->periodo }}</span>
    <span class="df-monto">S/ {{ number_format($pago->monto_matricula, 2) }}</span>
</div>
<div class="detalle-fila">
    <span class="df-desc">Pensión x {{ $pago->numero_pensiones ?? 10 }} meses</span>
    <span class="df-monto">S/ {{ number_format($pago->pension_mensual, 2) }} c/u</span>
</div>
<div class="detalle-fila" style="border-bottom:none;">
    <span class="df-desc">Subtotal pensiones</span>
    <span class="df-monto">S/ {{ number_format($totalAnual, 2) }}</span>
</div>

@if($descuento > 0)
<div class="borde-dash"></div>
<div class="detalle-fila" style="border-bottom:none;">
    <span class="df-desc">Descuento ({{ ucfirst($alumno->tipo_descuento) }})</span>
    <span class="df-monto">- S/ {{ number_format($descuento, 2) }}</span>
</div>
@endif

<div class="total-box">
    <span class="total-lbl">Total a pagar</span>
    <span class="total-val">S/ {{ number_format($totalPagar, 2) }}</span>
</div>

<div class="borde-dash"></div>

<div class="estado-box estado-{{ $estadoPago }}">
    Estado: {{ strtoupper($pago->estado_pago) }}
</div>
@endif

{{-- QR --}}
@php
    $qrAbsPath = $alumno->qr_code_path
        ? storage_path('app/public/' . $alumno->qr_code_path)
        : null;
    $qrSrc = ($qrAbsPath && file_exists($qrAbsPath))
        ? 'file:///' . str_replace('\\', '/', $qrAbsPath)
        : null;
@endphp
@if($qrSrc)
<div class="borde-dash"></div>
<div class="qr-box">
    <img src="{{ $qrSrc }}" alt="QR">
    <div class="qr-label">{{ $alumno->dni }} — Colegio Pre JEDSON</div>
</div>
@endif

{{-- FIRMA --}}
<div class="borde-dash"></div>
<div class="firma-box">
    <div style="height: 25px;"></div>
    <div class="firma-linea"></div>
    <div class="firma-txt"><strong>Director(a) / Administración</strong></div>
    <div class="firma-txt">Colegio Pre JEDSON — Arequipa</div>
</div>

<div class="borde-bottom"></div>

{{-- FOOTER --}}
<div class="footer">
    <p>Comprobante de matrícula para el período académico indicado.</p>
    <p>{{ $nroTicket }} &bull; {{ $fechaEmision }}</p>
</div>

</div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 11px;
    color: #1e293b;
    background: white;
}

.page {
    width: 100%;
    background: white;
    overflow: hidden;
}
.stripe-top { height: 12px; background: #0b3d91; }
.content { padding: 50px; }

/* Logo & Header */
.logo { text-align: center; margin-bottom: 10px; }
.logo-box {
    display: inline-block;
    width: 70px;
    height: 70px;
    background: #0b3d91;
    border-radius: 12px;
    color: white;
    font-size: 28px;
    font-weight: bold;
    text-align: center;
    line-height: 70px;
}
.inst-name {
    text-align: center;
    font-size: 22px;
    font-weight: 900;
    color: #0b3d91;
}
.inst-sub {
    text-align: center;
    font-size: 11px;
    color: #555;
    margin-bottom: 5px;
}

/* Divider */
.divider {
    height: 3px;
    background: linear-gradient(90deg, #0b3d91 0%, #3b82f6 50%, #0b3d91 100%);
    margin: 15px 0;
    border-radius: 2px;
}

/* Date & Title */
.fecha {
    text-align: right;
    font-size: 11px;
    color: #555;
    margin-bottom: 25px;
}
.titulo {
    text-align: center;
    font-size: 24px;
    font-weight: 900;
    color: #0b3d91;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 25px;
}

/* Document body */
.doc {
    font-size: 13px;
    line-height: 1.9;
    text-align: justify;
    color: #334155;
}
.dest {
    font-weight: 700;
    color: #0b3d91;
    text-transform: uppercase;
}

/* Data table */
table.data-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
table.data-table th {
    background: #0b3d91;
    color: white;
    padding: 10px 12px;
    font-size: 11px;
    text-align: left;
}
table.data-table td {
    padding: 9px 12px;
    border-bottom: 1px solid #eee;
    font-size: 12px;
}
table.data-table td.label { color: #555; width: 35%; }
table.data-table td.value { font-weight: 700; color: #1e293b; }
table.data-table tr:last-child td { border-bottom: none; }

/* Matriculas history table */
table.mat-table { width: 100%; border-collapse: collapse; margin: 14px 0; }
table.mat-table th, table.mat-table td {
    border: 1px solid #e2e8f0;
    padding: 7px 10px;
    font-size: 10.5px;
    text-align: left;
}
table.mat-table th { background: #0b3d91; color: white; font-weight: bold; }
table.mat-table tr:nth-child(even) td { background: #f8fafc; }

/* Final text */
.final {
    margin-top: 18px;
    font-size: 13px;
    line-height: 1.8;
    color: #334155;
}

/* Firma */
.firma-box {
    margin-top: 50px;
    text-align: center;
}
.firma-inner {
    display: inline-block;
    width: 220px;
    text-align: center;
}
.firma-space { height: 80px; }
.firma-linea {
    border-top: 2px solid #0b3d91;
    margin-bottom: 8px;
}
.firma-inner strong { font-size: 13px; color: #0b3d91; display: block; }
.firma-inner p { font-size: 11px; color: #555; }

/* Footer */
.footer {
    margin-top: 30px;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
    display: table;
    width: 100%;
}
.footer-left {
    display: table-cell;
    font-size: 8px;
    color: #94a3b8;
    vertical-align: middle;
}
.footer-right {
    display: table-cell;
    text-align: right;
    font-size: 8px;
    color: #94a3b8;
    vertical-align: middle;
}

@page { size: A4; margin: 15mm; }
</style>
</head>
<body>
<div class="page">

    <div class="stripe-top"></div>

    <div class="content">

        <!-- Logo -->
        <div class="logo">
            <div class="logo-box">J</div>
        </div>
        <div class="inst-name">Colegio Pre JEDSON</div>
        <div class="inst-sub">Institución Educativa Privada &mdash; {{ $alumno->sede?->nombre ?? 'Sede Principal' }}</div>

        <div class="divider"></div>

        @php
            $meses = ['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            $fecha_hoy = now()->day . ' de ' . $meses[now()->month] . ' de ' . now()->year;
        @endphp

        <div class="fecha">Arequipa, {{ $fecha_hoy }}</div>

        <div class="titulo">Constancia de Matrícula {{ date('Y') }}</div>

        <!-- Cuerpo del documento -->
        <div class="doc">
            Se deja constancia que el(la) estudiante
            <span class="dest">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</span>,
            identificado(a) con DNI N°
            <span class="dest">{{ $alumno->dni }}</span>,
            se encuentra debidamente matriculado(a) en el nivel
            <span class="dest">{{ $alumno->nivel_academico ?? '—' }}</span>,
            grado <span class="dest">{{ $alumno->grado_seccion ?? '—' }}</span>,
            correspondiente al periodo académico
            <span class="dest">{{ date('Y') }}</span>.
        </div>

        <!-- Datos de matrícula -->
        <table class="data-table">
            <tr><th colspan="2">Datos de Matrícula</th></tr>
            <tr>
                <td class="label">Alumno / Alumna</td>
                <td class="value">{{ strtoupper($alumno->apellidos . ', ' . $alumno->nombres) }}</td>
            </tr>
            <tr>
                <td class="label">DNI</td>
                <td class="value">{{ $alumno->dni }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Nacimiento</td>
                <td class="value">{{ $alumno->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Sexo</td>
                <td class="value">{{ $alumno->sexo ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Nivel</td>
                <td class="value">{{ $alumno->nivel_academico ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Grado / Sección</td>
                <td class="value">{{ $alumno->grado_seccion ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Sede</td>
                <td class="value">{{ $alumno->sede?->nombre ?? '—' }}</td>
            </tr>
            @if($alumno->apoderado)
                <tr>
                    <td class="label">Apoderado / Apoderada</td>
                    <td class="value">{{ strtoupper($alumno->apoderado->nombre_completo) }}
                        {{ $alumno->apoderado->parentesco ? '(' . $alumno->apoderado->parentesco . ')' : '' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Teléfono del Apoderado</td>
                    <td class="value">{{ $alumno->apoderado->telefono ?? '—' }}</td>
                </tr>
            @endif
            <tr>
                <td class="label">Fecha de emisión</td>
                <td class="value">{{ $fecha_hoy }}</td>
            </tr>
        </table>

        <!-- Historial de Matrículas -->
        @if($alumno->matriculas->isNotEmpty())
            <div style="margin-top: 20px;">
                <table class="mat-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Período</th>
                            <th>Nivel</th>
                            <th>Grado / Sección</th>
                            <th>Situación</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumno->matriculas->sortByDesc('periodo') as $mat)
                            <tr>
                                <td>{{ $mat->codigo_matricula }}</td>
                                <td>{{ $mat->periodo }}</td>
                                <td>{{ $mat->nivel_academico }}</td>
                                <td>{{ $mat->grado_seccion }}</td>
                                <td>{{ $mat->situacion }}</td>
                                <td>{{ ucfirst($mat->estado) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <p class="final">
            Se expide la presente constancia a solicitud del interesado para los fines que estime conveniente.
        </p>

        <!-- Firma -->
        <div class="firma-box">
            <div class="firma-inner">
                <div class="firma-space"></div>
                <div class="firma-linea"></div>
                <strong>Director(a)</strong>
                <p>Colegio Pre JEDSON</p>
                <p>Arequipa</p>
            </div>
        </div>

    </div><!-- /content -->

    <!-- Footer -->
    <div class="footer" style="padding: 0 50px 20px;">
        <div class="footer-left">
            Colegio Pre JEDSON &bull; Institución Educativa Privada &bull; Arequipa, Perú<br>
            Este documento tiene validez oficial con sello y firma.
        </div>
        <div class="footer-right">
            Constancia N° {{ str_pad($alumno->id, 6, '0', STR_PAD_LEFT) }}-{{ date('Y') }}<br>
            {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

</div>
</body>
</html>

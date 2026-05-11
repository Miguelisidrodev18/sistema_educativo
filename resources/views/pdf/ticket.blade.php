<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 10px;
    color: #1e293b;
    background: #fff;
}

/* Page */
.page {
    width: 100%;
    background: white;
    overflow: hidden;
}
.stripe-top { height: 10px; background: #0b3d91; }
.content { padding: 30px 25px; }

/* Logo & Header */
.logo { text-align: center; margin-bottom: 8px; }
.logo-box {
    display: inline-block;
    width: 55px;
    height: 55px;
    background: #0b3d91;
    border-radius: 10px;
    color: white;
    font-size: 22px;
    font-weight: bold;
    text-align: center;
    line-height: 55px;
}
.inst-name {
    text-align: center;
    font-size: 16px;
    font-weight: 900;
    color: #0b3d91;
}
.inst-sub {
    text-align: center;
    font-size: 9px;
    color: #555;
    margin-bottom: 6px;
}

/* Divider */
.divider {
    height: 3px;
    background: linear-gradient(90deg, #0b3d91 0%, #3b82f6 50%, #0b3d91 100%);
    margin: 10px 0;
    border-radius: 2px;
}

/* Title */
.titulo {
    text-align: center;
    font-size: 14px;
    font-weight: 900;
    color: #0b3d91;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin: 12px 0;
}
.titulo-sub {
    text-align: center;
    font-size: 9px;
    color: #64748b;
    margin-top: -6px;
    margin-bottom: 12px;
}

/* Alumno header section */
.alumno-header {
    display: table;
    width: 100%;
    margin-bottom: 12px;
    padding: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
}
.alumno-photo-cell {
    display: table-cell;
    width: 60px;
    vertical-align: middle;
}
.alumno-photo {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    border: 2px solid #0b3d91;
    object-fit: cover;
}
.alumno-photo-placeholder {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    border: 2px solid #0b3d91;
    background: #dbeafe;
    text-align: center;
    line-height: 55px;
    font-size: 18px;
    font-weight: bold;
    color: #1d4ed8;
}
.alumno-info-cell {
    display: table-cell;
    vertical-align: middle;
    padding-left: 12px;
}
.alumno-name { font-size: 13px; font-weight: bold; color: #0f172a; }
.alumno-dni  { font-size: 9px; color: #64748b; margin-top: 2px; }
.badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 8px;
    font-weight: bold;
    margin-top: 4px;
}
.badge-pink   { background: #fce7f3; color: #9d174d; }
.badge-blue   { background: #dbeafe; color: #1d4ed8; }
.badge-purple { background: #ede9fe; color: #5b21b6; }

/* Section titles */
.section-title {
    background: #0b3d91;
    color: white;
    padding: 5px 10px;
    font-size: 9px;
    font-weight: bold;
    border-radius: 4px;
    margin: 10px 0 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Data table */
table.data-table { width: 100%; border-collapse: collapse; }
table.data-table th { background: #0b3d91; color: white; padding: 6px 8px; font-size: 9px; text-align: left; }
table.data-table td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 9.5px; }
table.data-table td.label { color: #64748b; width: 35%; }
table.data-table td.value { font-weight: bold; color: #1e293b; }
table.data-table tr:last-child td { border-bottom: none; }

/* Pay rows */
.pay-section { margin: 8px 0; }
.pay-row {
    display: table;
    width: 100%;
    padding: 4px 0;
    border-bottom: 1px dotted #e2e8f0;
}
.pay-label {
    display: table-cell;
    color: #64748b;
    font-size: 9px;
    width: 60%;
}
.pay-value {
    display: table-cell;
    font-weight: bold;
    font-size: 9.5px;
    text-align: right;
}
.pay-total {
    display: table;
    width: 100%;
    padding: 6px 0;
    margin-top: 4px;
    border-top: 2px solid #0b3d91;
}
.pay-total .pay-label { font-weight: bold; color: #0b3d91; font-size: 10px; }
.pay-total .pay-value { color: #0b3d91; font-size: 11px; }

.estado-badge {
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 8px;
    font-weight: bold;
}
.estado-pagado   { background: #dcfce7; color: #166534; }
.estado-pendiente{ background: #fef9c3; color: #713f12; }
.estado-vencido  { background: #fee2e2; color: #991b1b; }
.estado-parcial  { background: #dbeafe; color: #1e40af; }

/* QR */
.qr-section {
    text-align: center;
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px dashed #cbd5e1;
}
.qr-section img { width: 65px; height: 65px; }
.qr-section p   { font-size: 7.5px; color: #94a3b8; margin-top: 3px; }

/* Firma */
.firma-box {
    margin-top: 30px;
    text-align: center;
}
.firma-linea {
    border-top: 2px solid #0b3d91;
    width: 180px;
    margin: 0 auto 6px;
}
.firma-box strong { font-size: 10px; color: #0b3d91; }
.firma-box p { font-size: 8.5px; color: #555; }

/* Footer */
.footer {
    margin-top: 14px;
    padding-top: 8px;
    border-top: 1px solid #e2e8f0;
    text-align: center;
    font-size: 7.5px;
    color: #94a3b8;
}

/* Print */
@page { margin: 10mm; }
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

        <div class="titulo">Ticket de Matrícula {{ date('Y') }}</div>
        <div class="titulo-sub">N° {{ str_pad($alumno->id, 6, '0', STR_PAD_LEFT) }}-{{ date('Y') }} &bull; Generado: {{ now()->format('d/m/Y H:i:s') }}</div>

        <!-- Alumno -->
        <div class="alumno-header">
            <div class="alumno-photo-cell">
                @if($alumno->foto_path && file_exists(storage_path('app/public/' . $alumno->foto_path)))
                    <img src="{{ storage_path('app/public/' . $alumno->foto_path) }}" class="alumno-photo" alt="">
                @else
                    <div class="alumno-photo-placeholder">
                        {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellidos, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="alumno-info-cell">
                <div class="alumno-name">{{ strtoupper($alumno->apellidos) }}, {{ strtoupper($alumno->nombres) }}</div>
                <div class="alumno-dni">DNI: {{ $alumno->dni }}</div>
                <div>
                    @if($alumno->nivel_academico)
                        <span class="badge {{ $alumno->nivel_academico === 'INICIAL' ? 'badge-pink' : ($alumno->nivel_academico === 'PRIMARIA' ? 'badge-blue' : 'badge-purple') }}">
                            {{ $alumno->nivel_academico }}
                        </span>
                    @endif
                    @if($alumno->grado_seccion)
                        <span class="badge badge-blue">{{ $alumno->grado_seccion }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Datos del Alumno -->
        <div class="section-title">&#128100; Datos del Alumno</div>
        <table class="data-table">
            <tr>
                <td class="label">Fecha de Nacimiento</td>
                <td class="value">{{ $alumno->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Sexo</td>
                <td class="value">{{ $alumno->sexo ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Ciudad</td>
                <td class="value">{{ $alumno->ciudad ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Sede</td>
                <td class="value">{{ $alumno->sede?->nombre ?? '—' }}</td>
            </tr>
            @if($alumno->apoderado)
                <tr>
                    <td class="label">Apoderado</td>
                    <td class="value">{{ $alumno->apoderado->nombre_completo }}</td>
                </tr>
                <tr>
                    <td class="label">Teléfono Apoderado</td>
                    <td class="value">{{ $alumno->apoderado->telefono ?? '—' }}</td>
                </tr>
            @endif
        </table>

        <!-- Última Matrícula -->
        @if($alumno->matriculas->isNotEmpty())
            @php $mat = $alumno->matriculas->sortByDesc('periodo')->first(); @endphp
            <div class="section-title">&#128196; Datos de Matrícula</div>
            <table class="data-table">
                <tr>
                    <td class="label">Código Matrícula</td>
                    <td class="value">{{ $mat->codigo_matricula }}</td>
                </tr>
                <tr>
                    <td class="label">Período Académico</td>
                    <td class="value">{{ $mat->periodo }}</td>
                </tr>
                <tr>
                    <td class="label">Nivel</td>
                    <td class="value">{{ $mat->nivel_academico ?? $alumno->nivel_academico }}</td>
                </tr>
                <tr>
                    <td class="label">Grado / Sección</td>
                    <td class="value">{{ $mat->grado_seccion ?? $alumno->grado_seccion }}</td>
                </tr>
                <tr>
                    <td class="label">Situación</td>
                    <td class="value">{{ $mat->situacion }}</td>
                </tr>
                <tr>
                    <td class="label">Modalidad de Pago</td>
                    <td class="value">{{ ucfirst($mat->modalidad_pago) }}</td>
                </tr>
            </table>

            @if($mat->pagoMatricula)
                <div class="section-title">&#128176; Detalle de Pago</div>
                <div class="pay-section">
                    <div class="pay-row">
                        <span class="pay-label">Monto de Matrícula:</span>
                        <span class="pay-value">S/ {{ number_format($mat->pagoMatricula->monto_matricula, 2) }}</span>
                    </div>
                    <div class="pay-row">
                        <span class="pay-label">Pensión Mensual:</span>
                        <span class="pay-value">S/ {{ number_format($mat->pagoMatricula->pension_mensual, 2) }}</span>
                    </div>
                    <div class="pay-row">
                        <span class="pay-label">N° de Pensiones:</span>
                        <span class="pay-value">{{ $mat->pagoMatricula->numero_pensiones }}</span>
                    </div>
                    @if($alumno->tipo_descuento !== 'ninguno')
                        <div class="pay-row">
                            <span class="pay-label">Descuento ({{ ucfirst($alumno->tipo_descuento) }}):</span>
                            <span class="pay-value" style="color:#166534">- S/ {{ number_format($alumno->monto_descuento, 2) }}</span>
                        </div>
                    @endif
                    <div class="pay-row" style="border-bottom:none">
                        <span class="pay-label">Estado de Pago:</span>
                        <span class="estado-badge estado-{{ strtolower($mat->pagoMatricula->estado_pago) }}">
                            {{ $mat->pagoMatricula->estado_pago }}
                        </span>
                    </div>
                </div>
            @endif
        @endif

        <!-- QR Code -->
        @if($alumno->qr_code_path && file_exists(storage_path('app/public/' . $alumno->qr_code_path)))
            <div class="qr-section">
                <img src="{{ storage_path('app/public/' . $alumno->qr_code_path) }}" alt="QR Code">
                <p>{{ $alumno->dni }} &bull; Colegio Pre JEDSON</p>
            </div>
        @endif

        <!-- Firma -->
        <div class="firma-box">
            <div style="height:50px;"></div>
            <div class="firma-linea"></div>
            <strong>Director(a)</strong>
            <p>Colegio Pre JEDSON</p>
            <p>Arequipa</p>
        </div>

    </div><!-- /content -->

    <!-- Footer -->
    <div class="footer" style="padding: 0 25px 15px;">
        <p>Colegio Pre JEDSON &bull; Institución Educativa Privada &bull; Arequipa, Perú</p>
        <p>Ticket generado el {{ now()->format('d/m/Y H:i:s') }} &mdash; Este documento tiene validez para trámites internos.</p>
    </div>

</div>
</body>
</html>

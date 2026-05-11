@extends('layouts.app')

@section('title', 'Lector QR')
@section('page-title', 'Lector QR - Asistencia')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-qrcode text-purple-700 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-slate-800 font-bold text-lg">Lector QR de Asistencia</h2>
                <p class="text-slate-500 text-sm">Escanee el código QR del alumno para registrar su asistencia</p>
            </div>
        </div>

        <!-- QR Scanner Area -->
        <div id="scanner-container"
             x-data="qrScanner()"
             x-init="init()"
             class="space-y-6">

            <!-- Video feed -->
            <div class="relative">
                <div id="video-container" class="bg-slate-900 rounded-xl overflow-hidden aspect-video flex items-center justify-center">
                    <div id="scanner-placeholder" class="text-center py-12">
                        <i class="fa-solid fa-camera text-slate-600 text-5xl mb-4"></i>
                        <p class="text-slate-400">Haga clic en "Iniciar Cámara" para comenzar</p>
                    </div>
                    <video id="qr-video" class="hidden w-full h-full object-cover"></video>
                    <canvas id="qr-canvas" class="hidden"></canvas>
                </div>

                <!-- Overlay border animation -->
                <div id="scan-overlay" class="hidden absolute inset-0 pointer-events-none">
                    <div class="absolute inset-8 border-2 border-white/30 rounded-xl">
                        <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-400 rounded-tl-lg"></div>
                        <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-400 rounded-tr-lg"></div>
                        <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-400 rounded-bl-lg"></div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-400 rounded-br-lg"></div>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex gap-3">
                <button @click="startCamera()" :disabled="scanning"
                        class="flex-1 bg-blue-700 hover:bg-blue-800 disabled:opacity-50 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-camera"></i>
                    <span x-text="scanning ? 'Escaneando...' : 'Iniciar Cámara'"></span>
                </button>
                <button @click="stopCamera()" x-show="scanning"
                        class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-stop"></i> Detener
                </button>
            </div>

            <!-- Result -->
            <div x-show="result" x-transition
                 class="rounded-xl p-4 flex items-center gap-4"
                 :class="success ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                     :class="success ? 'bg-green-100' : 'bg-red-100'">
                    <i :class="success ? 'fa-solid fa-check text-green-600' : 'fa-solid fa-xmark text-red-600'"></i>
                </div>
                <div>
                    <p class="font-semibold text-sm" :class="success ? 'text-green-800' : 'text-red-800'" x-text="result"></p>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="resultDetail"></p>
                </div>
            </div>

            <!-- Manual entry fallback -->
            <div class="border-t border-slate-200 pt-5">
                <p class="text-slate-500 text-sm mb-3 font-medium">O ingrese el ID del alumno manualmente:</p>
                <form @submit.prevent="manualRegister" class="flex gap-3">
                    <input type="number" x-model="manualId" placeholder="ID del alumno"
                           class="flex-1 border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                            class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition">
                        <i class="fa-solid fa-arrow-right"></i> Registrar
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Recent scans -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-slate-800 font-semibold text-base mb-4 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-blue-700"></i> Registros Recientes (Hoy)
        </h3>
        @php
            $recientes = \App\Models\Asistencia::with('alumno')
                ->whereDate('fecha', today())
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        @endphp

        @if($recientes->isNotEmpty())
            <div class="space-y-2">
                @foreach($recientes as $reg)
                    <div class="flex items-center justify-between py-2 px-3 bg-slate-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-700 text-xs font-bold">{{ substr($reg->alumno->nombres, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-slate-700 text-sm font-medium">{{ $reg->alumno->apellidos }}, {{ $reg->alumno->nombres }}</p>
                                <p class="text-slate-400 text-xs">{{ $reg->hora_registro }}</p>
                            </div>
                        </div>
                        @php
                            $eClass = match($reg->estado) {
                                'PRESENTE'    => 'bg-green-100 text-green-700',
                                'TARDANZA'    => 'bg-yellow-100 text-yellow-700',
                                'AUSENTE'     => 'bg-red-100 text-red-700',
                                'JUSTIFICADO' => 'bg-blue-100 text-blue-700',
                                default       => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $eClass }}">{{ $reg->estado }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-slate-400 text-sm text-center py-4">No hay registros hoy</p>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
function qrScanner() {
    return {
        scanning: false,
        result: '',
        resultDetail: '',
        success: false,
        manualId: '',
        stream: null,

        init() {
            // Check jsQR availability
        },

        async startCamera() {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                const video = document.getElementById('qr-video');
                const placeholder = document.getElementById('scanner-placeholder');
                const overlay = document.getElementById('scan-overlay');

                video.srcObject = this.stream;
                video.play();
                video.classList.remove('hidden');
                placeholder.classList.add('hidden');
                overlay.classList.remove('hidden');

                this.scanning = true;
                this.scanLoop();
            } catch (e) {
                this.result = 'No se pudo acceder a la cámara: ' + e.message;
                this.success = false;
            }
        },

        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(t => t.stop());
                this.stream = null;
            }
            const video = document.getElementById('qr-video');
            video.classList.add('hidden');
            document.getElementById('scanner-placeholder').classList.remove('hidden');
            document.getElementById('scan-overlay').classList.add('hidden');
            this.scanning = false;
        },

        scanLoop() {
            if (!this.scanning) return;
            const video = document.getElementById('qr-video');
            const canvas = document.getElementById('qr-canvas');
            const ctx = canvas.getContext('2d');

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width  = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

                if (typeof jsQR === 'function') {
                    const code = jsQR(imageData.data, imageData.width, imageData.height);
                    if (code) {
                        this.processQR(code.data);
                        return;
                    }
                }
            }
            requestAnimationFrame(() => this.scanLoop());
        },

        processQR(data) {
            // Extract alumno ID from URL or raw data
            const match = data.match(/\/alumnos\/(\d+)/);
            const alumnoId = match ? match[1] : data;

            if (!isNaN(alumnoId) && alumnoId > 0) {
                this.registerAttendance(alumnoId);
            } else {
                this.result = 'QR no válido para este sistema';
                this.success = false;
                setTimeout(() => this.scanLoop(), 2000);
            }
        },

        async registerAttendance(alumnoId) {
            try {
                const response = await fetch('{{ route('asistencias.registrar-qr') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ alumno_id: alumnoId }),
                });

                const data = await response.json();
                this.result = data.mensaje;
                this.resultDetail = 'Hora: ' + data.hora;
                this.success = data.success;

                // Continue scanning after delay
                setTimeout(() => this.scanLoop(), 3000);
            } catch (e) {
                this.result = 'Error al registrar asistencia';
                this.success = false;
                setTimeout(() => this.scanLoop(), 2000);
            }
        },

        manualRegister() {
            if (this.manualId) {
                this.registerAttendance(this.manualId);
            }
        }
    };
}
</script>
<!-- jsQR library for QR decoding -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
@endpush

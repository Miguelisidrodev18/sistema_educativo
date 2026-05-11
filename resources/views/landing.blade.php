<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Colegio Pre JEDSON — Formando líderes del mañana</title>
<meta name="description" content="Colegio Pre JEDSON, institución educativa de excelencia en Arequipa, Perú. Educación inicial, primaria y secundaria con valores y tecnología.">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*{font-family:'Inter',sans-serif}
@keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
@keyframes pulse-glow{0%,100%{box-shadow:0 0 20px rgba(59,130,246,0.3)}50%{box-shadow:0 0 40px rgba(59,130,246,0.6)}}
@keyframes slide-right{from{transform:translateX(-100%);opacity:0}to{transform:translateX(0);opacity:1}}
@keyframes count-up{from{opacity:0;transform:scale(0.5)}to{opacity:1;transform:scale(1)}}
.fade-up{animation:fadeUp 0.8s ease both}
.fade-up-d1{animation:fadeUp 0.8s 0.15s ease both}
.fade-up-d2{animation:fadeUp 0.8s 0.3s ease both}
.fade-up-d3{animation:fadeUp 0.8s 0.45s ease both}
.floating{animation:float 3s ease-in-out infinite}
.glow-btn{animation:pulse-glow 2s ease-in-out infinite}
.hero-gradient{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 40%,#1e40af 100%)}
.glass{background:rgba(255,255,255,0.08);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.12)}
.card-hover{transition:all 0.4s cubic-bezier(0.4,0,0.2,1)}
.card-hover:hover{transform:translateY(-8px);box-shadow:0 25px 50px -12px rgba(0,0,0,0.25)}
.nav-blur{backdrop-filter:blur(12px);background:rgba(15,23,42,0.85)}
.gradient-text{background:linear-gradient(135deg,#60a5fa,#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.section-reveal{opacity:0;transform:translateY(40px);transition:all 0.8s ease}
.section-reveal.visible{opacity:1;transform:translateY(0)}
</style>
</head>
<body class="bg-slate-950 text-white overflow-x-hidden">

<!-- Navbar -->
<nav class="fixed top-0 w-full z-50 nav-blur border-b border-white/10" id="navbar">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between h-16">
<div class="flex items-center gap-3">
<img src="{{ asset('images/logo.png') }}" alt="Logo JEDSON" class="w-10 h-10 rounded-xl object-contain"
     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
<div style="display:none" class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl items-center justify-center">
<i class="fa-solid fa-graduation-cap text-white text-lg"></i>
</div>
<div>
<h1 class="text-lg font-bold text-white leading-tight">Pre JEDSON</h1>
<p class="text-[10px] text-blue-300 -mt-0.5">Arequipa • Perú</p>
</div>
</div>
<div class="hidden md:flex items-center gap-8 text-sm text-slate-300">
<a href="#inicio" class="hover:text-white transition">Inicio</a>
<a href="#nosotros" class="hover:text-white transition">Nosotros</a>
<a href="#niveles" class="hover:text-white transition">Niveles</a>
<a href="#contacto" class="hover:text-white transition">Contacto</a>
</div>
<div class="flex items-center gap-3">
<a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-sm font-semibold rounded-xl transition-all duration-300 glow-btn">
<i class="fa-solid fa-right-to-bracket"></i> Ingresar
</a>
<button id="menuBtn" class="md:hidden text-white text-xl"><i class="fa-solid fa-bars"></i></button>
</div>
</div>
</div>
<!-- Mobile menu -->
<div id="mobileMenu" class="hidden md:hidden border-t border-white/10 pb-4">
<div class="px-4 pt-3 flex flex-col gap-3 text-sm text-slate-300">
<a href="#inicio" class="hover:text-white py-1">Inicio</a>
<a href="#nosotros" class="hover:text-white py-1">Nosotros</a>
<a href="#niveles" class="hover:text-white py-1">Niveles</a>
<a href="#contacto" class="hover:text-white py-1">Contacto</a>
<a href="{{ route('login') }}" class="mt-2 text-center py-2 bg-blue-600 text-white rounded-lg font-semibold">Ingresar</a>
</div>
</div>
</nav>

<!-- Hero -->
<section id="inicio" class="relative min-h-screen flex items-center hero-gradient overflow-hidden">
<div class="absolute inset-0 opacity-20">
<img src="/images/hero-landing.png" alt="Campus JEDSON" class="w-full h-full object-cover">
</div>
<div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-transparent"></div>
<div class="absolute top-20 right-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl floating"></div>
<div class="absolute bottom-20 left-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl floating" style="animation-delay:1.5s"></div>

<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
<div class="max-w-2xl">
<div class="fade-up inline-flex items-center gap-2 px-4 py-1.5 glass rounded-full text-blue-300 text-sm mb-6">
<i class="fa-solid fa-star text-yellow-400 text-xs"></i> Matrículas Abiertas {{ date('Y') }}
</div>
<h2 class="fade-up-d1 text-4xl sm:text-5xl lg:text-6xl font-black leading-tight">
Formando <span class="gradient-text">líderes</span> del mañana
</h2>
<p class="fade-up-d2 mt-6 text-lg text-slate-300 leading-relaxed max-w-lg">
Educación integral de calidad con valores, tecnología y excelencia académica en el corazón de Arequipa.
</p>
<div class="fade-up-d3 mt-8 flex flex-wrap gap-4">
<a href="#contacto" class="px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 rounded-xl font-bold text-sm transition-all duration-300 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 flex items-center gap-2">
<i class="fa-solid fa-paper-plane"></i> Solicitar Información
</a>
<a href="#nosotros" class="px-8 py-3.5 glass hover:bg-white/15 rounded-xl font-semibold text-sm transition-all duration-300 flex items-center gap-2">
<i class="fa-solid fa-play"></i> Conócenos
</a>
</div>
</div>
</div>
</section>

<!-- Stats Bar -->
<section class="relative -mt-16 z-10 max-w-5xl mx-auto px-4">
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
<div class="glass rounded-2xl p-5 text-center card-hover">
<div class="text-3xl font-black text-blue-400">+500</div>
<div class="text-xs text-slate-400 mt-1">Estudiantes</div>
</div>
<div class="glass rounded-2xl p-5 text-center card-hover">
<div class="text-3xl font-black text-emerald-400">25</div>
<div class="text-xs text-slate-400 mt-1">Años de Experiencia</div>
</div>
<div class="glass rounded-2xl p-5 text-center card-hover">
<div class="text-3xl font-black text-amber-400">98%</div>
<div class="text-xs text-slate-400 mt-1">Satisfacción</div>
</div>
<div class="glass rounded-2xl p-5 text-center card-hover">
<div class="text-3xl font-black text-purple-400">3</div>
<div class="text-xs text-slate-400 mt-1">Niveles Educativos</div>
</div>
</div>
</section>

<!-- About -->
<section id="nosotros" class="py-24 section-reveal">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="grid lg:grid-cols-2 gap-16 items-center">
<div>
<span class="text-blue-400 text-sm font-semibold tracking-wider uppercase">Sobre Nosotros</span>
<h3 class="text-3xl sm:text-4xl font-bold mt-3 leading-tight">Una educación que transforma <span class="gradient-text">vidas</span></h3>
<p class="text-slate-400 mt-6 leading-relaxed">
En el Colegio Pre JEDSON creemos que cada estudiante tiene un potencial único. Nuestra metodología combina excelencia académica con formación en valores, preparando a nuestros alumnos para los desafíos del futuro.
</p>
<div class="mt-8 space-y-4">
<div class="flex items-start gap-4">
<div class="w-10 h-10 bg-blue-500/15 rounded-xl flex items-center justify-center shrink-0">
<i class="fa-solid fa-book-open text-blue-400"></i>
</div>
<div>
<h4 class="font-semibold text-white">Metodología Innovadora</h4>
<p class="text-sm text-slate-400 mt-0.5">Aprendizaje basado en proyectos y tecnología educativa de vanguardia.</p>
</div>
</div>
<div class="flex items-start gap-4">
<div class="w-10 h-10 bg-emerald-500/15 rounded-xl flex items-center justify-center shrink-0">
<i class="fa-solid fa-users text-emerald-400"></i>
</div>
<div>
<h4 class="font-semibold text-white">Docentes Calificados</h4>
<p class="text-sm text-slate-400 mt-0.5">Equipo profesional comprometido con la excelencia educativa.</p>
</div>
</div>
<div class="flex items-start gap-4">
<div class="w-10 h-10 bg-amber-500/15 rounded-xl flex items-center justify-center shrink-0">
<i class="fa-solid fa-heart text-amber-400"></i>
</div>
<div>
<h4 class="font-semibold text-white">Formación en Valores</h4>
<p class="text-sm text-slate-400 mt-0.5">Desarrollamos ciudadanos íntegros con responsabilidad social.</p>
</div>
</div>
</div>
</div>
<div class="relative">
<div class="absolute -inset-4 bg-gradient-to-r from-blue-500/20 to-indigo-500/20 rounded-3xl blur-2xl"></div>
<img src="/images/classroom-modern.png" alt="Aula moderna JEDSON" class="relative rounded-2xl shadow-2xl w-full object-cover aspect-[4/3]">
</div>
</div>
</div>
</section>

<!-- Niveles -->
<section id="niveles" class="py-24 bg-slate-900/50 section-reveal">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center mb-16">
<span class="text-blue-400 text-sm font-semibold tracking-wider uppercase">Oferta Educativa</span>
<h3 class="text-3xl sm:text-4xl font-bold mt-3">Nuestros <span class="gradient-text">Niveles</span></h3>
</div>
<div class="grid md:grid-cols-3 gap-8">
<div class="glass rounded-2xl p-8 card-hover group">
<div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
<i class="fa-solid fa-puzzle-piece text-white text-xl"></i>
</div>
<h4 class="text-xl font-bold">Inicial</h4>
<p class="text-slate-400 text-sm mt-3 leading-relaxed">Estimulación temprana y desarrollo integral en un ambiente seguro y lúdico para los más pequeños.</p>
<ul class="mt-5 space-y-2 text-sm text-slate-300">
<li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>3 a 5 años</li>
<li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Psicomotricidad</li>
<li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Inglés básico</li>
</ul>
</div>
<div class="relative glass rounded-2xl p-8 card-hover group border-blue-500/30">
<div class="absolute -top-3 right-6 px-3 py-1 bg-blue-600 text-xs font-bold rounded-full">Popular</div>
<div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
<i class="fa-solid fa-book text-white text-xl"></i>
</div>
<h4 class="text-xl font-bold">Primaria</h4>
<p class="text-slate-400 text-sm mt-3 leading-relaxed">Formación sólida en competencias fundamentales con enfoque en pensamiento crítico.</p>
<ul class="mt-5 space-y-2 text-sm text-slate-300">
<li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>1° a 6° grado</li>
<li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Robótica y STEM</li>
<li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Talleres artísticos</li>
</ul>
</div>
<div class="glass rounded-2xl p-8 card-hover group">
<div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
<i class="fa-solid fa-flask text-white text-xl"></i>
</div>
<h4 class="text-xl font-bold">Secundaria</h4>
<p class="text-slate-400 text-sm mt-3 leading-relaxed">Preparación preuniversitaria con enfoque en ciencias, humanidades y liderazgo.</p>
<ul class="mt-5 space-y-2 text-sm text-slate-300">
<li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>1° a 5° año</li>
<li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Pre-universitario</li>
<li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Orientación vocacional</li>
</ul>
</div>
</div>
</div>
</section>

<!-- Contact / Info Request -->
<section id="contacto" class="py-24 section-reveal">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="grid lg:grid-cols-2 gap-16">
<div>
<span class="text-blue-400 text-sm font-semibold tracking-wider uppercase">Contáctanos</span>
<h3 class="text-3xl sm:text-4xl font-bold mt-3">Solicita <span class="gradient-text">información</span></h3>
<p class="text-slate-400 mt-4 leading-relaxed">Completa el formulario y nuestro equipo de admisiones se pondrá en contacto contigo.</p>
<div class="mt-10 space-y-5">
<div class="flex items-center gap-4">
<div class="w-12 h-12 glass rounded-xl flex items-center justify-center">
<i class="fa-solid fa-location-dot text-blue-400"></i>
</div>
<div>
<div class="font-semibold text-sm">Dirección</div>
<div class="text-slate-400 text-sm">Arequipa, Perú</div>
</div>
</div>
<div class="flex items-center gap-4">
<div class="w-12 h-12 glass rounded-xl flex items-center justify-center">
<i class="fa-solid fa-phone text-emerald-400"></i>
</div>
<div>
<div class="font-semibold text-sm">Teléfono</div>
<div class="text-slate-400 text-sm">(054) 000-000</div>
</div>
</div>
<div class="flex items-center gap-4">
<div class="w-12 h-12 glass rounded-xl flex items-center justify-center">
<i class="fa-solid fa-envelope text-amber-400"></i>
</div>
<div>
<div class="font-semibold text-sm">Email</div>
<div class="text-slate-400 text-sm">info@jedson.edu.pe</div>
</div>
</div>
</div>
</div>
<div>
<form class="glass rounded-2xl p-8 space-y-5" id="contactForm">
<div class="grid sm:grid-cols-2 gap-5">
<div>
<label class="text-sm font-medium text-slate-300 mb-1.5 block">Nombre del Padre/Madre</label>
<input type="text" placeholder="Ingrese su nombre" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
</div>
<div>
<label class="text-sm font-medium text-slate-300 mb-1.5 block">Teléfono</label>
<input type="tel" placeholder="999 999 999" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
</div>
</div>
<div>
<label class="text-sm font-medium text-slate-300 mb-1.5 block">Correo Electrónico</label>
<input type="email" placeholder="correo@ejemplo.com" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
</div>
<div class="grid sm:grid-cols-2 gap-5">
<div>
<label class="text-sm font-medium text-slate-300 mb-1.5 block">Nombre del Alumno</label>
<input type="text" placeholder="Nombre del estudiante" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
</div>
<div>
<label class="text-sm font-medium text-slate-300 mb-1.5 block">Nivel de Interés</label>
<select class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
<option value="">Seleccionar</option>
<option>Inicial</option>
<option>Primaria</option>
<option>Secundaria</option>
</select>
</div>
</div>
<div>
<label class="text-sm font-medium text-slate-300 mb-1.5 block">Mensaje (Opcional)</label>
<textarea rows="3" placeholder="¿Alguna consulta adicional?" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"></textarea>
</div>
<button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 rounded-xl font-bold text-sm transition-all duration-300 shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2">
<i class="fa-solid fa-paper-plane"></i> Enviar Solicitud
</button>
<p id="formMsg" class="hidden text-center text-sm text-emerald-400 font-medium"><i class="fa-solid fa-circle-check mr-1"></i>¡Solicitud enviada! Nos comunicaremos contigo pronto.</p>
</form>
</div>
</div>
</div>
</section>

<!-- Footer -->
<footer class="border-t border-white/10 py-10">
<div class="max-w-7xl mx-auto px-4 text-center">
<div class="flex items-center justify-center gap-3 mb-4">
<img src="{{ asset('images/logo.png') }}" alt="Logo JEDSON" class="w-8 h-8 rounded-lg object-contain"
     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
<div style="display:none" class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg items-center justify-center">
<i class="fa-solid fa-graduation-cap text-white text-sm"></i>
</div>
<span class="font-bold text-lg">Colegio Pre JEDSON</span>
</div>
<div class="flex justify-center gap-4 mb-6">
<a href="#" class="w-9 h-9 glass rounded-full flex items-center justify-center hover:bg-blue-600/30 transition"><i class="fa-brands fa-facebook-f text-sm"></i></a>
<a href="#" class="w-9 h-9 glass rounded-full flex items-center justify-center hover:bg-pink-600/30 transition"><i class="fa-brands fa-instagram text-sm"></i></a>
<a href="#" class="w-9 h-9 glass rounded-full flex items-center justify-center hover:bg-red-600/30 transition"><i class="fa-brands fa-youtube text-sm"></i></a>
<a href="#" class="w-9 h-9 glass rounded-full flex items-center justify-center hover:bg-green-600/30 transition"><i class="fa-brands fa-whatsapp text-sm"></i></a>
</div>
<p class="text-slate-500 text-xs">&copy; {{ date('Y') }} Colegio Pre JEDSON — Arequipa, Perú. Todos los derechos reservados.</p>
</div>
</footer>

<script>
// Mobile menu
document.getElementById('menuBtn').addEventListener('click',()=>{
const m=document.getElementById('mobileMenu');m.classList.toggle('hidden')
});
// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a=>{
a.addEventListener('click',e=>{e.preventDefault();const t=document.querySelector(a.getAttribute('href'));if(t)t.scrollIntoView({behavior:'smooth'});document.getElementById('mobileMenu').classList.add('hidden')})
});
// Scroll reveal
const obs=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible')})},{threshold:0.15});
document.querySelectorAll('.section-reveal').forEach(el=>obs.observe(el));
// Form
document.getElementById('contactForm').addEventListener('submit',e=>{
e.preventDefault();const msg=document.getElementById('formMsg');msg.classList.remove('hidden');e.target.reset();setTimeout(()=>msg.classList.add('hidden'),5000)
});
// Navbar scroll
window.addEventListener('scroll',()=>{document.getElementById('navbar').classList.toggle('shadow-lg',window.scrollY>50)});
</script>
</body>
</html>

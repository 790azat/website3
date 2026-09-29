{{--
    Entry gate (press-and-hold captcha) shown over the site until the visitor
    completes it. The pass is kept in localStorage for 24 hours; add ?gate=1
    to any URL to show it again. Legal pages stay open so the gate's own links work.
    After passing, the visitor is sent to one of the main guides (random).
--}}
<script>window.__gateGuides = @json(\App\Support\SiteContent::programs()->pluck('slug')->values());</script>
@verbatim
<style>
    html.gate-on { background: #0d1424; }
    html.gate-on body { visibility: hidden; overflow: hidden; }
    #cm-gate {
        visibility: visible; position: fixed; inset: 0; z-index: 2147483000;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 24px 16px; overflow-y: auto; color: #f1f5f9;
        background:
            linear-gradient(rgba(159, 176, 204, .06) 1px, transparent 1px) 0 0 / 32px 32px,
            linear-gradient(90deg, rgba(159, 176, 204, .06) 1px, transparent 1px) 0 0 / 32px 32px,
            radial-gradient(50rem 30rem at 50% -10%, #2b3c5e, transparent 70%),
            #0d1424;
        font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        transition: opacity .45s ease;
    }
    #cm-gate.is-leaving { opacity: 0; }
    #cm-gate * { box-sizing: border-box; }
    .cm-card {
        position: relative; overflow: hidden; width: 100%; max-width: 420px; padding: 32px 26px 28px; text-align: center;
        border-radius: 6px; background: #162036; border: 1px solid #2b3c5e; border-top: 4px solid #fb7025;
        box-shadow: 0 30px 70px -25px rgba(0, 0, 0, .8);
        animation: cm-rise .45s cubic-bezier(.2, .8, .2, 1) both;
    }
    @keyframes cm-rise { from { opacity: 0; transform: translateY(12px); } }
    .cm-card::after {
        content: ""; position: absolute; left: 0; right: 0; top: -40%; height: 40%; pointer-events: none;
        background: linear-gradient(180deg, transparent, rgba(251, 112, 37, .10), transparent);
        animation: cm-scan 3.2s linear infinite;
    }
    @keyframes cm-scan { to { top: 110%; } }
    .cm-brand { font-weight: 800; font-size: 15px; letter-spacing: .02em; text-transform: uppercase; color: #cbd5e1; }
    .cm-brand b { color: #fb7025; }
    .cm-kicker { display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; font-size: 12px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #fda06c; }
    .cm-kicker i { width: 8px; height: 8px; background: #fb7025; border-radius: 1px; animation: cm-blink 1s steps(2) infinite; }
    @keyframes cm-blink { 50% { opacity: .2; } }
    .cm-title { margin: 10px 0 0; font-size: 26px; line-height: 1.2; font-weight: 800; letter-spacing: -.01em; }
    .cm-sub { margin: 8px 0 0; font-size: 15px; line-height: 1.5; color: #9fb0cc; }
    .cm-hold {
        position: relative; margin: 28px auto 8px; width: 150px; height: 150px; border: 0; padding: 0; border-radius: 50%;
        cursor: pointer; background: none; color: #fff; font: inherit; touch-action: none;
        -webkit-user-select: none; user-select: none; -webkit-touch-callout: none;
    }
    .cm-hold:focus-visible { outline: 3px solid #fda06c; outline-offset: 4px; }
    .cm-ring { position: absolute; inset: 0; border-radius: 50%; background: conic-gradient(#fb7025 calc(var(--p, 0) * 1%), #2b3c5e 0); }
    .cm-core {
        position: absolute; inset: 10px; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;
        background: radial-gradient(circle at 50% 30%, #3b5078, #1f2c47 70%);
        box-shadow: inset 0 2px 0 rgba(255, 255, 255, .12), 0 10px 24px -8px rgba(0, 0, 0, .7);
        font-size: 12px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; transition: transform .15s ease;
    }
    .cm-hold.is-holding .cm-core { transform: scale(.94); }
    .cm-hold.is-done .cm-ring { background: #22c55e; }
    .cm-hold.is-shake { animation: cm-shake .35s ease; }
    @keyframes cm-shake { 25% { transform: translateX(-6px); } 75% { transform: translateX(6px); } }
    .cm-hint { margin: 10px 0 0; font-size: 13px; color: #7489ad; }
    .cm-steps { list-style: none; margin: 24px 0 0; padding: 0; text-align: left; display: grid; gap: 10px; }
    .cm-steps li { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 4px; background: #1f2c47; font-size: 14px; color: #cbd5e1; opacity: .45; transition: opacity .25s ease; }
    .cm-steps li.on { opacity: 1; }
    .cm-steps li s { flex: none; width: 18px; height: 18px; border-radius: 50%; border: 2px solid #506891; }
    .cm-steps li.on s { border-color: #fb7025; border-right-color: transparent; animation: cm-rot .7s linear infinite; }
    .cm-steps li.ok s { animation: none; border: 0; background: #22c55e url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M5 12.5l4.5 4.5L19 7.5'/%3E%3C/svg%3E") center / 12px no-repeat; }
    @keyframes cm-rot { to { transform: rotate(360deg); } }
    .cm-wait { margin-top: 18px; font-size: 12px; letter-spacing: .18em; text-transform: uppercase; color: #7489ad; }
    .cm-foot { max-width: 420px; margin-top: 20px; font-size: 12px; line-height: 1.55; text-align: center; color: #7489ad; }
    .cm-foot a { color: #fda06c; text-decoration: underline; text-underline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { #cm-gate *, .cm-card::after { animation: none !important; } }
</style>
<script>
(function () {
    var KEY = 'cm_gate_pass', TTL = 864e5, HOLD = 1600, root = document.documentElement;
    try { if (/[?&]gate=1\b/.test(location.search)) localStorage.removeItem(KEY); } catch (e) {}
    var passed = false;
    try { passed = Number(localStorage.getItem(KEY)) > Date.now() - TTL; } catch (e) {}
    if (passed || /\/(terms-of-use|privacy-policy)(\.html)?$/.test(location.pathname)) return;
    root.classList.add('gate-on');

    var T = {
        en: { kick: 'Checking connection', title: 'Confirm you are human', sub: 'Press and hold the button until the ring is full.', hold: 'Hold', keep: 'Keep holding', ok: 'Done', hint: 'Released too early? Just try again.', s1: 'Scanning access', s2: 'Verifying integrity', s3: 'Opening the site', wait: 'Please wait', foot: 'The following content is informational and educational and does not constitute financial, legal or professional advice.', agree: 'By continuing, you agree to our {t} and our {p}.', t: 'Terms of Use', p: 'Privacy Policy' },
        es: { kick: 'Comprobando conexión', title: 'Confirma que eres humano', sub: 'Mantén pulsado el botón hasta que el anillo se llene.', hold: 'Mantén', keep: 'Sigue', ok: 'Listo', hint: '¿Lo soltaste antes? Inténtalo de nuevo.', s1: 'Escaneando acceso', s2: 'Verificando integridad', s3: 'Abriendo el sitio', wait: 'Espera por favor', foot: 'El siguiente contenido es informativo y educativo y no constituye asesoramiento financiero, legal ni profesional.', agree: 'Al continuar, aceptas nuestros {t} y nuestra {p}.', t: 'Términos de uso', p: 'Política de privacidad' },
        fr: { kick: 'Vérification de la connexion', title: 'Confirmez que vous êtes humain', sub: "Maintenez le bouton appuyé jusqu'à ce que l'anneau soit plein.", hold: 'Maintenir', keep: 'Continuez', ok: 'Terminé', hint: 'Relâché trop tôt ? Réessayez.', s1: "Analyse de l'accès", s2: "Vérification de l'intégrité", s3: 'Ouverture du site', wait: 'Veuillez patienter', foot: "Le contenu suivant est informatif et éducatif et ne constitue pas un conseil financier, juridique ou professionnel.", agree: 'En continuant, vous acceptez nos {t} et notre {p}.', t: "Conditions d'utilisation", p: 'Politique de confidentialité' }
    };
    var lang = (root.lang || 'en').slice(0, 2), t = T[lang] || T.en, pre = T[lang] && lang !== 'en' ? '/' + lang : '';
    var brand = '<div class="cm-brand">Contractor-<b>Mag</b></div>';
    var finger = '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fda06c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 11V5a1.5 1.5 0 0 1 3 0v6"/><path d="M15 10.5a1.5 1.5 0 0 1 3 0V12"/><path d="M18 11.5a1.5 1.5 0 0 1 3 0V15a6 6 0 0 1-6 6h-2a6 6 0 0 1-5-2.7L4.3 14.2a1.5 1.5 0 0 1 2.4-1.8L9 15V8a1.5 1.5 0 0 1 3 0"/></svg>';

    function build() {
        var g = document.createElement('div');
        g.id = 'cm-gate';
        g.setAttribute('role', 'dialog');
        g.setAttribute('aria-modal', 'true');
        g.setAttribute('aria-labelledby', 'cm-q');
        var agree = t.agree.replace('{t}', '<a href="' + pre + '/terms-of-use">' + t.t + '</a>').replace('{p}', '<a href="' + pre + '/privacy-policy">' + t.p + '</a>');
        g.innerHTML =
            '<div class="cm-card">' + brand +
                '<div class="cm-kicker"><i></i>' + t.kick + '</div>' +
                '<h2 class="cm-title" id="cm-q">' + t.title + '</h2>' +
                '<p class="cm-sub">' + t.sub + '</p>' +
                '<button type="button" class="cm-hold" aria-describedby="cm-q"><span class="cm-ring"></span><span class="cm-core">' + finger + '<span class="cm-lbl">' + t.hold + '</span></span></button>' +
                '<p class="cm-hint" hidden>' + t.hint + '</p>' +
            '</div>' +
            '<p class="cm-foot">' + t.foot + '<br>' + agree + '</p>';
        document.body.appendChild(g);

        var card = g.querySelector('.cm-card'), btn = g.querySelector('.cm-hold'), ring = g.querySelector('.cm-ring'),
            lbl = g.querySelector('.cm-lbl'), hint = g.querySelector('.cm-hint');
        var start = 0, raf = 0, done = false, holding = false;
        function tick(now) {
            var p = Math.min(1, (now - start) / HOLD);
            ring.style.setProperty('--p', (p * 100).toFixed(1));
            if (p >= 1) return complete();
            raf = requestAnimationFrame(tick);
        }
        function down(e) {
            if (done || holding) return;
            if (e && e.pointerId !== undefined) { try { btn.setPointerCapture(e.pointerId); } catch (x) {} }
            holding = true;
            btn.classList.add('is-holding');
            lbl.textContent = t.keep;
            start = performance.now();
            raf = requestAnimationFrame(tick);
        }
        function up() {
            if (done || !holding) return;
            holding = false;
            cancelAnimationFrame(raf);
            btn.classList.remove('is-holding');
            lbl.textContent = t.hold;
            ring.style.setProperty('--p', 0);
            hint.hidden = false;
            btn.classList.remove('is-shake');
            btn.offsetWidth;
            btn.classList.add('is-shake');
        }
        btn.addEventListener('pointerdown', function (e) { e.preventDefault(); down(e); });
        btn.addEventListener('pointerup', up);
        btn.addEventListener('pointercancel', up);
        btn.addEventListener('contextmenu', function (e) { e.preventDefault(); });
        btn.addEventListener('keydown', function (e) { if ((e.key === ' ' || e.key === 'Enter') && !e.repeat) { e.preventDefault(); down(); } });
        btn.addEventListener('keyup', function (e) { if (e.key === ' ' || e.key === 'Enter') up(); });

        function complete() {
            done = true;
            btn.classList.remove('is-holding');
            btn.classList.add('is-done');
            lbl.textContent = t.ok;
            try { localStorage.setItem(KEY, String(Date.now())); } catch (e) {}
            setTimeout(function () {
                card.innerHTML = brand +
                    '<div class="cm-kicker"><i></i>' + t.kick + '</div>' +
                    '<ul class="cm-steps" role="status"><li>' + '<s></s>' + t.s1 + '</li><li><s></s>' + t.s2 + '</li><li><s></s>' + t.s3 + '</li></ul>' +
                    '<div class="cm-wait">' + t.wait + '</div>';
                var li = card.querySelectorAll('.cm-steps li');
                [0, 1, 2].forEach(function (i) {
                    setTimeout(function () { li[i].classList.add('on'); }, i * 450);
                    setTimeout(function () { li[i].classList.add('ok'); }, i * 450 + 420);
                });
            }, 450);
            setTimeout(function () {
                var guides = window.__gateGuides || [];
                if (guides.length && !/\/programs\//.test(location.pathname)) {
                    location.replace(pre + '/programs/' + guides[Math.floor(Math.random() * guides.length)]);
                    return;
                }
                g.classList.add('is-leaving');
                root.classList.remove('gate-on');
                setTimeout(function () { g.remove(); }, 460);
            }, 2400);
        }
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', build);
    else build();
})();
</script>
@endverbatim

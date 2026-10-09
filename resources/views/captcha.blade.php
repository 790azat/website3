{{-- Captcha page: neon "Are you 18 or older?" gate, blue (no site name or logo). Pass logic: window.Gate below; guard: partials/head. --}}
@verbatim
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#02060f">
<meta name="robots" content="noindex, nofollow">
<meta name="referrer" content="no-referrer-when-downgrade">
<title>Age verification</title>
<link rel="icon" href="data:,">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* Neon age gate: blue screen, red YES disc, cyan as the second light. */
    :root {
        --bg: #02060f;
        --screen: #030812;
        --blue: #3d8bff;
        --cyan: #00dfe8;
        --white: #f7fbff;
        --muted: #8f9db5;
        --faint: #6b7890;
        --lobby-a: #142a52;
        --lobby-b: #0a1630;
        --vignette: #01040a;
        --blue-a85: rgba(61, 139, 255, .85);
        --blue-a80: rgba(61, 139, 255, .8);
        --blue-a65: rgba(61, 139, 255, .65);
        --blue-a55: rgba(61, 139, 255, .55);
        --blue-a34: rgba(61, 139, 255, .34);
        --blue-a30: rgba(61, 139, 255, .3);
        --blue-a22: rgba(61, 139, 255, .22);
        --red: #ff3b4e;
        --red-disc: #1c0610;
        --red-a80: rgba(255, 59, 78, .8);
        --red-a65: rgba(255, 59, 78, .65);
        --red-a34: rgba(255, 59, 78, .34);
        --red-a30: rgba(255, 59, 78, .3);
        --cyan-a90: rgba(0, 223, 232, .9);
        --cyan-a75: rgba(0, 223, 232, .75);
        --cyan-a45: rgba(0, 223, 232, .45);
        --cyan-a38: rgba(0, 223, 232, .38);
        --cyan-a20: rgba(0, 223, 232, .2);
        --cyan-a14: rgba(0, 223, 232, .14);
        --cyan-a075: rgba(0, 223, 232, .075);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { background: var(--bg); }
    body { font-family: Archivo, Helvetica, system-ui, sans-serif; -webkit-font-smoothing: antialiased; color: var(--white); }

    .gate {
        position: relative; width: 100%; height: 100vh; height: 100dvh; overflow: hidden; background: var(--screen);
        user-select: none; -webkit-user-select: none; -webkit-tap-highlight-color: transparent;
    }
    /* Lobby: the blurred layer behind the door, it sharpens on unlock. */
    .lobby {
        position: absolute; inset: -40px; filter: blur(22px) saturate(1.2); transform: scale(1.16);
        transition: filter 1100ms cubic-bezier(.2, .8, .2, 1), transform 1500ms cubic-bezier(.2, .8, .2, 1);
    }
    .gate.done .lobby { filter: blur(0) saturate(1.2); transform: scale(1); }
    .lobby-stripes { position: absolute; inset: 0; background: repeating-linear-gradient(50deg, var(--lobby-a) 0 4px, var(--lobby-b) 4px 11px); }
    .lobby-glow {
        position: absolute; inset: 0;
        background: radial-gradient(110% 55% at 70% 22%, var(--blue-a55), transparent 62%), radial-gradient(95% 50% at 20% 80%, var(--cyan-a38), transparent 66%);
    }

    .screen { position: absolute; inset: 0; z-index: 3; opacity: 1; transition: opacity 640ms ease 120ms; }
    .gate.done .screen { opacity: 0; pointer-events: none; }
    .screen-bg { position: absolute; inset: 0; background: var(--screen); }
    .screen-grid {
        position: absolute; inset: 0; background-size: 34px 34px;
        background-image: linear-gradient(var(--cyan-a075) 1px, transparent 1px), linear-gradient(90deg, var(--cyan-a075) 1px, transparent 1px);
    }
    .screen-glow {
        position: absolute; inset: 0;
        background: radial-gradient(80% 44% at 50% 8%, var(--blue-a30), transparent 70%), radial-gradient(70% 40% at 50% 100%, var(--cyan-a20), transparent 70%);
    }
    .screen-scan {
        position: absolute; left: 0; right: 0; top: -180px; height: 180px;
        background: linear-gradient(180deg, transparent, var(--cyan-a14), transparent); animation: scan 7000ms linear infinite;
    }
    .screen-vignette { position: absolute; inset: 0; box-shadow: inset 0 0 140px var(--vignette); }
    .screen-grid, .screen-glow, .screen-scan { transition: opacity 380ms ease; }
    .gate.done .screen-grid, .gate.done .screen-glow, .gate.done .screen-scan { opacity: 0; }

    .content {
        position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: clamp(40px, 9vh, 74px); padding: clamp(48px, 11vh, 96px) clamp(24px, 11vw, 46px) clamp(40px, 7vh, 64px);
    }
    .question {
        font-size: clamp(30px, 8.5vw, 46px); font-weight: 800; letter-spacing: -0.045em; line-height: 1.05; text-align: center; color: var(--white);
        text-shadow: 0 0 5px var(--white), 0 0 16px var(--blue), 0 0 42px var(--blue-a85), 0 0 84px var(--blue-a55);
        animation: flick 5200ms linear infinite; text-wrap: pretty;
    }

    .yes {
        position: relative; width: clamp(168px, 46vw, 208px); height: clamp(168px, 46vw, 208px); flex: none; padding: 0; border: 0;
        background: none; color: inherit; font: inherit; cursor: pointer; touch-action: manipulation;
        transform: scale(1); transition: transform 200ms cubic-bezier(.34, 1.4, .64, 1);
    }
    .yes:focus-visible { outline: 2px solid var(--cyan); outline-offset: 10px; border-radius: 50%; }
    .yes.pressed { transform: scale(.93); }
    .yes .halo { position: absolute; inset: -30px; border-radius: 50%; background: radial-gradient(circle, var(--red-a34), transparent 68%); animation: halo 2400ms ease-in-out infinite; }
    .yes .sweep {
        position: absolute; inset: -6px; border-radius: 50%; filter: blur(3px);
        background: conic-gradient(from 0deg, transparent 0deg, var(--cyan-a90) 40deg, transparent 96deg); animation: spin 3400ms linear infinite;
    }
    .yes .disc { position: absolute; inset: 0; border-radius: 50%; background: var(--red-disc); border: 2px solid var(--red); box-shadow: 0 0 22px var(--red-a80), inset 0 0 34px var(--red-a30); }
    .yes .inner-ring { position: absolute; inset: 16px; border-radius: 50%; border: 1px solid var(--cyan-a45); animation: creep 2400ms ease-in-out infinite; }
    .yes .ripple { position: absolute; inset: 0; border-radius: 50%; border: 2px solid var(--cyan-a75); pointer-events: none; opacity: 0; transform: scale(.62); }
    .yes .ripple.armed { opacity: .95; transform: scale(.62); transition: none; }
    .yes .ripple.expand { opacity: 0; transform: scale(2.75); transition: transform 780ms cubic-bezier(.2, .8, .2, 1), opacity 780ms ease; }
    .yes .label {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
        font-size: clamp(38px, 12vw, 52px); font-weight: 800; letter-spacing: -0.04em; color: var(--white);
        text-shadow: 0 0 6px var(--white), 0 0 22px var(--red), 0 0 54px var(--red-a65);
    }

    /* Redirect loader: fades in as the gate opens and stays until the next page paints. */
    .loader {
        position: absolute; inset: 0; z-index: 3; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 22px;
        opacity: 0; pointer-events: none; transition: opacity 420ms ease 240ms;
    }
    .gate.done .loader { opacity: 1; }
    .loader-ring {
        width: 64px; height: 64px; border-radius: 50%; border: 3px solid var(--cyan-a20); border-top-color: var(--cyan);
        box-shadow: 0 0 18px var(--blue-a34), inset 0 0 12px var(--blue-a22); animation: spin 900ms linear infinite;
    }
    .loader-label {
        font-family: 'IBM Plex Mono', ui-monospace, monospace; font-size: 11px; font-weight: 500; letter-spacing: .26em; text-transform: uppercase;
        color: var(--cyan); text-shadow: 0 0 12px var(--cyan-a45); animation: creep 1800ms ease-in-out infinite;
    }
    .bloom {
        position: absolute; inset: 0; z-index: 4; pointer-events: none; opacity: 0; transition: opacity 950ms ease;
        background: radial-gradient(circle at 50% 63%, var(--white), var(--blue-a34) 34%, transparent 70%);
    }
    .bloom.on { opacity: 1; }

    footer {
        width: 100%; max-width: 560px; margin: 0 auto; padding: 28px 20px 36px;
        font-family: 'IBM Plex Mono', ui-monospace, monospace; font-size: 11px; line-height: 1.6; color: var(--faint); text-align: center;
    }
    footer p { margin-bottom: 8px; text-wrap: pretty; }
    footer a { color: var(--cyan); text-decoration: none; border-bottom: 1px solid var(--cyan-a45); }
    .legal-meta { margin-top: 12px; color: var(--muted); letter-spacing: .08em; }

    @keyframes halo { 0%, 100% { opacity: .4; transform: scale(1); } 50% { opacity: .85; transform: scale(1.08); } }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes flick { 0%, 96%, 100% { opacity: 1; } 97% { opacity: .55; } 98.5% { opacity: .85; } }
    @keyframes scan { 0% { transform: translateY(0); } 100% { transform: translateY(calc(100vh + 180px)); } }
    @keyframes creep { 0%, 100% { opacity: .5; } 50% { opacity: 1; } }
    @media (prefers-reduced-motion: reduce) {
        .question, .yes .halo, .yes .sweep, .yes .inner-ring, .screen-scan, .loader-label { animation: none; }
        .lobby, .screen, .bloom, .yes, .yes .ripple { transition-duration: 1ms; }
    }
</style>
<script>
/*
 * Same-site captcha. Every page sends a visitor without a pass to
 * /captcha?next=<page>. Passing it stores a short-lived cookie (30 minutes)
 * and opens the page they asked for (the home page for site.com).
 */
window.Gate = (function () {
    var PASS_MINUTES = 30;
    var qs = new URLSearchParams(location.search);
    var next = qs.get('next') || '/';
    if (!/^\/(?![\/\\])/.test(next) || /^\/(?:(?:es|fr)\/)?captcha(?:[?#.]|$)/.test(next)) next = '/';

    var m = next.match(/^\/(es|fr)(?=[\/?#]|$)/) || next.match(/[?&]lang=(es|fr)\b/);
    var lang = m ? m[1] : 'en', pre = lang === 'en' ? '' : '/' + lang;
    var TITLES = { en: 'Age verification', es: 'Verificación de edad', fr: "Vérification de l'âge" };
    document.documentElement.lang = lang;
    document.title = TITLES[lang];

    var FOOT = {
        en: { foot: 'The following content is informational and educational and does not constitute financial, legal, medical, or professional advice. Results are not guaranteed; your experience may vary.', rights: 'All rights reserved.', agree: 'By continuing, you agree to our {t} and our {p}', t: 'Terms of Use', p: 'Privacy Policy' },
        es: { foot: 'El siguiente contenido es informativo y educativo y no constituye asesoramiento financiero, legal, médico ni profesional. Los resultados no están garantizados; tu experiencia puede variar.', rights: 'Todos los derechos reservados.', agree: 'Al continuar, aceptas nuestros {t} y nuestra {p}', t: 'Términos de uso', p: 'Política de privacidad' },
        fr: { foot: "Le contenu suivant est informatif et éducatif et ne constitue pas un conseil financier, juridique, médical ou professionnel. Les résultats ne sont pas garantis ; votre expérience peut varier.", rights: 'Tous droits réservés.', agree: 'En continuant, vous acceptez nos {t} et notre {p}', t: "Conditions d'utilisation", p: 'Politique de confidentialité' }
    };

    return {
        lang: lang,
        footer: function () {
            var f = FOOT[lang];
            return '<p>' + f.foot + '</p><p>' + f.agree
                .replace('{t}', '<a href="' + pre + '/terms-of-use">' + f.t + '</a>')
                .replace('{p}', '<a href="' + pre + '/privacy-policy">' + f.p + '</a>') +
                '.</p><div class="legal-meta">© ' + new Date().getFullYear() + ' - ' + f.rights + '</div>';
        },
        go: function () {
            document.cookie = 'gate_pass=1; Max-Age=' + PASS_MINUTES * 60 + '; Path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
            location.replace(next);
        }
    };
})();
</script>
</head>
<body>
<main class="gate" id="gate">
    <div class="lobby">
        <div class="lobby-stripes"></div>
        <div class="lobby-glow"></div>
    </div>
    <div class="screen">
        <div class="screen-bg"></div>
        <div class="screen-grid"></div>
        <div class="screen-glow"></div>
        <div class="screen-scan"></div>
        <div class="screen-vignette"></div>
        <div class="content">
            <h1 class="question" id="q">Are you 18 or older?</h1>
            <button class="yes" id="yes" type="button" aria-describedby="q">
                <span class="halo" aria-hidden="true"></span>
                <span class="sweep" aria-hidden="true"></span>
                <span class="disc" aria-hidden="true"></span>
                <span class="inner-ring" aria-hidden="true"></span>
                <span class="ripple" id="ripple" aria-hidden="true"></span>
                <span class="label" id="yes-label">YES</span>
            </button>
        </div>
    </div>
    <div class="loader" id="loader" role="status">
        <span class="loader-ring" aria-hidden="true"></span>
        <span class="loader-label" id="loader-label">Entering</span>
    </div>
    <div class="bloom" id="bloom" aria-hidden="true"></div>
</main>
<footer id="foot"></footer>
<script>
(function () {
    var T = {
        en: { q: 'Are you 18 or older?', yes: 'YES', enter: 'Entering' },
        es: { q: '¿Tienes 18 años o más?', yes: 'SÍ', enter: 'Entrando' },
        fr: { q: 'Avez-vous 18 ans ou plus ?', yes: 'OUI', enter: 'Entrée' }
    };
    var t = T[Gate.lang] || T.en;
    var gate = document.getElementById('gate'), yes = document.getElementById('yes'),
        ripple = document.getElementById('ripple'), bloom = document.getElementById('bloom'), done = false;
    document.getElementById('q').textContent = t.q;
    document.getElementById('yes-label').textContent = t.yes;
    document.getElementById('loader-label').textContent = t.enter;
    document.getElementById('foot').innerHTML = Gate.footer();

    yes.addEventListener('pointerdown', function () { if (!done) yes.classList.add('pressed'); });
    ['pointerup', 'pointerleave', 'pointercancel'].forEach(function (evt) {
        yes.addEventListener(evt, function () { yes.classList.remove('pressed'); });
    });
    yes.addEventListener('click', function () {
        if (done) return;
        done = true;
        yes.classList.remove('pressed');
        // ripple: snap to the small ring, then expand out on the next frame
        ripple.classList.add('armed');
        requestAnimationFrame(function () { ripple.classList.remove('armed'); ripple.classList.add('expand'); });
        // door opens: the screen fades, the lobby sharpens, a bloom flashes over the top
        setTimeout(function () {
            gate.classList.add('done');
            bloom.classList.add('on');
            setTimeout(function () { bloom.classList.remove('on'); }, 130);
        }, 300);
        setTimeout(Gate.go, 900);
    });
})();
</script>
</body>
</html>
@endverbatim

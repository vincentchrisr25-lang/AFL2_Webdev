document.querySelectorAll('.nav').forEach((navigation) => {
    const toggle = navigation.querySelector('.nav__toggle');
    const menu = navigation.querySelector('.nav__menu');

    if (!toggle || !menu) {
        return;
    }

    toggle.addEventListener('click', () => {
        const isExpanded = toggle.getAttribute('aria-expanded') === 'true';

        toggle.setAttribute('aria-expanded', String(!isExpanded));
        toggle.setAttribute('aria-label', isExpanded ? 'Buka menu navigasi' : 'Tutup menu navigasi');
        menu.classList.toggle('is-open', !isExpanded);
    });

    menu.addEventListener('click', (event) => {
        if (event.target instanceof HTMLAnchorElement) {
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-label', 'Buka menu navigasi');
            menu.classList.remove('is-open');
        }
    });
});

document.querySelectorAll('.home-hero').forEach((hero) => {
    const track = hero.querySelector('.home-hero-track');
    const slides = [...hero.querySelectorAll('.home-hero-slide')];
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let activeSlide = 1;
    let startX = null;
    let autoAdvance;
    let loopResetTimeout;

    if (!track || slides.length === 0) {
        return;
    }

    const createClone = (slide) => {
        const clone = slide.cloneNode(true);

        clone.setAttribute('aria-hidden', 'true');
        clone.querySelectorAll('img').forEach((image) => {
            image.alt = '';
            image.removeAttribute('fetchpriority');
        });

        return clone;
    };

    track.prepend(createClone(slides[slides.length - 1]));
    track.append(createClone(slides[0]));
    track.style.setProperty('--home-slide-index', activeSlide);

    const resetToRealSlide = () => {
        if (activeSlide !== 0 && activeSlide !== slides.length + 1) {
            return;
        }

        window.clearTimeout(loopResetTimeout);
        activeSlide = activeSlide === 0 ? slides.length : 1;
        track.style.transition = 'none';
        track.style.setProperty('--home-slide-index', activeSlide);
        void track.offsetWidth;
        track.style.transition = '';
    };

    const showSlide = (index) => {
        window.clearTimeout(loopResetTimeout);
        activeSlide = index;
        track.style.setProperty('--home-slide-index', activeSlide);

        if (reducedMotion) {
            resetToRealSlide();
        } else if (activeSlide === 0 || activeSlide === slides.length + 1) {
            loopResetTimeout = window.setTimeout(resetToRealSlide, 1200);
        }
    };

    track.addEventListener('transitionend', (event) => {
        if (event.target === track && event.propertyName === 'transform') {
            resetToRealSlide();
        }
    });

    const restartAutoAdvance = () => {
        window.clearInterval(autoAdvance);

        if (!reducedMotion) {
            autoAdvance = window.setInterval(() => {
                if (!document.hidden) {
                    showSlide(activeSlide + 1);
                }
            }, 5000);
        }
    };

    hero.addEventListener('pointerdown', (event) => {
        if (event.target.closest('button, a')) {
            return;
        }

        startX = event.clientX;
    });

    hero.addEventListener('pointerup', (event) => {
        if (startX === null) {
            return;
        }

        const distance = event.clientX - startX;
        startX = null;

        if (Math.abs(distance) > 48) {
            showSlide(activeSlide + (distance < 0 ? 1 : -1));
            restartAutoAdvance();
        }
    });

    hero.addEventListener('pointercancel', () => {
        startX = null;
    });

    restartAutoAdvance();
});

const parallaxFrames = [...document.querySelectorAll('[data-parallax]')];
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (parallaxFrames.length && !prefersReducedMotion) {
    let parallaxFrameRequested = false;

    const updateParallax = () => {
        parallaxFrames.forEach((frame) => {
            const bounds = frame.getBoundingClientRect();
            const speed = Number(frame.dataset.parallax);
            const offset = Math.max(-28, Math.min(28, (window.innerHeight / 2 - bounds.top - bounds.height / 2) * speed));

            frame.style.transform = `translate3d(0, ${offset}px, 0)`;
        });

        parallaxFrameRequested = false;
    };

    const requestParallaxUpdate = () => {
        if (!parallaxFrameRequested) {
            window.requestAnimationFrame(updateParallax);
            parallaxFrameRequested = true;
        }
    };

    window.addEventListener('scroll', requestParallaxUpdate, { passive: true });
    window.addEventListener('resize', requestParallaxUpdate);
    requestParallaxUpdate();
}

document.querySelectorAll('.inquiry-form').forEach((form) => {
    const number = form.dataset.whatsappNumber;

    if (!number) {
        return;
    }

    const getValue = (name) => form.elements[name]?.value?.trim() ?? '';

    const getPillLabels = (name) => [...form.querySelectorAll(`input[name="${name}"]`)]
        .filter((input) => input.checked)
        .map((input) => input.nextElementSibling?.textContent.trim() || input.value);

    const formatDate = (value) => {
        if (!value) {
            return '';
        }

        const [year, month, day] = value.split('-').map(Number);

        if (!year || !month || !day) {
            return value;
        }

        return new Date(year, month - 1, day).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const vision = getValue('vision');
        const lines = ['Halo, saya ingin melakukan inquiry.', ''];

        const addLine = (label, value) => {
            if (value) {
                lines.push(`${label}: ${value}`);
            }
        };

        addLine('Nama', getValue('name'));
        addLine('Partner / Brand', getValue('partner_or_brand'));
        addLine('Email', getValue('email'));
        addLine('WhatsApp', getValue('whatsapp'));
        addLine('Instagram', getValue('instagram'));
        addLine('Jenis Session', getPillLabels('session_type[]').join(', '));
        addLine('Tanggal Shoot', formatDate(getValue('shoot_date')));
        addLine('Lokasi', getValue('location'));
        if (vision) {
            lines.push('', 'Cerita / moodboard:', vision);
        }

        const url = `https://wa.me/${number}?text=${encodeURIComponent(lines.join('\n'))}`;
        const opened = window.open(url, '_blank');

        if (opened) {
            opened.opener = null;
        } else {
            window.location.href = url;
        }
    });
});
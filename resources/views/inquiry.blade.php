@extends('layouts.app')

@section('title', 'Inquiry | AFL2 Webdev')

@section('content')
    <div class="inquiry-page">
        <aside class="inquiry-sidebar">
            <section class="inquiry-office" aria-labelledby="office-title">
                <h1 class="inquiry-heading" id="office-title">Office &amp; Studio.</h1>
                <address class="inquiry-address">
                    <span>Holder</span>
                    <span>Indonesia</span>
                </address>
            </section>

            <figure class="inquiry-portrait">
                <svg viewBox="0 0 420 480" role="img" aria-labelledby="portrait-title">
                    <title id="portrait-title">Ilustrasi fotografer sedang mengambil gambar</title>
                    <rect width="420" height="480" fill="#e8e5df" />
                    <path d="M0 353c54-42 111-63 177-55 71 8 118 46 243 24v158H0z" fill="#c6c1b8" />
                    <path d="M124 480c8-94 19-170 61-210 20-19 42-27 70-27 51 0 89 36 105 93l25 144z" fill="#353431" />
                    <path d="M197 226c-7-55 13-104 60-110 48-6 73 32 67 86-4 42-29 72-66 72-31 0-56-17-61-48" fill="#a49e95" />
                    <path d="M199 184c9-52 37-77 78-68 23 5 38 19 47 44-23-8-44-13-61-30-13 25-35 42-64 54" fill="#353431" />
                    <path d="M182 296c18-17 35-26 57-30l40 39 38-42c23 7 42 21 55 42l-58 52-28-28-29 30z" fill="#e1ddd5" />
                    <path d="m237 277 42 28 39-35 27 15-14 40-48 38-49-33-19-40z" fill="#8f8980" />
                    <path d="m274 292 57-39 49 12 20 47-27 22-43-25-40 24z" fill="#242321" />
                    <rect x="323" y="229" width="82" height="57" rx="8" transform="rotate(-18 323 229)" fill="#292826" />
                    <circle cx="359" cy="254" r="20" fill="#c8c3ba" />
                    <circle cx="359" cy="254" r="13" fill="#55534e" />
                    <circle cx="359" cy="254" r="7" fill="#20201f" />
                    <path d="m319 224 15-13 26-8 12 22z" fill="#292826" />
                    <path d="M0 0h420v480H0z" fill="none" stroke="#fff" stroke-width="16" />
                </svg>
                <figcaption>Behind every frame, there is a story.</figcaption>
            </figure>

            <section class="inquiry-contact-details" aria-labelledby="contact-title">
                <h2 class="inquiry-heading" id="contact-title">Let’s capture your story.</h2>
                <address class="inquiry-contacts">
                    <a href="tel:+6287898823735">Holder1</a>
                    <a href="tel:+628138080613">Holder2</a>
                    <a href="mailto:info.caliaphotos@gmail.com">Holder@gmail.com</a>
                </address>
            </section>
        </aside>

        <section class="inquiry-form-section" aria-labelledby="inquiry-title">
            <p class="inquiry-eyebrow">A LITTLE ABOUT YOUR PLANS</p>
            <h2 class="inquiry-form-title" id="inquiry-title">Inquire within.</h2>
            <p class="inquiry-intro">Tell us about your story, moodboard, or dream session. The more we know, the more personal we can make it.</p>

            <form class="inquiry-form">
                <div class="inquiry-name-fields">
                    <label class="inquiry-field">
                        <span>Your Name <span class="inquiry-required" aria-label="required">*</span></span>
                        <input type="text" name="name" autocomplete="name" placeholder="How should we call you?" required>
                    </label>
                    <label class="inquiry-field">
                        <span>Partner / Brand Name <span class="inquiry-optional">(optional)</span></span>
                        <input type="text" name="partner_or_brand" autocomplete="organization" placeholder="A name we should know">
                    </label>
                </div>

                <div class="inquiry-name-fields">
                    <label class="inquiry-field">
                        <span>Email <span class="inquiry-required" aria-label="required">*</span></span>
                        <input type="email" name="email" autocomplete="email" placeholder="you@example.com" required>
                    </label>
                    <label class="inquiry-field">
                        <span>WhatsApp Number</span>
                        <input type="tel" name="whatsapp" autocomplete="tel" placeholder="+62">
                    </label>
                </div>

                <label class="inquiry-field">
                    <span>Instagram Handle <span class="inquiry-optional">(optional)</span></span>
                    <input type="text" name="instagram" autocomplete="off" placeholder="@yourhandle">
                </label>

                <fieldset class="inquiry-fieldset">
                    <legend>What kind of session are you dreaming of? <span class="inquiry-required" aria-label="required">*</span></legend>
                    <div class="inquiry-pill-options">
                        <label class="inquiry-pill"><input type="checkbox" name="session_type[]" value="wedding" required><span>Wedding</span></label>
                        <label class="inquiry-pill"><input type="checkbox" name="session_type[]" value="pre-wedding"><span>Pre-wedding</span></label>
                        <label class="inquiry-pill"><input type="checkbox" name="session_type[]" value="portrait"><span>Portrait</span></label>
                        <label class="inquiry-pill"><input type="checkbox" name="session_type[]" value="commercial"><span>Commercial</span></label>
                        <label class="inquiry-pill"><input type="checkbox" name="session_type[]" value="editorial"><span>Editorial</span></label>
                    </div>
                </fieldset>

                <div class="inquiry-name-fields">
                    <label class="inquiry-field">
                        <span>Estimated Shoot Date</span>
                        <input type="date" name="shoot_date">
                    </label>
                    <label class="inquiry-field">
                        <span>Location / Venue</span>
                        <input type="text" name="location" placeholder="City, venue, or still deciding">
                    </label>
                </div>

                <fieldset class="inquiry-fieldset">
                    <legend>Estimated Budget <span class="inquiry-optional">(optional)</span></legend>
                    <div class="inquiry-pill-options">
                        <label class="inquiry-pill"><input type="radio" name="budget" value="3-5-million"><span>Rp 3jt – 5jt</span></label>
                        <label class="inquiry-pill"><input type="radio" name="budget" value="5-10-million"><span>Rp 5jt – 10jt</span></label>
                        <label class="inquiry-pill"><input type="radio" name="budget" value="10-million-plus"><span>Rp 10jt+</span></label>
                    </div>
                </fieldset>

                <label class="inquiry-field">
                    <span>Tell us about your story, moodboard, or dream session <span class="inquiry-required" aria-label="required">*</span></span>
                    <textarea name="vision" rows="4" placeholder="The feeling, the little details, the things that matter most to you..." required></textarea>
                </label>

                <div class="inquiry-submit-area">
                    <button class="inquiry-submit" type="button">Send Inquiry <span aria-hidden="true">→</span></button>
                    <p class="inquiry-response-note">
                        We typically respond within 24–48 hours. For urgent commercial bookings,
                        <a href="https://wa.me/6287898823735">reach us directly via WhatsApp</a>.
                    </p>
                </div>
            </form>
        </section>
    </div>
@endsection
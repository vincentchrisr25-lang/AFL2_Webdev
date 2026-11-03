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
                <img
                    src="{{ asset('assets/images/portrait.jpg') }}"
                    alt="Fotografer sedang mengambil gambar"
                    class="inquiry-portrait-img"
                />
                <figcaption>Behind every frame, there is a story.</figcaption>
            </figure>
        </aside>

        <section class="inquiry-form-section" aria-labelledby="inquiry-title">
            <p class="inquiry-eyebrow">A LITTLE ABOUT YOUR PLANS</p>
            <h2 class="inquiry-form-title" id="inquiry-title">Inquire within.</h2>
            <p class="inquiry-intro">
                Tell us about your story, moodboard, or dream session. The more we know, the more personal we can make
                it.
            </p>

            <form class="inquiry-form" data-whatsapp-number="6285748430957">
                <div class="inquiry-name-fields">
                    <label class="inquiry-field">
                        <span>Your Name <span class="inquiry-required" aria-label="required">*</span></span>
                        <input
                            type="text"
                            name="name"
                            autocomplete="name"
                            placeholder="How should we call you?"
                            required
                        />
                    </label>
                    <label class="inquiry-field">
                        <span>Partner / Brand Name <span class="inquiry-optional">(optional)</span></span>
                        <input
                            type="text"
                            name="partner_or_brand"
                            autocomplete="organization"
                            placeholder="A name we should know"
                        />
                    </label>
                </div>

                <div class="inquiry-name-fields">
                    <label class="inquiry-field">
                        <span>Email <span class="inquiry-required" aria-label="required">*</span></span>
                        <input type="email" name="email" autocomplete="email" placeholder="you@example.com" required />
                    </label>
                    <label class="inquiry-field">
                        <span>WhatsApp Number</span>
                        <input type="tel" name="whatsapp" autocomplete="tel" placeholder="+6285748430957" />
                    </label>
                </div>

                <label class="inquiry-field">
                    <span>Instagram Handle <span class="inquiry-optional">(optional)</span></span>
                    <input type="text" name="instagram" autocomplete="off" placeholder="@yourhandle" />
                </label>

                <fieldset class="inquiry-fieldset">
                    <legend>
                        What kind of session are you dreaming of?
                        <span class="inquiry-required" aria-label="required">*</span>
                    </legend>
                    <div class="inquiry-pill-options">
                        @foreach (['wedding' => 'Wedding', 'pre-wedding' => 'Pre-wedding', 'portrait' => 'Portrait', 'commercial' => 'Commercial', 'editorial' => 'Editorial'] as $value => $label)
                            <label class="inquiry-pill"
                                ><input
                                    type="checkbox"
                                    name="session_type[]"
                                    value="{{ $value }}"
                                    @if ($loop->first) required @endif
                                /><span>{{ $label }}</span></label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="inquiry-name-fields">
                    <label class="inquiry-field">
                        <span>Estimated Shoot Date</span>
                        <input type="date" name="shoot_date" />
                    </label>
                    <label class="inquiry-field">
                        <span>Location / Venue</span>
                        <input type="text" name="location" placeholder="City, venue, or still deciding" />
                    </label>
                </div>

                <label class="inquiry-field">
                    <span
                        >Tell us about your story, moodboard, or dream session
                        <span class="inquiry-required" aria-label="required">*</span></span>
                    <textarea
                        name="vision"
                        rows="4"
                        placeholder="The feeling, the little details, the things that matter most to you..."
                        required
                    ></textarea>
                </label>

                <div class="inquiry-submit-area">
                    <button class="inquiry-submit" type="submit">Send Inquiry <span aria-hidden="true">→</span></button>
                    <p class="inquiry-response-note">
                        We typically respond within 24–48 hours. For urgent commercial bookings,
                        <a href="https://wa.me/6285748430957">reach us directly via WhatsApp</a>.
                    </p>
                </div>
            </form>
        </section>
    </div>
@endsection

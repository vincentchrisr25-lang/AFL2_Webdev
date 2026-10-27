<?php

namespace Tests\Feature;

use Tests\TestCase;

class InquiryPageTest extends TestCase
{
    public function test_inquiry_page_shows_contact_details_and_contact_form(): void
    {
        $this->get('/inquiry')
            ->assertOk()
            ->assertSee('Office &amp; Studio.', false)
            ->assertSee('Let’s capture your story.')
            ->assertSee('Your Name')
            ->assertSee('name="partner_or_brand"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="whatsapp"', false)
            ->assertSee('name="instagram"', false)
            ->assertSee('name="session_type[]"', false)
            ->assertSee('Wedding')
            ->assertSee('Pre-wedding')
            ->assertSee('Portrait')
            ->assertSee('Commercial')
            ->assertSee('Editorial')
            ->assertSee('name="shoot_date"', false)
            ->assertSee('name="location"', false)
            ->assertSee('name="budget"', false)
            ->assertSee('Rp 3jt – 5jt')
            ->assertSee('Rp 5jt – 10jt')
            ->assertSee('Rp 10jt+')
            ->assertSee('name="vision"', false)
            ->assertSee('Send Inquiry')
            ->assertSee('We typically respond within 24–48 hours.')
            ->assertDontSee('name="subject"', false)
            ->assertDontSee('name="first_name"', false)
            ->assertDontSee('name="last_name"', false);
    }
}

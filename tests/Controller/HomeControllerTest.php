<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Homepage!');

        $client->clickLink('Contact Form');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Message")');
    }

    public function testContactFormSubmission()
    {
        $client = static::createClient();
        $client->request('GET', '/contact-form');
        $client->submitForm('Wyślij formularz', [
            'form[name]' => 'Jan',
            'form[email]' => 'jan@wp.pl',
            'form[phone]' => '511522533',
            'form[password]' => 'Test1234!',
            'form[message]' => 'Test message'
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("Dane przesłane z formularza przeszły walidację!")');
    }

    public function testContactForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact-form');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Contact Form!');
    }
}

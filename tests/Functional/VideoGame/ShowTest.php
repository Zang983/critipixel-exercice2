<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Tests\Functional\FunctionalTestCase;

final class ShowTest extends FunctionalTestCase
{
    public function testShouldShowVideoGame(): void
    {
        $this->get('http://127.0.0.1:8000/jeu-video-0');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Jeu vidéo 0');
    }
    public function testShouldPostReviewWithoutLogin(): void
    {
        $this->client->request('POST', '/jeu-video-49', ['content' => 'Super jeu!!!', 'rating' => 5]);
        self::assertResponseIsSuccessful();
        self::assertAnySelectorTextNotContains('div.d-flex.flex-column.gap-2.w-100.justify-content-start p.m-0', 'Super jeu!!!');
    }

    public function testShouldPostReviewWhenLogged(): void
    {
        $this->login();
        $this->get('/jeu-video-0');
        $form = [
            'review[comment]' => 'Super jeu !',
            'review[rating]' => 5,
        ];
        $this->client->submitForm('Poster', $form);
        self::assertResponseRedirects('/jeu-video-0');
        $this->client->followRedirect();
        self::assertSelectorTextContains('div.list-group-item:last-child h3', 'user+0');
        self::assertSelectorTextContains('div.list-group-item:last-child p', 'Super jeu !');
        self::assertSelectorTextContains('div.list-group-item:last-child span.value', '5');

    }
}
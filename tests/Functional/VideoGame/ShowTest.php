<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\List\VideoGameList\Filter;
use App\List\VideoGameList\Pagination;
use App\Model\Entity\Tag;
use App\Model\Entity\VideoGame;
use App\Tests\Functional\FunctionalTestCase;
use App\Model\ValueObject\Sorting;
use App\Model\ValueObject\Direction;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

final class ShowTest extends FunctionalTestCase
{
    public function testShouldShowVideoGame(): void
    {
        $this->get('http://127.0.0.1:8000/jeu-video-0');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Jeu vidéo 0');
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

    public function testShouldPostReviewWithoutLogin(): void
    {
        $this->client->request('POST', '/jeu-video-1', ['content' => 'Super jeu !', 'rating' => 5]);
        self::assertResponseRedirects('/auth/login');
        $this->client->followRedirect();
        self::assertSelectorTextContains('h1', 'Connectez-vous !');
    }


}
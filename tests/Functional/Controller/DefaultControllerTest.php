<?php
declare(strict_types=1);
/**
 * /tests/Functional/Controller/DefaultControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 *
 * @package App\Tests\Functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DefaultControllerTest extends WebTestCase
{
    public function testThatDefaultRouteReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $response = $client->getResponse();

        static::assertSame(200, $response->getStatusCode());
    }

    public function testThatHealthzRouteReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/healthz');

        $response = $client->getResponse();

        static::assertSame(200, $response->getStatusCode());
    }
}

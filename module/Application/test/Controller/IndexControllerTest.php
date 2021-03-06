<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApplicationTest\Controller;

use Application\Controller\IndexController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName(IndexController::class); // as specified in router's controller name alias
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $hits = [
            '2019-06-01' => count(file(__DIR__ . '/../../../../data/cache/2019-06-01.log')),
            '2019-06-02' => count(file(__DIR__ . '/../../../../data/cache/2019-06-02.log')),
            '2019-06-03' => count(file(__DIR__ . '/../../../../data/cache/2019-06-03.log')),
            '2019-06-04' => count(file(__DIR__ . '/../../../../data/cache/2019-06-04.log')),
        ];

        $this->assertContains('data-hits="' . htmlspecialchars(json_encode($hits)) . '"', $this->getResponse()->getContent());
    }

    /**
     * @group performances
     */
    public function testIndexActionPerformance()
    {
        $consumed = 0;
        $controller = new IndexController();

        for ($i = 0; $consumed < 1; $i++) {
            $m = microtime(true);
            $controller->indexAction();
            $consumed += microtime(true) - $m;
        }

        $this->assertGreaterThan(2000, $i, 'IndexController::indexAction should be at least ' . round(2000 / $i) . ' times faster');
    }

    public function testIndexActionViewModelTemplateRenderedWithinLayout()
    {
        $this->dispatch('/', 'GET');
        $this->assertQuery('.container .jumbotron');
    }

    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}

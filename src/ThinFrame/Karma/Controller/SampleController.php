<?php
namespace ThinFrame\Karma\Controller;

/**
 * Class DummyController
 *
 * @package ThinFrame\Karma\Controller
 * @see     http://symfony.com/doc/current/components/routing/introduction.html for routing paramters details
 * @since   0.1
 */
class SampleController extends BaseController
{
    /**
     * @Route {
     *          "names":"homePage",
     *          "path":"/",
     *          "default":[],
     *          "requirements":[],
     *          "options":[],
     *          "host":"",
     *          "schemes":[],
     *          "methods":[]
     * }
     */
    public function indexAction()
    {
        return 'Homepage of ThinFrame Karma';
    }

    /**
     * @Route {
     *          "names":"contactPage",
     *          "path":"contact"
     * }
     */
    public function contactAction()
    {
        return 'Send me a email at sorin.badea91@gmailcom';
    }
}
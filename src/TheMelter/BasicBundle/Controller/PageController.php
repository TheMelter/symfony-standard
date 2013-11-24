<?php
namespace TheMelter\BasicBundle\Controller;

class PageController extends BaseController
{

    /**
     * {@inheritDoc}
     */
    protected function init()
    {
    }

    /**
     * Test page.
     */
    public function testAction()
    {
        return $this->render('TheMelterBasicBundle::test.html.twig');
    }

}

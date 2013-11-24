<?php
namespace TheMelter\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class BaseController extends Controller
{

    protected $em;
    protected $sc;
    protected $request;
    protected $uploadDir;
    protected $session;

    /**
     * Overrides default setContainer and initializes default services.
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->em = $this->get('doctrine.orm.entity_manager');
        $this->request = $this->getRequest();
        $this->session = $this->request->getSession();
        $this->sc = $this->get('security.context');
        $this->uploadDir =  $this->get('kernel')->getRootDir() . '/../web/uploads/';
        $this->init();
    }

    /**
     * Returns entity class name.
     */
    protected function getEntityClassName($entity)
    {
        $classArr = explode('\\', get_class($entity));
        return end($classArr);
    }

    /**
     * Persists and flushes a single entity.
     */
    protected function persistEntity($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * Removes and flushes a single entity.
     */
    protected function removeEntity($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * Function to be overriden if necessary in derived classes.
     */
    protected function init() {}

    /**
     * Throws forbidden response.
     */
    protected function throwAccessDenied($message = "Forbidden!")
    {
        throw new AccessDeniedException($message);
    }

    /**
     * Shortcut for redirect.
     */
    protected function redirectTo($route, $options = array())
    {
        return $this->redirect($this->generateUrl($route, $options));
    }

    /**
     * Returns random name.
     */
    protected function generateRandomName($length = 15)
    {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz' 
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            . '01234567890'
        );
        shuffle($seed); 
        $rand = '';
        foreach (array_rand($seed, $length) as $k) 
            $rand .= $seed[$k];

        return $rand;
    }

}

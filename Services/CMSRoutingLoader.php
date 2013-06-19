<?php

namespace Bafford\SimpleCmsBundle\Services;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class CMSRoutingLoader extends Loader
{
    protected $em;
    
    public function __construct($doctrine)
    {
        $this->em = $doctrine->getManager();
    }
    
    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();
        
        $repo = $this->em->getRepository('BaffordSimpleCmsBundle:Page');
        $pages = $repo->findAll();
        foreach($pages as $page)
        {
            $collection->add('bafford_simplecms_' . $page->getSlug(), new Route($page->getUrl(), array(
                '_controller' => 'BaffordSimpleCmsBundle:Default:page',
                'id' => $page->getId(),
            )));
        }
        
        return $collection;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'bafford_simplecms' === $type;
    }
}
<?php

namespace App\Controller\Website;

use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;

/**
 * Default Controller for rendering templates, uses the themes from the ClientWebsiteBundle.
 */

class DefaultController extends WebsiteController
{
    /**
     * Loads the content from the request (filled by the route provider) and creates a response with this content and
     * the appropriate cache headers.
     *
     * @param bool $preview
     * @param bool $partial
     *
     * @return Response
     */
    public function indexAction(StructureInterface $structure, $preview = false, $partial = false): Response
    {
        $response = $this->renderStructure(
            $structure,
            [],
            $preview,
            $partial
        );

        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

        return $response;
    }
}
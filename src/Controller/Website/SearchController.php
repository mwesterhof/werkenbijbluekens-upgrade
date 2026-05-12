<?php

declare(strict_types=1);

namespace App\Controller\Website;

use Massive\Bundle\SearchBundle\Search\QueryHit;
use Massive\Bundle\SearchBundle\Search\SearchManagerInterface;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Sulu\Component\Content\Document\WorkflowStage;
use Sulu\Component\Content\Repository\ContentRepositoryInterface;
use Sulu\Component\Content\Repository\Mapping\MappingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SearchController
 *
 * @package App\Controller\Website
 */
class SearchController extends AbstractController
{
    private SearchManagerInterface $searchManager;
    private ContentRepositoryInterface $contentRepository;
    private MediaManagerInterface $mediaManager;

    public function __construct(
        SearchManagerInterface $searchManager,
        #[Autowire(service: 'sulu_page.content_repository')]
        ContentRepositoryInterface $contentRepository,
        MediaManagerInterface $mediaManager
    )
    {
        $this->searchManager = $searchManager;
        $this->contentRepository = $contentRepository;
        $this->mediaManager = $mediaManager;
    }

    public function results(Request $request): Response
    {
        // i18n:nl-excerpt-images
        $mapping = MappingBuilder::create()
            ->addProperties(
                [
                    'title',
                    'number_of_hours',
                    'locations',
                    'url',
                    'header_image',
                    'function_profile',
                    'excerpt-images',
                    'excerpt-description',
                    'authored'
                ]
            )
            ->getMapping();

        $massiveQuery = $this->searchManager->createSearch(
            '(' . implode(' OR ', ['_structure_type:joboffer']) . ')'
        );

        $massiveQuery->index('page_werkenbijbluekens_published');
        $massiveQuery->setLimit(100);
        /** @var QueryHit[] $res */
        $res = $massiveQuery->execute();


        $locations = $request->get('location', []);
        $functionProfile = $request->get('function_profile');

        if ($locations == '') {
            $locations = [];
        }
        if ($functionProfile == '') {
            $functionProfile = null;
        }
        if (is_string($locations)) {
            $locations = explode(',', $locations);
        }

        $results = [];
        foreach ($res as $result) {
            $jobOffer = $this->contentRepository->find($result->getId(), 'nl', 'werkenbijbluekens', $mapping);

            $data = $jobOffer->getData();

            $selected = false;

            $locationOfJobOffer = [];
            if (array_key_exists('locations', $data) && is_string($data['locations'])) {
                $locationOfJobOffer = explode(' ', $data['locations']);
            }

            // Fugly
            if (count($locations) === 0 && $functionProfile === null) {
                // default, nothing selected, so we show everything
                $selected = true;
            } elseif (
                count($locations) > 0 && count(array_intersect($locationOfJobOffer, $locations)) > 0
                && $functionProfile !== null && $data['function_profile'] === $functionProfile
            ) {
                $selected = true;
            } elseif (
                count($locations) > 0 && count(array_intersect($locationOfJobOffer, $locations)) > 0
                && $functionProfile === null
            ) {
                $selected = true;
            } elseif (
                count($locations) === 0 && $functionProfile !== null && $data['function_profile'] === $functionProfile
            ) {
                $selected = true;
            } elseif (count($locationOfJobOffer) === 0) {
                $selected = true;
            }


            if ($selected && $jobOffer->getRow()->getValue('state') === WorkflowStage::PUBLISHED) {
                $tmpResult = [
                    'url' => $data['url'],
                    'excerptTitle' => $data['title'],
                    'excerptDescription' => $data['excerpt-description'],
                    'number_of_hours' => $data['number_of_hours'],
                    'authored'  => $data['authored'],
                ];

                if (array_key_exists('locations', $data)) {
                    $tmpResult['locations'] = $data['locations'];
                }
                $media = json_decode($data['excerpt-images'], true);

                if (count($media['ids']) > 0) {
                    $tmpResult['excerptImage'] = $this->mediaManager->getById((int)$media['ids'][0], 'nl');
                }
                $results[] = $tmpResult;
            }
        }

        // code added with help of zbrag
        uasort($results, [$this, 'cmp']);

        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => true,
                'job_html' => $this->renderView('partials/job-listings-search.html.twig', [
                    'jobs' => $results,
                ]),
                'count' => count($results)
            ]);
        }
        return $this->render('partials/job-listings-search.html.twig', [
            'jobs' => $results,
        ]);
    }

    /**
     * @throws \Exception
     */
    private function cmp($a, $b): int
    {
        if (new \DateTime($a['authored']) == new \DateTime($b['authored'])) {
            return 0;
        }
        return (new \DateTime($a['authored']) > new \DateTime($b['authored'])) ? -1 : 1;
    }
}

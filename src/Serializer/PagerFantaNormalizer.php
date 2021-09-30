<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

use Pagerfanta\Pagerfanta;

class PagerFantaNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * You can add / remove / update any item after the object has been transformed into
     * array but BEFORE transformed to JSON.
     */
    public function normalize($pagerFanta, string $format = null, array $context = [])
    {
        $data = [];

        $items = $pagerFanta->getCurrentPageResults();
        foreach ($items as $i => $item) {
            if (is_object($item)) {
                $items[$i] = $this->normalizer->normalize($item, $format, $context);
            }
        }

        $data['results'] = $items;
        $data = array_merge($data, [
            'totalResults' => $pagerFanta->getNbResults(),
            'totalPages' => $pagerFanta->getNbPages(),
            'hasPreviousPage' => $pagerFanta->hasPreviousPage(),
            'hasNextPage' => $pagerFanta->hasNextPage(),
            'currentPage' => $pagerFanta->getCurrentPage(),
            'nbItemsPerPage' => $pagerFanta->getMaxPerPage(),
        ]);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Pagerfanta;
    }
}

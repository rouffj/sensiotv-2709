<?php

namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class UserNormalizer implements ContextAwareNormalizerInterface
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
    public function normalize($user, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($user, $format, $context);

        // TODO
        if (isset($data['id'])) {
            $data['user_url'] = '/api/users/' . $data['id'];
        }

        if (count($data['reviews']) > 0) {
            $data['nb_reviews'] = count($data['reviews']);

            // on affiche les reviews même si elle n'appartient à aucun groupe
            $data['reviews'] = $this->normalizer->normalize($user)['reviews'];
        } else {
            $data['reviews'] = [];
        }
        //unset($data['reviews']);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User;
    }
}

<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
        $context['circular_reference_handler'] = function($object, $format, $context) {
            return $object->getId();
        };
        $data = $this->normalizer->normalize($user, $format, $context);

        if (!is_array($data)) {
            // Gère le cas où le user est en circularReference. Ici data returnera le $userId
            return $data;
        }

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

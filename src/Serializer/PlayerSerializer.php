<?php


namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PlayerSerializer implements NormalizerInterface
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'username' => $object->getUsername(),
            'email' => $object->getEmail(),
            'roles' => $object->getRoles(),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof User;
    }
}
<?php


namespace App\Serializer;

use App\Entity\Party;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PartySerializer implements NormalizerInterface
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'player1' => $object->getPlayer1()->getUsername(),
            'player2' => $object->getPlayer2() ? $object->getPlayer2()->getUsername() : null,
            'choice1' => $object->getChoice1(),
            'choice2' => $object->getChoice2(),
            'whoWin' => $object->getWhoWin() ? $object->getWhoWin()->getUsername() : null,
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Party;
    }
}
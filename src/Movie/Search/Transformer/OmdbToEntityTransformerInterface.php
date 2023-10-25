<?php

namespace App\Movie\Search\Transformer;

interface OmdbToEntityTransformerInterface
{
    public function transform(mixed $value): object;
}

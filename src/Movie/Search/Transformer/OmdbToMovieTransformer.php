<?php

namespace App\Movie\Search\Transformer;

use App\Entity\Movie;

class OmdbToMovieTransformer implements OmdbToEntityTransformerInterface
{
    public const KEYS = [
        'Title',
        'Year',
        'Released',
        'Plot',
        'Country',
        'Poster',
        'Genre',
        'imdbID',
        'Rated'
    ];

    public function __construct(private readonly OmdbToGenreTransformer $genreTransformer)
    {
    }

    public function transform(mixed $value): Movie
    {
        if (!\is_array($value) || \count(array_diff(self::KEYS, \array_keys($value))) > 0) {
            throw new \InvalidArgumentException();
        }

        $date = $value['Released'] === 'N/A' ? '01-01-'.$value['Year'] : $value['Released'];

        return (new Movie())
            ->setTitle($value['Title'])
            ->setPlot($value['Plot'])
            ->setCountry($value['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPoster($value['Poster'])
            ->setImdbId($value['imdbID'])
            ->setRated($value['Rated'])
            ->setPrice(5.0)
        ;
    }
}

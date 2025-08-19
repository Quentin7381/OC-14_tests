<?php

namespace App\Tests\Rating;

use App\Model\Entity\VideoGame;
use PHPUnit\Framework\TestCase;
use App\Model\Entity\Review;
use App\Rating\RatingHandler;

class RatingHandlerTest extends TestCase
{

    public RatingHandler $ratingHandler;

    public function setUp(): void
    {
        $this->ratingHandler = new RatingHandler();
    }

    /**
     * @dataProvider videogameRatingProvider
     */
    public function testCalculateAverage(VideoGame $videoGame, ?int $expectedAverage): void
    {
        $this->ratingHandler->calculateAverage($videoGame);
        $this->assertEquals($expectedAverage, $videoGame->getAverageRating());
    }

    /**
     * @dataProvider videoGameRatingProvider2
     */
    public function testCountRatingsPerValue(VideoGame $videoGame, ?array $expectedRatings): void
    {
        $this->ratingHandler->countRatingsPerValue($videoGame);
        $ratings = $videoGame->getNumberOfRatingsPerValue();

        foreach ($expectedRatings as $value => $count) {
            $method = 'getNumberOf' . ucfirst($value);
            $this->assertEquals($count, $ratings->$method());
        }
    }

    public function videogameRatingProvider(): array
    {
        $videoGameWithNoReviews = new VideoGame();

        $videoGameWithOneReview = new VideoGame();
        $videoGameWithOneReview->getReviews()->add((new Review())->setRating(3));

        $videoGameWithMultipleReviews = new VideoGame();
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(1));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(2));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(3));

        return [
            'no reviews' => [
                $videoGameWithNoReviews,
                null,
            ],
            'one review' => [
                $videoGameWithOneReview,
                3,
            ],
            'multiple reviews' => [
                $videoGameWithMultipleReviews,
                2,
            ],
        ];
    }

    public function videoGameRatingProvider2(): array
    {
        $videoGameWithNoReviews = new VideoGame();

        $videoGameWithOneReview = new VideoGame();
        $videoGameWithOneReview->getReviews()->add((new Review())->setRating(3));

        $videoGameWithMultipleReviews = new VideoGame();
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(1));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(1));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(2));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(2));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(2));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(3));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(3));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(4));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(4));
        $videoGameWithMultipleReviews->getReviews()->add((new Review())->setRating(5));

        return [
            'no reviews' => [
                $videoGameWithNoReviews,
                [
                    'One' => 0,
                    'Two' => 0,
                    'Three' => 0,
                    'Four' => 0,
                    'Five' => 0,
                ],
            ],
            'one review' => [
                $videoGameWithOneReview,
                [
                    'One' => 0,
                    'Two' => 0,
                    'Three' => 1,
                    'Four' => 0,
                    'Five' => 0,
                ]
            ],
            'multiple reviews' => [
                $videoGameWithMultipleReviews,
                [
                    'One' => 2,
                    'Two' => 3,
                    'Three' => 2,
                    'Four' => 2,
                    'Five' => 1,
                ]
            ],
        ];
    }
}
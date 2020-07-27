<?php

/** @var Factory $factory */

use App\Helpers\ImageFaker;
use App\Models\Business\Product\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(/**
 * @param Faker $faker
 * @return array
 */ Product::class, function ( Faker $faker )
{
    $productName = $faker->unique()->sentence( $nbWords = 6, $variableNbWords = true );
    $productSlug = strSlug( $productName );

    return [
        'name' => $productName,
        'slug' => $productSlug,
        'description' => $faker->sentence,
        'price' => $faker->randomFloat( 2, 10, 500 ),
        'active' => true,
        'image' => ImageFaker::downloadImage(
            public_path( 'storage/images' ),
            640,
            480,
            null,
            false
        ),
    ];

});

/**
 * Generate a slug for the given string
 *
 * @param $string
 * @return string
 */
function strSlug( $string )
{
    return strtolower( str_replace( '.', '', str_replace( ' ', '-', $string ) ) );
}

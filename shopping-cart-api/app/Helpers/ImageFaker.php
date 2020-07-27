<?php namespace App\Helpers;

use Faker\Provider\Image;
use Illuminate\Support\Facades\Log;

class ImageFaker extends Image
{
    /**
     * Generate the URL that will return a random image
     *
     * Set randomize to false to remove the random GET parameter at the end of the url.
     *
     * @example 'http://lorempixel.com/640/480/?12345'
     *
     * @param int $width
     * @param int $height
     * @param string|null $category
     * @param bool $randomize
     * @param string|null $word
     * @param bool $gray
     *
     * @return string
     */
    public static function imageUrl($width = 640, $height = 480, $category = null, $randomize = true, $word = null, $gray = false)
    {
        $baseUrl = config( 'environment.FAKER_IMAGE_BASE_URL' );

        $url = "{$width}/{$height}/";

        if ($gray)
        {
            $url = "gray/" . $url;
        }

        if ($category)
        {
            if (! in_array($category, static::$categories))
            {
                throw new \InvalidArgumentException(sprintf('Unknown image category "%s"', $category));
            }

            $url .= "{$category}/";

            if ($word)
            {
                $url .= "{$word}/";
            }
        }

        if ($randomize)
        {
            $url .= '?' . static::randomNumber(5, true);
        }



        return $baseUrl . $url;
    }

    /**
     * Download a remote random image to disk and return its location
     *
     * Requires curl, or allow_url_fopen to be on in php.ini.
     * @param null $dir
     * @param int $width
     * @param int $height
     * @param null $category
     * @param bool $fullPath
     * @param bool $randomize
     * @return bool|\RuntimeException|string
     * @example '/path/to/dir/13b73edae8443990be1aa8f1a483bc27.jpg'
     */
    public static function downloadImage(
        $dir = null,
        $width = 640,
        $height = 480,
        $category = null,
        $fullPath = true,
        $randomize = true
    )
    {
        try
        {
            $dir = is_null($dir) ? sys_get_temp_dir() : $dir; // GNU/Linux / OS X / Windows compatible

            // Validate directory path
            if (! is_dir($dir) || ! is_writable($dir))
            {
                $old = umask(0);
                @mkdir($dir, 0777);
                umask($old);
            }

            // Generate a random filename. Use the server address so that a file
            // generated at the same time on a different server won't have a collision.
            $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
            $filename = $name .'.jpg';
            $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

            $url = static::imageUrl( $width, $height, $category, $randomize, null, false );

            // save file
            if (function_exists('curl_exec'))
            {
                // use cURL
                $fp = fopen($filepath, 'w');
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                $success = curl_exec($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
                fclose($fp);
                curl_close($ch);

                if (! $success)
                {
                    unlink($filepath);

                    // could not contact the distant URL or HTTP error - fail silently.
                    $baseUrl = config( 'environment.FAKER_IMAGE_BASE_URL' );

                    throw new \InvalidArgumentException(
                        "Something went wrong downloading the image from: $baseUrl. If you have the url over " .
                        "http please change it to https or in the other way. Then try running the migration again."
                    );
                }
            }
            elseif (ini_get('allow_url_fopen'))
            {
                // use remote fopen() via copy()
                copy($url, $filepath);
            }
            else
            {
                return new \RuntimeException(
                    "The image formatter downloads an image from a remote HTTP server. Therefore, it " .
                    "requires that PHP can request remote hosts, either via cURL or fopen()"
                );
            }

            return $fullPath ? $filepath : $filename;
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "ImageFaker.downloadImage: Something went wrong downloading the image Details:
                {$exception->getMessage()} "
            );

            throw new \InvalidArgumentException(
                "Something went wrong downloading the image, please check the application log. Fix any " .
                "possible issue if you can and try again. "
            );
        }
    }

}

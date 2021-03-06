<?php

namespace App\Providers;

use App\Models\WpLink;
use Cocur\Slugify\Slugify;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class PbcFormMailTemplateLayoutComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['partials.form_mail.social_media_icons'], function ($view) {

            $slugify = new Slugify();
            $links = WpLink::whereIn(
                'link_name',
                ['Facebook', 'Twitter', 'Instagram', 'Youtube', 'Twitch']
            )
                ->whereNotIn('link_url', ['#'])
                ->distinct()
                ->get();
            $social = [];
            // holder for checking if this link has already been added
            $found = [];
            if ($links) {
                foreach ($links as $key => $link) {
                    // check if we've already added this link.
                    if (array_key_exists($key, $social) && in_array($social[$key]['link_name'], $found)) {
                        continue;
                    }
                    $social[$key] = $link->toArray();
                    array_push($found, $social[$key]['link_name']);
                    $social[$key]['link_image'] = 'https://gigazonegaming.com/wp-content/uploads/2016/06/' . strtolower($social[$key]['link_name']) . '.png';
                    // check that the image exists, if not, then continue to the next icon
                    $data = @file_get_contents($social[$key]['link_image']);
                    if(!$data) {
                        unset($social[$key]);
                        continue;
                    }
                    $social[$key]['link_slug'] = $slugify->slugify($social[$key]['link_name']);

                    // make base64 image version of image
                    $social[$key]['link_image_type'] = pathinfo($social[$key]['link_image'], PATHINFO_EXTENSION);
                    $social[$key]['link_image_64'] = 'data:image/' . $social[$key]['link_image_type'] . ';base64,' . base64_encode($data);
                }
            }
            $view->with(['social' => $social]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

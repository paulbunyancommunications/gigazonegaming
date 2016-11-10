<?php

use Illuminate\Database\Seeder;

class PhotoRotatorCestImageSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // there's a tag that all the front page rotator images are in, the image post needs to be attached to.
        $termId = \DB::table('wp_terms')->where('slug', 'home-masthead-photo-rotator')->first();


        for ($i=1; $i <= 5; $i++) {
            // create image entry
            $image = \App\Models\WpPost::firstOrCreate([
                'post_name' => 'PhotoRotatorCest'.$i,
            ]);

            $image->post_author = 1;
            $image->post_date = \Carbon\Carbon::now()->toDateTimeString();
            $image->post_date_gmt = \Carbon\Carbon::now()->toDateTimeString();
            $image->post_title = 'PhotoRotatorCest'.$i;
            $image->post_status = 'inherit';
            $image->comment_status = 'open';
            $image->ping_status = 'closed';
            $image->ping_status = 'closed';
            $image->post_modified = \Carbon\Carbon::now()->toDateTimeString();
            $image->post_modified_gmt = \Carbon\Carbon::now()->toDateTimeString();
            $image->post_parent = 0;
            $image->guid = env('APP_URL') .'/testing_assets/PhotoRotatorCest'.$i.'.png';
            $image->post_type = "attachment";
            $image->post_mime_type = "image/png";
            $image->save();

            // create attachment in meta table
            $_wp_attached_file = \App\Models\WpPostMeta::firstOrCreate([
                'post_id' => 'PhotoRotatorCest'.$i,
                'meta_key' => '_wp_attached_file',
            ]);
            $_wp_attached_file->meta_value = 'testing_assets/PhotoRotatorCest'.$i.'.png';
            $_wp_attached_file->save();

            // create edit lock in meta table
            $_edit_lock = \App\Models\WpPostMeta::firstOrCreate([
                'post_id' => 'PhotoRotatorCest'.$i,
                'meta_key' => '_edit_lock',
            ]);

            $_edit_lock->meta_value = '1471958382:1';
            $_edit_lock->save();

            // create last edit meta
            $_edit_last = \App\Models\WpPostMeta::firstOrCreate([
                'post_id' => 'PhotoRotatorCest'.$i,
                'meta_key' => '_edit_last',
            ]);
            $_edit_last->meta_value = '1471958382:1';
            $_edit_last->save();

            // create meta data for image from serialized string
            $_wp_attachment_metadata = \App\Models\WpPostMeta::firstOrCreate([
                'post_id' => 'PhotoRotatorCest'.$i,
                'meta_key' => '_wp_attachment_metadata',
            ]);

            $_wp_attachment_metadata->meta_value = serialize([
                "width" => 1024,
                "height" => 410,
                "file" => "testing_assets/PhotoRotatorCest{$i}.png",
                "sizes" => [
                    "thumbnail" => [
                        "file" => "PhotoRotatorCest{$i}.png",
                        "width" => 1024,
                        "height" => 410,
                        "mime-type" => "image/png",
                    ],
                    "medium" => [
                        "file" => "PhotoRotatorCest{$i}.png",
                        "width" => 1024,
                        "height" => 410,
                        "mime-type" => "image/png",
                    ],
                    "medium_large" => [
                        "file" => "PhotoRotatorCest{$i}.png",
                        "width" => 1024,
                        "height" => 410,
                        "mime-type" => "image/png",
                    ],
                    "large" => [
                        "file" => "PhotoRotatorCest{$i}.png",
                        "width" => 1024,
                        "height" => 410,
                        "mime-type" => "image/png",
                    ],
                ],
                "image_meta" => [
                    "aperture" => "0",
                    "credit" => "",
                    "camera" => "",
                    "caption" => "",
                    "created_timestamp" => "0",
                    "copyright" => "",
                    "focal_length" => "0",
                    "iso" => "0",
                    "shutter_speed" => "0",
                    "title" => "",
                    "orientation" => "0",
                    "keywords" => [],
                ],
            ]);
            $_wp_attachment_metadata->save();

            // now attach the rotator tag to this post if it's not already

            $tagMeta = DB::table('wp_term_relationships')->where([
                ['object_id', '=', $image->ID],
                ['term_taxonomy_id', '=', $termId->term_id],
            ])->first();
            if (!$tagMeta) {
                DB::table('wp_term_relationships')->insert(
                    ['object_id' => $image->ID, 'term_taxonomy_id' => $termId->term_id]
                );
            }
        }
    }
}

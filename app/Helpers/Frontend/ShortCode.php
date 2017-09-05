<?php
namespace App\Helpers\Frontend;

/**
 * ShortCode
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Helpers\Frontend
 */

use Carbon\Carbon;
use Cocur\Slugify\Slugify;
use Pbc\Bandolier\Type\Arrays;

class ShortCode
{

    public function generateTournamentSignUpFormShortCode($attributes = [])
    {
        $slug = new Slugify();
        $attr = Arrays::defaultAttributes(
            [
                'tag' => 'build-form',
                'tournament-name' => '',
                'fields' => [],
                'new-line' => ',',
                'delimiter' => '|',
                'sign-up-open' => Carbon::now()->subDay(1)->toDateTimeString(),
                'sign-up-close' => Carbon::now()->addMonth(1)->toDateTimeString(),
                'headings' => 'Team Info|team-name,Team Captain|team-captain,Teammates|teammate-one-name'

            ],
            $attributes);

        $form_shortcode = '['.$attr['tag'].' name="' . $attr['tournament-name'] . '-sign-up" new_line="' . $attr['new-line'] . '" delimiter="' . $attr['delimiter'] . '" start="' . strtotime($attr['sign-up-open']) . '" expires="' . strtotime($attr['sign-up-close']) . '"';

        $questions = ' questions="';
        foreach ($attr['fields'] as $key => $value) {
            $questions .= $value[0] . $attr['delimiter'] . $value[2];
            if (isset($value[3]) && !empty($value[3])) {
                $questions .= $attr['delimiter'] . $value[3];
            }
            $questions .= $attr['new-line'];
        }
        $questions = rtrim($questions, $attr['new-line']);
        $questions .= '"';
        $form_shortcode .= $questions;

        // now put in the input fixes
        $inputs = ' inputs="';
        foreach ($attr['fields'] as $key => $value) {
            $inputs .= $slug->slugify($value[0]) . $attr['delimiter'] . $key . $attr['new-line'];
        }
        $inputs = rtrim($inputs, $attr['new-line']);
        $inputs .= '"';
        $form_shortcode .= $inputs;

        // set headings for form
        $headings = ' headings="'. $attr['headings'] .'"';
        $form_shortcode .= $headings;

        // close the short code
        $form_shortcode .= ']';

        return $form_shortcode;
    }

}
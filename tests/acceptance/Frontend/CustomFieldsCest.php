<?php

namespace Tests\Acceptance\Frontend;

use Cocur\Slugify\Slugify;

class CustomFieldsCest extends \BaseAcceptance
{
    public function _before(\AcceptanceTester $I)
    {
        parent::_before($I);
        $I->loginToWordpress($I, 'admin', 'password', 1);
    }

    public function _after(\AcceptanceTester $I)
    {
        parent::_after($I);
    }

    /**
     * Test when using a custom layout there's a class for that layout in the dom
     * @param \AcceptanceTester $I
     * @test
     * @group customFields
     */
    public function testWhenUsingACustomLayoutThereSAClassForThatLayoutInTheDom(\AcceptanceTester $I)
    {
        $layout = implode('-', $this->faker->words());
        $postProperties = [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'meta' => [
                ['checkOption','#postcustom-hide',''],
            ],
            'customFields' => [
                ['layout', $layout]
            ]
        ];
        $I->createAPost($I, $postProperties);

        $I->seeElementInDOM('.layout.layout-'.$layout);
    }


    /**
     * Test when using a full width layout that no sidebar is present
     * @param \AcceptanceTester $I
     * @test
     * @group customFields
     * @group sidebar
     */
    public function testWhenUsingAFullWidthLayoutThatNoSidebarIsPresent(\AcceptanceTester $I)
    {
        $layout = 'full-width';
        $postProperties = [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'meta' => [
                ['checkOption','#postcustom-hide',''],
            ],
            'customFields' => [
                ['layout', $layout]
            ]
        ];
        $I->createAPost($I, $postProperties);

        $I->dontSeeElement('.layout .sidebar');
    }



    /**
     * Test when using the sidebar layout sidebar is present with default elements
     * @param \AcceptanceTester $I
     * @test
     * @group customFields
     * @group sidebar
     */
    public function testWhenUsingTheSidebarLayoutSidebarIsPresentWithDefaultElements(\AcceptanceTester $I)
    {
        $layout = 'sidebar';
        $postProperties = [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'meta' => [
                ['checkOption','#postcustom-hide',''],
            ],
            'customFields' => [
                ['layout', $layout]
            ]
        ];
        $I->createAPost($I, $postProperties);

        $I->seeElement('.layout .sidebar');
        $I->seeElement('.layout .sidebar .sidebar-social-media');
        $I->seeElement('.layout .sidebar .sidebar-constant-contact-signup-button');
        $I->seeElement('.layout .sidebar .sidebar-highlight-links');
    }

    /**
     * Test when using a using the "display_as_a_page" custom field that the comments do not show by default
     * @param \AcceptanceTester $I
     * @test
     * @group customFields
     */
    public function testWhenUsingAUsingTheDisplayAsAPageCustomFieldThatTheCommentsDoNotShowByDefault(\AcceptanceTester $I)
    {
        $slugify = new Slugify();
        $postProperties = [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'meta' => [
                ['checkOption','#postcustom-hide',''],
            ],
            'customFields' => [
                ['display_as_a_page', "yes"]
            ]
        ];
        $I->createAPost($I, $postProperties);

        $I->dontSeeElement('#'.$slugify->slugify($postProperties['title']));
    }

    /**
     * Test when not using a using the "display_as_a_page" custom field that the comments will display if comments are turned on
     * @param \AcceptanceTester $I
     * @test
     * @group customFields
     */
    public function testWhenNotUsingAUsingTheDisplayAsAPageCustomFieldThatTheCommentsWillDisplayIfCommentsAreTurnedOn(\AcceptanceTester $I)
    {
        $slugify = new Slugify();
        $postProperties = [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'meta' => [
                ['checkOption','#postcustom-hide',''],
                ['checkOption','#commentstatusdiv-hide',''],
                ['checkOption','#comment_status',''],
            ],
            'customFields' => [
                ['display_as_a_page', "no"]
            ]
        ];
        $I->createAPost($I, $postProperties);

        $I->seeElement('#comments-for-'.$slugify->slugify($postProperties['title']));
    }


    /**
     * Test when not using a using the "display_as_a_page" custom field that the comments will not display if comments are turned off
     * @param \AcceptanceTester $I
     * @test
     * @group customFields
     */
    public function testWhenNotUsingAUsingTheDisplayAsAPageCustomFieldThatTheCommentsWillNotDisplayIfCommentsAreTurnedOff(\AcceptanceTester $I)
    {
        $slugify = new Slugify();
        $postProperties = [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'meta' => [
                ['checkOption','#postcustom-hide',''],
                ['checkOption','#commentstatusdiv-hide',''],
                ['uncheckOption','#comment_status',''],
            ],
            'customFields' => [
                ['display_as_a_page', "no"]
            ]
        ];
        $I->createAPost($I, $postProperties);

        $I->dontSeeElement('#comments-for-'.$slugify->slugify($postProperties['title']));
    }
}

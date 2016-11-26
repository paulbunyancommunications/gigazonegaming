<?php

namespace Tests\Acceptance;
use \AcceptanceTester;
use GigaZone\GigaZoneGamingBootstrap;

/**
 * Class WpUserAdditionalFieldsCest
 * @package Tests\Acceptance
 */
class WpUserAdditionalFieldsCest extends \BaseAcceptance
{
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->loginWithAdminUser($I);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
        $this->logoutOfWp($I);
    }


    /**
     * @test
     * @group additionalFields
     * @param AcceptanceTester $I
     */
    public function setAdditionalProfileFieldsTest(AcceptanceTester $I)
    {
        $I->wantTo('fill in the additional profile fields in the wp backend');
        $faker = \Faker\Factory::create();
        $fields = GigaZoneGamingBootstrap::extraProfileFields();
        $I->amOnPage('/wp/wp-admin/profile.php');
        $I->fillField(['id' => 'nickname'], $faker->userName);
        for($i=0; $i < count($fields); $i++) {
            $fieldValue = $faker->userName;
            $fieldProfileValue = $faker->url;
            $I->fillField(['id' => $fields[$i]], $fieldValue);
            $I->fillField(['id' => $fields[$i].'_profile'], $fieldProfileValue);
            $I->click(['id' => 'submit']);
            $I->see('Profile updated');
            $I->seeInField(['id' => $fields[$i]], $fieldValue);
            $I->seeInField(['id' => $fields[$i].'_profile'], $fieldProfileValue);
        }
    }

    /**
     * @test
     * @param AcceptanceTester $I
     * @group additionalFields
     *
     */
    public function dontSeeAdditionalProfileFieldsTest(AcceptanceTester $I)
    {
        $I->wantTo('leaving the additional profile fields in the wp backend blank will prevent the link from showing from the short code');
        $faker = \Faker\Factory::create();
        $fields = GigaZoneGamingBootstrap::extraProfileFields();
        $I->amOnPage('/wp/wp-admin/profile.php');
        $I->fillField(['id' => 'nickname'], $faker->userName);
        for($i=0; $i < count($fields); $i++) {
            $fieldValue = '';
            $fieldProfileValue = '';
            $I->fillField(['id' => $fields[$i]], $fieldValue);
            $I->fillField(['id' => $fields[$i].'_profile'], $fieldProfileValue);
            $I->click(['id' => 'submit']);
            // make a new post and check to see that the field are missing on page with the profile shortcode
            $I->amOnPage('/wp/wp-admin/post-new.php');
            $I->fillField(['id' => 'title'], $faker->sentence);
            $I->click(['id' => 'content-html']);
            $I->wait(1);
            $I->fillField(['id' => 'content'], '[user-profile id="'.$this->wpAdminUser['name'].'"]');
            $I->wait(3);
            $I->click(['id' => 'publish']);
            $I->see('Post published');
            $I->click('#sample-permalink a');
            $I->wait(1);
            $I->dontSeeElementInDOM(['class' => 'user-profile--'.$this->wpAdminUser['name'].'-'.$fields[$i]]);
            $I->dontSee(ucfirst($fields[$i]));
            $I->amOnPage('/wp/wp-admin/profile.php');
        }
    }

    /**
     * @test
     * @group additionalFields
     * @param AcceptanceTester $I
     */
    public function seeAdditionalProfileFieldsTest(AcceptanceTester $I)
    {
        $I->wantTo('fill in the additional profile fields in the wp backend and check that they show up on the front end with short code');
        $faker = \Faker\Factory::create();
        $fields = GigaZoneGamingBootstrap::extraProfileFields();
        $I->amOnPage('/wp/wp-admin/profile.php');
        $I->fillField(['id' => 'nickname'], $faker->userName);
        for($i=0; $i < count($fields); $i++) {
            $fieldValue = $faker->userName;
            $fieldProfileValue = $faker->url;
            $I->fillField(['id' => $fields[$i]], $fieldValue);
            $I->fillField(['id' => $fields[$i].'_profile'], $fieldProfileValue);
            $I->click(['id' => 'submit']);

            // make a new post and check to see that the field filled is on the page with the profile shortcode
            $I->amOnPage('/wp/wp-admin/post-new.php');
            $I->fillField(['id' => 'title'], $faker->sentence);
            $I->click(['id' => 'content-html']);
            $I->wait(1);
            $I->fillField(['id' => 'content'], '[user-profile id="'.$this->wpAdminUser['name'].'"]');
            $I->wait(3);
            $I->click(['id' => 'publish']);
            $I->see('Post published');
            $I->click('#sample-permalink a');
            $I->wait(1);
            $I->seeElementInDOM(['class' => 'user-profile--'.$this->wpAdminUser['name'].'-'.$fields[$i]]);
            $I->see(ucfirst($fields[$i]));
            $I->assertSame($fieldProfileValue, $I->grabAttributeFrom('.user-profile--'.$this->wpAdminUser['name'].'-'.$fields[$i].' a', 'href'));
            $I->amOnPage('/wp/wp-admin/profile.php');
        }
    }
}

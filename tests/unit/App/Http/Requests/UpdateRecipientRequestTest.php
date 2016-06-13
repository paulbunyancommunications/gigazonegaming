<?php
/**
 * UpdateRecipientRequestTest
 *
 * Created 6/8/16 8:39 PM
 * UpdateRecipientRequest class tests
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Unit\App\Http\Requests
 */

namespace App\Http\Requests;

class UpdateRecipientRequestTest extends \TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_is_authorized()
    {
        $request = new UpdateRecipientRequest();
        $this->assertTrue($request->authorize());
    }

    /**
     * @test
     */
    public function it_has_rules()
    {
        $request = new UpdateRecipientRequest();
        $this->assertArrayHasKey('email', $request->rules());
        $this->assertArrayHasKey('update-recipient', $request->rules());
    }


    /**
     * @test
     */
    public function it_has_email_rules()
    {
        $request = new UpdateRecipientRequest();
        $this->assertSame($request->rules()['email'], 'required|email|unique:update_recipients,email');
    }

    /**
     * @test
     */
    public function it_has_update_recipient_rules()
    {
        $request = new UpdateRecipientRequest();
        $this->assertSame($request->rules()['update-recipient'], 'required|in:yes');
    }
}

<?php

declare(strict_types=1);

namespace Forge\User\Tests\Acceptance;

use Forge\User\Tests\Support\AcceptanceTester;

final class RequestAcceptanceCest
{
    public function testRequestPage(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the request page.');
        $I->amOnPage('/request');

        $I->expectTo('see request page.');
        $I->seeInTitle('Password reset');
    }

    public function testRequestEmptyDataTest(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the request page.');
        $I->amOnPage('/request');

        $I->fillField('#requestform-email', '');

        $I->click('Send');

        $I->expectTo('see validations errors.');
        $I->see('This field is required.');
    }

    public function testRequestSubmitFormWrongData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the request page.');
        $I->amOnPage('/request');

        $I->fillField('#requestform-email', 'noExist');

        $I->click('Send');

        $I->expectTo('see validations errors.');
        $I->see('This value is not a valid email address.');

        $I->fillField('#requestform-email', 'noexist@mail.com');

        $I->click('Send');

        $I->expectTo('see validations errors.');
        $I->see('Email not found.');
    }

    public function testRequestSubmitFormSuccessData(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the request page.');
        $I->amOnPage('/request');

        $I->fillField('#requestform-email', 'administrator@example.com');

        $I->click('Send');

        $I->expectTo('see request success message.');
        $I->see('We have sent you an email with instructions for resetting your password.');
        $I->dontSeeLink('Password reset');
    }
}

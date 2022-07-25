<?php

declare(strict_types=1);

namespace Forge\User\Tests\Acceptance;

use Forge\User\Tests\Support\AcceptanceTester;

final class EmailChangeAcceptanceCest
{
    /**
     * @depends Forge\User\Tests\Acceptance\RegisterAcceptanceCest:testRegisterSuccessDataDefaultAccountConfirmationFalse
     */
    public function testEmailChangePage(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->fillField('#loginform-login', 'admin');
        $I->fillField('#loginform-password', '123456');

        $I->click('Send');

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->amGoingTo('go to the email change page.');
        $I->amOnPage('/email/change');
        $I->see('Change email');
    }

    public function testEmailChangeValidation(AcceptanceTester $I): void
    {
        $I->amGoingTo('go to the log in page.');
        $I->amOnPage('/login');

        $I->fillField('#loginform-login', 'admin');
        $I->fillField('#loginform-password', '123456');

        $I->click('Send');

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $I->amGoingTo('go to the email change page.');
        $I->amOnPage('/email/change');
        $I->see('Change email');

        $I->fillField('#emailchangeform-email', '');

        $I->click('Send');

        $I->expectTo('see register validation.');
        $I->see('This field is required.');

        $I->fillField('#emailchangeform-email', 'noexist');

        $I->click('Send');

        $I->expectTo('see register validation.');
        $I->see('This value is not a valid email address');
    }
}

<?php

declare(strict_types=1);

namespace Forge\User\Tests\Functional;

use Forge\User\Tests\Support\FunctionalTester;

final class ResendFunctionalCest
{
    public function testResendAccountConfirmationTrue(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings confirmation true.');
        $I->updateInDatabase('settings', ['confirmation' => true], ['id' => 1]);

        $I->amGoingTo('go to the resend page.');
        $I->amOnPage('/resend');

        $I->expectTo('see resend page.');
        $I->seeInTitle('Resend confirmation email');

        $I->amGoingTo('update settings confirmation false.');
        $I->updateInDatabase('settings', ['confirmation' => false], ['id' => 1]);
    }

    public function testResendAccountConfirmationTrueEmptyDataTest(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings confirmation true.');
        $I->updateInDatabase('settings', ['confirmation' => true], ['id' => 1]);

        $I->amGoingTo('go to the resend page.');
        $I->amOnPage('/resend');

        $I->submitForm('#form-recovery-resend', ['ResendForm[email]' => '']);

        $I->expectTo('see validations errors.');
        $I->see('This field is required.');

        $I->amGoingTo('update settings confirmation false.');
        $I->updateInDatabase('settings', ['confirmation' => false], ['id' => 1]);
    }

    public function testResendAccountConfirmationTrueSubmitFormWrongData(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings confirmation true.');
        $I->updateInDatabase('settings', ['confirmation' => true], ['id' => 1]);

        $I->amGoingTo('go to the resend page.');
        $I->amOnPage('/resend');

        $I->submitForm('#form-recovery-resend', ['ResendForm[email]' => 'noExist']);

        $I->expectTo('see validations errors.');
        $I->see('This value is not a valid email address.');

        $I->submitForm('#form-recovery-resend', ['ResendForm[email]' => 'noExist@example.com']);

        $I->expectTo('see validations errors.');
        $I->see('Email not found.');

        $I->amGoingTo('update settings confirmation false.');
        $I->updateInDatabase('settings', ['confirmation' => false], ['id' => 1]);
    }

    public function testResendAccountConfirmationTrueUserIsActive(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings confirmation true.');
        $I->updateInDatabase('settings', ['confirmation' => true], ['id' => 1]);

        $I->amGoingTo('go to the resend page.');
        $I->amOnPage('/resend');

        $I->submitForm('#form-recovery-resend', ['ResendForm[email]' => 'administrator100@example.com']);

        $I->expectTo('see validations errors.');
        $I->see('Email already confirmed.');

        $I->amGoingTo('update settings confirmation false.');
        $I->updateInDatabase('settings', ['confirmation' => false], ['id' => 1]);
    }

    /**
     * @depends Forge\User\Tests\Functional\RegisterFunctionalCest:testRegisterSuccessDataDefaultAccountSettingsConfirmationTrue
     */
    public function registrationResendEmailOptionsDefaultAccountConfirmationTrue(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings confirmation true.');
        $I->updateInDatabase('settings', ['confirmation' => true], ['id' => 1]);

        $I->amGoingTo('go to the resend page.');
        $I->amOnPage('/resend');

        $I->submitForm('#form-recovery-resend', ['ResendForm[email]' => 'administrator1@example.com']);

        $I->expectTo('see resend success message.');
        $I->see('A confirmation message has been sent to your email address');

        $I->expectTo('see to page log in.');
        $I->see('Send');

        $I->amGoingTo('update settings confirmation false.');
        $I->updateInDatabase('settings', ['confirmation' => false], ['id' => 1]);
    }
}

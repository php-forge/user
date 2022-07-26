<?php

declare(strict_types=1);

namespace Forge\User\Tests\Functional;

use Forge\User\Tests\Support\FunctionalTester;

final class RequestFunctionalCest
{
    public function testRequestSettingsPasswordRecoveryFalse(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings password recovery false.');
        $I->updateInDatabase('settings', ['passwordRecovery' => false], ['id' => 1]);

        $I->amGoingTo('go to the request page.');
        $I->amOnPage('/request');

        $I->expectTo('see request success message.');
        $I->see('We were unable to find the page "/en/request".');

        $I->amGoingTo('update settings password recovery true.');
        $I->updateInDatabase('settings', ['passwordRecovery' => true], ['id' => 1]);
    }

    public function testRequestAccountConfirmationTrue(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings confirmation true.');
        $I->updateInDatabase('settings', ['confirmation' => true], ['id' => 1]);

        $I->amGoingTo('go to the register page.');
        $I->amOnPage('/register');

        $I->expectTo('see registration register form.');
        $I->submitForm('#form-registration-register', [
            'RegisterForm[email]' => 'request@example.com',
            'RegisterForm[username]' => 'request',
            'RegisterForm[password]' => '123456',
            'RegisterForm[passwordVerify]' => '123456',
        ]);

        $I->amGoingTo('go to the request page.');
        $I->amOnPage('/request');

        $I->expectTo('see request form.');
        $I->submitForm('#form-recovery-request', [
            'RequestForm[email]' => 'request@example.com',
        ]);

        $I->expectTo('see error request form.');
        $I->see('Email not confirmed.');

        $I->amGoingTo('update settings confirmation false');
        $I->updateInDatabase('settings', ['confirmation' => false], ['id' => 1]);
    }
}

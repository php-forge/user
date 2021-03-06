<?php

declare(strict_types=1);

namespace Forge\User\Tests\Functional;

use Forge\User\Tests\Support\FunctionalTester;

final class ResetFunctionalCest
{
    public function testResetPasswordWithEmptyQueryParams(FunctionalTester $I): void
    {
        $I->amGoingTo('go to the reset page.');
        $I->amOnPage('/reset');

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    public function testResetPasswordWrongId(FunctionalTester $I): void
    {
        $id = 25;
        $code = '6f5d0dad53ef73e6ba6f01a441c0e602';

        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/reset' . '/' . $id . '/' . $code);

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    public function testResetPasswordWrongCode(FunctionalTester $I): void
    {
        $id = 100;
        $code = '6f5d0dad53ef73e6ba6f01a441c0e601';

        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/reset' . '/' . $id . '/' . $code);

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    public function testResetPasswordWithTokenExpired(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture user with token expired.');

        $this->recoveryTokenExpiredUser($I);

        $id = $I->grabColumnFromDatabase('token', 'identity_id', ['identity_id' => 101]);
        $token = $I->grabColumnFromDatabase('token', 'code');

        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/reset' . '/' . $id[0] . '/' . $token[0]);

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    public function testResetPasswordSuccessData(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture user recovery.');

        $this->resetUser($I);

        $id = $I->grabColumnFromDatabase('token', 'identity_id', ['identity_id' => 100]);
        $token = $I->grabColumnFromDatabase('token', 'code', ['identity_id' => 100]);

        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/reset' . '/' . $id[0] . '/' . $token[0]);

        $I->submitForm(
            '#form-recovery-reset',
            ['ResetForm[password]' => 'newpass', 'ResetForm[passwordVerify]' => 'newpass'],
        );


        $I->expectTo('see reset success message.');
        $I->see('Your password has been reset.');
    }

    private function resetUser(FunctionalTester $I): void
    {
        $time = time();

        $I->haveInDatabase(
            'account',
            [
                'identity_id' => 100,
                'username' => 'alex',
                'email' => 'alex@example.com',
                'password_hash' => '$argon2i$v=19$m=65536,t=4,p=1$ZVlUZk1NS2wwdi45d0t6dw$pn/0BLB3EzYtNdm3NSj6Yntk9lUT1pEOFsd85xV3Ig4',
                'created_at' => $time,
                'updated_at' => $time,
                'confirmed_at' => $time,
            ]
        );

        $I->haveInDatabase(
            'token',
            [
                'identity_id' => 100,
                'code' => '6f5d0dad53ef73e6ba6f01a441c0e602',
                'type' => 1,
                'created_at' => $time,
            ]
        );
    }

    private function recoveryTokenExpiredUser(FunctionalTester $I): void
    {
        $time = time();

        $I->haveInDatabase(
            'account',
            [
                'identity_id' => 101,
                'username' => 'andrew',
                'email' => 'andrew@example.com',
                'password_hash' => '$2y$13$qY.ImaYBppt66qez6B31QO92jc5DYVRzo5NxM1ivItkW74WsSG6Ui',
                'created_at' => $time - 90000,
                'updated_at' => $time - 90000,
                'confirmed_at' => $time - 90000,
            ]
        );

        $I->haveInDatabase(
            'token',
            [
                'identity_id' => 101,
                'code' => 'a5839d0e73b9c525942c2f59e88c1aaf',
                'type' => 1,
                'created_at' => $time - 90000,
            ]
        );
    }
}

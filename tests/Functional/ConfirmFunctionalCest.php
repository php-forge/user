<?php

declare(strict_types=1);

namespace Forge\User\Tests\Functional;

use Forge\User\Tests\Support\FunctionalTester;

final class ConfirmFunctionalCest
{
    public function testRegistrationConfirmWithEmptyQueryParams(FunctionalTester $I): void
    {
        $I->amGoingTo('go to the page confirm.');
        $I->amOnPage('/confirm');

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    public function testRegistationConfirmAccountConfirmationTrue(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user.');

        $this->unconfirmedUser($I);

        $id = $I->grabColumnFromDatabase('token', 'identity_id', ['identity_id' => 100]);
        $token = $I->grabColumnFromDatabase('token', 'code', ['identity_id' => 100]);

        $I->amGoingTo('page recovery confirm.');
        $I->amOnPage('/confirm' . '/' . $id[0] . '/' . $token[0]);

        $I->expectTo('see confirmation success message.');
        $I->see('Your account has been confirmed.');

        $I->expectTo('see logged index page.');
        $I->see('Hello!');
        $I->see("Let's start something great with Yii3!");

        $id = $I->grabColumnFromDatabase('token', 'identity_id', ['identity_id' => 100]);

        $I->expectTo('not finding the registered token in the database.');
        $I->assertEmpty($id);
    }

    public function testRegistationConfirmAccountWrongId(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user.');

        $id = 4;
        $code = 'NO2aCmBIjFQX624xmAc3VBu7Th3NJoa6';

        $I->amGoingTo('page recovery confirm.');
        $I->amOnPage('/confirm' . '/' . $id . '/' . $code);

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    public function testRegistationConfirmAccountWrongCode(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user.');

        $id = 100;
        $code = 'NO2aCmBIjFQX624xmAc3VBu7Th3NJoa1';

        $I->amGoingTo('page recovery confirm.');
        $I->amOnPage('/confirm' . '/' . $id . '/' . $code);

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    public function testRegistationConfirmAccountWithTokenExpired(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user with token expired.');

        $this->unconfirmedTokenExpiredUser($I);

        $id = $I->grabColumnFromDatabase('token', 'identity_id', ['identity_id' => 101]);
        $token = $I->grabColumnFromDatabase('token', 'code', ['identity_id' => 101]);

        $I->amGoingTo('page recovery confirm.');
        $I->amOnPage('/confirm' . '/' . $id[0] . '/' . $token[0]);

        $I->expectTo('error 404 response.');
        $I->seeResponseCodeIs(404);
    }

    private function unconfirmedUser(FunctionalTester $I): void
    {
        $time = time();

        $I->haveInDatabase(
            'identity',
            [
                'id' => 100,
                'auth_key' => 'mhh1A6KfqQLmHP-MiWN0WB0M90Q2u5I',
            ]
        );

        $I->haveInDatabase(
            'account',
            [
                'identity_id' => 100,
                'username' => 'joe',
                'email' => 'joe@example.com',
                'password_hash' => '$argon2i$v=19$m=65536,t=4,p=1$ZVlUZk1NS2wwdi45d0t6dw$pn/0BLB3EzYtNdm3NSj6Yntk9lUT1pEOFsd85xV3Ig4',
                'registration_ip' => '127.0.0.1',
                'created_at' => $time,
                'updated_at' => $time,
            ]
        );

        $I->haveInDatabase(
            'token',
            [
                'identity_id' => 100,
                'code' => 'NO2aCmBIjFQX624xmAc3VBu7Th3NJoa6',
                'type' => 0,
                'created_at' => $time,
            ]
        );

        $I->haveInDatabase(
            'profile',
            [
                'identity_id' => 100,
                'name' => 'Joe Dow',
                'public_email' => 'joedow@example.com',
            ]
        );
    }

    private function unconfirmedTokenExpiredUser(FunctionalTester $I): void
    {
        $time = time();

        $I->haveInDatabase(
            'identity',
            [
                'id' => 101,
                'auth_key' => 'mhh1A6KfqQLmHP-MiWN0WB0M90Q2u5OE',
            ]
        );

        $I->haveInDatabase(
            'account',
            [
                'identity_id' => 101,
                'username' => 'john',
                'email' => 'john@example.com',
                'password_hash' => '$2y$13$qY.ImaYBppt66qez6B31QO92jc5DYVRzo5NxM1ivItkW74WsSG6Ui',
                'created_at' => $time - 90000,
                'updated_at' => $time - 90000,
            ]
        );

        $I->haveInDatabase(
            'token',
            [
                'identity_id' => 101,
                'code' => 'qxYa315rqRgCOjYGk82GFHMEAV3T82AX',
                'type' => 0,
                'created_at' => $time - 90000,
            ]
        );
    }
}

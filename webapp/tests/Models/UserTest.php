<?php

namespace Tests\Models;

use App\Providers\UserServiceProvider;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetAuthIdentifierName()
    {
        $user = UserServiceProvider::getAUser();
        $this->assertEquals('userId', $user->getAuthIdentifierName());
    }

    public function testGetWrongAuthIdentifier()
    {
        $user = UserServiceProvider::getAUser();
        $this->assertNotEquals(null, $user->getAuthIdentifier());
    }
    public function testGetAuthIdentifier()
    {
        $user = UserServiceProvider::getAUser();
        $this->assertEquals('0', $user->getAuthIdentifier());
    }

    public function testGetRole()
    {
        $user = UserServiceProvider::getAUser();
        $this->assertEquals('Utente', $user->getRole());
    }
    public function testGetPassword()
    {
        $user = UserServiceProvider::getAUser();
        $this->assertEquals("password", $user->getAuthPassword());
    }
    public function testGetTelegramName()
    {
        $user = UserServiceProvider::getAUser();
        $this->assertEquals("pippo", $user->getTelegramName());
    }
    public function testGetChatId()
    {
        $user = UserServiceProvider::getAUser();
        $this->assertEquals("00000", $user->getChatId());
    }
    public function testDelete()
    {
        $user = UserServiceProvider::getAUser();
        $user->setDeleted(true);
        $this->assertEquals(true, $user->getDeleted());
    }
}

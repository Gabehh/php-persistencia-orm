<?php
/**
 * PHP version 7.3
 * tests/Entity/UserTest.php
 *
 * @category EntityTests
 * @package  MiW\Results\Tests
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Tests\Entity;

use MiW\Results\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 *
 * @package MiW\Results\Tests\Entity
 * @group   users
 */
class UserTest extends TestCase
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->user = new User();
    }

    /**
     * @covers \MiW\Results\Entity\User::__construct()
     */
    public function testConstructor(): void
    {
        self::assertEquals(
            null,
            $this->user->getUsername()
        );

        self::assertEquals(
            0,
            $this->user->getId()
        );


        self::assertEquals(
            null,
            $this->user->getEmail()
        );

        self::assertEquals(
            false,
            $this->user->isAdmin()
        );
    }

    /**
     * @covers \MiW\Results\Entity\User::getId()
     */
    public function testGetId(): void
    {
        self::assertEquals(
            0,
            $this->user->getId()
        );
    }

    /**
     * @covers \MiW\Results\Entity\User::setUsername()
     * @covers \MiW\Results\Entity\User::getUsername()
     */
    public function testGetSetUsername(): void
    {
        $this->user->setUsername("Gabriel");
        self::assertEquals(
            "Gabriel",
            $this->user->getUsername()
        );
    }

    /**
     * @covers \MiW\Results\Entity\User::getEmail()
     * @covers \MiW\Results\Entity\User::setEmail()
     */
    public function testGetSetEmail(): void
    {
        $this->user->setEmail("gabe3195@gmail.com");
        self::assertEquals(
            "gabe3195@gmail.com",
            $this->user->getEmail()
        );
    }

    /**
     * @covers \MiW\Results\Entity\User::setEnabled()
     * @covers \MiW\Results\Entity\User::isEnabled()
     */
    public function testIsSetEnabled(): void
    {
        $this->user->setEnabled(false);
        self::assertEquals(
            false,
            $this->user->isEnabled()
        );
    }

    /**
     * @covers \MiW\Results\Entity\User::setIsAdmin()
     * @covers \MiW\Results\Entity\User::isAdmin
     */
    public function testIsSetAdmin(): void
    {
        $this->user->setIsAdmin(true);
        self::assertEquals(
            true,
            $this->user->isAdmin()
        );
    }

    /**
     * @covers \MiW\Results\Entity\User::setPassword()
     * @covers \MiW\Results\Entity\User::validatePassword()
     */
    public function testSetValidatePassword(): void
    {
        $this->user->setPassword("password212");
        self::assertNotEquals(
            "pass",
            $this->user->validatePassword("password212")
        );
    }

    /**
     * @covers \MiW\Results\Entity\User::__toString()
     */
    public function testToString(): void
    {
        $this->user->setToken("1123124214");
        self::assertNotEquals(
            "1123124214",
            $this->toString()
        );
    }

    /**
     * @covers \MiW\Results\Entity\User::jsonSerialize()
     */
    public function testJsonSerialize(): void
    {
        $this->user->setUsername("gabe");
        $this->user->setEmail("gabe3195@gmail.com");
        $this->user->setPassword("231231");
        $_user = new User("gabe","gabe3195@gmail.com", 231231);
        $_user->jsonSerialize();
        self::assertEquals(
            $_user->jsonSerialize(),
            $this->user->jsonSerialize()
        );
    }
}

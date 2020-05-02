<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 04-Apr-20
 * Time: 5:37 AM
 */

require_once __DIR__ . '../../vendor/autoload.php';


use PHPUnit\Framework\TestCase;
use Src\Email;

final class EmailTest extends TestCase
{
    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $this->assertInstanceOf(
            Email::class,
            Email::fromString('user@example.com')
        );
    }

    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }
}
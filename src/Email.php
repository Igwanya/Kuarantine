<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 04-Apr-20
 * Time: 5:35 AM
 */

namespace Src;


use SebastianBergmann\RecursionContext\InvalidArgumentException;

require_once __DIR__ . '../../vendor/autoload.php';

final class Email
{
    private $email;

    private function __construct(string $email)
    {
        $this->ensureIsValidEmail($email);

        $this->email = $email;
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public function __toString(): string
    {
        return $this->email;
    }

    private function ensureIsValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                sprintf(
                    '"%s" is not a valid email address',
                    $email
                )
            );
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 28-Apr-20
 * Time: 4:03 PM
 */

namespace Src\Tests;

require_once __DIR__ . '../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Src\Login;


class LoginTest extends TestCase
{
    private $m_email = 'felixmuthui32@gmail.com';
    private $m_password = '$2y$10$LqWFL22ui/PNf51aN.dFe.jaQiC4EqUmgVdLm7CtgVdwi.NvYh2hm';

    public function testInvalidEmailReturnsFalseCheck(): bool
    {
        $login = new Login();
        $this->assertFalse($login->perform_email_check($this->m_email));
    }
    
}
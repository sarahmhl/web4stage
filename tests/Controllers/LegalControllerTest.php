<?php
// Verifie le comportement attendu du controleur qui affiche les pages legales.

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\LegalController;
use PHPUnit\Framework\TestCase;

final class LegalControllerTest extends TestCase
{
    public function testMentionsPageRendersExpectedContent(): void
    {
        $_SERVER['REQUEST_URI'] = '/projet web/public/index.php/mentions-legales';

        ob_start();
        (new LegalController())->mentions();
        $output = (string) ob_get_clean();

        $this->assertStringContainsString('Web4Stage', $output);
        $this->assertStringContainsString('Mentions', $output);
    }
}

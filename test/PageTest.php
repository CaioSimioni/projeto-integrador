<?php

namespace Dev\ProjetoIntegrador\Test;

use Dev\ProjetoIntegrador\Pages\Page;
use InvalidArgumentException;

require_once __DIR__ . '/../autoload.php';

class PageTest
{
    public function testRenderHeader()
    {
        $page = $this->getMockForAbstractClass(Page::class);
        $page->expects($this->any())
             ->method('cssLink')
             ->willReturn('/public/css/page.css');
        $page->expects($this->any())
             ->method('addLinksHead')
             ->willReturn('<link rel="stylesheet" href="/public/css/extra.css">');

        ob_start();
        $page->renderHeader();
        $output = ob_get_clean();

        if (strpos($output, '<!DOCTYPE html>') === false) {
            throw new \Exception('Header should contain <!DOCTYPE html>');
        }
        if (strpos($output, '<link rel="stylesheet" href="/public/css/page.css">') === false) {
            throw new \Exception('Header should contain the CSS link');
        }
        if (strpos($output, '<link rel="stylesheet" href="/public/css/extra.css">') === false) {
            throw new \Exception('Header should contain the extra CSS link');
        }
    }

    public function testRenderFooter()
    {
        $page = $this->getMockForAbstractClass(Page::class);

        ob_start();
        $page->renderFooter();
        $output = ob_get_clean();

        if (strpos($output, '</body></html>') === false) {
            throw new \Exception('Footer should contain </body></html>');
        }
    }

    public function testAddLinksHead()
    {
        $page = $this->getMockForAbstractClass(Page::class);
        $elements = [
            '<link rel="stylesheet" href="/public/css/extra1.css">',
            '<link rel="stylesheet" href="/public/css/extra2.css">',
            '<script src="/public/js/script.js"></script>'
        ];

        $result = $page->addLinksHead($elements);

        if (strpos($result, '<link rel="stylesheet" href="/public/css/extra1.css">') === false) {
            throw new \Exception('addLinksHead should contain the first CSS link');
        }
        if (strpos($result, '<link rel="stylesheet" href="/public/css/extra2.css">') === false) {
            throw new \Exception('addLinksHead should contain the second CSS link');
        }
        if (strpos($result, '<script src="/public/js/script.js"></script>') !== false) {
            throw new \Exception('addLinksHead should not contain the script tag');
        }
    }
}

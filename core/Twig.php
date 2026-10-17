<?php

namespace core;

require_once '../configbot.php';

use Closure;
use Twig\TwigFunction;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class Twig {

    private $twig;
    private $functions = [];


    public function __construct() {
        $this->twig = $this->loadTwig();
        $this->twig->addGlobal('CLIENT_ID_GOOGLE', CLIENT_ID_GOOGLE);
    }

    public function loadTwig() {
        $loader = new FilesystemLoader('../app/views');
        $this->twig = new Environment($loader, [
            'debug' => true,
            'auto_reload' => true
        ]);

        return $this->twig;
    }

    public function truncateText($text, $length = 30, $ellipsis = '...')
    {
        if (strlen($text) > $length) {
            return substr($text, 0, $length) . $ellipsis;
        }

        return $text;
    }

    private function functionsToView($name, Closure $callback) {
        return new TwigFunction($name, $callback);
    }

    public function loadFunctions() {
        if ($this->twig === null) {
            throw new \Exception('Twig environment is not initialized.');
        }

        require '../app/functions/twig.php';

        foreach ($this->functions as $key => $value) {
            $this->twig->addFunction($this->functions[$key]);
        }
    }

    public function getTwig() {
        return $this->twig;
    }
}
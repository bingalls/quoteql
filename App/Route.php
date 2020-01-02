<?php

namespace App;

class Route
{
    /** @var string */
    public const HOME = 'Home';
    /** @var string */
    public const SUFFIX = 'Ctrl';


    /** @var string */
    private $action = '';
    /** @var string */
    private $ctrl = self::HOME . self::SUFFIX;
    /** @var array<string> */
    private $variables = [];

    public function __construct(string $path)
    {
        $this->parseRoute($path);
    }

    /**
     * Action from 2nd segment of URL
     * Defaults to current HTTP 'get' or 'post'
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Required Controller from start of URL
     * Defaults to "Home"
     * @return string
     */
    public function getCtrl(): string
    {
        return $this->ctrl;
    }

    /**
     * Any key/values at end of URL
     * @return array<string>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * Load ReST query from URL
     * This custom router, unlike standard ReST, is limited to 32 segments.
     * Any additional segments are ignored for security & performance.
     *
     * @param string $path
     * <li>Controller (required, empty defaults to Home)
     * <li>Action (optional, empty defaults to HTTP method)
     * <li>
     * <li>Each optional parameter
     * <li>Each optional value
     * <li>Each optional parameter
     * <li>Each optional value ...
     *
     * Currently init capital for Controller redirects to its lowercase path
     *
     * @return void
     */
    private function parseRoute(string $path): void
    {
        $segments = explode('/', escapeshellcmd($path), 32);    //limit of 15 key/values for security
        array_shift($segments);

        // convert snake_case to PascalCase
        $this->ctrl = str_replace('_', '', ucwords(((string)array_shift($segments)), '_'));

        if ($this->ctrl === "''") {
            $this->ctrl = self::HOME . self::SUFFIX;
            return;
        }
        $this->ctrl .= self::SUFFIX;

        if (count($segments)) {
            $this->action = array_shift($segments);
        } else {
            return;
        }
        while (count($segments)) {
            $segment = array_shift($segments);
            $this->variables[] = $segment;
            $this->variables[$segment] = array_shift($segments);
        }
    }
}

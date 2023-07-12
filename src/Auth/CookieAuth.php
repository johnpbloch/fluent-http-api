<?php

namespace JohnPBloch\FluentApi\Auth;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Cookie\SetCookie;

class CookieAuth implements AuthInterface
{
    public function __construct(private string $name, private string $value, private string $domain)
    {
    }

    public function addAuthToRequestConfig(array $config): array
    {
        if (empty($config['cookies']) || !$config['cookies'] instanceof CookieJarInterface) {
            $config['cookies'] = new CookieJar();
        }
        $config['cookies']->setCookie(new SetCookie([
            'Domain' => $this->domain,
            'Name' => $this->name,
            'Value' => $this->value,
            'Discard' => true,
        ]));
        return $config;
    }
}

<?php

namespace Src\Traits;

use Src\RSAEncryption;

trait RSAEncryptionTrait
{
    private function getRSAEncryption(): RSAEncryption
    {
        return new RSAEncryption(PRIVATE_KEY);
    }

    private function decrypt(string $data): mixed
    {
        return $this->getRSAEncryption()->decrypt($data);
    }

    private function encrypt(string $data): string
    {
        return $this->getRSAEncryption()->encrypt($data);
    }
}
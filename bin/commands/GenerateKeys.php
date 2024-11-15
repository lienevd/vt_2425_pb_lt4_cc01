<?php

namespace Bin\Commands;

use Src\RSAEncryption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateKeys extends Command
{
    protected static $defaultName = 'generate-keys';

    protected function configure(): void
    {
        $this->setName(self::$defaultName);
        $this
            ->setDescription('Generates a new RSA public and private key pair.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rsaEncryption = new RSAEncryption();

        $privateKey = $rsaEncryption->getPrivateKey();
        $publicKey = $rsaEncryption->getPublicKey();

        $output->writeln('Private Key:');
        $output->writeln($privateKey);

        $output->writeln('Public Key:');
        $output->writeln($publicKey);

        return Command::SUCCESS;
    }
}
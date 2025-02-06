<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\JsonResponse;
use App\Services\TonApiClient;
use App\Services\TonClient;
use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Olifanton\Interop\KeyPair;
use Olifanton\Mnemonic\TonMnemonic;
use Olifanton\Ton\Contracts\Wallets\Wallet;
use Olifanton\Ton\Transports\Toncenter\ClientOptions;
use Olifanton\Ton\Transports\Toncenter\ToncenterHttpV2Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class TonAction implements RequestHandlerInterface
{
    private ToncenterHttpV2Client $tonCenterClient;
    private Wallet $wallet;
    private KeyPair $keyPair;

    public function __construct()
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $mnemonic = 'fossil alert evoke travel relief amazing escape marine bread test fine ripple call hidden woman dice cruise wagon object tenant camera father dance merit';
        $client = new TonApiClient(
            tatumApiKey: '7b190682ac904de6e6e508cfbc86d8311ec41b7497f76a6b873b6c4f6149155d',
            mnemonic: explode(' ', $mnemonic),
            testMode: true
        );

        $address = '0QCxxdLsVRwdzoXYB9fC69cMStjhkbIbCC6glubn_hOHHwwq'; // Тестовый адрес
        $balance = $client->getBalance($address);
        var_dump($balance);exit();

        $transactions = $client->getTransactions($address);


        return new JsonResponse(['balance' => 'SUCCESS']);
    }
}
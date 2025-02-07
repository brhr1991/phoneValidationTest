<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\JsonResponse;
use App\Services\TonApiClient;
use Olifanton\Interop\KeyPair;
use Olifanton\Ton\Contracts\Wallets\Wallet;
use Olifanton\Ton\Transports\Toncenter\ToncenterHttpV2Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class TonAction implements RequestHandlerInterface
{
    public function __construct()
    {
    }
// home 0QCxxdLsVRwdzoXYB9fC69cMStjhkbIbCC6glubn_hOHHwwq
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $mnemonic = 'fossil alert evoke travel relief amazing escape marine bread test fine ripple call hidden woman dice cruise wagon object tenant camera father dance merit';

        $client = new TonApiClient(
            tatumApiKey: '7b190682ac904de6e6e508cfbc86d8311ec41b7497f76a6b873b6c4f6149155d',
            mnemonic: explode(' ', $mnemonic),
            testMode: true
        );

        $address = '0QCciRsmZ-58lAichaN6_mMqZSJHTDq477hO52-sBXOQ26us'; // Тестовый адрес

        try {
            $result = $client->sendTransaction(
                address: '0QDqUgM7qGd0txRl_zxKA8Z7H2H4cnuaLj_gyLxXnlInpPOT',
                amount: "0.2",
                message: "start testing",
            );
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        var_dump($result);exit();
//        $balance = $client->getBalance($address)->toFloat();
//        $wi = $client->getAddressInformation($address);
//        var_dump($balance);exit();
//        $transactions = $client->getTransactions($address);

        exit();
        return new JsonResponse(['balance' => 'SUCCESS']);
    }
}
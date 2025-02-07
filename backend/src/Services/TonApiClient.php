<?php

declare(strict_types=1);

namespace App\Services;

use Brick\Math\BigNumber;
use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Olifanton\Interop\Address;
use Olifanton\Interop\Bytes;
use Olifanton\Interop\KeyPair;
use Olifanton\Interop\Units;
use Olifanton\Mnemonic\TonMnemonic;
use Olifanton\Ton\AddressState;
use Olifanton\Ton\Contracts\Wallets\Transfer;
use Olifanton\Ton\Contracts\Wallets\TransferOptions;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4Options;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4R2;
use Olifanton\Ton\SendMode;
use Olifanton\Ton\Transports\Toncenter\ClientOptions;
use Olifanton\Ton\Transports\Toncenter\ToncenterHttpV2Client;
use Olifanton\Ton\Transports\Toncenter\ToncenterTransport;

class TonApiClient
{
    private ToncenterHttpV2Client $tonCenter;
    private WalletV4R2 $wallet;
    private KeyPair $keyPair;

    public function __construct(
        string $tatumApiKey,
        array  $mnemonic,
        bool   $testMode = false
    )
    {
        $httpClient = new HttpMethodsClient(
            Psr18ClientDiscovery::find(),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory()
        );

        $this->tonCenter = new ToncenterHttpV2Client(
            $httpClient,
            new ClientOptions(
                ClientOptions::TEST_BASE_URL,
                ''
            )
        );

        $this->keyPair = TonMnemonic::mnemonicToKeyPair($mnemonic);
        $walletOptions = new WalletV4Options($this->keyPair->publicKey);
        $this->wallet = new WalletV4R2($walletOptions);

    }


    public function getBalance(string $address): BigNumber
    {
        $balance = $this->tonCenter->getAddressBalance(new Address($address));

        return Units::fromNano($balance);
    }

    public function sendTransaction(
        string $address,
        string $amount,
        string $message
    ): string
    {
        $transport = new ToncenterTransport($this->tonCenter);
        $seQno = $this->wallet->seqno($transport);
        $address = new Address($address);

        // Создаем сообщение для трансфера
        $transferMessage = $this->wallet->createTransferMessage(
            [
                new Transfer(
                    $address,
                    Units::toNano($amount),
                    $message,
                    SendMode::PAY_GAS_SEPARATELY,
                )
            ],
            new TransferOptions($seQno ?? 0),
        );

        // Сначала подписываем сообщение
        $signedMessage = $transferMessage->sign($this->keyPair->secretKey);

        // Затем отправляем подписанное сообщение
        $transport->send($signedMessage->toBoc(false));

        // Возвращаем хеш транзакции
        return Bytes::bytesToBase64($signedMessage->hash());
    }

    public function getTransactions(
        string  $address,
        ?int    $limit = null,
        ?int    $lt = null,
        ?string $hash = null,
        ?int    $toLt = null
    ): array
    {
        return $this->tonCenter->getTransactions(
            new Address($address),
            $limit,
            $lt,
            $hash,
            $toLt
        )->items;
    }
}
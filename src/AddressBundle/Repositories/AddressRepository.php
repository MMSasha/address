<?php

namespace AddressBundle\Repositories;

use AddressBundle\Entities\Address;

class AddressRepository
{
    const HOST = 'localhost';
    const PORT = 5432;
    const DB_NAME = 'forecast';
    const USER = 'postgres';
    const PASSWORD = 'postgres';

    private $connection;

    public function __construct()
    {
        $this->connection = pg_connect(
            sprintf(
                "host=localhost port=5432 dbname=forecast user=postgres password=postgres",
                self::HOST,
                self::PORT,
                self::DB_NAME,
                self::USER,
                self::PASSWORD
            )
        );
    }

    private function fromArrayToEntity(array $data): Address
    {
        return new Address(
            $data['corp_id'], $data['street'], $data['number'], $data['city'], $data['org_id']
        );
    }

    /**
     * @param Address $address
     * @return Address[]
     */
    public function getPossibleAddresses(Address $address): array
    {
        $query = sprintf(
            "select * from address where number = %s and city = '%s' and corp_id = %s AND org_id != %s",
            $address->getNumber(),
            $address->getCity(),
            $address->getCorpId(),
            $address->getOrgId()
        );
        $data = pg_query($this->connection, $query);

        $possibleAddresses = [];

        while ($possibleAddress = pg_fetch_array($data, null, PGSQL_ASSOC)) {
            $possibleAddresses[] = $this->fromArrayToEntity($possibleAddress);
        }

        return $possibleAddresses;
    }

    /**
     * @return Address[]
     */
    public function getAllAddresses(): array
    {
        $data = pg_query($this->connection, "select * from address limit 1000");
        $addresses = [];

        while ($address = pg_fetch_array($data, null, PGSQL_ASSOC)) {
            $addresses[] = $this->fromArrayToEntity($address);
        }

        return $addresses;
    }
}
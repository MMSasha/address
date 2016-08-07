<?php

namespace AddressBundle\Entities;

class Address
{
    /** @var int */
    private $corpId;
    /** @var string */
    private $street;
    /** @var int */
    private $number;
    /** @var string */
    private $city;
    /** @var Address[] */
    private $similarAddresses;
    /** @var int */
    private $orgId;
    /** @var float */
    private $percentage;

    public function __construct(
        $corpId,
        $street,
        $number,
        $city,
        $orgId
    )
    {
        $this->corpId = $corpId;
        $this->street = $street;
        $this->number = $number;
        $this->city = $city;
        $this->orgId = $orgId;
    }

    public function getCorpId(): int
    {
        return $this->corpId;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function addSimilarAddress(Address $address): Address
    {
        $this->similarAddresses[] = $address;
        return $this;
    }

    /**
     * @return Address[]|null
     */
    public function getSimilarAddresses()
    {
        return $this->similarAddresses;
    }

    public function getOrgId(): int
    {
        return $this->orgId;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): Address
    {
        $this->percentage = $percentage;
        return $this;
    }
}
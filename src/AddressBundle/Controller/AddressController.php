<?php

namespace AddressBundle\Controller;

use AddressBundle\Entities\Address;
use AddressBundle\Repositories\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AddressController extends Controller
{
    const MIN = 0.7;

    /** @var AddressRepository */
    private $addressRepository;

    public function __construct()
    {
        $this->addressRepository = new AddressRepository();
    }

    public function indexAction()
    {
        try {
            $addresses = $this->getAllData();
        } catch (\Exception $ex) {
            return new \HttpResponseException($ex->getMessage());
        }

        return $this->render('@Address/Default/addresses.html.twig',
            [
                'addresses' => $addresses,
            ]
        );
    }

    /**
     * @return Address[]
     */
    private function getAllData(): array
    {
        $addresses = $this->addressRepository->getAllAddresses();

        foreach ($addresses as $address) {
            $possibleAddresses = $this->addressRepository->getPossibleAddresses($address);

            foreach ($possibleAddresses as $possibleAddress) {
                $levensteinDistance = levenshtein($address->getStreet(), $possibleAddress->getStreet());
                $percentage = 1 - ($levensteinDistance / strlen($address->getStreet()));

                if ($percentage > self::MIN) {
                    $possibleAddress->setPercentage($percentage);
                    $address->addSimilarAddress($possibleAddress);
                }
            }
        }

        return $addresses;
    }
}

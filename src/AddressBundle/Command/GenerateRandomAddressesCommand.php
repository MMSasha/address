<?php

namespace AddressBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRandomAddressesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('generate:addresses')
            ->setDescription('Generate street addresses');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getConnection();
        $values = [];
        $cities = $this->getCityNames();
        for ($i = 0; $i < 100000; $i++) {
            $value = sprintf(
                "(%s, %s, %s, %s)",
                array_rand($cities, 1),
                $this->getRandomStreet(),
                rand(1, 10),
                rand(1, 50)
            );
            $values[] = $value;
        }
        $query = sprintf(
            "INSERT INTO address(city, street, number, corp_id) VALUES %s",
            implode(',', $values)
        );
        pg_query($connection, $query);

    }

    private function getRandomStreet()
    {
        $streets = $this->getStreetNames();
        $streetIndex = rand(0, count($streets) - 1);
        $randomStreet = $streets[$streetIndex];
        $randDifference = rand(0, strlen($randomStreet));
        $allChars = str_split($randomStreet);
        for ($d = 0; $d < $randDifference; $d++) {
            if ($allChars[$d] !== ' ') {
                $allChars[$d] = chr(ord($allChars[$d]) + 1);
            }
        }

        return "'" . implode('', $allChars) . "'";
    }

    private function getStreetNames()
    {
        $streets = ['ocust Street', 'South Street', 'Colonial Avenue', 'Cedar Lane', 'Summit Avenue', 'Canterbury Court', 'Clay Street', 'Hillcrest Drive', 'Maiden Lane', '2nd Street', 'Green Street', 'Hillcrest Avenue', 'Pennsylvania Avenue', 'Tanglewood Drive', 'Pleasant Street', '3rd Avenue', 'Elmwood Avenue', 'Smith Street', 'James Street', 'Garfield Avenue', 'Poplar Street', 'Briarwood Drive', 'Route 44', 'Forest Street', 'Grant Avenue', 'Broad Street West', 'Berkshire Drive', 'Route 29', 'Willow Drive', '3rd Street North', 'Madison Street', 'Country Lane', 'Bridge Street', 'John Street', 'Cottage Street', 'Queen Street', 'Evergreen Drive', 'Lafayette Avenue', 'Euclid Avenue', 'Schoolhouse Lane', 'Union Street', '4th Street', 'Spring Street', 'Mulberry Court', '6th Avenue', '1st Avenue', 'Lake Avenue', 'Wood Street', 'Homestead Drive', 'Meadow Lane', 'Park Drive', 'Ridge Road', 'Westminster Drive', 'Overlook Circle', '2nd Street North', 'Hawthorne Avenue', 'Roosevelt Avenue', 'West Street', 'Ashley Court', 'Railroad Street', 'Magnolia Avenue', 'Sycamore Street', '6th Street West', 'Williams Street', 'Sycamore Drive', 'Spruce Avenue', 'Valley View Drive', 'Cooper Street', 'Ann Street', 'Lilac Lane', 'Aspen Court', 'Buckingham Drive', '1st Street', 'Route 1', 'Sunset Avenue', 'Garden Street', 'Hanover Court', '5th Street West', 'Andover Court', 'Forest Avenue', 'New Street', 'Jones Street', 'Lantern Lane', 'Harrison Avenue', 'Rose Street', 'Cleveland Avenue', 'Church Road', 'Creek Road', 'Eagle Street', 'Cambridge Drive', 'Willow Avenue', 'Deerfield Drive', 'Lake Street', 'Lincoln Avenue', 'Jackson Street', 'Lakeview Drive', 'King Street', 'Beech Street', 'Henry Street', 'Division Street'];

        return $streets;
    }

    private function getCityNames()
    {
        $cities = ["Bozgos", "Krivachenko", "Nevklya", "Grigorevka", "Zamlicze", "Men’kovka", "Polstvin", "Sloboda Shlyshkovetskaya", "Khopniv", "Novo Dmitrovka", "Dar’ino-Yermakovka", "Kostyanka", "Blyumenfel’d", "Старий Яричів", "Solomka", "Mikhal’cha", "Cваркове", "Garnishevka", "Adamovka Novaya", "Sontsevo", "Postal’", "Чабин", "Zelenkovka", "Nastosovka", "Zhdanov", "Rudoye Selo", "Obirky", "Nyzhni Petrivtsi", "Dobryachin", "Chernyatin-Maly", "Berlebash", "Mali Holoby", "Велика Паладь", "Kazavka", "Bilashov", "Grudek", "Черепин", "Stasya", "Самара", "Slobodka Pol’na", "Mykil’s’ke", "Valkovtsy", "Lisova Losiyevka", "Ілемня", "Слобода-Небилівська", "Klevan’", "Golo-Ravskoye", "Shchurovitse", "Korostovichi", "Solonytsivka"];

        return $cities;
    }

    private function getConnection()
    {
        return pg_connect("host=localhost port=5432 dbname=forecast user=postgres password=postgres");
    }
}
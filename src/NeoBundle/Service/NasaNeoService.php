<?php
namespace NeoBundle\Service;


use GuzzleHttp\Client as HttpClient;


class NasaNeoService
{
    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * NasaNeoService constructor.
     *
     * @param string $apiUrl
     * @param string $apiKey
     */
    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function fetchData(string $startDate, string $endDate)
    {
        $startDate = new \DateTimeImmutable($startDate);
        $endDate = new \DateTimeImmutable($endDate);

        if ($startDate > $endDate) {
            // throw new \Exception(...);
            return [];
        }
        $numDays = $endDate->diff($startDate)->d;

        $client = $this->getHttpClient();

        $response = $client->get($this->apiUrl, ['query' => [
            'api_key' => $this->apiKey,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'detailed' => 'false',
        ]]);

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            // throw new \Exception('....');
            return [];
        }

        $data = json_decode($response->getBody(), true);

        $count = $data['element_count'];

        $objects = [];
        if ($count === 0) {
            return $objects;
        }

        for ($i = $numDays; $i >= 0; --$i) {
            $interval = 'P' . strval($i) . 'D';
            $day = $endDate->sub(new \DateInterval($interval))->format('Y-m-d');

            $rawObjects = $data['near_earth_objects'][$day];

            foreach ($rawObjects as $rawObject) {
                $object = $this->transform($rawObject);
                $object['date'] = $day;
                $objects[] = $object;
            }
        }

        return $objects;
    }

    public function fetchLastThreeDays()
    {
        $endDate = new \DateTimeImmutable('yesterday');
        $startDate = $endDate->sub(new \DateInterval('P2D')); // yesterday + 2 days before yesterday

        return $this->fetchData($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
    }

    protected function transform(array $rawObject)
    {
        $object = [];
        // @todo add checking keys
        $object['reference'] = intval($rawObject['neo_reference_id']);
        $object['name'] = $rawObject['name'];
        $object['speed'] = $rawObject['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'];
        $object['is_hazardous'] = $rawObject['is_potentially_hazardous_asteroid'];

        return $object;
    }

    protected function getHttpClient()
    {
        if ($this->client instanceof HttpClient) {
            return $this->client;
        }
        $this->client = new HttpClient();
        return $this->client;
    }

    public function setHttpClient(HttpClient $client)
    {
        $this->client = $client;
    }
}

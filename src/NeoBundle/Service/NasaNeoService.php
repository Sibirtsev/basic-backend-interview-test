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
     * @param HttpClient $client
     * @param string     $apiUrl
     * @param string     $apiKey
     */
    public function __construct(\GuzzleHttp\Client $client, string $apiUrl, string $apiKey)
    {
        $this->client = $client;
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

        $response = $this->client->get($this->apiUrl, ['query' => [
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
                if (empty($object)) {
                    continue;
                }
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

        if (!array_key_exists('neo_reference_id', $rawObject)) {
            return [];
        }
        $object['reference'] = intval($rawObject['neo_reference_id']);

        if (!array_key_exists('name', $rawObject)) {
            return [];
        }
        $object['name'] = strval($rawObject['name']);

        if (!array_key_exists('close_approach_data', $rawObject)) {
            return [];
        }
        if (!is_array($rawObject['close_approach_data'])) {
            return [];
        }
        $closeApproachData = reset($rawObject['close_approach_data']);
        if (!array_key_exists('relative_velocity', $closeApproachData)
            || !array_key_exists('kilometers_per_hour', $closeApproachData['relative_velocity'])) {
            return [];
        }
        $object['speed'] = floatval($closeApproachData['relative_velocity']['kilometers_per_hour']);

        if (!array_key_exists('is_potentially_hazardous_asteroid', $rawObject)) {
            return [];
        }
        $object['is_hazardous'] = boolval($rawObject['is_potentially_hazardous_asteroid']);

        return $object;
    }
}

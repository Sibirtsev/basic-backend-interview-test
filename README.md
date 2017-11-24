# Basic Backend Developer Interview

## Test tasks:

- [x] Specify a default controller
  - for route `/`
  - with a proper json return `{"hello":"world!"}`
  - **Solution**
    - Code: `\NeoBundle\Controller\DefaultController::indexAction`
    - Test: `\NeoBundle\Tests\Controller\DefaultControllerTest::testIndex`

- [x] Use the api.nasa.gov
  - the API-KEY is `N7LkblDsc5aen05FJqBQ8wU4qSdmsftwJagVK7UD`
  - documentation: https://api.nasa.gov/api.html#neows-feed
  - **Solution**
    - Code: `\NeoBundle\Service\NasaNeoService`
    - Test: `\NeoBundle\Tests\Service\NasaNeoServiceTest`
  
- [x] Write a command
  - to request the data from the last 3 days from nasa api
  - response contains count of Near-Earth Objects (NEOs)
  - persist the values in your DB
  - Define the model as follows:
    - date
    - reference (neo_reference_id)
    - name
    - speed (kilometers_per_hour)
    - is hazardous (is_potentially_hazardous_asteroid)
  - **Solution**
    - Code: `\NeoBundle\Command\NeoFetchDataCommand`

- [x] Create a route `/neo/hazardous`
  - display all DB entries which contain potentially hazardous asteroids
  - format JSON
  - **Solution**
    - Code: `\NeoBundle\Controller\DefaultController::hazardousAction`
    - Test: `\NeoBundle\Tests\Controller\DefaultControllerTest::testHazardous`

- [x] Create a route `/neo/fastest?hazardous=(true|false)`
  - analyze all data
  - calculate and return the model of the fastest asteroid
  - with a hazardous parameter, where `true` means `is hazardous`
  - default hazardous value is `false`
  - format JSON
  - **Solution**
    - Code: `\NeoBundle\Controller\DefaultController::fastestAction`
    - Test: 
        - `\NeoBundle\Tests\Controller\DefaultControllerTest::testFastestNonHazardous`
        - `\NeoBundle\Tests\Controller\DefaultControllerTest::testFastestHazardous`
    

- [x] Create a route `/neo/best-year?hazardous=(true|false)`
  - analyze all data
  - calculate and return a year with most asteroids
  - with a hazardous parameter, where `true` means `is hazardous`
  - default hazardous value is `false`
  - format JSON
  - **Solution**
    - Code: `\NeoBundle\Controller\DefaultController::bestYearAction`
    - Test:
        - `\NeoBundle\Tests\Controller\DefaultControllerTest::testBestYearNonHazardous`
        - `\NeoBundle\Tests\Controller\DefaultControllerTest::testBestYearHazardous`

- [x] Create a route `/neo/best-month?hazardous=(true|false)`
  - analyze all data
  - calculate and return a month with most asteroids (not a month in a year)
  - with a hazardous parameter, where `true` means `is hazardous`
  - default hazardous value is `false`
  - format JSON
  - **Solution**
    - Code: `\NeoBundle\Controller\DefaultController::bestMonthAction`
    - Test:
        - `\NeoBundle\Tests\Controller\DefaultControllerTest::testBestMonthNonHazardous`
        - `\NeoBundle\Tests\Controller\DefaultControllerTest::testBestMonthHazardous`
   
**Test's fixtures** `\NeoBundle\DataFixtures\MongoDB\Fixtures`
   
## Requirements

- PHP 7+
- Composer
- MongoDB php extension for php 7
- MongoDB

## Third-Party Bundles

- DoctrineMongoDBBundle
- FOSRestBundle
- JMSSerializerBundle
- DoctrineFixturesBundle

## Third-Party Libraries

- guzzlehttp/guzzle
- alcaeus/mongo-php-adapter

## ToDo

- [ ] Tests for NeoFetchDataCommand
- [ ] Pre-fetch data
- [ ] Volume for MongoDB
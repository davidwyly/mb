<?php

use PHPUnit\Framework\TestCase;
use Davidwyly\Mb\Mock\MockRequest;
use Davidwyly\Mb\Model\Patient;
use Davidwyly\Mb\Http\Controller\PatientController;


class PatientTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testJson()
    {
        $data_fixture = file_get_contents(__DIR__ . '/fixtures/patient2.json');
        $json         = json_decode($data_fixture);
        $mock_request = new MockRequest('post', 'application/json', '/patient');
        $parsed_data  = $this->callPrivateMethod(new PatientController($mock_request), 'parseJson', [$json]);
        $this->assertEquals([
            'first_name'    => 'Jane',
            'last_name'     => 'Doe',
            'external_id'   => '478048293',
            'date_of_birth' => '1946-12-19',
        ], $parsed_data);
        $passed_validation = (new Patient())->create($parsed_data);
        $this->assertTrue($passed_validation);
    }

    /**
     * @throws Exception
     */
    public function testXml()
    {
        $data_fixture = file_get_contents(__DIR__ . '/fixtures/patient1.xml');
        $xml          = new \SimpleXMLElement($data_fixture);
        $mock_request = new MockRequest('post', 'application/xml', '/patient');
        $parsed_data  = $this->callPrivateMethod(new PatientController($mock_request), 'parseXml', [$xml]);
        $this->assertEquals([
            'first_name'    => 'Doe',
            'last_name'     => 'John',
            'external_id'   => '123',
            'date_of_birth' => '1980-10-12',
        ], $parsed_data);
        $passed_validation = (new Patient())->create($parsed_data);
        $this->assertTrue($passed_validation);
    }

    /**
     * @param $object
     * @param $method_name
     *
     * @return mixed
     * @throws ReflectionException
     */
    private function callPrivateMethod($object, $method_name, $params)
    {
        $reflection       = new \ReflectionClass($object);
        $reflectionMethod = $reflection->getMethod($method_name);
        $reflectionMethod->setAccessible(true);
        return $reflectionMethod->invokeArgs($object, $params);
    }
}

<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller;

use Davidwyly\Mb\Model\Patient;
use Davidwyly\Mb\Http\Controller\Collect;
use Davidwyly\Mb\Http\Controller\Parse;
use Davidwyly\Mb\Exception\ControllerException;

class PatientController extends Controller implements Parse\Json, Parse\Xml
{
    /**
     * Trait to collect JSON from the controller's request
     *
     * @see collectJson()
     */
    use Collect\Json;

    /**
     * Trait to collect XML from the controller's request
     *
     * @see collectXml()
     */
    use Collect\Xml;

    /**
     * Create controller action
     */
    public function create()
    {
        try {
            $request_data = $this->getRequestData();
            $patient      = new Patient();
            $patient->create($request_data);
            $this->renderSuccess($request_data, self::HTTP_CREATED);
        } catch (\Exception $e) {
            $this->renderFail($e);
        }
    }

    /**
     * @return array
     * @throws ControllerException
     */
    private function getRequestData()
    {
        if ($this->request->isXml()) {
            $xml = $this->collectXml();
            return $this->parseXml($xml);
        }
        if ($this->request->isJson()) {
            $json = $this->collectJson();
            return $this->parseJson($json);
        }

        throw new ControllerException("HTTP Content Type '{$this->request->http_content_type}' is not allowed",
            self::HTTP_CLIENT_ERROR);
    }

    /**
     * Parse\Json interface
     *
     * @param \stdClass $json
     *
     * @return array
     * @throws ControllerException
     */
    public function parseJson(\stdClass $json): array
    {
        $this->validateJson($json);

        preg_match('/(?<=patientid)\d+$/', $json->patientUid, $matches);
        if (!isset($matches[0])) {
            throw new ControllerException("External ID '$json->patientUid' does not match expected pattern",
                self::HTTP_CLIENT_ERROR);
        }
        $external_id   = $matches[0];
        $date_of_birth = (new \DateTime($json->dateOfBirth))->format('Y-m-d');

        return [
            'first_name'    => $json->firstName,
            'last_name'     => $json->lastName,
            'external_id'   => $external_id,
            'date_of_birth' => $date_of_birth,
        ];
    }

    /**
     * Parse\Xml interface
     *
     * @param \SimpleXMLElement $xml
     *
     * @return array
     * @throws ControllerException
     */
    public function parseXml(\SimpleXMLElement $xml): array
    {
        $this->validateXml($xml);
        $date_of_birth = (string)$xml->PatientDemographics->DOB;
        $date_of_birth = (new \DateTime($date_of_birth))->format('Y-m-d');

        return [
            'first_name'    => (string)$xml->PatientDemographics->FirstName,
            'last_name'     => (string)$xml->PatientDemographics->LastName,
            'external_id'   => (string)$xml->UniqueIdentifiers->MasterPatient->MasterPatientID,
            'date_of_birth' => $date_of_birth,
        ];
    }

    /**
     * @param \stdClass $json
     *
     * @throws ControllerException
     */
    private function validateJson(\stdClass $json): void
    {
        $required_fields = [
            'firstName',
            'lastName',
            'patientUid',
            'dateOfBirth',
        ];

        $missing_fields = [];
        foreach ($required_fields as $required_field) {
            if (empty($json->{$required_field})) {
                $missing_fields[] = $required_field;
            }
        }
        if (!empty($missing_fields)) {
            $error_string = implode(", ", $missing_fields);
            throw new ControllerException("Missing required fields: $error_string", self::HTTP_CLIENT_ERROR);
        }
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @throws ControllerException
     */
    private function validateXml(\SimpleXMLElement $xml) {
        if (empty($xml->PatientDemographics->FirstName)
            || empty($xml->PatientDemographics->LastName)
            || empty($xml->UniqueIdentifiers->MasterPatient->MasterPatientID)
            || empty($xml->PatientDemographics->DOB)
        ) {
            throw new ControllerException("Missing required fields, verify contract", self::HTTP_CLIENT_ERROR);
        }
    }
}

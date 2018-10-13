<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller;


use Davidwyly\Mb\Model\Patient;
use Davidwyly\Mb\Http\Controller\Collect;
use Davidwyly\Mb\Http\Controller\Parse;
use Davidwyly\Mb\Exception\ControllerException;

class PatientController extends Controller implements Parse\Json, Parse\Xml
{
    use Collect\Json;
    use Collect\Xml;

    public function create()
    {
        try {
            if ($this->request->isXml()) {
                $xml  = $this->collectXml();
                $data = $this->parseXml($xml);
            } else if ($this->request->isJson()) {
                $json = $this->collectJson();
                $data = $this->parseJson($json);
            } else {
                throw new ControllerException("HTTP Content Type '{$this->request->http_content_type}' is not allowed",
                    self::HTTP_CLIENT_ERROR);
            }

            $patient = new Patient();
            $patient->create($data);
            $this->renderSuccess($data, self::HTTP_CREATED);
        } catch (\Exception $e) {
            $this->renderFail($e);
        }
    }

    public function parseJson(\stdClass $json): array
    {
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

    public function parseXml(\SimpleXMLElement $xml): array
    {
        $date_of_birth = (string)$xml->PatientDemographics->DOB;
        $date_of_birth = (new \DateTime($date_of_birth))->format('Y-m-d');

        return [
            'first_name'    => (string)$xml->PatientDemographics->FirstName,
            'last_name'     => (string)$xml->PatientDemographics->LastName,
            'external_id'   => (string)$xml->UniqueIdentifiers->MasterPatient->MasterPatientID,
            'date_of_birth' => $date_of_birth,
        ];
    }
}

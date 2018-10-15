<?php declare(strict_types=1);

namespace Davidwyly\Mb\Model;

use Davidwyly\Mb\Exception\ModelException;

/**
 * @property  string $first_name
 * @property  string $last_name
 * @property  string $external_id
 * @property  string $date_of_birth
 */
class Patient extends DataModel
{

    /**
     * @param array $data
     *
     * @return bool
     * @throws \Exception
     */
    public function create(array $data): bool
    {
        $this->validateData($data);
        /**
         * TODO: write the data in the database, or pass the data on to another integration to store the data
         *
         * For now we're going to just assume that this operation is always successful as long as it validates
         */
        return true;
    }

    /**
     * @param $data
     *
     * @throws \Exception
     */
    private function validateData(array $data): void
    {
        $this->validateRequiredKeys([
            'first_name',
            'last_name',
            'external_id',
            'date_of_birth',
        ], $data);
        if (!$this->isValidExternalId($data['external_id'])) {
            throw new ModelException("External ID '{$data['external_id']}' is invalid");
        }

        if (!$this->isValidDateOfBirth($data['date_of_birth'])) {
            throw new ModelException("Date of Birth '{$data['date_of_birth']}' is invalid");
        }
    }

    /**
     * @param array $required_keys
     * @param array $data
     *
     * @throws ModelException
     */
    private function validateRequiredKeys(array $required_keys, array $data): void
    {
        foreach ($required_keys as $required_key) {
            if (!array_key_exists($required_key, $data)) {
                throw new ModelException("Required key '$required_key' is missing from parsed data");
            }
            if (empty($data[$required_key])) {
                throw new ModelException("Key '$required_key' cannot be empty");
            }
        }
    }

    /**
     * @param $external_id
     *
     * @return bool
     */
    private function isValidExternalId($external_id)
    {
        if (is_int($external_id) && $external_id > 0) {
            return true;
        }
        if (is_string($external_id)
            && is_numeric($external_id)
            && ctype_digit($external_id)
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param $date_of_birth
     *
     * @return bool
     */
    private function isValidDateOfBirth($date_of_birth)
    {
        $date_time = new \DateTime($date_of_birth);
        if (is_null($date_time)) {
            return false;
        }
        if ($date_time > new \DateTime('now')) {
            return false;
        }
        return true;
    }
}

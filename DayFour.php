<?php

$passports = new DayFour();
echo $passports->checkForValidPassports();

class DayFour
{
    const MAX_KEYS = 8;
    const MIN_KEYS = 7;
    const OPTIONAL_KEY = 'cid';

    /**
     * Gets passport data
     * @return string
     */
    public function getRawData(): string
    {
        $rawData = file_get_contents('data.txt');
        return $rawData;
    }

    /**
     * @return int
     */
    public function checkForValidPassports(): int
    {
        $rawData = $this->getRawData();
        $passports = $this->getPassports($rawData);
        foreach($passports as $key => $val) {
            $passports[$key] = $this->splitStringToArray($val);
        }

        $validPassportCount = 0;
        // Determine passport validity
        foreach($passports as $passport) {
            // count number of keys for this passport
            $numKeys = count($passport);
            if (Testing::MAX_KEYS == $numKeys) {
                // correct number of keys present, count as valid passport
                $validPassportCount++;
            } elseif (Testing::MIN_KEYS == $numKeys && !array_key_exists(Testing::OPTIONAL_KEY, $passport)) {
                // only missing optional key, count as valid passport
                $validPassportCount++;
            }
        }
        return $validPassportCount;
    }

    /**
     * Splits string of key/value pairs into array
     * @param string $rawData
     * @return array
     */
    public function splitStringToArray(string $rawData): array
    {
        parse_str($rawData, $output);
        return $output;
    }

    public function getPassports(string $rawData)
    {
        $passports = explode("\n\n", $rawData);
        // Remove new lines, spaces, and convert key/value pair separators
        foreach ($passports as $key => $val) {
            $val = trim(str_replace(["\r\n", "\r", "\n", ' '], '&', $val));
            $val = str_replace(':', '=', $val);
            $passports[$key] = $val;
        }
        return $passports;
    }
}

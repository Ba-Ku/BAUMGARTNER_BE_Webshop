<?php


abstract class InputValidation
{
    public function __construct()
    {

    }

    private function streamlineString($inputString)
    {
        $streamlinedString = str_ireplace(" ", "", $inputString);
        $trimmedString = trim($streamlinedString);
        $upperString = strtoupper($trimmedString);
        return $upperString;
    }

    private function sanitizeString($inputString)
    {
        $xssSanitizedString = htmlspecialchars($inputString);
        $sqlInjectionSanitizedString = htmlentities($xssSanitizedString, ENT_QUOTES, "UTF-8");
        return $sqlInjectionSanitizedString;
    }

    public function validateString($inputString)
    {
        $streamlinedString = $this->streamlineString($inputString);
        $validatedString = $this->sanitizeString($streamlinedString);
        return $validatedString;
    }
}
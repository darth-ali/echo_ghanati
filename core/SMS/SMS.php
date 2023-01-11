<?php

namespace SMS;

use SoapClient;
use UserManagement\User;

class SMS
{

    /**
     * @param User $receiver
     * @return int
     */
    public static function SendWelcomeMessage(User $receiver): int
    {
        return self::SendByPattern($receiver, '5oazj4n02u20qpn', ['name' => $receiver->getFirstName()]);
    }

    /**
     * @param User $receiver
     * @param string $verificationCode
     * @return int :: message tracking code
     */
    public static function SendVerificationCode(User $receiver, string $verificationCode): int
    {
        return self::SendByPattern($receiver, 'eivaknyxkdkvvr5', ['verification-code' => $verificationCode]);
    }

    /**
     * @param User $receiver
     * @param string $patternCode
     * @param array $data
     * @return int :: message tracking code
     */
    private static function SendByPattern(User $receiver, string $patternCode, array $data): int
    {
        $client = new SoapClient("http://188.0.240.110/class/sms/wsdlservice/server.php?wsdl");
        $user = "09141492090";
        $pass = "o4413465298";
        $fromNum = "3000505";
        $toNum[] = $receiver->getMobileNumber('national');
        $pattern_code = $patternCode;
        $input_data = $data;
        return $client->sendPatternSms($fromNum, $toNum, $user, $pass, $pattern_code, $input_data);
    }
}
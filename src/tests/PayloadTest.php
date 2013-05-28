<?php
/**
 * Name: Matt Hooge
 * Company: Urban Airship
 * Date: 5/13/13
 * Time: 4:11 PM
 */

require_once __DIR__ . "/../../vendor/autoload.php";

use UrbanAirship\Push\Payload\IosMessagePayload;
use UrbanAirship\Push\Payload\IosRegistrationPayload;
use UrbanAirship\Push\Payload\AndroidMessagePayload;

class TestPayloads extends PHPUnit_Framework_TestCase
{

    protected $key;
    protected $secret;
    protected $token;
    protected $payload;

    protected function setUp()
    {
        $this->key = "key";
        $this->secret = "secret";
        $this->token = "token";
        $this->payload = array("payload" => "stuff");
    }

    public function testIosAps()
    {
        $alert = "alert";
        $badge = 42;
        $sound = "sound";
        $extras = array("extra" => "more things");
        $aps = IosMessagePayload::payload()
            ->setAlert($alert)
            ->setBadge($badge)
            ->setSound($sound)
            ->setExtras($extras);

        $json = json_encode($aps, JSON_PRETTY_PRINT);
        $jsonObject = json_decode($json);
        $this->assertTrue($jsonObject->{IosMessagePayload::APS_ALERT_KEY} === $alert);
        $this->assertTrue($jsonObject->{IosMessagePayload::APS_BADGE_KEY} === $badge);
        $this->assertTrue($jsonObject->{IosMessagePayload::APS_SOUND_KEY} === $sound);
        $this->assertTrue($jsonObject->extra === "more things");
    }

    public function testAndroidMessage()
    {
        $alert = "alert";
        $extras = array("extra" => "more things");
        $payload = AndroidMessagePayload::payload()
            ->setAlert($alert)
            ->setExtra($extras);
        $json = json_encode($payload);
        $jsonObject = json_decode($json);
        $this->assertTrue($jsonObject->{AndroidMessagePayload::ANDROID_ALERT_KEY} === $alert);
        $this->assertTrue($jsonObject->extra === "more things");
    }

    public function testIosRegistration()
    {
        $alias = "alias";
        $badge = 42;
        $timeZone = "pancake_time";
        $quietTimeStart = "7:00";
        $quietTimeEnd = "22:00";
        $tags = array("cats", "hats");

        $payload = new IosRegistrationPayload();
        $payload->setAlias($alias)
            ->setBadge($badge)
            ->setQuietTime($quietTimeStart, $quietTimeEnd)
            ->setTimeZone($timeZone)
            ->setTags($tags);

        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $jsonObject = json_decode($json);

        $this->assertTrue(
            $jsonObject->{IosRegistrationPayload::REGISTRATION_PAYLOAD_ALIAS_KEY}
                === $alias, "Bad registration alias");
        $this->assertTrue(
            $jsonObject->{IosRegistrationPayload::REGISTRATION_PAYLOAD_TAGS_KEY}
                === $tags, "Bad registration tags");
        $this->assertTrue(
            $jsonObject->{IosRegistrationPayload::REGISTRATION_PAYLOAD_TIME_ZONE_KEY}
                === $timeZone, "Bad registration time zone");
        $this->assertTrue(
            $jsonObject->{IosRegistrationPayload::REGISTRATION_PAYLOAD_BADGE_KEY}
                === $badge, "Bad registration badge");

        $this->assertTrue(
            $jsonObject->{IosRegistrationPayload::REGISTRATION_PAYLOAD_QUIET_TIME_KEY}
                ->{IosRegistrationPayload::REGISTRATION_PAYLOAD_QUIET_TIME_START_KEY}
                === $quietTimeStart, "Bad quiet time start");
        $this->assertTrue(
            $jsonObject->{IosRegistrationPayload::REGISTRATION_PAYLOAD_QUIET_TIME_KEY}
            ->{IosRegistrationPayload::REGISTRATION_PAYLOAD_QUIET_TIME_END_KEY}
            === $quietTimeEnd, "Bad quiet time end");
        

    }


}
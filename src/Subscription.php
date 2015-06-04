<?php
namespace ZSquare;

/**
 * Class Subscription
 *
 * @package ZSquare
 * @author Nikolay Bondarenko <misterionkell@gmail.com>
 * @version 1.0
 * @license The MIT License (MIT)
 */
class Subscription implements \JsonSerializable
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $activationCode;

    /**
     * @var int
     */
    private $licenseCount;

    /**
     * @var \DateTimeImmutable
     */
    private $statusChangeTime;

    /**
     * @var \DateTimeImmutable
     */
    private $startTime;

    /**
     * @var \DateTimeImmutable
     */
    private $endTime;

    /**
     * @var string
     */
    private $productId;

    public function __construct(
        $status,
        $activationCode,
        $licenseCount,
        \DateTimeImmutable $statusChangeTime,
        \DateTimeImmutable $startTime,
        \DateTimeImmutable $endTime,
        $productId
    ) {
        $this->status = $status;
        $this->activationCode = $activationCode;
        $this->licenseCount = $licenseCount;
        $this->statusChangeTime = $statusChangeTime;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->productId = $productId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * @return mixed
     */
    public function getLicenseCount()
    {
        return $this->licenseCount;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStatusChangeTime()
    {
        return $this->statusChangeTime;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return [
            'status' => $this->status,
            'activationCode' => $this->activationCode,
            'licenseCount' => $this->licenseCount,
            'statusChangeTime' => $this->statusChangeTime,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'productId' => $this->productId,
        ];
    }
}
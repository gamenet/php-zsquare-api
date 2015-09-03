<?php
namespace ZSquare;

/**
 * Class Api
 *
 * @package ZSquare
 * @author Nikolay Bondarenko <misterionkell@gmail.com>
 * @version 1.0
 * @license The MIT License (MIT)
 */
class Api
{
    /**
     * SubscriberId specified in the activate order was previously activated.
     */
    const ERROR_SUBSCRIPTION_EXISTS = 1;

    /**
     * The Subscription with the specified SubscriberId is not yet activated
     */
    const ERROR_SUBSCRIPTION_NOT_ACTIVATED = 2;

    /**
     * Incorrect subscription end time: the end time specified in the renew order is less than the previous
     * subscription end time.
     */
    const ERROR_INCORRECT_SUBSCRIPTION_END_TIME = 3;

    /**
     * The subscription with the specified SubscriberId is already hard cancelled.
     */
    const ERROR_ALREADY_HARD_CANCELED = 4;

    /**
     * The SubscriberId does not exist in KSS.
     */
    const ERROR_SUBSCRIBER_ID_NOT_EXISTS = 5;

    /**
     * Duplicated order.
     */
    const ERROR_DUPLICATED_ORDER = 6;

    /**
     * Activation code pool for the service provider is empty.
     */
    const ERROR_ACTIVATION_CODE_POOL_IS_EMPTY = 8;

    /**
     * Unknown product in the activate order.
     */
    const ERROR_UNKNOWN_PRODUCT = 9;

    /**
     * Unknown api error.
     */
    const ERROR_UNKNOWN_ERROR = 99;

    /**
     * The difference between subscription end time and the subscription start time specified in the activate order
     * is less than 28 days. Subscription period must not be less than 28 days.
     */
    const ERROR_INCORRECT_TIME_DIFFERENCE = 101;

    /**
     * Subscription start time specified in the activate order is greater than the current  time or the request
     * timestamp. The subscription cannot start in future.
     */
    const ERROR_INCORRECT_ACTIVATE_TIME = 103;

    /**
     * The subscription with specified SubscriberId is soft-cancelled and will not be activated.
     */
    const ERROR_SUBSCRIPTION_SOFT_CANCELED = 105;

    /**
     * The renew request received for open-ended subscription. Open-ended subscriptions cannot be renewed.
     */
    const ERROR_RENEW_OPEN_ENDED_SUBSCRIPTION = 202;

    /**
     * The subscription with specified SubscriberId is paused and will not be renewed. Use the resume order to
     * resume the subscription.
     */
    const ERROR_RENEW_PAUSED_SUBSCRIPTION = 204;

    /**
     * The subscription with the SubscriberId specified cannot be soft-cancelled: the subscription is paused.
     */
    const ERROR_SOFT_CANCEL_PAUSED_SUBSCRIPTION = 303;

    /**
     * The subscription with the SubscriberId cannot be soft-cancelled or hard-cancelled (new subscription end time
     * should not be earlier than 14 days before the current date).
     */
    const ERROR_CANCEL_TOO_LATE = 304;

    /**
     * The subscription with the SubscriberId specified cannot be paused (only openended subscriptions can be paused)
     */
    const ERROR_PAUSE_NOT_OPEN_ENDED_SUBSCRIPTION = 401;

    /**
     * The subscription with the SubscriberId specified in the pause order is already paused.
     */
    const ERROR_ALREADY_PAUSED = 402;

    /**
     * The subscription with the SubscriberId cannot be paused (pause time should not be earlier than 14 days
     * before the current date)
     */
    const ERROR_PAUSE_TOO_LATE = 304;

    /**
     * The subscription with the SubscriberId specified in the resume order is not paused (is active).
     */
    const ERROR_RESUME_ACTIVE_SUBSCRIPTION = 501;

    /**
     * The order is not allowed for the service provider.
     */
    const ERROR_COMMAND_DISALLOWED = 601;

    /**
     * Incorrect order attribute. The date could not be earlier then 2008-01-01. The date could not be earlier then
     * the subscription start date.
     */
    const ERROR_WRONG_REQUEST = 602;

    const BASE_URL = 'https://api1.zsquare.ru/subscription/';

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var bool
     */
    private $ignoreSsl = true;

    public function __construct($login, $password)
    {
        $this->authToken = "$login:$password";
    }

    /**
     * The zsquare api uses invalid ssl certificate. You may add it as writen in http://curl.haxx.se/docs/sslcerts.html
     * or just disable checking.
     *
     * @param boolean $ignoreSsl
     *
     * @return $this
     */
    public function setIgnoreSsl($ignoreSsl)
    {
        $this->ignoreSsl = $ignoreSsl;

        return $this;
    }

    /**
     * Activate new subscription.
     *
     * @param string $subscriberId 1-50 alphanumeric sequence The unique identifier of subscriber system provider.
     *
     * @param string $subscriptionId 1-50 alphanumeric sequence The unique identifier of the subscription system service
     *                          provider. Not a required field.
     * @param string $productId The unique identifier of the application for which is activated by subscription.
     *                          application Type It can not be changed during maintenance subscription.
     * @param int $licenseCount The number of licenses for subscription. Number License can not be changed
     *                          during subscription service.
     * @param \DateTimeImmutable $startTime Date / time of the subscription (first day subscription included).
     * @param \DateTimeImmutable $endTime Date / time of the subscription (last day subscription included). If the
     *                                    subscription activated for an unlimited period, the value of the field is not
     *                                    set.
     *
     * @return string Activation key.
     */
    public function activate(
        $subscriberId,
        $subscriptionId,
        $productId,
        $licenseCount,
        \DateTimeImmutable $startTime,
        \DateTimeImmutable $endTime = null
    ) {
        return $this->request(
            'activate',
            [
                'SubscriberId' => $subscriberId,
                'SubscriptionId' => $subscriptionId,
                'ProductId' => $productId,
                'LicenseCount' => $licenseCount,
                'StartTime' => $startTime->format('d/m/Y'),
                'EndTime' => ($endTime === null) ? 'indefinite' : $endTime->format('d/m/Y')
            ]
        );
    }

    /**
     * @param string $subscriberId
     * @param string $subscriptionId
     * @param \DateTimeImmutable $endTime Date / time of the subscription (last day subscription included). If the
     *                                    subscription activated for an unlimited period, the value of the field is not
     *                                    set.
     *
     * @see Api::activate()
     * @return mixed
     */
    public function renew($subscriberId, $subscriptionId, \DateTimeImmutable $endTime = null)
    {
        $args = [
            'SubscriberId' => $subscriberId,
            'SubscriptionId' => $subscriptionId,
            'EndTime' => $endTime === null ? 'indefinite' : $endTime->format('d/m/Y'),
        ];

        return $this->request('renew', $args);
    }

    /**
     * @param string $subscriberId
     * @param string $subscriptionId
     * @param \DateTimeImmutable $endTime Last date / time of the subscription (last day subscription included).
     *
     * @see Api::activate()
     * @return mixed
     */
    public function softCancel($subscriberId, $subscriptionId = '', \DateTimeImmutable $endTime = null)
    {
        if ($endTime === null)  {
            $endTime = new \DateTimeImmutable();
        }

        $args = [
            'SubscriberId' => $subscriberId,
            'SubscriptionId' => $subscriptionId,
            'EndTime' => $endTime->format('d/m/Y'),
        ];

        return $this->request('softcancel', $args);
    }

    /**
     * @param string $subscriberId
     * @param string $subscriptionId
     * @param \DateTimeImmutable $endTime Last date / time of the subscription (last day subscription included).
     *
     * @see Api::activate()
     * @return mixed
     */
    public function hardCancel($subscriberId, $subscriptionId = '', \DateTimeImmutable $endTime = null)
    {
        if ($endTime === null)  {
            $endTime = new \DateTimeImmutable();
        }

        $args = [
            'SubscriberId' => $subscriberId,
            'SubscriptionId' => $subscriptionId,
            'EndTime' => $endTime->format('d/m/Y'),
        ];

        return $this->request('hardcancel', $args);
    }

    /**
     * @param string $subscriberId
     * @param string $subscriptionId
     * @param \DateTimeImmutable $pauseTime Pause date / time of the subscription (last day subscription included).
     *
     * @see Api::activate()
     * @return mixed
     */
    public function pause($subscriberId, $subscriptionId = '', \DateTimeImmutable $pauseTime = null)
    {
        if ($pauseTime === null)  {
            $pauseTime = new \DateTimeImmutable();
        }

        $args = [
            'SubscriberId' => $subscriberId,
            'SubscriptionId' => $subscriptionId,
            'EndTime' => $pauseTime->format('d/m/Y'),
        ];

        return $this->request('pause', $args);
    }

    /**
     * @param string $subscriberId 1-50 alphanumeric sequence The unique identifier of subscriber system provider.
     * @param string $subscriptionId
     *
     * @see Api::activate()
     * @return mixed
     */
    public function resume($subscriberId, $subscriptionId = '')
    {
        $args = [
            'SubscriberId' => $subscriberId,
            'SubscriptionId' => $subscriptionId,
        ];

        return $this->request('resume', $args);
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        //INFO The GetProducts method do not work with POST. Also, using GET we should not use `Accept` header as in
        // post case.
        $products = $this->request('getproducts', [], true);

        return array_map(
            function ($e) {
                return new Product($e['value'], $e['label']);
            },
            $products
        );
    }

    /**
     * @param string $subscriberId
     * @param string $subscriptionId
     *
     * @see Api::activate()
     * @return Subscription
     */
    public function getInfo($subscriberId, $subscriptionId = '')
    {
        $args = [
            'SubscriberId' => $subscriberId,
            'SubscriptionId' => $subscriptionId,
        ];

        $response = $this->request('getinfo', $args);
        if (!isset($response['Message']) || !isset($response['Message']['Subscription'])) {
            throw new \RuntimeException('GetInfo response has no Message/Subscription data');
        }

        $response = $response['Message']['Subscription'];

        return new Subscription(
            $response['Status'],
            $response['ActivationCode'],
            $response['LicenseCount'],
            new \DateTimeImmutable($response['StatusChangeTime']),
            new \DateTimeImmutable($response['StartTime']),
            $response['EndTime'] !== 'indefinite' ? new \DateTimeImmutable($response['EndTime']) : null,
            $response['ProductId']
        );
    }

    protected function request($method, $data = [], $forceGet = false)
    {
        $headers = [];

        $url =  self::BASE_URL . $method;
        if ($forceGet && !empty($data)) {
            $url .= '?' . http_build_query($data);
        };

        $curl = curl_init($url);
        if (!$forceGet) {
            curl_setopt($curl, CURLOPT_POST, true);
            if (!empty($data)) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($data)));
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERPWD, $this->authToken);
        curl_setopt($curl, CURLOPT_HEADERFUNCTION , function($ch, $headerLine) use (&$headers) {
            $headerValue = explode(': ', $headerLine);
            if (count($headerValue) === 2) {
                $headers[$headerValue[0]] = $headerValue[1];
            }

            return strlen($headerLine);
        });

        if ($this->ignoreSsl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        }

        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new Exception($error, self::ERROR_UNKNOWN_ERROR);
        }
        curl_close($curl);
        if ($code !== 200) {
            throw new Exception($headers['ErrorMessage'], (int)$headers['ErrorCode']);
        }

        $response = json_decode($data, true);
        if (null === $response) {
            throw new Exception($data, self::ERROR_UNKNOWN_ERROR);
        }

        return $response;
    }
}
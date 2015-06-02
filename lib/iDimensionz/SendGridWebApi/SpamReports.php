<?php

namespace iDimensionz\SendGridWebApi;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SpamReports
 * @package iDimensionz\SendGridWebApi
 * @see https://sendgrid.com/docs/API_Reference/Web_API/spam_reports.html
 */
class SpamReports extends SendGridWebApiAbstract
{
    const MODULE_SPAM_REPORTS = 'spamreports';
    /**
     * @var int
     */
    private $date;
    /**
     * @var int
     */
    private $days;
    /**
     * @var string
     */
    private $startDate;
    /**
     * @var string
     */
    private $endDate;
    /**
     * @var int
     */
    private $limit;
    /**
     * @var int
     */
    private $offset;
    /**
     * @var string
     * @Assert\Email
     */
    private $email;

    public function __construct($apiUserName, $apiPassword, $apiBaseUrl = self::SENDGRID_PRODUCTION_WEB_API_BASE_URL)
    {
        parent::__construct($apiUserName, $apiPassword, $apiBaseUrl);
        $this->setModule(self::MODULE_SPAM_REPORTS);
    }

    public function get($date=1, $days=null, $startDate=null, $endDate=null, $limit=null, $offset=null, $email=null)
    {
        $this->setAction('get');
        $this->setDate($date);
        $this->setDays($days);
        $this->setStartDate($startDate);
        $this->setEndDate($endDate);
        $this->setLimit($limit);
        $this->setOffset($offset);
        $this->setEmail($email);
        $response = $this->callApiFunction();

        return $response;
    }

    public function count()
    {
        $this->setAction('count');
        $response = $this->callApiFunction();

        return $response;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate($date)
    {
        if (is_null($date) || 1 === (int) $date) {
            $this->date = $date;
            $this->addParameter('date', $date);
        } else {
            throw new InvalidArgumentException('Date must be null or 1');
        }
    }

    /**
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param int $days
     */
    public function setDays($days)
    {
        if (is_null($days) || (is_int($days) && $days > 0)) {
            $this->days = $days;
            $this->addParameter('days', $days);
        } else {
            throw new InvalidArgumentException('Days must be null or a positive integer');
        }
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param string $startDateString
     */
    public function setStartDate($startDateString)
    {
        $startDate = new \DateTime($startDateString);
        if (is_null($startDateString) || ($startDate->format(self::VALID_DATE_FORMAT) == $startDateString)) {
            $this->startDate = $startDateString;
            $this->addParameter('start_date', $startDateString);
        } else {
            throw new InvalidArgumentException('Start Date must be null or in the format ' . self::VALID_DATE_FORMAT);
        }
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param string $endDateString
     */
    public function setEndDate($endDateString)
    {
        $endDate = new \DateTime($endDateString);
        if (is_null($endDateString) || ($endDate->format(self::VALID_DATE_FORMAT) == $endDateString)) {
            $this->endDate = $endDateString;
            $this->addParameter('end_date', $endDateString);
        } else {
            throw new InvalidArgumentException('End Date must be null or in the format ' . self::VALID_DATE_FORMAT);
        }
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        if (is_null($limit) || (is_int($limit) && $limit > 0)) {
            $this->limit = $limit;
            $this->addParameter('limit', $limit);
        } else {
            throw new InvalidArgumentException('Limit must be a positive integer');
        }
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        if (is_null($offset) || (is_int($offset) && $offset > 0)) {
            $this->offset = $offset;
            $this->addParameter('offset', $offset);
        } else {
            throw new InvalidArgumentException($offset);
        }
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
//        if (is_null($email) || )
        $this->email = $email;
    }
}
 
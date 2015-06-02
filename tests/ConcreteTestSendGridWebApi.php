<?php
namespace Tests\iDimensionz\SendGridWebApi;

use iDimensionz\SendGridWebApi\SendGridWebApiAbstract;

class ConcreteTestSendGridWebApi extends SendGridWebApiAbstract
{
    public function addParameter($key, $value)
    {
        parent::addParameter($key, $value);
    }
}

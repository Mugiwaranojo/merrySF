<?php
namespace Merry\Bundle\CoreBundle\Constants;

class HttpStatusCodes 
{
    /** @var int*/
    const SUCCESS = 200;

    /** @var int*/
    const CREATED = 201;
    
    /** @var int*/
    const BAD_REQUEST = 400;

    /** @var int*/
    const UNAUTHORIZED = 401;

    /** @var int*/
    const FORBIDDEN = 403;

    /** @var int*/
    const NOT_FOUND = 404;

    /** @var int*/
    const METHOD_NOT_ALLOWED = 405;

    /** @var int*/
    const NOT_ACCEPTABLE = 406;

    /** @var int*/
    const CONFLICT = 409;

    /** @var int*/
    const INTERNAL_SERVER_ERROR = 500;

    /** @var int*/
    const SERVICE_UNAVAILABLE = 503;
}

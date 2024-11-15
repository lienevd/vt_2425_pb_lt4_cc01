<?php

namespace Src\Enums;

enum ResponseTypeEnum: string
{
   case OK = "200";
   case NOT_FOUND = "404";
   case FORBIDDEN = "403";
   case UNAUTHORIZED = "401";
   case BAD_REQUEST = "400";
}
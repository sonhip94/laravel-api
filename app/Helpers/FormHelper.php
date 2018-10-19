<?php

function has_errors($fields, $errors, $errorCssClass = 'has-error', $noErrorCssClass = '')
{
    return $errors->has($fields) ? $errorCssClass : $noErrorCssClass;
}

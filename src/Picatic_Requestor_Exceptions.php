<?php

/**
 * The request was malformed
 */
class Picatic_Requestor_BadRequest_Exception extends Exception {

}

/**
 * Request was for a resource that does not exist
 */
class Picatic_Requestor_NotFound_Exception extends Exception {

}

/**
 * Request caused an unhandled error
 */
class Picatic_Requestor_Internal_Error_Exception extends Exception {

}

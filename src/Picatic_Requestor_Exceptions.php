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

/**
 * Request caused unauthorized error
 */
class Picatic_Requestor_Unauthorized_Exception extends Exception {

}

/**
 * Request caused Forbidden error
 */
class Picatic_Requestor_Forbidden_Exception extends Exception {

}

/**
 * Request caused Server error
 */
class Picatic_Requestor_Server_Exception extends Exception {

}

/**
 * Request had validation errors
 */
class Picatic_Requestor_Validation_Exception extends Exception {

}



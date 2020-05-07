<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/datastore/v1/query.proto

namespace Google\Cloud\Datastore\V1\EntityResult;

use UnexpectedValueException;

/**
 * Specifies what data the 'entity' field contains.
 * A `ResultType` is either implied (for example, in `LookupResponse.missing`
 * from `datastore.proto`, it is always `KEY_ONLY`) or specified by context
 * (for example, in message `QueryResultBatch`, field `entity_result_type`
 * specifies a `ResultType` for all the values in field `entity_results`).
 *
 * Protobuf type <code>google.datastore.v1.EntityResult.ResultType</code>
 */
class ResultType
{
    /**
     * Unspecified. This value is never used.
     *
     * Generated from protobuf enum <code>RESULT_TYPE_UNSPECIFIED = 0;</code>
     */
    const RESULT_TYPE_UNSPECIFIED = 0;
    /**
     * The key and properties.
     *
     * Generated from protobuf enum <code>FULL = 1;</code>
     */
    const FULL = 1;
    /**
     * A projected subset of properties. The entity may have no key.
     *
     * Generated from protobuf enum <code>PROJECTION = 2;</code>
     */
    const PROJECTION = 2;
    /**
     * Only the key.
     *
     * Generated from protobuf enum <code>KEY_ONLY = 3;</code>
     */
    const KEY_ONLY = 3;

    private static $valueToName = [
        self::RESULT_TYPE_UNSPECIFIED => 'RESULT_TYPE_UNSPECIFIED',
        self::FULL => 'FULL',
        self::PROJECTION => 'PROJECTION',
        self::KEY_ONLY => 'KEY_ONLY',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ResultType::class, \Google\Cloud\Datastore\V1\EntityResult_ResultType::class);

